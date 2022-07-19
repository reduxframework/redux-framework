import { __ } from '@wordpress/i18n'
import { PagePreview } from '@onboarding/components/PagePreview'
import { SuggestedPlugins } from '@onboarding/components/SuggestedPlugins'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { Checkmark } from '@onboarding/svg'

export const metadata = {
    key: 'confirmation',
}
export const SiteSummary = () => {
    const { siteType, style, pages, goals } = useUserSelectionStore()
    const setPage = usePagesStore((state) => state.setPage)

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-partner-primary-text mb-4 mt-0">
                    {__("Let's launch your site!", 'extendify')}
                </h1>
                <p className="text-base">
                    {__('Review your site configuration.', 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <div className="flex flex-col space-y-8">
                    <div className="block">
                        <div className="flex align-center">
                            <h2 className="text-lg m-0 mb-4 text-gray-900">
                                {__('Design', 'extendify')}
                            </h2>
                            <button
                                className="text-xs underline cursor-pointer text-partner-primary-bg bg-white mb-4 ml-2"
                                onClick={() => setPage('style')}
                                title={__(
                                    'Press to change the style',
                                    'extendify',
                                )}>
                                {__('Change', 'extendify')}
                            </button>
                        </div>

                        {style?.label ? (
                            <div className="overflow-hidden rounded-lg relative">
                                <span
                                    aria-hidden="true"
                                    className="absolute top-0 bottom-0 left-3/4 right-0 z-40 bg-gradient-to-l from-white"></span>
                                {pages.length > 0 && (
                                    <div className="flex items-start space-x-2 w-full">
                                        <div className="lg:flex flex-no-wrap">
                                            {pages?.map((page) => {
                                                return (
                                                    <div
                                                        className="px-3 relative pointer-events-none"
                                                        style={{
                                                            height: 387,
                                                            width: 255,
                                                        }}
                                                        key={page.id}>
                                                        <PagePreview
                                                            displayOnly={true}
                                                            page={page}
                                                            blockHeight={175}
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
                        <div className="flex align-center">
                            <h2 className="text-lg m-0 mb-4">
                                {__('Industry', 'extendify')}
                            </h2>
                            <button
                                className="text-xs underline cursor-pointer text-partner-primary-bg bg-white mb-4 ml-2"
                                onClick={() => setPage('site-type')}
                                title={__(
                                    'Press to change the site type',
                                    'extendify',
                                )}>
                                {__('Change', 'extendify')}
                            </button>
                        </div>
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
                        <div className="flex align-center">
                            <h2 className="text-lg m-0 mb-4">
                                {__('Goals', 'extendify')}
                            </h2>
                            <button
                                className="text-xs underline cursor-pointer text-partner-primary-bg bg-white mb-4 ml-2"
                                onClick={() => setPage('goals')}
                                title={__(
                                    'Press to change the selected goals',
                                    'extendify',
                                )}>
                                {__('Change', 'extendify')}
                            </button>
                        </div>
                        {goals.length > 0 ? (
                            <div className="xl:grid grid-cols-3-minmax-300px-1fr gap-x-4 gap-y-1 -mx-6">
                                {goals?.map((goal) => {
                                    return (
                                        <div
                                            className="px-6 pb-2 flex items-center"
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
                        <div className="flex align-center">
                            <h2 className="text-lg m-0 mb-4">
                                {__('Pages', 'extendify')}
                            </h2>
                            <button
                                className="text-xs underline cursor-pointer text-partner-primary-bg bg-white mb-4 ml-2"
                                onClick={() => setPage('pages')}
                                title={__(
                                    'Press to change the selected pages',
                                    'extendify',
                                )}>
                                {__('Change', 'extendify')}
                            </button>
                        </div>
                        {pages.length > 0 ? (
                            <div className="xl:grid grid-cols-3-minmax-300px-1fr gap-x-4 gap-y-1 -mx-6">
                                {pages?.map((page) => {
                                    return (
                                        <div
                                            className="px-6 pb-2 flex items-center"
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
                        <div className="flex items-start space-x-2 cursor-pointer w-full">
                            <SuggestedPlugins />
                        </div>
                    </div>
                </div>
            </div>
        </PageLayout>
    )
}
