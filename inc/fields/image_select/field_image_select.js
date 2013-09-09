/* global confirm, redux_opts */
jQuery.noConflict();
jQuery(document).ready(function() {
	jQuery('.redux-image-select label').click(function() {
		var id = jQuery(this).attr('for');
		jQuery(this).parents("td:first").find('.redux-image-select-selected').removeClass('redux-image-select-selected');
		jQuery(this).find('input[type="radio"]').prop('checked');
		jQuery('label[for="' + id + '"]').addClass('redux-image-select-selected');
	});
	jQuery('.redux-save-preset').on("click", function(e) {
		e.preventDefault();
		var presets = jQuery(this).parents("td:first").find('input[type="radio"]:checked');
		var data = presets.data('presets');
		if (presets !== undefined && presets !== null) {
			var answer = confirm(redux_opts.preset_confirm);
			if (answer) {
				window.onbeforeunload = null;
				jQuery('#import-code-value').val(JSON.stringify(data));
				jQuery('#redux-import').click();
			}
		}
		return false;
	});
});

// Used to display a full image of a tile/pattern
jQuery(document).ready(function() {
	var xOffset = 10;
	var yOffset = 30;
	// these 2 variable determine popup's distance from the cursor
	// you might want to adjust to get the right result
	/* END CONFIG */
	jQuery(".tiles").hover(function(e) {
		jQuery("body").append("<div id='tilesFullView'><img src='" + jQuery(this).attr('rel') + "' alt='' /></div>");
		jQuery("#tilesFullView").css("top", (e.pageY - xOffset) + "px").css("left", (e.pageX + yOffset) + "px").fadeIn("fast");
	}, function() {
		jQuery("#tilesFullView").remove();
	});
	jQuery(".tiles").mousemove(function(e) {
		jQuery("#tilesFullView").css("top", (e.pageY - xOffset) + "px").css("left", (e.pageX + yOffset) + "px");
	});
});