import create from 'zustand'
import { subscribeWithSelector } from 'zustand/middleware'
import { useGlobalStore } from './GlobalState'
import { useTaxonomyStore } from './Taxonomies'
import { useUserStore } from './User'

const defaultCategoryForType = (tax) =>
    tax === 'siteType'
        ? { slug: '', title: 'Not set' }
        : { slug: '', title: 'Featured' }

export const useTemplatesStore = create(
    subscribeWithSelector((set, get) => ({
        templates: [],
        skipNextFetch: false,
        fetchToken: null,
        taxonomyDefaultState: {},
        nextPage: '',
        searchParams: {
            taxonomies: {},
            type: 'pattern',
        },
        initTemplateData() {
            set({ activeTemplate: {} })
            get().setupDefaultTaxonomies()
            get().updateType(useGlobalStore.getState().currentType)
        },
        appendTemplates: async (templates) => {
            for (const template of templates) {
                // If we already have this one, ignore it
                if (get().templates.find((t) => t.id === template.id)) {
                    continue
                }
                // Add some delay to prevent a batch update.
                await new Promise((resolve) => setTimeout(resolve, 5))
                requestAnimationFrame(() => {
                    const templatesAll = [...get().templates, template]
                    set({ templates: templatesAll })
                })
            }
        },
        setupDefaultTaxonomies: () => {
            const taxonomies = useTaxonomyStore.getState().taxonomies
            let taxonomyDefaultState = Object.entries(taxonomies).reduce(
                (state, current) => (
                    (state[current[0]] = defaultCategoryForType(current[0])),
                    state
                ),
                {},
            )
            const tax = {}
            let preferredTax =
                useUserStore.getState().preferredOptions?.taxonomies ?? {}

            // Check for old site type and set it if it exists
            if (preferredTax.tax_categories) {
                preferredTax = get().getLegacySiteType(preferredTax, taxonomies)
            }
            taxonomyDefaultState = Object.assign(
                {},
                taxonomyDefaultState,

                // Override with the user's preferred taxonomies
                preferredTax,

                // Override with the global state
                useGlobalStore.getState()?.currentTaxonomies ?? {},
            )

            tax.taxonomies = Object.assign({}, taxonomyDefaultState)
            set({
                taxonomyDefaultState: taxonomyDefaultState,
                searchParams: {
                    ...Object.assign(get().searchParams, tax),
                },
            })
        },
        updateTaxonomies: (params) => {
            const data = {}
            data.taxonomies = Object.assign(
                {},
                get().searchParams.taxonomies,
                params,
            )
            if (data?.taxonomies?.siteType) {
                // This is what the user "prefers", which may be used outside the library
                // which is persisted to the database, where as the global library state is in local storage
                useUserStore
                    .getState()
                    .updatePreferredOption(
                        'siteType',
                        data?.taxonomies?.siteType,
                    )
            }
            useGlobalStore.getState().updateCurrentTaxonomies(data?.taxonomies)
            get().updateSearchParams(data)
        },
        updateType(type) {
            useGlobalStore.getState().updateCurrentType(type)
            get().updateSearchParams({ type })
        },
        updateSearchParams: (params) => {
            // If taxonomies are set to {}, lets use the default
            if (params?.taxonomies && !Object.keys(params.taxonomies).length) {
                params.taxonomies = get().taxonomyDefaultState
            }

            const searchParams = Object.assign({}, get().searchParams, params)

            // If the params are not the same, then update
            if (
                JSON.stringify(searchParams) !==
                JSON.stringify(get().searchParams)
            ) {
                set({ templates: [], nextPage: '', searchParams })
            }
        },
        resetTemplates: () => set({ templates: [], nextPage: '' }),
        getLegacySiteType: (preferredTax, taxonomies) => {
            const oldSiteType = taxonomies.siteType.find((t) =>
                [t.slug, t?.title].includes(preferredTax.tax_categories),
            )
            // TODO: This is kind of wonky, as we keep track of the state in two places.
            useUserStore.getState().updatePreferredSiteType(oldSiteType)
            get().updateTaxonomies({ siteType: oldSiteType })
            // Remove the legacy term so this only runs once
            useUserStore
                .getState()
                .updatePreferredOption('tax_categories', null)
            return useUserStore.getState().preferredOptions.taxonomies
        },
    })),
)
