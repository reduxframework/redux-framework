import { useTemplatesStore } from '../state/Templates'

export default function TaxonomyBreadcrumbs() {
    const searchParams = useTemplatesStore(state => state.searchParams)
    const formatTitle = (title) => title.replace('tax_', '').replace(/_/g , ' ').replace(/\b\w/g, l => l.toUpperCase())
    return <div className="hidden sm:flex items-start flex-col lg:flex-row -mt-2 lg:-mx-2 mb-4 lg:divide-x-2 lg:leading-none">
        {Object.entries(searchParams.taxonomies).map((tax) => {
            // Special exception for page templates
            if (searchParams.type === 'template' && tax[0] === 'tax_pattern_types') {
                return ''
            }
            // Special exception for plugins (like metaslider) that won't have full page templates
            if (searchParams.type === 'template' && tax[0] === 'tax_features') {
                return ''
            }
            // Special exception for page types
            if (searchParams.type === 'pattern' && tax[0] === 'tax_page_types') {
                return ''
            }
            return <div key={tax[0]} className="lg:px-2 text-left">
                <span className="font-bold">{formatTitle(tax[0])}</span>: <span>{tax[1]
                    ? tax[1]
                    : 'All'}</span>
            </div>
        })}</div>
}
