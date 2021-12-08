/**
 * External dependencies.
 */
import { ReduxTemplatesIcon } from '~redux-templates/icons'
// import { ModalDesignLibrary } from '~stackable/components'
import {ModalManager} from '../../modal-manager';
import LibraryModal from '../../modal-library';

/**
 * WordPress dependencies.
 */
import {
	Button, Placeholder,
} from '@wordpress/components'
import { compose } from '@wordpress/compose'
import { createBlock, parse } from '@wordpress/blocks'
import { withDispatch } from '@wordpress/data'
import { useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { applyFilters } from '@wordpress/hooks'

const edit = ( { removeLibraryBlock, preview } ) => {
	if (preview) {
		alert('here i am');
	}

	return (
		<div className="redux-template-library-block">
			<Placeholder
				icon={ <ReduxTemplatesIcon /> }
				label={ __( 'Redux Template Library', redux_templates.i18n ) }
				instructions={ __( 'Open the Design Library and select a pre-designed block or layout.', redux_templates.i18n ) }
			>
				<Button
					isSecondary
					isLarge
					hasIcon
					className="redux-template-library-block__button"
					onClick={ () => {
						ModalManager.open(<LibraryModal />);
						removeLibraryBlock()
					} }
				>{ __( 'Open Design Library', redux_templates.i18n ) }</Button>
			</Placeholder>
		</div>
	)
}

export default compose( [
	withDispatch( ( dispatch, {
		clientId,
	} ) => {
		const { removeBlocks } = dispatch( 'core/block-editor' )
		return {
			removeLibraryBlock: serializedBlock => {
				removeBlocks( clientId );
			},
		}
	} ),
] )( edit )
