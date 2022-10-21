import { ExternalLink, Spinner } from '@wordpress/components'
import { useEffect, useState } from '@wordpress/element'
import { __, _n, sprintf } from '@wordpress/i18n'
import classNames from 'classnames'
import { Checkmark } from '@onboarding/svg'
import { getOption } from '@assist/api/WPApi'
import { useAdminColors } from '@assist/hooks/useAdminColors'
import { useTasks } from '@assist/hooks/useTasks'
import { useGlobalStore } from '@assist/state/Global'
import {
    useSelectionStore,
    useSelectionStoreReady,
} from '@assist/state/Selections'
import { useTasksStoreReady, useTasksStore } from '@assist/state/Tasks'
import { UpdateLogo } from '@assist/tasks/UpdateLogo'
import { UpdateSiteIcon } from '@assist/tasks/UpdateSiteIcon'

export const TasksList = () => {
    const { isCompleted, seeTask } = useTasksStore()
    const { tasks, loading, error } = useTasks()
    const readyTasks = useTasksStoreReady()
    const readyPlugins = useSelectionStoreReady()
    const pluginBasedGoals = useSelectionStore((state) =>
        state.plugins?.reduce(
            (acc, plugin) => [...acc, ...(plugin?.goals ?? [])],
            [],
        ),
    )
    // Filter out tasks that have goal dependencies that don't match the user's goals
    const tasksFiltered = tasks?.filter((task) => {
        // If no goals, show the task
        if (!task?.goals?.length) return true
        // Check if task.goals intersect with pluginBasedGoals
        return task?.goals?.some((goal) => pluginBasedGoals.includes(goal))
    })
    const remainingTasks = tasksFiltered?.reduce(
        (count, task) => count - Number(isCompleted(task.slug)),
        tasksFiltered.length,
    )

    useEffect(() => {
        if (!tasksFiltered?.length || !readyTasks) return
        // Mark all tasks as seen. If always seen they will not update.
        tasksFiltered.forEach((task) => seeTask(task.slug))
    }, [tasksFiltered, seeTask, readyTasks])

    if (loading || !readyTasks || !readyPlugins || error) {
        return (
            <div className="my-4 w-full flex items-center max-w-3/4 mx-auto bg-gray-100 p-12">
                <Spinner />
            </div>
        )
    }

    if (tasks?.length === 0 || tasksFiltered?.length === 0) {
        return (
            <div className="my-4 max-w-3/4 w-full mx-auto bg-gray-100 p-12">
                {__('No tasks found...', 'extendify')}
            </div>
        )
    }

    return (
        <div className="my-4 max-w-3/4 w-full mx-auto bg-gray-100 p-12 pt-10">
            <div className="mb-6 flex gap-2 items-center justify-center">
                <h2 className="my-0 text-lg text-center">
                    {__('Get ready to go live', 'extendify')}
                </h2>
                {remainingTasks > 0 && (
                    <span
                        title={sprintf(
                            _n(
                                '%s task remaining',
                                '%s tasks remaining',
                                remainingTasks,
                                'extendify',
                            ),
                            remainingTasks,
                        )}
                        className="rounded-full bg-gray-700 text-white text-base px-2 py-0 cursor-default">
                        {remainingTasks}
                    </span>
                )}
            </div>
            <div className="w-full">
                {tasksFiltered.map((task) => (
                    <TaskCheckBox key={task.slug} task={task} />
                ))}
            </div>
        </div>
    )
}

const TaskCheckBox = ({ task }) => {
    const { isCompleted, toggleCompleted } = useTasksStore()

    return (
        <div className="p-3 flex gap-3 justify-between border border-solid border-gray-400 bg-white mt-4 relative items-center">
            <div className="flex gap-3 w-4/5">
                <span className="block mt-1 relative self-start">
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
                <span className="flex flex-col">
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
            <ActionButton task={task} />
        </div>
    )
}

const ActionButton = ({ task }) => {
    const { pushModal } = useGlobalStore()
    const { mainColor } = useAdminColors()
    if (task?.slug === 'logo') {
        return (
            <button
                style={{ backgroundColor: mainColor }}
                className="px-4 py-3 text-white button-focus border-0 rounded relative z-10 cursor-pointer w-1/5"
                onClick={() => pushModal(UpdateLogo)}>
                {__('Upload', 'extendify')}
            </button>
        )
    }
    if (task?.slug === 'site-icon') {
        return (
            <button
                style={{ backgroundColor: mainColor }}
                className="px-4 py-3 text-white button-focus border-0 rounded relative z-10 cursor-pointer w-1/5"
                onClick={() => pushModal(UpdateSiteIcon)}>
                {__('Upload', 'extendify')}
            </button>
        )
    }
    if (task?.slug === 'edit-homepage') {
        return <EditHomePageButton />
    }
    return null
}

const EditHomePageButton = () => {
    const [homepageId, setHomepageId] = useState(0)
    const { mainColor } = useAdminColors()
    useEffect(() => {
        getOption('page_on_front').then(setHomepageId)
    }, [homepageId])
    const handleClick = () => {
        window.open(
            `${window.extAssistData.adminUrl}post.php?post=${homepageId}&action=edit`,
            '_blank',
        )
    }

    if (!homepageId) return null
    return (
        <button
            style={{ backgroundColor: mainColor }}
            className="px-4 py-3 text-white button-focus border-0 rounded relative z-10 cursor-pointer w-1/5 disabled:bg-gray-700"
            onClick={handleClick}>
            {__('Edit now', 'extendify')}
        </button>
    )
}
