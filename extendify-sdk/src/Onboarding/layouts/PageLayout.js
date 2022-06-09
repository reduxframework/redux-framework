import { __ } from '@wordpress/i18n'
import { PageControl } from '@onboarding/components/PageControl'
import { Logo } from '@onboarding/svg'

export const PageLayout = ({ children, includeNav = true }) => {
    return (
        <div className="flex flex-col md:flex-row">
            <div className="bg-partner-primary-bg text-partner-primary-text py-12 px-10 md:h-screen flex flex-col justify-between md:w-40vw md:max-w-md flex-shrink-0">
                <div className="max-w-sm pr-8">
                    {window.extOnbData?.partnerLogo && (
                        <div className="pb-8">
                            <img
                                src={window.extOnbData.partnerLogo}
                                alt={window.extOnbData?.partnerName ?? ''}
                            />
                        </div>
                    )}
                    {children[0]}
                </div>

                <div className="flex items-center space-x-3">
                    <span className="opacity-70 text-xs">
                        {__('Powered by', 'extendify')}
                    </span>
                    <span className="relative">
                        <Logo className="logo text-white w-28" />
                        <span className="absolute -bottom-2 right-3 font-semibold tracking-tight">
                            {__('Launch', 'extendify')}
                        </span>
                    </span>
                </div>
            </div>
            <div className="flex-grow md:h-screen md:overflow-y-scroll">
                {includeNav ? (
                    <div className="py-4 px-8 sticky top-0 bg-white z-50">
                        <PageControl />
                    </div>
                ) : null}
                <div className="mt-8 p-8 lg:px-12 flex justify-center">
                    {children[1]}
                </div>
            </div>
        </div>
    )
}
