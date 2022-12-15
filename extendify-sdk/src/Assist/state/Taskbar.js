import create from 'zustand'
import { devtools } from 'zustand/middleware'

const state = (set) => ({
    open: false,
    toggleOpen: () => set((state) => ({ open: !state.open })),
})

export const useTaskbarStore = create(
    devtools(state, { name: 'Extendify Assist Taskbar' }),
    state,
)
