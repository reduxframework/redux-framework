import { dispatch } from '@wordpress/data'
import { get } from 'lodash'

import { createBlocksFromInnerBlocksTemplate } from './blocks'

export function injectTemplate(template) {
    if (!template) {
        throw Error('Template not found')
    }

    const { parse } = window.wp.blocks
    const createdBlocks = createBlocksFromInnerBlocksTemplate(parse(get(template, 'fields.code')))
    return injectTemplateBlocks(createdBlocks, template)
}

export function injectTemplateBlocks(blocks, templateRaw) {
    const { insertBlocks } = dispatch('core/block-editor')
    return insertBlocks(blocks).then(() => {
        window.dispatchEvent(new CustomEvent('extendify-sdk::template-inserted', {
            detail: {
                template: templateRaw,
            },
            bubbles: true,
        }))
    })

}
