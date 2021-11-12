import create from 'zustand'
import { persist } from 'zustand/middleware'
import { User } from '../api/User'

const storage = {
    getItem: async () => await User.getData(),
    setItem: async (_name, value) => await User.setData(value),
}

export const useUserStore = create(persist((set, get) => ({
    email: '',
    apiKey: '',
    imports: 0,
    uuid: '',
    registration: {
        email: '',
    },
    allowedImports: 0,
    entryPoint: 'not-set',
    enabled: true,
    hasClickedThroughWelcomePage: false,
    canInstallPlugins: false,
    canActivatePlugins: false,
    preferredOptions: {
        taxonomies: {},
        type: '',
        search: '',
    },
    incrementImports: () => set({ imports: get().imports + 1 }),
    canImport: () => get().apiKey
        ? true
        : (Number(get().imports) < Number(get().allowedImports)),
    remainingImports: () => {
        if (get().apiKey) {
            return 'unlimited'
        }
        const remaining = Number(get().allowedImports) - Number(get().imports)
        return remaining > 0 ? remaining : 0
    },
    updatePreferredOption: (option, value) => {
        // If the option doesn't exist, assume it's a taxonomy
        if (!Object.prototype.hasOwnProperty.call(get().preferredOptions, option)) {
            value = Object.assign(
                {},
                get().preferredOptions?.taxonomies ?? {},
                { [option]: value },
            )
            option = 'taxonomies'
        }
        // Reset if the type changes from template/pattern/etc
        const resetTaxonomies = (option == 'type' && value !== get().preferredOptions?.type)
        set({
            preferredOptions: {
                ...Object.assign(
                    {},
                    get().preferredOptions,
                    { [option]: value },
                    resetTaxonomies ? { taxonomies: {}} : {},
                ),
            },
        })
    },
}), {
    name: 'extendify-user',
    getStorage: () => storage,
}))
