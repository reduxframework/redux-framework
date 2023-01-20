import { useEffect, useState } from '@wordpress/element'
import create from 'zustand'
import { devtools, persist } from 'zustand/middleware'
import { getTourData, saveTourData } from '../api/Data'

const state = (set, get) => ({
    currentTour: null,
    currentStep: 0,
    progress: [],
    startTour(tourData) {
        set({ currentTour: tourData })
        // See if the tour already was opened
        const tour = get().trackTourProgress(tourData.id)
        // Increment the opened count
        get().updateProgress(tour.id, {
            openedCount: tour.openedCount + 1,
            lastAction: 'started',
        })
    },
    completeCurrentTour() {
        if (!get().currentTour) return
        const tour = get().findTourProgress(get().currentTour.id)
        // if already completed, dont update the completedAt
        if (!get().isCompleted(tour.id)) {
            get().updateProgress(tour.id, {
                completedAt: new Date().toISOString(),
                lastAction: 'completed',
            })
        }
        // Track how many times it was completed
        get().updateProgress(tour.id, {
            completedCount: tour.completedCount + 1,
            lastAction: 'completed',
        })
        set({ currentTour: null, currentStep: 0 })
    },
    closeForRedirect() {
        if (!get().currentTour) return
        const tour = get().findTourProgress(get().currentTour.id)
        // update last action
        get().updateProgress(tour?.id ?? get().currentTour, {
            lastAction: 'redirected',
        })
        set({ currentTour: null, currentStep: 0 })
    },
    closeCurrentTourManually() {
        const tour = get().findTourProgress(get().currentTour.id)
        // Track how many times it was closed early
        get().updateProgress(tour.id, {
            closedManuallyCount: tour.closedManuallyCount + 1,
            lastAction: 'closed-manually',
        })
        set({ currentTour: null, currentStep: 0 })
    },
    closeCurrentTourFromError() {
        if (!get().currentTour) return console.error('No tour found')
        const tour = get().findTourProgress(get().currentTour.id)
        if (!tour) return console.error('No tour found')
        get().updateProgress(tour.id, {
            errored: true,
            lastAction: 'closed-by-caught-error',
        })
        set({ currentTour: null, currentStep: 0 })
    },
    findTourProgress(tourId) {
        return get().progress.find((tour) => tour.id === tourId)
    },
    isCompleted(tourId) {
        return get().findTourProgress(tourId)?.completedAt
    },
    isSeen(tourId) {
        return get().findTourProgress(tourId)?.firstSeenAt
    },
    trackTourProgress(tourId) {
        // If we are already tracking it, return that
        if (get().findTourProgress(tourId)) {
            return get().findTourProgress(tourId)
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
        return get().findTourProgress(tourId)
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
    hasNextStep() {
        if (!get().currentTour) return false
        return get().currentStep < get().currentTour.steps.length - 1
    },
    nextStep() {
        if (!get().hasNextStep()) {
            get().closeCurrentTourFromError()
            return
        }
        const tour = get().currentTour
        const next = get().currentStep + 1
        set(() => ({ currentStep: next }))
        get().updateProgress(tour.id, {
            currentStep: next,
            lastAction: 'next',
        })
    },
    hasPreviousStep() {
        if (!get().currentTour) return false
        return get().currentStep > 0
    },
    prevStep() {
        if (!get().hasPreviousStep()) {
            get().closeCurrentTourFromError()
            return
        }
        const tour = get().currentTour
        const prev = get().currentStep - 1
        set(() => ({ currentStep: prev }))
        get().updateProgress(tour.id, {
            currentStep: prev,
            lastAction: 'prev',
        })
    },
    goToStep(step) {
        const tour = get().currentTour
        // Check that the step is valid
        if (step < 0 || step > tour.steps.length - 1) return
        get().updateProgress(tour.id, {
            currentStep: step,
            lastAction: `go-to-step-${step}`,
        })
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
