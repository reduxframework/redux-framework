import { useGlobalStore } from '../state/GlobalState'
import { useUserStore } from '../state/User'
import { __ } from '@wordpress/i18n'

export default function LoginButton() {
    const apiKey = useUserStore(state => state.apiKey)
    if (apiKey.length === 0) {
        return <button
            className="components-button"
            onClick={() => useGlobalStore.setState({
                currentPage: 'login',
            })}>
            {__('Log into account', 'extendify-sdk')}
        </button>
    }

    // This code currently won't run unless in DEVMODE
    return <button
        className="components-button"
        onClick={() => useUserStore.setState({
            apiKey: '',
        })}>{__('Log out', 'extendify-sdk')}</button>
}
