import { ExternalLink } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { Logo } from '@onboarding/svg'
import { useDesignColors } from '@assist/hooks/useDesignColors'
import { useRouter } from '@assist/hooks/useRouter'
import { Nav } from '@assist/pages/parts/Nav'

export const Header = () => {
    const { pages, navigateTo, current } = useRouter()
    useDesignColors()
    return (
        <header className="w-full min-h-40 flex bg-design-main">
            <div className="max-w-screen-lg w-full mx-auto mt-auto flex flex-col">
                <div className="flex justify-between items-center my-8 mx-4 xl:mx-0">
                    {window.extAssistData?.partnerLogo && (
                        <div className="flex flex-col items-end">
                            <div className="w-32 sm:w-40 h-16">
                                <img
                                    className="block max-h-full max-w-full self-start"
                                    src={window.extAssistData.partnerLogo}
                                    alt={window.extAssistData.partnerName}
                                />
                            </div>
                            <Logo className="mt-1 logo text-design-text w-16 sm:w-24" />
                        </div>
                    )}
                    {!window.extAssistData?.partnerLogo && (
                        <Logo className="logo text-design-text w-32 sm:w-40" />
                    )}
                    <ExternalLink
                        className="flex items-center gap-1 text-sm sm:text-base text-design-main cursor-pointer rounded px-3 sm:px-6 py-2 bg-white border-none no-underline"
                        href={window.extAssistData.home}>
                        {__('View site', 'extendify')}
                    </ExternalLink>
                </div>
                <div className="flex mx-4 xl:mx-0 overflow-x-auto">
                    <Nav
                        pages={pages}
                        activePage={current?.slug}
                        setActivePage={navigateTo}
                    />
                </div>
            </div>
        </header>
    )
}
