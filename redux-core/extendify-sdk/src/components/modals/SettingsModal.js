import { useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import LoginInterface from '../LoginInterface'
import { Modal } from './Modal'

export default function SettingsModal({ isOpen, onClose }) {
    const initialFocus = useRef(null)

    return (
        <Modal
            heading={__('Settings', 'extendify-sdk')}
            isOpen={isOpen}
            ref={initialFocus}
            onRequestClose={onClose}>
            <div className="flex p-6 justify-center">
                <LoginInterface
                    initialFocus={initialFocus}
                    actionCallback={onClose}
                />
            </div>
        </Modal>
    )
}
