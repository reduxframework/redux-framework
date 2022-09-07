import { __, sprintf } from '@wordpress/i18n'
import { PagesModule } from '@assist/components/PagesModule'

export const AssistLandingPage = () => {
    return (
        <div>
            <div className="pt-12 flex justify-center flex-col">
                <h2 className="text-center text-3xl">
                    {sprintf(__('Welcome to %s', 'extendify'), 'Assist')}
                </h2>
                <p className="text-center text-xl">
                    {__(
                        'Manage your site content from a centralized location.',
                        'extendify',
                    )}
                </p>
                <PagesModule />
            </div>
        </div>
    )
}
