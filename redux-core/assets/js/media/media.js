/*global redux_change, wp, redux */

/**
 * Media Uploader
 * Dependencies        : jquery, wp media uploader
 * Feature added by    : Smartik - http://smartik.ws/
 * Date                : 05.28.2013
 *
 * Modified by         : Kevin Provance (kprovance/svl-studios)
 * Date                : 07.07.2021
 */

( function ( $ ) {
	'use strict';

	let isFiltered;

	redux.field_objects       = redux.field_objects || {};
	redux.field_objects.media = redux.field_objects.media || {};

	redux.field_objects.media.init = function ( selector ) {
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-media:visible' );
		}

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

				if ( undefined !== redux.field_objects.image_filters) {
					redux.field_objects.image_filters.sliderInit( el, 'media' );
					redux.field_objects.image_filters.checkbox( el, 'media' );
				}

				isFiltered = false;

				// Remove the image button.
				el.find( '.remove-image, .remove-file' ).off( 'click' ).on(
					'click',
					function () {
						redux.field_objects.media.removeFile( $( this ).parents( 'fieldset.redux-field:first' ) );
					}
				);

				// Upload media button.
				el.find( '.media_upload_button' ).off().on(
					'click',
					function ( event ) {
						redux.field_objects.media.addFile( event, $( this ).parents( 'fieldset.redux-field:first' ) );
					}
				);
			}
		);
	};

	// Add a file via the wp.media function.
	redux.field_objects.media.addFile = function ( event, selector ) {
		let frame;
		let libFilter;
		let filter;
		let data;
		let thumbSrc;
		let height;
		let key;
		let object;

		const jQueryel = $( this );

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Get library filter data.
		filter = $( selector ).find( '.library-filter' ).data( 'lib-filter' );

		// Must exist to do decoding.
		if ( undefined !== filter ) {
			if ( '' !== filter ) {
				libFilter  = [];
				isFiltered = true;
				filter     = decodeURIComponent( filter );
				filter     = JSON.parse( filter );

				$.each(
					filter,
					function ( index, value ) {
						index = null;
						libFilter.push( value );
					}
				);
			}
		}

		// Create the media frame.
		frame = wp.media(
			{ multiple: false,
				library: { type: libFilter },  // Only allow images.

				// Set the title of the modal.
				title: jQueryel.data( 'choose' ),

				// Customize the 'submit' button.
				button: {

					// Set the text of the button.
					text: jQueryel.data( 'update' )

					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
				}
			}
		);

		// When an image is selected, run a callback.
		frame.on(
			'select',
			function () {

				// Grab the selected attachment.
				const attachment = frame.state().get( 'selection' ).first();

				frame.close();

				data = $( selector ).find( '.data' ).data();

				if ( 'undefined' === typeof redux.field_objects.media || undefined === typeof redux.field_objects.media ) {
					redux.field_objects.media = {};
				}

				if ( undefined === data || 'undefined' === data.mode ) {
					data      = {};
					data.mode = 'image';
				}

				if ( true === isFiltered ) {
					data.mode = 0;
				}

				if ( 0 === data.mode ) {

				} else {
					if ( false !== data.mode ) {
						if ( attachment.attributes.type !== data.mode ) {
							if ( attachment.attributes.subtype !== data.mode ) {
								return;
							}
						}
					}
				}

				selector.find( '.upload' ).val( attachment.attributes.url );
				selector.find( '.upload-id' ).val( attachment.attributes.id );
				selector.find( '.upload-height' ).val( attachment.attributes.height );
				selector.find( '.upload-width' ).val( attachment.attributes.width );

				redux_change( $( selector ).find( '.upload-id' ) );

				thumbSrc = attachment.attributes.url;

				if ( 'undefined' !== typeof attachment.attributes.sizes && 'undefined' !== typeof attachment.attributes.sizes.thumbnail ) {
					if ( 'thumbnail' === data.previewSize ) {
						thumbSrc = attachment.attributes.sizes.thumbnail.url;
					}
				} else if ( 'undefined' !== typeof attachment.attributes.sizes ) {
					height = attachment.attributes.height;

					for ( key in attachment.attributes.sizes ) {
						if ( attachment.attributes.sizes.hasOwnProperty( key ) ) {
							object = attachment.attributes.sizes[ key ];

							if ( object.height < height ) {
								height   = object.height;
								thumbSrc = object.url;
							}
						}
					}
				} else {
					thumbSrc = attachment.attributes.icon;
				}

				selector.find( '.upload-thumbnail' ).val( thumbSrc );
				if ( ! selector.find( '.upload' ).hasClass( 'noPreview' ) ) {
					selector.find( '.screenshot' ).empty().hide().append( '<img alt="" class="redux-option-image" src="' + thumbSrc + '">' ).slideDown( 'fast' );
				}

				selector.find( '.remove-image' ).removeClass( 'hide' ); // Show "Remove" button.
				selector.find( '.redux-background-properties' ).slideDown();
			}
		);

		// Finally, open the modal.
		frame.open();
	};

	// Function to remove the image on click. Still requires a save.
	redux.field_objects.media.removeFile = function ( selector ) {
		let screenshot;

		// This shouldn't have been run...
		if ( ! selector.find( '.remove-image' ).addClass( 'hide' ) ) {
			return;
		}

		selector.find( '.remove-image' ).addClass( 'hide' ); // Hide "Remove" button.
		selector.find( '.upload' ).val( '' );
		selector.find( '.upload-id' ).val( '' );
		selector.find( '.upload-height' ).val( '' );
		selector.find( '.upload-width' ).val( '' );
		selector.find( '.upload-thumbnail' ).val( '' );
		redux_change( $( selector ).find( '.upload-id' ) );
		selector.find( '.redux-background-properties' ).hide();

		screenshot = selector.find( '.screenshot' );

		// Hide the screenshot.
		screenshot.slideUp();

		selector.find( '.remove-file' ).off();

		// We don't display the upload button if .upload-notice is present.
		// This means the user doesn't have the WordPress 3.5 Media Library Support.
		if ( selector.find( '.section-upload .upload-notice' ).length > 0 ) {
			selector.find( '.media_upload_button' ).remove();
		}
	};
} )( jQuery );
