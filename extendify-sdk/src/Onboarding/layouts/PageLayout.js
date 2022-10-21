import { __ } from '@wordpress/i18n'
import { CompletedTasks } from '@onboarding/components/CompletedTasks'
import { PageControl } from '@onboarding/components/PageControl'
import { Logo } from '@onboarding/svg'

export const PageLayout = ({ children, includeNav = true }) => {
    return (
        <div className="flex flex-col md:flex-row">
            <div className="bg-partner-primary-bg text-partner-primary-text py-12 px-10 md:h-screen flex flex-col justify-between md:w-40vw md:max-w-md flex-shrink-0">
                <div className="max-w-prose md:max-w-sm pr-8">
                    <div className="md:min-h-48">
                        {window.extOnbData?.partnerLogo && (
                            <div className="pb-8">
                                <img
                                    style={{ maxWidth: '200px' }}
                                    src={window.extOnbData.partnerLogo}
                                    alt={window.extOnbData?.partnerName ?? ''}
                                />
                            </div>
                        )}
                        {children[0]}
                    </div>
                    <CompletedTasks disabled={!includeNav} />
                </div>

                <div className="hidden md:flex items-center space-x-3">
                    <span className="opacity-70 text-xs">
                        {__('Powered by', 'extendify')}
                    </span>
                    <span className="relative">
                        <Logo className="logo text-partner-primary-text w-28" />
                        <span className="absolute -bottom-2 right-3 font-semibold tracking-tight">
                            Launch
                        </span>
                    </span>
                </div>
            </div>
            <div className="flex-grow md:h-screen md:overflow-y-scroll">
                {includeNav ? (
                    <div className="pt-12 pb-4 sticky top-0 bg-white z-50 w-full px-4 xl:px-0">
                        <div className="max-w-onboarding-content mx-auto">
                            <PageControl />
                        </div>
                    </div>
                ) : null}
                <div className="mt-8 mb-8 xl:mb-12 flex justify-center max-w-onboarding-content mx-auto px-4 xl:px-0">
                    {children[1]}
                </div>
            </div>
        </div>
    )
}
