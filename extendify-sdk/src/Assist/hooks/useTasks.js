import useSWRImmutable from 'swr/immutable'
import { getTasks } from '@assist/api/Data'
import { getActivePlugins } from '@assist/api/WPApi'

export const useTasks = () => {
    const { data: tasks, error } = useSWRImmutable('tasks', async () => {
        const { data: activePlugins } = await getActivePlugins()
        const response = await getTasks()
        if (!response?.data || !Array.isArray(response.data)) {
            console.error(response)
            throw new Error('Bad data')
        }
        return response.data?.filter((task) => {
            // If no plugins, show the task
            if (!task?.plugins?.length) return true
            // Check if task.plugins intersect with activePlugins
            return task?.plugins?.some((plugin) =>
                activePlugins?.includes(plugin),
            )
        })
    })

    // Filter out tasks that have plugin dependencies that don't match the user's plugins

    return { tasks, error, loading: !tasks && !error }
}
