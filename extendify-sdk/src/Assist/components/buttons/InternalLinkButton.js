import { useEffect, useState } from '@wordpress/element'
import { getOption } from '@assist/api/WPApi'
import { useTasksStore } from '@assist/state/Tasks'

export const InternalLinkButton = ({ task, className }) => {
    const { completeTask } = useTasksStore()
    const [link, setLink] = useState(null)
    const handleClick = () => {
        // If no dependency then complete the task
        !task.doneDependencies && completeTask(task.slug)
    }
    useEffect(() => {
        if (task.slug === 'edit-homepage') {
            const split = task.internalLink.split('$')
            getOption('page_on_front').then((id) => {
                setLink(split[0] + id + split[1])
            })
            return
        }
        setLink(task.internalLink)
    }, [task])

    if (!link) return null
    return (
        <a
            href={window.extAssistData.adminUrl + link}
            target="_blank"
            rel="noreferrer"
            className={className}
            onClick={handleClick}>
            {task.buttonTextToDo}
        </a>
    )
}
