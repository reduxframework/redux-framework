/* global redux, wp, redux_custom_fonts_l10, ajaxurl */

(function ( $ ) {
	'use strict';

	var l10n;
	var reduxObject;
	var ajaxDone = false;

	redux.field_objects              = redux.field_objects || {};
	redux.field_objects.custom_fonts = redux.field_objects.custom_fonts || {};

	redux.field_objects.custom_fonts.init = function ( selector ) {

		// If no selector is passed, grab one from the HTML.
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-custom_font:visible' );
		}

		// Enum instances of our object.
		$( selector ).each(
			function () {
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

				// Init module level code.
				redux.field_objects.custom_fonts.modInit( el );
			}
		);
	};

	redux.field_objects.custom_fonts.modInit = function ( el ) {
		var optName = $( '.redux-ajax-security' ).data( 'opt-name' );

		l10n = redux_custom_fonts_l10;

		if ( undefined === optName ) {
			reduxObject = redux;
		} else {
			reduxObject = redux.optName;
		}

		el.find( '.checkbox' ).on(
			'click',
			function () {
				var val = 0;
				var checkName;
				var checkVal;
				var opVal;

				checkName = $( this ).attr( 'id' );

				if ( 'custom-font-convert' === checkName ) {
					if ( $( this ).is( ':checked' ) ) {
						checkVal = '';
						opVal    = 1;

						el.find( '.conversion-types' ).removeClass( 'is-disabled' );
					} else {
						checkVal = 'disabled';
						opVal    = 0.7;

						el.find( '.conversion-types' ).addClass( 'is-disabled' );
					}

					el.find( '#custom-font-eot,#custom-font-svg,#custom-font-ttf,#custom-font-woff,#custom-font-woff2' ).prop( 'disabled', checkVal );
					el.find( '.conversion-types' ).css( 'opacity', opVal );
				}

				if ( $( this ).is( ':checked' ) ) {
					val = $( this ).parent().find( '.checkbox-check' ).attr( 'data-val' );
				}

				$( this ).parent().find( '.checkbox-check' ).val( val );

				redux_change( $( this ) );
			}
		);

		// Remove the image button.
		el.find( '.remove-font' ).off( 'click' ).on(
			'click',
			function () {
				redux.field_objects.custom_fonts.remove_font( el, $( this ).parents( 'fieldset.redux-field:first' ) );
			}
		);

		// Upload media button.
		el.find( '.media_add_font' ).off().on(
			'click',
			function ( event ) {
				redux.field_objects.custom_fonts.add_font( el, event, $( this ).parents( 'fieldset.redux-field:first' ) );
			}
		);

		el.find( '.fontDelete' ).on(
			'click',
			function ( e ) {
				var data;

				var parent = $( this ).parents( 'td:first' );

				e.preventDefault();

				parent.find( '.spinner' ).show();

				data        = $( this ).data();
				data.action = 'redux_custom_fonts';
				data.nonce  = $( this ).parents( '.redux-container-custom_font:first' ).find( '.media_add_font' ).attr( 'data-nonce' );

				$.post(
					ajaxurl,
					data,
					function ( response ) {
						var rowCount;

						response = JSON.parse( response );

						if ( response.type && 'success' === response.type ) {
							rowCount = parent.parents( 'table:first' ).find( 'tr' ).length;

							if ( 1 === rowCount ) {
								parent.parents( 'table:first' ).fadeOut().remove();
							} else {
								parent.parents( 'tr:first' ).fadeOut().remove();
							}
						} else {
							alert( l10n.delete_error + ' ' + response.msg );

							parent.find( '.spinner' ).hide();
						}
					}
				);

				return false;
			}
		);
	};

	redux.field_objects.custom_fonts.startTimer = function ( el, status ) {
		var cur_data;

		$.ajax(
			{
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'redux_custom_font_timer'
				},
				beforeSend: function () {

				},
				success: function ( data ) {
					var msg;

					if ( false === ajaxDone ) {
						setTimeout( redux.field_objects.custom_fonts.startTimer( el, status ), 500 );

						msg = reduxObject.args.please_wait + ': ' + status + '<br><br>' + data;
					} else {
						msg  = l10n.complete;
						data = 'finished';
					}

					if ( '' !== data ) {
						if ( cur_data !== data ) {
							$( '.blockUI.blockMsg h2' ).html( msg );

							cur_data = data;
						}
					}
				}
			}
		);
	};

	redux.field_objects.custom_fonts.add_font = function ( el, event, selector ) {
		var frame;

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Create the media frame.
		frame = wp.media(
			{
				multiple: false,
				library: {
					type: ['application', 'font'] // Only allow zip files.
				}, // Set the title of the modal.
				title: 'Redux Custom Fonts:  ' + l10n.media_title, // Customize the submit button.
				button: {

					// Set the text of the button.
					text: l10n.media_button

					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
				}
			}
		);

		// When an image is selected, run a callback.
		frame.on(
			'select',
			function () {
				var nonce;
				var data;
				var status;
				var conversion;

				// Grab the selected attachment.
				var attachment = frame.state().get( 'selection' ).first();
				var error      = selector.find( '.font-error' );

				error.slideUp();
				error.find( 'span' ).text( '' );

				frame.close();
				if ( 'application' !== attachment.attributes.type && 'font' !== attachment.attributes.type ) {
					return;
				}

				nonce = $( selector ).find( '.media_add_font' ).attr( 'data-nonce' );

				conversion = $( '#custom-font-convert' ).is( ':checked' );

				data = {
					action: 'redux_custom_fonts',
					nonce: nonce,
					attachment_id: attachment.id,
					title: attachment.attributes.title,
					mime: attachment.attributes.mime,
					filename: attachment.attributes.filename,
					conversion: Boolean( conversion )
				};

				if ( 'application/zip ' === data.mime ) {
					status = l10n.unzip;
				} else {
					status = l10n.convert;
				}

				redux.field_objects.custom_fonts.startTimer( el, status );

				$.blockUI( { message: '<h2>' + reduxObject.args.please_wait + ': ' + status + '</h2>' } );

				$.post(
					ajaxurl,
					data,
					function ( response ) {
						console.log( 'Redux Custom Fonts API Response (For support purposes)' );
						console.log( response );

						response = JSON.parse( response );

						if ( 'success' === response.type ) {
							if ( '' !== response.msg ) {
								$.unblockUI();

								error.find( 'span' ).html( response.msg + '  ' + l10n.partial );
								error.slideDown();

								ajaxDone = true;

								return;
							}

							window.onbeforeunload = '';
							location.reload();
						} else if ( 'error' === response.type ) {
							$.unblockUI();
							error.find( 'span' ).html( response.msg );
							error.slideDown();
						} else {
							$.unblockUI();
							error.find( 'span' ).text( l10n.unknown );
							error.slideDown();
						}

						ajaxDone = true;
					}
				);
			}
		);

		// Finally, open the modal.
		frame.open();
	};

	redux.field_objects.custom_fonts.remove_font = function ( el, selector ) {
		el = null;

		// This shouldn't have been run...
		if ( ! selector.find( '.remove-image' ).addClass( 'hide' ) ) {
			return;
		}
	};

	redux.field_objects.custom_fonts.sleep = function ( milliseconds ) {
		var start = new Date().getTime();

		var i;

		for ( i = 0; i < 1e7; i += 1 ) {
			if ( ( new Date().getTime() - start ) > milliseconds ) {
				break;
			}
		}
	};
})( jQuery );
