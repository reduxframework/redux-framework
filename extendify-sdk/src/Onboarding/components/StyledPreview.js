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
import { getThemeVariations, parseThemeJson } from '@onboarding/api/WPApi'
import { useFetch } from '@onboarding/hooks/useFetch'
import { useIsMounted } from '@onboarding/hooks/useIsMounted'
import { capitalize, lowerImageQuality } from '@onboarding/lib/util'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { SpinnerIcon } from '@onboarding/svg'

const fetcher = async (themeJson) => {
    if (!themeJson) return '{}'
    const res = await parseThemeJson(JSON.stringify(themeJson))
    return res?.styles ?? '{}'
}
export const StylePreview = ({
    style,
    onSelect,
    blockHeight,
    context,
    active = false,
    onHover = null,
}) => {
    const siteType = useUserSelectionStore((state) => state.siteType)
    const isMounted = useIsMounted()
    const [code, setCode] = useState('')
    const [loaded, setLoaded] = useState(false)
    const [inView, setInView] = useState(false)
    const [hoverCleanup, setHoverCleanup] = useState(null)
    const [variation, setVariation] = useState()
    const previewContainer = useRef(null)
    const content = useRef(null)
    const blockRef = useRef(null)
    const observer = useRef(null)
    const loadTime = useRef(false)
    const { data: themeJson } = useFetch(
        inView && variation ? variation : null,
        fetcher,
    )
    const { data: variations } = useFetch('variations', getThemeVariations)

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

    useLayoutEffect(() => {
        if (!inView || !context.measure) return
        const key = `${context.type}-${context.detail}`
        // If the componeent is in view, start the timer
        if (!loaded && !loadTime.current) {
            performance.mark(key)
            return
        }
        const time = performance.measure(key, {
            start: key,
            // The extendify key is used to filter only our measurements
            detail: { context, extendify: true },
        })

        loadTime.current = time.duration
        const q = new URLSearchParams(window.location.search)
        if (q?.has('performance')) {
            console.info(
                `🚀 ${capitalize(context.type)} (${
                    context.detail
                }) in ${time.duration.toFixed()}ms`,
            )
        }
    }, [loaded, context, inView])

    useEffect(() => {
        if (!variations?.length) return

        // Grab the styles from the theme.json variation
        const variation = variations.find(
            (theme) => theme.title === style.label,
        )
        setVariation(variation)
    }, [style, variations])

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
        let raf1, raf2
        if (!content.current || !loaded) return
        const p = previewContainer.current
        const scale = p.offsetWidth / 1400
        const iframe = content.current
        const body = iframe.contentDocument.body
        if (body?.style) {
            body.style.transitionProperty = 'all'
            body.style.top = 0
        }

        const handleIn = () => {
            if (!body.offsetHeight) return
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
            if (!body.offsetHeight) return
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
    }, [blockHeight, loaded])

    useEffect(() => {
        if (!blocks?.length || !inView) return
        let iframe

        // Inserts theme styles after iframe is loaded
        const handle = () => {
            if (!iframe.contentDocument?.getElementById('ext-tj')) {
                iframe.contentDocument?.head?.insertAdjacentHTML(
                    'beforeend',
                    `<style id="ext-tj">${transformedStyles}</style>`,
                )
            }
            content.current = iframe
            setTimeout(() => {
                if (isMounted.current) setLoaded(true)
            }, 100)
        }
        // The callback will attach a load event to the iframe
        const observer = new MutationObserver(() => {
            iframe = previewContainer.current.querySelector('iframe[title]')
            iframe.addEventListener('load', handle)
            setTimeout(() => {
                // In some cases, the load event doesn't fire.
                handle()
            }, 2000)
            observer.disconnect()
        })
        // observe the ref for when the iframe is injected by wp
        observer.observe(previewContainer.current, {
            attributes: false,
            childList: true,
            subtree: false,
        })
        return () => {
            observer.disconnect()
            iframe?.removeEventListener('load', handle)
        }
    }, [blocks, transformedStyles, isMounted, inView])

    useEffect(() => {
        // Only trigger the mutation observer if we are in view
        if (!observer.current) {
            observer.current = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    setInView(true)
                }
            })
        }
        observer.current.observe(blockRef.current)
        return () => {
            observer.current.disconnect()
        }
    }, [])

    return (
        <>
            {loaded && code ? null : (
                <div className="absolute inset-0 z-20 bg-gray-50 flex items-center justify-center">
                    <SpinnerIcon className="spin w-8" />
                </div>
            )}
            <div
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
                {window?.extOnbData?.devbuild ? (
                    <div className="-m-px absolute bg-gray-900 border border-t border-white bottom-0 group-hover:opacity-100 left-0 opacity-0 p-1 px-4 text-left text-sm text-white z-30 transition duration-300">
                        {style?.label} - {Number(loadTime.current).toFixed(2)}ms
                    </div>
                ) : null}
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
