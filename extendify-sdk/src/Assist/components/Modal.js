import { Button } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { Dialog } from '@headlessui/react'
import { useGlobalStore } from '@assist/state/Global'

export const Modal = () => {
    const { modals, popModal } = useGlobalStore()
    const ModalContent = modals[0]

    return (
        <Dialog
            as="div"
            className="extendify-assist extendify-assist-modal"
            open={modals.length > 0}
            onClose={popModal}>
            <div className="absolute top-0 mx-auto w-full h-full overflow-hidden p-2 md:p-6 md:flex justify-center items-center z-high">
                <div
                    className="fixed inset-0 bg-black bg-opacity-40 transition-opacity"
                    aria-hidden="true"
                />
                <Dialog.Title className="sr-only">
                    {__('Assist', 'extendify')}
                </Dialog.Title>
                <div className="sm:flex relative shadow-2xl sm:overflow-hidden mx-auto bg-white flex flex-col p-8 min-w-md">
                    <Button
                        className="absolute top-0 right-0 bg-white inline-flex border-0 p-1 cursor-pointer"
                        onClick={popModal}
                        icon={<Icon icon={close} size={24} />}
                        label={__('Close Modal', 'extendify')}
                        showTooltip={false}
                    />
                    <div className="m-0 text-left relative">
                        {modals?.length > 0 && <ModalContent />}
                    </div>
                </div>
            </div>
        </Dialog>
    )
}
