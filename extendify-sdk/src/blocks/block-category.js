/**
 * WordPress dependencies
 */
import { registerBlockCollection, setCategories } from '@wordpress/blocks'
import { Icon } from '@wordpress/components'
import { select } from '@wordpress/data'
import { brandBlockIcon } from '@extendify/components/icons'

/**
 * Register the 'Extendify' block category.
 *
 * Note: The category label is overridden via registerBlockCollection() below.
 */
const currentCategories = select('core/blocks').getCategories()
setCategories([
    {
        slug: 'extendify',
        title: 'Extendify',
        icon: null,
    },
    ...currentCategories,
])

/**
 * Function to register a block collection for our block(s).
 */
registerBlockCollection('extendify', {
    title: 'Extendify',
    icon: <Icon icon={brandBlockIcon} />,
})
