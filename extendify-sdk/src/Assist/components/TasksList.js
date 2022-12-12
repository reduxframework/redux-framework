import { ExternalLink, Spinner } from '@wordpress/components'
import { useEffect, useState } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import classNames from 'classnames'
import { motion, AnimatePresence } from 'framer-motion'
import { Checkmark } from '@onboarding/svg'
import { getOption } from '@assist/api/WPApi'
import { useActivePlugins } from '@assist/hooks/useActivePlugins'
import { useAdminColors } from '@assist/hooks/useAdminColors'
import { useTasks } from '@assist/hooks/useTasks'
import { useGlobalStore } from '@assist/state/Global'
import { useSelectionStoreReady } from '@assist/state/Selections'
import { useTasksStoreReady, useTasksStore } from '@assist/state/Tasks'
import { UpdateLogo } from '@assist/tasks/UpdateLogo'
import { UpdateSiteDescription } from '@assist/tasks/UpdateSiteDescription'
import { UpdateSiteIcon } from '@assist/tasks/UpdateSiteIcon'
import { useTourStore } from '../state/Tours'
import welcomeTour from '../tours/welcome.js'

export const TasksList = () => {
    const { seeTask, completedTasks } = useTasksStore()
    const { tasks, loading, error } = useTasks()
    const { activePlugins } = useActivePlugins()
    const [showCompleted, setShowCompleted] = useState(false)
    const readyTasks = useTasksStoreReady()
    const readyPlugins = useSelectionStoreReady()

    // Filter out tasks that have plugin dependencies that don't match the user's plugins
    const tasksFiltered = tasks?.filter((task) => {
        // If no plugins, show the task
        if (!task?.plugins?.length) return true
        // Check if task.plugins intersect with activePlugins
        return task?.plugins?.some((plugin) => activePlugins.includes(plugin))
    })
    // Divide filtered tasks by completion status, first simplify the array
    const completedTasksArray = completedTasks.map((task) => task.id)
    // Now filter all tasks that are marked as completed
    const tasksCompleted = tasksFiltered?.filter((task) => {
        return completedTasksArray.includes(task.slug)
    })
    // Now filter all tasks that are not completed yet
    const tasksOpen = tasksFiltered?.filter((task) => {
        return !completedTasksArray.includes(task.slug)
    })
    // Toggle show/hide completed tasks
    const toggleCompletedTasks = () => {
        showCompleted ? setShowCompleted(false) : setShowCompleted(true)
    }

    useEffect(() => {
        if (!tasksFiltered?.length || !readyTasks) return
        // Mark all tasks as seen. If always seen they will not update.
        tasksFiltered.forEach((task) => seeTask(task.slug))
    }, [tasksFiltered, seeTask, readyTasks])

    useEffect(() => {
        const badgeCount = tasksFiltered?.length - tasksCompleted?.length
        document
            .querySelector(
                '#toplevel_page_extendify-admin-page.wp-has-current-submenu',
            )
            ?.classList.add('current')

        const badges = document.querySelectorAll(
            '.extendify-assist-badge-count',
        )
        for (const badge of badges) {
            if (badgeCount || badgeCount >= 0) {
                badge.style.opacity = badgeCount === 0 ? '0' : '1'
                badge.textContent = `${badgeCount}`
            }
        }
    }, [tasksFiltered, tasksCompleted])

    if (loading || !readyTasks || !readyPlugins || error) {
        return (
            <div className="my-4 w-full flex items-center lg:max-w-3/4 mx-auto bg-gray-100 p-4 lg:p-12">
                <Spinner />
            </div>
        )
    }

    if (tasks?.length === 0 || tasksFiltered?.length === 0) {
        return (
            <div
                className="my-4 lg:max-w-3/4 w-full mx-auto bg-gray-100 p-4 lg:p-12"
                data-test="no-tasks-found">
                {__('No tasks found...', 'extendify')}
            </div>
        )
    }

    return (
        <div className="my-4 lg:max-w-3/4 w-full mx-auto bg-gray-100 p-4 lg:p-12 pt-10">
            <div className="mb-6 flex gap-0 flex-col">
                <h2 className="my-0 text-lg">
                    {__('Get ready to go live', 'extendify')}
                </h2>
                <div className="flex gap-1">
                    <span>
                        {sprintf(
                            // translators: %s is the number of tasks
                            __('%s completed', 'extendify'),
                            tasksCompleted.length,
                        )}
                    </span>
                    {tasksCompleted.length > 0 && (
                        <>
                            <span>&middot;</span>
                            <button
                                className="underline cursor-pointer p-0"
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
                className="uncompleted-tasks w-full"
                data-test="uncompleted-tasks">
                {showCompleted ? (
                    tasksOpen.map((task) => (
                        <TaskCheckBox key={task.slug} task={task} />
                    ))
                ) : (
                    <AnimatePresence>
                        {tasksOpen.map((task) => (
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
                                <TaskCheckBox key={task.slug} task={task} />
                            </motion.div>
                        ))}
                    </AnimatePresence>
                )}
            </div>
            {showCompleted && (
                <div className="completed-tasks w-full">
                    {tasksCompleted.map((task) => (
                        <TaskCheckBox key={task.slug} task={task} />
                    ))}
                </div>
            )}
        </div>
    )
}

const TaskCheckBox = ({ task }) => {
    const { isCompleted, toggleCompleted } = useTasksStore()
    return (
        <div className="pt-4">
            <div className="p-3 flex gap-3 justify-between border border-solid border-gray-400 bg-white relative items-center">
                <div className="flex gap-3 w-4/5">
                    <span className="block mt-1 relative self-start">
                        <input
                            id={`task-${task.slug}`}
                            type="checkbox"
                            className={classNames(
                                'hide-checkmark h-6 w-6 rounded-full border-gray-400 outline-none focus:ring-wp ring-partner-primary-bg ring-offset-2 ring-offset-white m-0 focus:outline-none focus:shadow-none',
                                {
                                    'bg-partner-primary-bg': isCompleted(
                                        task.slug,
                                    ),
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
                {task.taskType === 'modal' && <ModalButton task={task} />}
                {task.taskType === 'internal link' && (
                    <InternalLinkButton task={task} />
                )}
                {task.taskType === 'tour' && <TourButton task={task} />}
            </div>
        </div>
    )
}

const TourButton = ({ task }) => {
    const { mainColor } = useAdminColors()
    const { startTour } = useTourStore()
    const { isCompleted } = useTasksStore()

    return (
        <button
            style={{ backgroundColor: mainColor }}
            className="px-4 py-3 text-white button-focus border-0 rounded relative z-10 cursor-pointer w-1/5"
            onClick={() => startTour(welcomeTour)}>
            {isCompleted(task.slug) ? task.buttonTextDone : task.buttonTextToDo}
        </button>
    )
}

const ModalButton = ({ task }) => {
    const { pushModal } = useGlobalStore()
    const { mainColor } = useAdminColors()
    const { isCompleted } = useTasksStore()
    const Components = {
        UpdateLogo,
        UpdateSiteDescription,
        UpdateSiteIcon,
    }

    if (!Components[task.modalFunction]) return null

    return (
        <button
            style={{ backgroundColor: mainColor }}
            className="px-4 py-3 text-white button-focus border-0 rounded relative z-10 cursor-pointer w-1/5"
            onClick={() => pushModal(Components[task.modalFunction])}>
            {isCompleted(task.slug) ? task.buttonTextDone : task.buttonTextToDo}
        </button>
    )
}

const InternalLinkButton = ({ task }) => {
    const [homepageId, setHomepageId] = useState(0)
    const { mainColor } = useAdminColors()
    const { completeTask } = useTasksStore()
    const handleClick = () => {
        completeTask(task.slug)
        if (task.slug === 'edit-homepage' && homepageId) {
            const split = task.internalLink.split('$')
            task.internalLink = split[0] + homepageId + split[1]
        }
        window.open(
            `${window.extAssistData.adminUrl + task.internalLink}`,
            '_blank',
        )
    }

    useEffect(() => {
        getOption('page_on_front').then(setHomepageId)
    }, [homepageId])

    if (!homepageId) return null

    return (
        <button
            style={{ backgroundColor: mainColor }}
            className="px-4 py-3 text-white button-focus border-0 rounded relative z-10 cursor-pointer w-1/5 disabled:bg-gray-700"
            onClick={handleClick}>
            {task.buttonTextToDo}
        </button>
    )
}
