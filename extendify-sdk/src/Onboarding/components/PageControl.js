import { useEffect, useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { useGlobalStore } from '@onboarding/state/Global'
import { usePagesStore } from '@onboarding/state/Pages'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { LeftArrowIcon, RightArrowIcon } from '@onboarding/svg'

export const PageControl = () => {
    const { previousPage, currentPageIndex, pages } = usePagesStore()
    const { openExitModal, setExitButtonHovered } = useGlobalStore()
    const onFirstPage = currentPageIndex === 0
    const currentPageKey = Array.from(pages.keys())[currentPageIndex]

    return (
        <div className="flex items-center space-x-2">
            {onFirstPage && (
                <div className="fixed top-0 right-0 px-3 md:px-6 py-2">
                    <button
                        className="flex items-center p-1 text-gray-900 font-medium button-focus md:focus:bg-transparent bg-transparent shadow-none"
                        type="button"
                        title={__('Exit Launch', 'extendify')}
                        onMouseEnter={setExitButtonHovered}
                        onClick={openExitModal}
                        data-test="exit-launch">
                        <span className="dashicons dashicons-no-alt text-white md:text-black"></span>
                    </button>
                </div>
            )}
            <div
                className={classNames('flex flex-1', {
                    'justify-end': currentPageKey === 'welcome',
                    'justify-between': currentPageKey !== 'welcome',
                })}>
                {onFirstPage || (
                    <button
                        className="flex items-center px-4 py-3 font-medium button-focus text-gray-900 bg-gray-100 hover:bg-gray-200 focus:bg-gray-200 bg-transparent"
                        type="button"
                        onClick={previousPage}
                        data-test="back-button">
                        <RightArrowIcon className="h-5 w-5" />
                        {__('Back', 'extendify')}
                    </button>
                )}
                {onFirstPage && (
                    <button
                        className="flex items-center px-4 py-3 font-medium button-focus text-gray-900 bg-gray-100 hover:bg-gray-200 focus:bg-gray-200 bg-transparent"
                        type="button"
                        onMouseEnter={setExitButtonHovered}
                        onClick={openExitModal}
                        data-test="exit-button">
                        <RightArrowIcon className="h-5 w-5" />
                        {__('Exit Launch', 'extendify')}
                    </button>
                )}
                <NextButton />
            </div>
        </div>
    )
}

const NextButton = () => {
    const { nextPage, currentPageIndex, pages } = usePagesStore()
    const totalPages = usePagesStore((state) => state.count())
    const canLaunch = useUserSelectionStore((state) => state.canLaunch())
    const onLastPage = currentPageIndex === totalPages - 1
    const currentPageKey = Array.from(pages.keys())[currentPageIndex]
    const pageState = pages.get(currentPageKey).state
    const [canProgress, setCanProgress] = useState(false)
    const showNextButton = () =>
        window.extOnbData?.activeTests?.['launch-site-vs-next'] === 'A'

    useEffect(() => {
        setCanProgress(pageState?.getState()?.ready)
        return pageState.subscribe(({ ready }) => setCanProgress(ready))
    }, [pageState, currentPageIndex])

    if (canLaunch && onLastPage) {
        return (
            <button
                className="flex items-center px-4 py-3 font-bold bg-partner-primary-bg text-partner-primary-text button-focus"
                onClick={() => {
                    useGlobalStore.setState({ generating: true })
                }}
                type="button"
                data-test="next-button">
                {showNextButton
                    ? __('Next', 'extendify')
                    : __('Launch site', 'extendify')}
                {showNextButton ? <LeftArrowIcon className="h-5 w-5" /> : null}
            </button>
        )
    }

    return (
        <button
            className="flex items-center px-4 py-3 font-bold bg-partner-primary-bg text-partner-primary-text button-focus"
            onClick={nextPage}
            disabled={!canProgress}
            type="button"
            data-test="next-button">
            {__('Next', 'extendify')}
            <LeftArrowIcon className="h-5 w-5" />
        </button>
    )
}
