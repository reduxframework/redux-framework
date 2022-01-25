import Masonry from 'react-masonry-css'
import {
    useEffect,
    useState,
    useCallback,
    useRef,
    memo,
} from '@wordpress/element'
import { Spinner, Button } from '@wordpress/components'
import { __, sprintf } from '@wordpress/i18n'
import { useTemplatesStore } from '../state/Templates'
import { Templates as TemplatesApi } from '../api/Templates'
import { useInView } from 'react-intersection-observer'
import { useIsMounted } from '../hooks/helpers'
import { ImportTemplateBlock } from '../components/ImportTemplateBlock'

export const GridView = memo(() => {
    const isMounted = useIsMounted()
    const templates = useTemplatesStore((state) => state.templates)
    const appendTemplates = useTemplatesStore((state) => state.appendTemplates)
    const [serverError, setServerError] = useState('')
    const [nothingFound, setNothingFound] = useState(false)
    const [loading, setLoading] = useState(false)
    const [loadMoreRef, inView] = useInView()
    const searchParamsRaw = useTemplatesStore((state) => state.searchParams)
    const resetTemplates = useTemplatesStore((state) => state.resetTemplates)

    // Store the next page in case we have pagination
    const nextPage = useRef(useTemplatesStore.getState().nextPage)
    const searchParams = useRef(useTemplatesStore.getState().searchParams)
    const taxonomyType =
        searchParams.current.type === 'pattern' ? 'patternType' : 'layoutType'
    const currentTax = searchParams.current.taxonomies[taxonomyType]

    // Subscribing to the store will keep these values updates synchronously
    useEffect(() => {
        return useTemplatesStore.subscribe(
            (n) => (nextPage.current = n),
            (state) => state.nextPage,
        )
    }, [])
    useEffect(() => {
        return useTemplatesStore.subscribe(
            (s) => (searchParams.current = s),
            (state) => state.searchParams,
        )
    }, [])

    // Fetch the templates then add them to the current state
    const fetchTemplates = useCallback(() => {
        setServerError('')
        setNothingFound(false)
        const defaultError = __(
            'Unknown error occured. Check browser console or contact support.',
            'extendify',
        )
        const args = { offset: nextPage.current }
        TemplatesApi.get(searchParams.current, args)
            .then((response) => {
                if (!isMounted.current) return
                if (response?.error?.length) {
                    setServerError(response?.error)
                    return
                }
                if (response?.records?.length <= 0) {
                    setNothingFound(true)
                    return
                }
                if (
                    searchParamsRaw === searchParams.current &&
                    response?.records.length
                ) {
                    useTemplatesStore.setState({
                        nextPage: response?.offset ?? '',
                    })
                    appendTemplates(response.records)
                    setLoading(false)
                }
            })
            .catch((error) => {
                if (!isMounted.current) return
                console.error(error)
                setServerError(defaultError)
            })
    }, [appendTemplates, isMounted, searchParamsRaw])

    useEffect(() => {
        if (templates.length === 0) {
            setLoading(true)
            return
        }
    }, [templates.length, searchParamsRaw])

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
        fetchTemplates()
    }, [fetchTemplates, searchParams])

    // Fetches when the load more is in view
    useEffect(() => {
        nextPage.current && inView && fetchTemplates()
    }, [inView, fetchTemplates, templates])

    if (serverError.length) {
        return (
            <div className="text-left">
                <h2 className="text-left">{__('Server error', 'extendify')}</h2>
                <code
                    className="block max-w-xl p-4 mb-4"
                    style={{ minHeight: '10rem' }}>
                    {serverError}
                </code>
                <Button
                    isTertiary
                    onClick={() => resetTemplates() && fetchTemplates()}>
                    {__('Press here to reload')}
                </Button>
            </div>
        )
    }

    if (nothingFound) {
        return (
            <div className="flex h-full items-center justify-center w-full -mt-2 sm:mt-0">
                <h2 className="text-sm text-extendify-gray font-normal">
                    {sprintf(
                        searchParams.current.type === 'template'
                            ? __(
                                  'We couldn\'t find any layouts in the "%s" category.',
                                  'extendify',
                              )
                            : __(
                                  'We couldn\'t find any patterns in the "%s" category.',
                                  'extendify',
                              ),
                        currentTax?.title ?? currentTax.slug,
                    )}
                </h2>
            </div>
        )
    }

    const breakpointColumnsObj = {
        default: 2,
        1320: 2,
        860: 1,
        599: 2,
        400: 1,
    }

    return (
        <>
            {loading && (
                <div className="flex h-full items-center justify-center w-full -mt-2 sm:mt-0">
                    <Spinner />
                </div>
            )}
            <Masonry
                breakpointCols={breakpointColumnsObj}
                className="flex -ml-6 md:-ml-8 w-auto pb-40 pt-0.5 px-0.5 relative z-10"
                columnClassName="pl-6 md:pl-8 bg-clip-padding min-h-screen">
                {templates.map((template) => {
                    return (
                        <ImportTemplateBlock
                            key={template.id}
                            template={template}
                        />
                    )
                })}
            </Masonry>

            {nextPage.current && (
                <>
                    <div className="my-20">
                        <Spinner />
                    </div>
                    {/* This is a large div that, when in view, will trigger more patterns to load */}
                    <div
                        className="-translate-y-full flex flex-col items-end justify-end relative transform"
                        ref={loadMoreRef}
                        style={{
                            zIndex: -1,
                            marginBottom: '-200vh',
                            height: '200vh',
                        }}
                    />
                </>
            )}
        </>
    )
})
