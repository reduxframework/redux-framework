/**
 * Field Border (border)
 */

/*global redux_change, redux, colorValidate */

(function( $ ) {
	'use strict';

	redux.field_objects        = redux.field_objects || {};
	redux.field_objects.border = redux.field_objects.border || {};

	redux.field_objects.border.init = function( selector ) {
		selector = $.redux.getSelector( selector, 'border' );

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

				el.find( '.redux-border-top, .redux-border-right, .redux-border-bottom, .redux-border-left, .redux-border-all' ).numeric( { allowMinus: false } );
				el.find( '.redux-border-style' ).select2();

				el.find( '.redux-border-input' ).on(
					'change',
					function() {
						var value;

						var units = $( this ).parents( '.redux-field:first' ).find( '.field-units' ).val();

						if ( 0 !== $( this ).parents( '.redux-field:first' ).find( '.redux-border-units' ).length ) {
							units = $( this ).parents( '.redux-field:first' ).find( '.redux-border-units option:selected' ).val();
						}

						value = $( this ).val();

						if ( 'undefined' !== typeof units && value ) {
							value += units;
						}

						if ( $( this ).hasClass( 'redux-border-all' ) ) {
							$( this ).parents( '.redux-field:first' ).find( '.redux-border-value' ).each(
								function() {
									$( this ).val( value );
								}
							);
						} else {
							$( '#' + $( this ).attr( 'rel' ) ).val( value );
						}
					}
				);

				el.find( '.redux-border-units' ).on(
					'change',
					function() {
						$( this ).parents( '.redux-field:first' ).find( '.redux-border-input' ).change();
					}
				);

				el.find( '.redux-color-init' ).wpColorPicker(
					{
						change: function( e, ui ) {
							$( this ).val( ui.color.toString() );
							redux_change( $( this ) );
							el.find( '#' + e.target.getAttribute( 'data-id' ) + '-transparency' ).removeAttr( 'checked' );
						},
						clear: function( e, ui ) {
							e = null;
							$( this ).val( ui.color.toString() );
							redux_change( $( this ).parent().find( '.redux-color-init' ) );
						}
					}
				);

				el.find( '.redux-color' ).on(
					'keyup',
					function() {
						var color = colorValidate( this );

						if ( color && color !== $( this ).val() ) {
							$( this ).val( color );
						}
					}
				);

				// Replace and validate field on blur.
				el.find( '.redux-color' ).on(
					'blur',
					function() {
						var value = $( this ).val();

						if ( colorValidate( this ) === value ) {
							if ( 0 !== value.indexOf( '#' ) ) {
								$( this ).val( $( this ).data( 'oldcolor' ) );
							}
						}
					}
				);

				// Store the old valid color on keydown.
				el.find( '.redux-color' ).on(
					'keydown',
					function() {
						$( this ).data( 'oldkeypress', $( this ).val() );
					}
				);

			}
		);
	};
})( jQuery );
