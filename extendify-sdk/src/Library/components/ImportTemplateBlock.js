import { BlockPreview } from '@wordpress/block-editor'
import { rawHandler } from '@wordpress/blocks'
import { useRef, useMemo, useEffect, useState } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import classNames from 'classnames'
import { Templates as TemplatesApi } from '@library/api/Templates'
import { useIsDevMode } from '@library/hooks/helpers'
import { AuthorizationCheck, Middleware } from '@library/middleware'
import { useGlobalStore } from '@library/state/GlobalState'
import { useUserStore } from '@library/state/User'
import { injectTemplateBlocks } from '@library/util/templateInjection'
import { DevButtonOverlay } from './DevHelpers'
import { NoImportModal } from './modals/NoImportModal'
import { ProModal } from './modals/ProModal'

const canImportMiddleware = Middleware([
    'hasRequiredPlugins',
    'hasPluginsActivated',
])

export function ImportTemplateBlock({ template, maxHeight }) {
    const importButtonRef = useRef(null)
    const hasAvailableImports = useUserStore(
        (state) => state.hasAvailableImports,
    )
    const loggedIn = useUserStore((state) => state.apiKey.length)
    const setOpen = useGlobalStore((state) => state.setOpen)
    const pushModal = useGlobalStore((state) => state.pushModal)
    const removeAllModals = useGlobalStore((state) => state.removeAllModals)
    const [topValue, setTopValue] = useState(0)
    const type = Array.isArray(template?.fields?.type)
        ? template.fields.type[0]
        : template?.fields?.type
    const blocks = useMemo(
        () => rawHandler({ HTML: halfImageSizes(template.fields.code) }),
        [template.fields.code],
    )
    // The above will cut the image sizes in half, and the below will be inserted into the page
    const blocksRaw = useMemo(
        () => rawHandler({ HTML: template.fields.code }),
        [template.fields.code],
    )
    const devMode = useIsDevMode()

    const importTemplates = async () => {
        await canImportMiddleware.check(template)
        AuthorizationCheck(canImportMiddleware)
            .then(() => {
                setTimeout(() => {
                    injectTemplateBlocks(blocksRaw, template)
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

    // Handle layout animation
    useEffect(() => {
        if (!Number.isInteger(maxHeight)) return
        if (type !== 'layout') return
        const button = importButtonRef.current
        const handleIn = () => {
            // The live component changes over time so easier to query on demand
            const height = button.offsetHeight
            button.style.transitionDuration = height * 1.5 + 'ms'
            setTopValue(Math.abs(height - maxHeight) * -1)
        }
        const handleOut = () => {
            const height = button.offsetHeight
            button.style.transitionDuration = height / 1.5 + 'ms'
            setTopValue(0)
        }
        button.addEventListener('focus', handleIn)
        button.addEventListener('mouseenter', handleIn)
        button.addEventListener('blur', handleOut)
        button.addEventListener('mouseleave', handleOut)
        return () => {
            button.removeEventListener('focus', handleIn)
            button.removeEventListener('mouseenter', handleIn)
            button.removeEventListener('blur', handleOut)
            button.removeEventListener('mouseleave', handleOut)
        }
    }, [maxHeight, type])

    return (
        <div className="group relative">
            <div
                role="button"
                tabIndex="0"
                aria-label={sprintf(
                    // translators: %s is the type of template (e.g. layout, pattern)
                    __('Press to import %s', 'extendify'),
                    template?.fields?.type,
                )}
                style={{ maxHeight }}
                className="button-focus relative m-0 cursor-pointer overflow-hidden bg-gray-100 ease-in-out"
                onClick={importTemplate}
                onKeyDown={handleKeyDown}>
                <div
                    ref={importButtonRef}
                    style={{ top: topValue, transitionProperty: 'all' }}
                    className={classNames('with-light-shadow relative', {
                        [`is-template--${template.fields.status}`]:
                            template?.fields?.status && devMode,
                        'p-6 md:p-8': Number.isInteger(maxHeight),
                    })}>
                    <BlockPreview
                        blocks={blocks}
                        live={false}
                        viewportWidth={1400}
                    />
                </div>
            </div>
            {/* Show dev info after the preview is loaded to trigger observer */}
            {devMode && <DevButtonOverlay template={template} />}
            {template?.fields?.pro && (
                <div className="pointer-events-none absolute top-4 right-4 z-20 rounded-md border border-none bg-white bg-wp-theme-500 py-1 px-2.5 font-medium text-white no-underline shadow-sm">
                    {__('Pro', 'extendify')}
                </div>
            )}
        </div>
    )
}

const halfImageSizes = (html) => {
    return html.replace(
        /\w+:\/\/\S*(w=(\d*))&(h=(\d*))&\w+\S*"/g,
        (url, w, width, h, height) =>
            url
                .replace(w, 'w=' + Math.floor(Number(width) / 2))
                .replace(h, 'h=' + Math.floor(Number(height) / 2)),
    )
}
