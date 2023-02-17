import { useCallback, useEffect, useState, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { AnimatePresence, motion } from 'framer-motion'
import { getStyles } from '@onboarding/api/DataApi'
import { getThemeVariations } from '@onboarding/api/WPApi'
import { SmallPreview } from '@onboarding/components/SmallPreview'
import { useFetch } from '@onboarding/hooks/useFetch'
import { useIsMountedLayout } from '@onboarding/hooks/useIsMounted'
import { PageLayoutFull } from '@onboarding/layouts/PageLayoutFull'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { pageState } from '@onboarding/state/factory'
import { Checkmark } from '@onboarding/svg'

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
    const { data: styleData, loading } = useFetch(fetchData, fetcher)
    const isMounted = useIsMountedLayout()
    const [styles, setStyles] = useState([])
    const { data: variations } = useFetch('variations', getThemeVariations)
    const { setStyle, style: currentStyle } = useUserSelectionStore()
    const onSelect = useCallback((style) => setStyle(style), [setStyle])
    const wrapperRef = useRef()
    const once = useRef(false)

    useEffect(() => {
        state.setState({ ready: !loading })
    }, [loading])

    useEffect(() => {
        if (!styleData || !variations) return
        ;(async () => {
            for (const style of styleData) {
                if (!isMounted.current) return
                // Combine location variations with styles
                const variation = variations.find(
                    ({ title }) => title === style.label,
                )
                setStyles((styles) => [...styles, { ...style, variation }])
                // number between 750 and 1500 to make it less rigid
                const random = Math.floor(Math.random() * 750) + 750
                await new Promise((resolve) => setTimeout(resolve, random))
            }
        })()
    }, [styleData, isMounted, variations])

    useEffect(() => {
        if (!styles || currentStyle) return
        setStyle(styles[0])
        state.setState({ default: styles[0] })
    }, [styles, currentStyle, setStyle])

    useEffect(() => {
        if (!currentStyle || !styles || once.current) return
        once.current = true
        wrapperRef.current
            ?.querySelector(
                `#layout-style-${currentStyle.slug} [role="button"]`,
            )
            ?.focus()
    }, [currentStyle, styles])
    return (
        <PageLayoutFull>
            <div className="w-full">
                <div className="flex flex-col gap-2 mb-16">
                    <h1
                        data-test="layout-heading"
                        className={classNames(
                            'text-2xl text-center m-0 text-gray-900 transition-opacity duration-1000',
                            {
                                'opacity-0': loading,
                            },
                        )}>
                        {__(
                            'Now pick a design for your new site.',
                            'extendify',
                        )}
                    </h1>
                    <p className="text-center text-base m-0 p-0">
                        {loading
                            ? __(
                                  'Please wait a moment while we generate the homepage layout previews...',
                                  'extendify',
                              )
                            : __(
                                  'You can personalize this later.',
                                  'extendify',
                              )}
                    </p>
                </div>
                <div
                    className="gap-8 grid md:grid-cols-2 lg:grid-cols-3"
                    data-test="layout-preview-wrapper"
                    ref={wrapperRef}>
                    {styles?.map((style) => (
                        <div
                            id={`layout-style-${style.slug}`}
                            className="relative"
                            key={style.slug}>
                            <AnimatePresence>
                                <motion.div
                                    initial={{ opacity: 0 }}
                                    animate={{ opacity: 1 }}
                                    duration={0.7}
                                    className={classNames(
                                        'relative overflow-hidden border border-gray-200 rounded focus-within:ring-4 focus-within:ring-offset-2 focus-within:ring-offset-white focus-within:ring-design-main focus-within:outline-none',
                                        {
                                            'ring-4 ring-offset-2 ring-offset-white ring-design-main':
                                                currentStyle?.slug ===
                                                style.slug,
                                        },
                                    )}
                                    style={{ aspectRatio: '1.55' }}>
                                    <SmallPreview
                                        style={style}
                                        onSelect={onSelect}
                                    />
                                </motion.div>
                            </AnimatePresence>
                            <span aria-hidden="true">
                                {currentStyle?.slug === style.slug ? (
                                    <Checkmark className="absolute top-0 right-0 m-2 text-design-text bg-design-main w-6 h-6 z-50 rounded-full transform translate-x-5 -translate-y-5" />
                                ) : null}
                            </span>
                        </div>
                    ))}
                    {styleData?.slice(styles?.length).map((_, i) => (
                        <AnimatePresence key={i}>
                            <motion.div
                                initial={{ opacity: 1 }}
                                animate={{ opacity: 1 }}
                                exit={{ opacity: 0 }}
                                duration={0.7}
                                style={{ aspectRatio: '1.55' }}
                                className="relative bg-gray-100"
                            />
                        </AnimatePresence>
                    ))}
                </div>
            </div>
        </PageLayoutFull>
    )
}
