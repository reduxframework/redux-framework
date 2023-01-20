import { Spinner } from '@wordpress/components'
import { sprintf, __ } from '@wordpress/i18n'
import { Icon, chevronRightSmall } from '@wordpress/icons'
import { useRecommendations } from '@assist/hooks/useRecommendations'

export const Recommendations = () => {
    const { recommendations, loading, error } = useRecommendations()

    if (loading || error) {
        return (
            <div className="w-full flex justify-center mx-auto border border-gray-400 p-2 lg:p-4">
                <Spinner />
                {error && <p className="text-sm text-red-500">{error}</p>}
            </div>
        )
    }

    if (recommendations?.length === 0) {
        return (
            <div className="w-full mx-auto border border-gray-400 p-2 lg:p-4">
                {__('No recommendations found...', 'extendify')}
            </div>
        )
    }

    return (
        <div className="w-full border border-gray-400 mx-auto text-base">
            <h2 className="text-base m-0 border-b border-gray-400 p-3">
                {__('Recommendatons', 'extendify')}
            </h2>
            <div className="w-full mx-auto text-base">
                {recommendations
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
                                className="p-3 flex gap-3 justify-between border-0 border-b border-gray-400 bg-white relative items-center">
                                <span className="m-0 p-0 text-base">
                                    {title}
                                </span>
                                <a
                                    className="px-4 py-2 w-max button-focus border border-design-main text-design-main rounded relative z-10 cursor-pointer bg-white text-center no-underline text-sm"
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
                    className="inline-flex items-center no-underline text-base">
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
