/* global redux, jQuery */

/**
 * JS Button library
 *
 * @author Kevin Provance (kprovance)
 */

( function( $ ) {
	'use strict';

	redux.field_objects                  = redux.field_objects || {};
	redux.field_objects.js_button        = redux.field_objects.js_button || {};
	redux.field_objects.js_button.mainID = '';

	/*******************************************************************************
	 * Runs when library is loaded.
	 ******************************************************************************/
	redux.field_objects.js_button.init = function( selector ) {

		// If no selector is passed, grab one from the HTML.
		if ( ! selector ) {
			selector = $( document ).find( '.redux-container-js_button' );
		}

		// Enum instances of our object.
		$( selector ).each(
			function() {
				var button;

				var el     = $( this );
				var parent = el;

				if ( ! el.hasClass( 'redux-field-container' ) ) {
					parent = el.parents( '.redux-field-container:first' );
				}

				if ( parent.hasClass( 'redux-field-init' ) ) {
					parent.removeClass( 'redux-field-init' );
				} else {
					return;
				}

				// Do module level init.
				redux.field_objects.js_button.modInit( el );

				// Get the button handle.
				button = $( el ).find( 'input' );

				$.each(
					button,
					function( key, value ) {
						key = null;

						$( this ).on(
							'click',
							function( e ) {
								var funcName = $( value ).data( 'function' );

								// Not really needed, but just in case.
								e.preventDefault();

								if ( '' !== funcName ) {

									// Ensure custom function exists.
									if ( 'function' === typeof ( window[funcName] ) ) {

										// Add it to the window object and execute.
										window[funcName]();
									} else {

										// Let the dev know he fucked up someplace.
										throw( 'JS Button Error.  Function ' + funcName + ' does not exist.' );
									}
								}
							}
						);
					}
				);
			}
		);
	};

	/*******************************************************************************
	 * Module level init
	 ******************************************************************************/
	redux.field_objects.js_button.modInit = function( el ) {

		// ID of the fieldset.
		redux.field_objects.js_button.mainID = el.attr( 'data-id' );
	};
} )( jQuery );
