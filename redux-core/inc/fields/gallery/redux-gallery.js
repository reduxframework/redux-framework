/* global redux_change, wp, redux */

(function ( $ ) {
	'use strict';

	redux.field_objects         = redux.field_objects || {};
	redux.field_objects.gallery = redux.field_objects.gallery || {};

	redux.field_objects.gallery.init = function ( selector ) {
		selector = $.redux.getSelector( selector, 'gallery' );

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

				// When the user clicks on the Add/Edit gallery button, we need to display the gallery editing.
				el.on(
					{
						click: function ( event ) {
							let current_gallery;
							let final;
							let val;
							let frame;
							let uploader;
							let spinner;
							let inline;

							// Hide gallery settings used for posts/pages.
							wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend(
								{
									template: function () {
										return null;
									}
								}
							);

							current_gallery = $( this ).closest( 'fieldset' );

							if ( 'clear-gallery' === event.currentTarget.id ) {

								// Remove value from input.
								current_gallery.find( '.gallery_values' ).val( '' );

								// Remove preview images.
								current_gallery.find( '.screenshot' ).html( '' );

								return;
							}

							// Make sure the media gallery API exists.
							if ( 'undefined' === typeof wp || ! wp.media || ! wp.media.gallery ) {
								return;
							}

							event.preventDefault();

							// Activate the media editor.
							val = current_gallery.find( '.gallery_values' ).val();

							if ( ! val ) {
								final = '[gallery ids="0"]';
							} else {
								final = '[gallery ids="' + val + '"]';
							}

							frame = wp.media.gallery.edit( final );

							if ( ! val ) {
								uploader = $( 'body' ).find( '#' + frame.el.id );
								inline   = uploader.find( '.uploader-inline' );
								spinner  = uploader.find( '.media-toolbar .spinner' );

								setTimeout(
									function () {
										if ( inline.hasClass( 'hidden' ) ) {
											inline.removeClass( 'hidden' );
											spinner.removeClass( 'is-active' );
										}
									},
									400
								);
							}

							// When the gallery-edit state is updated, copy the attachment ids across.
							frame.state( 'gallery-edit' ).on(
								'update',
								function ( selection ) {
									let ids;
									let element;
									let preview_img;

									let preview_html = '';

									// Clear screenshot div so we can append new selected images.
									current_gallery.find( '.screenshot' ).html( '' );

									ids = selection.models.map(
										function ( e ) {
											element = e.toJSON();

											preview_img = ( 'undefined' !== typeof element.sizes && 'undefined' !== typeof element.sizes.thumbnail ) ? element.sizes.thumbnail.url : element.url;

											preview_html = '<a class="of-uploaded-image" href="' + preview_img + '"><img class="redux-option-image" src="' + preview_img + '" alt="" /></a>';
											current_gallery.find( '.screenshot' ).append( preview_html );

											return e.id;
										}
									);

									current_gallery.find( '.gallery_values' ).val( ids.join( ',' ) );

									redux_change( current_gallery.find( '.gallery_values' ) );

									frame.detach();
								}
							);

							return false;
						}
					},
					'.gallery-attachments'
				);
			}
		);
	};
})( jQuery );
