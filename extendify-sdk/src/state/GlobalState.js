import create from 'zustand'
import { persist } from 'zustand/middleware'

export const useGlobalStore = create(
    persist(
        (set) => ({
            open: false,
            metaData: {},
            // These two are here just to persist their previous values,
            // but could be refactored to be the source instead.
            // It would require a refactor to state/Templates.js
            currentTaxonomies: {},
            currentType: 'pattern',
            settingsModal: false,
            currentModal: null,
            updateCurrentTaxonomies: (data) =>
                set({
                    currentTaxonomies: Object.assign({}, data),
                }),
            updateCurrentType: (data) => set({ currentType: data }),
            setOpen: (value) => {
                set({ open: value })
            },
            setCurrentModal: (value) => {
                set({ currentModal: value })
            },
        }),
        {
            name: 'extendify-global-state',
            partialize: (state) => {
                delete state.currentModal
                return state
            },
        },
    ),
)
