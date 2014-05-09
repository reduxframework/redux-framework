/*
 Field Border (border)
 */

(function($) {
    "use strict";
    $(document).ready(function() {

        $(".redux-border-top, .redux-border-right, .redux-border-bottom, .redux-border-left, .redux-border-all").numeric({
            allowMinus: false,
        });

        $(".redux-border-style").select2({
            triggerChange: true,
            allowClear: true
        });

        $('.redux-border-input').on('change', function() {
            var units = $(this).parents('.redux-field:first').find('.field-units').val();
            if ($(this).parents('.redux-field:first').find('.redux-border-units').length !== 0) {
                units = $(this).parents('.redux-field:first').find('.redux-border-units option:selected').val();
            }
            var value = $(this).val();
            if (typeof units !== 'undefined' && value) {
                value += units;
            }
            if ($(this).hasClass('redux-border-all')) {
                $(this).parents('.redux-field:first').find('.redux-border-value').each(function() {
                    $(this).val(value);
                });
            } else {
                $('#' + $(this).attr('rel')).val(value);
            }
        });
        $('.redux-border-units').on('change', function() {
            $(this).parents('.redux-field:first').find('.redux-border-input').change();
        });

    });
})(jQuery);