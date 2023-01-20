import { useEffect, useState, useRef, Fragment } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Combobox } from '@headlessui/react'
import uFuzzy from '@leeoniya/ufuzzy'
import classNames from 'classnames'
import { motion, AnimatePresence } from 'framer-motion'
import { mutate } from 'swr'
import { getSiteTypes } from '@onboarding/api/DataApi'
import { updateOption } from '@onboarding/api/WPApi'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { pageState } from '@onboarding/state/factory'
import { SearchIcon, LeftArrowIcon, Checkmark } from '@onboarding/svg'
import {
    fetcher as styleFetcher,
    fetchData as styleFetchData,
} from './SiteLayout'

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

const fuzzyFilter = new uFuzzy({
    intraIns: 2,
    intraMode: 1,
})

export const SiteTypeSelect = () => {
    const { nextPage } = usePagesStore()
    const { siteType, setSiteType } = useUserSelectionStore()
    const [search, setSearch] = useState('')
    const searchRef = useRef(null)
    const { data: siteTypes, loading } = useFetch(fetchData, fetcher)
    const handleSetSiteType = async (optionValue) => {
        await updateOption('extendify_siteType', optionValue)
        setSiteType({
            label: optionValue.title,
            recordId: optionValue.id,
            slug: optionValue.slug,
            styles: optionValue.styles,
        })
        nextPage()
    }

    // Fuzzy search
    const titles = siteTypes?.map(({ title }) => title) ?? []
    const idx = fuzzyFilter.filter(titles, search)
    const info = fuzzyFilter.info(idx, titles, search)
    const order = fuzzyFilter.sort(info, titles, search)

    const visibleSiteTypes = search
        ? siteTypes
              // fitler and highlight
              ?.filter((_, i) => idx.includes(i))
              ?.map((_, i, types) => {
                  const item = types[order[i]]
                  return {
                      ...item,
                      titleMarked: uFuzzy.highlight(
                          item.title,
                          info.ranges[order[i]],
                          (part, matched) =>
                              matched
                                  ? '<b class="font-bold">' + part + '</b>'
                                  : part,
                      ),
                  }
              })
              .filter((item) => item.slug !== 'default')
        : siteTypes
              ?.filter((option) => option.slug !== 'default')
              ?.sort((a) => (a?.featured ? -1 : 0))
              ?.sort((a) => (a.slug === siteType.slug ? -1 : 0))

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
        if (!defaultSiteType) return

        const fallback = {
            label: defaultSiteType.title,
            recordId: defaultSiteType.id,
            slug: defaultSiteType.slug,
        }
        setSiteType(fallback)
        state.setState({ default: fallback })
    }, [loading, siteType?.slug, siteTypes, setSiteType])

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

    return (
        <PageLayout>
            <div>
                <h1
                    className="text-3xl text-partner-primary-text mb-4 mt-0"
                    data-test="launch-welcome-message">
                    {__('Welcome to your WordPress site', 'extendify')}
                </h1>
                <p className="text-base opacity-70 mb-0">
                    {__(
                        'Design and launch your site with this guided experience, or head right into the WordPress dashboard if you know your way around.',
                        'extendify',
                    )}
                </p>
            </div>

            <div className="w-full relative max-w-onboarding-md mx-auto">
                <h2 className="text-lg m-0 mb-4 text-gray-900">
                    {__('What is your site about?', 'extendify')}
                </h2>
                <Combobox
                    value={siteType}
                    by={(option, current) => option.slug === current.slug}
                    onChange={handleSetSiteType}>
                    <div className="relative border border-gray-200 rounded">
                        <div className="mx-auto search-panel flex items-center justify-center relative">
                            <Combobox.Input
                                ref={searchRef}
                                className="w-full h-14 px-4 m-0 input-focus ring-offset-0 focus:bg-white relative z-20 rounded"
                                autoComplete="off"
                                displayValue={() => ''}
                                placeholder={__(
                                    'Search for your business...',
                                    'extendify',
                                )}
                                onChange={(event) =>
                                    setSearch(event.target.value)
                                }
                                onBlur={() =>
                                    visibleSiteTypes?.length === 0 &&
                                    setSearch('')
                                }
                                data-test="sitetype-search-input"
                            />
                            <Combobox.Button className="absolute inset-y-0 right-0 mx-2 bg-transparent flex items-center p-2">
                                <SearchIcon />
                            </Combobox.Button>
                        </div>
                        {loading ? (
                            <span className="flex border-none items-center justify-between p-4 py-2.5 relative w-full text-base m-0 text-gray-700">
                                {__('Loading...', 'extendify')}
                            </span>
                        ) : (
                            <Combobox.Options
                                static
                                className="m-0 py-2 border-t border-gray-200 overflow-auto max-h-half rounded-b"
                                data-test="site-type-options">
                                {visibleSiteTypes?.length === 0 &&
                                search !== '' ? (
                                    <div className="flex border-none items-center justify-between p-4 py-2.5 relative w-full text-base m-0 text-gray-700">
                                        {__('No Results', 'extendify')}
                                    </div>
                                ) : (
                                    visibleSiteTypes?.map((type) => (
                                        <SelectButton
                                            type={type}
                                            key={type.id}
                                        />
                                    ))
                                )}
                            </Combobox.Options>
                        )}
                    </div>
                </Combobox>
                {loading || (
                    <AnimatePresence>
                        <MissingSiteType
                            siteTypes={visibleSiteTypes}
                            search={search}
                        />
                    </AnimatePresence>
                )}
            </div>
        </PageLayout>
    )
}

const SelectButton = ({ type }) => {
    const hoveringTimeout = useRef(0)
    return (
        <Combobox.Option
            onMouseEnter={() => {
                // Prefetch style templates when hovering over site type
                window.clearTimeout(hoveringTimeout.current)
                hoveringTimeout.current = window.setTimeout(() => {
                    const data = () => styleFetchData(type)
                    mutate(data, (cache) => {
                        if (cache?.length) return cache
                        return styleFetcher(data())
                    })
                }, 100)
            }}
            onMouseLeave={() => {
                window.clearTimeout(hoveringTimeout.current)
            }}
            className="flex border-none items-center justify-between p-4 py-2.5 relative w-full text-base cursor-pointer m-0 text-gray-900"
            value={type}>
            {({ selected, active }) => (
                <>
                    <span
                        className={classNames(
                            'absolute inset-0 pointer-events-none',
                            {
                                'bg-partner-primary-bg': active,
                            },
                        )}
                        aria-hidden="true"
                        style={{ opacity: '0.04' }}
                    />
                    <span
                        dangerouslySetInnerHTML={{
                            __html: type?.titleMarked ?? type.title,
                        }}
                    />
                    {selected ? (
                        <Checkmark className="h-6 w-6" />
                    ) : active ? (
                        <LeftArrowIcon className="h-6 w-6" />
                    ) : null}
                </>
            )}
        </Combobox.Option>
    )
}

const MissingSiteType = ({ siteTypes, search }) => {
    const { feedbackMissingSiteType, setFeedbackMissingSiteType } =
        useUserSelectionStore()
    const [show, setShow] = useState(false)

    useEffect(() => {
        // only show if there was a search that reproduced no results
        if (search !== '' && siteTypes?.length === 0) {
            setShow(
                window.extOnbData?.activeTests?.['remove-dont-see-inputs'] !==
                    'A',
            )
        }
    }, [search, siteTypes])

    if (!show) return null
    return (
        <motion.div
            initial={{ opacity: 0, y: 5 }}
            animate={{ opacity: 1, y: 0 }}
            className="mx-auto w-full">
            <h2 className="text-lg mt-12 mb-4 text-gray-900">
                {__("Don't see what you're looking for?", 'extendify')}
            </h2>
            <div className="search-panel flex items-center justify-center relative">
                <input
                    type="text"
                    className="w-full bg-gray-100 h-12 pl-4 input-focus rounded-none ring-offset-0 focus:bg-white"
                    value={feedbackMissingSiteType}
                    onChange={(e) => setFeedbackMissingSiteType(e.target.value)}
                    placeholder={__('Describe your site...', 'extendify')}
                />
            </div>
        </motion.div>
    )
}
