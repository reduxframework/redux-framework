import { dispatch } from '@wordpress/data'

export function injectTemplateBlocks(blocks, templateRaw) {
    const { insertBlocks } = dispatch('core/block-editor')
    return insertBlocks(blocks).then(() => {
        window.dispatchEvent(
            new CustomEvent('extendify-sdk::template-inserted', {
                detail: {
                    template: templateRaw,
                },
                bubbles: true,
            }),
        )
    })
}
