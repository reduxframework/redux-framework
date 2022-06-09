import create from 'zustand'
import { devtools } from 'zustand/middleware'
import { pages } from '@onboarding/lib/pages'

const store = (set, get) => ({
    pages: new Map(pages),
    currentPageIndex: 0,
    count() {
        return get().pages.size
    },
    pageOrder() {
        return Array.from(get().pages.keys())
    },
    currentPageData() {
        return get().pages.get(get().pageOrder()[get().currentPageIndex])
    },
    nextPageData() {
        const nextIndex = get().currentPageIndex + 1
        if (nextIndex > get().pageOrder().length - 1) return {}
        return get().pages.get(get().pageOrder()[nextIndex])
    },
    setPage(page) {
        // If page is a string, get the index
        if (typeof page === 'string') {
            page = get().pageOrder().indexOf(page)
        }
        if (page > get().pageOrder().length - 1) return
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
export const usePagesStore = create(devtools(store))
