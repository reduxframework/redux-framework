/*global jQuery, document, redux_change */

(function($) {
    "use strict";
    
    $(document).ready(function() {
        $(".redux-sortable").sortable({
            handle: ".drag",
            placeholder: "ui-state-highlight",
            opacity: 0.7,
            update: function() {
                redux_change($(this));
            }
        });

        $('.checkbox_sortable').on('click', function() {
            if ($(this).is(":checked")) {
                $('#' + $(this).attr('rel')).val(1);
            } else {
                $('#' + $(this).attr('rel')).val('');
            }
        });
    });
})(jQuery);