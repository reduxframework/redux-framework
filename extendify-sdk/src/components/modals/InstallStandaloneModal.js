import { useState, useRef } from '@wordpress/element'
import { Icon } from '@wordpress/components'
import { __, sprintf } from '@wordpress/i18n'
import { SplitModal } from './SplitModal'
import { download2, brandLogo } from '../icons'
import { Plugins } from '../../api/Plugins'
import { General } from '../../api/General'
import { useUserStore } from '../../state/User'
import { safeHTML } from '@wordpress/dom'
import { useGlobalStore } from '../../state/GlobalState'

export const InstallStandaloneModal = () => {
    const [text, setText] = useState(__('Install Extendify', 'extendify'))
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
                <div className="flex space-x-2 items-center mb-10 text-extendify-black">
                    {brandLogo}
                </div>
                <h3 className="text-xl">
                    {__(
                        'Get the brand new Extendify plugin today!',
                        'extendify',
                    )}
                </h3>
                <p
                    className="text-sm text-black"
                    dangerouslySetInnerHTML={{
                        __html: safeHTML(
                            sprintf(
                                __(
                                    'Install the new Extendify Library plugin to get the latest we have to offer — right from WordPress.org. Plus, well send you %1$s10 more imports%2$s. Nice.',
                                    'extendify',
                                ),
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
                        className="button-extendify-main inline-flex mt-2 px-4 py-3 button-focus justify-center"
                        style={{ minWidth: '225px' }}>
                        {text}
                        {success || (
                            <Icon
                                icon={download2}
                                size={24}
                                className="w-6 ml-2 flex-grow-0"
                            />
                        )}
                    </button>
                </div>
            </div>
            <div className="w-full bg-extendify-secondary flex justify-end rounded-tr-sm rounded-br-sm">
                <img
                    alt={__('Upgrade Now', 'extendify')}
                    className="max-w-full rounded-tr-sm roudned-br-sm"
                    src={
                        window.extendifyData.asset_path +
                        '/modal-extendify-purple.png'
                    }
                />
            </div>
        </SplitModal>
    )
}
