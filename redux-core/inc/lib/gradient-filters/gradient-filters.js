/* global jQuery, redux_change, redux */

(function ( $ ) {
	'use strict';

	redux.field_objects                  = redux.field_objects || {};
	redux.field_objects.gradient_filters = redux.field_objects.gradient_filters || {};

	redux.field_objects.gradient_filters.sliderInit = function ( el, mode ) {
		el.find( '.redux-gradient-slider' ).each(
			function () {
				let mainID = $( this ).data( 'id' );
				let minVal = $( this ).data( 'min' );
				let maxVal = $( this ).data( 'max' );
				let step   = $( this ).data( 'step' );
				let def    = $( this ).data( 'default' );
				let label  = $( this ).data( 'label' );
				let rtl    = Boolean( $( this ).data( 'rtl' ) );
				let range  = [minVal, maxVal];

				let slider = $( this ).reduxNoUiSlider(
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
						slide: function () {
							let sliderID = $( this ).data( 'id' );
							let unit;

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

	redux.field_objects.gradient_filters.selectChange = function ( el, mode ) {
		$( el ).find( '.redux-gradient-select' ).on(
			'change',
			function () {
				let type  = $( this ).val();
				let angle = el.find( '.slider-gradient-angle' );

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

	redux.field_objects.gradient_filters.changeValue = function ( el, update, mode ) {
		let parent  = el.parents( '.redux-container-' + mode );
		let mainID  = parent.data( 'id' );
		let preview = parent.find( '.redux-gradient-preview' );

		let hide = preview.css( 'display' );

		let colorFrom = parent.find( '#' + mainID + '-from' ).val();
		let colorTo   = parent.find( '#' + mainID + '-to' ).val();

		let type = parent.find( '.redux-gradient-select' ).val();

		let result_w3c;
		let result;
		let fromReach;
		let toReach;
		let colors;

		let angle   = 0;
		let w3c_deg = Math.abs( angle - 450 ) % 360;

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
