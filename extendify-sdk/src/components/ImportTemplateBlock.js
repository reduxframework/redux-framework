import { useEffect, useState, useRef, memo } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { BlockPreview } from '@wordpress/block-editor'
import { rawHandler } from '@wordpress/blocks'
import { Icon } from '@wordpress/icons'
import { Button } from '@wordpress/components'
import { useInView } from 'react-intersection-observer'
import {
    growthArrow,
    patterns,
    layouts,
    support,
    star,
    brandLogo,
} from '../components/icons/'
import { AuthorizationCheck, Middleware } from '../middleware'
import { injectTemplateBlocks } from '../util/templateInjection'
import { useUserStore } from '../state/User'
import { useGlobalStore } from '../state/GlobalState'
import { Templates as TemplatesApi } from '../api/Templates'
import Primary from './buttons/Primary'
import SplitModal from './modals/SplitModal'
import SettingsModal from './modals/SettingsModal'
import { useIsDevMode } from '../hooks/helpers'
import { DevButtonOverlay } from './DevHelpers'

const LiveBlocksMemoized = memo(
    ({ blocks }) => {
        return (
            <div className="with-light-shadow relative">
                <BlockPreview
                    blocks={blocks}
                    live={false}
                    viewportWidth={1400}
                />
            </div>
        )
    },
    (oldBlocks, newBlocks) => oldBlocks.clientId == newBlocks.clientId,
)
const canImportMiddleware = Middleware([
    'NeedsRegistrationModal',
    'hasRequiredPlugins',
    'hasPluginsActivated',
])

// TODO: extract this modal
const NoImportModal = (
    <SplitModal
        isOpen={true}
        onRequestClose={() => useGlobalStore.setState({ currentModal: null })}
        left={
            <>
                <div className="flex space-x-2 items-center justify-center mb-10 text-extendify-black">
                    {brandLogo}
                </div>

                <h3 className="text-xl md:leading-3">
                    {__("You're out of imports", 'extendify')}
                </h3>
                <p className="text-sm text-black">
                    {__(
                        'Sign up today and get unlimited access to our entire collection of patterns and page layouts.',
                        'extendify',
                    )}
                </p>
                <div>
                    <Primary
                        tagName="a"
                        target="_blank"
                        className="m-auto mt-10 py-3"
                        href={`https://extendify.com/pricing/?utm_source=${window.extendifyData.sdk_partner}&utm_medium=library&utm_campaign=no-imports-modal&utm_content=get-unlimited-imports`}
                        rel="noreferrer">
                        {__('Get Unlimited Imports', 'extendify')}
                        <Icon
                            icon={growthArrow}
                            size={24}
                            className="-ml-1 mr-1"
                        />
                    </Primary>
                    <p className="text-sm text-extendify-gray mb-0">
                        {__('Have an account?', 'extendify')}
                        <Button
                            onClick={() => {
                                useGlobalStore.setState({
                                    currentModal: (
                                        <SettingsModal
                                            isOpen={true}
                                            onClose={() =>
                                                useGlobalStore.setState({
                                                    currentModal: null,
                                                })
                                            }
                                        />
                                    ),
                                })
                            }}
                            className="underline hover:no-underline text-sm text-extendify-gray pl-2">
                            {__('Sign in', 'extendify')}
                        </Button>
                    </p>
                </div>
            </>
        }
        right={
            <div className="space-y-2">
                <div className="flex items-center space-x-2">
                    <Icon icon={patterns} size={24} className="-ml-1 mr-1" />
                    <span className="text-sm leading-none">
                        {__("Access to 100's of Patterns", 'extendify')}
                    </span>
                </div>
                <div className="flex items-center space-x-2">
                    <Icon icon={layouts} size={24} className="-ml-1 mr-1" />
                    <span className="text-sm leading-none">
                        {__('Beautiful full page layouts', 'extendify')}
                    </span>
                </div>
                <div className="flex items-center space-x-2">
                    <Icon icon={support} size={24} className="-ml-1 mr-1" />
                    <span className="text-sm leading-none">
                        {__('Fast and friendly support', 'extendify')}
                    </span>
                </div>
                <div className="flex items-center space-x-2">
                    <Icon icon={star} size={24} className="-ml-1 mr-1" />
                    <span className="text-sm leading-none">
                        {__('14-Day guarantee', 'extendify')}
                    </span>
                </div>
            </div>
        }
    />
)

export function ImportTemplateBlock({ template }) {
    const importButtonRef = useRef(null)
    const once = useRef(false)
    const canImport = useUserStore((state) => state.canImport)
    const setOpen = useGlobalStore((state) => state.setOpen)
    const setCurrentModal = useGlobalStore((state) => state.setCurrentModal)
    const blocks = rawHandler({ HTML: template.fields.code })
    const [hasBeenSeen, setHasBeenSeen] = useState(false)
    const [loaded, setLoaded] = useState(false)
    const [onlyLoadInView, inView] = useInView()
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
                        .then(() => setCurrentModal(null))
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
        if (!canImport()) {
            setCurrentModal(NoImportModal)
            return
        }
        TemplatesApi.maybeImport(template)
        importTemplates()
    }

    // Trigger resize event on the live previews to add
    // Grammerly/Loom/etc compatability
    // TODO: This can probably be removed after WP 5.9
    useEffect(() => {
        const rafIds = []
        let rafId1, rafId2, rafId3
        rafId1 = window.requestAnimationFrame(() => {
            rafId2 = window.requestAnimationFrame(() => {
                importButtonRef.current
                    .querySelectorAll('iframe')
                    .forEach((frame) => {
                        const rafId = window.requestAnimationFrame(() => {
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
        return () =>
            [...rafIds, rafId1, rafId2, rafId3].forEach((id) =>
                window.cancelAnimationFrame(id),
            )
    }, [])

    useEffect(() => {
        if (!hasBeenSeen && inView) {
            setHasBeenSeen(true)
        }
    }, [inView, hasBeenSeen, template])

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
                    ref={onlyLoadInView}
                    className="invisible absolute inset-0 pointer-events-none"></div>
                {hasBeenSeen && <LiveBlocksMemoized blocks={blocks} />}
            </div>
            {/* Show dev info after the preview is loaded to trigger observer */}
            {devMode && loaded && <DevButtonOverlay template={template} />}
        </div>
    )
}
