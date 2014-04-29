
/*
 Field Button Set (button_set)
 */

/*global jQuery, document*/

(function($) {
    "use strict";
    
    $(document).ready(function() {
        $('.buttonset').each(function() {
            if ($(this).is(':checkbox')) {
                $(this).find('.buttonset-item').button();
            }
            
            $(this).buttonset();
        });
    });
})(jQuery);