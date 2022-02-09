import { Icon } from '@wordpress/components'
import { Button } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { useRef } from '@wordpress/element'
import { SplitModal } from './SplitModal'
import SettingsModal from './SettingsModal'
import {
    growthArrow,
    patterns,
    layouts,
    support,
    star,
    brandLogo,
    diamond,
} from '../icons'
import { useGlobalStore } from '../../state/GlobalState'

export const NoImportModal = () => {
    const pushModal = useGlobalStore((state) => state.pushModal)
    const initialFocus = useRef(null)
    return (
        <SplitModal
            isOpen={true}
            ref={initialFocus}
            leftContainerBgColor="bg-white">
            <div>
                <div className="flex space-x-2 items-center mb-5 text-extendify-black">
                    {brandLogo}
                </div>

                <h3 className="text-xl mt-0">
                    {__("You're out of imports", 'extendify')}
                </h3>
                <p className="text-sm text-black">
                    {__(
                        'Sign up today and get unlimited access to our entire collection of patterns and page layouts.',
                        'extendify',
                    )}
                </p>
                <div>
                    <a
                        target="_blank"
                        ref={initialFocus}
                        className="button-extendify-main inline-flex mt-2 px-4 py-3 button-focus justify-center"
                        style={{ minWidth: '225px' }}
                        href={`https://extendify.com/pricing/?utm_source=${window.extendifyData.sdk_partner}&utm_medium=library&utm_campaign=no-imports-modal&utm_content=get-unlimited-imports`}
                        rel="noreferrer">
                        {__('Get Unlimited Imports', 'extendify')}
                        <Icon icon={growthArrow} size={24} className="-mr-1" />
                    </a>
                    <p className="text-sm text-extendify-gray mb-0 text-left">
                        {__('Have an account?', 'extendify')}
                        <Button
                            onClick={() => pushModal(<SettingsModal />)}
                            className="underline hover:no-underline text-sm text-extendify-gray pl-2">
                            {__('Sign in', 'extendify')}
                        </Button>
                    </p>
                </div>
            </div>
            <div className="space-y-2 flex flex-col justify-center p-10 text-black h-full">
                <div className="flex items-center space-x-3">
                    <Icon icon={patterns} size={24} />
                    <span className="text-sm leading-none">
                        {__("Access to 100's of Patterns", 'extendify')}
                    </span>
                </div>
                <div className="flex items-center space-x-3">
                    <Icon icon={diamond} size={24} />
                    <span className="text-sm leading-none">
                        {__('Access to "Pro" catalog', 'extendify')}
                    </span>
                </div>
                <div className="flex items-center space-x-3">
                    <Icon icon={layouts} size={24} />
                    <span className="text-sm leading-none">
                        {__('Beautiful full page layouts', 'extendify')}
                    </span>
                </div>
                <div className="flex items-center space-x-3">
                    <Icon icon={support} size={24} />
                    <span className="text-sm leading-none">
                        {__('Fast and friendly support', 'extendify')}
                    </span>
                </div>
                <div className="flex items-center space-x-3">
                    <Icon icon={star} size={24} />
                    <span className="text-sm leading-none">
                        {__('14-Day guarantee', 'extendify')}
                    </span>
                </div>
            </div>
        </SplitModal>
    )
}
