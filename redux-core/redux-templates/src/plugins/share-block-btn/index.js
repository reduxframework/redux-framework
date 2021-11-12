import { withSelect } from '@wordpress/data'
import ShareBlockButton from './buttons'
import { ReduxTemplatesIcon } from '~redux-templates/icons';

if (wp.plugins) {
    const { registerPlugin } = wp.plugins;
    const Buttons = withSelect( select => {
        const { getSelectedBlockClientIds } = select( 'core/block-editor' )

        // Only supported by WP >= 5.3.
        if ( ! getSelectedBlockClientIds ) {
            return {}
        }

        return {
            clientIds: getSelectedBlockClientIds(),
        }
    } )( ShareBlockButton );
	// TODO - Finish this off and show to users.
    // registerPlugin( 'redux-templates-share-block-btn', {
    //     icon: ReduxTemplatesIcon,
    //     render: Buttons,
    // } );
}
