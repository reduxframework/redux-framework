import useSWRImmutable from 'swr/immutable'
import {
    getSupportArticles,
    getSupportArticleCategories,
    getSupportArticle,
} from '@assist/api/Data'

export const useSupportArticles = () => {
    const { data, error } = useSWRImmutable('support-articles', async () => {
        const response = await getSupportArticles()
        if (!response?.data || !Array.isArray(response.data)) {
            console.error(response)
            throw new Error('Bad data')
        }
        return response.data
    })
    return { data, error, loading: !data && !error }
}

export const useSupportArticleCategories = () => {
    const { data, error } = useSWRImmutable(
        'support-article-categories',
        async () => {
            const response = await getSupportArticleCategories()
            if (!response?.data || !Array.isArray(response.data)) {
                console.error(response)
                throw new Error('Bad data')
            }
            return response.data
        },
    )
    return { data: data, error, loading: !data && !error }
}

export const useSupportArticle = (slug) => {
    const { data, error } = useSWRImmutable(
        `support-article-${slug}`,
        async () => {
            const response = await getSupportArticle(slug)
            if (!response?.data || !Array.isArray(response.data)) {
                console.error(response)
                throw new Error('Bad data')
            }
            return response.data?.[0] ?? {}
        },
    )
    return { data, error, loading: !data && !error }
}
