import { Spinner } from '@wordpress/components'
import { __, sprintf } from '@wordpress/i18n'
import { chevronRightSmall, Icon } from '@wordpress/icons'
import { TourButton } from '@assist/components/buttons/TourButton'
import { useTours } from '@assist/hooks/useTours'

export const Tours = () => {
    const { tours, loading, error } = useTours()

    if (loading || error) {
        return (
            <div className="w-full flex justify-center mx-auto bg-gray-50 border border-gray-400 p-2 lg:p-4">
                <Spinner />
                {error && <p className="text-sm text-red-500">{error}</p>}
            </div>
        )
    }

    if (tours.length === 0) {
        return (
            <div className="w-full mx-auto border border-gray-400 p-2 lg:p-4">
                {__('No tours found...', 'extendify')}
            </div>
        )
    }

    return (
        <div className="w-full border border-gray-400 mx-auto text-base bg-white">
            <h2 className="text-base m-0 border-b border-gray-400 bg-gray-50 p-3">
                {__('Tours', 'extendify')}
            </h2>
            <div className="w-full mx-auto text-base">
                {tours.slice(0, 5).map((tour) => (
                    <div
                        key={tour.slug}
                        className="p-3 flex gap-3 justify-between border-0 border-b border-gray-400 relative items-center">
                        <span className="m-0 p-0 text-base">{tour.title}</span>
                        <TourButton
                            key={tour.slug}
                            task={tour}
                            className="px-4 py-3 text-white button-focus border-0 rounded relative z-10 cursor-pointer md:w-48 disabled:bg-gray-700 bg-design-main text-center no-underline"
                        />
                    </div>
                ))}
            </div>
            <div className="p-3">
                <a
                    href="admin.php?page=extendify-assist#tours"
                    className="inline-flex items-center no-underline text-base text-design-main hover:underline">
                    {sprintf(__('View all %s', 'extendify'), tours?.length)}
                    <Icon icon={chevronRightSmall} className="fill-current" />
                </a>
            </div>
        </div>
    )
}
