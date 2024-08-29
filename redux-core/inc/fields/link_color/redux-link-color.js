/*
 * Field Link Color
 */

/*global jQuery, redux_change, redux, colorValidate */

(function ( $ ) {
	'use strict';

	redux.field_objects            = redux.field_objects || {};
	redux.field_objects.link_color = redux.field_objects.link_color || {};

	redux.field_objects.link_color.init = function ( selector ) {
		selector = $.redux.getSelector( selector, 'link_color' );

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

				el.find( '.redux-color-init' ).wpColorPicker(
					{
						change: function ( e, ui ) {
							$( this ).val( ui.color.toString() );

							redux_change( $( this ) );

							el.find( '#' + e.target.getAttribute( 'data-id' ) + '-transparency' ).prop( 'checked', false );
						}, clear: function ( e, ui ) {
							e = null;
							$( this ).val( ui.color.toString() );

							redux_change( $( this ).parent().find( '.redux-color-init' ) );
						}
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
			}
		);
	};
})( jQuery );
