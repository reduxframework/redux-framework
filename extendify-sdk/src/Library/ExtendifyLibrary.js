import { useEffect, useCallback } from '@wordpress/element'
import { General as GeneralApi } from '@library/api/General'
import MainWindow from '@library/pages/MainWindow'
import { useGlobalStore } from '@library/state/GlobalState'
import { useTemplatesStore } from '@library/state/Templates'
import { useUserStore } from '@library/state/User'
import '@library/utility-control'
import { useTaxonomyStore } from './state/Taxonomies'

export default function ExtendifyLibrary({ show = false }) {
    const { open, setReady, setOpen } = useGlobalStore()
    const showLibrary = useCallback(() => setOpen(true), [setOpen])
    const hideLibrary = useCallback(() => setOpen(false), [setOpen])
    const { initTemplateData } = useTemplatesStore()
    const fetchTaxonomies = useTaxonomyStore((state) => state.fetchTaxonomies)

    // When the uuid of the user comes back from the database, we can
    // assume that the state object is ready. This is important to check
    // as the library may be "open" when loaded, but not ready.
    const userHasHydrated = useUserStore((state) => state._hasHydrated)
    const taxonomiesReady = useTemplatesStore(
        (state) => Object.keys(state.taxonomyDefaultState).length > 0,
    )

    useEffect(() => {
        if (!open) return
        fetchTaxonomies().then(() => {
            useTemplatesStore.getState().setupDefaultTaxonomies()
        })
    }, [open, fetchTaxonomies])

    useEffect(() => {
        if (userHasHydrated && taxonomiesReady) {
            initTemplateData()
            setReady(true)
        }
    }, [userHasHydrated, taxonomiesReady, initTemplateData, setReady])

    useEffect(() => {
        const search = new URLSearchParams(window.location.search)
        if (show || search.has('ext-open')) {
            setOpen(true)
        }
    }, [show, setOpen])

    useEffect(() => {
        if (
            window?.location?.pathname?.includes('post-new.php') &&
            window.extendifyData.openOnNewPage === '1'
        ) {
            setOpen(true)
        }
    }, [setOpen])

    useEffect(() => {
        GeneralApi.metaData().then((data) => {
            useGlobalStore.setState({
                metaData: data,
            })
        })
    }, [])

    // Let the visibility to be controlled from outside the application
    // e.g. from the main button in the toolbar.
    useEffect(() => {
        window.addEventListener('extendify::open-library', showLibrary)
        window.addEventListener('extendify::close-library', hideLibrary)
        return () => {
            window.removeEventListener('extendify::open-library', showLibrary)
            window.removeEventListener('extendify::close-library', hideLibrary)
        }
    }, [hideLibrary, showLibrary])

    return <MainWindow />
}
