
/*
	Field Button Set (button_set)
 */

/*global jQuery, document*/
jQuery(document).ready(function () {
    jQuery('.buttonset').each(function() {
        if ( jQuery(this).is(':checkbox') ) {
            jQuery(this).find('.buttonset-item').button();
        } 
        jQuery(this).buttonset();
    });  
});
