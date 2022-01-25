import { __ } from '@wordpress/i18n'
import { Button } from '@wordpress/components'
import { useUserStore } from '../../state/User'
import { useGlobalStore } from '../../state/GlobalState'

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
            <div className="flex space-x-2 justify-center items-center">
                <Button
                    variant="link"
                    className="text-black underline hover:no-underline p-0 h-auto"
                    href={`https://extendify.com/welcome/?utm_source=${window.extendifyData.sdk_partner}&utm_medium=library&utm_campaign=welcome-notice&utm_content=tell-me-more`}
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
                            className="text-black underline hover:no-underline p-0 h-auto"
                            onClick={disableLibrary}>
                            {__('Turn off the library', 'extendify')}
                        </Button>
                    </>
                )}
            </div>
        </>
    )
}
