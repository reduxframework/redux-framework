import { useEffect, useState, useRef } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { mutate } from 'swr'
import { getSiteTypes } from '@onboarding/api/DataApi'
import { updateOption } from '@onboarding/api/WPApi'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { usePagesStore } from '@onboarding/state/Pages'
import { useProgressStore } from '@onboarding/state/Progress'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { SearchIcon, LeftArrowIcon } from '@onboarding/svg'
import {
    fetcher as styleFetcher,
    fetchData as styleFetchData,
} from './SiteStyle'

export const fetcher = () => getSiteTypes()
export const fetchData = () => ({ key: 'site-types' })
export const metadata = {
    key: 'site-type',
    title: __('Site Industry', 'extendify'),
    completed: () => true,
}
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
            styles: optionValue.styles,
        })
        await updateOption('extendify_siteType', optionValue)
        nextPage()
    }

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-partner-primary-text mb-4 mt-0">
                    {__('What is your site about?', 'extendify')}
                </h1>
                <p className="text-base opacity-70">
                    {__('Search for your site industry.', 'extendify')}
                </p>
            </div>
            <div className="w-80">
                <div className="flex justify-between mb-4">
                    <h2 className="text-lg m-0 text-gray-900">
                        {__('Choose an industry', 'extendify')}
                    </h2>
                    {search?.length > 0 ? null : (
                        <button
                            type="button"
                            className="bg-transparent hover:text-partner-primary-bg p-0 text-partner-primary-bg text-xs underline cursor-pointer"
                            onClick={() => {
                                setShowExamples((show) => !show)
                                searchRef.current.focus()
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
                <div className="search-panel flex items-center justify-center relative mb-8">
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
                {visibleSiteTypes?.length > 0 && (
                    <div>
                        <div
                            className="overflow-y-auto p-2 -m-2"
                            style={{
                                maxHeight: '380px',
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
    const touch = useProgressStore((state) => state.touch)
    return (
        <button
            onClick={() => {
                selectSiteType(option)
                touch(metadata.key)
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
            className="flex border border-gray-800 hover:text-partner-primary-bg focus:text-partner-primary-bg items-center justify-between mb-2 p-4 py-3 relative w-full button-focus">
            <span className="text-left">{option.title}</span>
            <LeftArrowIcon />
        </button>
    )
}
