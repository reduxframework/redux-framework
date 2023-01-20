import { Dropdown, Icon } from '@wordpress/components'
import { useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { InternalLinkButton } from '@assist/components/buttons/InternalLinkButton'
import { ModalButton } from '@assist/components/buttons/ModalButton'
import { TourButton } from '@assist/components/buttons/TourButton'
import { TaskHead } from '@assist/components/task-items/TaskHead'
import { useTasksStore } from '@assist/state/Tasks'

export const TaskItem = ({ task, className }) => {
    const actions = {
        modal: ModalButton,
        tour: TourButton,
        'internal link': InternalLinkButton,
    }
    const Action = task?.taskType ? actions[task.taskType] : null
    const { dismissTask, seeTask } = useTasksStore()

    useEffect(() => {
        task.slug && seeTask(task.slug)
    }, [seeTask, task?.slug])

    return (
        <TaskHead task={task}>
            <div className={className}>
                <h2
                    className="m-0 p-0 text-lg font-semibold leading-normal"
                    style={{
                        color: 'inherit',
                    }}>
                    {task.title}
                </h2>
            </div>
            <div className="flex gap-2 items-center">
                {Action && (
                    <Action
                        task={task}
                        className="px-4 py-3 text-white text-xs border-0 rounded cursor-pointer bg-gray-900 text-center no-underline"
                    />
                )}
                <Dropdown
                    position="bottom left"
                    renderContent={({ onClose }) => (
                        <button
                            onClick={() => {
                                onClose()
                                dismissTask(task.slug)
                            }}
                            type="button"
                            className="-m-2 p-2 px-4 text-gray-900 text-sm border-0 cursor-pointer rounded-none bg-white hover:bg-gray-50 text-center no-underline">
                            {__('Dismiss', 'extendify')}
                        </button>
                    )}
                    renderToggle={({ onToggle }) => (
                        <button
                            onClick={onToggle}
                            type="button"
                            className="p-0 text-white text-xs border-0 rounded cursor-pointer bg-transparent text-center no-underline transform rotate-90">
                            <Icon icon="ellipsis" className="" />
                        </button>
                    )}
                />
            </div>
        </TaskHead>
    )
}
