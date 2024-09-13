/* global jQuery, redux, redux_change, ace */

( function ( $ ) {
	'use strict';

	redux.field_objects            = redux.field_objects || {};
	redux.field_objects.ace_editor = redux.field_objects.ace_editor || {};

	redux.field_objects.ace_editor.init = function ( selector ) {
		selector = $.redux.getSelector( selector, 'ace_editor' );

		$( selector ).each(
			function () {
				const el   = $( this );
				let parent = el;

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

				el.find( '.ace-editor' ).each(
					function ( index, element ) {
						const area      = element;
						const params    = JSON.parse( $( this ).parent().find( '.localize_data' ).val() );
						const editor    = $( element ).attr( 'data-editor' );
						const aceeditor = ace.edit( editor );

						index = null;

						aceeditor.setTheme( 'ace/theme/' + $( element ).attr( 'data-theme' ) );
						aceeditor.getSession().setMode( 'ace/mode/' + $( element ).attr( 'data-mode' ) );
						aceeditor.setOptions( params );
						aceeditor.on(
							'change',
							function () {
								$( '#' + area.id ).val( aceeditor.getSession().getValue() );
								redux_change( $( element ) );
								aceeditor.resize();
							}
						);
					}
				);
			}
		);
	};
})( jQuery );
