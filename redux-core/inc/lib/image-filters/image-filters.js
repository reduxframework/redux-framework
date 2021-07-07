/* global jQuery, redux_change, redux */

(function( $ ) {
	'use strict';

	redux.field_objects               = redux.field_objects || {};
	redux.field_objects.image_filters = redux.field_objects.image_filters || {};

	redux.field_objects.image_filters.sliderInit = function( el, mode ) {
		el.find( '.redux-' + mode + '-slider' ).each(
			function() {
				var mainID = $( this ).data( 'id' );
				var minVal = $( this ).data( 'min' );
				var maxVal = $( this ).data( 'max' );
				var step   = $( this ).data( 'step' );
				var def    = $( this ).data( 'default' );
				var unit   = $( this ).data( 'unit' );
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
						unit: unit,
						serialization: {
							resolution: step
						},
						slide: function() {
							var val = slider.val();

							if ( '0.00' === val ) {
								val = 0;
							} else if ( '1.00' === val ) {
								val = 1;
							}

							$( this ).next( '#redux-slider-value-' + mainID ).attr(
								'value',
								val
							);

							$( this ).prev( 'label' ).find( 'span' ).html(
								'<strong>' + val + unit + '</strong>'
							);

							redux.field_objects.image_filters.changeValue( $( this ), true, mode );
						}
					}
				);
			}
		);
	};

	redux.field_objects.image_filters.changeValue = function( el, update, mode ) {
		var parent    = el.parents( '.redux-container-' + mode );
		var container = parent.find( '.redux-' + mode + '-filter-container' );

		var filterCSS = '';

		var isChecked;
		var mainID;
		var preview;
		var img;
		var filters;
		var val;
		var unit;
		var hide;

		if ( container.length > 0 ) {
			mainID  = parent.data( 'id' );
			preview = parent.find( '.screenshot' );
			img     = preview.find( 'img' );
			filters = container.data( 'filters' );

			filters = decodeURIComponent( filters );
			filters = JSON.parse( filters );

			$.each(
				filters,
				function( idx, filter ) {
					isChecked = container.find( '#' + mainID + '-' + filter ).is( ':checked' );

					if ( true === isChecked ) {
						val  = container.find( '#redux-slider-value-' + mainID + '-' + filter ).val();
						unit = container.find( '#redux-slider-value-' + mainID + '-' + filter ).data( 'unit' );

						filterCSS = filterCSS + ' ' + filter + '(' + val + unit + ')';
					}
				}
			);

			img.css(
				{
					'filter': filterCSS,
					'-webkit-filter': filterCSS
				}
			);

			hide = preview.css( 'display' );

			if ( 'none' === hide ) {
				preview.fadeIn();
			}

			if ( update ) {
				redux_change( el );
			}
		}
	};

	redux.field_objects.image_filters.checkbox = function( el, mode ) {
		el.find( '.checkbox' ).on(
			'click',
			function() {
				var val    = 0;
				var slider = $( this ).parent().next( '.redux-' + mode + '-slider' );
				var label  = $( this ).parent( 'label' );

				if ( $( this ).is( ':checked' ) ) {
					val = $( this ).parent().find( '.checkbox-check' ).attr( 'data-val' );

					slider.attr( 'disabled', false );
					label.removeClass( 'pro-disabled' );
				} else {
					slider.attr( 'disabled', true );
					label.addClass( 'pro-disabled' );
				}

				$( this ).parent().find( '.checkbox-check' ).val( val );

				redux.field_objects.image_filters.changeValue( $( this ), true, mode );
			}
		);
	};
} )( jQuery );
