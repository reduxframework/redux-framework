import create from 'zustand'
import { useTemplatesStore } from './Templates'

export const useGlobalStore = create((set) => ({
    open: false,
    metaData: {},
    currentPage: 'main',
    setOpen: (value) => {
        set({
            open: value,
        })
        // Reset the state if it's closed manualy
        // value && useTemplatesStore.getState().setActive({}) - Not this though
        value && useTemplatesStore.getState().removeTemplates()
        // value && useTemplatesStore.getState().setActive({}) // This can be used to default to grid
    },
}))
