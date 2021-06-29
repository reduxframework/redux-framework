/*global jQuery, redux, redux_change, ace */

( function( $ ) {
	'use strict';

	redux.field_objects            = redux.field_objects || {};
	redux.field_objects.ace_editor = redux.field_objects.ace_editor || {};

	redux.field_objects.ace_editor.init = function( selector ) {
		selector = $.redux.getSelector( selector, 'ace_editor' );

		$( selector ).each(
			function() {
				var el     = $( this );
				var parent = el;

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
					function( index, element ) {
						var area      = element;
						var params    = JSON.parse( $( this ).parent().find( '.localize_data' ).val() );
						var editor    = $( element ).attr( 'data-editor' );
						var aceeditor = ace.edit( editor );
						var id        = '';

						index = null;

						aceeditor.setTheme( 'ace/theme/' + jQuery( element ).attr( 'data-theme' ) );
						aceeditor.getSession().setMode( 'ace/mode/' + $( element ).attr( 'data-mode' ) );

						if ( el.hasClass( 'redux-field-container' ) ) {
							id = el.attr( 'data-id' );
						} else {
							id = el.parents( '.redux-field-container:first' ).attr( 'data-id' );
						}

						aceeditor.setOptions( params );
						aceeditor.on(
							'change',
							function() {
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
