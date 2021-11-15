import { useEffect, useCallback } from '@wordpress/element'
import { Taxonomies as TaxonomiesApi } from '../api/Taxonomies'
import { useTaxonomyStore } from '../state/Taxonomies'
import { useTemplatesStore } from '../state/Templates'

export default function useTaxonomies(fetchImmediately=false) {
    const setupDefaultTaxonomies = useTemplatesStore(state => state.setupDefaultTaxonomies)
    const setTaxonomies = useTaxonomyStore(state => state.setTaxonomies)
    const fetchTaxonomies = useCallback(async () => {
        let tax = await TaxonomiesApi.get()
        // Only allow items that have the 'tax_' prefix
        tax = Object.keys(tax)
            .filter((t) => t.startsWith('tax_'))
            .reduce((taxFiltered, key) => {
                taxFiltered[key] = tax[key]
                return taxFiltered
            }, {})
        setTaxonomies(tax)
        setupDefaultTaxonomies()
    }, [setTaxonomies, setupDefaultTaxonomies])

    useEffect(() => {
        fetchImmediately && fetchTaxonomies()
    }, [fetchTaxonomies, fetchImmediately])
}
