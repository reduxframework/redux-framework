import { useRef, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { GridView } from '@extendify/pages/GridView'
import { Sidebar } from '@extendify/pages/Sidebar'
import { useTemplatesStore } from '@extendify/state/Templates'
import HasSidebar from './HasSidebar'
import { Toolbar } from './Toolbar'

export const Layout = ({ setOpen }) => {
    const gridContainer = useRef()
    const searchParams = useTemplatesStore((state) => state.searchParams)

    useEffect(() => {
        if (!gridContainer.current) return
        gridContainer.current.scrollTop = 0
    }, [searchParams])

    return (
        <div className="relative mx-auto flex h-full max-w-screen-4xl flex-col items-center">
            <div className="w-full flex-grow overflow-hidden">
                <button
                    onClick={() =>
                        document
                            .getElementById('extendify-templates')
                            .querySelector('button')
                            .focus()
                    }
                    className="extendify-skip-to-sr-link sr-only focus:not-sr-only focus:text-blue-500">
                    {__('Skip to templates', 'extendify')}
                </button>
                <div className="relative mx-auto h-full sm:flex">
                    <HasSidebar>
                        <Sidebar />
                        <div className="relative z-30 flex h-full flex-col">
                            <Toolbar
                                className="hidden h-12 w-full flex-shrink-0 px-6 sm:block md:px-8"
                                hideLibrary={() => setOpen(false)}
                            />
                            <div
                                ref={gridContainer}
                                className="z-20 flex-grow overflow-y-auto px-6 md:px-8">
                                <GridView />
                            </div>
                        </div>
                    </HasSidebar>
                </div>
            </div>
        </div>
    )
}
