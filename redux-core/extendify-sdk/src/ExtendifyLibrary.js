import { useEffect, useCallback } from '@wordpress/element'
import { useGlobalStore } from './state/GlobalState'
import { useUserStore } from './state/User'
import MainWindow from './pages/parts/MainWindow'

export default function ExtendifyLibrary({ show = false }) {
    const setOpen = useGlobalStore(state => state.setOpen)
    const showLibrary = useCallback(() => setOpen(true), [setOpen])
    const hideLibrary = useCallback(() => {
        setOpen(false)
    }, [setOpen])

    useEffect(() => {
        show && setOpen(true)
    }, [show, setOpen])

    // Here for legacy reasons, we're checking if they have the old key stored
    useEffect(() => {
        if (window.localStorage.getItem('etfy_library__key')) {
            useUserStore.setState({
                apiKey: 'any-key-will-work-during-beta',
            })
        }
        return () => window.localStorage.removeItem('etfy_library__key')
    }, [])

    // Let the visibility to be controlled from outside the application
    useEffect(() => {
        window.addEventListener('extendify-sdk::open-library', showLibrary)
        window.addEventListener('extendify-sdk::close-library', hideLibrary)
        return () => {
            window.removeEventListener('extendify-sdk::open-library', showLibrary)
            window.removeEventListener('extendify-sdk::close-library', hideLibrary)
        }
    }, [hideLibrary, showLibrary])

    return <MainWindow/>
}
