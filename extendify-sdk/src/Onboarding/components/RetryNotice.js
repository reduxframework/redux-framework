import { Snackbar } from '@wordpress/components'
import { __ } from '@wordpress/i18n'

export const RetryNotice = () => {
    return (
        <div className="extendify-onboarding w-full fixed bottom-4 px-4 flex justify-end z-max">
            <div className="shadow-2xl">
                <Snackbar>
                    {__(
                        'Just a moment, this is taking longer than expected.',
                        'extendify',
                    )}
                </Snackbar>
            </div>
        </div>
    )
}
