import { useEffect, useState } from '@wordpress/element'
import create from 'zustand'
import { devtools, persist } from 'zustand/middleware'
import { getTourData, saveTourData } from '../api/Data'

const state = (set, get) => ({
    currentTour: null,
    currentStep: undefined,
    progress: [],
    startTour: async (tourData) => {
        const { trackTourProgress, updateProgress, getStepData } = get()
        // See if the tour already was opened
        await tourData?.onStart?.(tourData)
        await getStepData(0, tourData)?.events?.beforeAttach?.(tourData)
        set({ currentTour: tourData, currentStep: 0 })
        // Increment the opened count
        const tour = trackTourProgress(tourData.id)
        updateProgress(tour.id, {
            openedCount: tour.openedCount + 1,
            lastAction: 'started',
        })
    },
    completeCurrentTour: async () => {
        const { currentTour, finishedTour, findTourProgress, updateProgress } =
            get()
        if (!currentTour) return
        const tour = findTourProgress(currentTour.id)
        // if already completed, don't update the completedAt
        if (!finishedTour(tour.id)) {
            updateProgress(tour.id, {
                completedAt: new Date().toISOString(),
                lastAction: 'completed',
            })
        }
        // Track how many times it was completed
        updateProgress(tour.id, {
            completedCount: tour.completedCount + 1,
            lastAction: 'completed',
        })
        await currentTour?.onDetach?.()
        await currentTour?.onFinish?.()
        set({ currentTour: null, currentStep: undefined })
    },
    closeForRedirect() {
        const { currentTour, findTourProgress, updateProgress } = get()
        if (!currentTour) return
        const tour = findTourProgress(currentTour.id)
        // update last action
        updateProgress(tour?.id ?? currentTour, {
            lastAction: 'redirected',
        })
        set({ currentTour: null, currentStep: undefined })
    },
    closeCurrentTourManually: async () => {
        const { currentTour, findTourProgress, updateProgress } = get()
        const tour = findTourProgress(currentTour.id)
        // Track how many times it was closed early
        updateProgress(tour.id, {
            closedManuallyCount: tour.closedManuallyCount + 1,
            lastAction: 'closed-manually',
        })
        await currentTour?.onDetach?.()
        await currentTour?.onFinish?.()
        set({ currentTour: null, currentStep: undefined })
    },
    closeCurrentTourFromError() {
        const { currentTour, findTourProgress, updateProgress } = get()
        if (!currentTour) return console.error('No tour found')
        const tour = findTourProgress(currentTour.id)
        if (!tour) return console.error('No tour found')
        updateProgress(tour.id, {
            errored: true,
            lastAction: 'closed-by-caught-error',
        })
        // Don't call onFinish here, as it was an error?
        set({ currentTour: null, currentStep: undefined })
    },
    findTourProgress(tourId) {
        return get().progress.find((tour) => tour.id === tourId)
    },
    finishedTour(tourId) {
        return get().findTourProgress(tourId)?.completedAt
    },
    wasOpened(tourId) {
        return get().findTourProgress(tourId)?.openedCount > 0
    },
    isSeen(tourId) {
        return get().findTourProgress(tourId)?.firstSeenAt
    },
    trackTourProgress(tourId) {
        const { findTourProgress } = get()
        // If we are already tracking it, return that
        if (findTourProgress(tourId)) {
            return findTourProgress(tourId)
        }
        set((state) => ({
            progress: [
                ...state.progress,
                {
                    id: tourId,
                    firstSeenAt: new Date().toISOString(),
                    updatedAt: new Date().toISOString(),
                    completedAt: null,
                    lastAction: 'init',
                    currentStep: 0,
                    openedCount: 0,
                    closedManuallyCount: 0,
                    completedCount: 0,
                    errored: false,
                },
            ],
        }))
        return findTourProgress(tourId)
    },
    updateProgress(tourId, update) {
        const lastAction = update?.lastAction ?? 'unknown'
        set((state) => {
            const progress = state.progress.map((tour) => {
                if (tour.id === tourId) {
                    return {
                        ...tour,
                        ...update,
                        lastAction,
                        updatedAt: new Date().toISOString(),
                    }
                }
                return tour
            })
            return { progress }
        })
    },
    getStepData(step, tour = get().currentTour) {
        return tour?.steps?.[step] ?? {}
    },
    hasNextStep() {
        if (!get().currentTour) return false
        return get().currentStep < get().currentTour.steps.length - 1
    },
    nextStep: async () => {
        const {
            currentTour,
            currentStep,
            updateProgress,
            hasNextStep,
            closeCurrentTourFromError,
            getStepData,
        } = get()
        if (!hasNextStep()) {
            closeCurrentTourFromError()
            return
        }
        const tour = currentTour
        const next = currentStep + 1
        await getStepData(next)?.events?.beforeAttach?.(tour)
        set(() => ({ currentStep: next }))
        updateProgress(tour.id, {
            currentStep: next,
            lastAction: 'next',
        })
    },
    hasPreviousStep() {
        if (!get().currentTour) return false
        return get().currentStep > 0
    },
    prevStep: async () => {
        const {
            updateProgress,
            hasPreviousStep,
            closeCurrentTourFromError,
            currentTour,
            currentStep,
            getStepData,
        } = get()
        if (!hasPreviousStep()) {
            closeCurrentTourFromError()
            return
        }
        const tour = currentTour
        const prev = currentStep - 1
        await getStepData(prev)?.events?.beforeAttach?.(tour)
        set(() => ({ currentStep: prev }))
        // make events async here?
        updateProgress(tour.id, {
            currentStep: prev,
            lastAction: 'prev',
        })
    },
    goToStep: async (step) => {
        const { currentTour, updateProgress, getStepData } = get()
        const tour = currentTour
        // Check that the step is valid
        if (step < 0 || step > tour.steps.length - 1) return
        updateProgress(tour.id, {
            currentStep: step,
            lastAction: `go-to-step-${step}`,
        })
        await getStepData(step)?.events?.beforeAttach?.(tour)
        set(() => ({ currentStep: step }))
    },
})

const storage = {
    getItem: async () => JSON.stringify(await getTourData()),
    setItem: async (_, value) => await saveTourData(value),
    removeItem: () => undefined,
}

export const useTourStore = create(
    persist(devtools(state, { name: 'Extendify Assist Tour Progress' }), {
        name: 'extendify-assist-tour-progress',
        getStorage: () => storage,
        partialize: (state) => {
            // return without currentTour or currentStep
            // eslint-disable-next-line no-unused-vars
            const { currentTour, currentStep, ...newState } = state
            return newState
        },
    }),
    state,
)

/* Hook useful for when you need to wait on the async state to hydrate */
export const useTourStoreReady = () => {
    const [hydrated, setHydrated] = useState(useTourStore.persist.hasHydrated)
    useEffect(() => {
        const unsubFinishHydration = useTourStore.persist.onFinishHydration(
            () => setHydrated(true),
        )
        return () => {
            unsubFinishHydration()
        }
    }, [])
    return hydrated
}
