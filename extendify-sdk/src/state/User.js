import { sample } from 'lodash'
import create from 'zustand'
import { persist } from 'zustand/middleware'
import { User } from '@extendify/api/User'

const storage = {
    getItem: async () => await User.getData(),
    setItem: async (_name, value) => await User.setData(value),
    removeItem: async () => await User.deleteData(),
}

const isGlobalLibraryEnabled = () =>
    window.extendifyData.sitesettings === null ||
    window.extendifyData?.sitesettings?.state?.enabled

// Keep track of active tests as some might be active
// but never rendered.
const activeTests = {
    ['main-button-text2']: '0007',
}

export const useUserStore = create(
    persist(
        (set, get) => ({
            _hasHydrated: false,
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
            participatingTestsGroups: {},
            preferredOptions: {
                taxonomies: {},
                type: '',
                search: '',
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
            testGroup(testKey, groupOptions) {
                if (!Object.keys(activeTests).includes(testKey)) return
                let groups = get().participatingTestsGroups
                // If the test is already in the group, don't add it again
                if (!groups[testKey]) {
                    set({
                        participatingTestsGroups: Object.assign({}, groups, {
                            [testKey]: sample(groupOptions),
                        }),
                    })
                }
                groups = get().participatingTestsGroups
                return groups[testKey]
            },
            activeTestGroups() {
                return Object.entries(get().participatingTestsGroups)
                    .filter(([key]) => Object.keys(activeTests).includes(key))
                    .reduce((obj, [key, value]) => {
                        obj[key] = value
                        return obj
                    }, {})
            },
            activeTestGroupsUtmValue() {
                const active = Object.entries(get().activeTestGroups())
                    .map(([key, value]) => {
                        return `${activeTests[key]}=${value}`
                    }, '')
                    .join(':')
                return encodeURIComponent(active)
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
                // where it's just fetching templates (and/or their max allowed)
                if (!get().allowedImports) {
                    return null
                }
                return remaining > 0 ? remaining : 0
            },
            updatePreferredSiteType: (value) => {
                get().updatePreferredOption('siteType', value)
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
            onRehydrateStorage: () => () => {
                useUserStore.setState({ _hasHydrated: true })
            },
            partialize: (state) => {
                delete state._hasHydrated
                return state
            },
        },
    ),
)
