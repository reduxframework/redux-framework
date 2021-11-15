import create from 'zustand'
import { persist } from 'zustand/middleware'
import { useTemplatesStore } from './Templates'
import { templates as config } from '../config'

export const useGlobalStore = create(persist((set) => ({
    open: false,
    metaData: {},
    currentPage: 'main',
    currentTaxonomies: {},
    currentType: config.defaultType,
    settingsModal: false,
    updateCurrentTaxonomies: (data) => set({
        currentTaxonomies: Object.assign({}, data),
    }),
    updateCurrentType: (data) => set({ currentType: data }),
    setOpen: (value) => {
        set({ open: value })
        // Reset the state if it's closed manualy
        // value && useTemplatesStore.getState().setActive({}) - Not this though
        value && useTemplatesStore.getState().removeTemplates()
        // value && useTemplatesStore.getState().setActive({}) // This can be used to default to grid
    },
}), {
    name: 'extendify-global-state',
}))
