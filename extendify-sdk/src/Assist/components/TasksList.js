import { ExternalLink, Spinner } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { Checkmark } from '@onboarding/svg'
import { useTasks } from '@assist/hooks/useTasks'
import { useTasksStoreReady, useTasksStore } from '@assist/state/Tasks'

export const TasksList = () => {
    const { tasks, loading, error } = useTasks()
    const ready = useTasksStoreReady()

    if (loading || !ready || error) {
        return (
            <div className="my-4 w-full flex items-center max-w-3/4 mx-auto bg-gray-100 p-12">
                <Spinner />
            </div>
        )
    }

    if (tasks.length === 0) {
        return (
            <div className="my-4 max-w-3/4 w-full mx-auto bg-gray-100 p-12">
                {__('No tasks found...', 'extendify')}
            </div>
        )
    }

    return (
        <div className="my-4 max-w-3/4 w-full mx-auto bg-gray-100 p-12">
            <div className="flex gap-2 items-center justify-center">
                <h2 className="mb-0 text-lg text-center">
                    {__('Get ready to go live', 'extendify')}
                </h2>
                <span className="rounded-full bg-gray-700 text-white text-base px-3 py-0.5">
                    {tasks.length}
                </span>
            </div>
            <div className="w-full">
                {tasks.map((task) => (
                    <TaskCheckBox key={task.slug} task={task} />
                ))}
            </div>
        </div>
    )
}

const TaskCheckBox = ({ task }) => {
    const { isCompleted, toggleCompleted } = useTasksStore()
    return (
        <div className="p-3 flex border border-solid border-gray-400 bg-white mt-4 relative">
            <span className="block mt-1 relative">
                <input
                    id={`task-${task.slug}`}
                    type="checkbox"
                    className={classNames(
                        'hide-checkmark h-6 w-6 rounded-full border-gray-400 outline-none focus:ring-wp ring-partner-primary-bg ring-offset-2 ring-offset-white m-0 focus:outline-none focus:shadow-none',
                        {
                            'bg-partner-primary-bg': isCompleted(task.slug),
                        },
                    )}
                    checked={isCompleted(task.slug)}
                    value={task.slug}
                    name={task.slug}
                    onChange={() => toggleCompleted(task.slug)}
                />
                <Checkmark className="text-white fill-current absolute h-6 w-6 block top-0" />
            </span>
            <span className="flex flex-col pl-2">
                <label
                    htmlFor={`task-${task.slug}`}
                    className="text-base font-semibold">
                    <span
                        aria-hidden="true"
                        className="absolute inset-0"></span>
                    {task.title}
                </label>
                <span>
                    {task.description}{' '}
                    {task.link && (
                        <ExternalLink
                            className="relative z-10"
                            href={task.link}>
                            {__('Learn more', 'extendify')}
                        </ExternalLink>
                    )}
                </span>
            </span>
        </div>
    )
}
