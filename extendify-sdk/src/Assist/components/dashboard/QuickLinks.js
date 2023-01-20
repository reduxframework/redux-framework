import { Spinner } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { Icon, chevronRightSmall } from '@wordpress/icons'
import { useQuickLinks } from '@assist/hooks/useQuickLinks'

export const QuickLinks = () => {
    const { quickLinks, loading, error } = useQuickLinks()

    if (loading || error) {
        return (
            <div className="w-full flex justify-center mx-auto border border-gray-400 p-2 lg:p-4">
                <Spinner />
            </div>
        )
    }

    if (quickLinks.length === 0) {
        return (
            <div className="w-full mx-auto border border-gray-400 p-2 lg:p-4">
                {__('No quick links found...', 'extendify')}
            </div>
        )
    }

    return (
        <div className="w-full border border-gray-400 mx-auto text-base">
            <h2 className="text-base m-0 border-b border-gray-400 p-3">
                {__('Quick links', 'extendify')}
            </h2>
            <div className="grid grid-cols-1 xs:grid-cols-2 gap-4 p-3 py-4">
                {quickLinks.map((link) => (
                    <a
                        key={link.slug}
                        className="flex items-center no-underline hover:underline text-black hover:text-partner-primary-bg"
                        href={
                            link.slug == 'view-site'
                                ? `${window.extAssistData.home}`
                                : `${window.extAssistData.adminUrl}${link.internalLink}`
                        }>
                        <span>{link.name}</span>
                        <Icon
                            icon={chevronRightSmall}
                            className="fill-current"
                        />
                    </a>
                ))}
            </div>
        </div>
    )
}
