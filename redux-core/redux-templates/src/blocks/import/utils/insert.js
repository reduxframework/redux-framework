/**
 * WordPress dependencies
 */
const { select, dispatch } = wp.data;
const { parse, createBlock } = wp.blocks;

export default function insertImportedBlocks( clientId, blocks, onClose ) {
    blocks = parse( blocks );
    const toSelect = [];
    const blockIndex = select( 'core/block-editor' ).getBlockInsertionPoint();
    if ( blocks.length > 0 ) {
        for ( const block in blocks ) {
            const created = createBlock( blocks[ block ].name, blocks[ block ].attributes, blocks[ block ].innerBlocks );
            dispatch( 'core/block-editor' ).insertBlocks( created, parseInt( blockIndex.index ) + parseInt( block ) );

            if ( typeof created !== 'undefined' ) {
                toSelect.push( created.clientId );
            }
        }

        //remove insertion point if empty
        dispatch( 'core/block-editor' ).removeBlock( clientId );

        //select inserted blocks
        if ( toSelect.length > 0 ) {
            dispatch( 'core/block-editor' ).multiSelect( toSelect[ 0 ], toSelect.reverse()[ 0 ] );
        }
    }

    onClose();
}
