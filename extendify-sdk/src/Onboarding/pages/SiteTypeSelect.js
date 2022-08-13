import { useEffect, useState, useRef } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { mutate } from 'swr'
import { getSiteTypes } from '@onboarding/api/DataApi'
import { updateOption } from '@onboarding/api/WPApi'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { pageState } from '@onboarding/state/factory'
import { SearchIcon, LeftArrowIcon } from '@onboarding/svg'
import {
    fetcher as styleFetcher,
    fetchData as styleFetchData,
} from './SiteStyle'

export const fetcher = () => getSiteTypes()
export const fetchData = () => ({ key: 'site-types' })
export const state = pageState('Site Industry', (set, get) => ({
    title: __('Site Industry', 'extendify'),
    default: undefined,
    showInSidebar: true,
    ready: false,
    isDefault: () =>
        useUserSelectionStore.getState()?.siteType?.slug ===
        get().default?.slug,
}))
export const SiteTypeSelect = () => {
    const siteType = useUserSelectionStore((state) => state.siteType)
    const feedback = useUserSelectionStore(
        (state) => state.feedbackMissingSiteType,
    )
    const [visibleSiteTypes, setVisibleSiteTypes] = useState([])
    const [search, setSearch] = useState('')
    const [showExamples, setShowExamples] = useState(true)
    const searchRef = useRef(null)
    const { data: siteTypes, loading } = useFetch(fetchData, fetcher)

    useEffect(() => {
        state.setState({ ready: !loading })
    }, [loading])

    useEffect(() => {
        const raf = requestAnimationFrame(() => searchRef.current?.focus())
        return () => cancelAnimationFrame(raf)
    }, [searchRef])

    useEffect(() => {
        if (loading) return
        if (siteType?.slug) return
        const defaultSiteType = siteTypes?.find(
            (record) => record.slug === 'default',
        )
        if (defaultSiteType) {
            const defaultS = {
                label: defaultSiteType.title,
                recordId: defaultSiteType.id,
                slug: defaultSiteType.slug,
            }
            useUserSelectionStore.getState().setSiteType(defaultS)
            state.setState({ default: defaultS })
        }
    }, [loading, siteType?.slug, siteTypes])

    useEffect(() => {
        if (loading) return
        if (search?.length > 0) {
            setVisibleSiteTypes(
                siteTypes?.filter((option) => {
                    const { title, keywords } = option
                    const s = search?.toLowerCase()
                    if (!s) return true
                    if (title.toLowerCase().indexOf(s) > -1) return true
                    if (!keywords?.length) return false
                    return keywords.find(
                        (value) => value.toLowerCase().indexOf(s) > -1,
                    )
                }),
            )
            return
        }
        // If search = '' then show the examples
        setVisibleSiteTypes(siteTypes?.filter((i) => i.featured))
        setShowExamples(true)
    }, [siteTypes, search, loading])

    useEffect(() => {
        if (loading) return
        setVisibleSiteTypes(
            showExamples
                ? siteTypes.filter((i) => i.featured)
                : siteTypes.sort((a, b) => a.title.localeCompare(b.title)),
        )
    }, [siteTypes, showExamples, loading])

    useEffect(() => {
        if (!search) return
        const timer = setTimeout(() => {
            useUserSelectionStore.setState({
                siteTypeSearch: [
                    ...useUserSelectionStore.getState().siteTypeSearch,
                    search,
                ],
            })
        }, 500)
        return () => clearTimeout(timer)
    }, [search])

    const selectSiteType = async (optionValue) => {
        useUserSelectionStore.getState().setSiteType({
            label: optionValue.title,
            recordId: optionValue.id,
            slug: optionValue.slug,
            styles: optionValue.styles,
        })
        await updateOption('extendify_siteType', optionValue)
    }

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-partner-primary-text mb-4 mt-0">
                    {__('What is your site about?', 'extendify')}
                </h1>
                <p className="text-base opacity-70 mb-0">
                    {__('Search for your site industry.', 'extendify')}
                </p>
            </div>
            <div className="w-full relative max-w-onboarding-sm mx-auto">
                <div className="sticky bg-white top-10 z-40 pt-9 pb-3 mb-2">
                    <div className="mx-auto flex justify-between mb-4">
                        <h2 className="text-lg m-0 text-gray-900">
                            {__('Choose an industry', 'extendify')}
                        </h2>
                        {search?.length > 0 ? null : (
                            <button
                                type="button"
                                className="bg-transparent hover:text-partner-primary-bg p-0 text-partner-primary-bg text-xs underline cursor-pointer"
                                onClick={() => {
                                    setShowExamples((show) => !show)
                                    searchRef.current?.focus()
                                }}>
                                {showExamples
                                    ? sprintf(
                                          __('Show all %s', 'extendify'),
                                          loading ? '...' : siteTypes.length,
                                      )
                                    : __('Show less', 'extendify')}
                            </button>
                        )}
                    </div>
                    <div className="mx-auto search-panel flex items-center justify-center relative mb-6">
                        <input
                            ref={searchRef}
                            type="text"
                            className="w-full bg-gray-100 h-12 pl-4 input-focus rounded-none ring-offset-0 focus:bg-white"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            placeholder={__('Search...', 'extendify')}
                        />
                        <SearchIcon className="icon-search" />
                    </div>
                    {loading && <p>{__('Loading...', 'extendify')}</p>}
                </div>
                {visibleSiteTypes?.length > 0 && (
                    <div className="relative">
                        {visibleSiteTypes.map((option) => (
                            <SelectButton
                                key={option.id}
                                selectSiteType={selectSiteType}
                                option={option}
                            />
                        ))}
                    </div>
                )}
                {!loading && visibleSiteTypes?.length === 0 && (
                    <div className="mx-auto w-full">
                        <div className="flex items-center justify-between uppercase">
                            {__('No Results', 'extendify')}
                            <button
                                type="button"
                                className="bg-transparent hover:text-partner-primary-bg p-0 text-partner-primary-bg text-xs underline cursor-pointer"
                                onClick={() => {
                                    setSearch('')
                                    searchRef.current?.focus()
                                }}>
                                {sprintf(
                                    __('Show all %s', 'extendify'),
                                    loading ? '...' : siteTypes.length,
                                )}
                            </button>
                        </div>
                        <h2 className="text-lg mt-12 mb-4 text-gray-900">
                            {__(
                                "Don't see what you're looking for?",
                                'extendify',
                            )}
                        </h2>
                        <div className="search-panel flex items-center justify-center relative">
                            <input
                                type="text"
                                className="w-full bg-gray-100 h-12 pl-4 input-focus rounded-none ring-offset-0 focus:bg-white"
                                value={feedback}
                                onChange={(e) =>
                                    useUserSelectionStore
                                        .getState()
                                        .setFeedbackMissingSiteType(
                                            e.target.value,
                                        )
                                }
                                placeholder={__(
                                    'Describe your site...',
                                    'extendify',
                                )}
                            />
                        </div>
                    </div>
                )}
            </div>
        </PageLayout>
    )
}

const SelectButton = ({ option, selectSiteType }) => {
    const hoveringTimeout = useRef(0)
    return (
        <button
            onClick={() => {
                selectSiteType(option)
            }}
            onMouseEnter={() => {
                // Prefetch style templates when hovering over site type
                window.clearTimeout(hoveringTimeout.current)
                hoveringTimeout.current = window.setTimeout(() => {
                    const data = () => styleFetchData(option)
                    mutate(data, (cache) => {
                        if (cache?.length) return cache
                        return styleFetcher(data())
                    })
                }, 100)
            }}
            onMouseLeave={() => {
                window.clearTimeout(hoveringTimeout.current)
            }}
            className="flex border border-gray-800 hover:text-partner-primary-bg focus:text-partner-primary-bg items-center justify-between mb-3 p-4 py-3 relative w-full button-focus">
            <span className="text-left">{option.title}</span>
            <LeftArrowIcon />
        </button>
    )
}
