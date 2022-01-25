import { useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { useGlobalStore } from '../../state/GlobalState'
import LoginInterface from '../LoginInterface'
import { Modal } from './Modal'

export default function SettingsModal() {
    const initialFocus = useRef(null)
    const actionCallback = useGlobalStore((state) => state.removeAllModals)

    return (
        <Modal
            heading={__('Settings', 'extendify')}
            isOpen={true}
            ref={initialFocus}>
            <div className="flex p-6 justify-center">
                <LoginInterface
                    initialFocus={initialFocus}
                    actionCallback={actionCallback}
                />
            </div>
        </Modal>
    )
}
