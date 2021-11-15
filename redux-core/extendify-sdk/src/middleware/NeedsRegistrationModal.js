import { __ } from '@wordpress/i18n'
import {
    Modal, Button, ButtonGroup,
} from '@wordpress/components'
import { render, useRef } from '@wordpress/element'
import { useUserStore } from '../state/User'
import { useState } from '@wordpress/element'
import { User as UserApi } from '../api/User'

export default function NeedsRegistrationModal({ finished }) {
    const [email, setEmail] = useState('')
    const submitRef = useRef()

    const registerAndContinue = async (event) => {
        event.preventDefault()
        await UserApi.registerMailingList(email)
        useUserStore.setState({
            registration: { email },
        })
        finished()
    }

    return <Modal
        className="extendify-sdk"
        title={__('One last step...', 'extendify-sdk')}
        isDismissible={false}>
        <p className="m-0 mb-4 max-w-md">
            {__('Register now to receive updates and special offers from Extendify', 'extendify-sdk')}
        </p>
        <form onSubmit={registerAndContinue} className="flex space-x-4 mb-8">
            <div className="relative w-full max-w-xs">
                <input
                    id="extendify-email-register"
                    required
                    onChange={(event) => setEmail(event.target.value)}
                    type="text"
                    className="extendify-special-input button-focus text-sm h-8 min-h-0 border border-gray-900 special-input placeholder-transparent rounded-none w-full px-2"
                    placeholder={__('Email', 'extendify-sdk')} />
                <label htmlFor="extendify-email-register" className="-top-3 bg-white absolute left-1 px-1 transition-all">{__('Email', 'extendify-sdk')}</label>
            </div>
            <input type="submit" className="hidden" />
        </form>

        <ButtonGroup>
            <Button ref={submitRef} isPrimary onClick={registerAndContinue}>
                {__('Submit and import', 'extendify-sdk')}
            </Button>
            <Button isTertiary onClick={finished} style={{
                boxShadow: 'none', margin: '0 4px',
            }}>
                {__('Skip and import', 'extendify-sdk')}
            </Button>
        </ButtonGroup>
    </Modal>
}

export function check() {
    return {
        id: 'NeedsRegistrationModal',
        pass: (Boolean(useUserStore.getState().registration?.email || useUserStore.getState().apiKey)),
        allow() {},
        deny() {
            return new Promise((finished) => {
                render(<NeedsRegistrationModal finished={finished}/>, document.getElementById('extendify-root'))
            })
        },
    }
}
