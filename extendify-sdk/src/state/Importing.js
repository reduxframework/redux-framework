import create from 'zustand'
import { persist } from 'zustand/middleware'

export const useWantedTemplateStore = create(persist((set) => ({
    wantedTemplate: {},
    importOnLoad: false,
    setWanted: (template) => set({
        wantedTemplate: template,
    }),
    removeWanted: () => set({
        wantedTemplate: {},
    }),

}), {
    name: 'extendify-wanted-template',
}))
