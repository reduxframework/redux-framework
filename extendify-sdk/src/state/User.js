import create from 'zustand'
import { persist } from 'zustand/middleware'
import { User } from '../api/User'

const storage = {
    getItem: async () => await User.getData(),
    setItem: async (_name, value) => await User.setData(value),
}

const isGlobalLibraryEnabled = () =>
    window.extendifySdkData.sitesettings === null ||
    window.extendifySdkData?.sitesettings?.state?.enabled

export const useUserStore = create(
    persist(
        (set, get) => ({
            email: '',
            apiKey: '',
            imports: 0,
            uuid: '',
            sdkPartner: '',
            registration: {
                email: '',
            },
            noticesDismissedAt: {},
            allowedImports: 0,
            entryPoint: 'not-set',
            enabled: isGlobalLibraryEnabled(),
            canInstallPlugins: false,
            canActivatePlugins: false,
            preferredOptions: {
                taxonomies: {},
                type: '',
                search: '',
            },
            preferredOptionsHistory: {
                siteType: [],
            },
            incrementImports: () => set({ imports: get().imports + 1 }),
            canImport: () =>
                get().apiKey
                    ? true
                    : Number(get().imports) < Number(get().allowedImports),
            remainingImports: () => {
                if (get().apiKey) {
                    return 'unlimited'
                }
                const remaining =
                    Number(get().allowedImports) - Number(get().imports)
                return remaining > 0 ? remaining : 0
            },
            updateSiteType: (value) => {
                get().updatePreferredOption('tax_categories', value)
                if (!value || value === 'Unknown') return

                const history = new Set([
                    value,
                    ...get().preferredOptionsHistory.siteType,
                ])
                set({
                    preferredOptionsHistory: Object.assign(
                        {},
                        get().preferredOptionsHistory,
                        {
                            siteType: [...history].slice(0, 3),
                        },
                    ),
                })
            },
            updatePreferredOption: (option, value) => {
                // If the option doesn't exist, assume it's a taxonomy
                if (
                    !Object.prototype.hasOwnProperty.call(
                        get().preferredOptions,
                        option,
                    )
                ) {
                    value = Object.assign(
                        {},
                        get().preferredOptions?.taxonomies ?? {},
                        { [option]: value },
                    )
                    option = 'taxonomies'
                }
                // Reset if the type changes from template/pattern/etc
                const resetTaxonomies =
                    option == 'type' && value !== get().preferredOptions?.type
                set({
                    preferredOptions: {
                        ...Object.assign(
                            {},
                            get().preferredOptions,
                            { [option]: value },
                            resetTaxonomies ? { taxonomies: {} } : {},
                        ),
                    },
                })
            },
        }),
        {
            name: 'extendify-user',
            getStorage: () => storage,
        },
    ),
)
