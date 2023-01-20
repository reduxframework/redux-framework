import { registerCoreBlocks } from '@wordpress/block-library'
import { useSelect } from '@wordpress/data'
import { useDispatch } from '@wordpress/data'
import { useEffect, useState } from '@wordpress/element'
import { SWRConfig, useSWRConfig } from 'swr'
import { RetryNotice } from '@onboarding/components/RetryNotice'
import { CreatingSite } from '@onboarding/pages/CreatingSite'
import { useGlobalStore } from '@onboarding/state/Global'
import { usePagesStore } from '@onboarding/state/Pages'
import { updateOption } from './api/WPApi'
import { ExitModal } from './components/ExitModal'
import { useTelemetry } from './hooks/useTelemetry'
import { NeedsTheme } from './pages/NeedsTheme'
import { useUserSelectionStore } from './state/UserSelections'

export const Onboarding = () => {
    const { updateSettings } = useDispatch('core/block-editor')
    const [retrying, setRetrying] = useState(false)
    const { siteType } = useUserSelectionStore()
    const CurrentPage = usePagesStore((state) => {
        const pageData = state.getCurrentPageData()
        return pageData?.component
    })
    const { fetcher, fetchData } = usePagesStore((state) =>
        state.getNextPageData(),
    )
    const { setPage, currentPageIndex } = usePagesStore()
    const { mutate } = useSWRConfig()
    const { generating } = useGlobalStore()
    const [show, setShow] = useState(false)
    const [needsTheme, setNeedsTheme] = useState(false)
    const theme = useSelect((select) => select('core').getCurrentTheme())
    useTelemetry()

    const page = () => {
        if (needsTheme) return <NeedsTheme />
        // Site type is required to progress
        if (!siteType?.slug && currentPageIndex !== 0) {
            setPage(0)
            return null
        }
        if (generating) return <CreatingSite />
        if (!CurrentPage) return null
        return <CurrentPage />
    }

    useEffect(() => {
        // Add editor styles to use for live previews
        updateSettings(window.extOnbData.editorStyles)
    }, [updateSettings])

    useEffect(() => {
        // Keep an eye on this. If WP starts registering blocks when
        // importing the block-library module (as they likely should be doing)
        // then we will need to have a conditional here
        registerCoreBlocks()
    }, [])

    useEffect(() => {
        // Check that the textdomain came back and that it's extendable
        if (!theme?.textdomain) return
        if (theme?.textdomain === 'extendable') return
        setNeedsTheme(true)
    }, [theme])

    useEffect(() => {
        setShow(true)
        updateOption('extendify_launch_loaded', new Date().toISOString())
    }, [])

    useEffect(() => {
        if (fetcher) {
            const data =
                typeof fetchData === 'function' ? fetchData() : fetchData
            mutate(data, () => fetcher(data))
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
            <ExitModal />
        </SWRConfig>
    )
}
