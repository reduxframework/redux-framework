import {
    useEffect, useRef, useState,
} from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { useTaxonomyStore } from '../state/Taxonomies'
import SidebarMain from './parts/sidebars/SidebarMain'
import RowByTax from './parts/RowByTax'
import HasSidebar from './parts/HasSidebar'
import TypeSelect from '../components/TypeSelect'
import TaxonomyBreadcrumbs from '../components/TaxonomyBreadcrumbs'
import Toolbar from './parts/Toolbar'
import { useTemplatesStore } from '../state/Templates'

export default function CuratedView() {
    const [terms, setTerms] = useState([])
    const scrollableArea = useRef()
    const taxonomies = useTaxonomyStore(state => state.taxonomies)
    const searchParams = useTemplatesStore(state => state.searchParams)
    const termsFiltered = (t) => Object.values(t)
        .filter((term) => term.type.includes('pattern'))
        .map((term) => term.term)
    const mergeTerm = (tax, term) => {
        const params = { ...searchParams }
        params.taxonomies = Object.assign(
            {}, searchParams.taxonomies, { [tax]: term },
        )
        return params
    }

    useEffect(() => {
        if (Object.keys(taxonomies?.tax_pattern_types ?? {}).length) {
            setTerms(termsFiltered(taxonomies.tax_pattern_types))
        }
    }, [taxonomies])

    useEffect(() => {
        scrollableArea.current.scrollTop = 0
    }, [searchParams])

    return <div className="bg-white h-full flex flex-col items-center relative shadow-xl max-w-screen-4xl mx-auto">
        <Toolbar className="w-full h-16 border-solid border-0 border-b border-gray-300 flex-shrink-0"/>
        <div className="w-full flex-grow overflow-hidden">
            <a href="#extendify-templates" className="sr-only focus:not-sr-only focus:text-blue-500">
                {__('Skip to content', 'extendify-sdk')}
            </a>
            <div className="sm:flex sm:space-x-12 relative bg-white mx-auto max-w-screen-4xl h-full">
                <HasSidebar>
                    <SidebarMain/>
                    <>
                        <TypeSelect/>
                        {/* TODO: we may want to inject this as a portal so it can directly share state with SidebarMain.js */}
                        <TaxonomyBreadcrumbs/>
                        <div className="relative h-full z-30 bg-white">
                            <div
                                ref={scrollableArea}
                                className="absolute z-20 inset-0 lg:static h-screen overflow-y-auto pt-16 px-6 sm:pl-0 sm:pr-8 pb-60">
                                {terms.length > 0 && terms.map((term) => {
                                    return <RowByTax
                                        key={term}
                                        title={term}
                                        tax="tax_pattern_types"
                                        searchParams={mergeTerm('tax_pattern_types', term)}/>
                                })}
                            </div>
                        </div>
                    </>
                </HasSidebar>
            </div>
        </div>
    </div>
}
