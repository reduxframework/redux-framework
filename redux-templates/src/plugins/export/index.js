import ExportManager from './export-block-menu-item';
if (wp.plugins) {
	const { registerPlugin } = wp.plugins;

	registerPlugin( 'redux-templates-export', {
		render: ExportManager,
	} );
}
