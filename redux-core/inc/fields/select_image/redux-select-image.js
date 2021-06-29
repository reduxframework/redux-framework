/*global redux*/

(function( $ ) {
	'use strict';

	redux.field_objects              = redux.field_objects || {};
	redux.field_objects.select_image = redux.field_objects.select_image || {};

	redux.field_objects.select_image.init = function( selector ) {
		selector = $.redux.getSelector( selector, 'select_image' );

		$( selector ).each(
			function() {
				var value;
				var preview;

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

				el.find( 'select.redux-select-images' ).select2();

				value   = el.find( 'select.redux-select-images' ).val();
				preview = el.find( 'select.redux-select-images' ).parents( '.redux-field:first' ).find( '.redux-preview-image' );

				preview.attr( 'src', value );

				el.find( '.redux-select-images' ).on(
					'change',
					function() {
						var preview = $( this ).parents( '.redux-field:first' ).find( '.redux-preview-image' );

						if ( '' === $( this ).val() ) {
							preview.fadeOut(
								'medium',
								function() {
									preview.attr( 'src', '' );
								}
							);
						} else {
							preview.attr( 'src', $( this ).val() );
							preview.fadeIn().css( 'visibility', 'visible' );
						}
					}
				);
			}
		);
	};
})( jQuery );
