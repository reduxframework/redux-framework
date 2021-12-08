import { Icon, close } from '@wordpress/icons'
import { __ } from '@wordpress/i18n'
import { Dialog, Transition } from '@headlessui/react'
import { Fragment } from '@wordpress/element'

export default function SplitModal({ onRequestClose, isOpen, left, right }) {
    return (
        <Transition.Root appear show={true} as={Fragment}>
            <Dialog
                as="div"
                static
                open={isOpen}
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
                        <div className="m-auto">
                            <div className="shadow-modal relative m-8 md:m-0 max-w-md rounded-sm md:flex bg-gray-100 items-center justify-center md:max-w-2xl">
                                <button
                                    onClick={onRequestClose}
                                    className="absolute bg-transparent block p-4 top-0 right-0 rounded-md cursor-pointer text-gray-700 opacity-30 hover:opacity-100">
                                    <span className="sr-only">
                                        {__('Close', 'extendify-sdk')}
                                    </span>
                                    <Icon icon={close} />
                                </button>
                                <div className="rounded-md md:rounded-l-md md:rounded-tr-none bg-white p-12 text-center md:w-7/12 items-center">
                                    {left}
                                </div>
                                <div className="justify-center md:justify-none md:w-6/12 p-10 text-black hidden md:block ">
                                    {right}
                                </div>
                            </div>
                        </div>
                    </Transition.Child>
                </div>
            </Dialog>
        </Transition.Root>
    )
}
