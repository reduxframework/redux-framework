import create from 'zustand'
import { persist } from 'zustand/middleware'
import { Taxonomies as TaxonomiesApi } from '@library/api/Taxonomies'

export const useTaxonomyStore = create(
    persist(
        (set, get) => ({
            taxonomies: {},
            setTaxonomies: (taxonomies) => set({ taxonomies }),
            fetchTaxonomies: async () => {
                let tax
                try {
                    tax = await TaxonomiesApi.get()
                    if (tax?.errors) {
                        console.error(tax)
                        throw new Error('Error fetching taxonomies')
                    }
                } catch (e) {
                    // If error then try again
                    get().fetchTaxonomies()
                    return
                }
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
