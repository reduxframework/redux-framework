/**
 * Field Box Shadow (box_shadow)
 *
 * Author: Kevin Provance (kprovance)
 */

/* global jQuery, document, redux, redux_change */

(function ( $ ) {
	'use strict';

	redux.field_objects            = redux.field_objects || {};
	redux.field_objects.box_shadow = redux.field_objects.box_shadow || {};

	redux.field_objects.box_shadow.init = function ( selector ) {
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-box_shadow:visible' );
		}

		$( selector ).each(
			function () {
				const el   = $( this );
				let parent = el;

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

				redux.field_objects.box_shadow.colorPicker( el );
				redux.field_objects.box_shadow.sliderInit( el );

				el.find( '.checkbox' ).on(
					'click',
					function () {
						let val      = 0;
						const col    = $( this ).parents( '.col-2' );
						const slider = col.find( '.redux-box-shadow-slider' );
						const color  = col.find( '.wp-picker-container' );
						const label  = $( this ).parent( 'label' );

						if ( $( this ).is( ':checked' ) ) {
							val = $( this ).parent().find( '.checkbox-check' ).attr( 'data-val' );
							color.removeClass( 'disabled' );
							slider.attr( 'disabled', false );
							label.removeClass( 'shadow-disabled' );
						} else {
							color.addClass( 'disabled' );
							slider.attr( 'disabled', true );
							label.addClass( 'shadow-disabled' );
						}

						$( this ).parent().find( '.checkbox-check' ).val( val );

						redux.field_objects.box_shadow.changeValue( $( this ), true );
					}
				);
			}
		);
	};

	redux.field_objects.box_shadow.colorPicker = function ( el ) {
		const colorPicker = el.find( '.redux-color-init' ).wpColorPicker(
			{
				change: function ( e, ui ) {
					$( this ).val( ui.color.toString() );

					redux.field_objects.box_shadow.changeValue( $( this ), true );
				},
				clear: function ( e, ui ) {
					$( this ).val( ui.color.toString() );

					redux.field_objects.box_shadow.changeValue( $( this ).parent().find( '.redux-color-init' ), true );
				}
			}
		);

		const id = el.data( 'id' );

		colorPicker.each(
			function () {
				const column    = $( this ).parents( '.col-2' );
				const shadow    = column.data( 'shadow' );
				const label     = column.find( 'label[for="' + id + '-' + shadow + '-shadow"]' );
				const container = column.find( '.wp-picker-container' );

				if ( label.hasClass( 'shadow-disabled' ) ) {
					container.addClass( 'disabled' );
				}
			}
		);
	};

	redux.field_objects.box_shadow.sliderInit = function ( el ) {
		el.find( '.redux-box-shadow-slider' ).each(
			function () {
				const mainID = $( this ).data( 'id' );
				const minVal = $( this ).data( 'min' );
				const maxVal = $( this ).data( 'max' );
				const step   = $( this ).data( 'step' );
				const def    = $( this ).data( 'default' );
				const label  = $( this ).data( 'label' );
				const rtl    = Boolean( $( this ).data( 'rtl' ) );
				const range  = [minVal, maxVal];

				const slider = $( this ).reduxNoUiSlider(
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
						slide: function () {
							const val = slider.val();

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

	redux.field_objects.box_shadow.changeValue = function ( el, update ) {
		const parent    = el.parents( '.redux-container-box_shadow' );
		const container = parent.find( '.box-shadow-controls' );
		const inset     = container.find( '.shadow-inset' );
		const drop      = container.find( '.shadow-drop' );

		const mainID  = parent.data( 'id' );
		const preview = parent.find( '#shadow-result' );
		let css       = '';

		let insetColor;
		let insetH;
		let insetV;
		let insetB;
		let insetS;
		let dropColor;
		let dropH;
		let dropV;
		let dropB;
		let dropS;

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
