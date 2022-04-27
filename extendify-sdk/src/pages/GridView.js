import { Spinner, Button } from '@wordpress/components'
import {
    useEffect,
    useState,
    useCallback,
    useRef,
    memo,
} from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { cloneDeep } from 'lodash'
import { useInView } from 'react-intersection-observer'
import Masonry from 'react-masonry-css'
import { Templates as TemplatesApi } from '@extendify/api/Templates'
import { ImportTemplateBlock } from '@extendify/components/ImportTemplateBlock'
import { useIsMounted } from '@extendify/hooks/helpers'
import { useTestGroup } from '@extendify/hooks/useTestGroup'
import { useGlobalStore } from '@extendify/state/GlobalState'
import { useTaxonomyStore } from '@extendify/state/Taxonomies'
import { useTemplatesStore } from '@extendify/state/Templates'

export const GridView = memo(function GridView() {
    const isMounted = useIsMounted()
    const templates = useTemplatesStore((state) => state.templates)
    const [templatesCount, setTemplatesCount] = useState(0)
    const appendTemplates = useTemplatesStore((state) => state.appendTemplates)
    const [serverError, setServerError] = useState('')
    const retryOnce = useRef(false)
    const [nothingFound, setNothingFound] = useState(false)
    const [loading, setLoading] = useState(false)
    const [loadMoreRef, inView] = useInView()
    const searchParamsRaw = useTemplatesStore((state) => state.searchParams)
    const currentType = useGlobalStore((state) => state.currentType)
    const resetTemplates = useTemplatesStore((state) => state.resetTemplates)
    const open = useGlobalStore((state) => state.open)
    const taxonomies = useTaxonomyStore((state) => state.taxonomies)
    const updateType = useTemplatesStore((state) => state.updateType)
    const updateTaxonomies = useTemplatesStore(
        (state) => state.updateTaxonomies,
    )

    // Store the next page in case we have pagination
    const nextPage = useRef(useTemplatesStore.getState().nextPage)
    const searchParams = useRef(useTemplatesStore.getState().searchParams)
    const taxonomyType =
        searchParams.current.type === 'pattern' ? 'patternType' : 'layoutType'
    const currentTax = searchParams.current.taxonomies[taxonomyType]
    const defaultOrAlt = useTestGroup('default-or-alt-sitetype', ['A', 'B'])

    // Subscribing to the store will keep these values updates synchronously
    useEffect(() => {
        return useTemplatesStore.subscribe(
            (state) => state.nextPage,
            (n) => (nextPage.current = n),
        )
    }, [])
    useEffect(() => {
        return useTemplatesStore.subscribe(
            (state) => state.searchParams,
            (s) => (searchParams.current = s),
        )
    }, [])

    // Fetch the templates then add them to the current state
    const fetchTemplates = useCallback(() => {
        if (!defaultOrAlt) {
            return
        }
        setServerError('')
        setNothingFound(false)
        const defaultError = __(
            'Unknown error occurred. Check browser console or contact support.',
            'extendify',
        )
        const args = { offset: nextPage.current }
        // AB test the default or defaultAlt site type
        const defaultSiteType =
            defaultOrAlt === 'A' ? { slug: 'default' } : { slug: 'defaultAlt' }
        const siteType = searchParams.current.taxonomies?.siteType?.slug?.length
            ? searchParams.current.taxonomies.siteType
            : defaultSiteType
        // End AB test - otherwise use { slug: 'default' } when empty
        const params = cloneDeep(searchParams.current)
        params.taxonomies.siteType = siteType
        TemplatesApi.get(params, args)
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
                    response?.records?.length
                ) {
                    useTemplatesStore.setState({
                        nextPage: response?.offset ?? '',
                    })
                    // Essentially used to trigger the inview observer
                    appendTemplates(response.records)
                    setTemplatesCount((c) => response.records.length + c)
                    setLoading(false)
                }
            })
            .catch((error) => {
                if (!isMounted.current) return
                console.error(error)
                setServerError(defaultError)
            })
    }, [appendTemplates, isMounted, searchParamsRaw, defaultOrAlt])

    useEffect(() => {
        if (templates?.length === 0) {
            setLoading(true)
            return
        }
    }, [templates?.length, searchParamsRaw])

    useEffect(() => {
        // If there's a server error, retry the request
        // This is temporary until we upgrade the backend and add
        // a tool like react query to handle this automatically
        if (!retryOnce.current && serverError.length) {
            retryOnce.current = true
            fetchTemplates()
        }
    }, [serverError, fetchTemplates])

    useEffect(() => {
        // This will check the URL for a pattern type and set that and remove it
        // TODO: possibly refactor this if we expand it to support layouts
        if (!open || !taxonomies?.patternType?.length) return
        const search = new URLSearchParams(window.location.search)
        if (!search.has('ext-patternType')) return
        const term = search.get('ext-patternType')
        // Delete it right away
        search.delete('ext-patternType')
        window.history.replaceState(
            null,
            null,
            window.location.pathname + '?' + search.toString(),
        )
        // Search the slug in patternTypes
        const tax = taxonomies.patternType.find((t) => t.slug === term)
        if (!tax) return
        updateTaxonomies({ patternType: tax })
        updateType('pattern')
    }, [open, taxonomies, updateType, updateTaxonomies])

    // This is the main driver for loading templates
    // This loads the initial batch of templates. But if we don't yet have taxonomies.
    // There's also an option to skip loading on first mount
    useEffect(() => {
        if (!Object.keys(searchParams.current?.taxonomies)?.length) {
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
        return () => resetTemplates()
    }, [fetchTemplates, searchParams, resetTemplates])

    // Fetches when the load more is in view
    useEffect(() => {
        nextPage.current && inView && fetchTemplates()
    }, [inView, fetchTemplates, templatesCount])

    if (serverError.length && retryOnce.current) {
        return (
            <div className="text-left">
                <h2 className="text-left">{__('Server error', 'extendify')}</h2>
                <code
                    className="mb-4 block max-w-xl p-4"
                    style={{ minHeight: '10rem' }}>
                    {serverError}
                </code>
                <Button
                    isTertiary
                    onClick={() => {
                        retryOnce.current = false
                        fetchTemplates()
                    }}>
                    {__('Press here to reload')}
                </Button>
            </div>
        )
    }

    if (nothingFound) {
        return (
            <div className="-mt-2 flex h-full w-full items-center justify-center sm:mt-0">
                <h2 className="text-sm font-normal text-extendify-gray">
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

    return (
        <>
            {loading && (
                <div className="-mt-2 flex h-full w-full items-center justify-center sm:mt-0">
                    <Spinner />
                </div>
            )}

            <Grid type={currentType} templates={templates}>
                {templates.map((template) => {
                    return (
                        <ImportTemplateBlock
                            maxHeight={
                                currentType === 'template' ? 520 : 'none'
                            }
                            key={template.id}
                            template={template}
                        />
                    )
                })}
            </Grid>

            {nextPage.current && (
                <>
                    <div className="mt-8">
                        <Spinner />
                    </div>
                    <div
                        className="relative flex flex-col items-end justify-end -top-1/4 h-4"
                        ref={loadMoreRef}
                        style={{ zIndex: -1 }}
                    />
                </>
            )}
        </>
    )
})

const Grid = ({ type, children }) => {
    const sharedClasses = 'relative min-h-screen z-10 pb-40 pt-0.5'
    switch (type) {
        case 'template':
            return (
                <div
                    className={`grid gap-6 md:gap-8 lg:grid-cols-2 ${sharedClasses}`}>
                    {children}
                </div>
            )
    }
    const breakpointColumnsObj = {
        default: 3,
        1600: 2,
        860: 1,
        599: 2,
        400: 1,
    }
    return (
        <Masonry
            breakpointCols={breakpointColumnsObj}
            className={`-ml-6 flex w-auto px-0.5 md:-ml-8 ${sharedClasses}`}
            columnClassName="pl-6 md:pl-8 bg-clip-padding space-y-6 md:space-y-8">
            {children}
        </Masonry>
    )
}
