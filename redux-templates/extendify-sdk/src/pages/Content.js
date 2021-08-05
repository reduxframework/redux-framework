import { useTemplatesStore } from '../state/Templates'
import Filtering from '../components/Filtering'
import TemplatesList from '../components/TemplatesList'
import TemplatesSingle from '../components/TemplatesSingle'
import HasSidebar from '../layout/HasSidebar'
import TypeSelect from '../components/TypeSelect'
import { __ } from '@wordpress/i18n'
import SidebarSingle from '../layout/SidebarSingle'
import TaxonomyBreadcrumbs from '../components/TaxonomyBreadcrumbs'

export default function Content({ className }) {
    const templates = useTemplatesStore(state => state.templates)
    const activeTemplate = useTemplatesStore(state => state.activeTemplate)
    return <div className={className}>
        <a href="#extendify-templates" className="sr-only focus:not-sr-only focus:text-blue-500">
            {__('Skip to content', 'extendify-sdk')}
        </a>
        <div className="sm:flex sm:space-x-12 relative bg-white mx-auto max-w-screen-4xl h-full">
            {!!Object.keys(activeTemplate).length &&
                <div className="absolute bg-white sm:flex inset-0 z-50 sm:space-x-12">
                    <HasSidebar>
                        <SidebarSingle template={activeTemplate}/>
                        <TemplatesSingle template={activeTemplate}/>
                    </HasSidebar>
                </div>
            }
            <HasSidebar>
                <Filtering/>
                <>
                    <TypeSelect/>
                    {/* TODO: we may want to inject this as a portal so it can directly share state with Filtering.js */}
                    <TaxonomyBreadcrumbs/>
                    <div className="relative h-full z-30 bg-white">
                        <div className="absolute z-20 inset-0 lg:static h-screen lg:h-full overflow-y-auto pt-4 sm:pt-0 px-6 sm:pl-0 sm:pr-8">
                            <TemplatesList templates={templates}/>
                        </div>
                    </div>
                </>
            </HasSidebar>
        </div>
    </div>
}
