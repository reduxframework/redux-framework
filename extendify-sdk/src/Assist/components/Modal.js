import { Button } from '@wordpress/components'
import { useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { Dialog } from '@headlessui/react'
import { useGlobalStore } from '@assist/state/Global'

export const Modal = () => {
    const { modals, popModal } = useGlobalStore()
    const ModalContent = modals[0]
    const [title, setTitle] = useState('')

    useEffect(() => {
        if (!modals[0]) setTitle('')
    }, [modals])

    return (
        <Dialog
            as="div"
            className="extendify-assist"
            open={modals.length > 0}
            onClose={popModal}>
            <div className="fixed top-0 mx-auto w-full h-full overflow-hidden p-2 md:p-6 md:flex justify-center items-center z-high">
                <div
                    className="fixed inset-0 bg-black bg-opacity-40 transition-opacity"
                    aria-hidden="true"
                />
                <div className="sm:flex relative shadow-2xl sm:overflow-hidden mx-auto bg-white flex flex-col min-w-md rounded-sm">
                    <div className="flex items-center justify-between">
                        <Dialog.Title className="m-0 px-6 text-base text-gray-900">
                            {title}
                        </Dialog.Title>
                        <Button
                            className="border-0 cursor-pointer m-4"
                            onClick={popModal}
                            icon={<Icon icon={close} size={24} />}
                            label={__('Close Modal', 'extendify')}
                            showTooltip={false}
                        />
                    </div>
                    <div className="m-0 p-6 pt-0 text-left relative">
                        {modals?.length > 0 && (
                            <ModalContent
                                popModal={popModal}
                                setModalTitle={setTitle}
                            />
                        )}
                    </div>
                </div>
            </div>
        </Dialog>
    )
}
