import { useEffect, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { updateOption } from '@onboarding/api/WPApi'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { usePagesStore } from '@onboarding/state/Pages'
import { pageState } from '@onboarding/state/factory'

export const state = pageState('Welcome', () => ({
    title: __('Welcome', 'extendify'),
    default: undefined,
    ready: true,
    isDefault: () => true,
}))
export const Landing = () => {
    const nextPage = usePagesStore((state) => state.nextPage)
    const continueButton = useRef(null)

    useEffect(() => {
        const raf = requestAnimationFrame(() => continueButton.current.focus())
        return () => cancelAnimationFrame(raf)
    }, [continueButton])

    const handleSkipLaunch = async (e) => {
        e.preventDefault()

        // Store when Launch is skipped.
        await updateOption(
            'extendify_onboarding_skipped',
            new Date().toISOString(),
        )

        location.href = window.extOnbData.adminUrl
    }

    return (
        <PageLayout>
            <div>
                <h1 className="text-3xl text-partner-primary-text mb-4 mt-0">
                    {__('Welcome to Your WordPress Site', 'extendify')}
                </h1>
                <p className="text-base opacity-70 mb-0">
                    {__(
                        'Design and launch your site with this guided experience, or head right into the WordPress dashboard if you know your way around.',
                        'extendify',
                    )}
                </p>
            </div>
            <div>
                <h2 className="text-lg m-0 mb-4 text-gray-900">
                    {__('Pick one:', 'extendify')}
                </h2>
                <div className="xl:flex xl:gap-x-6">
                    <button
                        onClick={nextPage}
                        ref={continueButton}
                        type="button"
                        aria-label={__('Press to continue', 'extendify')}
                        className="button-card max-w-sm button-focus mb-6 xl:mb-0">
                        <div
                            className="bg-gray-100 w-full h-64 bg-cover bg-center border border-gray-200"
                            style={{
                                backgroundImage: `url(${window.extOnbData.pluginUrl}/public/assets/extendify-preview.png)`,
                            }}
                        />
                        <p className="font-bold text-lg text-gray-900">
                            Extendify Launch
                        </p>
                        <p className="text-base text-gray-900">
                            {__(
                                'Create a super-fast, beautiful, and fully customized site in minutes. Simply answer a few questions and pick a design to get started. Then, everything can be fully customized in the core WordPress editor.',
                                'extendify',
                            )}
                        </p>
                    </button>
                    <a
                        onClick={(e) => handleSkipLaunch(e)}
                        className="button-card max-w-sm button-focus">
                        <div
                            className="bg-gray-100 w-full h-64 bg-cover bg-center border border-gray-200"
                            style={{
                                backgroundImage: `url(${window.extOnbData.pluginUrl}/public/assets/wp-admin.png)`,
                            }}
                        />
                        <p className="font-bold text-lg text-gray-900">
                            {__('WordPress Dashboard', 'extendify')}
                        </p>
                        <p className="text-base text-gray-900">
                            {__(
                                'Are you a WordPress developer and want to go straight to the admin dashboard?',
                                'extendify',
                            )}
                        </p>
                    </a>
                </div>
            </div>
        </PageLayout>
    )
}
