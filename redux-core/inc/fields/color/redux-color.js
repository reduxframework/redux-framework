/**
 *  Field Color (color)
 */

/*global jQuery, redux_change, redux, colorValidate */

(function ( $ ) {
	'use strict';

	redux.field_objects       = redux.field_objects || {};
	redux.field_objects.color = redux.field_objects.color || {};

	redux.field_objects.color.init = function ( selector ) {
		selector = $.redux.getSelector( selector, 'color' );

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

				if ( el.find( 'input.redux-color' ).hasClass( 'alpha-enabled' ) ) {
					el.addClass( 'alpha-enabled' );
				}

				el.find( '.redux-color-init' ).wpColorPicker(
					{
						change: function ( e, ui ) {
							$( this ).val( ui.color.toString() );

							redux_change( $( this ) );

							el.find( '#' + e.target.getAttribute( 'data-id' ) + '-transparency' ).prop( 'checked', false );
						}, clear: function () {
							$( this ).val( '' );

							redux_change( $( this ).parent().find( '.redux-color-init' ) );
						}
					}
				);

				el.find( '.redux-color' ).on(
					'focus',
					function () {
						$( this ).data( 'oldcolor', $( this ).val() );
					}
				);

				el.find( '.redux-color' ).on(
					'keyup',
					function () {
						const value = $( this ).val();
						const color = colorValidate( this );
						const id    = '#' + $( this ).attr( 'id' );

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
					function () {
						const value = $( this ).val();
						const id    = '#' + $( this ).attr( 'id' );

						if ( 'transparent' === value ) {
							$( this ).parent().parent().find( '.wp-color-result' ).css( 'background-color', 'transparent' );

							el.find( id + '-transparency' ).attr( 'checked', 'checked' );
						} else {
							if ( colorValidate( this ) === value ) {
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
					function () {
						$( this ).data( 'oldkeypress', $( this ).val() );
					}
				);

				// When transparency checkbox is clicked.
				el.find( '.color-transparency' ).on(
					'click',
					function () {
						let prevColor;

						if ( $( this ).is( ':checked' ) ) {
							el.find( '.redux-saved-color' ).val( $( '#' + $( this ).data( 'id' ) ).val() );
							el.find( '#' + $( this ).data( 'id' ) ).val( 'transparent' );
							el.find( '#' + $( this ).data( 'id' ) ).parents( '.redux-field-container' ).find( '.wp-color-result' ).css( 'background-color', 'transparent' );
						} else {
							prevColor = $( this ).parents( '.redux-field-container' ).find( '.redux-saved-color' ).val();

							if ( '' === prevColor ) {
								prevColor = $( '#' + $( this ).data( 'id' ) ).data( 'default-color' );
							}

							el.find( '#' + $( this ).data( 'id' ) ).parents( '.redux-field-container' ).find( '.wp-color-result' ).css( 'background-color', prevColor );
							el.find( '#' + $( this ).data( 'id' ) ).val( prevColor );
						}

						redux_change( $( this ) );
					}
				);
			}
		);
	};
})( jQuery );
