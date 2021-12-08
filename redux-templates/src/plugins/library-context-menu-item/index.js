/**
 * WordPress dependencies
 */
import { withDispatch, withSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { compose, ifCondition } from '@wordpress/compose';
import {ModalManager} from '../../modal-manager';
import LibraryModal from '../../modal-library';
import { ReduxTemplatesIconColor } from '~redux-templates/icons';

const { Fragment } = wp.element;

function OpenLibraryContentMenuItem( ) {
	if (!wp.plugins) return null;

	const { PluginMoreMenuItem } = wp.editPost;

	return (
		<Fragment>
			<PluginMoreMenuItem
				icon={ ReduxTemplatesIconColor() }
				role="menuitemcheckbox"
				onClick={ () => {
					ModalManager.open(<LibraryModal />);
				} }
			>
				{ __( 'Template Library', redux_templates.i18n ) }
			</PluginMoreMenuItem>
		</Fragment>
	);
}

const OpenLibraryContentMenu = compose(
	withSelect( ( select ) => ( {
	} ) ),
	withDispatch( ( dispatch ) => {
	} ),

)( OpenLibraryContentMenuItem );

if (wp.plugins) {
	const { registerPlugin } = wp.plugins;
	registerPlugin('redux-open-library-context', {
		render: OpenLibraryContentMenu,
	});
}
