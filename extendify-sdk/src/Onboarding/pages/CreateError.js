import { __ } from '@wordpress/i18n'
import { PageLayout } from '@onboarding/layouts/PageLayout'

export const CreateError = () => {
    return (
        <PageLayout includeNav={false}>
            <div>
                <h1 className="text-3xl text-white mb-4 mt-0">
                    {__('We encountered an error.', 'extendify')}
                </h1>
            </div>
            <div className="w-full">
                <p className="mt-0 mb-8 text-base">
                    {__(
                        "We encountered an error that we can't recover from. You can attempt to start over by pressing the button below.",
                        'extendify',
                    )}
                </p>
                <div className="flex flex-col items-start space-y-4 text-base">
                    <a
                        href={`${window.extOnbData.site}/wp-admin/post-new.php?extendify=onboarding`}>
                        {__('Start over')}
                    </a>
                </div>
            </div>
        </PageLayout>
    )
}
