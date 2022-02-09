import { __ } from '@wordpress/i18n'
import { Sidebar } from '../Sidebar'
import HasSidebar from './HasSidebar'
import { Toolbar } from './Toolbar'
import { GridView } from '../GridView'
import { Button } from '@wordpress/components'
import { useRef, useEffect, useState, useCallback } from '@wordpress/element'
import { useTemplatesStore } from '../../state/Templates'
import { useWhenIdle } from '../../hooks/helpers'

export const Layout = ({ setOpen }) => {
    const gridContainer = useRef()
    const searchParams = useTemplatesStore((state) => state.searchParams)
    const [showIdleScreen, setShowIdleScreen] = useState(false)
    const resetTemplates = useTemplatesStore((state) => state.resetTemplates)
    const idle = useWhenIdle(600_000) // 10 minutes
    const removeIdleScreen = useCallback(() => {
        setShowIdleScreen(false)
        resetTemplates()
    }, [resetTemplates])

    useEffect(() => {
        if (idle) setShowIdleScreen(true)
    }, [idle])
    useEffect(() => {
        setShowIdleScreen(false)
    }, [searchParams])
    useEffect(() => {
        gridContainer.current.scrollTop = 0
    }, [searchParams])
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
                    className="extendify-skip-to-sr-link sr-only focus:not-sr-only focus:text-blue-500">
                    {__('Skip to templates', 'extendify')}
                </button>
                <div className="sm:flex relative mx-auto h-full">
                    <HasSidebar>
                        <Sidebar />
                        <div className="relative h-full z-30 flex flex-col">
                            <Toolbar
                                className="hidden sm:block w-full h-20 flex-shrink-0 px-6 md:px-8"
                                hideLibrary={() => setOpen(false)}
                            />
                            <div
                                ref={gridContainer}
                                className="flex-grow z-20 overflow-y-auto px-6 md:px-8">
                                {showIdleScreen ? (
                                    <IdleScreen callback={removeIdleScreen} />
                                ) : (
                                    <GridView />
                                )}
                            </div>
                        </div>
                    </HasSidebar>
                </div>
            </div>
        </div>
    )
}

const IdleScreen = ({ callback }) => (
    <div className="flex flex-col items-center justify-center h-full">
        <p className="text-sm text-extendify-gray font-normal mb-6">
            {__("We've added new stuff while you were away.", 'extendify')}
        </p>
        <Button
            className="components-button bg-wp-theme-500 hover:bg-wp-theme-600 border-color-wp-theme-500 text-white"
            onClick={callback}>
            {__('Reload')}
        </Button>
    </div>
)
