import { Spinner } from '@wordpress/components'
import { sprintf, __ } from '@wordpress/i18n'
import { Icon, chevronRightSmall } from '@wordpress/icons'
import { useRecommendations } from '@assist/hooks/useRecommendations'

export const Recommendations = () => {
    const { recommendations, loading, error } = useRecommendations()

    // Get partner name from globals
    const partnerName = window.extAssistData.partnerName
    // Check if activePartnersName has something in it and if it matches the partner name
    const activePartnerRecs = recommendations?.filter(
        (rec) =>
            rec.activePartnersName !== null &&
            rec.activePartnersName?.includes(partnerName),
    )
    // Remove all excluded partners and remove where activePartnersName is set to something
    const filteredRecs = recommendations?.filter(
        (rec) =>
            !rec.excludedPartnersName?.includes(partnerName) &&
            rec.activePartnersName === null,
    )
    // Now combine the active recommendations if available with the filtered ones, active ones come before all others
    const finalRecs =
        activePartnerRecs?.length > 0
            ? [...activePartnerRecs, ...filteredRecs]
            : filteredRecs

    if (loading || error) {
        return (
            <div className="w-full flex justify-center mx-auto bg-gray-50 border border-gray-400 p-2 lg:p-4">
                <Spinner />
                {error && <p className="text-sm text-red-500">{error}</p>}
            </div>
        )
    }

    if (recommendations?.length === 0 || finalRecs?.length === 0) {
        return (
            <div className="w-full mx-auto border border-gray-400 p-2 lg:p-4">
                {__(
                    "All set! We don't have any recommendations right now for your site.",
                    'extendify',
                )}
            </div>
        )
    }

    return (
        <div className="w-full border border-gray-400 mx-auto text-base bg-white">
            <h2 className="text-base m-0 border-b border-gray-400 bg-gray-50 p-3">
                {__('Recommendations', 'extendify')}
            </h2>
            <div className="w-full mx-auto text-base">
                {finalRecs
                    .slice(0, 5)
                    .map(
                        ({
                            slug,
                            title,
                            linkType,
                            externalLink,
                            internalLink,
                            buttonText,
                        }) => (
                            <div
                                key={slug}
                                className="p-3 flex gap-3 justify-between border-0 border-b border-gray-400 relative items-center">
                                <span className="m-0 p-0 text-base">
                                    {title}
                                </span>
                                <a
                                    className="px-4 py-2 w-max button-focus border border-design-main text-design-main rounded relative z-10 cursor-pointer text-center no-underline text-sm"
                                    href={
                                        linkType === 'externalLink'
                                            ? `${externalLink}`
                                            : `${window.extAssistData.adminUrl}${internalLink}`
                                    }
                                    target={
                                        linkType === 'externalLink'
                                            ? '_blank'
                                            : ''
                                    }
                                    rel={
                                        linkType === 'externalLink'
                                            ? 'noreferrer'
                                            : undefined
                                    }>
                                    <span>{buttonText}</span>
                                </a>
                            </div>
                        ),
                    )}
            </div>
            <div className="p-3">
                <a
                    href="admin.php?page=extendify-assist#recommendations"
                    className="inline-flex items-center no-underline text-base text-design-main hover:underline">
                    {sprintf(
                        __('View all %s', 'extendify'),
                        recommendations?.length,
                    )}
                    <Icon icon={chevronRightSmall} className="fill-current" />
                </a>
            </div>
        </div>
    )
}
