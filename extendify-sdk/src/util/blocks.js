
/**
 * Given an array of InnerBlocks templates or Block Objects,
 * returns an array of created Blocks from them.
 * It handles the case of having InnerBlocks as Blocks by
 * converting them to the proper format to continue recursively.
 *
 * @param {Array} innerBlocksOrTemplate Nested blocks or InnerBlocks templates.
 *
 * @return {Object[]} Array of Block objects.
 */
export function createBlocksFromInnerBlocksTemplate(innerBlocksOrTemplate = []) {
    const { createBlock } = window.wp.blocks

    // TODO: This should return the native implementation if available here

    return innerBlocksOrTemplate.map((innerBlock) => {
        const innerBlockTemplate = Array.isArray(innerBlock)
            ? innerBlock
            : [innerBlock.name, innerBlock.attributes, innerBlock.innerBlocks]
        const [name, attributes, innerBlocks = []] = innerBlockTemplate
        return createBlock(
            name, attributes, createBlocksFromInnerBlocksTemplate(innerBlocks),
        )
    })
}
