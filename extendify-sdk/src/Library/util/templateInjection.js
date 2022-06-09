import { dispatch, select } from '@wordpress/data'

export function injectTemplateBlocks(blocks, templateRaw) {
    const { insertBlocks, replaceBlock } = dispatch('core/block-editor')
    const {
        getSelectedBlock,
        getBlockHierarchyRootClientId,
        getBlockIndex,
        getGlobalBlockCount,
    } = select('core/block-editor')

    const { clientId, name, attributes } = getSelectedBlock() || {}
    const rootClientId = clientId ? getBlockHierarchyRootClientId(clientId) : ''
    const insertPointIndex =
        (rootClientId ? getBlockIndex(rootClientId) : getGlobalBlockCount()) + 1

    const injectblock = () =>
        name === 'core/paragraph' && attributes?.content === ''
            ? replaceBlock(clientId, blocks)
            : insertBlocks(blocks, insertPointIndex)

    return injectblock().then(() =>
        window.dispatchEvent(
            new CustomEvent('extendify::template-inserted', {
                detail: {
                    template: templateRaw,
                },
                bubbles: true,
            }),
        ),
    )
}
