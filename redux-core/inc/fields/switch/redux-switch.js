/*global redux_change, redux*/

/**
 * Switch
 * Dependencies        : jquery
 * Feature added by    : Smartik - http://smartik.ws/
 * Date            : 03.17.2013
 */

(function ( $ ) {
	'use strict';

	redux.field_objects        = redux.field_objects || {};
	redux.field_objects.switch = redux.field_objects.switch || {};

	redux.field_objects.switch.init = function ( selector ) {
		selector = $.redux.getSelector( selector, 'switch' );

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

				el.find( '.cb-enable' ).on(
					'click',
					function () {
						let parent;
						let obj;
						let $fold;

						if ( $( this ).hasClass( 'selected' ) ) {
							return;
						}

						parent = $( this ).parents( '.switch-options' );

						$( '.cb-disable', parent ).removeClass( 'selected' );
						$( this ).addClass( 'selected' );
						$( '.checkbox-input', parent ).val( 1 ).trigger( 'change' );

						redux_change( $( '.checkbox-input', parent ) );

						// Fold/unfold related options.
						obj   = $( this );
						$fold = '.f_' + obj.data( 'id' );

						el.find( $fold ).slideDown( 'normal', 'swing' );
					}
				);

				el.find( '.cb-disable' ).on(
					'click',
					function () {
						let parent;
						let obj;
						let $fold;

						if ( $( this ).hasClass( 'selected' ) ) {
							return;
						}

						parent = $( this ).parents( '.switch-options' );

						$( '.cb-enable', parent ).removeClass( 'selected' );
						$( this ).addClass( 'selected' );
						$( '.checkbox-input', parent ).val( 0 ).trigger( 'change' );

						redux_change( $( '.checkbox-input', parent ) );

						// Fold/unfold related options.
						obj   = $( this );
						$fold = '.f_' + obj.data( 'id' );

						el.find( $fold ).slideUp( 'normal', 'swing' );
					}
				);

				el.find( '.cb-enable span, .cb-disable span' ).find().attr( 'unselectable', 'on' );
			}
		);
	};
})( jQuery );
