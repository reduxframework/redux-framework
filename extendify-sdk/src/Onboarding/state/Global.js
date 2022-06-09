import create from 'zustand'
import { devtools } from 'zustand/middleware'

const store = () => ({
    generating: false,
    generatedPages: {},
})
export const useGlobalStore = create(devtools(store))
