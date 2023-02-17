import { __ } from '@wordpress/i18n'
import { Logo } from '@onboarding/svg'
import { useDesignColors } from '@assist/hooks/useDesignColors'
import { useRouter } from '@assist/hooks/useRouter'
import { Nav } from '@assist/pages/parts/Nav'

export const Header = () => {
    const { pages, navigateTo, current } = useRouter()
    useDesignColors()
    return (
        <header className="w-full flex bg-design-main">
            <div className="max-w-screen-lg w-full mx-auto mt-auto flex flex-col">
                <div className="flex justify-between items-center my-10 mx-4 xl:mx-0">
                    {window.extAssistData?.partnerLogo && (
                        <div className="w-32 sm:w-40 h-16 flex flex-wrap content-center">
                            <img
                                className="max-h-full max-w-full"
                                src={window.extAssistData.partnerLogo}
                                alt={window.extAssistData.partnerName}
                            />
                        </div>
                    )}
                    {!window.extAssistData?.partnerLogo && (
                        <Logo className="logo text-design-text w-32 sm:w-40" />
                    )}
                    <div className="flex flex-wrap">
                        <Nav
                            pages={pages}
                            activePage={current?.slug}
                            setActivePage={navigateTo}
                        />
                        <a
                            className="flex items-center gap-1 text-sm text-design-main cursor-pointer rounded-sm px-3 py-1 bg-white border-none no-underline"
                            href={window.extAssistData.home}
                            target="_blank"
                            rel="noreferrer">
                            {__('View site', 'extendify')}
                        </a>
                    </div>
                </div>
            </div>
        </header>
    )
}
