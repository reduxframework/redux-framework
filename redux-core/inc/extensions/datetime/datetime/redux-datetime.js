/* global redux, jQuery */

( function ( $ ) {
	'use strict';

	redux.field_objects          = redux.field_objects || {};
	redux.field_objects.datetime = redux.field_objects.datetime || {};

	redux.field_objects.datetime.init = function ( selector ) {
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-datetime:visible' );
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

				el.find( '.redux-date-picker' ).each(
					function () {
						let dateFormat;
						let timeFormat;
						let separator;
						let rtl;
						let numOfMonths;
						let hourMin;
						let hourMax;
						let minuteMin;
						let minuteMax;
						let controlType;
						let datePicker;
						let timePicker;
						let timeOnly = false;
						let timezoneList;
						let dateMin;
						let minDate;
						let dateMax;
						let maxDate;
						let timezone;
						let split;
						let altField = '';
						let timePickerID;

						const mainID = $( this ).parents( '.redux-datetime-container:first' ).attr( 'id' );
						const id     = $( '#' + mainID );

						dateFormat = id.data( 'date-format' );
						dateFormat = String( ( '' === dateFormat ) ? 'mm-dd-yy' : dateFormat );

						timeFormat = id.data( 'time-format' );
						timeFormat = String( ( '' === timeFormat ) ? 'h:mm TT' : timeFormat );

						separator = id.data( 'separator' );
						separator = String( ( '' === separator ) ? ' ' : separator );

						rtl = id.data( 'rtl' );
						rtl = Boolean( ( '' === rtl ) ? false : rtl );

						numOfMonths = id.data( 'num-of-months' );
						hourMin     = id.data( 'hour-min' );
						hourMax     = id.data( 'hour-max' );
						minuteMin   = id.data( 'minute-min' );
						minuteMax   = id.data( 'minute-max' );

						controlType = id.data( 'control-type' );
						controlType = String( ( '' === controlType ) ? 'slider' : controlType );

						datePicker = id.data( 'date-picker' );
						datePicker = Boolean( ( '' === datePicker ) ? false : datePicker );

						timePicker = id.data( 'time-picker' );
						timePicker = Boolean( ( '' === timePicker ) ? false : timePicker );

						if ( false === datePicker ) {
							timeOnly = true;
						}

						timezoneList = id.data( 'timezone-list' );
						timezoneList = decodeURIComponent( timezoneList );
						timezoneList = JSON.parse( timezoneList );

						dateMin = id.data( 'date-min' );
						dateMin = decodeURIComponent( dateMin );
						dateMin = JSON.parse( dateMin );

						if ( dateMin === - 1 ) {
							minDate = null;
						} else if ( 'object' === typeof dateMin ) {
							minDate = new Date( dateMin.year, dateMin.month, dateMin.day );
						} else {
							minDate = dateMin;
						}

						dateMax = id.data( 'date-max' );
						dateMax = decodeURIComponent( dateMax );
						dateMax = JSON.parse( dateMax );

						if ( dateMax === - 1 ) {
							maxDate = null;
						} else if ( 'object' === typeof dateMax ) {
							maxDate = new Date( dateMax.year, dateMax.month, dateMax.day );
						} else {
							maxDate = dateMax;
						}

						timezone = id.data( 'timezone' );

						split = id.data( 'mode' );
						split = Boolean( ( '' === split ) ? false : split );

						if ( true === split ) {
							timePickerID = el.find( 'input.redux-time-picker' ).data( 'id' );
							altField     = '#' + timePickerID + '-time'; // '.redux-time-picker';
						}

						$( this ).datetimepicker(
							{
								beforeShow: function ( input, instance ) {
									const el      = $( '#ui-datepicker-div' );
									const popover = instance.dpDiv;

									$( '.redux-container:first' ).append( el );
									el.hide();

									setTimeout(
										function () {
											popover.position(
												{
													my: 'left top',
													at: 'left bottom',
													collision: 'none',
													of: input
												}
											);
										},
										1
									);
								},
								altField: altField,
								dateFormat: dateFormat,
								timeFormat: timeFormat,
								separator: separator,
								showTimepicker: timePicker,
								timeOnly: timeOnly,
								controlType: controlType,
								isRTL: rtl,
								timezoneList: timezoneList,
								timezone: timezone,
								hourMin: hourMin,
								hourMax: hourMax,
								minuteMin: minuteMin,
								minuteMax: minuteMax,
								minDate: minDate,
								maxDate: maxDate,
								numberOfMonths: numOfMonths
							}
						);
					}
				);
			}
		);
	};
} )( jQuery );
