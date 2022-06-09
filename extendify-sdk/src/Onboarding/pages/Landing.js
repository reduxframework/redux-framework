import { useEffect, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { updateOption } from '@onboarding/api/WPApi'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { usePagesStore } from '@onboarding/state/Pages'

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
                <h1 className="text-3xl text-white mb-4 mt-0">
                    {__('Welcome to Your WordPress Site', 'extendify')}
                </h1>
                <p className="text-base opacity-70">
                    {__(
                        "Design and launch your site with our guided experience, or jump right to the WordPress dashboard if you already know what you're doing.",
                        'extendify',
                    )}
                </p>
            </div>
            <div className="">
                <p className="mt-0 mb-8 text-base">
                    {__('Pick one:', 'extendify')}
                </p>
                <div className="lg:flex lg:space-x-8">
                    <button
                        onClick={nextPage}
                        ref={continueButton}
                        type="button"
                        aria-label={__('Press to continue', 'extendify')}
                        className="button-card max-w-sm button-focus">
                        <div
                            className="bg-gray-100 w-full h-64 bg-cover border border-gray-200"
                            style={{
                                backgroundImage: `url(${window.extOnbData.pluginUrl}/public/assets/extendify-preview.png)`,
                            }}
                        />
                        <p className="font-bold text-lg text-gray-900">
                            {__('Extendify Launch', 'extendify')}
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
                            className="bg-gray-100 w-full h-64 bg-cover border border-gray-200"
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
