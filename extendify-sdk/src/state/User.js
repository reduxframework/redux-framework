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
            uuid: '',
            sdkPartner: '',
            noticesDismissedAt: {},
            modalNoticesDismissedAt: {},
            imports: 0, // total imports over time
            runningImports: 0, // timed imports, resets to 0 every month
            allowedImports: 0, // Max imports the Extendify service allows
            freebieImports: 0, //  Various free imports from actions (rewards)
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
            incrementImports: () => {
                // If the user has freebie imports, use those first
                const freebieImports =
                    Number(get().freebieImports) > 0
                        ? Number(get().freebieImports) - 1
                        : Number(get().freebieImports)
                // If they don't, then increment the running imports
                const runningImports =
                    Number(get().runningImports) + +(freebieImports < 1)
                set({
                    imports: Number(get().imports) + 1,
                    runningImports,
                    freebieImports,
                })
            },
            giveFreebieImports: (amount) => {
                set({ freebieImports: get().freebieImports + amount })
            },
            totalAvailableImports: () => {
                return (
                    Number(get().allowedImports) + Number(get().freebieImports)
                )
            },
            hasAvailableImports: () => {
                return get().apiKey
                    ? true
                    : Number(get().runningImports) <
                          Number(get().totalAvailableImports())
            },
            remainingImports: () => {
                const remaining =
                    Number(get().totalAvailableImports()) -
                    Number(get().runningImports)
                // If they have no allowed imports, this might be a first load
                // where it's just fetching templates (and/or their max alllowed)
                if (!get().allowedImports) {
                    return null
                }
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
            // Will mark a modal or footer notice
            markNoticeSeen: (key, type) => {
                set({
                    [`${type}DismissedAt`]: {
                        ...get()[`${type}DismissedAt`],
                        [key]: new Date().toISOString(),
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
