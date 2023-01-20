import { useTasksStore } from '@assist/state/Tasks'
import { useTourStore } from '@assist/state/Tours'
import welcomeTour from '@assist/tours/welcome.js'

export const TourButton = ({ task, className }) => {
    const { startTour } = useTourStore()
    const { isCompleted } = useTasksStore()

    return (
        <button
            className={className}
            type="button"
            onClick={() => startTour(welcomeTour)}>
            {isCompleted(task.slug) ? task.buttonTextDone : task.buttonTextToDo}
        </button>
    )
}
