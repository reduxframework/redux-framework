import create from 'zustand'
import { devtools, persist } from 'zustand/middleware'

// Similiar to Global.js but syncronous ("faster")
const state = (set) => ({
    designColors: {},
    queuedTour: null,
    setDesignColors(designColors) {
        set({ designColors })
    },
    queueTourForRedirect(tour) {
        set({ queuedTour: tour })
    },
    clearQueuedTour() {
        set({ queuedTour: null })
    },
})

export const useGlobalSyncStore = create(
    persist(devtools(state, { name: 'Extendify Assist Globals Sync' }), {
        name: 'extendify-assist-globals-sync',
        getStorage: () => localStorage,
    }),
    state,
)
