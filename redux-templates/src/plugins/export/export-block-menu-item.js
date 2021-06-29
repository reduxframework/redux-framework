/**
 * External dependencies
 */
import { kebabCase } from 'lodash';
/**
 * Internal dependencies
 */

import exportReusableBlock from './reusable';
import { download } from './file';
import { colorizeIcon } from '~redux-templates/icons'

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { withSelect, select } = wp.data;
const { compose } = wp.compose;
const { Fragment } = wp.element;
const { withSpokenMessages } = wp.components;
const { serialize } = wp.blocks;
import { Dashicon } from '@wordpress/components';

/**
 * Render plugin
 */
function ExportManager(props) {
	const { selectedBlockCount, selectedBlock, selectedBlocks } = props;

	if (!wp.editPost) return null;

	const { PluginBlockSettingsMenuItem } = wp.editPost;
	const saveAsJSON = () => {
		if ( selectedBlockCount < 1 ) {
			return;
		}

		let blocks;
		let title = 'redux_templates/export';

		if ( selectedBlockCount === 1 ) {
			//export as reusable when reusable is selected
			if ( selectedBlock.name === 'core/block' ) {
				exportReusableBlock( selectedBlock.attributes.ref );
				return;
			}

			blocks = serialize( selectedBlock );
			title = selectedBlock.name;
		}

		if ( selectedBlockCount > 1 ) {
			blocks = serialize( selectedBlocks );
		}

		//do export magic

		const fileContent = JSON.stringify( {
			__file: 'core_block',
			content: blocks,
		}, null, 2 );

		const fileName = 'block-template-' + kebabCase( title ) + '.json';
		download( fileName, fileContent, 'application/json' );
	}

	return (
		<Fragment>
			<PluginBlockSettingsMenuItem
				icon={ colorizeIcon( <Dashicon icon="migrate" /> ) }
				label={ __( 'Export Block', redux_templates.i18n ) }
				onClick={ saveAsJSON }
			>

			</PluginBlockSettingsMenuItem>
		</Fragment>
	);
}

export default compose( [
	withSelect( () => {
		const { getSelectedBlockCount, getSelectedBlock, getMultiSelectedBlocks } = select( 'core/block-editor' );
		const { getBlock } = select( 'core/block-editor' );

		return {
			selectedBlockCount: getSelectedBlockCount(),
			selectedBlock: getSelectedBlock(),
			selectedBlocks: getMultiSelectedBlocks(),
			getBlock,
		};
	} ),
	withSpokenMessages,
] )( ExportManager );
