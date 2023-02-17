import { __ } from '@wordpress/i18n'
import { useTourStore } from '@assist/state/Tours'
import tours from '@assist/tours/tours.js'

export const TourButton = ({ task, className }) => {
    const { startTour, wasOpened } = useTourStore()

    if (!tours[task.slug]) return null

    const getButtonText = () => {
        const { buttonTextDone, buttonTextToDo } = task
        if (wasOpened(task.slug)) {
            return buttonTextDone ?? __('Restart Tour', 'extendify')
        }
        return buttonTextToDo ?? __('Start Tour', 'extendify')
    }

    return (
        <button
            className={className}
            type="button"
            onClick={() => startTour(tours[task.slug])}>
            {getButtonText()}
        </button>
    )
}
