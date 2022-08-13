import { useSelect } from '@wordpress/data'
import { useEffect, useState } from '@wordpress/element'
import { SWRConfig, useSWRConfig } from 'swr'
import { RetryNotice } from '@onboarding/components/RetryNotice'
import { useDisableWelcomeGuide } from '@onboarding/hooks/useDisableWelcomeGuide'
import { useBodyScrollLock } from '@onboarding/hooks/useScrollLock'
import { CreatingSite } from '@onboarding/pages/CreatingSite'
import { Finished } from '@onboarding/pages/Finished'
import { useGlobalStore } from '@onboarding/state/Global'
import { usePagesStore } from '@onboarding/state/Pages'
import { updateOption } from './api/WPApi'
import { useSentry } from './hooks/useSentry'
import { useTelemetry } from './hooks/useTelemetry'
import { NeedsTheme } from './pages/NeedsTheme'

export const Onboarding = () => {
    const [retrying, setRetrying] = useState(false)
    const { component: CurrentPage } = usePagesStore((state) =>
        state.currentPageData(),
    )
    const { fetcher, fetchData } = usePagesStore((state) =>
        state.nextPageData(),
    )
    const { mutate } = useSWRConfig()
    const generating = useGlobalStore((state) => state.generating)
    const generatedPages = useGlobalStore((state) => state.generatedPages)
    const [show, setShow] = useState(false)
    const [needsTheme, setNeedsTheme] = useState(false)
    const theme = useSelect((select) => select('core').getCurrentTheme())
    const { Sentry } = useSentry()
    useDisableWelcomeGuide()
    useBodyScrollLock()
    useTelemetry()

    const page = () => {
        if (needsTheme) return <NeedsTheme />
        if (Object.keys(generatedPages)?.length) return <Finished />
        if (generating) return <CreatingSite />
        return <CurrentPage />
    }

    useEffect(() => {
        // Check that the textdomain came back and that it's extendable
        if (!theme?.textdomain) return
        if (theme?.textdomain === 'extendable') return
        setNeedsTheme(true)
    }, [theme])

    useEffect(() => {
        if (!show) return
        // If the library happens to be open, try to close it.
        const timeout = setTimeout(() => {
            window.dispatchEvent(
                new CustomEvent('extendify::close-library', { bubbles: true }),
            )
        }, 0)
        document.title = 'Extendify Launch' // Don't translate
        return () => clearTimeout(timeout)
    }, [show])

    useEffect(() => {
        const q = new URLSearchParams(window.location.search)
        if (['onboarding'].includes(q.get('extendify'))) {
            setShow(true)
            updateOption('extendify_launch_loaded', new Date().toISOString())
        }
    }, [])

    useEffect(() => {
        if (fetcher) {
            mutate(fetchData, fetcher)
        }
    }, [fetcher, mutate, fetchData])

    if (!show) return null

    return (
        <SWRConfig
            value={{
                errorRetryInterval: 1000,
                onErrorRetry: (
                    error,
                    key,
                    config,
                    revalidate,
                    { retryCount },
                ) => {
                    if (error?.data?.status === 403) {
                        // if they are logged out, we can't recover
                        window.location.reload()
                        return
                    }
                    if (retrying) return

                    // TODO: Add back when we have something to show here
                    // if (retryCount >= 5) {
                    //     console.error('Encountered unrecoverable error', error)
                    //     throw new Error(error?.message ?? 'Unknown error')
                    // }
                    console.error(key, error)
                    Sentry.captureException(
                        new Error(error?.message ?? 'Unknown error'),
                        {
                            tags: { retrying: true },
                            extra: { cacheKey: key },
                        },
                    )

                    setRetrying(true)
                    setTimeout(() => {
                        setRetrying(false)
                        revalidate({ retryCount })
                    }, 5000)
                },
            }}>
            <div
                style={{ zIndex: 99999 + 1 }} // 1 more than the library
                className="h-screen w-screen fixed inset-0 overflow-y-auto md:overflow-hidden bg-white">
                {page()}
            </div>
            {retrying && <RetryNotice />}
        </SWRConfig>
    )
}
