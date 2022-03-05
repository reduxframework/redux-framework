/**
 * Extension for jQuery Spinner.
 *
 * @license jQuery UI Spinner 1.20
 *
 * Copyright (c) 2009-2010 Brant Burnett
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * The original author is no longer maintaining this code.
 *
 * Modified for the Redux Framework Spinner field by Kevin Provance (kprovance).
 *
 * Listen up y'all, I painstakingly recreated the original code from it's modified and minified version to the thing of
 * beauty you see below.  Mess with it and I'll kill ya! - kp
 *
 * Deprecated jQuery API $.browser was replaced with the accepted hack below.
 * Deprecated boxSupport was removed, since Redux does not use boxSupport.
 */

/* global jQuery */

jQuery.uaMatch = function( ua ) {
	'use strict';

	var match;

	ua = ua.toLowerCase();

	match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
		/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
		/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
		/(msie) ([\w.]+)/.exec( ua ) ||
		ua.indexOf( 'compatible' ) < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) || [];
	return {
		browser: match[ 1 ] || '',
		version: match[ 2 ] || '0'
	};
};

(function( $ ) {
	'use strict';

	var active   = 'ui-state-active';
	var hover    = 'ui-state-hover';
	var disabled = 'ui-state-disabled';

	var keyCode  = $.ui.keyCode;
	var up       = keyCode.UP;
	var down     = keyCode.DOWN;
	var right    = keyCode.RIGHT;
	var left     = keyCode.LEFT;
	var pageUp   = keyCode.PAGE_UP;
	var pageDown = keyCode.PAGE_DOWN;
	var home     = keyCode.HOME;
	var end      = keyCode.END;

	var msie                = $.uaMatch.msie;
	var mouseWheelEventName = $.uaMatch.mozilla ? 'DOMMouseScroll' : 'mousewheel';

	var eventNamespace = '.uispinner';

	var validKeys = [up, down, right, left, pageUp, pageDown, home, end, keyCode.BACKSPACE, keyCode.DELETE, keyCode.TAB];

	var focusCtrl;

	$.widget(
		'ui.spinner',
		{
			options: {
				min: null,
				max: null,
				allowNull: false,
				group: '',
				point: '.',
				prefix: '',
				suffix: '',
				places: null,
				defaultStep: 1,
				largeStep: 10,
				mouseWheel: true,
				increment: 'slow',
				className: null,
				showOn: 'always',
				width: 95,
				upIconClass: 'ui-icon-triangle-1-n',
				downIconClass: 'ui-icon-triangle-1-s',
				addText: '+',
				subText: '-',

				format: function( num, places ) {
					var _this = this;
					var regex = /(\d+)(\d{3})/;

					var result;

					var realNum = Number( num );

					/* jscs:disable disallowImplicitTypeConversion */
					result = ( isNaN( num ) ? 0 : Math.abs( realNum ) ).toFixed( places ) + '';

					for ( result = result.replace( '.', _this.point ); regex.test( result ) && _this.group; result = result.replace( regex, '$1' + _this.group + '$2' ) ) {

					}

					return ( num < 0 ? '-' : '' ) + _this.prefix + result + _this.suffix;
				},

				parse: function( val ) {
					var _this = this;

					if ( '.' === _this.group ) {
						val = val.replace( '.', '' );
					}

					if ( '.' !== _this.point ) {
						val = val.replace( _this.point, '.' );
					}

					return parseFloat( val.replace( /[^0-9\-\.]/g, '' ) );
				}
			},
			_create: function() {
				var _this = this;
				var input = _this.element;
				var type  = input.attr( 'type' );

				if ( ! input.is( 'input' ) || 'text' !== type && 'number' !== type ) {
					console.error( 'Invalid target for ui.spinner' );
					return;
				}

				_this._procOptions( true );
				_this._createButtons( input );

				if ( ! input.is( ':enabled' ) ) {
					_this.disable();
				}
			},
			_createButtons: function( input ) {
				var _this       = this;
				var options     = _this.options;
				var className   = options.className;
				var buttonWidth = options.width;
				var showOn      = options.showOn;
				var height      = input.outerHeight();

				var wrapper;
				var upButton;
				var downButton;
				var buttons;
				var icons;
				var hoverDelay;
				var hoverDelayCallback;
				var hovered;
				var inKeyDown;
				var inSpecialKey;
				var inMouseDown;
				var btnContainer;
				var rtl;

				function mouseUp() {
					/* jshint validthis:true */
					if ( inMouseDown ) {
						$( this ).removeClass( active );
						_this._stopSpin();
						inMouseDown = false;
					}

					return false;
				}

				function mouseDown() {
					var input;
					var dir;

					/* jshint validthis:true */
					if ( ! options.disabled ) {
						input = _this.element[0];
						dir   = upButton === this ? 1 : - 1;

						input.trigger( 'focus' );
						input.trigger( 'select' );

						$( this ).addClass( active );

						inMouseDown = true;

						_this._startSpin( dir );
					}

					return false;
				}

				function setHoverDelay( callback ) {
					function execute() {
						hoverDelay = 0;
						callback();
					}

					if ( hoverDelay ) {
						if ( callback === hoverDelayCallback ) {
							return;
						}

						clearTimeout( hoverDelay );
					}

					hoverDelayCallback = callback;

					hoverDelay = setTimeout( execute, 100 );
				}

				function invalidKey( keyCode, charCode ) {
					var ch;
					var options;

					if ( inSpecialKey ) {
						return false;
					}

					ch      = String.fromCharCode( charCode || keyCode );
					options = _this.options;

					if ( ch >= '0' && ch <= '9' || '-' === ch ) {
						return false;
					}

					if ( _this.places > 0 && ch === options.point || ch === options.group ) {
						return false;
					}

					return true;
				}

				function isSpecialKey( keyCode ) {
					var vKeys = validKeys.length;
					var i;

					for ( i = 0; i < vKeys; i += 1 ) {
						if ( validKeys[i] === keyCode ) {
							return true;
						}
					}

					return false;
				}

				wrapper = input.wrap( '<span class="spinner-wrpr" />' ).css(
					{
						width: ( _this.oWidth = input.outerWidth() ) - buttonWidth + '!important',
						marginRight: '30px',
						marginLeft: '30px',
						textAlign: 'center',
						'float': 'none',
						marginTop: 0
					}
				).after( '<span class="ui-spinner ui-widget"></span>' ).next();

				_this.wrapper = wrapper;

				btnContainer = $(
					'<div class="ui-spinner-buttons"><div class="ui-spinner-up ui-spinner-button ui-state-default ui-corner-tr"><span class="ui-icon ' +
					options.upIconClass + '">' + options.addText +
					'</span></div><div class="ui-spinner-down ui-spinner-button ui-state-default ui-corner-br"><span class="ui-icon ' +
					options.downIconClass + '">' + options.subText + '</span></div></div>'
				);

				_this.btnContainer = btnContainer;

				rtl = 'rtl' === input[0].dir;

				if ( className ) {
					wrapper.addClass( className );
				}

				wrapper.append( btnContainer.css( { height: height, left: 0, top: 0 } ) );

				buttons = _this.buttons = btnContainer.find( '.ui-spinner-button' );

				buttons.css( { width: '30px', height: height - ( 0 ) } );
				buttons.eq( 0 ).css( { right: '0' } );
				buttons.eq( 1 ).css( { left: '0' } );

				upButton   = buttons[0];
				downButton = buttons[1];
				icons      = buttons.find( '.ui-icon' );

				btnContainer.width( '135px' );

				if ( 'always' !== showOn ) {
					btnContainer.css( 'opacity', 0 );
				}

				if ( 'hover' === showOn || 'both' === showOn ) {
					buttons.add( input ).on(
						'mouseenter' + eventNamespace,
						function() {
							setHoverDelay(
								function() {
									hovered = true;
									if ( ! _this.focused || 'hover' === showOn ) {
										_this.showButtons();
									}
								}
							);
						}
					).on(
						'mouseleave' + eventNamespace,
						function() {
							setHoverDelay(
								function() {
									hovered = false;
									if ( ! _this.focused || 'hover' === showOn ) {
										_this.hideButtons();
									}
								}
							);
						}
					);
				}

				buttons.on(
					'hover',
					function() {
						_this.buttons.removeClass( hover );
						if ( ! options.disabled ) {
							$( this ).addClass( hover );
						}
					},
					function() {
						$( this ).removeClass( hover );
					}
				)
				.on(
					'mousedown',
					mouseDown
				)
				.on(
					'mouseup',
					mouseUp
				)
				.on(
					'mouseout',
					mouseUp
				);

				if ( msie ) {
					buttons.on(
						'dblclick',
						function() {
							if ( ! options.disabled ) {
								_this._change();
								_this._doSpin( ( this === upButton ? 1 : - 1 ) * options.step );
							}

							return false;
						}
					).on(
						'selectstart',
						function() {
							return false;
						}
					);
				}

				input.on(
					'keydown' + eventNamespace,
					function( e ) {
						var dir;
						var large;
						var limit;
						var keyCode = e.keyCode;

						if ( e.ctrl || e.alt ) {
							return true;
						}

						if ( isSpecialKey( keyCode ) ) {
							inSpecialKey = true;
						}

						if ( inKeyDown ) {
							return false;
						}

						/*jslint bitwise: true */
						switch ( keyCode ) {
							case up:
							case pageUp:
								dir   = 1;
								large = keyCode === pageUp;
								break;
							case down:
							case pageDown:
								hover = - 1;
								large = keyCode === pageDown;
								break;
							case right:
							case left:
								dir = ( keyCode === right ) ^ rtl ? 1 : - 1;
								break;
							case home:
								limit = _this.options.min;
								if ( null !== limit ) {
									_this._setValue( limit );
								}

								return false;
							case end:
								limit = _this.options.max;
								limit = _this.options.max;
								if ( null !== limit ) {
									_this._setValue( limit );
								}

								return false;
						}

						if ( dir ) {
							if ( ! inKeyDown && ! options.disabled ) {
								$( dir > 0 ? upButton : downButton ).addClass( active );

								inKeyDown = true;

								_this._startSpin( dir, large );
							}

							return false;
						}
					}
				).on(
					'keyup' + eventNamespace,
					function( e ) {
						if ( e.ctrl || e.alt ) {
							return true;
						}

						if ( isSpecialKey( keyCode ) ) {
							inSpecialKey = false;
						}

						switch ( e.keyCode ) {
							case up:
							case right:
							case pageUp:
							case down:
							case left:
							case pageDown:
								buttons.removeClass( active );
								_this._stopSpin();
								inKeyDown = false;

								return false;
						}
					}
				).on(
					'keypress' + eventNamespace,
					function( e ) {
						if ( invalidKey( e.keyCode, e.charCode ) ) {
							return false;
						}
					}
				).on(
					'change' + eventNamespace,
					function() {
						_this._change();
					}
				).on(
					'focus' + eventNamespace,
					function() {
						function selectAll() {
							_this.element.trigger( 'select' );
						}

						if ( msie ) {
							selectAll();
						} else {
							setTimeout( selectAll, 0 );
						}

						_this.focused = true;
						focusCtrl     = _this;

						if ( ! hovered && ( 'focus' === showOn || 'both' === showOn ) ) {
							_this.showButtons();
						}
					}
				).on(
					'blur' + eventNamespace,
					function() {
						_this.focused = false;
						if ( ! hovered && ( 'focus' === showOn || 'both' === showOn ) ) {
							_this.hideButtons();
						}
					}
				);
			},
			_procOptions: function( init ) {
				var _this     = this;
				var input     = _this.element;
				var options   = _this.options;
				var min       = options.min;
				var max       = options.max;
				var step      = options.step;
				var places    = options.places;
				var maxLength = -1;

				var temp;

				if ( 'slow' === options.increment ) {
					options.increment = [
						{ count: 1, mult: 1, delay: 250 },
						{ count: 3, mult: 1, delay: 100 },
						{ count: 0, mult: 1, delay: 50 }
					];
				} else if ( 'fast' === options.increment ) {
					options.increment = [
						{ count: 1, mult: 1, delay: 250	},
						{ count: 19, mult: 1, delay: 100 },
						{ count: 80, mult: 1, delay: 20 },
						{ count: 100, mult: 10, delay: 20 },
						{ count: 0, mult: 100, delay: 20 }
					];
				}

				if ( null === min && null !== ( temp = input.attr( 'min' ) ) ) {
					min = parseFloat( temp );
				}

				if ( null === max && null !== ( temp = input.attr( 'max' ) ) ) {
					max = parseFloat( temp );
				}

				if ( ! step && null !== ( temp = input.attr( 'step' ) ) ) {
					if ( 'any' !== temp ) {
						step = parseFloat( temp );

						options.largeStep *= step;
					}
				}

				options.step = step = step || options.defaultStep;

				if ( null === places && - 1 !== ( temp = step + '' ).indexOf( '.' ) ) {
					places = temp.length - temp.indexOf( '.' ) - 1;
				}

				_this.places = places;

				if ( null !== max && null !== min ) {
					if ( min > max ) {
						min = max;
					}

					maxLength = Math.max( Math.max( maxLength, options.format( max, places, input ).length ), options.format( min, places, input ).length );
				}

				if ( init ) {
					_this.inputMaxLength = input[0].maxLength;
				}

				temp = _this.inputMaxLength;

				if ( temp > 0 ) {
					maxLength = maxLength > 0 ? Math.min( temp, maxLength ) : temp;
					temp      = Math.pow( 10, maxLength ) - 1;

					if ( null === max || max > temp ) {
						max = temp;
					}

					temp = -( temp + 1 ) / 10 + 1;

					if ( null === min || min < temp ) {
						min = temp;
					}
				}

				if ( maxLength > 0 ) {
					input.attr( 'maxlength', maxLength );
				}

				options.min = min;
				options.max = max;

				_this._change();

				input.off( mouseWheelEventName + eventNamespace );

				if ( options.mouseWheel ) {
					input.on( mouseWheelEventName + eventNamespace, _this._mouseWheel );
				}
			},
			_mouseWheel: function( e ) {
				var self = $.data( this, 'spinner' );

				if ( ! self.options.disabled && self.focused && self === focusCtrl ) {
					self._change();
					self._doSpin( ( ( e.wheelDelta || -e.detail ) > 0 ? 1 : - 1 ) * self.options.step );

					return false;
				}
			},
			_setTimer: function( delay, dir, large ) {
				var _this = this;

				function e() {
					_this._spin( dir, large );
				}

				_this._stopSpin();
				_this.timer = setInterval( e, delay );
			},
			_stopSpin: function() {
				if ( this.timer ) {
					clearInterval( this.timer );

					this.timer = 0;
				}
			},
			_startSpin: function( dir, large ) {
				var _this     = this;
				var options   = _this.options;
				var increment = options.increment;

				_this._change();
				_this._doSpin( dir * ( large ? _this.options.largeStep : _this.options.step ) );

				if ( increment && increment.length > 0 ) {
					_this.counter    = 0;
					_this.incCounter = 0;

					_this._setTimer( increment[0].delay, dir, large );
				}
			},
			_spin: function( dir, large ) {
				var _this        = this;
				var increment    = _this.options.increment;
				var curIncrement = increment[_this.incCounter];

				_this._doSpin( dir * curIncrement.mult * ( large ? _this.options.largeStep : _this.options.step ) );

				_this.counter += 1;

				if ( _this.counter > curIncrement.count && _this.incCounter < increment.length - 1 ) {
					_this.counter = 0;

					/* jshint plusplus:false */
					curIncrement = increment[++_this.incCounter];

					_this._setTimer( curIncrement.delay, dir, large );
				}
			},
			_doSpin: function( step ) {
				var _this = this;
				var value = _this.curvalue;

				if ( null === value ) {
					value = ( step > 0 ? _this.options.min : _this.options.max ) || 0;
				}

				_this._setValue( value + step );
			},
			_parseValue: function() {
				var value = this.element.val();

				return value ? this.options.parse( value, this.element ) : null;
			},
			_validate: function( value ) {
				var options = this.options;
				var min     = options.min;
				var max     = options.max;

				if ( null === value && ! options.allowNull ) {
					value = null !== this.curvalue ? this.curvalue : min || max || 0;
				}

				if ( null !== max && ( value > max || value === max ) ) {
					if ( undefined !== this.buttons ) {
						$( this.buttons[0] ).addClass( disabled );
					}

					return max;
				} else if ( null !== min && ( value < min || value === min ) ) {
					if ( undefined !== this.buttons ) {
						$( this.buttons[1] ).addClass( disabled );
					}

					return min;
				} else {
					if ( undefined !== this.buttons ) {
						$( this.buttons[0] ).removeClass( disabled );
						$( this.buttons[1] ).removeClass( disabled );
					}

					return value;
				}
			},
			_change: function() {
				var _this = this;
				var value = _this._parseValue();

				if ( ! _this.selfChange ) {
					if ( isNaN( value ) ) {
						value = _this.curvalue;
					}

					_this._setValue( value, true );
				}
			},
			_setOption: function( key, value ) {
				$.Widget.prototype._setOption.call( this, key, value );

				this._procOptions();
			},
			increment: function() {
				this._doSpin( this.options.step );
			},
			decrement: function() {
				this._doSpin( - this.options.step );
			},
			showButtons: function( immediate ) {
				var btnContainer = this.btnContainer.stop();

				if ( immediate ) {
					btnContainer.css( 'opacity', 1 );
				} else {
					btnContainer.fadeTo( 'fast', 1 );
				}
			},
			hideButtons: function( immediate ) {
				var btnContainer = this.btnContainer.stop();

				if ( immediate ) {
					btnContainer.css( 'opacity', 0 );
				} else {
					btnContainer.fadeTo( 'fast', 0 );
				}

				this.buttons.removeClass( hover );
			},
			_setValue: function( value, suppressFireEvent ) {
				var _this = this;

				_this.curvalue = value = _this._validate( value );
				_this.element.val( null !== value ? _this.options.format( value, _this.places, _this.element ) : '' );

				if ( ! suppressFireEvent ) {
					_this.selfChange = true;
					_this.element.trigger( 'change' );
					_this.selfChange = false;
				}
			},
			value: function( newValue ) {
				if ( arguments.length ) {
					this._setValue( newValue );

					return this.element;
				}

				return this.curvalue;
			},
			enable: function() {
				this.buttons.removeClass( disabled );
				this.element[0].disabled = false;

				$.Widget.prototype.enable.call( this );
			},
			disable: function() {
				this.buttons.addClass( disabled ).removeClass( hover );
				this.element[0].disabled = true;

				$.Widget.prototype.disable.call( this );
			},
			destroy: function() {
				this.wrapper.remove();

				this.element.off( eventNamespace ).css(
					{
						width: this.oWidth,
						marginRight: this.oMargin
					}
				);

				$.Widget.prototype.destroy.call( this );
			}
		}
	);
})( jQuery );
