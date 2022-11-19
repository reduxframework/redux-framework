import { Icon } from '@wordpress/components'
import { safeHTML } from '@wordpress/dom'
import { useState, useRef } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { General } from '@library/api/General'
import { Plugins } from '@library/api/Plugins'
import { download2, brandLogo } from '@library/components/icons'
import { useGlobalStore } from '@library/state/GlobalState'
import { useUserStore } from '@library/state/User'
import { SplitModal } from './SplitModal'

export const InstallStandaloneModal = () => {
    const [text, setText] = useState(
        sprintf(
            // translators: %s: The name of the plugin, Extendify.
            __('Install %s', 'extendify'),
            'Extendify',
        ),
    )
    const [success, setSuccess] = useState(false)
    const [disabled, setDisabled] = useState(false)
    const initialFocus = useRef(null)
    const markNoticeSeen = useUserStore((state) => state.markNoticeSeen)
    const giveFreebieImports = useUserStore((state) => state.giveFreebieImports)
    const removeAllModals = useGlobalStore((state) => state.removeAllModals)

    const installAndActivate = () => {
        setText(__('Installing...', 'extendify'))
        setDisabled(true)
        Promise.all([
            General.ping('stln-modal-install'),
            Plugins.installAndActivate(['extendify']),
            new Promise((resolve) => setTimeout(resolve, 1000)),
        ])
            .then(async () => {
                setText(__('Success! Reloading...', 'extendify'))
                setSuccess(true)
                giveFreebieImports(10)
                await General.ping('stln-modal-success')
                window.location.reload()
            })
            .catch(async (error) => {
                console.error(error)
                setText(__('Error. See console.', 'extendify'))
                await General.ping('stln-modal-fail')
            })
    }

    const dismiss = async () => {
        removeAllModals()
        markNoticeSeen('standalone', 'modalNotices')
        await General.ping('stln-modal-x')
    }
    return (
        <SplitModal ref={initialFocus} onClose={dismiss}>
            <div>
                <div className="mb-10 flex items-center space-x-2 text-extendify-black">
                    {brandLogo}
                </div>
                <h3 className="text-xl">
                    {sprintf(
                        // translators: %s: The name of the plugin, Extendify.
                        __('Get the brand new %s plugin today!', 'extendify'),
                        'Extendify',
                    )}
                </h3>
                <p
                    className="text-sm text-black"
                    dangerouslySetInnerHTML={{
                        __html: safeHTML(
                            sprintf(
                                // translators: %1$s: The name of the plugin, Extendify; %2$s and %3$s: <strong> tags.
                                __(
                                    'Install the new %1$s Library plugin to get the latest we have to offer — right from WordPress.org. Plus, well send you %2$s10 more imports%3$s. Nice.',
                                    'extendify',
                                ),
                                'Extendify',
                                '<strong>',
                                '</strong>',
                            ),
                        ),
                    }}
                />
                <div>
                    <button
                        onClick={installAndActivate}
                        ref={initialFocus}
                        disabled={disabled}
                        className="button-extendify-main button-focus mt-2 inline-flex justify-center px-4 py-3"
                        style={{ minWidth: '225px' }}>
                        {text}
                        {success || (
                            <Icon
                                icon={download2}
                                size={24}
                                className="ml-2 w-6 flex-grow-0"
                            />
                        )}
                    </button>
                </div>
            </div>
            <div className="flex w-full justify-end rounded-tr-sm rounded-br-sm bg-extendify-secondary">
                <img
                    alt={__('Upgrade Now', 'extendify')}
                    className="rounded-br-sm max-w-full rounded-tr-sm"
                    src={
                        window.extendifyData.asset_path +
                        '/modal-extendify-purple.png'
                    }
                />
            </div>
        </SplitModal>
    )
}
