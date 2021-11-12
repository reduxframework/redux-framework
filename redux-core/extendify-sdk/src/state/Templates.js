import create from 'zustand'
import { templates as config } from '../config'
import { createBlocksFromInnerBlocksTemplate } from '../util/blocks'
import { useGlobalStore } from './GlobalState'

const defaultCategoryForType = (type, tax) => type === 'pattern' && tax === 'tax_categories'
    ? 'Default'
    : ''

export const useTemplatesStore = create((set, get) => ({
    templates: [],
    skipNextFetch: false,
    fetchToken: null,
    activeTemplate: {},
    activeTemplateBlocks: {},
    taxonomyDefaultState: {},
    searchParams: {
        taxonomies: {},
        type: config.defaultType,
        search: '',
    },
    // The offset is returned from Airtable.
    // It's removed when search params are updated
    // Or otherwise updated on each request
    nextPage: '',
    removeTemplates: () => set({
        nextPage: '',
        templates: [],
    }),
    appendTemplates: (templates) => set({
        templates: [...new Map([...get().templates, ...templates].map(item => [item.id, item])).values()],
    }),
    setupDefaultTaxonomies: (taxonomies) => {
        // This will transform ['tax_categories', 'tax_another'] to {tax_categories: 'Default', tax_another: ''}
        const defaultState = (tax) => defaultCategoryForType(get().searchParams.type, tax)
        const taxonomyDefaultState = Object.keys(taxonomies).reduce((theObject, current) => (theObject[current] = defaultState(current), theObject), {})
        const tax = {}
        tax.taxonomies = Object.assign(
            {}, taxonomyDefaultState, get().searchParams.taxonomies,
        )

        set({
            taxonomyDefaultState: taxonomyDefaultState,
            searchParams: {
                ...Object.assign(get().searchParams, tax),
            },
        })
    },
    setActive: (template) => {
        set({ activeTemplate: template })

        // If we havea  template, we should move that that page
        if (Object.keys(template).length > 0) {
            useGlobalStore.setState({ currentPage: 'single' })
        }

        // This will convert the template to blocks for quick(er) injection
        if (template?.fields?.code) {
            const { parse } = window.wp.blocks
            set({ activeTemplateBlocks: createBlocksFromInnerBlocksTemplate(parse(template.fields.code)) })
        }
    },
    resetTaxonomy: (tax) => {
        get().updateTaxonomies({
            [tax]: get().taxonomyDefaultState[tax] ?? '',
        })
    },
    updateTaxonomies: (params) => {
        const tax = {}
        tax.taxonomies = Object.assign(
            {}, get().searchParams.taxonomies, params,
        )
        get().updateSearchParams(tax)
    },
    updateSearchParams: (params) => {
        // If taxonomies are set to {}, lets use the default
        if (params?.taxonomies && !Object.keys(params.taxonomies).length) {
            params.taxonomies = get().taxonomyDefaultState
        }

        // If changing the type, change the hard coded tax cat label
        if (params?.type && ['', 'Default'].includes(get().searchParams?.taxonomies?.tax_categories)) {
            get().updateTaxonomies({
                tax_categories: defaultCategoryForType(params.type, 'tax_categories'),
            })
        }

        const searchParams = Object.assign(
            {}, get().searchParams, params,
        )

        // If the params are the same then don't update
        if (JSON.stringify(searchParams) === JSON.stringify(get().searchParams)) {
            return
        }

        set({
            templates: [],
            nextPage: '',
            searchParams,
        })
    },
}))
