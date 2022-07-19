import { useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { useIsMounted } from '@library/hooks/helpers'
import { getLayoutTypes } from '@onboarding/api/DataApi'
import { PagePreview } from '@onboarding/components/PagePreview'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { useProgressStore } from '@onboarding/state/Progress'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

export const fetcher = async () => {
    const layoutTypes = await getLayoutTypes()
    const pageRecords = layoutTypes?.data ?? []
    if (!pageRecords?.length) throw new Error('Error fetching pages')

    // Home first and sort the other pages
    const homePage = pageRecords[0]
    const otherPages = pageRecords
        .slice(1)
        ?.sort((a, b) => (a.title > b.title ? 1 : -1))
    return [homePage, ...(otherPages ?? [])]
}
export const fetchData = () => {
    return { key: 'layout-types' }
}
export const metadata = {
    key: 'pages',
    title: __('Pages', 'extendify'),
    completed: () => true,
}
export const SitePages = () => {
    const { data: availablePages } = useFetch(fetchData, fetcher)
    const [pagesToShow, setPagesToShow] = useState([])
    const { add, goals, reset } = useUserSelectionStore()
    const isMounted = useIsMounted()
    const touch = useProgressStore((state) => state.touch)

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
        })()
    }, [availablePages, goals, isMounted])

    // Select all pages by default
    useEffect(() => {
        reset('pages')
        pagesToShow?.map((page) => add('pages', page))
    }, [pagesToShow, add, reset])

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-partner-primary-text mb-4 mt-0">
                    {__('What pages do you want on this site?', 'extendify')}
                </h1>
                <p className="text-base opacity-70">
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
                <div className="lg:flex mt-0 flex-wrap">
                    {pagesToShow?.map((page) => {
                        return (
                            <div
                                onClick={() => touch(metadata.key)}
                                className="px-3 mb-12 relative"
                                style={{ height: 442.5, width: 318.75 }}
                                key={page.id}>
                                <PagePreview
                                    required={page?.slug === 'home'}
                                    page={page}
                                    blockHeight={442.5}
                                />
                            </div>
                        )
                    })}
                </div>
            </div>
        </PageLayout>
    )
}
