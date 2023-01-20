import { useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { useIsMounted } from '@library/hooks/helpers'
import { getLayoutTypes } from '@onboarding/api/DataApi'
import { PagePreview } from '@onboarding/components/PagePreview'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { pageState } from '@onboarding/state/factory'

export const fetcher = async () => {
    const layoutTypes = await getLayoutTypes()
    const pageRecords = layoutTypes?.data ?? []
    if (!pageRecords?.length) throw new Error('Error fetching pages')

    // Home first and sort the other pages
    const homePage = pageRecords[0]
    const otherPages = pageRecords
        .slice(1)
        ?.sort((a, b) => (a.title > b.title ? 1 : -1))
    return { data: [homePage, ...(otherPages ?? [])] }
}
export const fetchData = () => {
    return { key: 'layout-types' }
}
export const state = pageState('Pages', (set, get) => ({
    title: __('Pages', 'extendify'),
    // default will end up just a list of all the pages
    default: undefined,
    showInSidebar: true,
    ready: false,
    isDefault: () =>
        useUserSelectionStore.getState().pages?.length ===
        get().default?.length,
}))
export const SitePages = () => {
    const { data: availablePages } = useFetch(fetchData, fetcher)
    const [pagesToShow, setPagesToShow] = useState([])
    const { add, goals, reset } = useUserSelectionStore()
    const isMounted = useIsMounted()

    useEffect(() => {
        if (pagesToShow?.length === availablePages?.length) {
            state.setState({ ready: true })
        }
    }, [availablePages?.length, pagesToShow?.length])

    useEffect(() => {
        if (!availablePages?.length) return
        const pagesbyGoal = availablePages.filter((page) => {
            // Show all if the user hasn't selected any goals
            if (!goals?.length) return true
            // If this page has no associated goals, show it
            if (!page?.goals?.length) return true
            // If this page has goals that the user has selected, show it
            return (
                // Check whether the goals intersect
                page?.goals?.some((goal) => goals.some((g) => goal == g.id)) ??
                true
            )
        })
        ;(async () => {
            for (const page of pagesbyGoal) {
                if (!isMounted.current) return
                setPagesToShow((pages) => [...pages, page])
                await new Promise((resolve) => setTimeout(resolve, 100))
            }
            state.setState({ ready: true })
        })()
    }, [availablePages, goals, isMounted])

    // Select all pages by default
    useEffect(() => {
        reset('pages')
        pagesToShow?.map((page) => add('pages', page))
        state.setState({ default: pagesToShow })
    }, [pagesToShow, add, reset])

    return (
        <PageLayout>
            <div>
                <h1
                    className="text-3xl text-partner-primary-text mb-4 mt-0"
                    data-test="pages-heading">
                    {__('What pages do you want on this site?', 'extendify')}
                </h1>
                <p className="text-base opacity-70 mb-0">
                    {__('You may add more later', 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <h2 className="text-lg m-0 mb-4 text-gray-900">
                    {__(
                        "Pick the pages you'd like to add to your site",
                        'extendify',
                    )}
                </h2>
                <div
                    className="flex gap-6 flex-wrap justify-center"
                    data-test="page-preview-wrapper">
                    {pagesToShow?.map((page) => {
                        if (page.slug !== 'home')
                            return (
                                <div
                                    className="relative"
                                    style={{ height: 541, width: 352 }}
                                    key={page.id}>
                                    <PagePreview
                                        required={page?.slug === 'home'}
                                        page={page}
                                        title={page?.title}
                                        blockHeight={541}
                                    />
                                </div>
                            )
                    })}
                </div>
            </div>
        </PageLayout>
    )
}
