import { __ } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { memo } from '@wordpress/element'
import { Button } from '@wordpress/components'
import { TypeSelect } from '../../components/TypeSelect'
import { useGlobalStore } from '../../state/GlobalState'
import { user } from '../../components/icons/'
import SettingsModal from '../../components/modals/SettingsModal'
import { useUserStore } from '../../state/User'

export const Toolbar = memo(function Toolbar({ className }) {
    const setOpen = useGlobalStore((state) => state.setOpen)
    const pushModal = useGlobalStore((state) => state.pushModal)
    const loggedIn = useUserStore((state) => state.apiKey.length)

    return (
        <div className={className}>
            <div className="flex justify-between items-center h-full">
                <div className="flex-1"></div>
                <TypeSelect className="flex-1 flex items-center justify-center" />
                <div className="flex-1 flex justify-end items-center">
                    <Button
                        onClick={() => pushModal(<SettingsModal />)}
                        icon={<Icon icon={user} size={24} />}
                        label={__('Login and settings area', 'extendify')}>
                        {loggedIn ? '' : __('Sign in', 'extendify')}
                    </Button>
                    <Button
                        onClick={() => setOpen(false)}
                        icon={<Icon icon={close} size={24} />}
                        label={__('Close library', 'extendify')}
                    />
                </div>
            </div>
        </div>
    )
})
