import { __ } from '@wordpress/i18n'
import { Icon, closeSmall } from '@wordpress/icons'
import { Button } from '@wordpress/components'
import { useUserStore } from '../state/User'
import { useState } from '@wordpress/element'
import { useGlobalStore } from '../state/GlobalState'

export default function WelcomeNotice() {
    const [visible, setVisible] = useState(!useUserStore.getState().noticesDismissedAt?.welcome)
    const setOpen = useGlobalStore(state => state.setOpen)

    const dismiss = () => {
        setVisible(false)
        useUserStore.setState({
            noticesDismissedAt: {
                welcome: (new Date).toISOString(),
            },
        })
    }

    const disableLibrary = () => {
        const button = document.getElementById('extendify-templates-inserter-btn')
        button.classList.add('invisible')
        useUserStore.setState({ enabled: false })
        setOpen(false)
    }

    if(!visible) return null

    return <div className="bg-extendify-secondary hidden lg:flex space-x-4 py-3 px-5 justify-center items-center relative">
        <span className='text-black'>
            { __('Welcome to the Extendify Library', 'extendify-sdk') }
        </span>
        <span className="px-2 opacity-50" aria-hidden="true">&#124;</span>
        <div className='flex space-x-2 justify-center items-center'>
            <Button
                variant="link"
                className="text-black underline hover:no-underline p-0 h-auto"
                href={`https://extendify.com/welcome/?utm_source=${window.extendifySdkData.sdk_partner}&utm_medium=library&utm_campaign=welcome-notice&utm_content=tell-me-more`}
                target="_blank"
            >
                { __('Tell me more', 'extendify-sdk') }
            </Button>
            <span className="font-bold" aria-hidden="true">&bull;</span>
            <Button
                variant="link"
                className="text-black underline hover:no-underline p-0 h-auto"
                onClick={ disableLibrary }
            >
                { __('Turn off the library', 'extendify-sdk') }
            </Button>
        </div>
        <div className="absolute right-1">
            <Button
                className="opacity-50 hover:opacity-100 focus:opacity-100 text-extendify-black"
                icon={ <Icon icon={ closeSmall } /> }
                label={ __('Dismiss this notice', 'extendify-sdk') }
                onClick={ dismiss }
                showTooltip={ false }
            />
        </div>
    </div>
}
