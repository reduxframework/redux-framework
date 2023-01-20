import { useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { useGlobalStore, useGlobalStoreReady } from '@assist/state/Global'
import { useTourStore } from '@assist/state/Tours'
import welcomeTour from '@assist/tours/welcome.js'

const noticeKey = 'welcome-message'
export const WelcomeNotice = () => {
    const { isDismissed, dismissNotice } = useGlobalStore()
    const ready = useGlobalStoreReady()
    // To avoid content flash, we load in this partial piece of state early via php
    const dismissed = window.extAssistData.dismissedNotices.find(
        (notice) => notice.id === noticeKey,
    )
    const [enabled, setEnabled] = useState(false)
    const { startTour } = useTourStore()

    useEffect(() => {
        if (dismissed || isDismissed(noticeKey)) {
            return
        }
        setEnabled(true)
    }, [dismissed, isDismissed, dismissNotice])

    useEffect(() => {
        if (!enabled || !ready) return
        // For this notice, we only want to show it once
        dismissNotice(noticeKey)
    }, [dismissNotice, enabled, ready])

    if (!enabled) return null

    return (
        <div className="bg-partner-primary-bg text-partner-primary-text p-8 max-w-screen-lg mx-auto flex justify-center mt-12">
            <div className="flex justify-center lg:max-w-3/4 gap-8">
                <div className="">
                    <p className="font-bold m-0 text-2xl">
                        {__('Congratulations!', 'extendify')}
                    </p>
                    <span className="block text-right text-base">
                        {__('Your site is ready.', 'extendify')}
                    </span>
                </div>
                <div className="">
                    <h1 className="text-4xl mt-0 text-white">
                        {__("What's Next?", 'extendify')}
                    </h1>
                    <p className="text-base">
                        {__(
                            'The Extendify Assistant is your go-to dashboard to help you get the most out of your site. Take a quick tour!',
                            'extendify',
                        )}
                    </p>
                    <div className="flex mt-8">
                        <button
                            className="flex items-center gap-1 text-sm sm:text-base text-partner-primary-bg cursor-pointer rounded px-3 sm:px-6 py-2 bg-white border-none no-underline"
                            onClick={() => startTour(welcomeTour)}>
                            {__('Take a tour', 'extendify')}
                        </button>
                        <button
                            className="bg-transparent text-white opacity-70 hover:opacity-100 border-0 shadow-none p-4 cursor-pointer text-base flex items-center"
                            type="button"
                            onClick={() => setEnabled(false)}>
                            <span className="dashicons dashicons-no-alt mr-1"></span>
                            <span>{__('Dismiss', 'extendify')}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    )
}
