import classNames from 'classnames'
import { useEffect, useState, useRef, useMemo } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { BlockPreview } from '@wordpress/block-editor'
import { rawHandler } from '@wordpress/blocks'
import { AuthorizationCheck, Middleware } from '../middleware'
import { injectTemplateBlocks } from '../util/templateInjection'
import { useUserStore } from '../state/User'
import { useGlobalStore } from '../state/GlobalState'
import { Templates as TemplatesApi } from '../api/Templates'
import { useIsDevMode } from '../hooks/helpers'
import { DevButtonOverlay } from './DevHelpers'
import { NoImportModal } from './modals/NoImportModal'
import { ProModal } from './modals/ProModal'

const canImportMiddleware = Middleware([
    'NeedsRegistrationModal',
    'hasRequiredPlugins',
    'hasPluginsActivated',
])

export function ImportTemplateBlock({ template }) {
    const importButtonRef = useRef(null)
    const once = useRef(false)
    const hasAvailableImports = useUserStore(
        (state) => state.hasAvailableImports,
    )
    const loggedIn = useUserStore((state) => state.apiKey.length)
    const setOpen = useGlobalStore((state) => state.setOpen)
    const pushModal = useGlobalStore((state) => state.pushModal)
    const removeAllModals = useGlobalStore((state) => state.removeAllModals)
    const blocks = useMemo(
        () => rawHandler({ HTML: template.fields.code }),
        [template.fields.code],
    )
    const [loaded, setLoaded] = useState(false)
    const devMode = useIsDevMode()

    const focusTrapInnerBlocks = () => {
        if (once.current) return
        once.current = true
        Array.from(
            importButtonRef.current.querySelectorAll(
                'a, button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])',
            ),
        ).forEach((el) => el.setAttribute('tabIndex', '-1'))
    }

    const importTemplates = async () => {
        await canImportMiddleware.check(template)
        AuthorizationCheck(canImportMiddleware)
            .then(() => {
                setTimeout(() => {
                    injectTemplateBlocks(blocks, template)
                        .then(() => removeAllModals())
                        .then(() => setOpen(false))
                        .then(() => canImportMiddleware.reset())
                }, 100)
            })
            .catch(() => {})
    }

    const handleKeyDown = (event) => {
        if (['Enter', 'Space', ' '].includes(event.key)) {
            event.stopPropagation()
            event.preventDefault()
            importTemplate()
        }
    }

    const importTemplate = () => {
        // Make a note that they attempted to import
        TemplatesApi.maybeImport(template)

        if (template?.fields?.pro && !loggedIn) {
            pushModal(<ProModal />)
            return
        }
        if (!hasAvailableImports()) {
            pushModal(<NoImportModal />)
            return
        }

        importTemplates()
    }

    // Trigger resize event on the live previews to add
    // Grammerly/Loom/etc compatability
    // TODO: This can probably be removed after WP 5.9
    useEffect(() => {
        const rafIds = []
        const timeouts = []
        let rafId1, rafId2, rafId3, rafId4
        rafId1 = window.requestAnimationFrame(() => {
            rafId2 = window.requestAnimationFrame(() => {
                importButtonRef.current
                    .querySelectorAll('iframe')
                    .forEach((frame) => {
                        const inner = frame.contentWindow.document.body
                        const rafId = window.requestAnimationFrame(() => {
                            const maybeRoot =
                                inner.querySelector('.is-root-container')
                            if (maybeRoot) {
                                const height = maybeRoot?.offsetHeight
                                if (height) {
                                    rafId4 = window.requestAnimationFrame(
                                        () => {
                                            frame.style.height = height + 'px'
                                        },
                                    )
                                    const id = window.setTimeout(() => {
                                        frame.style.height = height + 'px'
                                    }, 1000)
                                    timeouts.push(id)
                                }
                            }
                            frame.contentWindow.dispatchEvent(
                                new Event('resize'),
                            )
                        })
                        rafIds.push(rafId)
                    })
                rafId3 = window.requestAnimationFrame(() => {
                    window.dispatchEvent(new Event('resize'))
                    setLoaded(true)
                })
            })
        })
        return () => {
            ;[...rafIds, rafId1, rafId2, rafId3, rafId4].forEach((id) =>
                window.cancelAnimationFrame(id),
            )
            timeouts.forEach((id) => window.clearTimeout(id))
        }
    }, [])

    return (
        <div className="relative group">
            <div
                role="button"
                tabIndex="0"
                ref={importButtonRef}
                aria-label={sprintf(
                    __('Press to import %s', 'extendify'),
                    template?.fields?.type,
                )}
                className="mb-6 md:mb-8 cursor-pointer button-focus"
                onFocus={focusTrapInnerBlocks}
                onClick={importTemplate}
                onKeyDown={handleKeyDown}>
                <div
                    className={classNames('with-light-shadow relative', {
                        [`is-template--${template.fields.status}`]:
                            template?.fields?.status && devMode,
                    })}>
                    <BlockPreview
                        blocks={blocks}
                        live={false}
                        viewportWidth={1400}
                    />
                </div>
            </div>
            {/* Show dev info after the preview is loaded to trigger observer */}
            {devMode && loaded && <DevButtonOverlay template={template} />}
            {template?.fields?.pro && (
                <div className="bg-white bg-wp-theme-500 border font-medium border-none absolute z-20 top-4 right-4 py-1 px-2.5 rounded-md shadow-sm no-underline text-white pointer-events-none">
                    {__('Pro', 'extendify')}
                </div>
            )}
        </div>
    )
}
