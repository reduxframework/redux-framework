import { Button } from '@wordpress/components'
import { memo } from '@wordpress/element'
import { useEffect, useState } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { user } from '@library/components/icons/'
import { SettingsModal } from '@library/components/modals/settings/SettingsModal'
import { useGlobalStore } from '@library/state/GlobalState'
import { useUserStore } from '@library/state/User'

export const Toolbar = memo(function Toolbar({ className }) {
    const { setOpen, pushModal } = useGlobalStore()
    const loggedIn = useUserStore((state) => state.apiKey.length)
    const { setOpenOnNewPage: setOpenOnNewPageGlobal } = useUserStore()
    const [openOnNewPage, setOpenOnNewPage] = useState(
        window.extendifyData.openOnNewPage === '1',
    )

    useEffect(() => {
        setOpenOnNewPageGlobal(openOnNewPage)
    }, [setOpenOnNewPageGlobal, openOnNewPage])

    return (
        <div className={className}>
            <div className="flex h-full items-center justify-between">
                <div className="flex flex-1 items-center justify-end lg:-mr-1">
                    <label
                        className="mr-8"
                        htmlFor="extendify-open-on-new-pages"
                        title={sprintf(
                            // translators: %s: Extendify Library term
                            __('Toggle %s on new pages', 'extendify'),
                            'Extendify Library',
                        )}>
                        <input
                            id="extendify-open-on-new-pages"
                            className="border border-solid border-gray-900 rounded-sm mr-2"
                            type="checkbox"
                            checked={openOnNewPage}
                            onChange={(e) => setOpenOnNewPage(e.target.checked)}
                        />
                        {__('Open for new pages', 'extendify')}
                    </label>
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
