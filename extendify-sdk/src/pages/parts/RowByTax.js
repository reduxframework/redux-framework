import { useState, useEffect } from '@wordpress/element'
import { Templates as TemplatesApi } from '../../api/Templates'
import { useTemplatesStore } from '../../state/Templates'
import TemplateButton, { TemplateButtonSkeleton } from '../../components/TemplateButton'
import { useIsMounted } from '../../hooks/helpers'
import { useGlobalStore } from '../../state/GlobalState'
import { __ } from '@wordpress/i18n'
import { useTaxonomyStore } from '../../state/Taxonomies'

const apiResponses = new Map()

export default function RowByTax({ searchParams, title, tax }) {
    const updateTaxonomies = useTemplatesStore(state => state.updateTaxonomies)
    const toggleOpenedTaxonomy = useTaxonomyStore(state => state.toggleOpenedTaxonomy)
    const [templates, setTemplates] = useState([])
    const [howManyToFetch, setHowManyToFetch] = useState()
    const setActiveTemplate = useTemplatesStore(state => state.setActive)
    const isModalOpen = useGlobalStore(state => state.open)
    const isMounted = useIsMounted()

    useEffect(() => {
        setHowManyToFetch(window.innerWidth < 1600 ? 3 : 4)
    }, [])

    useEffect(() => {
        if (!isMounted.current || !howManyToFetch || !isModalOpen) {
            return
        }
        const key = JSON.stringify(Object.assign(searchParams, { pageSize: howManyToFetch, force: true }))
        if (apiResponses.has(key)) {
            setTemplates(apiResponses.get(key))
            return
        }
        TemplatesApi.get(searchParams, { pageSize: howManyToFetch, force: true }).then((response) => {
            if (response?.records?.length && isMounted.current) {
                apiResponses.set(key, response.records)
                setTemplates(response.records)
            }
        })
    }, [searchParams, isMounted, howManyToFetch, isModalOpen])

    return <section>
        <div className="flex justify-between">
            <h2 className="text-2xl mb-2 text-extendify-main uppercase m-0 text-left font-bold">
                {title}
            </h2>
            <button
                onClick={() => {
                    updateTaxonomies({ [tax]: title })
                    toggleOpenedTaxonomy('tax_pattern_types', true)
                }}
                type="button"
                className="components-button">{ __('View all', 'extendify-sdk') }</button>
        </div>
        <ul className="flex-grow gap-6 grid xl:grid-cols-2 2xl:grid-cols-3 3xl:grid-cols-4 pb-16 m-0">
            {/* TODO: we may want to keep intermediary state to have a better loading experience */}
            {templates.length === 0 && Array.from({ length: howManyToFetch }, (_, i) => <TemplateButtonSkeleton key={i}/>)}
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
    </section>
    // return <div>
}
