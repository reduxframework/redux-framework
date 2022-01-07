import { PanelBody, PanelRow } from '@wordpress/components'
import classNames from 'classnames'
import { useTemplatesStore } from '../state/Templates'
import { getTaxonomyName } from '../util/general'

export default function TaxonomySection({ taxType, taxonomies }) {
    const updateTaxonomies = useTemplatesStore(
        (state) => state.updateTaxonomies,
    )
    const searchParams = useTemplatesStore((state) => state.searchParams)

    if (!taxonomies?.length > 0) return null
    return (
        <PanelBody
            title={getTaxonomyName(taxType)}
            className="ext-type-control p-0"
            initialOpen={true}>
            <PanelRow>
                <div className="overflow-hidden w-full relative">
                    <ul className="px-5 py-1 m-0 w-full">
                        {taxonomies.map((tax) => {
                            const isCurrentTax =
                                searchParams?.taxonomies[taxType]?.slug ===
                                tax?.slug
                            return (
                                <li className="m-0 w-full" key={tax.slug}>
                                    <button
                                        type="button"
                                        className="text-left text-sm cursor-pointer w-full flex justify-between items-center px-0 py-2 m-0 leading-none bg-transparent hover:text-wp-theme-500 transition duration-200 button-focus"
                                        onClick={() =>
                                            updateTaxonomies({ [taxType]: tax })
                                        }>
                                        <span
                                            className={classNames({
                                                'text-wp-theme-500':
                                                    isCurrentTax,
                                            })}>
                                            {tax?.title ?? tax.slug}
                                        </span>
                                    </button>
                                </li>
                            )
                        })}
                    </ul>
                </div>
            </PanelRow>
        </PanelBody>
    )
}
