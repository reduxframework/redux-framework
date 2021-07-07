/* global jQuery, redux_change, redux */

(function( $ ) {
	'use strict';

	redux.field_objects                      = redux.field_objects || {};
	redux.field_objects.gradient_filters = redux.field_objects.gradient_filters || {};

	redux.field_objects.gradient_filters.sliderInit = function( el, mode ) {
		el.find( '.redux-gradient-slider' ).each(
			function() {
				var mainID = $( this ).data( 'id' );
				var minVal = $( this ).data( 'min' );
				var maxVal = $( this ).data( 'max' );
				var step   = $( this ).data( 'step' );
				var def    = $( this ).data( 'default' );
				var label  = $( this ).data( 'label' );
				var rtl    = Boolean( $( this ).data( 'rtl' ) );
				var range  = [minVal, maxVal];

				var slider = $( this ).reduxNoUiSlider(
					{
						range: range,
						start: def,
						handles: 1,
						step: step,
						connect: 'lower',
						behaviour: 'tap-drag',
						rtl: rtl,
						serialization: {
							resolution: 1
						},
						slide: function() {
							var sliderID = $( this ).data( 'id' );
							var unit;

							if ( sliderID.indexOf( 'angle' ) !== -1 ) {
								unit = '&deg;';
							} else {
								unit = '%';
							}

							$( this ).next( '#redux-slider-value-' + mainID ).attr(
								'value',
								slider.val()
							);

							$( this ).prev( 'label' ).html(
								label + ':  <strong>' + slider.val() + unit + '</strong>'
							);

							redux.field_objects.gradient_filters.changeValue( $( this ), true, mode );
						}
					}
				);
			}
		);
	};

	redux.field_objects.gradient_filters.selectChange = function( el, mode ) {
		$( el ).find( '.redux-gradient-select' ).on(
			'change',
			function() {
				var type  = $( this ).val();
				var angle = el.find( '.slider-gradient-angle' );

				if ( 'linear' === type ) {
					angle.fadeIn();
				} else {
					angle.fadeOut();
				}

				redux.field_objects.gradient_filters.changeValue( $( this ), true, mode );
			}
		);

		el.find( '.redux-gradient-select' ).select2();

	};

	redux.field_objects.gradient_filters.changeValue = function( el, update, mode ) {
		var parent  = el.parents( '.redux-container-' + mode );
		var mainID  = parent.data( 'id' );
		var preview = parent.find( '.redux-gradient-preview' );

		var hide = preview.css( 'display' );

		var colorFrom = parent.find( '#' + mainID + '-from' ).val();
		var colorTo   = parent.find( '#' + mainID + '-to' ).val();

		var type = parent.find( '.redux-gradient-select' ).val();

		var result_w3c = '';
		var result     = '';

		var angle     = 0;
		var fromReach = 0;
		var toReach   = 100;

		var w3c_deg = Math.abs( angle - 450 ) % 360;

		var colors;

		fromReach = parent.find( '#redux-slider-value-' + mainID + '-from' ).val();
		toReach   = parent.find( '#redux-slider-value-' + mainID + '-to' ).val();
		angle     = parent.find( '#redux-slider-value-' + mainID + '-angle' ).val();

		colors = colorFrom + ' ' + fromReach + '%, ' + colorTo + ' ' + toReach + '%)';

		if ( 'linear' === type ) {
			result_w3c = 'linear-gradient(' + w3c_deg + 'deg,' + colors;
			result     = 'linear-gradient(' + angle + 'deg,' + colors;
		} else {
			result_w3c = 'radial-gradient(center, ellipse cover,' + colors;
			result     = 'radial-gradient(center, ellipse cover,' + colors;
		}

		if ( 'none' === hide ) {
			preview.fadeIn();
		}

		preview.css( 'background', result_w3c );
		preview.css( 'background', '-moz-' + result );
		preview.css( 'background', '-webkit-' + result );
		preview.css( 'background', '-o-' + result );
		preview.css( 'background', '-ms-' + result );

		if ( update ) {
			redux_change( el );
		}
	};
})( jQuery );
