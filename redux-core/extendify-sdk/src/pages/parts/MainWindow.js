import { Fragment, useRef } from '@wordpress/element'
import { Dialog, Transition } from '@headlessui/react'
import { useGlobalStore } from '../../state/GlobalState'
import Router from '../Router'
import WelcomeNotice from '../../components/WelcomeNotice'

export default function MainWindow() {
    const containerRef = useRef(null)
    const open = useGlobalStore(state => state.open)
    const setOpen = useGlobalStore(state => state.setOpen)
    const currentPage = useGlobalStore(state => state.currentPage)

    return (
        <Transition.Root show={open} as={Fragment}>
            <Dialog
                as="div"
                static
                className="extendify-sdk"
                initialFocus={containerRef}
                onClose={() => setOpen(false)}
            >
                <div className="h-screen w-screen sm:h-auto m-auto sm:w-auto fixed z-high inset-0 overflow-y-auto">
                    <div className="flex h-full overflow-hidden p-4 lg:p-20 text-center w-full">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-50 transition"
                            enterFrom="opacity-0"
                            enterTo="opacity-100"
                        >
                            <Dialog.Overlay className="fixed inset-0 bg-black bg-opacity-30 transition-opacity" />
                        </Transition.Child>
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300 translate transform"
                            enterFrom="opacity-0 translate-y-4 sm:translate-y-5"
                            enterTo="opacity-100 translate-y-0"
                        >
                            <div
                                ref={containerRef}
                                tabIndex="0"
                                className="max-w-8xl mx-auto w-full">
                                <Router page={currentPage} />
                                <WelcomeNotice />
                            </div>
                        </Transition.Child>
                    </div>
                </div>
            </Dialog>
        </Transition.Root>
    )
}
