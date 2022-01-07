/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n'

/**
 * Internal dependencies
 */
import Sidebar from '../Sidebar'
import HasSidebar from './HasSidebar'
import { Toolbar } from './Toolbar'
import GridView from '../GridView'

export default function Layout({ setOpen }) {
    return (
        <div className="h-full flex flex-col items-center relative max-w-screen-4xl mx-auto">
            <div className="w-full flex-grow overflow-hidden">
                <button
                    onClick={() =>
                        document
                            .getElementById('extendify-templates')
                            .querySelector('button')
                            .focus()
                    }
                    className="sr-only focus:not-sr-only focus:text-blue-500">
                    {__('Skip to content', 'extendify')}
                </button>
                <div className="sm:flex relative mx-auto h-full">
                    <HasSidebar>
                        <Sidebar />
                        <div className="relative h-full z-30 flex flex-col">
                            <Toolbar
                                className="hidden sm:block w-full h-20 flex-shrink-0 px-6 md:px-8"
                                hideLibrary={() => setOpen(false)}
                            />
                            <div className="flex-grow z-20 overflow-y-auto px-6 md:px-8">
                                <GridView />
                            </div>
                        </div>
                    </HasSidebar>
                </div>
            </div>
        </div>
    )
}
