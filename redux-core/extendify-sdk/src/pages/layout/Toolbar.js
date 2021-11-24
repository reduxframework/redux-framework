import { __ } from '@wordpress/i18n'
import { useState } from '@wordpress/element'
import { Icon, close } from '@wordpress/icons'
import { Button } from '@wordpress/components'

import TypeSelect from '../../components/TypeSelect'
import { useGlobalStore } from '../../state/GlobalState'
import { user } from '../../components/icons/'
import SettingsModal from '../../components/modals/SettingsModal'
import { brandMark } from '../../components/icons/'

export default function Toolbar({ className }) {
    const setOpen = useGlobalStore((state) => state.setOpen)
    const [openModal, setOpenModal] = useState(false)

    return (
        <div className={className}>
            <div className="flex justify-between items-center px-6 sm:pl-6 sm:pr-12 h-full">
                <div className="flex space-x-12 h-full">
                    <div className="bg-transparent flex items-center space-x-1.5 lg:w-72 text-extendify-black">
                        <Icon icon={brandMark} size={40} />
                    </div>
                </div>
                <TypeSelect />
                <div className="space-x-2 transform sm:translate-x-6 flex">
                    <Button
                        onClick={() => setOpenModal(true)}
                        icon={<Icon icon={user} size={24} />}
                        label={__('Settings', 'extendify-sdk')}
                    />

                    {openModal && (
                        <SettingsModal
                            isOpen={openModal}
                            onClose={() => setOpenModal(false)}
                        />
                    )}

                    <Button
                        onClick={() => setOpen(false)}
                        icon={<Icon icon={close} size={24} />}
                        label={__('Close library', 'extendify-sdk')}
                    />
                </div>
            </div>
        </div>
    )
}
