import create from 'zustand'
import { persist } from 'zustand/middleware'

export const useGlobalStore = create(
    persist(
        (set) => ({
            open: false,
            metaData: {},
            currentTaxonomies: {},
            currentType: 'pattern',
            settingsModal: false,
            updateCurrentTaxonomies: (data) =>
                set({
                    currentTaxonomies: Object.assign({}, data),
                }),
            updateCurrentType: (data) => set({ currentType: data }),
            setOpen: (value) => {
                set({ open: value })
            },
        }),
        {
            name: 'extendify-global-state',
        },
    ),
)
