import { Dialog, Transition } from '@headlessui/react'
import { Fragment, useRef, forwardRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { Button } from '@wordpress/components'

const CloseButton = (props) => {
    return (
        <Button
            {...props}
            icon={<Icon icon={close} />}
            className="text-extendify-black opacity-75 hover:opacity-100"
            showTooltip={false}
            label={__('Close dialog', 'extendify-sdk')}
        />
    )
}

export const Modal = forwardRef(
    ({ isOpen, heading, onRequestClose, children }, initialFocus) => {
        const focusBackup = useRef(null)

        return (
            <Transition.Root appear show={isOpen} as={Fragment}>
                <Dialog
                    as="div"
                    static
                    open={isOpen}
                    initialFocus={initialFocus ?? focusBackup}
                    className="extendify-sdk"
                    onClose={onRequestClose}>
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
                            <div className="m-auto relative w-full">
                                <div className="bg-white shadow-modal items-center justify-center m-auto max-w-lg relative rounded-sm w-full">
                                    {heading ? (
                                        <div className="border-b flex justify-between items-center leading-none pl-6 py-2 pr-3">
                                            <span className="text-base text-extendify-black whitespace-nowrap">
                                                {heading}
                                            </span>
                                            <CloseButton
                                                onClick={onRequestClose}
                                            />
                                        </div>
                                    ) : (
                                        <div className="absolute block px-6 py-4 top-0 right-0 ">
                                            <CloseButton
                                                ref={focusBackup}
                                                onClick={onRequestClose}
                                            />
                                        </div>
                                    )}
                                    <div>{children}</div>
                                </div>
                            </div>
                        </Transition.Child>
                    </div>
                </Dialog>
            </Transition.Root>
        )
    },
)
