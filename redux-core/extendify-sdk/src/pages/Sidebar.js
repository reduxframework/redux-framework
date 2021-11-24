import { useTemplatesStore } from '../state/Templates'
import { Panel } from '@wordpress/components'
import TaxonomySection from '../components/TaxonomySection'
import { useTaxonomyStore } from '../state/Taxonomies'
import SiteTypeSelector from '../components/SiteTypeSelector'
import { useUserStore } from '../state/User'

export default function SidebarMain() {
    const taxonomies = useTaxonomyStore((state) => state.taxonomies)
    const searchParams = useTemplatesStore((state) => state.searchParams)
    const updateSiteType = useUserStore((state) => state.updateSiteType)
    const updateTaxonomies = useTemplatesStore(
        (state) => state.updateTaxonomies,
    )

    return (
        <>
            <div className="mb-8 mt-2 mx-6 sm:mx-0 sm:mt-0 pt-0.5">
                {Object.keys(taxonomies?.tax_categories ?? {}).length > 0 && (
                    <SiteTypeSelector
                        value={searchParams?.taxonomies?.tax_categories ?? ''}
                        setValue={(term) => {
                            updateSiteType(term)
                            updateTaxonomies({ tax_categories: term })
                        }}
                        terms={taxonomies.tax_categories}
                    />
                )}
            </div>
            <div className="mt-px flex-grow hidden overflow-y-auto pb-32 pt-px sm:block">
                <Panel>
                    {Object.entries(taxonomies).map((taxonomy) => {
                        // Tax categories has been extracted to display above
                        if (taxonomy[0] === 'tax_categories') return null
                        return (
                            <TaxonomySection
                                key={taxonomy[0]}
                                taxonomy={taxonomy}
                            />
                        )
                    })}
                </Panel>
            </div>
        </>
    )
}
