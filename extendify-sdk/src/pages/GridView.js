import SidebarMain from './parts/sidebars/SidebarMain'
import Grid from './parts/Grid'
import HasSidebar from './parts/HasSidebar'
import TypeSelect from '../components/TypeSelect'
import { __ } from '@wordpress/i18n'
import TaxonomyBreadcrumbs from '../components/TaxonomyBreadcrumbs'
import Toolbar from './parts/Toolbar'

export default function GridView() {
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
                            <div className="absolute z-20 inset-0 lg:static h-screen overflow-y-auto pt-4 sm:pt-0 px-6 sm:pl-0 sm:pr-8 pb-40">
                                <Grid/>
                            </div>
                        </div>
                    </>
                </HasSidebar>
            </div>
        </div>
    </div>
}
