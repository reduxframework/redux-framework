import { useEffect, useState, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { mutate } from 'swr'
import { getSiteTypes } from '@onboarding/api/DataApi'
import { updateSiteType } from '@onboarding/api/LibraryApi'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { SearchIcon, LeftArrowIcon } from '@onboarding/svg'
import {
    fetcher as styleFetcher,
    fetchData as styleFetchData,
} from './SiteStyle'

export const fetcher = () => getSiteTypes()
export const fetchData = () => ({ key: 'site-types' })
export const SiteTypeSelect = () => {
    const siteType = useUserSelectionStore((state) => state.siteType)
    const nextPage = usePagesStore((state) => state.nextPage)
    const [visibleSiteTypes, setVisibleSiteTypes] = useState([])
    const [search, setSearch] = useState('')
    const [showExamples, setShowExamples] = useState(true)
    const searchRef = useRef(null)
    const { data: siteTypes, loading } = useFetch(fetchData, fetcher)

    useEffect(() => {
        const raf = requestAnimationFrame(() => searchRef.current.focus())
        return () => cancelAnimationFrame(raf)
    }, [searchRef])

    useEffect(() => {
        if (loading) return
        if (siteType?.slug) return
        const defaultSiteType = siteTypes?.find(
            (record) => record.slug === 'default',
        )
        if (defaultSiteType) {
            useUserSelectionStore.getState().setSiteType({
                label: defaultSiteType.title,
                recordId: defaultSiteType.id,
                slug: defaultSiteType.slug,
            })
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
            showExamples ? siteTypes.filter((i) => i.featured) : siteTypes,
        )
    }, [siteTypes, showExamples, loading])

    const selectSiteType = async (optionValue) => {
        useUserSelectionStore.getState().setSiteType({
            label: optionValue.title,
            recordId: optionValue.id,
            slug: optionValue.slug,
        })

        // Update the site type in the library
        window.localStorage.removeItem('extendify-global-state')
        await updateSiteType({
            siteType: {
                title: optionValue.title,
                slug: optionValue.slug,
            },
        })
        nextPage()
    }

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-white mb-4 mt-0">
                    {__('What is your site about?', 'extendify')}
                </h1>
                <p className="text-base opacity-70">
                    {__(
                        'Search for the industry that best suits your site.',
                        'extendify',
                    )}
                </p>
            </div>
            <div className="w-80">
                <p className="mt-0 mb-8 text-base">
                    {__('Choose a site industry:', 'extendify')}
                </p>
                <div className="search-panel flex items-center justify-center relative mb-8">
                    <input
                        ref={searchRef}
                        type="text"
                        className="w-full border h-12 input-focus"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        placeholder={__('Search...', 'extendify')}
                    />
                    <SearchIcon className="icon-search" />
                </div>
                {loading && <p>{__('Loading...', 'extendify')}</p>}
                {visibleSiteTypes?.length > 0 && (
                    <div>
                        <div className="flex justify-between mb-3">
                            <p className="text-left uppercase text-xss m-0">
                                {__('Industries', 'extendify')}
                            </p>
                            {search?.length > 0 ? null : (
                                <button
                                    type="button"
                                    className="bg-transparent hover:text-partner-primary-bg p-0 text-partner-primary-bg text-xs underline"
                                    onClick={() => {
                                        setShowExamples((show) => !show)
                                        searchRef.current.focus()
                                    }}>
                                    {showExamples
                                        ? __('Show all', 'extendify')
                                        : __('Show less', 'extendify')}
                                </button>
                            )}
                        </div>
                        <div
                            className="overflow-y-auto p-2 -m-2"
                            style={{
                                maxHeight: '360px',
                            }}>
                            {visibleSiteTypes.map((option) => (
                                <SelectButton
                                    key={option.id}
                                    selectSiteType={selectSiteType}
                                    option={option}
                                />
                            ))}
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
            onClick={() => selectSiteType(option)}
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
            className="flex bg-gray-100 hover:bg-gray-200 items-center justify-between mb-2 p-4 py-3 relative w-full button-focus">
            <span>{option.title}</span>
            <LeftArrowIcon />
        </button>
    )
}
