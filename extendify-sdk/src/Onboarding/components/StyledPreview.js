import { BlockPreview, transformStyles } from '@wordpress/block-editor'
import { rawHandler } from '@wordpress/blocks'
import {
    useState,
    useRef,
    useEffect,
    useMemo,
    useLayoutEffect,
} from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { parseThemeJson } from '@onboarding/api/WPApi'
import { SkeletonLoader } from '@onboarding/components/SkeletonLoader'
import { useFetch } from '@onboarding/hooks/useFetch'
import { useIsMounted } from '@onboarding/hooks/useIsMounted'
import { capitalize, lowerImageQuality } from '@onboarding/lib/util'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'

const fetcher = async (themeJson) => {
    if (!themeJson) return '{}'
    const res = await parseThemeJson(JSON.stringify(themeJson))
    if (!res?.styles) {
        throw new Error('Invalid theme json')
    }
    return { data: res.styles }
}
export const StylePreview = ({
    style,
    onSelect,
    blockHeight,
    context,
    active = false,
    onHover = null,
}) => {
    const { siteType, variation } = useUserSelectionStore()
    const isMounted = useIsMounted()
    const [code, setCode] = useState('')
    const [loaded, setLoaded] = useState(false)
    const [waitForIframe, setWaitForIframe] = useState(0)
    const [iFrame, setIFrame] = useState(null)
    const [inView, setInView] = useState(false)
    const [hoverCleanup, setHoverCleanup] = useState(null)
    const previewContainer = useRef(null)
    const blockRef = useRef(null)
    const observer = useRef(null)
    const startTime = useRef(null)
    const loadTime = useRef(false)
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

    useLayoutEffect(() => {
        if (!inView || !context.measure) return
        const key = `${context.type}-${context.detail}`
        // If the component is in view, start the timer
        if (!loaded && !loadTime.current) {
            loadTime.current = 0
            startTime.current = performance.now()
            return
        }
        let time
        try {
            time = performance.measure(key, {
                start: startTime.current,
                // The extendify key is used to filter only our measurements
                detail: { context, extendify: true },
            })
        } catch (e) {
            console.error(e)
        }

        loadTime.current = time?.duration ?? 0
        const q = new URLSearchParams(window.location.search)
        if (q?.has('performance') && loadTime.current) {
            console.info(
                `🚀 ${capitalize(context.type)} (${
                    context.detail
                }) in ${loadTime.current.toFixed()}ms`,
            )
        }
    }, [loaded, context, inView])

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
        if (!iFrame || !loaded) return
        let raf1, raf2
        const p = previewContainer.current
        const scale = p.offsetWidth / 1400
        const body = iFrame.contentDocument.body
        if (body?.style) {
            body.style.transitionProperty = 'all'
            body.style.top = 0
        }
        // Remove load-styles in case WP laods them
        body.querySelector('[href*=load-styles]')?.remove()

        const handleIn = () => {
            if (!body?.offsetHeight) return
            const dynBlockHeight =
                (blockRef?.current?.offsetHeight ?? blockHeight) - 32
            const bodyHeight =
                body.getBoundingClientRect().height - dynBlockHeight / scale
            body.style.transitionDuration =
                Math.max(bodyHeight * 2, 3000) + 'ms'
            raf1 = window.requestAnimationFrame(() => {
                body.style.top = Math.max(0, bodyHeight) * -1 + 'px'
            })
        }
        const handleOut = () => {
            if (!body?.offsetHeight) return
            const dynBlockHeight =
                (blockRef?.current?.offsetHeight ?? blockHeight) - 32
            const bodyHeight = body.offsetHeight - dynBlockHeight / scale
            body.style.transitionDuration = bodyHeight + 'ms'
            raf2 = window.requestAnimationFrame(() => {
                body.style.top = 0
            })
        }

        p.addEventListener('focus', handleIn)
        p.addEventListener('mouseenter', handleIn)
        p.addEventListener('blur', handleOut)
        p.addEventListener('mouseleave', handleOut)
        return () => {
            window.cancelAnimationFrame(raf1)
            window.cancelAnimationFrame(raf2)
            p.removeEventListener('focus', handleIn)
            p.removeEventListener('mouseenter', handleIn)
            p.removeEventListener('blur', handleOut)
            p.removeEventListener('mouseleave', handleOut)
        }
    }, [blockHeight, loaded, iFrame])

    useEffect(() => {
        if (!blocks?.length || !iFrame) return
        let timer, timer2

        // Inserts theme styles after iframe is loaded
        const load = () => {
            const doc = iFrame.contentDocument
            const style = `<style id="ext-tj">${transformedStyles}</style>`
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
            {loaded && code ? null : (
                <>
                    <div className="absolute inset-0 z-20 flex items-center justify-center">
                        <SkeletonLoader
                            context="style"
                            theme={{
                                color: theme?.find(
                                    (c) => c.slug === 'foreground',
                                )?.color,
                                bgColor: theme?.find(
                                    (c) => c.slug === 'background',
                                )?.color,
                            }}
                        />
                    </div>
                </>
            )}
            <div
                data-test="layout-preview"
                ref={blockRef}
                role={onSelect ? 'button' : undefined}
                tabIndex={onSelect ? 0 : undefined}
                aria-label={
                    onSelect ? __('Press to select', 'extendify') : undefined
                }
                className={classNames(
                    'group w-full overflow-hidden bg-transparent z-10',
                    {
                        'relative min-h-full': loaded,
                        'absolute opacity-0': !loaded,
                        'button-focus button-card p-2': onSelect,
                        'ring-partner-primary-bg ring-offset-2 ring-offset-white ring-wp':
                            active,
                    },
                )}
                onKeyDown={(e) => {
                    if (['Enter', 'Space', ' '].includes(e.key)) {
                        onSelect && onSelect({ ...style, variation })
                    }
                }}
                onMouseEnter={() => {
                    if (!onHover) return
                    setHoverCleanup(onHover)
                }}
                onMouseLeave={() => {
                    if (hoverCleanup) {
                        hoverCleanup()
                        setHoverCleanup(null)
                    }
                }}
                onClick={
                    onSelect
                        ? () => onSelect({ ...style, variation })
                        : () => {}
                }>
                <div ref={previewContainer} className="relative rounded-lg">
                    {inView ? (
                        <BlockPreview
                            blocks={blocks}
                            viewportWidth={1400}
                            live={false}
                        />
                    ) : null}
                </div>
            </div>
        </>
    )
}
