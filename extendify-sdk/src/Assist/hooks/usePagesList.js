import useSWRImmutable from 'swr/immutable'
import { getLaunchPages } from '@assist/api/WPApi'

export const usePagesList = () => {
    const { data: pages, error } = useSWRImmutable('pages-list', async () => {
        const response = await getLaunchPages()
        if (!response?.data || !Array.isArray(response.data)) {
            console.error(response)
            throw new Error('Bad data')
        }
        return response.data
    })
    return { pages, error, loading: !pages && !error }
}
