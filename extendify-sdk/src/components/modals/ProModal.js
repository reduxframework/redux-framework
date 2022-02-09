import { Icon } from '@wordpress/components'
import { useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { SplitModal } from './SplitModal'
import { growthArrow, brandLogo } from '../icons'

export const ProModal = () => {
    const initialFocus = useRef(null)
    return (
        <SplitModal isOpen={true} invertedButtonColor={true} ref={initialFocus}>
            <div>
                <div className="flex space-x-2 items-center mb-5 text-extendify-black">
                    {brandLogo}
                </div>
                <h3 className="text-xl mt-0">
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
                        className="button-extendify-main inline-flex mt-2 px-4 py-3 button-focus justify-center"
                        style={{ minWidth: '225px' }}
                        href={`https://extendify.com/pricing/?utm_source=${window.extendifyData.sdk_partner}&utm_medium=library&utm_campaign=pro-modal&utm_content=upgrade-now`}
                        rel="noreferrer">
                        {__('Upgrade Now', 'extendify')}
                        <Icon icon={growthArrow} size={24} className="-mr-1" />
                    </a>
                </div>
            </div>
            <div className="w-full bg-black flex justify-endrounded-tr-sm rounded-br-sm">
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
