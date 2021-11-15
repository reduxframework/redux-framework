import { Axios as api } from './axios'
import { templates as config } from '../config'
import { useTaxonomyStore } from '../state/Taxonomies'
import { useUserStore } from '../state/User'

let count = 0

export const Templates = {
    async get(searchParams, options = {}) {
        count++
        const templates = await api.post('templates', {
            filterByFormula: prepareFilterFormula(searchParams),
            pageSize: options?.pageSize ?? config.templatesPerRequest,
            categories: searchParams.taxonomies,
            search: searchParams.search,
            type: searchParams.type,
            offset: options.offset ?? '',
            initial: count === 1,
            request_count: count,
            sdk_partner: useUserStore.getState().sdkPartner ?? '',
        })
        return templates
    },
    related(
        template, queryType, wantedType,
    ) {
        return api.post('related', {
            pageSize: 4,
            query_type: queryType,
            wanted_type: wantedType,
            categories: template?.fields?.tax_categories,
            pattern_types: template?.fields?.tax_pattern_types,
            style: template?.fields?.tax_style,
            type: template?.fields?.type,
            template_id: template?.id,
        })
    },

    // TODO: Refactor this later to combine the following three
    maybeImport(template) {
        return api.post(`templates/${template.id}`, {
            template_id: template.id,
            maybe_import: true,
            type: template.fields.type,
            pageSize: config.templatesPerRequest,
            template_name: template.fields?.title,
        })
    },
    single(template) {
        return api.post(`templates/${template.id}`, {
            template_id: template.id,
            single: true,
            type: template.fields.type,
            pageSize: config.templatesPerRequest,
            template_name: template.fields?.title,
        })
    },
    import(template) {
        return api.post(`templates/${template.id}`, {
            template_id: template.id,
            imported: true,
            type: template.fields.type,
            pageSize: config.templatesPerRequest,
            template_name: template.fields?.title,
        })
    },
}

const prepareFilterFormula = (filters) => {
    let { taxonomies, type } = filters
    taxonomies = { ... taxonomies }
    const formula = []

    // In Airtable, we tag them as Default
    if (taxonomies?.tax_categories === 'Unknown') {
        taxonomies.tax_categories = 'Default'
    }

    // Builds the taxonomy list by looping over all supplied taxonomies
    const taxFormula = Object.entries(taxonomies)
        .filter(([tax, term]) => checkTermIsAvailableOnType(
            tax, term, type,
        ))
        .filter(([tax]) => Boolean(tax[1].length))
        .map(([tax, term]) => `${tax} = "${term}"`)
        .join(', ')

    taxFormula.length && formula.push(taxFormula)
    type.length && formula.push(`{type}="${type}"`)

    return formula.length
        ? `AND(${formula.join(', ')})`.replace(/\r?\n|\r/g, '')
        : ''
}

const termTypeMap = new Map()
const checkTermIsAvailableOnType = (
    tax, term, type,
) => {
    const key = `${tax}-${term}-${type}`
    if (key === 'tax_categories-Default-pattern') {
        return true
    }
    if (!termTypeMap.has(key)) {
        termTypeMap.set(key, useTaxonomyStore.getState()?.taxonomies[tax]?.find((item) => item?.term === term)?.type?.includes(type))
    }
    return termTypeMap.get(key)
}
