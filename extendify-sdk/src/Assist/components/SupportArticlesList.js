import { Spinner } from '@wordpress/components'
import { useEffect, useLayoutEffect, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Icon, chevronRightSmall, chevronLeft } from '@wordpress/icons'
import parse from 'html-react-parser'
import { getArticleRedirect } from '@assist/api/Data'
import { router } from '@assist/hooks/useRouter'
import {
    useSupportArticles,
    useSupportArticleCategories,
    useSupportArticle,
} from '@assist/hooks/useSupportArticles'
import { useHelpCenterStore } from '@assist/state/HelpCenter'

export const SupportArticlesList = () => {
    const { articles, pushArticle, clearArticles } = useHelpCenterStore()
    const { data: categories, error: catError } = useSupportArticleCategories()
    const { data: articlesList, loading, error } = useSupportArticles()

    useLayoutEffect(() => {
        // This will clear the articles if they re-press the nav tab
        const handle = () => clearArticles()
        router.onRouteChange(handle)
        return () => router.removeOnRouteChange(handle)
    }, [clearArticles])

    if (error || catError) {
        return (
            <div className="my-4 w-full mx-auto bg-white border border-gray-400 p-2 lg:p-8">
                {__('There was an error loading articles', 'extendify')}
            </div>
        )
    }

    if (loading || !categories) {
        return (
            <div className="my-4 w-full flex justify-center mx-auto bg-white border border-gray-400 p-2 lg:p-8">
                <Spinner />
            </div>
        )
    }

    if (articlesList && articlesList?.length === 0) {
        return (
            <div className="my-4 w-full mx-auto bg-white border border-gray-400 p-2 lg:p-8">
                {__('No support articles found...', 'extendify')}
            </div>
        )
    }

    if (articles?.length > 0) {
        return <SupportArticle />
    }

    return (
        <div className="my-4 w-full mx-auto text-base bg-white border border-gray-400 p-2 lg:p-8">
            <div className="grid grid-cols-3">
                {categories.map(({ slug, title }) => (
                    <div key={slug} className="mb-10">
                        <h3 className="mt-0">{title}</h3>
                        {articlesList
                            .filter((article) =>
                                article.category.includes(title),
                            )
                            .map(({ slug, extendifyTitle }) => (
                                <button
                                    key={slug}
                                    type="button"
                                    className="flex items-center justify-between no-underline hover:underline text-black hover:text-partner-primary-bg bg-transparent cursor-pointer"
                                    onClick={() =>
                                        pushArticle({
                                            slug,
                                            title: extendifyTitle,
                                        })
                                    }>
                                    <span>{extendifyTitle}</span>
                                    <Icon
                                        icon={chevronRightSmall}
                                        className="fill-current"
                                    />
                                </button>
                            ))}
                    </div>
                ))}
            </div>
        </div>
    )
}

const SupportArticle = () => {
    const { articles, pushArticle, popArticle, updateTitle } =
        useHelpCenterStore()
    const articleRef = useRef()
    const slug = articles?.[0]?.slug
    const { data: article, error, loading } = useSupportArticle(slug)
    const title = article?.title?.rendered

    useEffect(() => {
        if (!slug || !title) return
        updateTitle(slug, title)
    }, [title, updateTitle, slug])

    useEffect(() => {
        if (!articleRef.current) return
        const links = articleRef.current?.querySelectorAll('a')
        const handleInternal = async (e) => {
            e.preventDefault()
            // Could be the parent element so check both
            const link = e.target?.href ?? e.target?.closest('a')?.href
            const { pathname } = new URL(link)
            const slug = pathname.split('/').filter(Boolean)?.at(-1)

            // Both the new docs site and the old may have redirects
            const { data } = await getArticleRedirect(pathname)
            if (!data) {
                // If nothing useful was returned, it could be the new docs site
                if (pathname.startsWith('/documentation/article/')) {
                    return pushArticle({ slug, title: undefined })
                }
                // But if not then just open the link in a new tab
                return window.open(`https://wordpress.org${pathname}`, '_blank')
            }
            // Finally load the article
            pushArticle({ slug: data.split('/').filter(Boolean)?.at(-1) })
        }

        const handleExternal = (e) => {
            e.preventDefault()
            window.open(e.target.href, '_blank')
        }

        const handleNoOp = (e) => e.preventDefault()

        links.forEach((link) => {
            const { hash, host, pathname } = new URL(link.href)
            // Hash links should be disabled since they don't work properly
            if (
                (hash && host === window.location.host) ||
                pathname.startsWith('/support/category')
            ) {
                link.addEventListener('click', handleNoOp)
                link.setAttribute('aria-disabled', 'true')
                link.classList.add('link-disabled')
                return
            }
            // if link is to an image or a file, remove it
            const pattern =
                /\.(jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|ppt|pptx)$/
            if (pathname.match(pattern)) {
                link.addEventListener('click', handleNoOp)
                return
            }
            if (
                pathname.startsWith('/documentation/article') ||
                pathname.startsWith('/support/article')
            ) {
                link.addEventListener('click', handleInternal)
                return
            }
            // If the link is something else, then open in a new tab
            link.addEventListener('click', handleExternal)
            const svg =
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="components-external-link__icon css-rvs7bx esh4a730" aria-hidden="true" focusable="false"><path d="M18.2 17c0 .7-.6 1.2-1.2 1.2H7c-.7 0-1.2-.6-1.2-1.2V7c0-.7.6-1.2 1.2-1.2h3.2V4.2H7C5.5 4.2 4.2 5.5 4.2 7v10c0 1.5 1.2 2.8 2.8 2.8h10c1.5 0 2.8-1.2 2.8-2.8v-3.6h-1.5V17zM14.9 3v1.5h3.7l-6.4 6.4 1.1 1.1 6.4-6.4v3.7h1.5V3h-6.3z"></path></svg>'
            const svgEl = document.createElement('span')
            svgEl.innerHTML = svg
            link.appendChild(svgEl)
        })
        return () => {
            links.forEach((link) => {
                link?.removeEventListener('click', handleInternal)
                link?.removeEventListener('click', handleExternal)
                link?.removeEventListener('click', handleNoOp)
            })
        }
    }, [article, pushArticle])

    if (loading) {
        return (
            <div className="my-4 w-full flex justify-center mx-auto bg-white border border-gray-400 p-2 lg:p-8">
                <Spinner />
            </div>
        )
    }

    if (error) {
        return (
            <div className="my-4 w-full mx-auto bg-white border border-gray-400 p-2 lg:p-8">
                {__('There was an error loading this article', 'extendify')}
            </div>
        )
    }

    return (
        <>
            <Breadcrumbs />
            <article className="relative mb-4 w-full mx-auto text-base bg-white border border-gray-400 p-2 lg:p-8">
                <div className="flex justify-between items-center">
                    <h1 className="m-0 text-2xl">{title}</h1>
                    <button
                        className="px-4 py-3 text-xs border-0 rounded cursor-pointer bg-gray-100 text-center no-underline flex items-center"
                        onClick={popArticle}>
                        <Icon icon={chevronLeft} className="fill-current" />
                        {__('Back', 'extendify')}
                    </button>
                </div>
                <div ref={articleRef} className="extendify-documentation">
                    {article?.content?.rendered &&
                        parse(article?.content?.rendered)}
                </div>

                <div className="flex justify-end">
                    <button
                        className="px-4 py-3 text-xs border-0 rounded cursor-pointer bg-gray-100 text-center no-underline flex items-center"
                        onClick={popArticle}>
                        <Icon icon={chevronLeft} className="fill-current" />
                        {__('Back', 'extendify')}
                    </button>
                </div>
            </article>
        </>
    )
}

const Breadcrumbs = () => {
    const { articles, popArticle, clearArticles } = useHelpCenterStore()
    return (
        <div className="flex items-center mb-3">
            <button
                className="underline p-0 text-xs border-0 rounded cursor-pointer bg-gray-100 text-center no-underliner"
                onClick={clearArticles}>
                {__('Home', 'extendify')}
            </button>
            {articles?.[1] && (
                <>
                    <Icon icon={chevronRightSmall} className="fill-current" />
                    <button
                        className="underline p-0 text-xs border-0 rounded cursor-pointer bg-gray-100 text-center no-underliner"
                        onClick={popArticle}>
                        {articles?.[1]?.title ?? articles?.[1]?.slug}
                    </button>
                </>
            )}
            {articles?.[0] && (
                <>
                    <Icon icon={chevronRightSmall} className="fill-current" />
                    <span>{articles?.[0]?.title ?? articles?.[0]?.slug}</span>
                </>
            )}
        </div>
    )
}
