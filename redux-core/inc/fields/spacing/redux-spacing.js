/*global redux*/

(function ( $ ) {
	'use strict';

	redux.field_objects         = redux.field_objects || {};
	redux.field_objects.spacing = redux.field_objects.spacing || {};

	redux.field_objects.spacing.init = function ( selector ) {
		selector = $.redux.getSelector( selector, 'spacing' );

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

				el.find( '.redux-spacing-units' ).select2();

				el.find( '.redux-spacing-input' ).on(
					'change',
					function () {
						let value;

						let units = $( this ).parents( '.redux-field:first' ).find( '.field-units' ).val();

						if ( 0 !== $( this ).parents( '.redux-field:first' ).find( '.redux-spacing-units' ).length ) {
							units = $( this ).parents( '.redux-field:first' ).find( '.redux-spacing-units option:selected' ).val();
						}

						value = $( this ).val();

						if ( 'undefined' !== typeof units && value ) {
							value += units;
						}

						if ( $( this ).hasClass( 'redux-spacing-all' ) ) {
							$( this ).parents( '.redux-field:first' ).find( '.redux-spacing-value' ).each(
								function () {
									$( this ).val( value );
								}
							);
						} else {
							$( '#' + $( this ).attr( 'rel' ) ).val( value );
						}
					}
				);

				el.find( '.redux-spacing-units' ).on(
					'change',
					function () {
						$( this ).parents( '.redux-field:first' ).find( '.redux-spacing-input' ).change();

						el.find( '.field-units' ).val( $( this ).val() );
					}
				);
			}
		);
	};
})( jQuery );
