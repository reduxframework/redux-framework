import { useGlobalStore } from '@assist/state/Global'
import { useTasksStore } from '@assist/state/Tasks'
import { UpdateLogo } from '@assist/tasks/UpdateLogo'
import { UpdateSiteDescription } from '@assist/tasks/UpdateSiteDescription'
import { UpdateSiteIcon } from '@assist/tasks/UpdateSiteIcon'

export const ModalButton = ({ task, className }) => {
    const { pushModal } = useGlobalStore()
    const { isCompleted } = useTasksStore()
    const Components = {
        UpdateLogo,
        UpdateSiteDescription,
        UpdateSiteIcon,
    }

    if (!Components[task.modalFunction]) return null

    return (
        <button
            className={className}
            type="button"
            onClick={() => pushModal(Components[task.modalFunction])}>
            {isCompleted(task.slug) ? task.buttonTextDone : task.buttonTextToDo}
        </button>
    )
}
