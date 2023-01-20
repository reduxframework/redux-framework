import {
    useCallback,
    useEffect,
    useState,
    useRef,
    useMemo,
} from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { getStyles } from '@onboarding/api/DataApi'
import { SkeletonLoader } from '@onboarding/components/SkeletonLoader'
import { StylePreview } from '@onboarding/components/StyledPreview'
import { useFetch } from '@onboarding/hooks/useFetch'
import { useIsMountedLayout } from '@onboarding/hooks/useIsMounted'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { pageState } from '@onboarding/state/factory'

export const fetcher = (params) => getStyles(params)
export const fetchData = (siteType) => {
    siteType = siteType ?? useUserSelectionStore?.getState().siteType
    return {
        key: 'site-layout',
        siteType: siteType?.slug ?? 'default',
        styles: siteType?.styles ?? [],
    }
}
export const state = pageState('Layout', (set, get) => ({
    title: __('Layout', 'extendify'),
    default: undefined,
    showInSidebar: true,
    ready: false,
    isDefault: () =>
        useUserSelectionStore.getState().style?.slug === get().default?.slug,
}))
export const SiteLayout = () => {
    const { style, variation, setStyle } = useUserSelectionStore()
    const { data: styleData, loading } = useFetch(fetchData, fetcher)
    const once = useRef(false)
    const stylesRef = useRef()
    const isMounted = useIsMountedLayout()
    const [styles, setStyles] = useState([])

    useEffect(() => {
        state.setState({ ready: !loading })
    }, [loading])

    useEffect(() => {
        if (!styleData?.length) return
        ;(async () => {
            for (const style of styleData) {
                if (!isMounted.current) return
                setStyles((styles) => [...styles, style])
                await new Promise((resolve) => setTimeout(resolve, 1000))
            }
        })()
    }, [styleData, isMounted])

    useEffect(() => {
        if (!styles?.length || style) return
        setStyle(styles[0])
        state.setState({ default: styles[0] })
    }, [variation, styles, style, setStyle])

    useEffect(() => {
        if (!styles?.length || once.current || !style) return
        once.current = true
        // Focus the first style
        stylesRef?.current?.querySelector('[role=button]')?.focus()
    }, [styles, style])

    return (
        <PageLayout>
            <div>
                <h1
                    className="text-3xl text-partner-primary-text mb-4 mt-0"
                    data-test="layout-heading">
                    {__(
                        'Now pick a layout for your site homepage.',
                        'extendify',
                    )}
                </h1>
                <p className="text-base opacity-70 mb-0">
                    {__('You can personalize this later.', 'extendify')}
                </p>
            </div>
            <div className="w-full">
                <h2 className="text-lg m-0 mb-4 text-gray-900">
                    {loading
                        ? __(
                              'Please wait a moment while we generate style previews...',
                              'extendify',
                          )
                        : __('Pick your homepage layout', 'extendify')}
                </h2>
                <div
                    ref={stylesRef}
                    className="flex gap-6 flex-wrap justify-center"
                    data-test="layout-preview-wrapper">
                    {styles?.map((style) => (
                        <StylePreviewWrapper key={style.slug} style={style} />
                    ))}
                    {styleData?.slice(styles?.length).map((data) => (
                        <div
                            key={data.slug}
                            style={{ height: 497, width: 352 }}
                            className="lg:flex gap-6 relative">
                            <SkeletonLoader context="style" />
                        </div>
                    ))}
                </div>
            </div>
        </PageLayout>
    )
}

const StylePreviewWrapper = ({ style }) => {
    const { setStyle, style: currentStyle } = useUserSelectionStore()
    const onSelect = useCallback((style) => setStyle(style), [setStyle])
    const context = useMemo(
        () => ({
            type: 'style',
            detail: style.slug,
            measure: true,
        }),
        [style],
    )
    return (
        <div className="relative" style={{ height: 497, width: 352 }}>
            <StylePreview
                style={style}
                context={context}
                onSelect={onSelect}
                active={currentStyle?.slug === style.slug}
                blockHeight={497}
            />
        </div>
    )
}
