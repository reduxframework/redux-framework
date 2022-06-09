import { BlockPreview, transformStyles } from '@wordpress/block-editor'
import { rawHandler } from '@wordpress/blocks'
import { useState, useRef, useEffect, useMemo } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { parseThemeJson } from '@onboarding/api/WPApi'
import { useIsMounted } from '@onboarding/hooks/useIsMounted'
import { lowerImageQuality } from '@onboarding/lib/util'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { SpinnerIcon } from '@onboarding/svg'

export const StylePreview = ({
    style,
    selectStyle,
    blockHeight,
    onHover = null,
}) => {
    const siteType = useUserSelectionStore((state) => state.siteType)
    const isMounted = useIsMounted()
    const [code, setCode] = useState('')
    const [themeJson, setThemeJson] = useState('')
    const [loaded, setLoaded] = useState(false)
    const [inView, setInView] = useState(false)
    const [hoverCleanup, setHoverCleanup] = useState(null)
    const previewContainer = useRef(null)
    const content = useRef(null)
    const blockRef = useRef(null)
    const observer = useRef(null)
    const blocks = useMemo(
        () => rawHandler({ HTML: lowerImageQuality(code) }),
        [code],
    )
    const transformedStyles = useMemo(
        () => transformStyles([{ css: themeJson }], '.editor-styles-wrapper'),
        [themeJson],
    )

    useEffect(() => {
        // If no theme provided, just load normally.
        if (!style?.themeJson) {
            setThemeJson('{}')
            return
        }
        parseThemeJson(style.themeJson).then((res) => {
            setThemeJson(res?.styles ?? '{}')
        })
    }, [style?.themeJson])

    useEffect(() => {
        if (!themeJson || !style?.code) return
        const code = [style?.headerCode, style?.code, style?.footerCode]
            .filter(Boolean)
            .join('')
            .replace(
                /<!-- wp:navigation {(.|\n)*?(\/wp:navigation -->|} \/-->)/g,
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
            }, 500)
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
                <div className="m-6 absolute inset-0 bg-gray-50 flex items-center justify-center">
                    <SpinnerIcon className="spin w-8" />
                </div>
            )}
            <div
                ref={blockRef}
                role={selectStyle ? 'button' : undefined}
                tabIndex={selectStyle ? 0 : undefined}
                aria-label={
                    selectStyle ? __('Press to select', 'extendify') : undefined
                }
                className={classNames(
                    'w-full overflow-hidden bg-transparent z-10',
                    {
                        relative: loaded,
                        'absolute opacity-0': !loaded,
                        'button-focus button-card': selectStyle,
                    },
                )}
                onKeyDown={(e) => {
                    if (['Enter', 'Space', ' '].includes(e.key)) {
                        selectStyle && selectStyle(style)
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
                onClick={selectStyle ? () => selectStyle(style) : () => {}}>
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
