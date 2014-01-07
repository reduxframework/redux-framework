/*global jQuery, document, redux_change */
(function($){
	'use strict';

	$.redux = $.redux || {};

	var tcolour; 

	$(document).ready(function(){
		$.redux.colorrgba();
	});

	$.redux.colorrgba = function(){
		$('.redux-colorrgba-init').minicolors({
			animationSpeed: 50,
			animationEasing: 'swing',
			inline: false,
			letterCase: 'lowercase',
			opacity: true,
			//theme: 'bootstrap',
			change: function(hex, opacity) {
				//console.log(hex + ' - ' + opacity);
				redux_change($(this));
				//$('#' + this.id + '-transparency').removeAttr('checked');
				//console.group("Trace"); 
				//console.log( $('#' + this.id + '-transparency').prop('checked') );
				//console.groupEnd();
				$('#' +$(this).data('id')+ '-transparency').removeAttr('checked');
				$('#' +$(this).data('id')+ '-alpha').val(opacity);
				//console.log('#' + this.id + '-transparency');
				//console.log($(this).minicolors('rgbaString'));
			}
		});

		$('.redux-colorrgba').on('focus', function() {
			$(this).data('oldcolor', $(this).val());
		});

		$('.redux-colorrgba').on('keyup', function() {
			var value = $(this).val();
			var color = redux_colorrgba_validate(this);
			var id = '#' + $(this).attr('id');
			if (value === "transparent") {
				$(this).parent().parent().find('.wp-color-result').css('background-color', 'transparent');
				$(id + '-transparency').attr('checked', 'checked');
			} else {
				$(id + '-transparency').removeAttr('checked');
				if (color && color !== $(this).val()) {
					$(this).val(color);
				}
			}
		});

		// Replace and validate field on blur
		$('.redux-colorrgba').on('blur', function() {
			var value = $(this).val();
			var id = '#' + $(this).attr('id');
			if (value === "transparent") {
				$(this).parent().parent().find('.wp-color-result').css('background-color', 'transparent');
				$(id + '-transparency').attr('checked', 'checked');
			} else {
				if (redux_color_validate(this) === value) {
					if (value.indexOf("#") !== 0) {
						$(this).val($(this).data('oldcolor'));
					}
				}
				$(id + '-transparency').removeAttr('checked');
			}
		});

		// Store the old valid color on keydown
		$('.redux-colorrgba').on('keydown', function() {
			$(this).data('oldkeypress', $(this).val());
		});

				// When transparency checkbox is clicked
		$('.colorrgba-transparency').on('click', function() {
			if ($(this).is(":checked")) {
				$('#' + $(this).data('id')).val('transparent');
				$('#' + $(this).data('id')).parent().parent().find('.wp-color-result').css('background-color', 'transparent');
			} else {
				if ($('#' + $(this).data('id')).val() === 'transparent') {
					$('#' + $(this).data('id')).val('');
				}
			}
		});
	};

})(jQuery);



// Run the validation
function redux_colorrgba_validate(field) {
	var value = jQuery(field).val();
	/*
	if (colourNameToHex(value) !== value.replace('#', '')) {
		return colourNameToHex(value);
	}
	*/
	return value;
}
