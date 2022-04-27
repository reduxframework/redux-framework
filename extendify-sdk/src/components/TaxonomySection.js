import { PanelBody, PanelRow } from '@wordpress/components'
import classNames from 'classnames'
import { useTemplatesStore } from '@extendify/state/Templates'
import { getTaxonomyName } from '@extendify/util/general'

const SingleTaxItem = ({ active, tax, update }) => {
    return (
        <li className="m-0 w-full" key={tax.slug}>
            <button
                type="button"
                className="group m-0 p-0 flex w-full cursor-pointer text-left text-sm leading-none my-px"
                onClick={update}>
                <span
                    className={classNames(
                        'w-full group-hover:bg-gray-900 p-2 group-hover:text-gray-50 rounded',
                        {
                            'bg-transparent text-gray-900': !active,
                            'bg-gray-900 text-gray-50': active,
                        },
                    )}>
                    {tax?.title ?? tax.slug}
                </span>
            </button>
        </li>
    )
}

export const TaxonomySection = ({ taxType, taxonomies, taxLabel }) => {
    const searchParams = useTemplatesStore((state) => state.searchParams)
    const updateTaxonomies = useTemplatesStore(
        (state) => state.updateTaxonomies,
    )
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
                            const update = () =>
                                updateTaxonomies({ [taxType]: tax })
                            const isCurrentTax =
                                searchParams?.taxonomies[taxType]?.slug ===
                                tax?.slug
                            return (
                                <SingleTaxItem
                                    key={tax?.slug}
                                    active={isCurrentTax}
                                    tax={tax}
                                    update={update}
                                />
                            )
                        })}
                    </ul>
                </div>
            </PanelRow>
        </PanelBody>
    )
}
