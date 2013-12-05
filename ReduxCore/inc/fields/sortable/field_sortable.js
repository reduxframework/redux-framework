/*global jQuery, document*/

jQuery(document).ready(function() {
	function triggerSaveNotice() {
		jQuery('#redux-opts-save-warn').slideDown('slow');
	}

	jQuery(".redux-sortable").dragsort({
		dragSelector: ".drag",
		dragBetween: false,
		dragEnd: triggerSaveNotice
	});

	jQuery('.checkbox_sortable').on('click', function() {
		if (jQuery(this).is(":checked")) {
			jQuery('#'+jQuery(this).attr('rel')).val(1);
		} else {
			jQuery('#'+jQuery(this).attr('rel')).val('');
		}
	});


});