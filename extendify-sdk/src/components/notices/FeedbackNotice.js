import { __ } from '@wordpress/i18n'
import { Button } from '@wordpress/components'

export default function FeedbackNotice() {
    return (
        <>
            <span className="text-black">
                {__(
                    'Tell us how to make the Extendify Library work better for you',
                    'extendify',
                )}
            </span>
            <span className="px-2 opacity-50" aria-hidden="true">
                &#124;
            </span>
            <div className="flex space-x-2 justify-center items-center">
                <Button
                    variant="link"
                    className="text-black underline hover:no-underline p-0 h-auto"
                    href={`https://extendify.com/feedback/?utm_source=${window.extendifyData.sdk_partner}&utm_medium=library&utm_campaign=feedback-notice&utm_content=give-feedback`}
                    target="_blank">
                    {__('Give feedback', 'extendify')}
                </Button>
            </div>
        </>
    )
}
