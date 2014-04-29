
(function($) {
    "use strict";
    $(function() {
        /**        Sorter (Layout Manager) */
        $('.redux-sorter').each(function() {
            var id = $(this).attr('id');

            $('#' + id).find('ul').sortable({
                items:          'li',
                placeholder:    "placeholder",
                connectWith:    '.sortlist_' + id,
                opacity:        0.6,
                update:         function() {
                    $(this).find('.position').each(function() {
                        var listID      = $(this).parent().attr('id');
                        var parentID    = $(this).parent().parent().attr('id');

                        parentID = parentID.replace(id + '_', '');
                        redux_change($(this));

                        var optionID = $(this).parent().parent().parent().attr('id');

                        $(this).prop("name", redux.args.opt_name + '[' + optionID + '][' + parentID + '][' + listID + ']');
                    });
                }
            });
        });
    });
})(jQuery);