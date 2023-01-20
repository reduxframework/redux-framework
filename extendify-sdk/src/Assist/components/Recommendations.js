import { Spinner } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { useRecommendations } from '@assist/hooks/useRecommendations'

export const Recommendations = () => {
    const { recommendations, loading, error } = useRecommendations()

    if (loading || error) {
        return (
            <div className="my-4 w-full flex justify-center mx-auto border border-gray-400 p-2 lg:p-4">
                <Spinner />
            </div>
        )
    }

    if (recommendations.length === 0) {
        return (
            <div className="my-4 w-full mx-auto border border-gray-400 p-2 lg:p-4">
                {__('No recommendations found...', 'extendify')}
            </div>
        )
    }

    return (
        <div className="my-4 w-full mx-auto text-base">
            {recommendations.map(
                ({
                    slug,
                    title,
                    description,
                    linkType,
                    externalLink,
                    internalLink,
                    buttonText,
                }) => (
                    <div
                        key={slug}
                        className="mb-4 w-full border border-gray-400 p-4 flex flex-col">
                        <h3 className="m-0 mb-2 text-md font-bold">{title}</h3>
                        <p className="m-0 text-sm">{description}</p>
                        <a
                            className="px-4 py-3 mt-3 w-max button-focus border border-design-main text-design-main rounded relative z-10 cursor-pointer bg-white text-center no-underline"
                            href={
                                linkType === 'externalLink'
                                    ? `${externalLink}`
                                    : `${window.extAssistData.adminUrl}${internalLink}`
                            }
                            target={linkType === 'externalLink' ? '_blank' : ''}
                            // eslint-disable-next-line react/jsx-no-target-blank
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
    )
}
