import { Icon, close } from '@wordpress/icons'
import { __ } from '@wordpress/i18n'
import { Dialog, Transition } from '@headlessui/react'
import { Fragment, forwardRef, useRef } from '@wordpress/element'
import { useGlobalStore } from '../../state/GlobalState'

export const SplitModal = forwardRef(
    ({ onClose, isOpen, invertedButtonColor, children }, initialFocus) => {
        const focusBackup = useRef(null)
        const defaultClose = useGlobalStore((state) => state.removeAllModals)
        onClose = onClose ?? defaultClose

        return (
            <Transition.Root appear show={true} as={Fragment}>
                <Dialog
                    as="div"
                    static
                    open={isOpen}
                    className="extendify"
                    initialFocus={initialFocus ?? focusBackup}
                    onClose={onClose}>
                    <div className="fixed z-high inset-0 flex">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-50 transition"
                            enterFrom="opacity-0"
                            enterTo="opacity-100">
                            <Dialog.Overlay className="fixed inset-0 bg-black bg-opacity-40 transition-opacity" />
                        </Transition.Child>
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300 translate transform"
                            enterFrom="opacity-0 translate-y-4 sm:translate-y-5"
                            enterTo="opacity-100 translate-y-0">
                            <div className="m-auto">
                                <div className="shadow-modal relative m-8 md:m-0 max-w-md rounded-sm md:flex bg-gray-100 items-center justify-center md:max-w-2xl">
                                    <button
                                        onClick={onClose}
                                        ref={focusBackup}
                                        className="absolute bg-transparent block p-4 top-0 right-0 rounded-md cursor-pointer text-gray-700 opacity-30 hover:opacity-100"
                                        style={
                                            invertedButtonColor && {
                                                filter: 'invert(1)',
                                            }
                                        }>
                                        <span className="sr-only">
                                            {__('Close', 'extendify')}
                                        </span>
                                        <Icon icon={close} />
                                    </button>
                                    <div className="md:w-7/12">
                                        {children[0]}
                                    </div>
                                    <div className="md:justify-none md:w-6/12 hidden md:block ">
                                        {children[1]}
                                    </div>
                                </div>
                            </div>
                        </Transition.Child>
                    </div>
                </Dialog>
            </Transition.Root>
        )
    },
)
