/* global jQuery, redux_change, redux */

(function ( $ ) {
	'use strict';

	redux.field_objects               = redux.field_objects || {};
	redux.field_objects.image_filters = redux.field_objects.image_filters || {};

	redux.field_objects.image_filters.sliderInit = function ( el, mode ) {
		el.find( '.redux-' + mode + '-slider' ).each(
			function () {
				let mainID = $( this ).data( 'id' );
				let minVal = $( this ).data( 'min' );
				let maxVal = $( this ).data( 'max' );
				let step   = $( this ).data( 'step' );
				let def    = $( this ).data( 'default' );
				let unit   = $( this ).data( 'unit' );
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
						unit: unit,
						serialization: {
							resolution: step
						},
						slide: function () {
							let val = slider.val();

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

	redux.field_objects.image_filters.changeValue = function ( el, update, mode ) {
		let parent    = el.parents( '.redux-container-' + mode );
		let container = parent.find( '.redux-' + mode + '-filter-container' );

		let filterCSS = '';

		let isChecked;
		let mainID;
		let preview;
		let img;
		let filters;
		let val;
		let unit;
		let hide;

		if ( container.length > 0 ) {
			mainID  = parent.data( 'id' );
			preview = parent.find( '.screenshot' );
			img     = preview.find( 'img' );
			filters = container.data( 'filters' );

			filters = decodeURIComponent( filters );
			filters = JSON.parse( filters );

			$.each(
				filters,
				function ( idx, filter ) {
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

	redux.field_objects.image_filters.checkbox = function ( el, mode ) {
		el.find( '.checkbox' ).on(
			'click',
			function () {
				let val    = 0;
				let slider = $( this ).parent().next( '.redux-' + mode + '-slider' );
				let label  = $( this ).parent( 'label' );

				if ( $( this ).is( ':checked' ) ) {
					val = $( this ).parent().find( '.checkbox-check' ).attr( 'data-val' );

					slider.attr( 'disabled', false );
					label.removeClass( 'filters-disabled' );
				} else {
					slider.attr( 'disabled', true );
					label.addClass( 'filters-disabled' );
				}

				$( this ).parent().find( '.checkbox-check' ).val( val );

				redux.field_objects.image_filters.changeValue( $( this ), true, mode );
			}
		);
	};
} )( jQuery );
