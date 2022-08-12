import { useEffect } from '@wordpress/element'
import * as Sentry from '@sentry/react'
import { BrowserTracing } from '@sentry/tracing'
import { useGlobalStore } from '@onboarding/state/Global'
import { usePagesStore } from '@onboarding/state/Pages'

if (window.extOnbData.insightsEnabled) {
    Sentry.init({
        dsn: 'https://c5c1aec4298743d399e86509ee4cef9c@o1352321.ingest.sentry.io/6633543',
        integrations: [new BrowserTracing()],
        release: window.extOnbData?.version,
        environment: window?.extOnbData?.devbuild ? 'dev' : 'production',

        // TODO: consider lowering this in production to reduce the amount of data sent
        tracesSampleRate: 1.0,
        beforeSend(event) {
            // Check if it is an exception, and if so, show the report dialog
            if (event.exception) {
                Sentry.showReportDialog({ eventId: event.event_id })
            }
            return event
        },
    })
}
export { Sentry }
export const useSentry = () => {
    const orderId = useGlobalStore((state) => state.orderId)
    const { pages, currentPageIndex } = usePagesStore()

    useEffect(() => {
        Sentry.setUser({ id: window.extOnbData?.insightsId })
        Sentry.configureScope((scope) => {
            scope.setExtra('Partner', window.extOnbData?.partnerName)
            scope.setExtra('Site', window.extOnbData?.home)
            scope.setExtra('Order ID', orderId)
            scope.setExtra('Insights ID', window.extOnbData?.insightsId)
        })
    }, [orderId])

    useEffect(() => {
        const p = [...pages].map((p) => p[0])
        Sentry.addBreadcrumb({
            type: 'navigation',
            category: 'step',
            message: `Navigated to ${p[currentPageIndex]}`,
        })
    }, [currentPageIndex, pages])

    return { Sentry }
}
