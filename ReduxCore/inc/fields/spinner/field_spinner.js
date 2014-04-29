/* global redux_change */

(function($) {
    "use strict";

    $(document).ready(function() {

        $('.redux_spinner').each(function() {
            //slider init
            var spinner = redux.spinner[$(this).attr('rel')];

            $("#" + spinner.id).spinner({
                value:  parseInt(spinner.val, null),
                min:    parseInt(spinner.min, null),
                max:    parseInt(spinner.max, null),
                step:   parseInt(spinner.step, null),
                range:  "min",

                slide: function(event, ui) {
                    var input = $("#" + spinner.id);
                    input.val(ui.value);
                    redux_change(input);
                }
            });

            // Limit input for negative
            var neg = false;
            if (parseInt(spinner.min, null) < 0) {
                neg = true;
            }

            $("#" + spinner.id).numeric({
                allowMinus: neg,
                min:        spinner.min,
                max:        spinner.max
            });

        });

        // Update the slider from the input and vice versa
        $(".spinner-input").keyup(function() {
            $(this).addClass('spinnerInputChange');
        });

        function cleanSpinnerValue(value, selector, spinner) {

            if (!selector.hasClass('spinnerInputChange')) {
                return;
            }
            selector.removeClass('spinnerInputChange');

            if (value === "" || value === null) {
                value = spinner.min;
            } else if (value >= parseInt(spinner.max)) {
                value = spinner.max;
            } else if (value <= parseInt(spinner.min)) {
                value = spinner.min;
            } else {
                value = Math.round(value / spinner.step) * spinner.step;
            }

            $("#" + spinner.id).val(value);

        }

        // Update the spinner from the input and vice versa
        $(".spinner-input").blur(function() {
            //        cleanSpinnerValue(jQuery(this).val(), jQuery(this), redux.spinner[jQuery(this).attr('id')]);
        });

        $(".spinner-input").focus(function() {
            cleanSpinnerValue($(this).val(), $(this), redux.spinner[$(this).attr('id')]);
        });

        $('.spinner-input').typeWatch({
            callback: function(value) {
                cleanSpinnerValue(value, $(this), redux.spinner[$(this).attr('id')]);
            },

            wait:           500,
            highlight:      false,
            captureLength:  1
        });
    });
})(jQuery);