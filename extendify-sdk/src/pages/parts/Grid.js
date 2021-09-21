import {
    useEffect, useState, useCallback, useRef,
} from '@wordpress/element'
import { useTemplatesStore } from '../../state/Templates'
import { Templates as TemplatesApi } from '../../api/Templates'
import { useInView } from 'react-intersection-observer'
import { Spinner, Button } from '@wordpress/components'
import { __, sprintf } from '@wordpress/i18n'
import { useIsMounted } from '../../hooks/helpers'
import TemplateButton from '../../components/TemplateButton'

export default function TemplatesList() {
    const isMounted = useIsMounted()
    const templates = useTemplatesStore(state => state.templates)
    const setActiveTemplate = useTemplatesStore(state => state.setActive)
    const appendTemplates = useTemplatesStore(state => state.appendTemplates)
    const [serverError, setServerError] = useState('')
    const [nothingFound, setNothingFound] = useState(false)
    // const [imagesLoaded, setImagesLoaded] = useState([])
    const [loadMoreRef, inView] = useInView()

    const updateSearchParams = useTemplatesStore(state => state.updateSearchParams)
    const searchParamsRaw = useTemplatesStore(state => state.searchParams)

    // Store the next page in case we have pagination
    const nextPage = useRef(useTemplatesStore.getState().nextPage)
    const searchParams = useRef(useTemplatesStore.getState().searchParams)
    // Connect to the store on mount, disconnect on unmount, catch state-changes in a reference
    useEffect(() => useTemplatesStore.subscribe(n => (nextPage.current = n),
        state => state.nextPage), [])
    useEffect(() => useTemplatesStore.subscribe(s => (searchParams.current = s),
        state => state.searchParams), [])

    // Fetch the templates then add them to the current state
    // TODO: This works, but it's not really doing what it's intended to do
    // as it has a side effect in there, and isn't pure.
    // It could be extracted to a hook
    const fetchTemplates = useCallback(async () => {
        setServerError('')
        setNothingFound(false)
        const response = await TemplatesApi.get(searchParams.current, { offset: nextPage.current })
            .catch((error) => {
                console.error(error)
                setServerError(error && error.message
                    ? error.message
                    : __('Unknown error occured. Check browser console or contact support.', 'extendify-sdk'))
            })
        if (!isMounted.current) {
            return
        }
        if (response?.error?.length) {
            setServerError(response?.error)
        }
        if (response?.records && searchParamsRaw === searchParams.current) {
            useTemplatesStore.setState({
                nextPage: response.offset,
            })
            appendTemplates(response.records)
            setNothingFound(response.records.length <= 0)
        }
    }, [searchParamsRaw, appendTemplates, isMounted])

    // This is the main driver for loading templates
    // This loads the initial batch of templates. But if we don't yet have taxonomies.
    // There's also an option to skip loading on first mount
    useEffect(() => {
        if (!Object.keys(searchParams.current.taxonomies).length) {
            return
        }

        if (useTemplatesStore.getState().skipNextFetch) {
            // This is useful if the templates are fetched already and
            // the library moves to/from another state that re-renders
            // The point is to keep the logic close to the list. That may change someday
            useTemplatesStore.setState({
                skipNextFetch: false,
            })
            return
        }
        // setImagesLoaded([])
        fetchTemplates()
    }, [fetchTemplates, searchParams])

    // Fetches when the load more is in view
    useEffect(() => {
        inView && fetchTemplates()
    }, [inView, fetchTemplates])

    if (serverError.length) {
        return <div className="text-left">
            <h2 className="text-left">{__('Server error', 'extendify-sdk')}</h2>
            <code className="block max-w-xl p-4 mb-4" style={{
                minHeight: '10rem',
            }}>{serverError}</code>
            <Button isTertiary onClick={() => {
                // setImagesLoaded([])
                updateSearchParams({
                    taxonomies: {},
                    search: '',
                })
                fetchTemplates()
            }}>{ __('Press here to reload experience')}</Button>
        </div>
    }

    if (nothingFound) {
        if (searchParamsRaw?.search.length) {
            return <h2 className="text-left">
                {sprintf(__('No results for %s.', 'extendify-sdk'), searchParamsRaw?.search)}
            </h2>
        }
        return <h2 className="text-left">{__('No results found.', 'extendify-sdk')}</h2>
    }

    if (!templates.length) {
        return <div className="flex items-center justify-center w-full sm:mt-64">
            <Spinner/>
        </div>
    }

    return <>
        <ul className="flex-grow gap-6 grid xl:grid-cols-2 2xl:grid-cols-3 pb-32 m-0">
            {templates.map((template) => {
                return <li key={template.id}>
                    <TemplateButton
                        template={template}
                        setActiveTemplate={() => setActiveTemplate(template)}
                        imageLoaded={() => {}}
                    />
                </li>
            })}
        </ul>
        {useTemplatesStore.getState().nextPage && <>
            <div
                className="-translate-y-full flex flex-col h-80 items-end justify-end my-2 relative transform z-0 text"
                ref={loadMoreRef}
                style={{ zIndex: -1 }}>
            </div>
            <div className="my-4">
                <Spinner/>
            </div>
        </>}
    </>
}
