import {
    useEffect, useCallback, useState,
} from '@wordpress/element'
import { useGlobalStore } from './state/GlobalState'
import { useUserStore } from './state/User'
import { useTemplatesStore } from './state/Templates'
import MainWindow from './pages/parts/MainWindow'
import './utility-control'
import useTaxonomies from './hooks/useTaxonomies'

export default function ExtendifyLibrary({ show = false }) {
    const open = useGlobalStore(state => state.open)
    const setOpen = useGlobalStore(state => state.setOpen)
    const [ready, setReady] = useState(false)
    const showLibrary = useCallback(() => setOpen(true), [setOpen])
    const hideLibrary = useCallback(() => setOpen(false), [setOpen])
    const initTemplateData = useTemplatesStore(state => state.initTemplateData)

    // When the uuid of the user comes back from the database, we can
    // assume that the state object is ready. This is important to check
    // as the library may be "open" when loaded, but not ready.
    const userStoreReady = useUserStore(state => state.uuid.length > 0)
    const templatesStoreReady = useTemplatesStore(state => Object.keys(state.taxonomyDefaultState).length > 0)
    useTaxonomies(open)

    useEffect(() => {
        if (userStoreReady && templatesStoreReady) {
            // TODO: temporary needed until single views are removed.
            if (useGlobalStore.getState().currentPage === 'single') {
                useGlobalStore.setState({
                    currentPage: 'main',
                })
            }
            initTemplateData()
            setReady(true)
        }
    }, [userStoreReady, templatesStoreReady, initTemplateData])

    useEffect(() => {
        show && setOpen(true)
    }, [show, setOpen])

    // Let the visibility to be controlled from outside the application
    useEffect(() => {
        window.addEventListener('extendify-sdk::open-library', showLibrary)
        window.addEventListener('extendify-sdk::close-library', hideLibrary)
        return () => {
            window.removeEventListener('extendify-sdk::open-library', showLibrary)
            window.removeEventListener('extendify-sdk::close-library', hideLibrary)
        }
    }, [hideLibrary, showLibrary])

    if (!ready) {
        return null
    }
    return <MainWindow/>
}
