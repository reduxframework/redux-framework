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
const withDevtools = devtools(store, { name: 'Extendify Launch Globals' })
const withPersist = persist(withDevtools, {
    name: 'extendify-launch-globals',
    getStorage: () => localStorage,
    partialize: (state) => ({
        orderId: state?.orderId ?? null,
    }),
})
export const useGlobalStore = create(withPersist)
