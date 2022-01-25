import { Dialog, Transition } from '@headlessui/react'
import { Fragment, useRef, forwardRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { Button } from '@wordpress/components'
import { useGlobalStore } from '../../state/GlobalState'

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
                    <div className="fixed z-high inset-0 flex">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-200 transition"
                            enterFrom="opacity-0"
                            enterTo="opacity-100">
                            <Dialog.Overlay className="fixed inset-0 bg-white bg-opacity-40" />
                        </Transition.Child>
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300 translate transform"
                            enterFrom="opacity-0 translate-y-4 sm:translate-y-5"
                            enterTo="opacity-100 translate-y-0">
                            <div className="m-auto relative w-full">
                                <div className="bg-white shadow-modal items-center justify-center m-auto max-w-lg relative rounded-sm w-full">
                                    {heading ? (
                                        <div className="border-b flex justify-between items-center leading-none pl-8 py-2 pr-3">
                                            <span className="text-base text-extendify-black whitespace-nowrap">
                                                {heading}
                                            </span>
                                            <CloseButton onClick={onClose} />
                                        </div>
                                    ) : (
                                        <div className="absolute block px-4 py-4 top-0 right-0 ">
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
