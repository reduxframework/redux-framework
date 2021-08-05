import { ImportButton } from './ImportButton'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { useUserStore } from '../state/User'
import { ExternalLink } from '@wordpress/components'
import { useEffect } from '@wordpress/element'
import { Templates as TemplatesApi } from '../api/Templates'
import TaxonomyList from '../components/TaxonomyList'

export default function Templates({ template }) {
    const {
        tax_categories: categories,
        required_plugins: requiredPlugins,
        tax_style: styles,
        tax_pattern_types: types,
    } = template.fields
    const apiKey = useUserStore(state => state.apiKey)

    useEffect(() => { TemplatesApi.single(template) }, [template])

    return <div className="flex flex-col min-h-screen bg-white sm:min-h-0 items-start overflow-y-auto h-full sm:pr-8 lg:pl-px lg:-ml-px">
        <div className="flex flex-col lg:flex-row items-start justify-start lg:items-center lg:justify-between w-full max-w-screen-xl">
            <div className="text-left m-0 h-full p-6 sm:p-0">
                <h1 className="leading-tight text-left mb-2.5 mt-0 sm:text-3xl font-normal">{template.fields.display_title}</h1>
                <ExternalLink href={template.fields.url}>
                    {__('Demo', 'extendify-sdk')}
                </ExternalLink>
            </div>
            <div className={classNames({
                'inline-flex absolute sm:static sm:top-auto right-0 m-6 sm:m-0 sm:my-6 space-x-3': true,
                'top-16 mt-5': !apiKey.length,
                'top-0': apiKey.length > 0,
            })}>
                <ImportButton template={template} />
            </div>
        </div>
        <div className="max-w-screen-xl sm:w-full sm:m-0 sm:mb-12 m-6 border border-gray-300 m-46">
            <img
                className="max-w-full w-full block"
                src={template?.fields?.screenshot[0]?.thumbnails?.full?.url ?? template?.fields?.screenshot[0]?.url}/>
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
