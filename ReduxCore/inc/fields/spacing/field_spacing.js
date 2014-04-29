(function($) {
    "use strict";

    $(document).ready(function() {

        $(".redux-spacing-top, .redux-spacing-right, .redux-spacing-bottom, .redux-spacing-left, .redux-spacing-all").numeric({
            //allowMinus   : false,
        });

        $(".redux-spacing-units").select2({
            width:          '80px',
            triggerChange:  true,
            allowClear:     true
        });

        $('.redux-spacing-input').on('change', function() {
            var units = $(this).parents('.redux-field:first').find('.field-units').val();

            if ($(this).parents('.redux-field:first').find('.redux-spacing-units').length !== 0) {
                units = $(this).parents('.redux-field:first').find('.redux-spacing-units option:selected').val();
            }

            var value = $(this).val();

            if (typeof units !== 'undefined' && value) {
                value += units;
            }

            if ($(this).hasClass('redux-spacing-all')) {
                $(this).parents('.redux-field:first').find('.redux-spacing-value').each(function() {
                    $(this).val(value);
                });
            } else {
                $('#' + $(this).attr('rel')).val(value);
            }
        });
        $('.redux-spacing-units').on('change', function() {
            $(this).parents('.redux-field:first').find('.redux-spacing-input').change();
        });

    });
})(jQuery);