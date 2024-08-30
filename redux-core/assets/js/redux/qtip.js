/* global redux */
// noinspection JSUnresolvedReference

(function ( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.initQtip = function () {
		let classes;

		// Shadow.
		let shadow      = '';
		const tipShadow = redux.optName.args.hints.tip_style.shadow;

		// Color.
		let color      = '';
		const tipColor = redux.optName.args.hints.tip_style.color;

		// Rounded.
		let rounded      = '';
		const tipRounded = redux.optName.args.hints.tip_style.rounded;

		// Tip style.
		let style      = '';
		const tipStyle = redux.optName.args.hints.tip_style.style;

		// Get position data.
		let myPos = redux.optName.args.hints.tip_position.my;
		let atPos = redux.optName.args.hints.tip_position.at;

		// Tooltip trigger action.
		const showEvent = redux.optName.args.hints.tip_effect.show.event;
		const hideEvent = redux.optName.args.hints.tip_effect.hide.event;

		// Tip show effect.
		const tipShowEffect   = redux.optName.args.hints.tip_effect.show.effect;
		const tipShowDuration = redux.optName.args.hints.tip_effect.show.duration;

		// Tip hide effect.
		const tipHideEffect   = redux.optName.args.hints.tip_effect.hide.effect;
		const tipHideDuration = redux.optName.args.hints.tip_effect.hide.duration;

		if ( $().qtip ) {
			if ( true === tipShadow ) {
				shadow = 'qtip-shadow';
			}

			if ( '' !== tipColor ) {
				color = 'qtip-' + tipColor;
			}

			if ( true === tipRounded ) {
				rounded = 'qtip-rounded';
			}

			if ( '' !== tipStyle ) {
				style = 'qtip-' + tipStyle;
			}

			classes = shadow + ',' + color + ',' + rounded + ',' + style + ',redux-qtip';
			classes = classes.replace( /,/g, ' ' );

			// Gotta be lowercase, and in proper format.
			myPos = $.redux.verifyPos( myPos.toLowerCase(), true );
			atPos = $.redux.verifyPos( atPos.toLowerCase(), false );

			$( 'div.redux-dev-qtip' ).each(
				function () {
					$( this ).qtip(
						{
							content: {
								text: $( this ).attr( 'qtip-content' ),
								title: $( this ).attr( 'qtip-title' )
							}, show: {
								effect: function () {
									$( this ).slideDown( 500 );
								},
								event: 'mouseover'
							}, hide: {
								effect: function () {
									$( this ).slideUp( 500 );
								},
								event: 'mouseleave'
							}, style: {
								classes: 'qtip-shadow qtip-light'
							}, position: {
								my: 'top center',
								at: 'bottom center'
							}
						}
					);
				}
			);

			$( 'div.redux-hint-qtip' ).each(
				function () {
					$( this ).qtip(
						{
							content: {
								text: $( this ).attr( 'qtip-content' ),
								title: $( this ).attr( 'qtip-title' )
							}, show: {
								effect: function () {
									switch ( tipShowEffect ) {
										case 'slide':
											$( this ).slideDown( tipShowDuration );
											break;
										case 'fade':
											$( this ).fadeIn( tipShowDuration );
											break;
										default:
											$( this ).show();
											break;
									}
								},
								event: showEvent
							}, hide: {
								effect: function () {
									switch ( tipHideEffect ) {
										case 'slide':
											$( this ).slideUp( tipHideDuration );
											break;
										case 'fade':
											$( this ).fadeOut( tipHideDuration );
											break;
										default:
											$( this ).hide( tipHideDuration );
											break;
									}
								},
								event: hideEvent
							}, style: {
								classes: classes
							}, position: {
								my: myPos,
								at: atPos
							}
						}
					);
				}
			);

			$( 'input[qtip-content]' ).each(
				function () {
					$( this ).qtip(
						{
							content: {
								text: $( this ).attr( 'qtip-content' ),
								title: $( this ).attr( 'qtip-title' )
							},
							show: 'focus',
							hide: 'blur',
							style: classes,
							position: {
								my: myPos,
								at: atPos
							}
						}
					);
				}
			);
		}
	};

	$.redux.verifyPos = function ( s, b ) {
		let split;
		let paramOne;
		let paramTwo;

		// Trim off spaces.
		s = s.replace( /^\s+|\s+$/gm, '' );

		// Position value is blank, set the default.
		if ( '' === s || - 1 === s.search( ' ' ) ) {
			if ( true === b ) {
				return 'top left';
			} else {
				return 'bottom right';
			}
		}

		// Split string into array.
		split = s.split( ' ' );

		// Evaluate first string.  Must be top, center, or bottom.
		paramOne = b ? 'top' : 'bottom';

		if ( 'top' === split[0] || 'center' === split[0] || 'bottom' === split[0] ) {
			paramOne = split[0];
		}

		// Evaluate second string.  Must be left, center, or right.
		paramTwo = b ? 'left' : 'right';

		if ( 'left' === split[1] || 'center' === split[1] || 'right' === split[1] ) {
			paramTwo = split[1];
		}

		return paramOne + ' ' + paramTwo;
	};
})( jQuery );
