import { __ } from '@wordpress/i18n'
import { PagePreview } from '@onboarding/components/PagePreview'
import { SuggestedPlugins } from '@onboarding/components/SuggestedPlugins'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { pageState } from '@onboarding/state/factory'
import { Checkmark } from '@onboarding/svg'

export const state = pageState('Site Summary', () => ({
    title: __('Summary', 'extendify'),
    default: undefined,
    showInSidebar: true,
    // Not ready because this is where the launch button shows
    ready: false,
    isDefault: () => true,
}))
export const SiteSummary = () => {
    const { siteType, style, pages, goals } = useUserSelectionStore()
    const setPage = usePagesStore((state) => state.setPage)

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-partner-primary-text mb-4 mt-0">
                    {__("Let's launch your site!", 'extendify')}
                </h1>
                <p className="text-base mb-0">
                    {__('Review your site configuration.', 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <div className="flex flex-col gap-y-12">
                    <div className="block">
                        <h2 className="text-lg m-0 mb-4 text-gray-900">
                            {__('Design', 'extendify')}
                        </h2>

                        {style?.label ? (
                            <div className="overflow-hidden rounded-lg relative">
                                <span
                                    aria-hidden="true"
                                    className="absolute top-0 bottom-0 left-3/4 right-0 z-40 bg-gradient-to-l from-white pointer-events-none"></span>
                                {pages.length > 0 && (
                                    <div className="flex justify-center lg:justify-start w-full overflow-y-scroll lg:pr-52">
                                        <div className="flex flex-col lg:flex-row lg:flex-no-wrap gap-4">
                                            {pages?.map((page) => {
                                                return (
                                                    <div
                                                        className="relative pointer-events-none"
                                                        style={{
                                                            height: 360,
                                                            width: 255,
                                                        }}
                                                        key={page.id}>
                                                        <PagePreview
                                                            displayOnly={true}
                                                            page={page}
                                                            blockHeight={356}
                                                        />
                                                    </div>
                                                )
                                            })}
                                        </div>
                                    </div>
                                )}
                            </div>
                        ) : (
                            <button
                                onClick={() => setPage('style')}
                                className="bg-transparent text-partner-primary underline text-base cursor-pointer">
                                {__('Press to change the style', 'extendify')}
                            </button>
                        )}
                    </div>
                    <div className="block">
                        <h2 className="text-lg m-0 mb-4">
                            {__('Industry', 'extendify')}
                        </h2>

                        {siteType?.label ? (
                            <div className="flex items-center">
                                <Checkmark
                                    className="text-extendify-main-dark"
                                    style={{ width: 24 }}
                                />
                                <span className="text-base pl-2">
                                    {siteType.label}
                                </span>
                            </div>
                        ) : (
                            <button
                                onClick={() => setPage('site-type')}
                                className="bg-transparent text-partner-primary underline text-base cursor-pointer">
                                {__('Press to set a site type', 'extendify')}
                            </button>
                        )}
                    </div>
                    <div className="block">
                        <h2 className="text-lg m-0 mb-4">
                            {__('Goals', 'extendify')}
                        </h2>

                        {goals.length > 0 ? (
                            <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                {goals?.map((goal) => {
                                    return (
                                        <div
                                            className="flex items-center"
                                            key={goal.id}>
                                            <Checkmark
                                                className="text-extendify-main-dark"
                                                style={{ width: 24 }}
                                            />
                                            <span className="text-base pl-2">
                                                {goal.title}
                                            </span>
                                        </div>
                                    )
                                })}
                            </div>
                        ) : (
                            <button
                                onClick={() => setPage('goals')}
                                className="bg-transparent text-partner-primary underline text-base cursor-pointer">
                                {__('Press to set your goals', 'extendify')}
                            </button>
                        )}
                    </div>
                    <div className="block">
                        <h2 className="text-lg m-0 mb-4">
                            {__('Pages', 'extendify')}
                        </h2>

                        {pages.length > 0 ? (
                            <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                {pages?.map((page) => {
                                    return (
                                        <div
                                            className="flex items-center"
                                            key={page.id}>
                                            <Checkmark
                                                className="text-extendify-main-dark"
                                                style={{ width: 24 }}
                                            />
                                            <span className="text-base pl-2">
                                                {page.title}
                                            </span>
                                        </div>
                                    )
                                })}
                            </div>
                        ) : (
                            <button
                                onClick={() => setPage('pages')}
                                className="bg-transparent text-partner-primary underline text-base cursor-pointer">
                                {__('Press to set your pages', 'extendify')}
                            </button>
                        )}
                    </div>
                    <div className="block">
                        <h2 className="text-lg m-0 mb-4">
                            {__('Plugins', 'extendify')}
                        </h2>
                        <SuggestedPlugins />
                    </div>
                </div>
            </div>
        </PageLayout>
    )
}
