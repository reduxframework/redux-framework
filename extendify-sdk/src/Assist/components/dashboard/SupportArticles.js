import { Spinner } from '@wordpress/components'
import { sprintf, __ } from '@wordpress/i18n'
import { Icon, chevronRightSmall } from '@wordpress/icons'
import { useRouter } from '@assist/hooks/useRouter'
import { useSupportArticles } from '@assist/hooks/useSupportArticles'
import { useHelpCenterStore } from '@assist/state/HelpCenter'

export const SupportArticles = () => {
    const { data: articles, loading, error } = useSupportArticles()
    const { navigateTo } = useRouter()
    const { pushArticle, clearArticles } = useHelpCenterStore()

    if (loading || error) {
        return (
            <div className="w-full flex justify-center mx-auto bg-gray-50 border border-gray-400 p-2 lg:p-4">
                <Spinner />
                {error && <p className="text-sm text-red-500">{error}</p>}
            </div>
        )
    }

    if (articles && articles?.length === 0) {
        return (
            <div className="w-full mx-auto bg-white border border-gray-400 p-2 lg:p-4">
                {__('No support articles found...', 'extendify')}
            </div>
        )
    }

    return (
        <div className="w-full bg-white border border-gray-400 mx-auto text-base">
            <h2 className="text-base m-0 border-b border-gray-400 bg-gray-50 p-3">
                {__('Help Center', 'extendify')}
            </h2>
            <div className="w-full mx-auto text-base p-3">
                {articles.slice(0, 5).map(({ slug, extendifyTitle }) => (
                    <button
                        type="button"
                        key={slug}
                        onClick={(e) => {
                            e.preventDefault()
                            clearArticles()
                            pushArticle({ slug, title: extendifyTitle })
                            navigateTo('help-center')
                        }}
                        className="flex items-center justify-between no-underline hover:underline text-black hover:text-partner-primary-bg bg-transparent mb-2 w-full cursor-pointer">
                        <span>{extendifyTitle}</span>
                        <Icon
                            icon={chevronRightSmall}
                            className="fill-current"
                        />
                    </button>
                ))}
            </div>
            <div className="p-3 border-t border-gray-400">
                <a
                    href="admin.php?page=extendify-assist#help-center"
                    className="inline-flex items-center no-underline text-base text-design-main">
                    {sprintf(__('View all %s', 'extendify'), articles?.length)}
                    <Icon icon={chevronRightSmall} className="fill-current" />
                </a>
            </div>
        </div>
    )
}
