/*global jQuery*/

(function($) {
    "use strict";

    $.reduxDate = $.reduxDate || {};

    $(document).ready(function() {
        $.reduxDate.init();
    });
    
    $.reduxDate.init = function() {
        $('.redux-datepicker').each(function() {
            $(this).datepicker();
        });
    };
})(jQuery);