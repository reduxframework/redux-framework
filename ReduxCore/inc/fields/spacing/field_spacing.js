(function($) {
    "use strict";

    $.reduxSpacing = $.reduxSpacing || {};

    $(document).ready(function() {
        $.reduxSpacing.init();
    });

    $.reduxSpacing.init = function () {
        var default_params = {
            width:          'resolve',
            triggerChange:  true,
            allowClear:     true
        };

        var select2_handle = $('.redux-container-spacing').find('.select2_params');
        if (select2_handle.size() > 0) {
            var select2_params = select2_handle.val();

            select2_params = JSON.parse(select2_params);
            default_params = $.extend({}, default_params, select2_params);
        }

        $(".redux-spacing-units").select2(default_params);

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
    };
})(jQuery);