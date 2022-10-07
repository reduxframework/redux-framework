import create from 'zustand'
import { persist, devtools } from 'zustand/middleware'
import { pages } from '@onboarding/lib/pages'

const store = (set, get) => ({
    pages: new Map(pages),
    currentPageIndex: 0,
    count() {
        return get().pages.size
    },
    getPageOrder() {
        return Array.from(get().pages.keys())
    },
    getCurrentPageData() {
        return get().pages.get(get().getCurrentPageSlug())
    },
    getCurrentPageSlug() {
        const page = get().getPageOrder()[get().currentPageIndex]
        if (!page) {
            get().setPage(0)
            return get().getPageOrder()[0]
        }
        return page
    },
    getNextPageData() {
        const nextIndex = get().currentPageIndex + 1
        if (nextIndex > get().count() - 1) return {}
        return get().pages.get(get().getPageOrder()[nextIndex])
    },
    setPage(page) {
        // If page is a string, get the index
        if (typeof page === 'string') {
            page = get().getPageOrder().indexOf(page)
        }
        if (page > get().count() - 1) return
        if (page < 0) return
        set({ currentPageIndex: page })
    },
    nextPage() {
        get().setPage(get().currentPageIndex + 1)
    },
    previousPage() {
        get().setPage(get().currentPageIndex - 1)
    },
})
const withDevtools = devtools(store, {
    name: 'Extendify Launch Pages',
    serialize: true,
})
const withPersist = persist(withDevtools, {
    name: 'extendify-pages',
    getStorage: () => localStorage,
    partialize: (state) => ({
        currentPageIndex: state?.currentPageIndex ?? 0,
        currentPageSlug: state?.getCurrentPageSlug() ?? null,
        availablePages: state?.getPageOrder() ?? [],
    }),
})
export const usePagesStore = create(withPersist)
