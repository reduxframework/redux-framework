import { PanelBody, PanelRow } from '@wordpress/components'
import classNames from 'classnames'
import { useTemplatesStore } from '@extendify/state/Templates'
import { getTaxonomyName } from '@extendify/util/general'

export default function TaxonomySection({ taxType, taxonomies, taxLabel }) {
    const updateTaxonomies = useTemplatesStore(
        (state) => state.updateTaxonomies,
    )
    const searchParams = useTemplatesStore((state) => state.searchParams)

    if (!taxonomies?.length > 0) return null
    return (
        <PanelBody
            title={getTaxonomyName(taxLabel ?? taxType)}
            className="ext-type-control p-0"
            initialOpen={true}>
            <PanelRow>
                <div className="relative w-full overflow-hidden">
                    <ul className="m-0 w-full px-5 py-1">
                        {taxonomies.map((tax) => {
                            const isCurrentTax =
                                searchParams?.taxonomies[taxType]?.slug ===
                                tax?.slug
                            return (
                                <li className="m-0 w-full" key={tax.slug}>
                                    <button
                                        type="button"
                                        className="button-focus m-0 flex w-full cursor-pointer items-center justify-between bg-transparent px-0 py-2 text-left text-sm leading-none transition duration-200 hover:text-wp-theme-500"
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
