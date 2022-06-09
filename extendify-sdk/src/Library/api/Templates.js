import { useTemplatesStore } from '@library/state/Templates'
import { useUserStore } from '@library/state/User'
import { Axios as api } from './axios'

let count = 0

export const Templates = {
    async get(searchParams, options = {}) {
        count++
        const defaultpageSize = searchParams.type === 'pattern' ? '8' : '4'
        const taxonomyType =
            searchParams.type === 'pattern' ? 'patternType' : 'layoutType'
        const args = Object.assign(
            {
                filterByFormula: prepareFilterFormula(
                    searchParams,
                    taxonomyType,
                ),
                pageSize: defaultpageSize,
                categories: searchParams.taxonomies,
                search: searchParams.search,
                type: searchParams.type,
                offset: '',
                initial: count === 1,
                request_count: count,
                sdk_partner: useUserStore.getState().sdkPartner ?? '',
            },
            options,
        )
        return await api.post('templates', args)
    },

    // TODO: Refactor this later to combine the following three
    maybeImport(template) {
        const categories =
            useTemplatesStore.getState()?.searchParams?.taxonomies ?? []
        return api.post(`templates/${template.id}`, {
            template_id: template?.id,
            categories,
            maybe_import: true,
            type: template.fields?.type,
            sdk_partner: useUserStore.getState().sdkPartner ?? '',
            pageSize: '1',
            template_name: template.fields?.title,
        })
    },
    import(template) {
        const categories =
            useTemplatesStore.getState()?.searchParams?.taxonomies ?? []
        return api.post(`templates/${template.id}`, {
            template_id: template.id,
            categories,
            imported: true,
            basePattern:
                template.fields?.basePattern ??
                template.fields?.baseLayout ??
                '',
            type: template.fields.type,
            sdk_partner: useUserStore.getState().sdkPartner ?? '',
            pageSize: '1',
            template_name: template.fields?.title,
        })
    },
}

const prepareFilterFormula = ({ taxonomies }, type) => {
    const siteType = taxonomies?.siteType?.slug
    const formula = [
        `{type}="${type.replace('Type', '')}"`,
        `{siteType}="${siteType}"`,
    ]
    if (taxonomies[type]?.slug) {
        formula.push(`{${type}}="${taxonomies[type].slug}"`)
    }
    return `AND(${formula.join(', ')})`.replace(/\r?\n|\r/g, '')
}
