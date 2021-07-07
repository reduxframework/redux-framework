/*
	Field Box Shadow (box_shadow)
	Author:  Kevin Provnace (kprovance)
 */

/* global jQuery, document, redux, redux_change */

(function( $ ) {
	'use strict';

	redux.field_objects            = redux.field_objects || {};
	redux.field_objects.box_shadow = redux.field_objects.box_shadow || {};

	redux.field_objects.box_shadow.init = function( selector ) {
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-box_shadow:visible' );
		}

		$( selector ).each(
			function() {
				var el     = $( this );
				var parent = el;
				var parent_el;

				if ( ! el.hasClass( 'redux-field-container' ) ) {
					parent = el.parents( '.redux-field-container:first' );
				}

				if ( parent.is( ':hidden' ) ) {
					return;
				}

				if ( parent.hasClass( 'redux-field-init' ) ) {
					parent.removeClass( 'redux-field-init' );
				} else {
					return;
				}

				parent_el = el;

				redux.field_objects.box_shadow.colorPicker( el );
				redux.field_objects.box_shadow.sliderInit( el );

				el.find( '.checkbox' ).on(
					'click',
					function() {
						var val    = 0;
						var col    = $( this ).parents( '.col-2' );
						var slider = col.find( '.redux-box-shadow-slider' );
						var color  = col.find( '.wp-picker-container' );
						var label  = $( this ).parent( 'label' );

						if ( $( this ).is( ':checked' ) ) {
							val = $( this ).parent().find( '.checkbox-check' ).attr( 'data-val' );
							color.removeClass( 'disabled' );
							slider.attr( 'disabled', false );
							label.removeClass( 'pro-disabled' );
						} else {
							color.addClass( 'disabled' );
							slider.attr( 'disabled', true );
							label.addClass( 'pro-disabled' );
						}

						$( this ).parent().find( '.checkbox-check' ).val( val );

						redux.field_objects.box_shadow.changeValue( $( this ), true );
					}
				);
			}
		);
	};

	redux.field_objects.box_shadow.colorPicker = function( el ) {
		var colorPicker = el.find( '.redux-color-init' ).wpColorPicker(
			{
				change: function( e, ui ) {
					$( this ).val( ui.color.toString() );

					redux.field_objects.box_shadow.changeValue( $( this ), true );
				},
				clear: function( e, ui ) {
					$( this ).val( ui.color.toString() );

					redux.field_objects.box_shadow.changeValue( $( this ).parent().find( '.redux-color-init' ), true );
				}
			}
		);

		var id = el.data( 'id' );

		colorPicker.each(
			function() {
				var column    = $( this ).parents( '.col-2' );
				var shadow    = column.data( 'shadow' );
				var label     = column.find( 'label[for="' + id + '-' + shadow + '-shadow"]' );
				var container = column.find( '.wp-picker-container' );

				if ( label.hasClass( 'pro-disabled' ) ) {
					container.addClass( 'disabled' );
				}
			}
		);
	};

	redux.field_objects.box_shadow.sliderInit = function( el ) {
		el.find( '.redux-box-shadow-slider' ).each(
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
							resolution: step
						},
						slide: function() {
							var val = slider.val();

							$( this ).next( '#redux-slider-value-' + mainID ).attr( 'value', val );

							$( this ).prev( 'label' ).html(
								label + ':  <strong>' + val + 'px</strong>'
							);

							redux.field_objects.box_shadow.changeValue( $( this ), true );
						}
					}
				);
			}
		);
	};

	redux.field_objects.box_shadow.changeValue = function( el, update ) {
		var parent    = el.parents( '.redux-container-box_shadow' );
		var container = parent.find( '.box-shadow-controls' );
		var inset     = container.find( '.shadow-inset' );
		var drop      = container.find( '.shadow-drop' );

		var mainID  = parent.data( 'id' );
		var preview = parent.find( '#shadow-result' );
		var css     = '';

		var insetColor;
		var insetH;
		var insetV;
		var insetB;
		var insetS;
		var dropColor;
		var dropH;
		var dropV;
		var dropB;
		var dropS;

		if ( inset.length > 0 ) {
			if ( inset.find( '.checkbox' ).is( ':checked' ) ) {
				insetColor = parent.find( '#' + mainID + '-inset-color' ).val();
				insetH     = parent.find( '#redux-slider-value-' + mainID + '-inset-horizontal' ).val();
				insetV     = parent.find( '#redux-slider-value-' + mainID + '-inset-vertical' ).val();
				insetB     = parent.find( '#redux-slider-value-' + mainID + '-inset-blur' ).val();
				insetS     = parent.find( '#redux-slider-value-' + mainID + '-inset-spread' ).val();

				css = 'inset ' + insetH + 'px ' + insetV + 'px ' + insetB + 'px ' + insetS + 'px ' + insetColor;
			}
		}

		if ( drop.length > 0 ) {
			if ( drop.find( '.checkbox' ).is( ':checked' ) ) {
				dropColor = parent.find( '#' + mainID + '-drop-color' ).val();
				dropH     = parent.find( '#redux-slider-value-' + mainID + '-drop-horizontal' ).val();
				dropV     = parent.find( '#redux-slider-value-' + mainID + '-drop-vertical' ).val();
				dropB     = parent.find( '#redux-slider-value-' + mainID + '-drop-blur' ).val();
				dropS     = parent.find( '#redux-slider-value-' + mainID + '-drop-spread' ).val();

				if ( '' !== css ) {
					css = css + ',';
				}

				css = css + dropH + 'px ' + dropV + 'px ' + dropB + 'px ' + dropS + 'px ' + dropColor;
			}
		}

		preview.css(
			{
				'box-shadow': css,
				'-webkit-box-shadow': css,
				'-moz-box-shadow': css,
				'-o-box-shadow': css
			}
		);

		if ( update ) {
			redux_change( el );
		}
	};
} )( jQuery );
