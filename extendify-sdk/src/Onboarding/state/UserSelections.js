import create from 'zustand'
import { persist, devtools } from 'zustand/middleware'

const initialState = {
    siteType: {},
    siteInformation: {
        title: undefined,
    },
    feedbackMissingSiteType: '',
    feedbackMissingGoal: '',
    siteTypeSearch: [],
    style: null,
    pages: [],
    plugins: [],
    goals: [],
}
const store = (set, get) => ({
    ...initialState,
    setSiteType(siteType) {
        set({ siteType })
    },
    setSiteInformation(name, value) {
        const siteInformation = { ...get().siteInformation, [name]: value }
        set({ siteInformation })
    },
    setFeedbackMissingSiteType(feedback) {
        set({ feedbackMissingSiteType: feedback })
    },
    setFeedbackMissingGoal(feedback) {
        set({ feedbackMissingGoal: feedback })
    },
    has(type, item) {
        if (!item?.id) return false
        return get()[type].some((t) => t.id === item.id)
    },
    add(type, item) {
        if (get().has(type, item)) return
        set({ [type]: [...get()[type], item] })
    },
    remove(type, item) {
        set({ [type]: get()[type].filter((t) => t.id !== item.id) })
    },
    reset(type) {
        set({ [type]: [] })
    },
    toggle(type, item) {
        if (get().has(type, item)) {
            get().remove(type, item)
            return
        }
        get().add(type, item)
    },
    setStyle(style) {
        set({ style })
    },
    canLaunch() {
        // The user can launch if they have a complete selection
        return (
            Object.keys(get()?.siteType ?? {})?.length > 0 &&
            Object.keys(get()?.style ?? {})?.length > 0 &&
            get()?.pages?.length > 0
        )
    },
    resetState() {
        set(initialState)
    },
})

const withDevtools = devtools(store, {
    name: 'Extendify Launch User Selection',
})
const withPersist = persist(withDevtools, {
    name: 'extendify-site-selection',
    getStorage: () => localStorage,
    partialize: (state) => ({
        siteType: state?.siteType ?? {},
        siteInformation: state?.siteInformation ?? {},
        feedbackMissingSiteType: state?.feedbackMissingSiteType ?? '',
        feedbackMissingGoal: state?.feedbackMissingGoal ?? '',
        siteTypeSearch: state?.siteTypeSearch ?? [],
        style: state?.style ?? null,
        pages: state?.pages ?? [],
        plugins: state?.plugins ?? [],
        goals: state?.goals ?? [],
    }),
})
export const useUserSelectionStore = window?.extOnbData?.devbuild
    ? create(withDevtools)
    : create(withPersist)
