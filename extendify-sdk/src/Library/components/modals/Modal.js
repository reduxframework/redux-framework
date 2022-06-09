import { Button } from '@wordpress/components'
import { Fragment, useRef, forwardRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { Dialog, Transition } from '@headlessui/react'
import { useGlobalStore } from '@library/state/GlobalState'

export const Modal = forwardRef(
    ({ isOpen, heading, onClose, children }, initialFocus) => {
        const focusBackup = useRef(null)
        const defaultClose = useGlobalStore((state) => state.removeAllModals)
        onClose = onClose ?? defaultClose

        return (
            <Transition
                appear
                show={isOpen}
                as={Fragment}
                className="extendify">
                <Dialog
                    initialFocus={initialFocus ?? focusBackup}
                    onClose={onClose}>
                    <div className="fixed inset-0 z-high flex">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-200 transition"
                            enterFrom="opacity-0"
                            enterTo="opacity-100">
                            <Dialog.Overlay className="fixed inset-0 bg-black bg-opacity-40" />
                        </Transition.Child>
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300 translate transform"
                            enterFrom="opacity-0 translate-y-4 sm:translate-y-5"
                            enterTo="opacity-100 translate-y-0">
                            <div className="relative m-auto w-full">
                                <div className="relative m-auto w-full max-w-lg items-center justify-center rounded-sm bg-white shadow-modal">
                                    {heading ? (
                                        <div className="flex items-center justify-between border-b py-2 pl-6 pr-3 leading-none">
                                            <span className="whitespace-nowrap text-base text-extendify-black">
                                                {heading}
                                            </span>
                                            <CloseButton onClick={onClose} />
                                        </div>
                                    ) : (
                                        <div className="absolute top-0 right-0 block px-4 py-4 ">
                                            <CloseButton
                                                ref={focusBackup}
                                                onClick={onClose}
                                            />
                                        </div>
                                    )}
                                    <div>{children}</div>
                                </div>
                            </div>
                        </Transition.Child>
                    </div>
                </Dialog>
            </Transition>
        )
    },
)

const CloseButton = forwardRef((props, focusRef) => {
    return (
        <Button
            {...props}
            icon={<Icon icon={close} />}
            ref={focusRef}
            className="text-extendify-black opacity-75 hover:opacity-100"
            showTooltip={false}
            label={__('Close dialog', 'extendify')}
        />
    )
})
