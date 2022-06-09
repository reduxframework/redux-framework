import { Button } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { General } from '@library/api/General'
import { useGlobalStore } from '@library/state/GlobalState'
import { useUserStore } from '@library/state/User'

export default function WelcomeNotice() {
    const setOpen = useGlobalStore((state) => state.setOpen)

    const disableLibrary = () => {
        const button = document.getElementById(
            'extendify-templates-inserter-btn',
        )
        button.classList.add('invisible')
        useUserStore.setState({ enabled: false })
        setOpen(false)
    }

    return (
        <>
            <span className="text-black">
                {__('Welcome to the Extendify Library', 'extendify')}
            </span>
            <span className="px-2 opacity-50" aria-hidden="true">
                &#124;
            </span>
            <div className="flex items-center justify-center space-x-2">
                <Button
                    variant="link"
                    className="h-auto p-0 text-black underline hover:no-underline"
                    href={`https://extendify.com/welcome/?utm_source=${
                        window.extendifyData.sdk_partner
                    }&utm_medium=library&utm_campaign=welcome-notice&utm_content=tell-me-more&utm_group=${useUserStore
                        .getState()
                        .activeTestGroupsUtmValue()}`}
                    onClick={async () =>
                        await General.ping('welcome-notice-tell-me-more-click')
                    }
                    target="_blank">
                    {__('Tell me more', 'extendify')}
                </Button>
                {window.extendifyData.standalone ? null : (
                    <>
                        <span className="font-bold" aria-hidden="true">
                            &bull;
                        </span>
                        <Button
                            variant="link"
                            className="h-auto p-0 text-black underline hover:no-underline"
                            onClick={disableLibrary}>
                            {__('Turn off the library', 'extendify')}
                        </Button>
                    </>
                )}
            </div>
        </>
    )
}
