import { __ } from '@wordpress/i18n'
import { useGlobalStore } from '../../../state/GlobalState'
import { useTemplatesStore } from '../../../state/Templates'
import { useUserStore } from '../../../state/User'
import TaxonomyList from '../../../components/TaxonomyList'

export default function SidebarSingle({ template }) {
    const setActiveTemplate = useTemplatesStore(state => state.setActive)
    const {
        tax_categories: categories,
        required_plugins: requiredPlugins,
        tax_style: styles,
        tax_pattern_types: types,
    } = template.fields
    const apiKey = useUserStore(state => state.apiKey)

    const goBack = () => {
        useGlobalStore.setState({ currentPage: 'main' })
        setActiveTemplate({})
    }

    return <div className="flex flex-col items-start justify-start">
        {!apiKey.length && <div className="h-full flex sm:hidden w-full p-4 justify-between border items-center border-gray-300 bg-extendify-lightest">
            <a
                className="button-extendify-main"
                target="_blank"
                href={`https://extendify.com/pricing?utm_source=${window.extendifySdkData.source}&utm_medium=library&utm_campaign=sign_up&utm_content=main`}
                rel="noreferrer">
                {__('Sign up today to get unlimited access', 'extendify-sdk')}
            </a>
            <button
                className="components-button"
                onClick={() => useGlobalStore.setState({
                    currentPage: 'login',
                })}>
                {__('Log in', 'extendify-sdk')}
            </button>
        </div>}
        <div className="p-6 sm:p-0">
            <button
                type="button"
                className="cursor-pointer text-black bg-transparent font-medium flex items-center p-3 transform -translate-x-3 button-focus"
                onClick={goBack}>
                <svg className="fill-current" width="8" height="12" viewBox="0 0 8 12" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.70998 9.88047L2.82998 6.00047L6.70998 2.12047C7.09998 1.73047 7.09998 1.10047 6.70998 0.710469C6.31998 0.320469 5.68998 0.320469 5.29998 0.710469L0.70998 5.30047C0.31998 5.69047 0.31998 6.32047 0.70998 6.71047L5.29998 11.3005C5.68998 11.6905 6.31998 11.6905 6.70998 11.3005C7.08998 10.9105 7.09998 10.2705 6.70998 9.88047Z"/>
                </svg>
                <span className="ml-4">{__('Go back', 'extendify-sdk')}</span>
            </button>
        </div>
        {/* Hides on mobile and is repeated at the bottom of the single page too */}
        <div className="text-left pt-14 divide-y w-full hidden sm:block">
            <TaxonomyList
                categories={categories}
                types={types}
                requiredPlugins={requiredPlugins}
                styles={styles}/>
        </div>
    </div>
}
