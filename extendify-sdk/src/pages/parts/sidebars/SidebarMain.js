import { useTemplatesStore } from '../../../state/Templates'
// import { SelectControl } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { debounce } from 'lodash'
import { useState } from '@wordpress/element'
import { Panel } from '@wordpress/components'
import TaxonomySection from '../../../components/TaxonomySection'
import { useTaxonomyStore } from '../../../state/Taxonomies'

export default function SidebarMain() {
    const updateSearchParams = useTemplatesStore(state => state.updateSearchParams)
    const taxonomies = useTaxonomyStore(state => state.taxonomies)
    const searchParams = useTemplatesStore(state => state.searchParams)
    const searchInputUpdate = debounce((value) => updateSearchParams({
        taxonomies: {},
        search: value,
    }), 500)
    const [searchValue, setSearchValue] = useState(searchParams?.search ?? '')

    return <>
        <div className="mt-px bg-white mb-6 mx-6 pt-6 lg:mx-0 lg:pt-0">
            <label
                className="sr-only"
                htmlFor="extendify-search-input">{__('What are you looking for?', 'extendify-sdk')}</label>
            <input
                id="extendify-search-input"
                type="search"
                placeholder={__('What are you looking for?', 'extendify-sdk')}
                onChange={(event) => {
                    useTemplatesStore.setState({
                        nextPage: '',
                    })
                    setSearchValue(event.target.value)
                    searchInputUpdate(event.target.value)
                }}
                value={searchValue}
                className="button-focus bg-gray-100 focus:bg-white border-0 m-0 p-3.5 pb-3 rounded-none text-sm w-full"
                autoComplete="off" />
            <svg className="absolute top-3 right-6 hidden lg:block pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" role="img" aria-hidden="true" focusable="false"><path d="M13.5 6C10.5 6 8 8.5 8 11.5c0 1.1.3 2.1.9 3l-3.4 3 1 1.1 3.4-2.9c1 .9 2.2 1.4 3.6 1.4 3 0 5.5-2.5 5.5-5.5C19 8.5 16.5 6 13.5 6zm0 9.5c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4z"></path></svg>
        </div>
        <div className="mt-px flex-grow hidden overflow-y-auto pb-32 pr-2 pt-px sm:block">
            <Panel>
                {Object.entries(taxonomies).map((taxonomy) => {
                    return <TaxonomySection
                        key={taxonomy[0]}
                        taxonomy={taxonomy} />
                })}
            </Panel>
        </div>
    </>
}
