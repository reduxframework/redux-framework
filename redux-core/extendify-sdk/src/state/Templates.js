import create from 'zustand'
import { useGlobalStore } from './GlobalState'
import { useUserStore } from './User'
import { useTaxonomyStore } from './Taxonomies'

const defaultCategoryForType = (tax) =>
    tax === 'tax_categories'
        ? 'Unknown'
        : useTaxonomyStore.getState()?.taxonomies[tax][0]?.term ?? undefined

export const useTemplatesStore = create((set, get) => ({
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
        set({
            activeTemplate: {},
        })
        get().setupDefaultTaxonomies()
        get().updateType(useGlobalStore.getState().currentType)
    },
    appendTemplates: (templates) =>
        set({
            templates: [
                ...new Map(
                    [...get().templates, ...templates].map((item) => [
                        item.id,
                        item,
                    ]),
                ).values(),
            ],
        }),
    setupDefaultTaxonomies: () => {
        const taxonomies = useTaxonomyStore.getState().taxonomies
        let taxonomyDefaultState = Object.entries(taxonomies).reduce(
            (state, current) => (
                (state[current[0]] = defaultCategoryForType(current[0])), state
            ),
            {},
        )
        const tax = {}

        taxonomyDefaultState = Object.assign(
            {},
            taxonomyDefaultState,

            // Override with the user's preferred taxonomies - Currently only supported with tax_categories
            useUserStore.getState().preferredOptions?.taxonomies ?? {},

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
        if (data?.taxonomies?.tax_categories) {
            // This is what the user "prefers", which may be used outside the library
            // which is persisted to the database, where as the global library state is in local storage
            useUserStore
                .getState()
                .updatePreferredOption(
                    'tax_categories',
                    data?.taxonomies?.tax_categories,
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

        // If the params are the same then don't update
        if (
            JSON.stringify(searchParams) === JSON.stringify(get().searchParams)
        ) {
            return
        }

        set({
            templates: [],
            nextPage: '',
            searchParams,
        })
    },
}))
