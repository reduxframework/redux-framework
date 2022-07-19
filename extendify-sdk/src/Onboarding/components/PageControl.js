import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { useGlobalStore } from '@onboarding/state/Global'
import { usePagesStore } from '@onboarding/state/Pages'
import { useProgressStore } from '@onboarding/state/Progress'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { LeftArrowIcon, RightArrowIcon } from '@onboarding/svg'

export const PageControl = () => {
    const { nextPage, previousPage, currentPageIndex, pages } = usePagesStore()
    const totalPages = usePagesStore((state) => state.count())
    const canLaunch = useUserSelectionStore((state) => state.canLaunch())
    const touchedPages = useProgressStore((state) => state.touched)
    const onLastPage = currentPageIndex === totalPages - 1
    const onFirstPage = currentPageIndex === 0
    const currentPageKey = Array.from(pages.keys())[currentPageIndex]
    const touched = touchedPages.includes(currentPageKey)
    const skippable = pages.get(currentPageKey)?.metadata?.skippable

    return (
        <div className="flex items-center space-x-2">
            <div
                className={classNames('flex flex-1', {
                    'justify-end': currentPageKey === 'welcome',
                    'justify-between': currentPageKey !== 'welcome',
                })}>
                {onFirstPage || (
                    <button
                        className="flex items-center px-4 py-3 text-partner-primary-bg hover:bg-gray-100 font-medium button-focus focus:bg-gray-100"
                        type="button"
                        onClick={previousPage}>
                        <RightArrowIcon className="h-5 w-5" />
                        {__('Back', 'extendify')}
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
                ) : touched || skippable ? (
                    <button
                        className="px-4 py-3 font-bold bg-partner-primary-bg text-partner-primary-text button-focus"
                        type="button"
                        onClick={nextPage}>
                        {__('Next', 'extendify')}
                    </button>
                ) : (
                    <button
                        className="flex items-center px-4 py-3 text-partner-primary-bg hover:bg-gray-100 font-medium button-focus focus:bg-gray-100"
                        type="button"
                        onClick={nextPage}>
                        {__('Skip', 'extendify')}
                        <LeftArrowIcon className="h-5 w-5" />
                    </button>
                )}
            </div>
        </div>
    )
}
