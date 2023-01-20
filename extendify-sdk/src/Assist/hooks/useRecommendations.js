import useSWRImmutable from 'swr/immutable'
import { getRecommendations } from '@assist/api/Data'

export const useRecommendations = () => {
    const { data: recommendations, error } = useSWRImmutable(
        'recommendations',
        async () => {
            const response = await getRecommendations()
            if (!response?.data || !Array.isArray(response.data)) {
                console.error(response)
                throw new Error('Bad data')
            }
            return response.data
        },
    )

    return { recommendations, error, loading: !recommendations && !error }
}
