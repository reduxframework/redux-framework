import { memo } from '@wordpress/element'
import { useTemplatesStore } from '../state/Templates'
import { Panel } from '@wordpress/components'
import TaxonomySection from '../components/TaxonomySection'
import { useTaxonomyStore } from '../state/Taxonomies'
import { SiteTypeSelector } from '../components/SiteTypeSelector'
import { useUserStore } from '../state/User'
import { ImportCounter } from '../components/ImportCounter'
import { brandMark } from '../components/icons/'
import { Icon } from '@wordpress/icons'
import { featured } from '../components/icons'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'

export const Sidebar = memo(function Sidebar() {
    const taxonomies = useTaxonomyStore((state) => state.taxonomies)
    const searchParams = useTemplatesStore((state) => state.searchParams)
    const updatePreferredSiteType = useUserStore(
        (state) => state.updatePreferredSiteType,
    )
    const updateTaxonomies = useTemplatesStore(
        (state) => state.updateTaxonomies,
    )
    const apiKey = useUserStore((state) => state.apiKey)
    const taxonomyType =
        searchParams.type === 'pattern' ? 'patternType' : 'layoutType'
    const isFeatured = !searchParams?.taxonomies[taxonomyType]?.slug?.length

    return (
        <>
            <div className="hidden sm:flex px-5 -ml-1.5 text-extendify-black">
                <Icon icon={brandMark} size={40} />
            </div>
            <div className="px-5">
                <button
                    onClick={() =>
                        updateTaxonomies({
                            [taxonomyType]: { slug: '', title: 'Featured' },
                        })
                    }
                    className={classNames(
                        'text-left text-sm cursor-pointer w-full flex items-center px-0 py-2 m-0 leading-none bg-transparent hover:text-wp-theme-500 transition duration-200 button-focus space-x-1',
                        { 'text-wp-theme-500': isFeatured },
                    )}>
                    <Icon icon={featured} size={24} />
                    <span className="text-sm">
                        {__('Featured', 'extendify')}
                    </span>
                </button>
            </div>
            <div className="sm:mb-8 mx-6 sm:mx-0 sm:mt-0 pt-0.5 px-5">
                {Object.keys(taxonomies?.siteType ?? {}).length > 0 && (
                    <SiteTypeSelector
                        value={searchParams?.taxonomies?.siteType ?? ''}
                        setValue={(termData) => {
                            updatePreferredSiteType(termData)
                            updateTaxonomies({ siteType: termData })
                        }}
                        terms={taxonomies.siteType}
                    />
                )}
            </div>
            <div className="mt-px flex-grow hidden overflow-y-auto pb-32 pt-px sm:block">
                <Panel className="bg-transparent">
                    <TaxonomySection
                        taxType={taxonomyType}
                        taxonomies={taxonomies[taxonomyType]}
                    />
                </Panel>
            </div>
            {!apiKey.length && (
                <div className="px-5">
                    <ImportCounter />
                </div>
            )}
        </>
    )
})
