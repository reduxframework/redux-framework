import { useUserStore } from '../state/User'
import { useState, useEffect } from '@wordpress/element'
import { User as UserApi } from '../api/User'
import { __ } from '@wordpress/i18n'
import { useGlobalStore } from '../state/GlobalState'
import classNames from 'classnames'

export default function LoginInterface() {
    const [apiKey, setApiKey] = useState(useUserStore.getState().apiKey)
    const [email, setEmail] = useState(useUserStore.getState().email)
    const [feedback, setFeedback] = useState('')
    const [feedbackType, setFeedbackType] = useState('info')
    const [possibleEmail, setPossibleEmail] = useState('')

    // This will reset the default error state to info
    useEffect(() => () => setFeedbackType('info'), [])

    const confirmKey = async (event) => {
        event.preventDefault()
        setFeedback('')
        const emailToSend = email.length
            ? email
            : possibleEmail
        const { token, error, exception, message } = await UserApi.authenticate(emailToSend, apiKey)

        if (typeof message !== 'undefined') {
            setFeedbackType('error')
            return setFeedback(message.length
                ? message
                : 'Error: Are you interacting with the wrong server?')
        }

        if (error || exception) {
            setFeedbackType('error')
            return setFeedback(error.length
                ? error
                : exception)
        }

        if (!token || typeof token !== 'string') {
            setFeedbackType('error')
            return setFeedback(__('Something went wrong', 'extendify-sdk'))
        }

        setFeedbackType('success')
        setFeedback('Success!')
        await new Promise((resolve) => setTimeout(resolve, 1500))
        useUserStore.setState({
            // email: emailToSend,
            apiKey: token,
        })
        useGlobalStore.setState({
            currentPage: 'main',
        })
    }

    useEffect(() => {
        if (!email) {
            UserApi.getMeta('user_email')
                .then((value) => setPossibleEmail(value))
        }
    }, [email])

    return <section className="w-96 text-left md:-mt-32">
        <h1 className="border-b border-gray-900 mb-12 pb-4">{__('Welcome', 'extendify-sdk')}</h1>
        {feedback &&
            <div className={classNames({
                'border-b pb-6 mb-6 -mt-6': true,
                'border-gray-900 text-gray-900': feedbackType === 'info',
                'border-wp-alert-red text-wp-alert-red': feedbackType === 'error',
                'border-extendify-main text-extendify-main': feedbackType === 'success',
            })}>
                {feedback}
            </div>
        }
        <form onSubmit={confirmKey} className=" space-y-6">
            <div className="flex items-center">
                <label htmlFor="extendifysdk-login-email" className="w-32 font-bold">
                    {__('Email:', 'extendify-sdk')}
                </label>
                <input
                    id="extendifysdk-login-email"
                    name="extendifysdk-login-email"
                    type="email"
                    className="border px-2 w-full"
                    placeholder="Email"
                    value={email.length
                        ? email
                        : possibleEmail}
                    onChange={(event) => setEmail(event.target.value)}/>
            </div>
            <div className="flex items-center">
                <label htmlFor="extendifysdk-login-license" className="w-32 font-bold">
                    {__('License:', 'extendify-sdk')}
                </label>
                <input
                    id="extendifysdk-login-license"
                    name="extendifysdk-login-email"
                    type="text"
                    className="border px-2 w-full"
                    placeholder="License key"
                    value={apiKey}
                    onChange={(event) => setApiKey(event.target.value)}/>
            </div>
            <div className="flex justify-end">
                <button
                    type="submit"
                    className="button-extendify-main p-3 px-4">
                    {__('Sign in', 'extendify-sdk')}
                </button>
            </div>
        </form>
    </section>
}
