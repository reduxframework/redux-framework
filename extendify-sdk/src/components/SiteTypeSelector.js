import { useEffect, useState, useRef, useMemo } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import Fuse from 'fuse.js'
import { useTestGroup } from '@extendify/hooks/useTestGroup'
import { useTemplatesStore } from '@extendify/state/Templates'

const searchMemo = new Map()

export const SiteTypeSelector = ({ value, setValue, terms }) => {
    const searchParams = useTemplatesStore((state) => state.searchParams)
    const [expanded, setExpanded] = useState(false)
    const searchRef = useRef()
    const [fuzzy, setFuzzy] = useState({})
    const [tempValue, setTempValue] = useState('')
    const [visibleChoices, setVisibleChoices] = useState([])
    const [showExamples, setShowExamples] = useState(true)
    const openOrClosed = useTestGroup('sitetype-open-closed', ['A', 'B'])

    const termsSorted = useMemo(() => {
        return terms.sort((a, b) => {
            if (a.slug < b.slug) return -1
            if (a.slug > b.slug) return 1
            return 0
        })
    }, [terms])

    const examples = useMemo(() => {
        return termsSorted.filter((t) => t?.featured)
    }, [termsSorted])

    const updateSearch = (term) => {
        setTempValue(term)
        filter(term)
    }

    const toggleExamples = () => setShowExamples((show) => !show)

    const filter = (term = '') => {
        if (searchMemo.has(term)) {
            setVisibleChoices(searchMemo.get(term))
            return
        }
        const results = fuzzy.search(term)
        searchMemo.set(
            term,
            results?.length ? results.map((t) => t.item) : examples,
        )
        setVisibleChoices(searchMemo.get(term))
    }

    useEffect(() => {
        setFuzzy(
            new Fuse(terms, {
                keys: ['slug', 'title', 'keywords'],
                minMatchCharLength: 1,
                threshold: 0.3,
            }),
        )
    }, [terms])

    useEffect(() => {
        if (!tempValue?.length) {
            setVisibleChoices(showExamples ? examples : termsSorted)
        }
    }, [examples, tempValue, termsSorted, showExamples])

    useEffect(() => {
        expanded && searchRef.current.focus()
    }, [expanded])

    useEffect(() => {
        if (openOrClosed === 'B' && !value.slug) {
            setExpanded(true)
        }
    }, [openOrClosed, value.slug])

    const contentHeader = (description) => {
        return (
            <>
                <span className="flex flex-col text-left">
                    <span
                        className={classNames('mb-1', {
                            'text-base font-normal': !value.slug,
                            'text-sm font-normal': value.slug?.length,
                        })}>
                        {__('Site Type', 'extendify')}
                    </span>
                    <span className="text-xs font-light">{description}</span>
                </span>
                <span className="flex items-center space-x-4">
                    {!expanded && !value.slug && (
                        <svg
                            className="text-wp-alert-red"
                            aria-hidden="true"
                            focusable="false"
                            width="21"
                            height="21"
                            viewBox="0 0 21 21"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                className="stroke-current"
                                d="M10.9982 4.05371C7.66149 4.05371 4.95654 6.75866 4.95654 10.0954C4.95654 13.4321 7.66149 16.137 10.9982 16.137C14.3349 16.137 17.0399 13.4321 17.0399 10.0954C17.0399 6.75866 14.3349 4.05371 10.9982 4.05371V4.05371Z"
                                strokeWidth="1.25"
                            />
                            <path
                                className="fill-current"
                                d="M10.0205 12.8717C10.0205 12.3287 10.4508 11.8881 10.9938 11.8881C11.5368 11.8881 11.9774 12.3287 11.9774 12.8717C11.9774 13.4147 11.5368 13.8451 10.9938 13.8451C10.4508 13.8451 10.0205 13.4147 10.0205 12.8717Z"
                            />
                            <path
                                className="fill-current"
                                d="M11.6495 10.2591C11.6086 10.6177 11.3524 10.9148 10.9938 10.9148C10.625 10.9148 10.3791 10.6074 10.3483 10.2591L10.0205 7.31855C9.95901 6.81652 10.4918 6.34521 10.9938 6.34521C11.4959 6.34521 12.0286 6.81652 11.9774 7.31855L11.6495 10.2591Z"
                            />
                        </svg>
                    )}
                    <svg
                        className={classNames('stroke-current text-gray-900', {
                            '-translate-x-1 rotate-90 transform': expanded,
                        })}
                        aria-hidden="true"
                        focusable="false"
                        width="8"
                        height="13"
                        viewBox="0 0 8 13"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1.24194 11.5952L6.24194 6.09519L1.24194 0.595215"
                            strokeWidth="1.5"
                        />
                    </svg>
                </span>
            </>
        )
    }

    const choicesList = (choices) => {
        return (
            <>
                <ul className="mt-4 mb-0">
                    {choices.map((item) => {
                        const label = item?.title ?? item.slug
                        const current =
                            searchParams?.taxonomies?.siteType?.slug ===
                            item.slug
                        return (
                            <li
                                key={item.slug + item?.title}
                                className="m-0 mb-1">
                                <button
                                    type="button"
                                    className={classNames(
                                        'm-0 w-full cursor-pointer bg-transparent pl-0 text-left text-sm font-normal hover:text-wp-theme-500',
                                        { 'text-gray-800': !current },
                                    )}
                                    onClick={() => {
                                        setExpanded(false)
                                        setValue(item)
                                    }}>
                                    {label}
                                </button>
                            </li>
                        )
                    })}
                </ul>
            </>
        )
    }

    return (
        <div className="w-full rounded bg-gray-50 border border-gray-900">
            <button
                type="button"
                onClick={() => setExpanded((expanded) => !expanded)}
                className="button-focus m-0 flex w-full cursor-pointer items-center justify-between rounded bg-transparent p-4 text-gray-800">
                {contentHeader(
                    expanded
                        ? __('Choose a site industry', 'extendify')
                        : value?.title ?? value.slug ?? 'Not set',
                )}
            </button>
            {expanded && (
                <div className="max-h-96 overflow-y-auto px-4 py-0">
                    <div className="sticky top-0 pt-0.5 pb-2 bg-gray-50">
                        <div className="relative">
                            <label
                                htmlFor="site-type-search"
                                className="sr-only">
                                {__('Search', 'extendify')}
                            </label>
                            <input
                                ref={searchRef}
                                id="site-type-search"
                                value={tempValue ?? ''}
                                onChange={(event) =>
                                    updateSearch(event.target.value)
                                }
                                type="text"
                                className="button-focus m-0 w-full bg-white p-3.5 py-2.5 text-sm border border-gray-900"
                                placeholder={__('Search', 'extendify')}
                            />
                            <svg
                                className="pointer-events-none absolute top-2 right-2 hidden lg:block"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                width="24"
                                height="24"
                                role="img"
                                aria-hidden="true"
                                focusable="false">
                                <path d="M13.5 6C10.5 6 8 8.5 8 11.5c0 1.1.3 2.1.9 3l-3.4 3 1 1.1 3.4-2.9c1 .9 2.2 1.4 3.6 1.4 3 0 5.5-2.5 5.5-5.5C19 8.5 16.5 6 13.5 6zm0 9.5c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4z"></path>
                            </svg>
                        </div>
                    </div>
                    {tempValue?.length > 1 && visibleChoices === examples && (
                        <p className="text-left">
                            {__('Nothing found...', 'extendify')}
                        </p>
                    )}
                    {visibleChoices?.length > 0 && (
                        <div>{choicesList(visibleChoices)}</div>
                    )}
                </div>
            )}
            {tempValue || !expanded ? null : (
                <button
                    type="button"
                    className="w-full cursor-pointer bg-transparent p-4 py-2 text-left text-sm text-wp-theme-500 hover:text-wp-theme-500"
                    onClick={toggleExamples}>
                    {showExamples
                        ? __('Show all', 'extendify')
                        : __('Close', 'extendify')}
                </button>
            )}
        </div>
    )
}
