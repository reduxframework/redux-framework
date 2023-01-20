import useSWRImmutable from 'swr/immutable'
import { getQuickLinks } from '@assist/api/Data'

export const useQuickLinks = () => {
    const { data: quickLinks, error } = useSWRImmutable(
        'quicklinks',
        async () => {
            const response = await getQuickLinks()
            if (!response?.data || !Array.isArray(response.data)) {
                console.error(response)
                throw new Error('Bad data')
            }
            return response.data
        },
    )

    return { quickLinks, error, loading: !quickLinks && !error }
}
