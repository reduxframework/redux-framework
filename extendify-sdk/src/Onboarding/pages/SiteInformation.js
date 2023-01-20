import { useEffect, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { getOption } from '@onboarding/api/WPApi'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { pageState } from '@onboarding/state/factory'

export const fetcher = async () => ({
    data: { title: await getOption('blogname') },
})
export const fetchData = () => ({ key: 'site-info' })
export const state = pageState('Site Title', () => ({
    title: __('Site Title', 'extendify'),
    default: undefined,
    showInSidebar: true,
    ready: false,
    isDefault: () => undefined,
}))
export const SiteInformation = () => {
    const { setSiteInformation, siteInformation } = useUserSelectionStore()
    const initialFocus = useRef(null)
    const nextPage = usePagesStore((state) => state.nextPage)
    const { data: siteInfoFromDb } = useFetch(fetchData, fetcher)

    useEffect(() => {
        // siteInformation.title is the users choice for their title
        if (siteInformation?.title !== undefined) return
        // On first load, set the "selected" title to the existing title
        // from the db as soon as it comes in.
        if (siteInfoFromDb?.title === undefined) return
        setSiteInformation('title', siteInfoFromDb.title)
    }, [siteInfoFromDb, setSiteInformation, siteInformation?.title])

    useEffect(() => {
        // This waits for the above useEffect to run and set the title
        // and will only run once
        if (siteInformation?.title === undefined) return
        if (state.getState().ready) return
        state.setState({ ready: true })
        const raf = requestAnimationFrame(() => initialFocus.current.focus())
        return () => cancelAnimationFrame(raf)
    }, [siteInformation?.title])

    return (
        <PageLayout>
            <div>
                <h1
                    className="text-3xl text-partner-primary-text mb-4 mt-0"
                    data-test="site-title-heading">
                    {__("What's the name of your new site?", 'extendify')}
                </h1>
                <p className="text-base opacity-70 mb-0">
                    {__('You can change this later.', 'extendify')}
                </p>
            </div>
            <div className="w-full max-w-onboarding-sm mx-auto">
                {siteInformation?.title === undefined ? (
                    __('Loading...', 'extendify')
                ) : (
                    <form
                        onSubmit={(e) => {
                            e.preventDefault()
                            nextPage()
                        }}>
                        <label
                            htmlFor="extendify-site-title-input"
                            className="block text-lg m-0 mb-4 font-semibold text-gray-900">
                            {__("What's the name of your site?", 'extendify')}
                        </label>
                        <div className="mb-8">
                            <input
                                data-test="site-title-input"
                                autoComplete="off"
                                ref={initialFocus}
                                type="text"
                                name="site-title-input"
                                id="extendify-site-title-input"
                                className="w-96 max-w-full border h-12 input-focus"
                                value={siteInformation.title}
                                onChange={(e) => {
                                    setSiteInformation('title', e.target.value)
                                }}
                                placeholder={__(
                                    'Enter your preferred site title...',
                                    'extendify',
                                )}
                            />
                        </div>
                    </form>
                )}
            </div>
        </PageLayout>
    )
}
