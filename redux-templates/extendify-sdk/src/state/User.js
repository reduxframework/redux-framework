import create from 'zustand'
import { persist } from 'zustand/middleware'
import { User } from '../api/User'

const storage = {
    getItem: async () => await User.getData(),
    setItem: async (_name, value) => await User.setData(value),
}

export const useUserStore = create(persist((set, get) => ({
    apiKey: '',
    imports: 0,
    uuid: '',
    email: '',
    allowedImports: 0,
    entryPoint: 'not-set',
    enabled: true,
    hasClickedThroughWelcomePage: false,
    canInstallPlugins: false,
    canActivatePlugins: false,
    incrementImports: () => set({
        imports: get().imports + 1,
    }),
    canImport: () => get().apiKey
        ? true
        : (Number(get().imports) < Number(get().allowedImports)),
    remainingImports: () => {
        if (get().apiKey) {
            return 'unlimited'
        }
        const remaining = Number(get().allowedImports) - Number(get().imports)
        return remaining > 0
            ? remaining
            : 0
    },
}), {
    name: 'extendify-user',
    getStorage: () => storage,
}))
