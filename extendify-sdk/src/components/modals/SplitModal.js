import { Fragment, forwardRef, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { Dialog, Transition } from '@headlessui/react'
import { useGlobalStore } from '@extendify/state/GlobalState'

export const SplitModal = forwardRef(
    (
        {
            onClose,
            isOpen,
            invertedButtonColor,
            children,
            leftContainerBgColor = 'bg-white',
            rightContainerBgColor = 'bg-gray-100',
        },
        initialFocus,
    ) => {
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
                    <div className="fixed inset-0 z-high flex">
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
                                <div className="relative m-8 max-w-md justify-between rounded-sm shadow-modal md:m-0 md:flex md:max-w-2xl">
                                    <button
                                        onClick={onClose}
                                        ref={focusBackup}
                                        className="absolute top-0 right-0 block cursor-pointer rounded-md bg-transparent p-4 text-gray-700 opacity-30 hover:opacity-100"
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
                                    <div
                                        className={`w-7/12 p-12 ${leftContainerBgColor}`}>
                                        {children[0]}
                                    </div>
                                    <div
                                        className={`hidden w-6/12 md:block ${rightContainerBgColor}`}>
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
