/* global redux_change */
jQuery(document).ready(function() {
	jQuery('.redux_slider').each(function() {
		//slider init
		var slider = redux.slider[jQuery(this).attr('rel')];
	
		jQuery(this).slider({
			value: parseInt(slider.val, null),
			min: parseInt(slider.min, null),
			max: parseInt(slider.max, null),
			step: parseInt(slider.step, null),
			range: "min",
			slide: function(event, ui) {
				var input = jQuery("#" + slider.id);
				input.val(ui.value);	
				redux_change(input);
			}
		});

		// Limit input for negative
		var neg = false;
		if (parseInt(slider.min, null) < 0) {
			neg = true;
		}

		jQuery(".slider-input").numeric({
			allowPlus: false,
			allowMinus: neg,
			min: slider.min,
			max: slider.max
		});

	});
	
	// Update the slider from the input and vice versa
	jQuery(".slider-input").keyup(function() {

		jQuery(this).addClass('sliderInputChange');

	});

	// Update the slider from the input and vice versa
	jQuery(".slider-input").focus(function() {

		if ( !jQuery(this).hasClass('sliderInputChange') ) {
			return;
		}
		jQuery(this).removeClass('sliderInputChange');

		var slider = redux.slider[jQuery(this).attr('id')];
		value = parseInt(jQuery(this).val());
		if (value > slider.max) {
			value = slider.max;
		} else if (value < slider.min) {
			value = slider.min;
		} else {
			value = Math.round(value / slider.step) * slider.step;
		}

		jQuery('#' + slider.id + '-slider').slider("value", value);
		jQuery("#" + slider.id).val(value);

	});

	jQuery('.slider-input').typeWatch({
		callback:function(value){

			if ( !jQuery(this).hasClass('sliderInputChange') ) {
				return;
			}
			jQuery(this).removeClass('sliderInputChange');

			var slider = redux.slider[jQuery(this).attr('id')];
			value = parseInt(jQuery(this).val());
			if (value > slider.max) {
				value = slider.max;
			} else if (value < slider.min) {
				value = slider.min;
			} else {
				value = Math.round(value / slider.step) * slider.step;
			}
			

			jQuery('#' + slider.id + '-slider').slider("value", value);
			jQuery("#" + slider.id).val(value);

		},
		wait:400,
		highlight:false,
		captureLength:1
	});

});