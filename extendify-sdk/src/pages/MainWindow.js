import { Fragment, useRef } from '@wordpress/element'
import { Dialog, Transition } from '@headlessui/react'
import FooterNotice from '@extendify/components/notices/FooterNotice'
import { useModal } from '@extendify/hooks/useModal'
import { useTestGroup } from '@extendify/hooks/useTestGroup'
import { useGlobalStore } from '@extendify/state/GlobalState'
import { Layout } from './layout/Layout'

export default function MainWindow() {
    const containerRef = useRef(null)
    const open = useGlobalStore((state) => state.open)
    const setOpen = useGlobalStore((state) => state.setOpen)
    const modal = useModal(open)
    const ready = useGlobalStore((state) => state.ready)
    const footerNoticePosition = useTestGroup('notice-position', ['A', 'B'])

    return (
        <Transition appear show={open} as={Fragment}>
            <Dialog
                as="div"
                static
                className="extendify"
                initialFocus={containerRef}
                onClose={() => setOpen(false)}>
                <div className="fixed inset-0 z-high m-auto h-screen w-screen overflow-y-auto sm:h-auto sm:w-auto">
                    <div className="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300"
                            enterFrom="opacity-0"
                            enterTo="opacity-100">
                            <Dialog.Overlay className="fixed inset-0 bg-black bg-opacity-40 transition-opacity" />
                        </Transition.Child>
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300"
                            enterFrom="opacity-0 translate-y-4 sm:translate-y-5"
                            enterTo="opacity-100 translate-y-0">
                            <div
                                ref={containerRef}
                                tabIndex="0"
                                onClick={(e) =>
                                    e.target === e.currentTarget &&
                                    setOpen(false)
                                }
                                className="fixed inset-0 transform p-2 transition-all lg:absolute lg:overflow-hidden lg:p-16">
                                {footerNoticePosition === 'B' && (
                                    <FooterNotice className="-mt-6" />
                                )}
                                <Layout />
                                {ready ? (
                                    <>
                                        {footerNoticePosition === 'A' && (
                                            <FooterNotice />
                                        )}
                                        {modal}
                                    </>
                                ) : null}
                            </div>
                        </Transition.Child>
                    </div>
                </div>
            </Dialog>
        </Transition>
    )
}
