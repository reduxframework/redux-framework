import { Icon } from '@wordpress/components'
import { Button } from '@wordpress/components'
import { useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { General } from '@extendify/api/General'
import { useGlobalStore } from '@extendify/state/GlobalState'
import { useUserStore } from '@extendify/state/User'
import {
    growthArrow,
    patterns,
    layouts,
    support,
    star,
    brandLogo,
    diamond,
} from '../icons'
import { SplitModal } from './SplitModal'
import { SettingsModal } from './settings/SettingsModal'

export const NoImportModal = () => {
    const pushModal = useGlobalStore((state) => state.pushModal)
    const initialFocus = useRef(null)
    return (
        <SplitModal
            isOpen={true}
            ref={initialFocus}
            leftContainerBgColor="bg-white">
            <div>
                <div className="mb-5 flex items-center space-x-2 text-extendify-black">
                    {brandLogo}
                </div>

                <h3 className="mt-0 text-xl">
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
                        className="button-extendify-main button-focus mt-2 inline-flex justify-center px-4 py-3"
                        style={{ minWidth: '225px' }}
                        href={`https://extendify.com/pricing/?utm_source=${
                            window.extendifyData.sdk_partner
                        }&utm_medium=library&utm_campaign=no-imports-modal&utm_content=get-unlimited-imports&utm_group=${useUserStore
                            .getState()
                            .activeTestGroupsUtmValue()}`}
                        onClick={async () =>
                            await General.ping('no-imports-modal-click')
                        }
                        rel="noreferrer">
                        {__('Get Unlimited Imports', 'extendify')}
                        <Icon icon={growthArrow} size={24} className="-mr-1" />
                    </a>
                    <p className="mb-0 text-left text-sm text-extendify-gray">
                        {__('Have an account?', 'extendify')}
                        <Button
                            onClick={() => pushModal(<SettingsModal />)}
                            className="pl-2 text-sm text-extendify-gray underline hover:no-underline">
                            {__('Sign in', 'extendify')}
                        </Button>
                    </p>
                </div>
            </div>
            <div className="flex h-full flex-col justify-center space-y-2 p-10 text-black">
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
