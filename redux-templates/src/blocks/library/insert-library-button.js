/**
 * External dependencies
 */
import { ReduxTemplatesIcon, ReduxTemplatesIconColorize } from '~redux-templates/icons'

/**
 * WordPress dependencies
 */
import {Button, Tooltip} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import {ModalManager} from '../../modal-manager';
import LibraryModal from '../../modal-library';
import './style.scss'

const InsertLibraryButton = () => {
	return (
		<Tooltip text={__( 'Redux Templates Library', redux_templates.i18n )} position={'bottom'}>
			<Button data-tut="tour__library_button"
					onClick={ () => {
						ModalManager.open(<LibraryModal />);
					} }
					className="redux-templates-insert-library-button"
					label={ __( 'Open Library', redux_templates.i18n ) }
					icon={ <ReduxTemplatesIcon /> }
			>{ __( 'Templates', redux_templates.i18n ) }</Button>
		</Tooltip>
	)
}

export default InsertLibraryButton
