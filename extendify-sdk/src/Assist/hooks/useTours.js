import useSWRImmutable from 'swr/immutable'
import { getTours } from '@assist/api/Data'

export const useTours = () => {
    const { data: tours, error } = useSWRImmutable('tours', async () => {
        const response = await getTours()

        if (!response?.data || !Array.isArray(response.data)) {
            console.error(response)
            throw new Error('Bad data')
        }

        return response.data
    })

    return { tours, error, loading: !tours && !error }
}
