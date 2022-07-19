import create from 'zustand'
import { persist, subscribeWithSelector } from 'zustand/middleware'

export const useGlobalStore = create(
    subscribeWithSelector(
        persist(
            (set, get) => ({
                open: false,
                ready: false,
                metaData: {},
                // These two are here just to persist their previous values,
                // but could be refactored to be the source instead.
                // It would require a refactor to state/Templates.js
                currentTaxonomies: {},
                currentType: 'pattern',
                modals: [],
                pushModal: (modal) => set({ modals: [modal, ...get().modals] }),
                popModal: () => set({ modals: get().modals.slice(1) }),
                removeAllModals: () => set({ modals: [] }),
                updateCurrentTaxonomies: (data) =>
                    set({ currentTaxonomies: { ...data } }),
                updateCurrentType: (data) => set({ currentType: data }),
                setOpen: (value) => set({ open: value }),
                setReady: (value) => set({ ready: value }),
            }),
            {
                name: 'extendify-global-state',
                partialize: (state) => {
                    delete state.modals
                    delete state.ready
                    return state
                },
            },
        ),
    ),
)
