/* global confirm, sof_opts */

jQuery.noConflict();

jQuery(document).ready(function(){

	jQuery('.redux-images label').click(function() {

		var id = jQuery(this).attr('for');
		
		jQuery(this).parent().parent().find('.redux-images-selected').removeClass('redux-images-selected');

		jQuery(this).find('input[type="radio"]').prop('checked');
		jQuery('label[for="'+id+'"]').addClass('redux-images-selected');
		
	});

	jQuery('.redux-save-preset').on("click",function(e) {
		e.preventDefault();
		var presets = jQuery(this).parent().parent().find('.redux-presets label.redux-images-selected input[type="radio"]');
		var data = presets.data('presets');
		if (typeof(presets) !== undefined && presets !== null) {
			var answer = confirm(sof_opts.preset_confirm);
			if (answer){
				window.onbeforeunload = null;
				jQuery('#import-code-value').val(JSON.stringify(data));
				jQuery('#redux-import').click();
			}
		}
		return false;
	});

});