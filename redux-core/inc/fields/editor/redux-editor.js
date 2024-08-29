/**
 * Redux Editor on change callback
 * Dependencies        : jquery
 * Feature added by    : Dovy Paukstys
 *                     : Kevin Provance (who helped)  :P
 * Date                : 07 June 2014
 */

/*global redux_change, tinymce, redux*/

(function ( $ ) {
	'use strict';

	redux.field_objects        = redux.field_objects || {};
	redux.field_objects.editor = redux.field_objects.editor || {};

	redux.field_objects.editor.init = function ( selector ) {
		let i;
		let len;

		selector = $.redux.getSelector( selector, 'editor' );

		$( selector ).each(
			function () {
				const el   = $( this );
				let parent = el;
				let id;
				let mce;
				let editorArea;

				if ( ! el.hasClass( 'redux-field-container' ) ) {
					parent = el.parents( '.redux-field-container:first' );
				}

				if ( parent.is( ':hidden' ) ) {
					return;
				}

				if ( parent.hasClass( 'redux-field-init' ) ) {
					parent.removeClass( 'redux-field-init' );
				} else {
					return;
				}

				// Editor in repeater needs to have content manually added to text area,
				// or the content won't save.
				editorArea = el.find( '.wp-editor-area' );
				if ( editorArea.hasClass( 'in-repeater' ) || editorArea.hasClass( 'in-tabbed' ) ) {
					id  = el.data( 'id' );
					mce = tinymce.editors[id];

					redux.field_objects.editor.onKeyup( el, mce );
				}
			}
		);

		setTimeout(
			function () {
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

	redux.field_objects.editor.onKeyup = function ( el, mce ) {
		mce.on(
			'keyup',
			function () {
				const id      = el.data( 'id' );
				const content = tinymce.get( id ).getContent();

				el.find( '.wp-editor-area' ).text( content );

			}
		);
	};

	redux.field_objects.editor.onChange = function ( i ) {
		tinymce.editors[i].on(
			'change',
			function () {
				if ( 0 !== $( '.wp-editor-area', window.parent.document ) ) {
					redux_change( $( '.wp-editor-area' ) );
				}
			}
		);
	};
})( jQuery );
