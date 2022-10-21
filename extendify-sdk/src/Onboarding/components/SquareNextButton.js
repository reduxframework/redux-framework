import { __ } from '@wordpress/i18n'
import { usePagesStore } from '@onboarding/state/Pages'
import { LeftArrowIcon } from '@onboarding/svg'

export const SquareNextButton = () => {
    const { nextPage, currentPageIndex, pages } = usePagesStore()
    const currentPageKey = Array.from(pages.keys())[currentPageIndex]
    const pageState = pages.get(currentPageKey).state.getState()

    return (
        <button
            className="flex items-center px-4 py-3 font-bold bg-partner-primary-bg text-partner-primary-text button-focus h-14 rounded-none"
            onClick={nextPage}
            disabled={!pageState.ready}
            type="button">
            {__('Next', 'extendify')}
            <LeftArrowIcon className="h-5 w-5" />
        </button>
    )
}
