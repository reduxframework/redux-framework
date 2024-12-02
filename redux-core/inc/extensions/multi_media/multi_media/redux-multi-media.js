/* global redux, redux_multi_media_l10, wp, redux_change */
// noinspection JSUnresolvedReference

/**
 * Multi Media Selector library
 *
 * @author Kevin Provance (kprovance)
 */

( function ( $ ) {
	'use strict';

	let l10n;

	redux.field_objects             = redux.field_objects || {};
	redux.field_objects.multi_media = redux.field_objects.multi_media || {};

	/*******************************************************************************
	 * Function: init
	 *
	 * Runs when the library is loaded.
	 ******************************************************************************/
	redux.field_objects.multi_media.init = function ( selector ) {

		// If no selector is passed, grab one from the HTML.
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-multi_media:visible' );
		}

		// Enum instances of our object.
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

				// Handle clicking of the delete button.
				redux.field_objects.multi_media.bindDelete( el );

				// Handle clicking of the upload icon.
				el.find( '.redux_upload_button' ).off().on(
					'click',
					function ( event ) {
						redux.field_objects.multi_media.addFile( event, $( this ).parents( 'fieldset.redux-field:first' ), $( this ) );
					}
				);

				// Init module level code.
				redux.field_objects.multi_media.modInit( el );
			}
		);
	};

	/*******************************************************************************
	 * Function: bindDelete
	 *
	 * Force DOM to recognize new delete button instances.
	 ******************************************************************************/
	redux.field_objects.multi_media.bindDelete = function ( el ) {
		el.find( '.redux_remove_file_button' ).off( 'click' ).on(
			'click',
			function ( event ) {
				redux.field_objects.multi_media.removeFile( event, $( this ).parents( 'fieldset.redux-field:first' ), $( this ) );
			}
		);
	};

	/*******************************************************************************
	 * Function: modInit
	 *
	 * Module level init
	 ******************************************************************************/
	redux.field_objects.multi_media.modInit = function () {

		// Localization variable.
		l10n = redux_multi_media_l10;
	};

	/*******************************************************************************
	 * Function: removeErrMsgs
	 *
	 * Removes all error messages after clicking upload
	 * button.
	 ******************************************************************************/

	// Removes error message(s) when clicking the Upload button.
	redux.field_objects.multi_media.removeErrMsgs = function ( mainID ) {

		// Enumerate and remove existing 'file exists' messages.
		$( '#' + mainID + ' .attach_list li.redux-file-exists' ).each(
			function ( idx, li ) {
				idx = null;

				$( li ).remove();
			}
		);

		// Enumerate and remove existing 'max upload' messages.
		$( '#' + mainID + ' .attach_list li.redux-max-limit' ).each(
			function ( idx, li ) {
				idx = null;

				$( li ).remove();
			}
		);
	};

	// Checks for duplicate after file selection.
	redux.field_objects.multi_media.selExists = function ( mainID, item ) {
		let len;

		let val = false;

		// Enumerate existing files.
		$( '#' + mainID + ' .attach_list li' ).each(
			function ( idx, li ) {
				idx = null;

				// Check for duplicate based on ID.
				len = $( li ).find( 'input#filelist-' + item );

				// If it exists, exit .each.
				if ( 0 !== len.length ) {
					val = true;
					return false;
				}
			}
		);

		// Return value.
		return val;
	};

	/*******************************************************************************
	 * Function: addFile
	 *
	 * Runs when upload button is clicked.
	 ******************************************************************************/
	redux.field_objects.multi_media.addFile = function ( event, selector, self ) {

		// Variables.
		let frame;
		let libFilter;
		let filter;
		let maxFileUpload;

		const isList     = true;
		let uploadStatus = true;

		// Get input ID.
		const inputID = self.prev( 'input' ).attr( 'id' );

		// Make form field ID.
		const $formfield = $( '#' + inputID );

		// Get form name.
		const formName = $formfield.data( 'name' );

		const mainID = selector.attr( 'data-id' );

		// Prevent default action.
		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Remove existing error messages.
		redux.field_objects.multi_media.removeErrMsgs( mainID );

		// Get library filter data.
		filter = $( selector ).find( '.library-filter' ).data( 'lib-filter' );

		// Get max file upload number.
		maxFileUpload = $( selector ).find( '.redux-multi-media-container' ).data( 'max-file-upload' );

		// Library filter MUST exist to do decoding.
		if ( undefined !== filter ) {
			if ( '' !== filter ) {
				libFilter = [];
				filter    = decodeURIComponent( filter );
				filter    = JSON.parse( filter );

				// Enum file extensions.
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
			{
				multiple: isList,
				title: l10n.title,
				library: {
					type: libFilter
				},
				button: {
					text: l10n.upload_file
				}
			}
		);

		// When an image is selected, run a callback.
		frame.on(
			'select',
			function () {
				let addCount = 0;
				let doChange;

				// Set up our fileGroup array.
				const fileGroup = [];
				const fileArr   = [];
				const imgArr    = [];
				const msgArr    = [];

				// Grab the selected attachment.
				const selection = frame.state().get( 'selection' );

				// Get all of our selected files.
				const attachment = selection.toJSON();

				// Get existing file count.
				const childCount = $( '#' + mainID + ' .attach_list' ).children().length;

				$formfield.val( attachment.url );
				$( '#' + inputID + '_id' ).val( attachment.id );

				// Enum through each attachment.
				$( attachment ).each(
					function () {
						let dupMsg;
						let maxMsg;

						// Respect max upload limit.
						if ( maxFileUpload <= 0 || ( addCount + childCount ) < maxFileUpload ) {

							// Check for duplicates and format duplicate message.
							if ( redux.field_objects.multi_media.selExists( mainID, this.id ) ) {
								dupMsg       = l10n.dup_warn;
								dupMsg       = dupMsg.replace( '%s', '<strong>' + this.filename + '</strong>' );
								uploadStatus = '<li class="redux-file-exists">' + dupMsg + '</li>';

								msgArr.push( uploadStatus );

								// If only file, then don't ask to save changes.
								doChange = false;

								// Continue equivalent.
								return true;
							}

							// Handle images.
							if ( this.type && 'image' === this.type ) {

								// Image preview.
								/* jscs:disable maximumLineLength */
								uploadStatus = '<li class="img_status"><img width="50" height="50" src="' + this.url + '" class="attachment-50x50" alt="' + this.filename + '"><p><a href="#" class="redux_remove_file_button" rel="' + inputID + '[' + this.id + ']">' + l10n.remove_image + '</a></p><input type="hidden" id="filelist-' + this.id + '" name="' + formName + '[' + this.id + ']" value="' + this.url + '"></li>';

								// Add our file to our fileGroup array.
								imgArr.push( uploadStatus );

								// Set change flag.
								doChange = true;

								// Handle everything else.
							} else {

								// Standard generic output if it's not an image.
								uploadStatus = '<li>' + l10n.file + ' <strong>' + this.filename + '</strong>&nbsp;&nbsp;&nbsp; (<a href="' + this.url + '" target="_blank" rel="external">' + l10n.download + '</a> / <a href="#" class="redux_remove_file_button" rel="' + inputID + '[' + this.id + ']">' + l10n.remove_file + '</a>)<input type="hidden" id="filelist-' + this.id + '" name="' + formName + '[' + this.id + ']" value="' + this.url + '"></li>';

								fileArr.push( uploadStatus );
							}

							// Increment count of added files.
							addCount++; // += 1;

							// If max file upload reached, generate error message.
						} else {
							maxMsg       = l10n.max_warn;
							maxMsg       = maxMsg.replace( '%s', '<strong>' + maxFileUpload + '</strong>' );
							uploadStatus = '<li class="redux-max-limit">' + maxMsg + '</li>';

							msgArr.push( uploadStatus );

							// Bail out of .each for good!
							return false;
						}
					}
				);

				// Push images files onto end of stack.
				if ( ! $.isEmptyObject( imgArr ) ) {
					$( imgArr ).each(
						function ( idx, val ) {
							idx = null;

							fileGroup.push( val );
							doChange = true;
						}
					);
				}

				// Push none image files onto end of stack.
				if ( ! $.isEmptyObject( fileArr ) ) {
					$( fileArr ).each(
						function ( idx, val ) {
							idx = null;

							fileGroup.push( val );
							doChange = true;
						}
					);
				}

				// Push errors onto end of stack.
				if ( ! $.isEmptyObject( msgArr ) ) {
					$( msgArr ).each(
						function ( idx, val ) {
							idx = null;

							fileGroup.push( val );
						}
					);
				}

				// Append each item from our fileGroup array to .redux_media_status.
				$( fileGroup ).each(
					function () {
						$formfield.siblings( '.redux_media_status' ).slideDown().append( this );
					}
				);

				// Close media frame.
				frame.close();

				// Prompt for save changes, if necessary.
				if ( true === doChange ) {
					redux.field_objects.multi_media.bindDelete( selector );

					redux_change( $( selector ).find( '.redux_media_status' ) );
				}
			}
		);

		// Finally, open the modal.
		frame.open();
	};

	/*******************************************************************************
	 * Function: removeFile Function
	 *
	 * Runs when the delete icon or remove link is clicked.
	 ******************************************************************************/
	redux.field_objects.multi_media.removeFile = function ( event, selector, self ) {
		let inputID;
		let $container;

		const $self = self;

		// Prevent default action.
		event.preventDefault();

		// If delete icon is clicked.
		if ( $self.is( '.attach_list .redux_remove_file_button' ) ) {

			// Remove image from page.
			$self.parents( 'li' ).remove();

			// Prompt for save changes.
			redux_change( $( selector ).find( '.redux_media_status' ) );

			// Bail out.
			return false;
		}

		// Remove file link from page.
		inputID    = $self.attr( 'rel' );
		$container = $self.parents( '.img_status' );

		selector.find( 'input#' + inputID ).val( '' );
		selector.find( 'input#' + inputID + '_id' ).val( '' );

		if ( ! $container.length ) {
			$self.parents( '.redux_media_status' ).html( '' );
		} else {
			$container.html( '' );
		}

		return false;
	};
})( jQuery );
