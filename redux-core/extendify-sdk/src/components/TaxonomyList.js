import { getPluginDescription } from '../util/general'
import { __ } from '@wordpress/i18n'

export default function TaxonomyList({ categories, styles, types, requiredPlugins }) {
    return <>
        {categories && <div className="w-full pb-4">
            <h3 className="text-sm m-0 mb-2">{__('Categories:', 'extendify-sdk')}</h3>
            <div>{categories.join(', ')}</div>
        </div>}
        {styles && <div className="w-full py-4">
            <h3 className="text-sm m-0 my-2">{__('Styles:', 'extendify-sdk')}</h3>
            <div>{styles.join(', ')}</div>
        </div>}
        {types && <div className="w-full py-4">
            <h3 className="text-sm m-0 my-2">{__('Types:', 'extendify-sdk')}</h3>
            <div>{types.join(', ')}</div>
        </div>}
        {/* // Hardcoded temporarily to not force EP install */}
        {/* {requiredPlugins && <div className="pt-4 w-full"> */}
        {requiredPlugins.filter((p) => p !== 'editorplus').length > 0 && <div className="pt-4 w-full">
            <h3 className="text-sm m-0 my-2">{__('Required Plugins:', 'extendify-sdk')}</h3>
            <div>
                {
                    // Hardcoded temporarily to not force EP install
                    // requiredPlugins.map(p => getPluginDescription(p)).join(', ')
                    requiredPlugins.filter((p) => p !== 'editorplus').map(p => getPluginDescription(p)).join(', ')
                }
            </div>
        </div>}
        <div className="py-4 mt-4">
            <a
                href={`https://extendify.com/what-happens-when-a-template-is-added?utm_source=${window.extendifySdkData.sdk_partner}&utm_medium=library&utm_campaign=sidebar`}
                rel="noreferrer"
                target="_blank">
                {__('What happens when a page layout is added?', 'extendify-sdk')}
            </a>
        </div>
    </>
}
