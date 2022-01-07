import { useEffect, useCallback, useState } from '@wordpress/element'
import { useGlobalStore } from './state/GlobalState'
import { useUserStore } from './state/User'
import { useTemplatesStore } from './state/Templates'
import MainWindow from './pages/MainWindow'
import './utility-control'
import useTaxonomies from './hooks/useTaxonomies'
import { General as GeneralApi } from './api/General'

export default function ExtendifyLibrary({ show = false }) {
    const open = useGlobalStore((state) => state.open)
    const setOpen = useGlobalStore((state) => state.setOpen)
    const [ready, setReady] = useState(false)
    const showLibrary = useCallback(() => setOpen(true), [setOpen])
    const hideLibrary = useCallback(() => setOpen(false), [setOpen])
    const initTemplateData = useTemplatesStore(
        (state) => state.initTemplateData,
    )

    // When the uuid of the user comes back from the database, we can
    // assume that the state object is ready. This is important to check
    // as the library may be "open" when loaded, but not ready.
    const userStoreReady = useUserStore((state) => state.uuid.length > 0)
    const templatesStoreReady = useTemplatesStore(
        (state) => Object.keys(state.taxonomyDefaultState).length > 0,
    )
    useTaxonomies(open)

    useEffect(() => {
        if (userStoreReady && templatesStoreReady) {
            initTemplateData()
            setReady(true)
        }
    }, [userStoreReady, templatesStoreReady, initTemplateData])

    useEffect(() => {
        show && setOpen(true)
    }, [show, setOpen])

    useEffect(() => {
        GeneralApi.metaData().then((data) => {
            useGlobalStore.setState({
                metaData: data,
            })
        })
    }, [])

    // Let the visibility to be controlled from outside the application
    useEffect(() => {
        window.addEventListener('extendify::open-library', showLibrary)
        window.addEventListener('extendify::close-library', hideLibrary)
        return () => {
            window.removeEventListener('extendify::open-library', showLibrary)
            window.removeEventListener('extendify::close-library', hideLibrary)
        }
    }, [hideLibrary, showLibrary])

    if (!ready) {
        return null
    }
    return <MainWindow />
}
