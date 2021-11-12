import { useGlobalStore } from '../state/GlobalState'
import { useUserStore } from '../state/User'
import { __ } from '@wordpress/i18n'

export default function LoginButton() {
    const apiKey = useUserStore(state => state.apiKey)
    if (apiKey.length === 0) {
        return <button
            type="button"
            className="components-button inline-block flex-1 text-center hover:bg-gray-100"
            onClick={() => useGlobalStore.setState({
                currentPage: 'login',
            })}>
            {__('Log into account', 'extendify-sdk')}
        </button>
    }

    // This code currently won't render unless in DEVMODE
    return <button
        type="button"
        className="components-button inline-block flex-1 text-center hover:bg-gray-100"
        onClick={() => useUserStore.setState({
            apiKey: '',
        })}>{__('Log out', 'extendify-sdk')}</button>
}
