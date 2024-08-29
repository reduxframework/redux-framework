/* global redux */

(function ( $ ) {
	'use strict';

	redux.field_objects         = redux.field_objects || {};
	redux.field_objects.spinner = redux.field_objects.spinner || {};

	redux.field_objects.spinner.init = function ( selector ) {
		selector = $.redux.getSelector( selector, 'spinner' );

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

				el.find( '.redux_spinner' ).each(
					function () {

						// Slider init.
						const spinner = $( this ).find( '.spinner-input' ).data();

						spinner.id = $( this ).find( '.spinner-input' ).attr( 'id' );

						el.find( '#' + spinner.id ).spinner(
							{
								value:      parseFloat( spinner.val, null ),
								min:        parseFloat( spinner.min, null ),
								max:        parseFloat( spinner.max, null ),
								step:       parseFloat( spinner.step, null ),
								addText:    spinner.plus,
								subText:    spinner.minus,
								prefix:     spinner.prefix,
								suffix:     spinner.suffix,
								places:     spinner.places,
								point:      spinner.point
							}
						);
					}
				);
			}
		);
	};
})( jQuery );
