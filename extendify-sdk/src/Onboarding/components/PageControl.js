import { __ } from '@wordpress/i18n'
import { ProgressBar } from '@onboarding/components/ProgressBar'
import { useGlobalStore } from '@onboarding/state/Global'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

export const PageControl = () => {
    const nextPage = usePagesStore((state) => state.nextPage)
    const previousPage = usePagesStore((state) => state.previousPage)
    const currentPageIndex = usePagesStore((state) => state.currentPageIndex)
    const totalPages = usePagesStore((state) => state.count())
    const canLaunch = useUserSelectionStore((state) => state.canLaunch())
    const onLastPage = currentPageIndex === totalPages - 1
    const onFirstPage = currentPageIndex === 0

    return (
        <div className="flex items-center justify-between space-x-2">
            <div className="flex-1"></div>
            <ProgressBar
                currentPageIndex={currentPageIndex}
                totalPages={totalPages}
            />
            <div className="flex space-x-2 flex-1 justify-end">
                {onFirstPage || (
                    <button
                        className="px-4 py-3 bg-transparent hover:bg-gray-200 font-medium button-focus focus:bg-gray-100"
                        type="button"
                        onClick={previousPage}>
                        {__('Previous', 'extendify')}
                    </button>
                )}
                {canLaunch && onLastPage ? (
                    <button
                        className="px-4 py-3 font-bold bg-partner-primary-bg text-partner-primary-text button-focus"
                        type="button"
                        onClick={() =>
                            useGlobalStore.setState({ generating: true })
                        }>
                        {__('Launch site', 'extendify')}
                    </button>
                ) : (
                    <button
                        className="px-4 py-3 font-bold bg-partner-primary-bg text-partner-primary-text button-focus"
                        type="button"
                        onClick={nextPage}>
                        {__('Next', 'extendify')}
                    </button>
                )}
            </div>
        </div>
    )
}
