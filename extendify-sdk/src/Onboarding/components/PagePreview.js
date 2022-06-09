import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { getTemplate } from '@onboarding/api/DataApi'
import { StylePreview } from '@onboarding/components/StyledPreview'
import { useFetch } from '@onboarding/hooks/useFetch'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { Checkmark } from '@onboarding/svg'
import { findTheCode } from '../lib/util'

export const fetcher = (data) => getTemplate(data)
export const PagePreview = ({
    page,
    blockHeight,
    lock = false,
    displayOnly = false,
}) => {
    const { siteType, style, toggle, has } = useUserSelectionStore()
    const isHome = page?.slug === 'home'
    const { data: pageData } = useFetch(
        {
            siteType: siteType.slug,
            layoutType: page.slug,
            baseLayout: isHome ? style?.homeBaseLayout : null,
            kit: page.slug !== 'home' ? style?.kit : null,
        },
        fetcher,
    )
    if (displayOnly) {
        return (
            <div className="text-base p-0 bg-transparent overflow-hidden rounded-lg border border-gray-100">
                <div className="border-gray-100 border-b p-2 flex justify-between min-w-sm">
                    {page.title}
                </div>
                <StylePreview
                    blockHeight={blockHeight}
                    key={style?.recordId}
                    style={{
                        ...style,
                        code: findTheCode({ template: pageData }),
                    }}
                />
            </div>
        )
    }

    return (
        <div>
            <div
                role="button"
                tabIndex={0}
                aria-label={__('Press to select', 'extendify')}
                disabled={lock}
                className="text-base p-0 bg-transparent overflow-hidden rounded-lg border border-gray-100 button-focus"
                title={lock ? __('This page is required', 'extendify') : null}
                onKeyDown={(e) => {
                    if (['Enter', 'Space', ' '].includes(e.key)) {
                        if (!lock) toggle('pages', page)
                    }
                }}
                onClick={() => lock || toggle('pages', page)}>
                <div className="border-gray-100 border-b p-2 flex justify-between min-w-sm">
                    <div
                        className={classNames('flex items-center', {
                            'text-gray-700': !has('pages', page),
                        })}>
                        <span>{page.title}</span>
                        {lock && (
                            <span className="w-4 h-4 text-base leading-none pl-2 mr-6 dashicons dashicons-lock"></span>
                        )}
                    </div>
                    {has('pages', page) && (
                        <Checkmark className="text-partner-primary-bg w-6" />
                    )}
                </div>
                <StylePreview
                    blockHeight={blockHeight}
                    key={style?.recordId}
                    style={{
                        ...style,
                        code: findTheCode({ template: pageData }),
                    }}
                />
            </div>
        </div>
    )
}
