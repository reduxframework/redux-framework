import { useMemo } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import classNames from 'classnames'
import { getTemplate } from '@onboarding/api/DataApi'
import { StylePreview } from '@onboarding/components/StyledPreview'
import { useFetch } from '@onboarding/hooks/useFetch'
import { findTheCode } from '@onboarding/lib/util'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { Checkmark } from '@onboarding/svg'

export const fetcher = (data) => getTemplate(data)
export const PagePreview = ({
    page,
    blockHeight,
    required = false,
    displayOnly = false,
    title = '',
}) => {
    const { siteType, style, toggle, has } = useUserSelectionStore()
    const isHome = page?.slug === 'home'
    const { data: pageData } = useFetch(
        {
            siteType: siteType.slug,
            layoutType: page.slug,
            baseLayout: isHome
                ? siteType.slug.startsWith('blog')
                    ? style?.blogBaseLayout
                    : style?.homeBaseLayout
                : null,
            kit: page.slug !== 'home' ? style?.kit : null,
        },
        fetcher,
    )
    if (displayOnly) {
        return (
            <div
                className="text-base p-2 bg-transparent overflow-hidden rounded-lg border border-gray-100"
                style={{ height: blockHeight }}>
                {title && (
                    <div className="p-3 pb-0 bg-white text-left">{title}</div>
                )}
                <StylePreviewWrapper
                    key={style?.recordId}
                    page={page}
                    measure={false}
                    blockHeight={blockHeight}
                    style={{
                        ...style,
                        code: findTheCode({ template: pageData }),
                    }}
                />
            </div>
        )
    }

    return (
        <div
            data-test="page-preview"
            role="button"
            tabIndex={0}
            aria-label={__('Press to select', 'extendify')}
            disabled={required}
            className="text-base p-0 bg-transparent overflow-hidden rounded-lg border border-gray-100 button-focus"
            onClick={() => required || toggle('pages', page)}
            title={
                required && title
                    ? sprintf(
                          // translators: %s is the name of a page (e.g. Home, Blog, About)
                          __('%s page is required', 'extendify'),
                          title,
                      )
                    : sprintf(
                          // translators: %s is the name of a page (e.g. Home, Blog, About)
                          __('Toggle %s page', 'extendify'),
                          title,
                      )
            }
            onKeyDown={(e) => {
                if (['Enter', 'Space', ' '].includes(e.key)) {
                    if (!required) toggle('pages', page)
                }
            }}>
            <div className="border-gray-100 border-b-0 min-w-sm z-30 relative bg-white pt-3 px-3 pb-1.5 flex justify-between items-center">
                {title && (
                    <div
                        className={classNames('flex items-center', {
                            'text-gray-700': !has('pages', page),
                        })}>
                        <span className="text-left">{title}</span>
                        {required && (
                            <span className="w-4 h-4 text-base leading-none pl-2 mr-6 dashicons dashicons-lock"></span>
                        )}
                    </div>
                )}
                {has('pages', page) ? (
                    <div
                        className={classNames('w-5 h-5 rounded-sm', {
                            'bg-gray-700': required,
                            'bg-partner-primary-bg': !required,
                        })}>
                        <Checkmark className="text-white w-5" />
                    </div>
                ) : (
                    <div
                        className={classNames('border w-5 h-5 rounded-sm', {
                            'border-gray-700': required,
                            'border-partner-primary-bg': !required,
                        })}></div>
                )}
            </div>
            <div className="p-2 relative" style={{ height: blockHeight - 44 }}>
                <StylePreviewWrapper
                    key={style?.recordId}
                    page={page}
                    blockHeight={blockHeight}
                    style={{
                        ...style,
                        code: findTheCode({ template: pageData }),
                    }}
                />
            </div>
        </div>
    )
}

const StylePreviewWrapper = ({
    page,
    style,
    measure = true,
    blockHeight = false,
}) => {
    const context = useMemo(
        () => ({
            type: 'page',
            detail: page.slug,
            measure,
        }),
        [page, measure],
    )
    return (
        <StylePreview
            style={style}
            context={context}
            blockHeight={blockHeight}
        />
    )
}
