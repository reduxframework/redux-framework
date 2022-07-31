import create from 'zustand'
import { persist, devtools } from 'zustand/middleware'

const initialState = {
    touched: [],
}
const store = (set) => ({
    ...initialState,
    touch(pageKey) {
        set((state) => {
            if (state.touched.includes(pageKey)) return
            state.touched = [...state.touched, pageKey]
        })
    },
    resetState() {
        set(initialState)
    },
})
const withDevtools = devtools(store, { name: 'Extendify Launch Progress' })
const withPersist = persist(withDevtools, {
    name: 'extendify-progress',
    getStorage: () => localStorage,
    partialize: (state) => ({
        touched: state.touched,
    }),
})
export const useProgressStore = window?.extOnbData?.devbuild
    ? create(withDevtools)
    : create(withPersist)
