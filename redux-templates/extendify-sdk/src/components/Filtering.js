import { useTemplatesStore } from '../state/Templates'
import { __experimentalSearchForm as SearchForm } from '@wordpress/block-editor'
// import { SelectControl } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { debounce } from 'lodash'
import {
    useEffect, useState, useCallback,
} from '@wordpress/element'
import { Taxonomies as TaxonomiesApi } from '../api/Taxonomies'
import { Panel } from '@wordpress/components'
import TaxonomySection from './TaxonomySection'

export default function Filtering() {
    const updateSearchParams = useTemplatesStore(state => state.updateSearchParams)
    const setupDefaultTaxonomies = useTemplatesStore(state => state.setupDefaultTaxonomies)
    const searchParams = useTemplatesStore(state => state.searchParams)
    const searchInputUpdate = debounce((value) => updateSearchParams({
        taxonomies: {},
        search: value,
    }), 500)
    const [searchValue, setSearchValue] = useState(searchParams?.search ?? '')
    const [taxonomies, setTaxonomies] = useState({})
    const fetchTaxonomies = useCallback(async () => {
        let tax = await TaxonomiesApi.get()
        // Only allow items that have the 'tax_' prefix
        tax = Object.keys(tax)
            .filter((t) => t.startsWith('tax_'))
            .reduce((taxFiltered, key) => {
                taxFiltered[key] = tax[key]
                return taxFiltered
            }, {})
        setupDefaultTaxonomies(tax)
        setTaxonomies(tax)
    }, [setupDefaultTaxonomies])

    useEffect(() => {
        fetchTaxonomies()
    }, [fetchTaxonomies])

    return <>
        <div className="pt-1 -mt-1 mb-1 bg-white">
            <SearchForm
                placeholder={__('What are you looking for?', 'extendify-sdk')}
                onChange={(value) => {
                    useTemplatesStore.setState({
                        nextPage: '',
                    })
                    setSearchValue(value)
                    searchInputUpdate(value)
                }}
                value={searchValue}
                className="sm:ml-px sm:mr-1 sm:mb-6 px-6 sm:p-0 sm:px-0"
                autoComplete="off" />
        </div>
        <div className="flex-grow hidden overflow-y-auto pb-32 pr-2 pt-px sm:block">
            <Panel>
                {Object.entries(taxonomies).map((taxonomy, index) => {
                    return <TaxonomySection
                        key={index}
                        open={false}
                        taxonomy={taxonomy} />
                })}
            </Panel>
        </div>
    </>
}
