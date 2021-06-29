/* global jQuery, redux */

(function( $ ) {
	'use strict';

	redux.field_objects            = redux.field_objects || {};
	redux.field_objects.dimensions = redux.field_objects.dimensions || {};

	redux.field_objects.dimensions.init = function( selector ) {
		selector = $.redux.getSelector( selector, 'dimensions' );

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

				el.find( '.redux-dimensions-units' ).select2();

				el.find( '.redux-dimensions-input' ).on(
					'change',
					function() {
						var units = $( this ).parents( '.redux-field:first' ).find( '.field-units' ).val();
						if ( 0 !== $( this ).parents( '.redux-field:first' ).find( '.redux-dimensions-units' ).length ) {
							units = $( this ).parents( '.redux-field:first' ).find( '.redux-dimensions-units option:selected' ).val();
						}
						if ( 'undefined' !== typeof units ) {
							el.find( '#' + $( this ).attr( 'rel' ) ).val( $( this ).val() + units );
						} else {
							el.find( '#' + $( this ).attr( 'rel' ) ).val( $( this ).val() );
						}
					}
				);

				el.find( '.redux-dimensions-units' ).on(
					'change',
					function() {
						$( this ).parents( '.redux-field:first' ).find( '.redux-dimensions-input' ).change();
					}
				);
			}
		);
	};
})( jQuery );
