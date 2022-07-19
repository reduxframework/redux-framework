import create from 'zustand'
import { persist } from 'zustand/middleware'
import { Taxonomies as TaxonomiesApi } from '@library/api/Taxonomies'

export const useTaxonomyStore = create(
    persist(
        (set, get) => ({
            // No longer handles site types
            taxonomies: {},
            setTaxonomies: (taxonomies) => set({ taxonomies }),
            fetchTaxonomies: async () => {
                let tax = await TaxonomiesApi.get()
                tax = Object.keys(tax).reduce((taxFiltered, key) => {
                    taxFiltered[key] = tax[key]
                    return taxFiltered
                }, {})
                if (!Object.keys(tax)?.length) {
                    return
                }
                get().setTaxonomies(tax)
            },
        }),
        {
            name: 'extendify-taxonomies',
        },
    ),
)
