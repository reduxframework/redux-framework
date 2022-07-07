import { __, sprintf } from '@wordpress/i18n'
import { addQueryArgs } from '@wordpress/url'
import { useConfetti } from '@onboarding/hooks/useConfetti'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { useGlobalStore } from '@onboarding/state/Global'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { Checkmark } from '@onboarding/svg'

export const Finished = () => {
    const generatedPages = useGlobalStore((state) => state.generatedPages)
    const siteType = useUserSelectionStore((state) => state.siteType)
    // const pages = useUserSelectionStore((state) => state.pages)
    // const style = useUserSelectionStore((state) => state.style)
    useConfetti(
        {
            particleCount: 2,
            angle: 60,
            spread: 55,
            origin: { x: 0, y: 0.7 },
            colors: ['var(--ext-partner-theme-primary-text, #ffffff)'],
        },
        3000,
    )
    return (
        <PageLayout includeNav={false}>
            <div>
                <h1 className="text-3xl text-partner-primary-text mb-4 mt-0">
                    {sprintf(
                        __(
                            'Your site has been successfully created. Enjoy!',
                            'extendify',
                        ),
                        siteType?.label?.toLowerCase(),
                    )}
                </h1>
            </div>
            <div className="w-full">
                <p className="mt-0 mb-8 text-base text-center">
                    {__(
                        'Your site is ready! You can now go to your site and start editing content.',
                        'extendify',
                    )}
                </p>
                <div className="text-center w-360 flex flex-col justify-center items-center -mt-150">
                    <Checkmark className="w-16 bg-partner-primary-bg text-partner-primary-text rounded-full" />
                    <h3 className="mb-8">{__('All Done', 'extendify')}</h3>
                    <div className="flex space-x-4">
                        <a
                            className="px-4 py-3 rounded-md bg-gray-200 text-black no-underline hover:bg-partner-primary-bg hover:text-partner-primary-text font-medium"
                            target="_blank"
                            rel="noreferrer"
                            href={window.extOnbData.home}>
                            {__('View Site', 'extendify')}
                        </a>
                    </div>
                    <div className="text-left self-start px-10 py-4 w-full">
                        <h4 className="">{__('New Pages:', 'extendify')}</h4>
                        <div className="">
                            {Object.values(generatedPages)?.map((page) => (
                                <div
                                    key={page.id}
                                    className="flex items-center mb-2">
                                    <Checkmark className="w-6 text-green-500" />
                                    <a
                                        target="_blank"
                                        href={addQueryArgs('post.php', {
                                            post: page.id,
                                            action: 'edit',
                                        })}
                                        className="text-primary no-underline hover:underline ml-2 text-base"
                                        rel="noreferrer">
                                        {page.title}
                                    </a>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </PageLayout>
    )
}
