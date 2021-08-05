import {
    useEffect, useState, useCallback, useRef,
} from '@wordpress/element'
import { useTemplatesStore } from '../state/Templates'
import { Templates as TemplatesApi } from '../api/Templates'
import { useInView } from 'react-intersection-observer'
import { Spinner, Button } from '@wordpress/components'
import { __, sprintf } from '@wordpress/i18n'

export default function TemplatesList({ templates }) {
    const setActiveTemplate = useTemplatesStore(state => state.setActive)
    const activeTemplate = useTemplatesStore(state => state.activeTemplate)
    const appendTemplates = useTemplatesStore(state => state.appendTemplates)
    const [serverError, setServerError] = useState('')
    const [nothingFound, setNothingFound] = useState(false)
    const [imagesLoaded, setImagesLoaded] = useState([])
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
    // as it has a side effect in there, and isn't pure. It should be updated
    const fetchTemplates = useCallback(async () => {
        setServerError('')
        setNothingFound(false)
        const response = await TemplatesApi.get(searchParams.current, nextPage.current)
            .catch((error) => {
                console.error(error)
                setServerError(error && error.message
                    ? error.message
                    : __('Unknown error occured. Check browser console or contact support.', 'extendify-sdk'))
            })
        if (response?.error?.length) {
            setServerError(response?.error)
        }
        if (response?.records && searchParamsRaw === searchParams.current) {
            appendTemplates(response.records)
            setNothingFound(response.records.length <= 0)
            useTemplatesStore.setState({
                nextPage: response.offset,
            })
        }
    }, [searchParamsRaw, appendTemplates])

    // This loads the initial batch of templates
    useEffect(() => {
        if (!Object.keys(searchParams.current.taxonomies).length) {
            return
        }
        setImagesLoaded([])
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
                setImagesLoaded([])
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
            {templates.map((template, id) => {
                return <li key={template.id} className="flex flex-col justify-between group overflow-hidden max-w-lg">
                    {/* Note: This li doesn't have tabindex nor keyboard event on purpose. a11y tabs to the button only */}
                    <div
                        className="flex justify-items-center flex-grow h-80 border-gray-200 bg-white border border-b-0 group-hover:border-wp-theme-500 transition duration-150 cursor-pointer"
                        onClick={() => setActiveTemplate(template)}>
                        <img
                            role="button"
                            className="max-w-full block m-auto object-cover"
                            onLoad={() => setImagesLoaded([...imagesLoaded, id])}
                            src={template?.fields?.screenshot[0]?.thumbnails?.large?.url ?? template?.fields?.screenshot[0]?.url}/>
                    </div>
                    <span
                        role="img"
                        aria-hidden="true"
                        className="h-px w-full bg-gray-200 border group-hover:bg-transparent border-t-0 border-b-0 border-gray-200 group-hover:border-wp-theme-500 transition duration-150"></span>
                    <div
                        className="bg-transparent text-left bg-white flex items-center justify-between p-4 border border-t-0 border-transparent group-hover:border-wp-theme-500 transition duration-150 cursor-pointer"
                        role="button"
                        onClick={() => setActiveTemplate(template)}>
                        <div>
                            <h4 className="m-0 font-bold">{template.fields.display_title}</h4>
                            <p className="m-0">{template?.fields?.tax_categories?.filter(c => c.toLowerCase() !== 'default').join(', ')}</p>
                        </div>
                        <Button
                            isSecondary
                            tabIndex={Object.keys(activeTemplate).length
                                ? '-1'
                                : '0'}
                            className="sm:opacity-0 group-hover:opacity-100 transition duration-150 focus:opacity-100"
                            onClick={(e) => {e.stopPropagation();setActiveTemplate(template)}}>
                            {__('View', 'extendify-sdk')}
                        </Button>
                    </div>
                </li>
            })}
        </ul>
        {
            (useTemplatesStore.getState().nextPage &&
            !!imagesLoaded.length &&
            (imagesLoaded.length === templates.length)) &&
            <>
                <div
                    className="-translate-y-full flex flex-col h-80 items-end justify-end my-2 relative transform z-0 text"
                    ref={loadMoreRef}
                    style={{
                        zIndex: -1,
                    }}>
                </div>
                <div className="my-4">
                    <Spinner/>
                </div>
            </>
        }
    </>
}
