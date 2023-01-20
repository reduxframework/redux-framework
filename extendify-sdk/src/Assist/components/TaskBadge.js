import { useTasksStoreReady, useTasksStore } from '@assist/state/Tasks'

export const TaskBadge = (props) => {
    const { availableTasks, isCompleted } = useTasksStore()
    const ready = useTasksStoreReady()
    if (!ready) return null
    const taskCount = availableTasks?.filter((t) => !isCompleted(t)).length ?? 0
    if (taskCount === 0) return null
    return (
        <span className="awaiting-mod" {...props}>
            {taskCount > 9 ? '9' : taskCount}
        </span>
    )
}
