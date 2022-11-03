import create from 'zustand'
import { devtools } from 'zustand/middleware'

const store = (set) => ({
    generating: false,
    exitModalOpen: false,
    closeExitModal: () => set({ exitModalOpen: false }),
    openExitModal: () => set({ exitModalOpen: true }),
    hoveredOverExitButton: false,
    setExitButtonHovered: () => set({ hoveredOverExitButton: true }),
})
const withDevtools = devtools(store, { name: 'Extendify Launch Globals' })
export const useGlobalStore = create(withDevtools)
