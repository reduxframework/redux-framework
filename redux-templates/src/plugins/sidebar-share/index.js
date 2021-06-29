import Sidebar from './sidebar'
import { ReduxTemplatesIcon } from '~redux-templates/icons';
if (wp.plugins) {
	const { registerPlugin } = wp.plugins;

	

	registerPlugin( 'redux-templates-share', {
		icon: ReduxTemplatesIcon,
		render: Sidebar,
	} );
}
