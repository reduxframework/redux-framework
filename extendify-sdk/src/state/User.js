import create from 'zustand'
import { persist } from 'zustand/middleware'
import { User } from '../api/User'

const storage = {
    getItem: async () => await User.getData(),
    setItem: async (_name, value) => await User.setData(value),
    removeItem: () => {},
}

const isGlobalLibraryEnabled = () =>
    window.extendifyData.sitesettings === null ||
    window.extendifyData?.sitesettings?.state?.enabled

export const useUserStore = create(
    persist(
        (set, get) => ({
            firstLoadedOn: new Date().toISOString(),
            email: '',
            apiKey: '',
            imports: 0,
            uuid: '',
            sdkPartner: '',
            registration: {
                email: '',
                optedOut: false,
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
            updatePreferredSiteType: (value) => {
                get().updatePreferredOption('siteType', value)
                if (!value?.slug || value.slug === 'unknown') return
                const current = get().preferredOptionsHistory?.siteType ?? []

                // If the site type isn't already included, prepend it
                if (!current.find((t) => t.slug === value.slug)) {
                    const siteType = [value, ...current]
                    set({
                        preferredOptionsHistory: Object.assign(
                            {},
                            get().preferredOptionsHistory,
                            { siteType: siteType.slice(0, 3) },
                        ),
                    })
                }
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

                set({
                    preferredOptions: {
                        ...Object.assign({}, get().preferredOptions, {
                            [option]: value,
                        }),
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
