import { Spinner } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { TourItem } from '@assist/components/task-items/TourItem'
import { useTours } from '@assist/hooks/useTours'
import { useTourStoreReady } from '@assist/state/Tours'

export const ToursList = () => {
    const { tours, loading, error } = useTours()
    const readyTours = useTourStoreReady()

    if (loading || !readyTours || error) {
        return (
            <div className="my-4 w-full flex justify-center mx-auto border border-gray-400 p-2 lg:p-8">
                <Spinner />
            </div>
        )
    }

    if (tours?.length === 0) {
        return (
            <div
                className="my-4 w-full mx-auto border border-gray-400 p-2 lg:p-8"
                data-test="no-tours-found">
                {__('No tours found...', 'extendify')}
            </div>
        )
    }

    return (
        <div
            className="all-tours w-full border border-b-0 border-gray-400"
            data-test="all-tours">
            {tours.map((tour) => (
                <TourItemWrapper key={tour.slug} tour={tour} />
            ))}
        </div>
    )
}

const TourItemWrapper = ({ tour }) => (
    <div className="p-3 flex gap-3 justify-between border-0 border-b border-gray-400 bg-white relative items-center">
        <TourItem tour={tour} />
    </div>
)
