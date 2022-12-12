import useSWRImmutable from 'swr/immutable'
import { getActivePlugins } from '@assist/api/WPApi'

export const useActivePlugins = () => {
    const { data: activePlugins, error } = useSWRImmutable(
        'active-plugins',
        async () => {
            const response = await getActivePlugins()
            if (!response?.data || !Array.isArray(response.data)) {
                console.error(response)
                throw new Error('Bad data')
            }
            return response.data
        },
    )
    return { activePlugins, error, loading: !activePlugins && !error }
}
