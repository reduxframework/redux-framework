/* global redux_change */
jQuery(document).ready(function() {

    jQuery('.redux_spinner').each(function() {
        //slider init
        var spinner = redux.spinner[jQuery(this).attr('rel')];

        jQuery("#" + spinner.id).spinner({
            value: parseInt(spinner.val, null),
            min: parseInt(spinner.min, null),
            max: parseInt(spinner.max, null),
            step: parseInt(spinner.step, null),
            range: "min",
            slide: function(event, ui) {
                var input = jQuery("#" + spinner.id);
                input.val(ui.value);
                redux_change(input);
            }
        });

        // Limit input for negative
        var neg = false;
        if (parseInt(spinner.min, null) < 0) {
            neg = true;
        }

		jQuery(".spinner-input").numeric({
			allowMinus: neg,
			min: spinner.min,
			max: spinner.max
		});

    });

    // Update the slider from the input and vice versa
    jQuery(".spinner-input").keyup(function() {

        jQuery(this).addClass('spinnerInputChange');

    });

    // Update the slider from the input and vice versa
    jQuery(".spinner-input").focus(function() {

        if (!jQuery(this).hasClass('spinnerInputChange')) {
            return;
        }
        jQuery(this).removeClass('spinnerInputChange');

        var spinner = redux.spinner[jQuery(this).attr('id')];
        value = jQuery(this).val();
        if (value > spinner.max) {
            value = spinner.max;
        } else if (value < spinner.min) {
            value = spinner.min;
        } else {
            value = Math.round(value / spinner.step) * spinner.step;
        }

        jQuery('#' + spinner.id).spinner("value", value);

    });

    jQuery('.spinner-input').typeWatch({
        callback: function(value) {

            if (!jQuery(this).hasClass('spinnerInputChange')) {
                return;
            }
            jQuery(this).removeClass('spinnerInputChange');

            var spinner = redux.spinner[jQuery(this).attr('id')];
            value = jQuery(this).val();
            if (value > spinner.max) {
                value = spinner.max;
            } else if (value < spinner.min) {
                value = spinner.min;
            } else {
                value = Math.round(value / spinner.step) * spinner.step;
            }

            jQuery('#' + spinner.id).spinner("value", value);

        },
        wait: 400,
        highlight: false,
        captureLength: 1
    });

});
