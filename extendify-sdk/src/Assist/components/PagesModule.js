import { Spinner } from '@wordpress/components'
import { useEffect, useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { getLaunchPages } from '@assist/api/WPApi'
import { maybeHttps } from '@assist/lib/util'

export const PagesModule = () => {
    const [pages, setPages] = useState()

    useEffect(() => {
        if (!pages) {
            getLaunchPages().then((res) => {
                setPages(res.data)
            })
        }
    }, [pages, setPages])

    return (
        <div className="my-4 text-base">
            <h2 className="text-lg mb-3">{__('Pages', 'extendify')}:</h2>
            <div className="grid grid-cols-2 gap-4">
                {!pages && (
                    <div className="mt-2">
                        <Spinner />
                    </div>
                )}
                {pages && !pages.length && (
                    <div className="mt-2">
                        {__('No Launch pages found...', 'extendify')}
                    </div>
                )}
                {pages &&
                    pages.map((page) => {
                        return (
                            <div
                                key={page.ID}
                                className="p-3 flex items-center justify-between border border-solid border-gray-400">
                                <div className="flex items-center">
                                    <span className="dashicons dashicons-saved"></span>
                                    <span className="pl-1 font-semibold">
                                        {page.post_title}
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
                        )
                    })}
            </div>
        </div>
    )
}
