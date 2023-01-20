import { Spinner } from '@wordpress/components'
import { useEffect } from '@wordpress/element'
import { sprintf, __ } from '@wordpress/i18n'
import { Icon, chevronRightSmall } from '@wordpress/icons'
import { TaskItemOld } from '@assist/components/task-items/TaskItemOld'
import { useTasks } from '@assist/hooks/useTasks'
import { useSelectionStoreReady } from '@assist/state/Selections'
import { useTasksStoreReady, useTasksStore } from '@assist/state/Tasks'
import { Confetti } from '@assist/svg'

export const TasksList = () => {
    const { seeTask, isCompleted } = useTasksStore()
    const { tasks, loading, error } = useTasks()
    const readyTasks = useTasksStoreReady()
    const readyPlugins = useSelectionStoreReady()

    // Now filter all tasks that are not completed yet
    const notCompleted = tasks?.filter((task) => !isCompleted(task.slug))

    useEffect(() => {
        if (!notCompleted?.length || !readyTasks) return
        // Mark all tasks as seen. If always seen they will not update.
        notCompleted.forEach((task) => seeTask(task.slug))
    }, [notCompleted, seeTask, readyTasks])

    if (loading || !readyTasks || !readyPlugins || error) {
        return (
            <div className="my-4 w-full flex justify-center mx-auto border border-gray-400 p-2 lg:p-4">
                <Spinner />
            </div>
        )
    }

    if (tasks?.length === 0) {
        return (
            <div className="my-4 w-full mx-auto border border-gray-400 p-2 lg:p-4">
                {__('No tasks found...', 'extendify')}
            </div>
        )
    }

    return (
        <div className="my-4 w-full border border-gray-400 mx-auto text-base">
            <h2 className="text-base m-0 border-b border-gray-400 p-3">
                {__('Tasks', 'extendify')}
            </h2>
            {notCompleted.length === 0 ? (
                <div className="flex flex-col items-center justify-center border-b border-gray-400 p-2 lg:p-8">
                    <Confetti aria-hidden={true} />
                    <p className="mb-0 text-lg font-bold">
                        {__('All caught up!', 'extendify')}
                    </p>
                    <p className="mb-0 text-sm">
                        {__(
                            'Congratulations! Take a moment to celebrate.',
                            'extendify',
                        )}
                    </p>
                </div>
            ) : (
                notCompleted
                    .slice(0, 5)
                    .map((task) => (
                        <TaskItemWrapper key={task.slug} task={task} />
                    ))
            )}
            <div className="p-3">
                <a
                    href="admin.php?page=extendify-assist#tasks"
                    className="inline-flex items-center no-underline text-base">
                    {notCompleted?.length > 0
                        ? sprintf(__('View all %s', 'extendify'), tasks?.length)
                        : __('View completed tasks', 'extendify')}
                    <Icon icon={chevronRightSmall} className="fill-current" />
                </a>
            </div>
        </div>
    )
}

const TaskItemWrapper = ({ task, Action }) => (
    <div className="p-3 flex gap-3 justify-between border-0 border-b border-gray-400 bg-white relative items-center">
        <TaskItemOld task={task} Action={Action} />
    </div>
)
