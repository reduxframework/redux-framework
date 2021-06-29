/**
 * Redux Editor on change callback
 * Dependencies        : jquery
 * Feature added by    : Dovy Paukstys
 *                     : Kevin Provance (who helped)  :P
 * Date                : 07 June 2014
 */

/*global redux_change, tinymce, redux*/

(function( $ ) {
	'use strict';

	redux.field_objects        = redux.field_objects || {};
	redux.field_objects.editor = redux.field_objects.editor || {};

	redux.field_objects.editor.init = function() {
		var i;
		var len;

		setTimeout(
			function() {
				if ( 'undefined' !== typeof ( tinymce ) ) {
					len = tinymce.editors.length;

					for ( i = 0; i < len; i += 1 ) {
						redux.field_objects.editor.onChange( i );
					}
				}
			},
			1000
		);
	};

	redux.field_objects.editor.onChange = function( i ) {
		tinymce.editors[i].on(
			'change',
			function( e ) {
				var el = jQuery( e.target.contentAreaContainer );
				if ( 0 !== el.parents( '.redux-container-editor:first' ).length ) {
					redux_change( $( '.wp-editor-area' ) );
				}
			}
		);
	};
})( jQuery );
