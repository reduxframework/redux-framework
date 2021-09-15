import { createTemplatesFilterFormula } from '../util/airtable'
import { Axios as api } from './axios'
import { templates as config } from '../config'

let count = 0

export const Templates = {
    async get(searchParams, options = {}) {
        count++
        const templates = await api.post('templates', {
            filterByFormula: createTemplatesFilterFormula(searchParams),
            pageSize: options?.pageSize ?? config.templatesPerRequest,
            categories: searchParams.taxonomies,
            search: searchParams.search,
            type: searchParams.type,
            offset: options.offset ?? '',
            initial: count === 1,
            request_count: count,
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
