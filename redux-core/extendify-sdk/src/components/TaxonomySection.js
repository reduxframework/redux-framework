import { PanelBody, PanelRow } from '@wordpress/components'
import classNames from 'classnames'
import { useTemplatesStore } from '../state/Templates'
import { __ } from '@wordpress/i18n'
import {
    useState, useEffect, useRef, useCallback,
} from '@wordpress/element'
import { useTaxonomyStore } from '../state/Taxonomies'

export default function TaxonomySection({ taxonomy: [title, data] }) {
    const updateTaxonomies = useTemplatesStore(state => state.updateTaxonomies)
    const resetTaxonomy = useTemplatesStore(state => state.resetTaxonomy)
    const searchParams = useTemplatesStore(state => state.searchParams)
    const openedTaxonomies = useTaxonomyStore(state => state.openedTaxonomies)
    const toggleOpenedTaxonomy = useTaxonomyStore(state => state.toggleOpenedTaxonomy)
    const [pageTwoTerms, setPageTwoTerms] = useState({})
    const [taxListHeight, setTaxListHeight] = useState({})
    const pageTwo = useRef()
    const pageOneFocus = useRef()
    const pageTwoFocus = useRef()
    const firstUpdate = useRef(true)

    // This will check whether the term is current (either child or top level/has no child)
    // And then it will search children so the parent is also marked as selected
    const isCurrentTax = (tax) => searchParams?.taxonomies[title] === tax.term
        || tax.children?.filter((t) => {
            return t.term === searchParams?.taxonomies[title]
        }).length > 0

    // Todo: memo this
    const isAvailableOnCurrentType = useCallback((tax) => {
        if (Object.prototype.hasOwnProperty.call(tax, 'children')) {
            return tax.children.filter((t) => t?.type.includes(searchParams.type)).length
        }
        return tax?.type?.includes(searchParams.type)
    }, [searchParams.type])

    useEffect(() => {
        if (firstUpdate.current) {
            firstUpdate.current = false
            return
        }
        setPageTwoTerms({})
    }, [searchParams.type])

    useEffect(() => {
        if (Object.keys(pageTwoTerms).length) {
            setTimeout(() => {
                requestAnimationFrame(() => {
                    setTaxListHeight(pageTwo.current.clientHeight)
                    pageTwoFocus.current.focus()
                })
            }, 200)
            return
        }
        setTaxListHeight('auto')
    }, [pageTwoTerms])

    useEffect(() => {
        const notSupported = !Object.values(data).filter((tax) => isAvailableOnCurrentType(tax)).length
        // Reset taxonomies that aren't supported on a type
        notSupported && resetTaxonomy(title)
    }, [resetTaxonomy, title, isAvailableOnCurrentType, data])

    // Return early if 1. No data or 2. Children don't match this type
    if (!Object.keys(data).length || !Object.values(data).filter((tax) => isAvailableOnCurrentType(tax)).length) {
        return ''
    }

    const theTitle = title.replace('tax_', '').replace(/_/g , ' ').replace(/\b\w/g, l => l.toUpperCase())
    return <PanelBody
        title={theTitle}
        initialOpen={openedTaxonomies.includes(title)}
        onToggle={(value) => toggleOpenedTaxonomy(title, value)}>
        <PanelRow>
            <div className="overflow-hidden w-full relative" style={{
                height: taxListHeight,
            }}>
                <ul className={classNames('p-1 m-0 w-full transform transition duration-200', {
                    '-translate-x-full': Object.keys(pageTwoTerms).length,
                })}>
                    <li className="m-0">
                        <button
                            type="button"
                            className="text-left cursor-pointer w-full flex justify-between items-center py-1.5 m-0 leading-none hover:text-wp-theme-500 bg-transparent transition duration-200 button-focus"
                            ref={pageOneFocus}
                            onClick={() => {
                                updateTaxonomies({
                                    [title]: searchParams.type === 'pattern' && title === 'tax_categories'
                                        ? 'Default'
                                        : '',
                                })
                            }}>
                            <span className={classNames({
                                'text-wp-theme-500': (!searchParams.taxonomies[title]?.length || searchParams?.taxonomies[title] === 'Default'),
                            })}>
                                {searchParams.type === 'pattern' && title === 'tax_categories'
                                    ? __('Default', 'extendify-sdk')
                                    : __('All', 'extendify-sdk')}
                            </span>
                        </button>
                    </li>
                    {Object.values(data)
                        .filter((tax) => isAvailableOnCurrentType(tax))
                        .sort((prev, next) => prev.term.localeCompare(next.term))
                        .map((tax) =>
                            <li className="m-0 w-full" key={tax.term}>
                                <button
                                    type="button"
                                    className="text-left cursor-pointer w-full flex justify-between items-center py-1.5 m-0 leading-none bg-transparent hover:text-wp-theme-500 transition duration-200 button-focus"
                                    onClick={() => {
                                        if (Object.prototype.hasOwnProperty.call(tax, 'children')) {
                                            setPageTwoTerms(tax)
                                            return
                                        }
                                        updateTaxonomies({
                                            [title]: tax.term,
                                        })
                                    }}>
                                    <span className={classNames({
                                        'text-wp-theme-500': isCurrentTax(tax),
                                    })}>
                                        {tax.term}
                                    </span>
                                    {Object.prototype.hasOwnProperty.call(tax, 'children') && <span className="text-black">
                                        <svg width="8" height="14" viewBox="0 0 8 14" className="stroke-current" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 12.5L6 6.99998L1 1.5" strokeWidth="1.5"/>
                                        </svg>
                                    </span>}
                                </button>
                            </li>)
                    }
                </ul>
                <ul ref={pageTwo} className={classNames('p-1 m-0 w-full transform transition duration-200 absolute top-0 right-0', {
                    'translate-x-full': !Object.keys(pageTwoTerms).length,
                })}>
                    {Object.values(pageTwoTerms).length > 0 && <li className="m-0">
                        <button
                            type="button"
                            className="text-left cursor-pointer font-bold flex space-x-4 items-center py-2 pr-4 m-0leading-none hover:text-wp-theme-500 bg-transparent transition duration-200 button-focus"
                            ref={pageTwoFocus}
                            onClick={() => {
                                setPageTwoTerms({})
                                pageOneFocus.current.focus()
                            }}>
                            <svg className="stroke-current transform rotate-180" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 12.5L6 6.99998L1 1.5" strokeWidth="1.5"/>
                            </svg>
                            <span>{pageTwoTerms.term}</span>
                        </button>
                    </li> }
                    {Object.values(pageTwoTerms).length
                        && Object.values(pageTwoTerms.children)
                            .filter((tax) => isAvailableOnCurrentType(tax))
                            .sort((prev, next) => prev.term.localeCompare(next.term))
                            .map((childTax) =>
                                <li className="m-0 pl-6 w-full flex justify-between items-center" key={childTax.term}>
                                    <button
                                        type="button"
                                        className="text-left cursor-pointer w-full flex justify-between items-center py-1.5 m-0 leading-none bg-transparent hover:text-wp-theme-500 transition duration-200 button-focus"
                                        onClick={() => {
                                            updateTaxonomies({
                                                [title]: childTax.term,
                                            })
                                        }}>
                                        <span className={classNames({
                                            'text-wp-theme-500': isCurrentTax(childTax),
                                        })}>
                                            {childTax.term}
                                        </span>
                                    </button>
                                </li>)
                    }
                </ul>
            </div>
        </PanelRow>
    </PanelBody>
}
