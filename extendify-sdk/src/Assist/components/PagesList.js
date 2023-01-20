import { Spinner } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { usePagesList } from '@assist/hooks/usePagesList'
import { maybeHttps } from '@assist/util/util'

export const PagesList = () => {
    const { pages, loading, error } = usePagesList()

    if (loading || error) {
        return (
            <div className="my-4 w-full flex items-center lg:max-w-3/4 mx-auto p-4 lg:p-12">
                <Spinner />
            </div>
        )
    }

    if (pages.length === 0) {
        return (
            <div className="my-4 lg:max-w-3/4 w-full mx-auto bg-gray-100 p-4 lg:p-12">
                {__('No pages found...', 'extendify')}
            </div>
        )
    }

    return (
        <div className="my-4 text-base lg:max-w-3/4 w-full mx-auto p-4 lg:p-0">
            <h2 className="text-lg mb-3">{__('Pages', 'extendify')}:</h2>
            <div className="grid grid-cols-2 gap-4" data-test="page-list">
                {pages.map((page) => (
                    <div
                        key={page.ID}
                        className="p-3 flex items-center justify-between border border-solid border-gray-400">
                        <div className="flex items-center">
                            <span className="dashicons dashicons-saved" />
                            <span className="pl-1 font-semibold">
                                {page.post_title || __('Untitled', 'extendify')}
                            </span>
                        </div>
                        <div className="flex text-sm">
                            <span>
                                <a
                                    target="_blank"
                                    rel="noreferrer"
                                    href={maybeHttps(page.url)}>
                                    {__('View', 'extendify')}
                                </a>
                            </span>
                            <span className="mr-2 pl-2">
                                <a
                                    target="_blank"
                                    rel="noreferrer"
                                    href={`${window.extAssistData.adminUrl}post.php?post=${page.ID}&action=edit`}>
                                    {__('Edit', 'extendify')}
                                </a>
                            </span>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    )
}
