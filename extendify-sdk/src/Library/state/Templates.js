import create from 'zustand'
import { subscribeWithSelector } from 'zustand/middleware'
import { useGlobalStore } from './GlobalState'
import { useSiteSettingsStore } from './SiteSettings'
import { useTaxonomyStore } from './Taxonomies'

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
                    (state[current[0]] = { slug: '', title: 'Featured' }), state
                ),
                {},
            )
            const tax = {
                taxonomies: {
                    ...taxonomyDefaultState,
                    // Override with the global state
                    ...(useGlobalStore.getState()?.currentTaxonomies ?? {}),
                    siteType: useSiteSettingsStore.getState().siteType,
                },
            }
            set((state) => ({
                taxonomyDefaultState: taxonomyDefaultState,
                searchParams: { ...state.searchParams, ...tax },
            }))
            // Do this because the siteType could change from the server
            useGlobalStore.getState().updateCurrentTaxonomies(tax.taxonomies)
        },
        updateTaxonomies: (params) => {
            const data = {}
            data.taxonomies = Object.assign(
                {},
                get().searchParams.taxonomies,
                params,
            )
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
    })),
)
