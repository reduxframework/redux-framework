import { __ } from '@wordpress/i18n'
import { Card } from '@onboarding/components/Card'
import { PagePreview } from '@onboarding/components/PagePreview'
import { StylePreview } from '@onboarding/components/StyledPreview'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { stripUrlParams } from '@onboarding/lib/util'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

export const SiteSummary = () => {
    const { siteType, style, pages, plugins } = useUserSelectionStore()
    const setPage = usePagesStore((state) => state.setPage)

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-white mb-4 mt-0">
                    {__("Let's launch your site!", 'extendify')}
                </h1>
                <p className="text-base">
                    {__('Review your site configuration.', 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <p className="mt-0 mb-8 text-base">
                    {__('Site settings', 'extendify')}
                </p>
                <div className="flex flex-col space-y-8">
                    <div className="flex items-center">
                        <div className="w-20 flex-shrink-0 text-base">
                            {__('Industry:', 'extendify')}
                        </div>
                        {siteType?.label ? (
                            <div
                                className="p-4 py-2 rounded-lg text-base flex bg-transparent border border-gray-600 cursor-pointer"
                                onClick={() => setPage('site-type-select')}
                                title={__(
                                    'Press to change the site type',
                                    'extendify',
                                )}>
                                {siteType.label}
                            </div>
                        ) : (
                            <button
                                onClick={() => setPage('site-type-select')}
                                className="bg-transparent text-partner-primary underline text-base">
                                {__('Press to set a site type', 'extendify')}
                            </button>
                        )}
                    </div>
                    <div className="flex items-start">
                        <div className="w-20 flex-shrink-0 text-base">
                            {__('Style:', 'extendify')}
                        </div>
                        {style?.label ? (
                            <div
                                className="cursor-pointer overflow-hidden border rounded-lg"
                                onClick={() => setPage('site-style-select')}
                                title={__(
                                    'Press to change the site style',
                                    'extendify',
                                )}>
                                <div
                                    className="p-2 relative"
                                    style={{ height: 354, width: 255 }}
                                    key={style.recordId}>
                                    <StylePreview
                                        style={style}
                                        blockHeight={354}
                                    />
                                </div>
                            </div>
                        ) : (
                            <button
                                onClick={() => setPage('site-style-select')}
                                className="bg-transparent text-partner-primary underline text-base">
                                {__('Press to set a style type', 'extendify')}
                            </button>
                        )}
                    </div>
                    <div className="flex items-start">
                        <div className="w-20 flex-shrink-0 text-base">
                            {__('Pages:', 'extendify')}
                        </div>
                        {pages.length > 0 ? (
                            <div
                                className="flex items-start space-x-2 cursor-pointer w-full"
                                onClick={() => setPage('site-pages-select')}
                                title={__(
                                    'Press to change the selected pages',
                                    'extendify',
                                )}>
                                <div className="lg:flex space-y-6 -m-8 lg:space-y-0 flex-wrap">
                                    {pages?.map((page) => {
                                        return (
                                            <div
                                                className="p-8 relative"
                                                style={{
                                                    height: 354,
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
                        ) : (
                            <button
                                onClick={() => setPage('site-pages-select')}
                                className="bg-transparent text-partner-primary underline text-base">
                                {__('Press to set your pages', 'extendify')}
                            </button>
                        )}
                    </div>
                    {plugins.length > 0 ? (
                        <div className="flex items-start">
                            <div className="w-20 flex-shrink-0 text-base">
                                {__('Plugins:', 'extendify')}
                            </div>
                            <div
                                className="flex items-start space-x-2 cursor-pointer w-full"
                                onClick={() => setPage('suggested-plugins')}
                                title={__(
                                    'Press to change the selected plugins',
                                    'extendify',
                                )}>
                                <div className="grid w-full grid-cols-3 gap-4">
                                    {plugins.map((plugin) => (
                                        <Card
                                            key={plugin.id}
                                            lock={true}
                                            image={stripUrlParams(
                                                plugin.previewImage,
                                            )}
                                            name={plugin.name}
                                            heading={plugin.heading}
                                            description={plugin.description}
                                        />
                                    ))}
                                </div>
                            </div>
                        </div>
                    ) : null}
                </div>
            </div>
        </PageLayout>
    )
}
