import { Spinner } from '@wordpress/components'
import { useEffect, useState } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { motion, AnimatePresence } from 'framer-motion'
import { TaskItemOld } from '@assist/components/task-items/TaskItemOld'
import { useTasks } from '@assist/hooks/useTasks'
import { useSelectionStoreReady } from '@assist/state/Selections'
import { useTasksStoreReady, useTasksStore } from '@assist/state/Tasks'
import { Confetti } from '@assist/svg'

export const TasksList = () => {
    const { seeTask, isCompleted } = useTasksStore()
    const { tasks, loading, error } = useTasks()
    const [showCompleted, setShowCompleted] = useState(false)
    const readyTasks = useTasksStoreReady()
    const readyPlugins = useSelectionStoreReady()

    // Now filter all tasks that are marked as completed
    const completed = tasks?.filter((task) => isCompleted(task.slug))
    // Now filter all tasks that are not completed yet
    const notCompleted = tasks?.filter((task) => !isCompleted(task.slug))
    // Toggle show/hide completed tasks
    const toggleCompletedTasks = () => {
        showCompleted ? setShowCompleted(false) : setShowCompleted(true)
    }

    useEffect(() => {
        if (!tasks?.length || !readyTasks) return
        // Mark all tasks as seen. If always seen they will not update.
        tasks.forEach((task) => seeTask(task.slug))
    }, [tasks, seeTask, readyTasks])

    if (loading || !readyTasks || !readyPlugins || error) {
        return (
            <div className="my-4 w-full flex justify-center mx-auto border border-gray-400 p-2 lg:p-8">
                <Spinner />
            </div>
        )
    }

    if (tasks?.length === 0 || tasks?.length === 0) {
        return (
            <div
                className="my-4 w-full mx-auto border border-gray-400 p-2 lg:p-8"
                data-test="no-tasks-found">
                {__('No tasks found...', 'extendify')}
            </div>
        )
    }

    return (
        <div className="my-4 w-full mx-auto border border-gray-400 p-2 lg:p-6">
            <div className="mb-6 flex gap-0 flex-col">
                <h2 className="my-0 text-lg">
                    {__('Personalized tasks for your site', 'extendify')}
                </h2>
                <div className="flex gap-1">
                    <span>
                        {sprintf(
                            // translators: %s is the number of tasks
                            __('%s completed', 'extendify'),
                            completed.length,
                        )}
                    </span>
                    {completed.length > 0 && (
                        <>
                            <span>&middot;</span>
                            <button
                                className="underline cursor-pointer p-0 bg-white"
                                onClick={toggleCompletedTasks}>
                                {showCompleted
                                    ? __('Hide', 'extendify')
                                    : __('Show', 'extendify')}
                            </button>
                        </>
                    )}
                </div>
            </div>
            <div
                className="uncompleted-tasks w-full border border-b-0 border-gray-400"
                data-test="uncompleted-tasks">
                {showCompleted ? (
                    notCompleted.map((task) => (
                        <TaskItemWrapper key={task.slug} task={task} />
                    ))
                ) : notCompleted.length === 0 ? (
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
                    <AnimatePresence>
                        {notCompleted.map((task) => (
                            <motion.div
                                key={task.slug}
                                variants={{
                                    fade: {
                                        opacity: 0,
                                        x: 15,
                                        transition: {
                                            duration: 0.5,
                                        },
                                    },
                                    shrink: {
                                        height: 0,
                                        transition: {
                                            delay: 0.5,
                                            duration: 0.2,
                                        },
                                    },
                                }}
                                exit={['fade', 'shrink']}>
                                <TaskItemWrapper task={task} />
                            </motion.div>
                        ))}
                    </AnimatePresence>
                )}
            </div>
            {showCompleted && (
                <div className="completed-tasks w-full border border-b-0 border-t-0 border-gray-400">
                    {completed.map((task) => (
                        <TaskItemWrapper key={task.slug} task={task} />
                    ))}
                </div>
            )}
        </div>
    )
}

const TaskItemWrapper = ({ task, Action }) => (
    <div className="p-3 flex gap-3 justify-between border-0 border-b border-gray-400 bg-white relative items-center">
        <TaskItemOld task={task} Action={Action} />
    </div>
)
