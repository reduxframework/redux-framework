import { Fragment, useRef } from '@wordpress/element'
import { Dialog, Transition } from '@headlessui/react'
import { useGlobalStore } from '../state/GlobalState'
import Layout from './layout/Layout'
import FooterNotice from '../components/FooterNotice'

export default function MainWindow() {
    const containerRef = useRef(null)
    const open = useGlobalStore((state) => state.open)
    const setOpen = useGlobalStore((state) => state.setOpen)

    return (
        <Transition.Root show={open} as={Fragment}>
            <Dialog
                as="div"
                static
                className="extendify-sdk"
                initialFocus={containerRef}
                onClose={() => setOpen(false)}>
                <div className="h-screen w-screen sm:h-auto m-auto sm:w-auto fixed z-high inset-0 overflow-y-auto">
                    <div className="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
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
                                className="fixed lg:absolute inset-0 lg:overflow-hidden transform transition-all p-2 lg:p-16">
                                <Layout />
                                <FooterNotice />
                            </div>
                        </Transition.Child>
                    </div>
                </div>
            </Dialog>
        </Transition.Root>
    )
}
