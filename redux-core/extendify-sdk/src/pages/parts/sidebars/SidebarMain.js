import { useTemplatesStore } from '../../../state/Templates'
import { Panel } from '@wordpress/components'
import TaxonomySection from '../../../components/TaxonomySection'
import { useTaxonomyStore } from '../../../state/Taxonomies'
import SiteTypeSelector from '../../../components/SiteTypeSelector'
import { useUserStore } from '../../../state/User'

export default function SidebarMain() {
    const taxonomies = useTaxonomyStore(state => state.taxonomies)
    const searchParams = useTemplatesStore(state => state.searchParams)
    const updateSiteType = useUserStore(state => state.updateSiteType)
    const updateTaxonomies = useTemplatesStore(state => state.updateTaxonomies)

    return <>
        <div className="mt-px bg-white mb-8 mx-6 pt-6 lg:mx-0 lg:pt-0">
            {Object.keys(taxonomies?.tax_categories ?? {}).length > 0 && <SiteTypeSelector
                value={searchParams?.taxonomies?.tax_categories ?? ''}
                setValue={(term) => {
                    updateSiteType(term)
                    updateTaxonomies({ tax_categories: term })
                }}
                terms={taxonomies.tax_categories} />}
        </div>
        <div className="mt-px flex-grow hidden overflow-y-auto pb-32 pr-2 pt-px sm:block">
            <Panel>
                {Object.entries(taxonomies).map((taxonomy) => {
                    // Tax categories has been extracted to display above
                    if (taxonomy[0] === 'tax_categories') return null
                    return <TaxonomySection
                        key={taxonomy[0]}
                        taxonomy={taxonomy} />
                })}
            </Panel>
        </div>
    </>
}
