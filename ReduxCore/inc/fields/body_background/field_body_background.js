
/*
	Field BodyBackground Color (color)
 */

function updateBGColor(myid)
{
		var value = jQuery(myid).val();
		var bgpreview = jQuery(myid).parents('.redux-field:first').find('.redux-bgpreview-image');
		bgpreview.css("background-color", value);
}

function updateBGRepeat(myid)
{
		var value = jQuery(myid).val();
		var bgpreview = jQuery(myid).parents('.redux-field:first').find('.redux-bgpreview-image');
		bgpreview.css("background-repeat", value);
}

function updateBGposition(myid)
{
		var value = jQuery(myid).val();
		var bgpreview = jQuery(myid).parents('.redux-field:first').find('.redux-bgpreview-image');
		bgpreview.css("background-position", value);
}

function updateBGattachment(myid)
{
		var value = jQuery(myid).val();
		var bgpreview = jQuery(myid).parents('.redux-field:first').find('.redux-bgpreview-image');
		bgpreview.css("background-attachment", value);
}

function updateBGorigin(myid)
{
		var value = jQuery(myid).val();
		var bgpreview = jQuery(myid).parents('.redux-field:first').find('.redux-bgpreview-image');
		bgpreview.css("background-origin", value);
}

function updateBGclip(myid)
{
		var value = jQuery(myid).val();
		var bgpreview = jQuery(myid).parents('.redux-field:first').find('.redux-bgpreview-image');
		bgpreview.css("background-clip", value);
}

function updateBGsize(myid)
{
		var value = jQuery(myid).val();
		var bgpreview = jQuery(myid).parents('.redux-field:first').find('.redux-bgpreview-image');
		bgpreview.css("background-size", value);
}

// Add a file via the wp.media function
function redux_add_bgfile(event, selector) {

	event.preventDefault();

	var frame;
	var jQueryel = jQuery(this);
	


	// If the media frame already exists, reopen it.
	if ( frame ) {
		frame.open();
		return;
	}

	// Create the media frame.
	frame = wp.media({
		multiple: false,
		library: {
			//type: 'image' //Only allow images
		},
		// Set the title of the modal.
		title: jQueryel.data('choose'),

		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: jQueryel.data('update')
			// Tell the button not to close the modal, since we're
			// going to refresh the page when the image is selected.

		}
	});

	// When an image is selected, run a callback.
	frame.on( 'select', function() {

		// Grab the selected attachment.
		var attachment = frame.state().get('selection').first();
		frame.close();

		if ( typeof redux.media[jQuery(selector).attr('data-id')] === 'undefined' ) {
			redux.media[jQuery(selector).attr('data-id')] = {};
			redux.media[jQuery(selector).attr('data-id')].mode = "image";
		}

		if ( redux.media[jQuery(selector).attr('data-id')].mode !== false && attachment.attributes.type !== redux.media[jQuery(selector).attr('data-id')].mode) {
			return;
		}

		selector.find('.bgupload').val(attachment.attributes.url);
		selector.find('.bgupload-id').val(attachment.attributes.id);
		selector.find('.bgupload-height').val(attachment.attributes.height);
		selector.find('.bgupload-width').val(attachment.attributes.width);
		redux_change( jQuery(selector).find( '.bgupload-id' ) );
		var thumbSrc = attachment.attributes.url;
		if (typeof attachment.attributes.sizes !== 'undefined' && typeof attachment.attributes.sizes.thumbnail !== 'undefined') {
			thumbSrc = attachment.attributes.sizes.thumbnail.url;
		} else if ( typeof attachment.attributes.sizes !== 'undefined' ) {
			var height = attachment.attributes.height;
			for (var key in attachment.attributes.sizes) {
				var object = attachment.attributes.sizes[key];
				if (object.height < height) {
					height = object.height;
					thumbSrc = object.url;
				}
			}
		} else {
			thumbSrc = attachment.attributes.icon;
		}
		selector.find('.bgupload-thumbnail').val(thumbSrc);
		if ( !selector.find('.bgupload').hasClass('noPreview') ) {
			selector.find('.bgscreenshot').empty().hide().append('<img class="redux-option-bgimage" src="' + thumbSrc + '">').slideDown('fast');
		}
		//selector.find('.media_upload_button').unbind();
		selector.find('.remove-bgimage').removeClass('hide');//show "Remove" button
		selector.find('.redux-background-properties').slideDown();


		var bgpreview =  selector.find('.redux-bgpreview-image');
		bgpreview.css("background-image", 'url(' +attachment.attributes.url+ ')');
	});

	// Finally, open the modal.
	frame.open();

}


// Function to remove the image on click. Still requires a save
function redux_remove_bgfile(selector) {

	// This shouldn't have been run...
	if (!selector.find('.remove-bgimage').addClass('hide')) {
		return;
	}
	selector.find('.remove-bgimage').addClass('hide');//hide "Remove" button
	selector.find('.bgupload').val('');
	selector.find('.bgupload-id').val('');
	selector.find('.bgupload-height').val('');
	selector.find('.bgupload-width').val('');
	redux_change( jQuery(selector).find( '.bgupload-id' ) );
	selector.find('.redux-background-properties').hide();
	var screenshot = selector.find('.bgscreenshot');
	
	// Hide the screenshot
	screenshot.slideUp();

	selector.find('.remove-bgfile').unbind();
	// We don't display the upload button if .upload-notice is present
	// This means the user doesn't have the WordPress 3.5 Media Library Support
	if ( jQuery('.section-upload .upload-notice').length > 0 ) {
		jQuery('.bgmedia_upload_button').remove();
	}

	var bgpreview =  selector.find('.redux-bgpreview-image');
	bgpreview.css("background-image", "");
}

/*global jQuery, document, redux_change */
(function($){
	'use strict';

	$.redux = $.redux || {};

	var tcolour; 

	$(document).ready(function(){
		$.redux.bgcolor();
		$.redux.bgmedia();
		
		$.redux.bgrepeat();
		$.redux.bgposition();
		$.redux.bgattachment();

		$.redux.bgorigin();
		$.redux.bgclip();
		$.redux.bgsize();

	});

	$.redux.bgcolor = function(){

		$('.redux-bgcolor-init').wpColorPicker({
			change: function(u) {
				redux_change($(this));
				updateBGColor(this);
			}
		});

		$('.redux-bgcolor').on('focus', function() {
			$(this).data('oldcolor', $(this).val());
			var id = '#' + $(this).attr('id');
			updateBGColor(this);
		});

		$('.redux-bgcolor').on('keyup', function() {
			var value = $(this).val();
			var color = redux_color_validate(this);
			var id = '#' + $(this).attr('id');
			updateBGColor(this);
		});

		// Replace and validate field on blur
		$('.redux-bgcolor').on('blur', function() {
			var value = $(this).val();
			var id = '#' + $(this).attr('id');
			updateBGColor(this);
		});

		// Store the old valid color on keydown
		$('.redux-bgcolor').on('keydown', function() {
			$(this).data('oldkeypress', $(this).val());
			updateBGColor(this);
		});

	};

	$.redux.bgmedia = function(){
			// Remove the image button
			$('.remove-bgimage, .remove-bgfile').unbind('click').on('click', function() {
				redux_remove_bgfile( $(this).parents('fieldset.redux-field:first') );
			});

			// Upload media button
			$('.bgmedia_upload_button').unbind().on('click', function( event ) {
				redux_add_bgfile( event, $(this).parents('fieldset.redux-field:first') );
			});
	};

	$.redux.bgrepeat = function(){
		$('.redux-bgrepeat-input').on('change', function() {
			updateBGRepeat(this);
		});
	};
	
	$.redux.bgposition = function(){
		$('.redux-bgposition-input').on('change', function() {
			updateBGposition(this);
		});
	};
	
	$.redux.bgattachment = function(){
		$('.redux-bgattachment-input').on('change', function() {
			updateBGattachment(this);
		});
	};
	
	$.redux.bgorigin = function(){
		$('.redux-bgorigin-input').on('change', function() {
			updateBGorigin(this);
		});
	};
	
	$.redux.bgclip = function(){
		$('.redux-bgclip-input').on('change', function() {
			updateBGclip(this);
		});
	};
	
	$.redux.bgsize = function(){
		$('.redux-bgsize-input').on('change', function() {
			updateBGsize(this);
		});
	};
	
})(jQuery);

