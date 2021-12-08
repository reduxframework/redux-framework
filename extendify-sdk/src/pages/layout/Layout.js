/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n'

/**
 * Internal dependencies
 */
import Sidebar from '../Sidebar'
import HasSidebar from './HasSidebar'
import Toolbar from './Toolbar'
import GridView from '../GridView'

export default function Layout({ setOpen }) {
    return (
        <div className="bg-white h-full flex flex-col items-center relative max-w-screen-4xl mx-auto">
            <Toolbar
                className="w-full h-20 flex-shrink-0"
                hideLibrary={() => setOpen(false)}
            />
            <div className="w-full flex-grow overflow-hidden">
                <button
                    onClick={() =>
                        document
                            .getElementById('extendify-templates')
                            .querySelector('button')
                            .focus()
                    }
                    className="sr-only focus:not-sr-only focus:text-blue-500">
                    {__('Skip to content', 'extendify-sdk')}
                </button>
                <div className="sm:flex sm:space-x-12 relative mx-auto h-full">
                    <HasSidebar>
                        <Sidebar />
                        <div className="relative h-full z-30">
                            <div className="absolute z-20 inset-0 lg:static h-screen overflow-y-auto pt-4 sm:pt-0 px-6 sm:pl-0 sm:pr-8 pb-40">
                                <GridView />
                            </div>
                        </div>
                    </HasSidebar>
                </div>
            </div>
        </div>
    )
}
