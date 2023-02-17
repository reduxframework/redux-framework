import { PageControl } from '@onboarding/components/PageControl'

export const PageLayoutFull = ({ children, includeNav = true }) => {
    return (
        <div className="md:h-screen md:overflow-y-scroll">
            {includeNav ? (
                <div className="pt-12 pb-4 sticky top-0 bg-white z-50 w-full px-4 xl:px-0">
                    <div className="max-w-screen-xl mx-auto">
                        <PageControl />
                    </div>
                </div>
            ) : null}
            <div className="mt-8 mb-8 xl:mb-12 flex justify-center max-w-screen-xl mx-auto px-4 xl:px-0">
                {children}
            </div>
        </div>
    )
}
