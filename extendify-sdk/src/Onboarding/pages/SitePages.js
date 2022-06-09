import { CheckboxControl } from '@wordpress/components'
import { useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { getLayoutTypes } from '@onboarding/api/DataApi'
import { PagePreview } from '@onboarding/components/PagePreview'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

export const fetcher = async () => {
    // TODO: these transforms should be moved to the server eventually
    const layoutTypes = await getLayoutTypes()
    const pageRecords = layoutTypes?.data?.map((record) => ({
        id: record.id,
        slug: record.slug,
        title: record.title,
    }))
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
export const SitePages = () => {
    const { data: availablePages } = useFetch(fetchData, fetcher)
    const [toggleAllPages, setToggleAllPages] = useState(false)
    const { pages: pagesSelected, add, remove } = useUserSelectionStore()

    // Toggle all pages on/off (except home)
    const updateToggleStatus = () => {
        availablePages?.map((page) => {
            if (page.slug === 'home') return
            toggleAllPages ? remove('pages', page) : add('pages', page)
        })
    }

    // Every time the number of selected pages changes, update the checkbox value
    useEffect(() => {
        setToggleAllPages(pagesSelected?.length === availablePages?.length)
    }, [pagesSelected, availablePages])

    // Select all pages by default
    useEffect(() => {
        availablePages?.map((page) => add('pages', page))
    }, [availablePages, add])

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-white mb-4 mt-0">
                    {__('What pages do you want on this site?', 'extendify')}
                </h1>
                <p className="text-base opacity-70">
                    {__('You may add more later', 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <div className="flex justify-between">
                    <p className="mt-0 mb-8 text-base">
                        {__(
                            "Pick the pages you'd like to add to your site",
                            'extendify',
                        )}
                    </p>

                    <CheckboxControl
                        label={__('Include all pages', 'extendify')}
                        checked={toggleAllPages}
                        onChange={updateToggleStatus}
                    />
                </div>
                <div className="lg:flex space-y-6 -m-8 lg:space-y-0 flex-wrap">
                    {availablePages?.map((page) => {
                        return (
                            <div
                                className="p-8 relative"
                                style={{ height: 442.5, width: 318.75 }}
                                key={page.id}>
                                <PagePreview
                                    lock={page?.slug === 'home'}
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
