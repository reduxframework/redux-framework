import { BlockPreview, transformStyles } from '@wordpress/block-editor'
import { rawHandler } from '@wordpress/blocks'
import { useState, useRef, useEffect, useMemo } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { colord } from 'colord'
import { AnimatePresence, motion } from 'framer-motion'
import { parseThemeJson } from '@onboarding/api/WPApi'
import { useFetch } from '@onboarding/hooks/useFetch'
import { useIsMounted } from '@onboarding/hooks/useIsMounted'
import { lowerImageQuality } from '@onboarding/lib/util'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

const fetcher = async (themeJson) => {
    if (!themeJson) return '{}'
    const res = await parseThemeJson(JSON.stringify(themeJson))
    if (!res?.styles) {
        throw new Error('Invalid theme json')
    }
    return { data: res.styles }
}

const styleOverrides = `
.wp-block-cover {
    min-height: 100% !important;
    height: calc(100vh - 0px);
    max-height: 905px;
}
`

export const SmallPreview = ({ style, onSelect }) => {
    const { siteType } = useUserSelectionStore()
    const isMounted = useIsMounted()
    const [code, setCode] = useState('')
    const [loaded, setLoaded] = useState(false)
    const [waitForIframe, setWaitForIframe] = useState(0)
    const [iFrame, setIFrame] = useState(null)
    const [inView, setInView] = useState(false)
    const previewContainer = useRef(null)
    const blockRef = useRef(null)
    const observer = useRef(null)
    const variation = style?.variation
    const { data: themeJson } = useFetch(
        inView && variation ? variation : null,
        fetcher,
    )
    const theme = variation?.settings?.color?.palette?.theme

    const blocks = useMemo(
        () => rawHandler({ HTML: lowerImageQuality(code) }),
        [code],
    )
    const transformedStyles = useMemo(
        () =>
            themeJson
                ? transformStyles(
                      [{ css: themeJson }],
                      '.editor-styles-wrapper',
                  )
                : null,
        [themeJson],
    )

    useEffect(() => {
        if (iFrame || !inView) return
        // continuously check for iframe
        const interval = setTimeout(() => {
            const container = previewContainer.current
            const frame = container?.querySelector('iframe[title]')
            if (!frame) return setWaitForIframe((prev) => prev + 1)
            setIFrame(frame)
        }, 100)
        return () => clearTimeout(interval)
    }, [iFrame, inView, waitForIframe])

    useEffect(() => {
        if (!themeJson || !style?.code) return
        const code = [style?.headerCode, style?.code, style?.footerCode]
            .filter(Boolean)
            .join('')
            .replace(
                // <!-- wp:navigation --> <!-- /wp:navigation -->
                /<!-- wp:navigation[.\S\s]*?\/wp:navigation -->/g,
                '<!-- wp:paragraph {"className":"tmp-nav"} --><p class="tmp-nav">Home | About | Contact</p ><!-- /wp:paragraph -->',
            )
            .replace(
                // <!-- wp:navigation /-->
                /<!-- wp:navigation.*\/-->/g,
                '<!-- wp:paragraph {"className":"tmp-nav"} --><p class="tmp-nav">Home | About | Contact</p ><!-- /wp:paragraph -->',
            )
        setCode(code)
    }, [siteType?.slug, themeJson, style])

    useEffect(() => {
        if (!blocks?.length || !iFrame) return
        let timer, timer2

        // Inserts theme styles after iframe is loaded
        const load = () => {
            const doc = iFrame.contentDocument

            // Remove load-styles in case WP laods them
            doc.querySelector('[href*=load-styles]')?.remove()

            const style = `<style id="ext-tj">${transformedStyles}${styleOverrides}</style>`
            if (!doc?.getElementById('ext-tj')) {
                doc?.head?.insertAdjacentHTML('beforeend', style)
            }
            timer2 = setTimeout(() => isMounted.current && setLoaded(true), 100)
            clearTimeout(timer)
        }
        iFrame.addEventListener('load', load)
        // In some cases, the load event doesn't fire.
        timer = setTimeout(load, 2000)
        return () => {
            iFrame?.removeEventListener('load', load)
            ;[(timer, timer2)].forEach((t) => clearTimeout(t))
        }
    }, [blocks, transformedStyles, isMounted, inView, iFrame])

    useEffect(() => {
        if (observer.current) return
        observer.current = new IntersectionObserver((entries) => {
            entries[0].isIntersecting && setInView(true)
        })
        observer.current.observe(blockRef.current)
        return () => observer.current.disconnect()
    }, [])

    return (
        <>
            <div
                data-test="layout-preview"
                className="w-full h-full relative overflow-hidden"
                ref={blockRef}
                role={onSelect ? 'button' : undefined}
                tabIndex={onSelect ? 0 : undefined}
                aria-label={
                    onSelect ? __('Press to select', 'extendify') : undefined
                }
                onKeyDown={(e) => {
                    if (['Enter', 'Space', ' '].includes(e.key)) {
                        onSelect && onSelect({ ...style, variation })
                    }
                }}
                onClick={
                    onSelect
                        ? () => onSelect({ ...style, variation })
                        : () => {}
                }>
                {inView ? (
                    <motion.div
                        ref={previewContainer}
                        className="absolute inset-0 z-20"
                        initial={{ opacity: 0 }}
                        animate={{ opacity: loaded ? 1 : 0 }}>
                        <BlockPreview
                            blocks={blocks}
                            viewportWidth={1400}
                            additionalStyles={[
                                {
                                    css: 'body { background-color: silver !important; }',
                                },
                            ]}
                        />
                    </motion.div>
                ) : null}
                <AnimatePresence>
                    {loaded || (
                        <motion.div
                            initial={{ opacity: 0.7 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            transition={{ duration: 0.5 }}
                            className="absolute inset-0 z-30"
                            style={{
                                backgroundColor: colord(
                                    theme?.find(
                                        ({ slug }) => slug === 'primary',
                                    )?.color ?? '#ccc',
                                )
                                    .alpha(0.25)
                                    .toRgbString(),
                                backgroundImage:
                                    'linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.5) 50%, rgba(255,255,255,0) 100%)',
                                backgroundSize: '600% 600%',
                                animation:
                                    'extendify-loading-skeleton 10s ease-in-out infinite',
                            }}
                        />
                    )}
                </AnimatePresence>
            </div>
        </>
    )
}
