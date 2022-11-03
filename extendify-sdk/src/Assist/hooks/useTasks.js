import useSWRImmutable from 'swr/immutable'
import { getTasks } from '@assist/api/Data'

export const useTasks = () => {
    const { data: tasks, error } = useSWRImmutable('tasks', async () => {
        const response = await getTasks()
        if (!response?.data || !Array.isArray(response.data)) {
            console.error(response)
            throw new Error('Bad data')
        }
        return response.data
    })

    return { tasks, error, loading: !tasks && !error }
}
