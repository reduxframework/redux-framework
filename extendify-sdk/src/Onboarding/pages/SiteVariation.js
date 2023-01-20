import { useEffect, useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { getStylesList } from '@onboarding/api/DataApi'
import { getThemeVariations } from '@onboarding/api/WPApi'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { pageState } from '@onboarding/state/factory'

export const fetcher = async ({ key }) => {
    const { data: remoteVariations } = await getStylesList()
    const { data: localVariations } = await getThemeVariations(key)
    // If the remote variations fail, just use the local variations
    if (!remoteVariations?.length) return localVariations
    if (!localVariations?.length) throw new Error('No local variations found')
    // Use the remote variations to determine the order
    const variations = remoteVariations.map((variation) => {
        const localVariation = localVariations.find(
            (local) => local.title === variation.title,
        )
        return localVariation
    })
    return { data: variations }
}
export const fetchData = () => ({ key: 'variations' })
export const state = pageState('Design', () => ({
    title: __('Design', 'extendify'),
    default: undefined,
    showInSidebar: true,
    ready: false,
    isDefault: () => undefined,
}))

const slugify = (str) => str.toLowerCase().replace(/\s/g, '-')

export const SiteVariation = () => {
    const [showAll, setShowAll] = useState(false)
    const { data: variations, loading } = useFetch('variations', fetcher)
    const { variation: current } = useUserSelectionStore()
    const setVariation = (variation) =>
        useUserSelectionStore.setState({ variation })

    useEffect(() => {
        state.setState({ ready: !loading })
        if (!loading && !current && variations?.length) {
            // Set the first one as the default
            setVariation(variations[0])
            state.setState({ default: variations[0] })
        }
    }, [loading, variations, current])

    return (
        <PageLayout>
            <div>
                <h1
                    className="text-3xl text-partner-primary-text mb-4 mt-0"
                    data-test="styles-heading">
                    {__('Now pick a design for your new site.', 'extendify')}
                </h1>
                <p className="text-base opacity-70 mb-0">
                    {__('You can personalize this later.', 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <h2 className="text-lg m-0 mb-4 text-gray-900">
                    {loading
                        ? __(
                              'Please wait a moment while we retrieve style previews...',
                              'extendify',
                          )
                        : __('Pick your style', 'extendify')}
                </h2>
                <div
                    className="grid md:grid-cols-2 gap-4 flex-wrap justify-center"
                    data-test="variation-wrapper">
                    {variations
                        ?.slice(0, showAll ? undefined : 6)
                        .map((variation) => {
                            const { title } = variation
                            return (
                                <button
                                    key={title}
                                    type="button"
                                    onClick={() => setVariation(variation)}
                                    aria-label={title}
                                    className={classNames(
                                        'p-1 m-0 bg-transparent border-0 cursor-pointer button-focus overflow-hidden',
                                        {
                                            'ring-partner-primary-bg ring-offset-2 ring-offset-white ring-wp':
                                                current?.title === title,
                                        },
                                    )}
                                    data-test="variation-item">
                                    <img
                                        src={`https://assets.extendify.com/style-cards/${slugify(
                                            title,
                                        )}.jpg`}
                                        style={{ aspectRatio: '1.333' }}
                                        alt=""
                                        loading="lazy"
                                        className="block w-full"
                                    />
                                </button>
                            )
                        })}
                </div>
                {variations?.length > 0 && !showAll && (
                    <div className="flex justify-center mt-8">
                        <button
                            className="flex items-center px-4 py-3 font-medium button-focus text-gray-900 bg-gray-100 hover:bg-gray-200 focus:bg-gray-200 bg-transparent"
                            type="button"
                            onClick={() => setShowAll(true)}>
                            {__('Show all styles', 'extendify')}
                        </button>
                    </div>
                )}
            </div>
        </PageLayout>
    )
}
