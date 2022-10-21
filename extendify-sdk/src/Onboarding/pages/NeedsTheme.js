import { __ } from '@wordpress/i18n'
import { PageLayout } from '@onboarding/layouts/PageLayout'

export const NeedsTheme = () => {
    return (
        <PageLayout includeNav={false}>
            <div>
                <h1 className="text-3xl text-white mb-4 mt-0">
                    {__('Hey, one more thing before we start.', 'extendify')}
                </h1>
            </div>
            <div className="w-full">
                <p className="mt-0 mb-8 text-base">
                    {__(
                        'Hey there, Launch is powered by Extendable and is required to proceed. You can install it from the link below and start over once activated.',
                        'extendify',
                    )}
                </p>
                <div className="flex flex-col items-start space-y-4 text-base">
                    <a
                        href={`${window.extOnbData.site}/wp-admin/theme-install.php?theme=extendable`}>
                        {__('Take me there', 'extendify')}
                    </a>
                </div>
            </div>
        </PageLayout>
    )
}
