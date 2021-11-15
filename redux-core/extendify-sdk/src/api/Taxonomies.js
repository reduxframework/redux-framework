import { Axios as api } from './axios'

export const Taxonomies = {
    async get() {
        const taxonomies = await api.get('taxonomies-simple')
        if (taxonomies['tax_pattern_types_2.0']) {
            taxonomies.tax_pattern_types = taxonomies['tax_pattern_types_2.0']
            delete taxonomies['tax_pattern_types_2.0']
        }
        if (taxonomies['tax_page_types_2.0']) {
            taxonomies.tax_page_types = taxonomies['tax_page_types_2.0']
            delete taxonomies['tax_page_types_2.0']
        }
        return taxonomies
    },
}
