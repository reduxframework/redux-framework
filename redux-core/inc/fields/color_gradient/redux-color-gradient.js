/*
 * Field Color Gradient
 */

/*global jQuery, redux, colorValidate, redux_change */

( function( $ ) {
	'use strict';

	var filtersLoaded = false;

	redux.field_objects                = redux.field_objects || {};
	redux.field_objects.color_gradient = redux.field_objects.color_gradient || {};

	redux.field_objects.color_gradient.init = function( selector ) {
		selector = $.redux.getSelector( selector, 'color_gradient' );

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

				if ( undefined === redux.field_objects.pro && undefined !== redux.field_objects.gradient_filters ) {
					filtersLoaded = true;

					redux.field_objects.gradient_filters.sliderInit( el, 'color_gradient' );
					redux.field_objects.gradient_filters.selectChange( el, 'color_gradient' );
				}

				el.find( '.redux-color-init' ).wpColorPicker(
					{
						change: function( e, ui ) {
							$( this ).val( ui.color.toString() );

							if ( filtersLoaded ) {
								redux.field_objects.gradient_filters.changeValue( $( this ), true, 'color_gradient' );
							}

							el.find( '#' + e.target.getAttribute( 'data-id' ) + '-transparency' ).prop( 'checked', false );
						}, clear: function() {
							$( this ).val( '' );

							if ( filtersLoaded ) {
								redux.field_objects.gradient_filters.changeValue( $( this ).parent().find( '.redux-color-init' ), true, 'color_gradient' );
							}
						}
					}
				);

				el.find( '.redux-color' ).on(
					'keyup',
					function() {
						var value = $( this ).val();
						var color = colorValidate( this );
						var id    = '#' + $( this ).attr( 'id' );

						if ( 'transparent' === value ) {
							$( this ).parent().parent().find( '.wp-color-result' ).css( 'background-color', 'transparent' );

							el.find( id + '-transparency' ).prop( 'checked', true );
						} else {
							el.find( id + '-transparency' ).prop( 'checked', false );

							if ( color && color !== $( this ).val() ) {
								$( this ).val( color );
							}
						}
					}
				);

				// Replace and validate field on blur.
				el.find( '.redux-color' ).on(
					'blur',
					function() {
						var value = $( this ).val();
						var id    = '#' + $( this ).attr( 'id' );

						if ( 'transparent' === value ) {
							$( this ).parent().parent().find( '.wp-color-result' ).css( 'background-color', 'transparent' );

							el.find( id + '-transparency' ).attr( 'checked', 'checked' );
						} else {
							if ( value === colorValidate( this ) ) {
								if ( 0 !== value.indexOf( '#' ) ) {
									$( this ).val( $( this ).data( 'oldcolor' ) );
								}
							}

							el.find( id + '-transparency' ).prop( 'checked', false );
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

				// When transparency checkbox is clicked.
				el.find( '.color-transparency' ).on(
					'click',
					function() {
						var prevColor;

						if ( $( this ).is( ':checked' ) ) {
							el.find( '.redux-saved-color' ).val( $( '#' + $( this ).data( 'id' ) ).val() );
							el.find( '#' + $( this ).data( 'id' ) ).val( 'transparent' );
							el.find( '#' + $( this ).data( 'id' ) ).parents( '.colorGradient' ).find( '.wp-color-result' ).css( 'background-color', 'transparent' );
						} else {
							prevColor = $( this ).parents( '.colorGradient' ).find( '.redux-saved-color' ).val();
							if ( '' === prevColor ) {
								prevColor = $( '#' + $( this ).data( 'id' ) ).data( 'default-color' );
							}
							el.find( '#' + $( this ).data( 'id' ) ).parents( '.colorGradient' ).find( '.wp-color-result' ).css( 'background-color', prevColor );
							el.find( '#' + $( this ).data( 'id' ) ).val( prevColor );
						}

						if ( filtersLoaded ) {
							redux.field_objects.gradient_filters.changeValue( $( this ), true, 'color_gradient' );
						}

						redux_change( $( this ) );
					}
				);
			}
		);
	};
} )( jQuery );
