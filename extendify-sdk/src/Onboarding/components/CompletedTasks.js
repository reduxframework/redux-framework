import { useMemo } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { usePagesStore } from '@onboarding/state/Pages'
import { Checkmark, Radio } from '@onboarding/svg'

export const CompletedTasks = ({ disabled = false }) => {
    const { setPage } = usePagesStore()
    const pages = usePagesStore((state) => state.pages)
    const currentPageIndex = usePagesStore((state) => state.currentPageIndex)
    const watched = useMemo(() => {
        return Array.from(pages.values())
            .map((task, index) => ({ ...task, pageIndex: index }))
            .filter((page) => page?.state.getState()?.showInSidebar)
    }, [pages])
    const lowestIndexWatched = useMemo(() => {
        return watched.reduce(
            (lowest, page) => Math.min(lowest, page.pageIndex),
            Infinity,
        )
    }, [watched])

    if (!watched?.length || currentPageIndex < lowestIndexWatched) {
        return null
    }

    return (
        <div className="hidden md:block mt-20">
            <h3 className="text-sm text-partner-primary-text uppercase">
                {__('Steps', 'extendify')}
            </h3>
            <ul data-test="sidebar-step-list">
                {watched.map((page) => (
                    <li
                        key={page?.state.getState()?.title}
                        data-test={page?.state.getState()?.title}
                        className={classNames('text-base', {
                            hidden: page.pageIndex > currentPageIndex,
                            'line-through opacity-60':
                                page.pageIndex < currentPageIndex,
                            'hover:opacity-100 hover:no-underline': !disabled,
                        })}>
                        <button
                            className={classNames(
                                'bg-transparent p-0 text-partner-primary-text flex items-center',
                                {
                                    'cursor-pointer':
                                        page.pageIndex < currentPageIndex &&
                                        !disabled,
                                },
                            )}
                            type="button"
                            disabled={disabled}
                            onClick={() => setPage(page?.pageIndex)}>
                            {page.pageIndex < currentPageIndex ? (
                                <Checkmark className="text-partner-primary-text h-6 w-6 mr-1" />
                            ) : (
                                <Radio className="text-partner-primary-text h-6 w-6 mr-1" />
                            )}
                            {page?.state.getState()?.title}
                        </button>
                    </li>
                ))}
            </ul>
        </div>
    )
}
