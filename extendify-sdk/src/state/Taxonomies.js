import create from 'zustand'

export const useTaxonomyStore = create((set, get) => ({
    taxonomies: {},
    openedTaxonomies: [],
    setTaxonomies: (taxonomies) => set({
        taxonomies,
    }),
    // This is here because I couldn't get the sidebar components to hold state on re-render
    toggleOpenedTaxonomy: (tax, add) => {
        const opened = get().openedTaxonomies
        set({
            openedTaxonomies: add ? [...opened, tax] : [...opened.filter(t => t != tax)],
        })
    },
}))
