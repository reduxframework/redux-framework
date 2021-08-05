import create from 'zustand'
import { templates as config } from '../config'
import { createBlocksFromInnerBlocksTemplate } from '../util/blocks'

const defaultCategoryForType = (type, tax) => type === 'pattern' && tax === 'tax_categories'
    ? 'Default'
    : ''

export const useTemplatesStore = create((set, get) => ({
    templates: [],
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
        set({
            activeTemplate: template,
        })

        // This will convert the template to blocks for quick(er) injection
        if (template?.fields?.code) {
            const { parse } = window.wp.blocks
            set({
                activeTemplateBlocks: createBlocksFromInnerBlocksTemplate(parse(template.fields.code)),
            })
        }
    },
    resetTaxonomies: () => {
        // Special default state for tax_categories
        const taxCatException = {
            ['tax_categories']: get().searchParams.type === 'pattern'
                ? 'Default'
                : '',
        }
        get().updateSearchParams({
            taxonomies: Object.assign(get().taxonomyDefaultState, taxCatException),
        })
    },
    updateTaxonomies: (params) => {
        // Special case for when the user isn't searching defaults. This way it mimics "all"
        // if (!Object.values(params).includes('Default') && !Object.keys(params).includes('tax_categories')) {
        //     console.log(get().searchParams.type,get().searchParams.taxonomies.tax_categories === 'Default')
        //     if (get().searchParams.type === 'pattern' && get().searchParams.taxonomies.tax_categories === 'Default') {
        //         params.tax_categories = ''
        //     }
        // }

        const tax = {}
        tax.taxonomies = Object.assign(
            {}, get().searchParams.taxonomies, params,
        )
        get().updateSearchParams(tax)
    },
    // TODO: Something is calling this too often
    updateSearchParams: (params) => {
        // If taxonomies are set to {}, lets use the default
        if (params?.taxonomies && !Object.keys(params.taxonomies).length) {
            params.taxonomies = get().taxonomyDefaultState
        }
        set({
            templates: [],
            nextPage: '',
            searchParams: {
                ...Object.assign(get().searchParams, params),
            },
        })
    },
}))
