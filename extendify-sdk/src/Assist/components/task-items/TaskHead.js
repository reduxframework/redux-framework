import { useEffect } from '@wordpress/element'
import { completedDependency } from '@assist/api/Data'
import { useTaskDependencies } from '@assist/hooks/useFetch'
import { useTasksStore } from '@assist/state/Tasks'

export const TaskHead = ({ task, children }) => {
    const { isCompleted, isAvailable, completeTask, setAvailable } =
        useTasksStore()
    const { slug, doneDependencies } = task
    // Some tasks have a dependency we look for before showing them
    // in case we want to pre-emptively mark it as completed
    const { data, loading } = useTaskDependencies(
        `tasks${slug}}`,
        () => {
            return doneDependencies
                ? completedDependency(slug)
                : // If no dependencies, return false to NOT mark it as completed
                  { data: false, loading: false }
        },
        !isCompleted(slug), // refresh when not completed
    )

    useEffect(() => {
        if (loading) return
        if (data) completeTask(slug)
        // All tasks are available by default currently,
        // but some we may want to mark as completed before showing
        setAvailable(slug)
    }, [data, loading, completeTask, slug, setAvailable, task])

    if (!isAvailable(slug)) return null

    return children ?? null
}
