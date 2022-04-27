import { Button } from '@wordpress/components'
import { memo } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { user } from '@extendify/components/icons/'
import { SettingsModal } from '@extendify/components/modals/settings/SettingsModal'
import { useGlobalStore } from '@extendify/state/GlobalState'
import { useUserStore } from '@extendify/state/User'

export const Toolbar = memo(function Toolbar({ className }) {
    const setOpen = useGlobalStore((state) => state.setOpen)
    const pushModal = useGlobalStore((state) => state.pushModal)
    const loggedIn = useUserStore((state) => state.apiKey.length)

    return (
        <div className={className}>
            <div className="flex h-full items-center justify-between">
                <div className="flex flex-1 items-center justify-end lg:-mr-1">
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
