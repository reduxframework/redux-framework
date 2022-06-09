import { useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { useGlobalStore } from '@library/state/GlobalState'
import { Modal } from '../Modal'
import { DevSettings } from './DevSettings'
import LoginInterface from './LoginInterface'

export const SettingsModal = () => {
    const initialFocus = useRef(null)
    const actionCallback = useGlobalStore((state) => state.removeAllModals)

    return (
        <Modal
            heading={__('Settings', 'extendify')}
            isOpen={true}
            ref={initialFocus}>
            <div className="flex justify-center flex-col divide-y">
                <DevSettings />
                <LoginInterface
                    initialFocus={initialFocus}
                    actionCallback={actionCallback}
                />
            </div>
        </Modal>
    )
}
