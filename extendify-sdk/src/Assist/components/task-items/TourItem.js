import { __ } from '@wordpress/i18n'
import { TourButton } from '@assist/components/buttons/TourButton'
import { useTourStore } from '@assist/state/Tours'
import { TaskHead } from './TaskHead'

export const TourItem = ({ tour }) => {
    const { wasOpened } = useTourStore()
    const { slug } = tour

    return (
        <TaskHead task={tour}>
            <div className="flex gap-3 items-center">
                <div className="sr-only">
                    {wasOpened(slug)
                        ? __('Completed', 'extendify')
                        : __('Not completed', 'extendify')}
                </div>
                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 16 16"
                    aria-hidden="true"
                    focusable="false"
                    className="flex-shrink-0 w-6 h-6 rounded-full text-gray-400">
                    {/* <!-- The background --> */}
                    <circle
                        className="checkbox__background"
                        r="5"
                        cx="8"
                        cy="8"
                        stroke={
                            wasOpened(slug)
                                ? 'var(--ext-design-main, #3959e9)'
                                : 'currentColor'
                        }
                        fill={
                            wasOpened(slug)
                                ? 'var(--ext-design-main, #3959e9)'
                                : 'none'
                        }
                        strokeWidth="1"
                    />
                    {/* <!-- The checkmark--> */}
                    <polyline
                        className="checkbox__checkmark"
                        points="5,8 8,10 11,6"
                        stroke={wasOpened(slug) ? '#fff' : 'transparent'}
                        strokeWidth="1"
                        fill="none"
                    />
                </svg>
                <div className="flex items-center">
                    <span className="text-base font-semibold mr-2">
                        {tour.title}
                    </span>
                </div>
            </div>
            <div className="flex items-center justify-end gap-3">
                <TourButton
                    task={tour}
                    className="px-4 py-2 w-max button-focus bg-white border border-design-main text-design-main rounded relative z-10 cursor-pointer text-center no-underline text-sm"
                />
            </div>
        </TaskHead>
    )
}
