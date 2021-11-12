import { ImportButton } from '../../components/ImportButton'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { useUserStore } from '../../state/User'
import { ExternalLink } from '@wordpress/components'
import {
    useEffect, useState, useCallback,
} from '@wordpress/element'
import { Templates as TemplatesApi } from '../../api/Templates'
import TaxonomyList from '../../components/TaxonomyList'
import { useIsMounted } from '../../hooks/helpers'
import { useTemplatesStore } from '../../state/Templates'

const relatedMap = new Map()

export default function Single({ template }) {
    const {
        tax_categories: categories,
        required_plugins: requiredPlugins,
        tax_style: styles,
        tax_pattern_types: types,
    } = template.fields
    const apiKey = useUserStore(state => state.apiKey)
    const [related, setRelated] = useState([])
    const [alternatives, setAlternatives] = useState([])
    const isMounted = useIsMounted()
    const setActiveTemplate = useTemplatesStore(state => state.setActive)

    const changeTemplate = (template) => {
        setRelated([])
        setAlternatives([])
        requestAnimationFrame(() => setActiveTemplate(template))
    }

    const fetchRelated = useCallback(async (queryType, wantedType) => {
        const key = `${template.id}|${queryType}|${wantedType}`
        if (relatedMap.has(key)) {
            return relatedMap.get(key)
        }
        const results = await TemplatesApi.related(
            template, queryType, wantedType,
        )
        relatedMap.set(key, results)
        return results
    }, [template])

    useEffect(() => { TemplatesApi.single(template) }, [template])
    useEffect(() => {
        fetchRelated('related', 'pattern').then((results) => {
            isMounted.current && setRelated(results)
            // fetchRelated('alternatives', template.fields.type).then((results) => {
            //     isMounted.current && setAlternatives(results)
            // })
        })
    }, [template, fetchRelated, isMounted])

    return <div className="flex flex-col min-h-screen bg-white sm:min-h-0 items-start overflow-y-auto h-full sm:pr-8 lg:pl-px lg:-ml-px">
        <div className="lg:sticky top-0 bg-white flex flex-col lg:flex-row items-start justify-start lg:items-center lg:justify-between w-full max-w-screen-xl lg:border-b border-gray-300">
            <div className="text-left m-0 h-full px-6 sm:p-0">
                <h1 className="leading-tight text-left mb-2.5 mt-0 sm:text-3xl font-normal">{template.fields.display_title}</h1>
                <ExternalLink href={template.fields.url}>
                    {__('Demo', 'extendify-sdk')}
                </ExternalLink>
            </div>
            <div className={classNames({
                'inline-flex sm:top-auto right-0 m-6 sm:m-0 sm:my-6 space-x-3': true,
                'top-16 mt-5': !apiKey.length,
                'top-0': apiKey.length > 0,
            })}>
                <ImportButton template={template} />
            </div>
        </div>
        <div className="max-w-screen-xl sm:w-full sm:m-0 sm:mb-8 m-6 border lg:border-t-0 border-gray-300 m-46">
            <img
                className="max-w-full w-full block"
                src={template?.fields?.screenshot[0]?.thumbnails?.full?.url ?? template?.fields?.screenshot[0]?.url}/>
        </div>

        <div className="divide-y p-6 sm:p-0 mb-16">
            {related.length > 0 && <section className="mb-4">
                <h4 className="text-lg m-0 mb-4 text-left font-semibold">{__('Related', 'extendify-sdk')}</h4>
                <div className="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
                    {related.map((template) => {
                        return <button key={template.id}
                            type="button"
                            className="min-h-60 border border-transparent hover:border-wp-theme-500 transition duration-150 p-0 m-0 cursor-pointer"
                            onClick={() => changeTemplate(template)}>
                            <img
                                className="max-w-full block p-0 m-0 object-cover"
                                src={template?.fields?.screenshot[0]?.thumbnails?.large?.url ?? template?.fields?.screenshot[0]?.url}/>
                        </button>
                    })}
                </div>
            </section>}
            {alternatives.length > 0 && <section className="mb-4 pt-6">
                <h4 className="text-lg m-0 mb-4 text-left font-semibold">{__('Alternatives', 'extendify-sdk')}</h4>
                <div className="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
                    {alternatives.map((template) => {
                        return <button key={template.id}
                            type="button"
                            className="min-h-60 border border-transparent hover:border-wp-theme-500 transition duration-150 p-0 m-0 cursor-pointer"
                            onClick={() => changeTemplate(template)}>
                            <img
                                className="max-w-full block p-0 m-0 object-cover"
                                src={template?.fields?.screenshot[0]?.thumbnails?.large?.url ?? template?.fields?.screenshot[0]?.url}/>
                        </button>
                    })}
                </div>
            </section>}
        </div>

        {/* Hides on desktop and is repeated in the single sidebar too */}
        <div className="text-xs text-left p-6 w-full block sm:hidden divide-y">
            <TaxonomyList
                categories={categories}
                types={types}
                requiredPlugins={requiredPlugins}
                styles={styles}/>
        </div>
    </div>
}
