import { Icon } from '@wordpress/components'
import { Button } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { SplitModal } from './SplitModal'
import SettingsModal from './SettingsModal'
import {
    growthArrow,
    patterns,
    layouts,
    support,
    star,
    brandLogo,
} from '../icons'
import { useGlobalStore } from '../../state/GlobalState'
import Primary from '../buttons/Primary'

export const NoImportModal = () => {
    const pushModal = useGlobalStore((state) => state.pushModal)

    return (
        <SplitModal isOpen={true}>
            <div className="bg-white p-12 text-center items-center">
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
                        <Icon icon={growthArrow} size={24} className="-mr-1" />
                    </Primary>
                    <p className="text-sm text-extendify-gray mb-0">
                        {__('Have an account?', 'extendify')}
                        <Button
                            onClick={() => pushModal(<SettingsModal />)}
                            className="underline hover:no-underline text-sm text-extendify-gray pl-2">
                            {__('Sign in', 'extendify')}
                        </Button>
                    </p>
                </div>
            </div>
            <div className="space-y-2 justify-center p-10 text-black">
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
        </SplitModal>
    )
}
