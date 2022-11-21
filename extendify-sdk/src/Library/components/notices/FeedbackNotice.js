import { Button } from '@wordpress/components'
import { __, sprintf } from '@wordpress/i18n'
import { General } from '@library/api/General'

export default function FeedbackNotice() {
    return (
        <>
            <span className="text-black">
                {sprintf(
                    // translators: %s: Extendify Library term.
                    __(
                        'Tell us how to make the %s work better for you',
                        'extendify',
                    ),
                    'Extendify Library',
                )}
            </span>
            <span className="px-2 opacity-50" aria-hidden="true">
                &#124;
            </span>
            <div className="flex items-center justify-center space-x-2">
                <Button
                    variant="link"
                    className="h-auto p-0 text-black underline hover:no-underline"
                    href={`https://extendify.com/feedback/?utm_source=${window.extendifyData.sdk_partner}&utm_medium=library&utm_campaign=feedback-notice&utm_content=give-feedback`}
                    onClick={async () =>
                        await General.ping('feedback-notice-click')
                    }
                    target="_blank">
                    {__('Give feedback', 'extendify')}
                </Button>
            </div>
        </>
    )
}
