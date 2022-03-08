/**
 * WordPress dependencies
 */
import { registerBlockCollection } from '@wordpress/blocks'
import { Icon } from '@wordpress/components'
import { brandBlockIcon } from '@extendify/components/icons'

/**
 * Function to register a block collection for our block(s).
 */
registerBlockCollection('extendify', {
    title: 'Extendify',
    icon: <Icon icon={brandBlockIcon} />,
})
