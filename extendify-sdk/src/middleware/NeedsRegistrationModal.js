import { __, sprintf } from '@wordpress/i18n'
import { Button } from '@wordpress/components'
import { useUserStore } from '../state/User'
import { useState, useRef } from '@wordpress/element'
import { User as UserApi } from '../api/User'
import { useGlobalStore } from '../state/GlobalState'
import { Modal } from '../components/modals/Modal'
import { brandMark } from '../components/icons'
import { Icon } from '@wordpress/icons'
import Primary from '../components/buttons/Primary'
import { safeHTML } from '@wordpress/dom'

export default function NeedsRegistrationModal({ finished, resetMiddleware }) {
    const [email, setEmail] = useState('')
    const remainingImports = useUserStore((state) => state.remainingImports)
    const emailRef = useRef(null)
    const removeAllModals = useGlobalStore((state) => state.removeAllModals)
    const registerAndContinue = async (event) => {
        event.preventDefault()
        useUserStore.setState({
            registration: { email },
            freebieImports: Number(useUserStore.getState().freebieImports) + 10,
        })
        await UserApi.registerMailingList(email)
        finished()
    }

    const optOut = () => {
        useUserStore.setState({
            registration: { optedOut: true },
        })
        finished()
    }

    return (
        <Modal
            isOpen={true}
            onClose={() => {
                removeAllModals()
                resetMiddleware()
            }}
            ref={emailRef}>
            <div className="p-10 space-y-4 text-extendify-black">
                <Icon icon={brandMark} size={42} className="-ml-2 -mt-2" />
                <h3 className="text-xl md:leading-3">
                    {remainingImports() == 1
                        ? __('This is your last import', 'extendify')
                        : sprintf(
                              __('You now have %s imports left', 'extendify'),
                              remainingImports(),
                          )}
                </h3>
                <p
                    className="max-w-md text-sm"
                    dangerouslySetInnerHTML={{
                        __html: safeHTML(
                            sprintf(
                                // Translators: 1. and 2. are <strong> tags
                                __(
                                    "Subscribe and %1$swe'll send you 10 more%2$s. Plus you'll get updates and special offers from us fine folks at Extendify.",
                                    'extendify',
                                ),
                                '<strong>',
                                '</strong>',
                            ),
                        ),
                    }}></p>
                <form
                    onSubmit={registerAndContinue}
                    className="flex space-x-2 py-2 items-stretch">
                    <div className="relative w-full max-w-xs">
                        <label
                            htmlFor="extendify-email-register"
                            className="sr-only">
                            {__('Email', 'extendify')}
                        </label>
                        <input
                            ref={emailRef}
                            id="extendify-email-register"
                            name="extendify-email-register"
                            required
                            onChange={(event) => setEmail(event.target.value)}
                            type="email"
                            className="text-sm min-h-0 p-2 border-2 border-gray-900 rounded-md w-full"
                            placeholder={__(
                                'Enter your email address',
                                'extendify',
                            )}
                        />
                    </div>
                    <Primary type="submit" className="px-4 rounded-md my-0">
                        {__('Submit', 'extendify')}
                    </Primary>
                </form>
                <Button
                    isLink
                    className="text-extendify-gray text-sm my-0"
                    onClick={optOut}>
                    {__('No thanks — finish importing', 'extendify')}
                </Button>
            </div>
        </Modal>
    )
}

const pass = () => {
    const userState = useUserStore.getState()
    const remainingImports = userState?.remainingImports()

    // On the last import, show the modal if they opted out
    if (remainingImports === 1 && userState?.registration?.optedOut) {
        return false
    }
    return (
        userState?.registration?.email?.length || // Already registered
        userState?.apiKey?.length || // Already a member
        userState?.registration?.optedOut || // Already opted out
        userState?.imports === 0 // Hasn't imported yet
    )
}

export function check() {
    const pushModal = useGlobalStore.getState().pushModal

    return {
        id: 'NeedsRegistrationModal',
        pass: pass(),
        allow() {},
        deny() {
            return new Promise((resolve, reject) => {
                pushModal(
                    <NeedsRegistrationModal
                        finished={resolve}
                        resetMiddleware={reject}
                    />,
                )
            })
        },
    }
}
