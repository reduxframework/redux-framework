(function($) {
    "use strict";

    $(document).ready(function() {

        $(".redux-dimensions-height, .redux-dimensions-width").numeric({
            //allowMinus   : false,
        });

        $(".redux-dimensions-units").select2({
            width: 'resolve',
            triggerChange: true,
            allowClear: true
        });

        $('.redux-dimensions-input').on('change', function() {
            var units = $(this).parents('.redux-field:first').find('.field-units').val();
            if ($(this).parents('.redux-field:first').find('.redux-dimensions-units').length !== 0) {
                units = $(this).parents('.redux-field:first').find('.redux-dimensions-units option:selected').val();
            }
            if (typeof units !== 'undefined') {
                $('#' + $(this).attr('rel')).val($(this).val() + units);
            } else {
                $('#' + $(this).attr('rel')).val($(this).val());
            }
        });

        $('.redux-dimensions-units').on('change', function() {
            $(this).parents('.redux-field:first').find('.redux-dimensions-input').change();
        });
    });
})(jQuery);