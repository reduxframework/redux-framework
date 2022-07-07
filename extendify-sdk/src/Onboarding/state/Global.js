import create from 'zustand'
import { devtools, persist } from 'zustand/middleware'

const store = (set) => ({
    generating: false,
    generatedPages: {},
    orderId: null,
    setOrderId(orderId) {
        set({ orderId })
    },
})
export const useGlobalStore = create(
    persist(devtools(store), {
        name: 'extendify-launch-globals',
        getStorage: () => localStorage,
        partialize: (state) => ({
            orderId: state?.orderId ?? null,
        }),
    }),
)
