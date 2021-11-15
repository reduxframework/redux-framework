import { PanelBody, PanelRow } from '@wordpress/components'
import classNames from 'classnames'
import { useTemplatesStore } from '../state/Templates'
import { useTaxonomyStore } from '../state/Taxonomies'
import { getTaxonomyName } from '../util/general'

export default function TaxonomySection({ taxonomy: [title, data] }) {
    const updateTaxonomies = useTemplatesStore(state => state.updateTaxonomies)
    const searchParams = useTemplatesStore(state => state.searchParams)
    const openedTaxonomies = useTaxonomyStore(state => state.openedTaxonomies)
    const toggleOpenedTaxonomy = useTaxonomyStore(state => state.toggleOpenedTaxonomy)
    const isCurrentTax = (tax) => searchParams?.taxonomies[title] === tax.term
    const taxSupported = Object.values(data).filter((tax) => tax?.type?.includes(searchParams.type)).length

    if (!Object.keys(data).length || !taxSupported) return null
    return <PanelBody
        title={getTaxonomyName(title)}
        initialOpen={openedTaxonomies.includes(title) || title === 'tax_pattern_types' || title === 'tax_page_types'}
        onToggle={(value) => toggleOpenedTaxonomy(title, value)}>
        <PanelRow>
            <div className="overflow-hidden w-full relative">
                <ul className="p-1 m-0 w-full">
                    {Object.values(data)
                        .filter((tax) => tax?.type?.includes(searchParams.type))
                        .map((tax) =>
                            <li className="m-0 w-full" key={tax.term}>
                                <button
                                    type="button"
                                    className="text-left cursor-pointer w-full flex justify-between items-center py-1.5 m-0 leading-none bg-transparent hover:text-wp-theme-500 transition duration-200 button-focus"
                                    onClick={() => updateTaxonomies({ [title]: tax.term })}>
                                    <span className={classNames({ 'text-wp-theme-500': isCurrentTax(tax) })}>
                                        {tax.term}
                                    </span>
                                </button>
                            </li>)
                    }
                </ul>
            </div>
        </PanelRow>
    </PanelBody>
}
