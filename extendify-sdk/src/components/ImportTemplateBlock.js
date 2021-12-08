import { useEffect, useState, useRef, memo } from '@wordpress/element'
import { AuthorizationCheck, Middleware } from '../middleware'
import { injectTemplateBlocks } from '../util/templateInjection'
import { useUserStore } from '../state/User'
import { useGlobalStore } from '../state/GlobalState'
import { __, sprintf } from '@wordpress/i18n'
import { Templates as TemplatesApi } from '../api/Templates'
import { useInView } from 'react-intersection-observer'
import { BlockPreview } from '@wordpress/block-editor'
import { rawHandler } from '@wordpress/blocks'
import { render } from '@wordpress/element'
import ExtendifyLibrary from '../ExtendifyLibrary'
import Primary from './buttons/Primary'
import SplitModal from './modals/SplitModal'
import { brandLogo } from '../components/icons/'

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

export function ImportTemplateBlock({ template }) {
    const importButtonRef = useRef(null)
    const once = useRef(false)
    const canImport = useUserStore((state) => state.canImport)
    const setOpen = useGlobalStore((state) => state.setOpen)
    const blocks = rawHandler({ HTML: template.fields.code })
    const [hasBeenSeen, setHasBeenSeen] = useState(false)
    const [onlyLoadInView, inView] = useInView()
    const [showModal, setShowModal] = useState(false)

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
        AuthorizationCheck(canImportMiddleware.stack).then(() => {
            setTimeout(() => {
                injectTemplateBlocks(blocks, template)
                    .then(() => setOpen(false))
                    .then(() =>
                        render(
                            <ExtendifyLibrary />,
                            document.getElementById('extendify-root'),
                        ),
                    )
                    .then(() => canImportMiddleware.reset())
            }, 100)
        })
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
            setShowModal(true)
            return
        }
        TemplatesApi.maybeImport(template)
        importTemplates()
    }

    useEffect(() => {
        if (!hasBeenSeen && inView) {
            setHasBeenSeen(true)
        }
    }, [inView, hasBeenSeen, template])

    return (
        <>
            <div
                role="button"
                tabIndex="0"
                ref={importButtonRef}
                aria-label={sprintf(
                    __('Press to import %s', 'extendify-sdk'),
                    template?.fields?.type,
                )}
                className="mb-8 cursor-pointer button-focus"
                onFocus={focusTrapInnerBlocks}
                onClick={importTemplate}
                onKeyDown={handleKeyDown}>
                <div
                    ref={onlyLoadInView}
                    className="invisible absolute inset-0 pointer-events-none"></div>
                {hasBeenSeen && <LiveBlocksMemoized blocks={blocks} />}
            </div>
            {showModal && (
                <SplitModal
                    isOpen={showModal}
                    onRequestClose={() => setShowModal(false)}
                    left={
                        <>
                            <div className="flex space-x-2 items-center justify-center mb-10 text-extendify-black">
                                {brandLogo}
                            </div>

                            <h3 className="text-xl md:leading-3">
                                {__("You're out of imports", 'extendify-sdk')}
                            </h3>
                            <p className="text-sm text-black">
                                {__(
                                    'Sign up today and get unlimited access to our entire collection of patterns and page layouts.',
                                    'extendify-sdk',
                                )}
                            </p>
                            <div>
                                <Primary
                                    tagName="a"
                                    target="_blank"
                                    className="m-auto mt-10"
                                    href={`https://extendify.com/pricing/?utm_source=${window.extendifySdkData.sdk_partner}&utm_medium=library&utm_campaign=no-imports-modal&utm_content=get-unlimited-imports`}
                                    rel="noreferrer">
                                    {__(
                                        'Get Unlimited Imports',
                                        'extendify-sdk',
                                    )}
                                    <svg
                                        fill="none"
                                        height="24"
                                        viewBox="0 0 25 24"
                                        width="25"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="m10.3949 8.7864 5.5476-.02507m0 0-.0476 5.52507m.0476-5.52507c-2.357 2.35707-5.4183 5.41827-7.68101 7.68097"
                                            stroke="currentColor"
                                            strokeWidth="1.5"
                                        />
                                    </svg>
                                </Primary>
                            </div>
                        </>
                    }
                    right={
                        <div className="space-y-2">
                            <div className="flex items-center space-x-2">
                                <svg
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        fillRule="evenodd"
                                        clipRule="evenodd"
                                        d="M7.49271 18.0092C6.97815 17.1176 7.28413 15.9755 8.17569 15.4609C9.06724 14.946 10.2094 15.252 10.7243 16.1435C11.2389 17.0355 10.9329 18.1772 10.0413 18.6922C9.14978 19.2071 8.00764 18.9011 7.49271 18.0092V18.0092Z"
                                        fill="currentColor"
                                    />
                                    <path
                                        fillRule="evenodd"
                                        clipRule="evenodd"
                                        d="M16.5073 6.12747C17.0218 7.01903 16.7158 8.16117 15.8243 8.67573C14.9327 9.19066 13.7906 8.88467 13.2757 7.99312C12.7611 7.10119 13.0671 5.95942 13.9586 5.44449C14.8502 4.92956 15.9923 5.23555 16.5073 6.12747V6.12747Z"
                                        fill="currentColor"
                                    />
                                    <path
                                        fillRule="evenodd"
                                        clipRule="evenodd"
                                        d="M4.60135 11.1355C5.11628 10.2439 6.25805 9.93793 7.14998 10.4525C8.04153 10.9674 8.34752 12.1096 7.83296 13.0011C7.31803 13.8927 6.17588 14.1987 5.28433 13.6841C4.39278 13.1692 4.08679 12.0274 4.60135 11.1355V11.1355Z"
                                        fill="currentColor"
                                    />
                                    <path
                                        fillRule="evenodd"
                                        clipRule="evenodd"
                                        d="M19.3986 13.0011C18.8837 13.8927 17.7419 14.1987 16.85 13.6841C15.9584 13.1692 15.6525 12.027 16.167 11.1355C16.682 10.2439 17.8241 9.93793 18.7157 10.4525C19.6072 10.9674 19.9132 12.1092 19.3986 13.0011V13.0011Z"
                                        fill="currentColor"
                                    />
                                    <path
                                        d="M9.10857 8.92594C10.1389 8.92594 10.9742 8.09066 10.9742 7.06029C10.9742 6.02992 10.1389 5.19464 9.10857 5.19464C8.0782 5.19464 7.24292 6.02992 7.24292 7.06029C7.24292 8.09066 8.0782 8.92594 9.10857 8.92594Z"
                                        fill="currentColor"
                                    />
                                    <path
                                        d="M14.8913 18.942C15.9217 18.942 16.7569 18.1067 16.7569 17.0763C16.7569 16.046 15.9217 15.2107 14.8913 15.2107C13.8609 15.2107 13.0256 16.046 13.0256 17.0763C13.0256 18.1067 13.8609 18.942 14.8913 18.942Z"
                                        fill="currentColor"
                                    />
                                    <path
                                        fillRule="evenodd"
                                        clipRule="evenodd"
                                        d="M10.3841 13.0011C9.86951 12.1096 10.1755 10.9674 11.067 10.4525C11.9586 9.93793 13.1007 10.2439 13.6157 11.1355C14.1302 12.0274 13.8242 13.1692 12.9327 13.6841C12.0411 14.1987 10.899 13.8927 10.3841 13.0011V13.0011Z"
                                        fill="currentColor"
                                    />
                                </svg>
                                <span className="text-sm leading-none">
                                    {__(
                                        "Access to 100's of Patterns",
                                        'extendify-sdk',
                                    )}
                                </span>
                            </div>
                            <div className="flex items-center space-x-2">
                                <svg
                                    fill="none"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    width="24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g stroke="currentColor" strokeWidth="1.5">
                                        <path d="m6 4.75h12c.6904 0 1.25.55964 1.25 1.25v12c0 .6904-.5596 1.25-1.25 1.25h-12c-.69036 0-1.25-.5596-1.25-1.25v-12c0-.69036.55964-1.25 1.25-1.25z" />
                                        <path d="m9.25 19v-14" />
                                    </g>
                                </svg>
                                <span className="text-sm leading-none">
                                    {__(
                                        'Beautiful full page layouts',
                                        'extendify-sdk',
                                    )}
                                </span>
                            </div>
                            <div className="flex items-center space-x-2">
                                <svg
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="7.25"
                                        stroke="currentColor"
                                        strokeWidth="1.5"
                                    />
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="4.25"
                                        stroke="currentColor"
                                        strokeWidth="1.5"
                                    />
                                    <circle
                                        cx="11.9999"
                                        cy="12.2"
                                        r="6"
                                        transform="rotate(-45 11.9999 12.2)"
                                        stroke="currentColor"
                                        strokeWidth="3"
                                        strokeDasharray="1.5 4"
                                    />
                                </svg>

                                <span className="text-sm leading-none">
                                    {__(
                                        'Fast and friendly support',
                                        'extendify-sdk',
                                    )}
                                </span>
                            </div>
                            <div className="flex items-center space-x-2">
                                <svg
                                    fill="none"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    width="24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="m11.7758 3.45425c.0917-.18582.3567-.18581.4484 0l2.3627 4.78731c.0364.07379.1068.12493.1882.13676l5.2831.76769c.2051.02979.287.28178.1386.42642l-3.8229 3.72637c-.0589.0575-.0858.1402-.0719.2213l.9024 5.2618c.0351.2042-.1793.36-.3627.2635l-4.7254-2.4842c-.0728-.0383-.1598-.0383-.2326 0l-4.7254 2.4842c-.18341.0965-.39776-.0593-.36274-.2635l.90247-5.2618c.01391-.0811-.01298-.1638-.0719-.2213l-3.8229-3.72637c-.14838-.14464-.0665-.39663.13855-.42642l5.28312-.76769c.08143-.01183.15182-.06297.18823-.13676z"
                                        fill="currentColor"
                                    />
                                </svg>
                                <span className="text-sm leading-none">
                                    {__('14-Day guarantee', 'extendify-sdk')}
                                </span>
                            </div>
                        </div>
                    }
                />
            )}
        </>
    )
}
