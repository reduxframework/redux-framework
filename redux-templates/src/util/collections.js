import { registerBlockCollection } from '@wordpress/blocks'

export const supportsBlockCollections = () => typeof registerBlockCollection !== 'undefined'
