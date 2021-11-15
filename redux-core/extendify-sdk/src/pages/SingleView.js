import { useTemplatesStore } from '../state/Templates'
import Single from './parts/Single'
import HasSidebar from './parts/HasSidebar'
import { __ } from '@wordpress/i18n'
import SidebarSingle from './parts/sidebars/SidebarSingle'
import Toolbar from './parts/Toolbar'

export default function SingleView() {
    const activeTemplate = useTemplatesStore(state => state.activeTemplate)
    return <div className="bg-white h-full flex flex-col items-center relative max-w-screen-4xl mx-auto">
        <Toolbar className="w-full h-16 border-solid border-0 border-b border-gray-300 flex-shrink-0"/>
        <div className="w-full flex-grow overflow-hidden">
            <a href="#extendify-templates" className="sr-only focus:not-sr-only focus:text-blue-500">
                {__('Skip to content', 'extendify-sdk')}
            </a>
            <div className="sm:flex sm:space-x-12 relative bg-white mx-auto max-w-screen-4xl h-full">
                <div className="absolute bg-white sm:flex inset-0 z-50 sm:space-x-12">
                    <HasSidebar>
                        <SidebarSingle template={activeTemplate}/>
                        <Single template={activeTemplate}/>
                    </HasSidebar>
                </div>
            </div>
        </div>
    </div>
}
