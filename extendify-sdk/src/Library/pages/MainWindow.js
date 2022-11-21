import { useSelect, dispatch } from '@wordpress/data'
import { useRef, useLayoutEffect } from '@wordpress/element'
import { Dialog } from '@headlessui/react'
import FooterNotice from '@library/components/notices/FooterNotice'
import { useModal } from '@library/hooks/useModal'
import { useGlobalStore } from '@library/state/GlobalState'
import { Layout } from './layout/Layout'

export default function MainWindow() {
    const { open, setOpen, ready } = useGlobalStore()
    const containerRef = useRef(null)
    const modal = useModal(open)
    const welcomeScreenOpen = useSelect((select) =>
        select('core/edit-post')?.isFeatureActive('welcomeGuide'),
    )

    useLayoutEffect(() => {
        if (!open) return
        // Disable the welcome guide if open
        if (welcomeScreenOpen) {
            dispatch('core/edit-post').toggleFeature('welcomeGuide')
        }
    }, [open, welcomeScreenOpen])

    return (
        <Dialog
            as="div"
            className="extendify"
            initialFocus={containerRef}
            open={open}
            onClose={() => setOpen(false)}>
            <div className="fixed inset-0 bg-black bg-opacity-40 transition-opacity" />
            <div className="fixed inset-0 z-high m-auto h-screen w-screen overflow-y-auto sm:h-auto sm:w-auto">
                <div className="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div
                        ref={containerRef}
                        tabIndex="0"
                        onClick={(e) =>
                            e.target === e.currentTarget && setOpen(false)
                        }
                        className="fixed inset-0 transform p-2 transition-all lg:absolute lg:overflow-hidden lg:p-16">
                        <Layout />
                        {ready ? (
                            <>
                                <FooterNotice />
                                {modal}
                            </>
                        ) : null}
                    </div>
                </div>
            </div>
        </Dialog>
    )
}
