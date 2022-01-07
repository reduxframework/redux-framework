import create from 'zustand'
import { persist } from 'zustand/middleware'

export const useTaxonomyStore = create(
    persist(
        (set) => ({
            taxonomies: {},
            setTaxonomies: (taxonomies) => set({ taxonomies }),
        }),
        {
            name: 'extendify-taxonomies',
        },
    ),
)
