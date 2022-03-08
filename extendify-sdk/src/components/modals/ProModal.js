import { Icon } from '@wordpress/components'
import { useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { General } from '@extendify/api/General'
import { growthArrow, brandLogo } from '@extendify/components/icons'
import { useUserStore } from '@extendify/state/User'
import { SplitModal } from './SplitModal'

export const ProModal = () => {
    const initialFocus = useRef(null)
    return (
        <SplitModal isOpen={true} invertedButtonColor={true} ref={initialFocus}>
            <div>
                <div className="mb-5 flex items-center space-x-2 text-extendify-black">
                    {brandLogo}
                </div>
                <h3 className="mt-0 text-xl">
                    {__(
                        'Get unlimited access to all our Pro patterns & layouts',
                        'extendify',
                    )}
                </h3>
                <p className="text-sm text-black">
                    {__(
                        "Upgrade to Extendify Pro and use all the patterns and layouts you'd like, including our exclusive Pro catalog.",
                        'extendify',
                    )}
                </p>
                <div>
                    <a
                        target="_blank"
                        ref={initialFocus}
                        className="button-extendify-main button-focus mt-2 inline-flex justify-center px-4 py-3"
                        style={{ minWidth: '225px' }}
                        href={`https://extendify.com/pricing/?utm_source=${
                            window.extendifyData.sdk_partner
                        }&utm_medium=library&utm_campaign=pro-modal&utm_content=upgrade-now&utm_group=${useUserStore
                            .getState()
                            .activeTestGroupsUtmValue()}`}
                        onClick={async () =>
                            await General.ping('pro-modal-click')
                        }
                        rel="noreferrer">
                        {__('Upgrade Now', 'extendify')}
                        <Icon icon={growthArrow} size={24} className="-mr-1" />
                    </a>
                </div>
            </div>
            <div className="justify-endrounded-tr-sm flex w-full rounded-br-sm bg-black">
                <img
                    alt={__('Upgrade Now', 'extendify')}
                    className="max-w-full rounded-tr-sm rounded-br-sm"
                    src={
                        window.extendifyData.asset_path +
                        '/modal-extendify-black.png'
                    }
                />
            </div>
        </SplitModal>
    )
}
