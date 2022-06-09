import { useEffect, useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { SWRConfig, useSWRConfig } from 'swr'
import { RetryNotice } from '@onboarding/components/RetryNotice'
import { useDisableWelcomeGuide } from '@onboarding/hooks/useDisableWelcomeGuide'
import { useBodyScrollLock } from '@onboarding/hooks/useScrollLock'
import { CreatingSite } from '@onboarding/pages/CreatingSite'
import { Finished } from '@onboarding/pages/Finished'
import { useGlobalStore } from '@onboarding/state/Global'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

export const Onboarding = () => {
    const resetState = useUserSelectionStore((state) => state.resetState)
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
    useDisableWelcomeGuide()
    useBodyScrollLock()

    useEffect(() => {
        if (!show) return
        // If the library happens to be open, try to close it.
        const timeout = setTimeout(() => {
            window.dispatchEvent(
                new CustomEvent('extendify::close-library', { bubbles: true }),
            )
        }, 0)
        document.title = __('Extendify Launch', 'extendify')
        return () => clearTimeout(timeout)
    }, [show])

    useEffect(() => {
        resetState()
        const q = new URLSearchParams(window.location.search)
        setShow(['onboarding'].includes(q.get('extendify')))
    }, [resetState])

    useEffect(() => {
        if (fetcher) {
            mutate(fetchData, fetcher)
        }
    }, [fetcher, mutate, fetchData])

    if (!show) return null

    if (Object.keys(generatedPages)?.length) {
        return (
            <div className="h-screen w-screen fixed z-high inset-0 overflow-y-auto md:overflow-hidden bg-white">
                <Finished />
            </div>
        )
    }

    return (
        <SWRConfig
            value={{
                errorRetryInterval: 1000,
                onErrorRetry: (error, key) => {
                    console.error(key, error)
                    if (error?.data?.status === 403) {
                        // if they are logged out, we can't recover
                        window.location.reload()
                        return
                    }
                    if (retrying) return
                    setRetrying(true)
                    setTimeout(() => {
                        setRetrying(false)
                    }, 5000)
                },
            }}>
            <div
                style={{ zIndex: 99999 + 1 }} // 1 more than the library
                className="h-screen w-screen fixed inset-0 overflow-y-auto md:overflow-hidden bg-white">
                {generating ? <CreatingSite /> : <CurrentPage />}
            </div>
            {retrying && <RetryNotice />}
        </SWRConfig>
    )
}
