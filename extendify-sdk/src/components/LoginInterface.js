import { useUserStore } from '../state/User'
import { useState, useEffect, useRef } from '@wordpress/element'
import { User as UserApi } from '../api/User'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { Spinner, Button } from '@wordpress/components'

import { Icon } from '@wordpress/icons'
import { user } from './icons/'
import { success as successIcon } from './icons/'
import { useIsDevMode } from '../hooks/helpers'

export default function LoginInterface({ actionCallback, initialFocus }) {
    const loggedIn = useUserStore((state) => state.apiKey.length)
    const [email, setEmail] = useState('')
    const [apiKey, setApiKey] = useState('')
    const [feedback, setFeedback] = useState('')
    const [feedbackType, setFeedbackType] = useState('info')
    const [isWorking, setIsWorking] = useState(false)
    const [success, setSuccess] = useState(false)
    const viewPatternsButtonRef = useRef(null)
    const licenseKeyRef = useRef(null)
    const devMode = useIsDevMode()

    useEffect(() => {
        setEmail(useUserStore.getState().email)
        // This will reset the default error state to info
        return () => setFeedbackType('info')
    }, [])

    useEffect(() => {
        success && viewPatternsButtonRef?.current?.focus()
    }, [success])

    const logout = () => {
        setApiKey('')
        useUserStore.setState({ apiKey: '' })
        setTimeout(() => {
            licenseKeyRef?.current?.focus()
        }, 0)
    }

    const confirmKey = async (event) => {
        event.preventDefault()
        setIsWorking(true)
        setFeedback('')
        const { token, error, exception, message } = await UserApi.authenticate(
            email,
            apiKey,
        )

        if (typeof message !== 'undefined') {
            setFeedbackType('error')
            setIsWorking(false)
            setFeedback(
                message.length
                    ? message
                    : 'Error: Are you interacting with the wrong server?',
            )
            return
        }

        if (error || exception) {
            setFeedbackType('error')
            setIsWorking(false)
            setFeedback(error.length ? error : exception)
            return
        }

        if (!token || typeof token !== 'string') {
            setFeedbackType('error')
            setIsWorking(false)
            setFeedback(__('Something went wrong', 'extendify'))
            return
        }

        setFeedbackType('success')
        setFeedback('Success!')
        setSuccess(true)
        setIsWorking(false)
        useUserStore.setState({
            email: email,
            apiKey: token,
        })
    }

    if (success) {
        return (
            <section className="w-80 space-y-8 text-center pt-2 pb-4">
                <Icon icon={successIcon} size={148} />
                <p className="text-lg text-extendify-black text-center leading-extra-tight font-semibold">
                    {__("You've signed in to Extendify", 'extendify')}
                </p>
                <Button
                    ref={viewPatternsButtonRef}
                    className="px-4 p-2 cursor-pointer text-center rounded bg-extendify-main text-white"
                    onClick={actionCallback}>
                    {__('View patterns', 'extendify')}
                </Button>
            </section>
        )
    }

    if (loggedIn) {
        return (
            <section className="space-y-8 w-full pb-2">
                <p className="text-base text-extendify-black leading-extra-tight">
                    {__('Account', 'extendify')}
                </p>
                <div className="flex justify-between items-center">
                    <div className="flex items-center space-x-2 -ml-2">
                        <Icon icon={user} size={48} />
                        <p className="text-extendify-black">
                            {email?.length
                                ? email
                                : __('Logged In', 'extendify')}
                        </p>
                    </div>
                    {devMode && (
                        <Button
                            className="px-4 py-3 cursor-pointer text-center rounded bg-extendify-main hover:bg-extendify-main-dark text-white"
                            onClick={logout}>
                            {__('Sign out', 'extendify')}
                        </Button>
                    )}
                </div>
            </section>
        )
    }

    return (
        <section className="w-80 text-left space-y-8 pb-6">
            <div>
                <p className="text-lg text-extendify-black text-center leading-extra-tight font-semibold">
                    {__('Sign in to Extendify', 'extendify')}
                </p>
                <p className="text-sm text-extendify-gray text-center space-x-1 leading-extra-tight">
                    <span>{__("Don't have an account?", 'extendify')}</span>
                    <a
                        href={`https://extendify.com/pricing?utm_source=${window.extendifyData.sdk_partner}&utm_medium=library&utm_campaign=sign-in-form&utm_content=sign-up`}
                        target="_blank"
                        className="underline hover:no-underline text-extendify-gray"
                        rel="noreferrer">
                        {__('Sign up', 'extendify')}
                    </a>
                </p>
            </div>
            <form onSubmit={confirmKey} className="space-y-2">
                <div className="flex items-center">
                    <label className="sr-only" htmlFor="extendify-login-email">
                        {__('Email address', 'extendify')}
                    </label>
                    <input
                        ref={initialFocus}
                        id="extendify-login-email"
                        name="extendify-login-email"
                        type="email"
                        className="border-2 p-2 w-full rounded"
                        placeholder={__('Email address', 'extendify')}
                        value={email.length ? email : ''}
                        onChange={(event) => setEmail(event.target.value)}
                    />
                </div>
                <div className="flex items-center">
                    <label
                        className="sr-only"
                        htmlFor="extendify-login-license">
                        {__('License key', 'extendify')}
                    </label>
                    <input
                        ref={licenseKeyRef}
                        id="extendify-login-license"
                        name="extendify-login-license"
                        type="text"
                        className="border-2 p-2 w-full rounded"
                        placeholder={__('License key', 'extendify')}
                        value={apiKey}
                        onChange={(event) => setApiKey(event.target.value)}
                    />
                </div>
                <div className="pt-2 flex justify-center">
                    <button
                        type="submit"
                        className="relative p-2 py-3 w-72 max-w-full flex justify-center cursor-pointer text-center rounded bg-extendify-main hover:bg-extendify-main-dark text-base text-white ">
                        <span>{__('Sign In', 'extendify')}</span>
                        {isWorking && (
                            <div className="absolute right-2.5">
                                <Spinner />
                            </div>
                        )}
                    </button>
                </div>
                {feedback && (
                    <div
                        className={classNames({
                            'border-gray-900 text-gray-900':
                                feedbackType === 'info',
                            'border-wp-alert-red text-wp-alert-red':
                                feedbackType === 'error',
                            'border-extendify-main text-extendify-main':
                                feedbackType === 'success',
                        })}>
                        {feedback}
                    </div>
                )}
                <div className="text-center pt-4">
                    <a
                        target="_blank"
                        rel="noreferrer"
                        href={`https://extendify.com/guides/sign-in?utm_source=${window.extendifyData.sdk_partner}&utm_medium=library&utm_campaign=sign-in-form&utm_content=need-help`}
                        className="underline hover:no-underline text-sm text-extendify-gray">
                        {__('Need Help?', 'extendify')}
                    </a>
                </div>
            </form>
        </section>
    )
}
