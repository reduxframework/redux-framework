/**
 * External dependencies
 */
import { isString } from 'lodash';

/**
 * Internal dependencies
 */
import { readTextFile } from './file';
const { dispatch, select } = wp.data;
const { editPost } = dispatch('core/editor');

/**
 * Import a reusable block from a JSON file.
 *
 * @param {File}     file File.
 * @return {Promise} Promise returning the imported reusable block.
 */
async function importReusableBlock( file ) {
    const fileContent = await readTextFile( file );
    let parsedContent;
    try {
        parsedContent = JSON.parse(JSON.parse(JSON.stringify(fileContent)));
    } catch ( e ) {
        throw new Error( 'Invalid JSON file' );
    }

    if ( parsedContent.__file === 'redux_template' ) {
		editPost( { 'template': 'redux-templates_full_width' } );
        return parsedContent.content;
    }

    if (
        parsedContent.__file !== 'wp_block' ||
        ! parsedContent.title ||
        ! parsedContent.content ||
        ! isString( parsedContent.title ) ||
        ! isString( parsedContent.content )
    ) {
	    if ( '' === select( 'core/editor' ).getEditedPostAttribute( 'template' ) ) {
		    editPost({'template': 'redux-templates_contained'});
	    }
        return importCoreBlocks( parsedContent );
    }

    const postType = await wp.apiFetch( { path: '/wp/v2/types/wp_block' } );
    const reusableBlock = await wp.apiFetch( {
        path: `/wp/v2/${ postType.rest_base }`,
        data: {
            title: parsedContent.title,
            content: parsedContent.content,
            status: 'publish',
        },
        method: 'POST',
    } );

    if ( reusableBlock.id ) {
        return '<!-- wp:block {"ref":' + reusableBlock.id + '} /-->';
    }
    throw new Error( 'Invalid Reusable Block JSON file contents' );
}

function importCoreBlocks( parsedContent ) {
    if (
        parsedContent.__file !== 'core_block' ||
        ! parsedContent.content ||
        ! isString( parsedContent.content )
    ) {
        throw new Error( 'Invalid JSON file' );
    }

    return parsedContent.content;
}

export default importReusableBlock;
