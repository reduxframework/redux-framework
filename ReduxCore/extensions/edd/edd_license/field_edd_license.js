/*global jQuery, document */
(function($){
	'use strict';

	$.redux = $.redux || {};

	$(document).ready(function(){
		$.redux.edd();
	});

	$.redux.edd = function(){

		jQuery('.redux-edd-input').change(function() {
			jQuery(this).parent().find('.redux-edd-status').val('');
		});
		jQuery( document ).on( "click", ".redux-EDDAction", function(e) {
			e.preventDefault();
			var parent = jQuery(this).parents('.redux-container-edd_license:first');
			var id = jQuery(this).attr('data-id');
			
			var theData = {};
			parent.find('.redux-edd').each(function() {
				theData[jQuery(this).attr('id').replace(id+'-', '')] = jQuery(this).val();
			});
			theData['edd_action'] = jQuery(this).attr('data-edd_action');
			theData['opt_name'] = redux_opts.opt_name;
			
			jQuery.post(
			    ajaxurl, {
			        'action': 'redux_edd_'+redux_opts.opt_name+'_license',
			        'data': theData
			    },
			    function(response) {
			    	response = jQuery.parseJSON(response);
			    	console.log(response);
			    	jQuery('#'+id+'-status').val(response.status);
			    	jQuery('#'+id+'-status_notice').html(response.status);
			    	if (response.response === "valid") {
			    		//jQuery('#'+id+'-notice').switchClass( "big", "blue", 1000, "easeInOutQuad" );
			    		jQuery('#'+id+'-notice').attr('class', "redux-info-field redux-success" );
			    	} else if (response.response === "deactivated") {
			    		jQuery('#'+id+'-notice').attr('class', "redux-info-field redux-warning" );
			    	} else { // Inactive or bad

			    	}
			        
			    }
			);			
		});
				
	}

})(jQuery);
