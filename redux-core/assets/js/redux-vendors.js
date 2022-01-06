// jscs:disable
// jshint ignore: start

/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */
(function( factory ) {
	if ( typeof define === 'function' && define.amd ) {
		// AMD (Register as an anonymous module)
		define( ['jquery'], factory );
	} else if ( typeof exports === 'object' ) {
		// Node/CommonJS
		module.exports = factory( require( 'jquery' ) );
	} else {
		// Browser globals
		factory( jQuery );
	}
}( function( $ ) {

	var pluses = /\+/g;

	function encode( s ) {
		return config.raw ? s : encodeURIComponent( s );
	}

	function decode( s ) {
		return config.raw ? s : decodeURIComponent( s );
	}

	function stringifyCookieValue( value ) {
		return encode( config.json ? JSON.stringify( value ) : String( value ) );
	}

	function parseCookieValue( s ) {
		if ( s.indexOf( '"' ) === 0 ) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice( 1, - 1 ).replace( /\\"/g, '"' ).replace( /\\\\/g, '\\' );
		}

		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent( s.replace( pluses, ' ' ) );
			return config.json ? JSON.parse( s ) : s;
		} catch ( e ) {
		}
	}

	function read( s, converter ) {
		var value = config.raw ? s : parseCookieValue( s );
		return 'function' === typeof converter ? converter( value ) : value;
	}

	var config = $.cookie = function( key, value, options ) {

		// Write

		if ( arguments.length > 1 && 'function' !== typeof value ) {
			options = $.extend( {}, config.defaults, options );

			if ( typeof options.expires === 'number' ) {
				var days = options.expires, t = options.expires = new Date();
				t.setMilliseconds( t.getMilliseconds() + days * 864e+5 );
			}

			return (document.cookie = [encode( key ), '=', stringifyCookieValue( value ), options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path ? '; path=' + options.path : '', options.domain ? '; domain=' + options.domain : '', options.secure ? '; secure' : ''].join( '' ));
		}

		// Read

		var result = key ? undefined : {}, // To prevent the for loop in the first place assign an empty array
		    // in case there are no cookies at all. Also prevents odd result when
		    // calling $.cookie().
		    cookies = document.cookie ? document.cookie.split( '; ' ) : [], i = 0, l = cookies.length;

		for ( ; i < l; i ++ ) {
			var parts = cookies[i].split( '=' ), name = decode( parts.shift() ), cookie = parts.join( '=' );

			if ( key === name ) {
				// If second argument (value) is a function it's a converter...
				result = read( cookie, value );
				break;
			}

			// Prevent storing a cookie that we couldn't decode.
			if ( ! key && (cookie = read( cookie )) !== undefined ) {
				result[name] = cookie;
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function( key, options ) {
		// Must not alter options, thus extending a fresh object...
		$.cookie( key, '', $.extend( {}, options, {expires: - 1} ) );
		return ! $.cookie( key );
	};

} ));

// jscs:disable
// jshint ignore: start

/********************************************************************
 * Limit the characters that may be entered in a text field
 * Common options: alphanumeric, alphabetic or numeric
 * Kevin Sheedy, 2012
 * http://github.com/KevinSheedy/jquery.alphanum
 *********************************************************************/

(function( $ ) {
	// API ///////////////////////////////////////////////////////////////////
	$.fn.alphanum = function( settings ) {

		var combinedSettings = getCombinedSettingsAlphaNum( settings );

		var $collection = this;

		setupEventHandlers( $collection, trimAlphaNum, combinedSettings );

		return this;
	};

	$.fn.alpha = function( settings ) {

		var defaultAlphaSettings = getCombinedSettingsAlphaNum( "alpha" );
		var combinedSettings     = getCombinedSettingsAlphaNum( settings, defaultAlphaSettings );

		var $collection = this;

		setupEventHandlers( $collection, trimAlphaNum, combinedSettings );

		return this;
	};

	$.fn.numeric = function( settings ) {

		var combinedSettings = getCombinedSettingsNum( settings );
		var $collection      = this;

		setupEventHandlers( $collection, trimNum, combinedSettings );

		$collection.on(
			'blur',
			function() {
				numericField_Blur( this, settings );
			}
		);

		return this;
	};

	// End of API /////////////////////////////////////////////////////////////

	// Start Settings ////////////////////////////////////////////////////////

	var DEFAULT_SETTINGS_ALPHANUM = {
		allow: '',   // Allow extra characters
		disallow: '',   // Disallow extra characters
		allowSpace: true, // Allow the space character
		allowNumeric: true, // Allow digits 0-9
		allowUpper: true, // Allow upper case characters
		allowLower: true, // Allow lower case characters
		allowCaseless: true, // Allow characters that don't have both upper & lower variants - eg Arabic or Chinese
		allowLatin: true, // a-z A-Z
		allowOtherCharSets: true, // eg �, �, Arabic, Chinese etc
		maxLength: NaN   // eg Max Length
	}

	var DEFAULT_SETTINGS_NUM = {
		allowPlus: false, // Allow the + sign
		allowMinus: true,  // Allow the - sign
		allowThouSep: true,  // Allow the thousands separator, default is the comma eg 12,000
		allowDecSep: true,  // Allow the decimal separator, default is the fullstop eg 3.141
		allowLeadingSpaces: false, maxDigits: NaN,   // The max number of digits
		maxDecimalPlaces: NaN,   // The max number of decimal places
		maxPreDecimalPlaces: NaN,   // The max number digits before the decimal point
		max: NaN,   // The max numeric value allowed
		min: NaN    // The min numeric value allowed
	}

	// Some pre-defined groups of settings for convenience
	var CONVENIENCE_SETTINGS_ALPHANUM = {
		"alpha": {
			allowNumeric: false
		}, "upper": {
			allowNumeric: false, allowUpper: true, allowLower: false, allowCaseless: true
		}, "lower": {
			allowNumeric: false, allowUpper: false, allowLower: true, allowCaseless: true
		}
	};

	// Some pre-defined groups of settings for convenience
	var CONVENIENCE_SETTINGS_NUMERIC = {
		"integer": {
			allowPlus: false, allowMinus: true, allowThouSep: false, allowDecSep: false
		}, "positiveInteger": {
			allowPlus: false, allowMinus: false, allowThouSep: false, allowDecSep: false
		}
	};

	var BLACKLIST   = getBlacklistAscii() + getBlacklistNonAscii();
	var THOU_SEP    = ",";
	var DEC_SEP     = ".";
	var DIGITS      = getDigitsMap();
	var LATIN_CHARS = getLatinCharsSet();

	// Return the blacklisted special chars that are encodable using 7-bit ascii
	function getBlacklistAscii() {
		var blacklist = '!@#$%^&*()+=[]\\\';,/{}|":<>?~`.-_';
		blacklist += " "; // 'Space' is on the blacklist but can be enabled using the 'allowSpace' config entry
		return blacklist;
	}

	// Return the blacklisted special chars that are NOT encodable using 7-bit ascii
	// We want this .js file to be encoded using 7-bit ascii so it can reach the widest possible audience
	// Higher order chars must be escaped eg "\xAC"
	// Not too worried about comments containing higher order characters for now (let's wait and see if it becomes a problem)
	function getBlacklistNonAscii() {
		var blacklist = "\xAC"     // �
			+ "\u20AC"   // �
			+ "\xA3"     // �
			+ "\xA6"     // �
		;
		return blacklist;
	}

	// End Settings ////////////////////////////////////////////////////////

	// Implementation details go here ////////////////////////////////////////////////////////

	function setupEventHandlers( $textboxes, trimFunction, settings ) {

		$textboxes.each( function() {

			var $textbox = $( this );

			$textbox.on( "keyup change paste", function( e ) {

				var pastedText = "";

				if ( e.originalEvent && e.originalEvent.clipboardData && e.originalEvent.clipboardData.getData ) pastedText = e.originalEvent.clipboardData.getData( "text/plain" )

				// setTimeout is necessary for handling the 'paste' event
				setTimeout( function() {
					trimTextbox( $textbox, trimFunction, settings, pastedText );
				}, 0 );
			} );

			$textbox.on( "keypress", function( e ) {

				// Determine which key is pressed.
				// If it's a control key, then allow the event's default action to occur eg backspace, tab
				var charCode = ! e.charCode ? e.which : e.charCode;
				if ( isControlKey( charCode ) || e.ctrlKey || e.metaKey ) // cmd on MacOS
					return;

				var newChar = String.fromCharCode( charCode );

				// Determine if some text was selected / highlighted when the key was pressed
				var selectionObject = $textbox.selection();
				var start           = selectionObject.start;
				var end             = selectionObject.end;

				var textBeforeKeypress = $textbox.val();

				// The new char may be inserted:
				//  1) At the start
				//  2) In the middle
				//  3) At the end
				//  4) User highlights some text and then presses a key which would replace the highlighted text
				//
				// Here we build the string that would result after the keypress.
				// If the resulting string is invalid, we cancel the event.
				// Unfortunately, it isn't enough to just check if the new char is valid because some chars
				// are position sensitive eg the decimal point '.'' or the minus sign '-'' are only valid in certain positions.
				var potentialTextAfterKeypress = textBeforeKeypress.substring( 0, start ) + newChar + textBeforeKeypress.substring( end );
				var validatedText              = trimFunction( potentialTextAfterKeypress, settings );

				// If the keypress would cause the textbox to contain invalid characters, then cancel the keypress event
				if ( validatedText != potentialTextAfterKeypress ) e.preventDefault();
			} );
		} );

	}

	// Ensure the text is a valid number when focus leaves the textbox
	// This catches the case where a user enters '-' or '.' without entering any digits
	function numericField_Blur( inputBox, settings ) {
		var fieldValueNumeric = parseFloat( $( inputBox ).val() );
		var $inputBox         = $( inputBox );

		if ( isNaN( fieldValueNumeric ) ) {
			$inputBox.val( "" );
			return;
		}

		if ( isNumeric( settings.min ) && fieldValueNumeric < settings.min ) $inputBox.val( "" );

		if ( isNumeric( settings.max ) && fieldValueNumeric > settings.max ) $inputBox.val( "" );
	}

	function isNumeric( value ) {
		return ! isNaN( value );
	}

	function isControlKey( charCode ) {

		if ( charCode >= 32 ) return false;
		if ( charCode == 10 ) return false;
		if ( charCode == 13 ) return false;

		return true;
	}

	// One way to prevent a character being entered is to cancel the keypress event.
	// However, this gets messy when you have to deal with things like copy paste which isn't a keypress.
	// Which event gets fired first, keypress or keyup? What about IE6 etc etc?
	// Instead, it's easier to allow the 'bad' character to be entered and then to delete it immediately after.

	function trimTextbox( $textBox, trimFunction, settings, pastedText ) {

		var inputString = $textBox.val();

		if ( inputString == "" && pastedText.length > 0 ) inputString = pastedText;

		var outputString = trimFunction( inputString, settings );

		if ( inputString == outputString ) return;

		var caretPos = $textBox.alphanum_caret();

		$textBox.val( outputString );

		//Reset the caret position
		if ( inputString.length == (outputString.length + 1) ) $textBox.alphanum_caret( caretPos - 1 ); else $textBox.alphanum_caret( caretPos );
	}

	function getCombinedSettingsAlphaNum( settings, defaultSettings ) {
		if ( typeof defaultSettings == "undefined" ) defaultSettings = DEFAULT_SETTINGS_ALPHANUM;
		var userSettings, combinedSettings = {};
		if ( typeof settings === "string" ) userSettings = CONVENIENCE_SETTINGS_ALPHANUM[settings]; else if ( typeof settings == "undefined" ) userSettings = {}; else userSettings = settings;

		$.extend( combinedSettings, defaultSettings, userSettings );

		if ( typeof combinedSettings.blacklist == 'undefined' ) combinedSettings.blacklistSet = getBlacklistSet( combinedSettings.allow, combinedSettings.disallow );

		return combinedSettings;
	}

	function getCombinedSettingsNum( settings ) {
		var userSettings, combinedSettings = {};
		if ( typeof settings === "string" ) userSettings = CONVENIENCE_SETTINGS_NUMERIC[settings]; else if ( typeof settings == "undefined" ) userSettings = {}; else userSettings = settings;

		$.extend( combinedSettings, DEFAULT_SETTINGS_NUM, userSettings );

		return combinedSettings;
	}

	// This is the heart of the algorithm
	function alphanum_allowChar( validatedStringFragment, Char, settings ) {

		if ( settings.maxLength && validatedStringFragment.length >= settings.maxLength ) return false;

		if ( settings.allow.indexOf( Char ) >= 0 ) return true;

		if ( settings.allowSpace && (Char == " ") ) return true;

		if ( settings.blacklistSet.contains( Char ) ) return false;

		if ( ! settings.allowNumeric && DIGITS[Char] ) return false;

		if ( ! settings.allowUpper && isUpper( Char ) ) return false;

		if ( ! settings.allowLower && isLower( Char ) ) return false;

		if ( ! settings.allowCaseless && isCaseless( Char ) ) return false;

		if ( ! settings.allowLatin && LATIN_CHARS.contains( Char ) ) return false;

		if ( ! settings.allowOtherCharSets ) {
			if ( DIGITS[Char] || LATIN_CHARS.contains( Char ) ) return true; else return false;
		}

		return true;
	}

	function numeric_allowChar( validatedStringFragment, Char, settings ) {

		if ( DIGITS[Char] ) {

			if ( isMaxDigitsReached( validatedStringFragment, settings ) ) return false;

			if ( isMaxPreDecimalsReached( validatedStringFragment, settings ) ) return false;

			if ( isMaxDecimalsReached( validatedStringFragment, settings ) ) return false;

			if ( isGreaterThanMax( validatedStringFragment + Char, settings ) ) return false;

			if ( isLessThanMin( validatedStringFragment + Char, settings ) ) return false;

			return true;
		}

		if ( settings.allowPlus && Char == '+' && validatedStringFragment == '' ) return true;

		if ( settings.allowMinus && Char == '-' && validatedStringFragment == '' ) return true;

		if ( Char == THOU_SEP && settings.allowThouSep && allowThouSep( validatedStringFragment, Char ) ) return true;

		if ( Char == DEC_SEP ) {
			// Only one decimal separator allowed
			if ( validatedStringFragment.indexOf( DEC_SEP ) >= 0 ) return false;
			if ( settings.allowDecSep ) return true;
		}

		return false;
	}

	function countDigits( string ) {

		// Error handling, nulls etc
		string = string + "";

		// Count the digits
		return string.replace( /[^0-9]/g, "" ).length;
	}

	function isMaxDigitsReached( string, settings ) {

		var maxDigits = settings.maxDigits;

		if ( maxDigits == "" || isNaN( maxDigits ) ) return false; // In this case, there is no maximum

		var numDigits = countDigits( string );

		if ( numDigits >= maxDigits ) return true;

		return false;
	}

	function isMaxDecimalsReached( string, settings ) {

		var maxDecimalPlaces = settings.maxDecimalPlaces;

		if ( maxDecimalPlaces == "" || isNaN( maxDecimalPlaces ) ) return false; // In this case, there is no maximum

		var indexOfDecimalPoint = string.indexOf( DEC_SEP );

		if ( indexOfDecimalPoint == - 1 ) return false;

		var decimalSubstring = string.substring( indexOfDecimalPoint );
		var numDecimals      = countDigits( decimalSubstring );

		if ( numDecimals >= maxDecimalPlaces ) return true;

		return false;
	}

	function isMaxPreDecimalsReached( string, settings ) {

		var maxPreDecimalPlaces = settings.maxPreDecimalPlaces;

		if ( maxPreDecimalPlaces == "" || isNaN( maxPreDecimalPlaces ) ) return false; // In this case, there is no maximum

		var indexOfDecimalPoint = string.indexOf( DEC_SEP );

		if ( indexOfDecimalPoint >= 0 ) return false;

		var numPreDecimalDigits = countDigits( string );

		if ( numPreDecimalDigits >= maxPreDecimalPlaces ) return true;

		return false;
	}

	function isGreaterThanMax( numericString, settings ) {

		if ( ! settings.max || settings.max < 0 ) return false;

		var outputNumber = parseFloat( numericString );
		if ( outputNumber > settings.max ) return true;

		return false;
	}

	function isLessThanMin( numericString, settings ) {

		if ( ! settings.min || settings.min > 0 ) return false;

		var outputNumber = parseFloat( numericString );
		if ( outputNumber < settings.min ) return true;

		return false;
	}

	/********************************
	 * Trims a string according to the settings provided
	 ********************************/
	function trimAlphaNum( inputString, settings ) {

		if ( typeof inputString != "string" ) return inputString;

		var inChars  = inputString.split( "" );
		var outChars = [];
		var i        = 0;
		var Char;

		for ( i = 0; i < inChars.length; i ++ ) {
			Char                        = inChars[i];
			var validatedStringFragment = outChars.join( "" );
			if ( alphanum_allowChar( validatedStringFragment, Char, settings ) ) outChars.push( Char );
		}

		return outChars.join( "" );
	}

	function trimNum( inputString, settings ) {
		if ( typeof inputString != "string" ) return inputString;

		var inChars  = inputString.split( "" );
		var outChars = [];
		var i        = 0;
		var Char;

		for ( i = 0; i < inChars.length; i ++ ) {
			Char                        = inChars[i];
			var validatedStringFragment = outChars.join( "" );
			if ( numeric_allowChar( validatedStringFragment, Char, settings ) ) outChars.push( Char );
		}

		return outChars.join( "" );
	}

	function removeUpperCase( inputString ) {
		var charArray   = inputString.split( '' );
		var i           = 0;
		var outputArray = [];
		var Char;

		for ( i = 0; i < charArray.length; i ++ ) {
			Char = charArray[i];
		}
	}

	function removeLowerCase( inputString ) {

	}

	function isUpper( Char ) {
		var upper = Char.toUpperCase();
		var lower = Char.toLowerCase();

		if ( (Char == upper) && (upper != lower) ) return true; else return false;
	}

	function isLower( Char ) {
		var upper = Char.toUpperCase();
		var lower = Char.toLowerCase();

		if ( (Char == lower) && (upper != lower) ) return true; else return false;
	}

	function isCaseless( Char ) {
		if ( Char.toUpperCase() == Char.toLowerCase() ) return true; else return false;
	}

	function getBlacklistSet( allow, disallow ) {

		var setOfBadChars  = new Set( BLACKLIST + disallow );
		var setOfGoodChars = new Set( allow );

		var blacklistSet = setOfBadChars.subtract( setOfGoodChars );

		return blacklistSet;
	}

	function getDigitsMap() {
		var array = "0123456789".split( "" );
		var map   = {};
		var i     = 0;
		var digit;

		for ( i = 0; i < array.length; i ++ ) {
			digit      = array[i];
			map[digit] = true;
		}

		return map;
	}

	function getLatinCharsSet() {
		var lower = "abcdefghijklmnopqrstuvwxyz";
		var upper = lower.toUpperCase();
		var azAZ  = new Set( lower + upper );

		return azAZ;
	}

	function allowThouSep( currentString, Char ) {

		// Can't start with a THOU_SEP
		if ( currentString.length == 0 ) return false;

		// Can't have a THOU_SEP anywhere after a DEC_SEP
		var posOfDecSep = currentString.indexOf( DEC_SEP );
		if ( posOfDecSep >= 0 ) return false;

		var posOfFirstThouSep = currentString.indexOf( THOU_SEP );

		// Check if this is the first occurrence of a THOU_SEP
		if ( posOfFirstThouSep < 0 ) return true;

		var posOfLastThouSep      = currentString.lastIndexOf( THOU_SEP );
		var charsSinceLastThouSep = currentString.length - posOfLastThouSep - 1;

		// Check if there has been 3 digits since the last THOU_SEP
		if ( charsSinceLastThouSep < 3 ) return false;

		var digitsSinceFirstThouSep = countDigits( currentString.substring( posOfFirstThouSep ) );

		// Check if there has been a multiple of 3 digits since the first THOU_SEP
		if ( (digitsSinceFirstThouSep % 3) > 0 ) return false;

		return true;
	}

	////////////////////////////////////////////////////////////////////////////////////
	// Implementation of a Set
	////////////////////////////////////////////////////////////////////////////////////
	function Set( elems ) {
		if ( typeof elems == "string" ) this.map = stringToMap( elems ); else this.map = {};
	}

	Set.prototype.add = function( set ) {

		var newSet = this.clone();

		for ( var key in set.map ) newSet.map[key] = true;

		return newSet;
	}

	Set.prototype.subtract = function( set ) {

		var newSet = this.clone();

		for ( var key in set.map ) delete newSet.map[key];

		return newSet;
	}

	Set.prototype.contains = function( key ) {
		if ( this.map[key] ) return true; else return false;
	}

	Set.prototype.clone = function() {
		var newSet = new Set();

		for ( var key in this.map ) newSet.map[key] = true;

		return newSet;
	}

	////////////////////////////////////////////////////////////////////////////////////

	function stringToMap( string ) {
		var map   = {};
		var array = string.split( "" );
		var i     = 0;
		var Char;

		for ( i = 0; i < array.length; i ++ ) {
			Char      = array[i];
			map[Char] = true;
		}

		return map;
	}

	// Backdoor for testing
	$.fn.alphanum.backdoorAlphaNum = function( inputString, settings ) {
		var combinedSettings = getCombinedSettingsAlphaNum( settings );

		return trimAlphaNum( inputString, combinedSettings );
	};

	$.fn.alphanum.backdoorNumeric = function( inputString, settings ) {
		var combinedSettings = getCombinedSettingsNum( settings );

		return trimNum( inputString, combinedSettings );
	};

	$.fn.alphanum.setNumericSeparators = function( settings ) {

		if ( settings.thousandsSeparator.length != 1 ) return;

		if ( settings.decimalSeparator.length != 1 ) return;

		THOU_SEP = settings.thousandsSeparator;
		DEC_SEP  = settings.decimalSeparator;
	}

})( jQuery );

//Include the 3rd party lib: jquery.caret.js

// Set caret position easily in jQuery
// Written by and Copyright of Luke Morton, 2011
// Licensed under MIT
(function( $ ) {
	// Behind the scenes method deals with browser
	// idiosyncrasies and such
	function caretTo( el, index ) {
		if ( el.createTextRange ) {
			var range = el.createTextRange();
			range.move( "character", index );
			range.select();
		} else if ( el.selectionStart != null ) {
			el.focus();
			el.setSelectionRange( index, index );
		}
	};

	// Another behind the scenes that collects the
	// current caret position for an element

	// TODO: Get working with Opera
	function caretPos( el ) {
		if ( "selection" in document ) {
			var range = el.createTextRange();
			try {
				range.setEndPoint( "EndToStart", document.selection.createRange() );
			} catch ( e ) {
				// Catch IE failure here, return 0 like
				// other browsers
				return 0;
			}
			return range.text.length;
		} else if ( el.selectionStart != null ) {
			return el.selectionStart;
		}
	};

	// The following methods are queued under fx for more
	// flexibility when combining with $.fn.delay() and
	// jQuery effects.

	// Set caret to a particular index
	$.fn.alphanum_caret = function( index, offset ) {
		if ( typeof (index) === "undefined" ) {
			return caretPos( this.get( 0 ) );
		}

		return this.queue( function( next ) {
			if ( isNaN( index ) ) {
				var i = $( this ).val().indexOf( index );

				if ( offset === true ) {
					i += index.length;
				} else if ( typeof (offset) !== "undefined" ) {
					i += offset;
				}

				caretTo( this, i );
			} else {
				caretTo( this, index );
			}

			next();
		} );
	};
}( jQuery ));

/**********************************************************
 * Selection Library
 * Used to determine what text is highlighted in the textbox before a key is pressed.
 * http://donejs.com/docs.html#!jQuery.fn.selection
 * https://github.com/jupiterjs/jquerymx/blob/master/dom/selection/selection.js
 ***********************************************************/
(function( $ ) {
	var convertType          = function( type ) {
		    return type.replace( /([a-z])([a-z]+)/gi, function( all, first, next ) {
			    return first + next.toLowerCase();
		    } ).replace( /_/g, "" );
	    }, reverse           = function( type ) {
		    return type.replace( /^([a-z]+)_TO_([a-z]+)/i, function( all, first, last ) {
			    return last + "_TO_" + first;
		    } );
	    }, getWindow         = function( element ) {
		    return element ? element.ownerDocument.defaultView || element.ownerDocument.parentWindow : window;
	    }, // A helper that uses range to abstract out getting the current start and endPos.
	    getElementsSelection = function( el, win ) {
		    var current                                                = $.Range.current( el ).clone(), entireElement = $.Range( el ).select( el );

		    if ( ! current.overlaps( entireElement ) ) {
			    return null;
		    }
		    // we need to check if it starts before our element ...
		    if ( current.compare( "START_TO_START", entireElement ) < 1 ) {
			    var startPos = 0;
			    // we should move current ...
			    current.move( "START_TO_START", entireElement );
		    } else {
			    var fromElementToCurrent = entireElement.clone();
			    fromElementToCurrent.move( "END_TO_START", current );

			    startPos = fromElementToCurrent.toString().length;
		    }

		    // now we need to make sure current isn't to the right of us ...
		    var endPos;

		    if ( current.compare( "END_TO_END", entireElement ) >= 0 ) {
			    endPos = entireElement.toString().length;
		    } else {
			    endPos = startPos + current.toString().length;
		    }

		    return {
			    start: startPos, end: endPos
		    };
	    }, getSelection      = function( el ) {
		    // use selectionStart if we can.
		    var win = getWindow( el );

		    if ( el.selectionStart !== undefined ) {
			    if ( document.activeElement && document.activeElement !== el && el.selectionStart === el.selectionEnd && el.selectionStart === 0 ) {
				    return {start: el.value.length, end: el.value.length};
			    }

			    return {start: el.selectionStart, end: el.selectionEnd};
		    } else if ( win.getSelection ) {
			    return getElementsSelection( el, win );
		    } else {
			    try {
				    //try 2 different methods that work differently
				    // one should only work for input elements, but sometimes doesn't
				    // I don't know why this is, or what to detect
				    if ( el.nodeName.toLowerCase() === 'input' ) {
					    var real = getWindow( el ).document.selection.createRange(), r = el.createTextRange();

					    r.setEndPoint( "EndToStart", real );

					    var start = r.text.length;

					    return {
						    start: start, end: start + real.text.length
					    };
				    } else {
					    var res = getElementsSelection( el, win );
					    if ( ! res ) {
						    return res;
					    }

					    // we have to clean up for ie's textareas
					    var current                                 = $.Range.current().clone(), r2 = current.clone().collapse().range,
					        r3                                      = current.clone().collapse( false ).range;

					    r2.moveStart( 'character', - 1 );
					    r3.moveStart( 'character', - 1 );

					    // if we aren't at the start, but previous is empty, we are at start of newline
					    if ( res.startPos !== 0 && r2.text === "" ) {
						    res.startPos += 2;
					    }

					    // do a similar thing for the end of the textarea
					    if ( res.endPos !== 0 && r3.text === "" ) {
						    res.endPos += 2;
					    }

					    return res;
				    }
			    } catch ( e ) {
				    return {start: el.value.length, end: el.value.length};
			    }
		    }
	    }, select            = function( el, start, end ) {
		    var win = getWindow( el );

		    if ( el.setSelectionRange ) {
			    if ( end === undefined ) {
				    el.focus();
				    el.setSelectionRange( start, start );
			    } else {
				    el.select();
				    el.selectionStart = start;
				    el.selectionEnd   = end;
			    }
		    } else if ( el.createTextRange ) {
			    //el.focus();
			    var r = el.createTextRange();
			    r.moveStart( 'character', start );
			    end = end || start;
			    r.moveEnd( 'character', end - el.value.length );

			    r.select();
		    } else if ( win.getSelection ) {
			    var doc                                                 = win.document, sel = win.getSelection(), range = doc.createRange(),
			        ranges                                              = [start, end !== undefined ? end : start];
			    getCharElement( [el], ranges );
			    range.setStart( ranges[0].el, ranges[0].count );
			    range.setEnd( ranges[1].el, ranges[1].count );

			    // removeAllRanges is suprisingly necessary for webkit ... BOOO!
			    sel.removeAllRanges();
			    sel.addRange( range );

		    } else if ( win.document.body.createTextRange ) { //IE's weirdness
			    var range = document.body.createTextRange();

			    range.moveToElementText( el );
			    range.collapse();
			    range.moveStart( 'character', start );
			    range.moveEnd( 'character', end !== undefined ? end : start );
			    range.select();
		    }
	    }, /*
     * If one of the range values is within start and len, replace the range
     * value with the element and its offset.
     */
	    replaceWithLess      = function( start, len, range, el ) {
		    if ( typeof range[0] === 'number' && range[0] < len ) {
			    range[0] = {
				    el: el, count: range[0] - start
			    };
		    }
		    if ( typeof range[1] === 'number' && range[1] <= len ) {
			    range[1] = {
				    el: el, count: range[1] - start
			    };
		    }
	    }, getCharElement    = function( elems, range, len ) {
		    var elem, start;

		    len = len || 0;

		    for ( var i = 0; elems[i]; i ++ ) {
			    elem = elems[i];
			    // Get the text from text nodes and CDATA nodes
			    if ( elem.nodeType === 3 || elem.nodeType === 4 ) {
				    start = len;
				    len += elem.nodeValue.length;
				    //check if len is now greater than what's in counts
				    replaceWithLess( start, len, range, elem );
				    // Traverse everything else, except comment nodes
			    } else if ( elem.nodeType !== 8 ) {
				    len = getCharElement( elem.childNodes, range, len );
			    }
		    }

		    return len;
	    };

	$.fn.selection = function( start, end ) {
		if ( start !== undefined ) {
			return this.each( function() {
				select( this, start, end );
			} );
		} else {
			return getSelection( this[0] );
		}
	};

	// for testing
	$.fn.selection.getCharElement = getCharElement;
})( jQuery );

// jscs:disable
// jshint ignore: start

/*
 * serializeForm
 * https://github.com/danheberden/serializeForm
 *
 * Copyright (c) 2012 Dan Heberden
 * Licensed under the MIT, GPL licenses.
 */
(function( $ ) {
	$.fn.serializeForm = function() {

		// don't do anything if we didn't get any elements.
		if ( this.length < 1 ) {
			return false;
		}

		var data     = {};
		var lookup   = data; // current reference of data.
		var selector = ':input[type!="checkbox"][type!="radio"], input:checked';
		var parse    = function() {

			// Ignore disabled elements.
			if ( this.disabled ) {
				return;
			}

			// data[a][b] becomes [ data, a, b ].
			var named = this.name.replace( /\[([^\]]+)?\]/g, ',$1' ).split( ',' );
			var cap   = named.length - 1;
			var $el   = $( this );

			// Ensure that only elements with valid `name` properties will be serialized.
			if ( named[0] ) {
				for ( var i = 0; i < cap; i ++ ) {
					// move down the tree - create objects or array if necessary.
					lookup = lookup[named[i]] = lookup[named[i]] || ((named[i + 1] === "" || named[i + 1] === '0') ? [] : {});
				}

				// at the end, push or assign the value.
				if ( lookup.length !== undefined ) {
					lookup.push( $el.val() );
				} else {
					lookup[named[cap]] = $el.val();
				}

				// assign the reference back to root.
				lookup = data;
			}
		};

		// first, check for elements passed into this function.
		this.filter( selector ).each( parse );

		// then parse possible child elements.
		this.find( selector ).each( parse );

		// return data.
		return data;
	};
}( jQuery ));

// jscs:disable
// jshint ignore: start

/*
*	TypeWatch 3
*
*	Examples/Docs: github.com/dennyferra/TypeWatch
*
*  Dual licensed under the MIT and GPL licenses:
*  http://www.opensource.org/licenses/mit-license.php
*  http://www.gnu.org/licenses/gpl.html
*/

!function(root, factory) {
	if (typeof define === 'function' && define.amd) {
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		factory(require('jquery'));
	} else {
		factory(root.jQuery);
	}
}(this, function($) {
	'use strict';
	$.fn.typeWatch = function(o) {
		// The default input types that are supported
		var _supportedInputTypes =
			    ['TEXT', 'TEXTAREA', 'PASSWORD', 'TEL', 'SEARCH', 'URL', 'EMAIL', 'DATETIME', 'DATE', 'MONTH', 'WEEK', 'TIME', 'DATETIME-LOCAL', 'NUMBER', 'RANGE', 'DIV'];

		// Options
		var options = $.extend({
			wait: 750,
			callback: function() { },
			highlight: true,
			captureLength: 2,
			allowSubmit: false,
			inputTypes: _supportedInputTypes
		}, o);

		function checkElement(timer, override) {
			var value = timer.type === 'DIV'
				? jQuery(timer.el).html()
				: jQuery(timer.el).val();

			// If has capture length and has changed value
			// Or override and has capture length or allowSubmit option is true
			// Or capture length is zero and changed value
			if ((value.length >= options.captureLength && value != timer.text)
				|| (override && (value.length >= options.captureLength || options.allowSubmit))
				|| (value.length == 0 && timer.text))
			{
				timer.text = value;
				timer.cb.call(timer.el, value);
			}
		};

		function watchElement(elem) {
			var elementType = (elem.type || elem.nodeName).toUpperCase();
			if (jQuery.inArray(elementType, options.inputTypes) >= 0) {

				// Allocate timer element
				var timer = {
					timer: null,
					text: (elementType === 'DIV') ? jQuery(elem).html() : jQuery(elem).val(),
					cb: options.callback,
					el: elem,
					type: elementType,
					wait: options.wait
				};

				// Set focus action (highlight)
				if (options.highlight && elementType !== 'DIV')
					jQuery(elem).focus(function() { this.select(); });

				// Key watcher / clear and reset the timer
				var startWatch = function(evt) {
					var timerWait = timer.wait;
					var overrideBool = false;
					var evtElementType = elementType;

					// If enter key is pressed and not a TEXTAREA or DIV
					if (typeof evt.keyCode != 'undefined' && evt.keyCode == 13
						&& evtElementType !== 'TEXTAREA' && elementType !== 'DIV') {
						timerWait = 1;
						overrideBool = true;
					}

					var timerCallbackFx = function() {
						checkElement(timer, overrideBool)
					}

					// Clear timer
					clearTimeout(timer.timer);
					timer.timer = setTimeout(timerCallbackFx, timerWait);
				};

				jQuery(elem).on('keydown paste cut input', startWatch);
			}
		};

		// Watch each element
		return this.each(function() {
			watchElement(this);
		});
	};
});

// jscs:disable
// jshint ignore: start

/* global console, jsonView */

/*
 * ViewJSON
 * Version 1.0
 * A Google Chrome extension to display JSON in a user-friendly format
 *
 * This is a chromeified version of the JSONView Firefox extension by Ben Hollis:
 * http://jsonview.com
 * http://code.google.com/p/jsonview
 *
 * Also based on the XMLTree Chrome extension by Moonty & alan.stroop
 * https://chrome.google.com/extensions/detail/gbammbheopgpmaagmckhpjbfgdfkpadb
 *
 * port by Jamie Wilkinson (@jamiew) | http://jamiedubs.com | http://github.com/jamiew
 * MIT license / copyfree (f) F.A.T. Lab http://fffff.at
 * Speed Project Approved: 2h
 */

function collapse( evt ) {
	var collapser = evt.target;
	var target    = collapser.parentNode.getElementsByClassName( 'collapsible' );
	if ( ! target.length ) {
		return;
	}
	target = target[0];
	if ( target.style.display === 'none' ) {
		var ellipsis = target.parentNode.getElementsByClassName( 'ellipsis' )[0];
		target.parentNode.removeChild( ellipsis );
		target.style.display = '';
	} else {
		target.style.display = 'none';
		var ellipsis         = document.createElement( 'span' );
		ellipsis.className   = 'ellipsis';
		ellipsis.innerHTML   = ' &hellip; ';
		target.parentNode.insertBefore( ellipsis, target );
	}
	collapser.innerHTML = (collapser.innerHTML === '-') ? '+' : '-';
}

function addCollapser( item ) {
	// This mainly filters out the root object (which shouldn't be collapsible).
	if ( item.nodeName !== 'LI' ) {
		return;
	}
	var collapser       = document.createElement( 'div' );
	collapser.className = 'collapser';
	collapser.innerHTML = '-';
	collapser.addEventListener( 'click', collapse, false );
	item.insertBefore( collapser, item.firstChild );
}

function jsonView( id, target ) {
	this.debug = false;
	if ( id.indexOf( '#' ) !== - 1 ) {
		this.idType = 'id';
		this.id     = id.replace( '#', '' );
	} else if ( id.indexOf( '.' ) !== - 1 ) {
		this.idType = 'class';
		this.id     = id.replace( '.', '' );
	} else {
		if ( this.debug ) {
			console.log( 'Can\'t find that element' );
		}
		return;
	}

	this.data = document.getElementById( this.id ).innerHTML;
	if ( typeof (target) !== undefined ) {
		if ( target.indexOf( '#' ) !== - 1 ) {
			this.targetType = 'id';
			this.target     = target.replace( '#', '' );
		} else if ( id.indexOf( '.' ) !== - 1 ) {
			this.targetType = 'class';
			this.target     = target.replace( '.', '' );
		} else {
			if ( this.debug ) {
				console.log( 'Can\'t find the target element' );
			}
			return;
		}
	}
	// Note: now using "*.json*" URI matching rather than these page regexes -- save CPU cycles!
	// var is_json = /^\s*(\{.*\})\s*$/.test(this.data);
	// var is_jsonp = /^.*\(\s*(\{.*\})\s*\)$/.test(this.data);
	// if(is_json || is_jsonp){
	// Our manifest specifies that we only do URLs matching '.json', so attempt to sanitize any HTML
	// added by Chrome's "text/plain" or "text/html" handlers.
	if ( /^\<pre.*\>(.*)\<\/pre\>$/.test( this.data ) ) {
		if ( this.debug ) {
			console.log( 'JSONView: data is wrapped in <pre>...</pre>, stripping HTML...' );
		}
		this.data = this.data.replace( /<(?:.|\s)*?>/g, '' ); // Aggressively strip HTML.
	}
	// Test if what remains is JSON or JSONp.
	var json_regex   = /^\s*([\[\{].*[\}\]])\s*$/; // Ghetto, but it works.
	var jsonp_regex  = /^[\s\u200B\uFEFF]*([\w$\[\]\.]+)[\s\u200B\uFEFF]*\([\s\u200B\uFEFF]*([\[{][\s\S]*[\]}])[\s\u200B\uFEFF]*\);?[\s\u200B\uFEFF]*$/;
	var jsonp_regex2 = /([\[\{][\s\S]*[\]\}])\)/; // more liberal support... this allows us to pass the jsonp.json & jsonp2.json tests.
	var is_json      = json_regex.test( this.data );
	var is_jsonp     = jsonp_regex.test( this.data );
	if ( this.debug ) {
		console.log( 'JSONView: is_json=' + is_json + ' is_jsonp=' + is_jsonp );
	}
	if ( is_json || is_jsonp ) {
		if ( this.debug ) {
			console.log( 'JSONView: sexytime!' );
		}
		// JSONFormatter json->HTML prototype straight from Firefox JSONView
		// For reference: http://code.google.com/p/jsonview.
		function JSONFormatter() {
			// No magic required.
		}

		JSONFormatter.prototype = {
			htmlEncode: function( t ) {
				return t != null ? t.toString().replace( /&/g, '&amp;' ).replace( /"/g, '&quot;' ).replace( /</g, '&lt;' ).replace( />/g, '&gt;' ) : '';
			}, decorateWithSpan: function( value, className ) {
				return '<span class="' + className + '">' + this.htmlEncode( value ) + '</span>';
			}, // Convert a basic JSON datatype (number, string, boolean, null, object, array) into an HTML fragment.
			valueToHTML: function( value ) {
				var valueType = typeof value;
				var output    = '';
				if ( value === null ) {
					output += this.decorateWithSpan( 'null', 'null' );
				} else if ( value && value.constructor === Array ) {
					output += this.arrayToHTML( value );
				} else if ( valueType === 'object' ) {
					output += this.objectToHTML( value );
				} else if ( valueType === 'number' ) {
					output += this.decorateWithSpan( value, 'num' );
				} else if ( valueType === 'string' ) {
					if ( /^(http|https):\/\/[^\s]+$/.test( value ) ) {
						output += '<a href="' + value + '">' + this.htmlEncode( value ) + '</a>';
					} else {
						output += this.decorateWithSpan( '"' + value + '"', 'string' );
					}
				} else if ( valueType === 'boolean' ) {
					output += this.decorateWithSpan( value, 'bool' );
				}
				return output;
			}, // Convert an array into an HTML fragment
			arrayToHTML: function( json ) {
				var output      = '[<ul class="array collapsible">';
				var hasContents = false;
				for ( var prop in json ) {
					hasContents = true;
					output     += '<li>';
					output     += this.valueToHTML( json[prop] );
					output     += '</li>';
				}
				output += '</ul>]';
				if ( ! hasContents ) {
					output = '[ ]';
				}
				return output;
			}, // Convert a JSON object to an HTML fragment
			objectToHTML: function( json ) {
				var output      = '{<ul class="obj collapsible">';
				var hasContents = false;
				for ( var prop in json ) {
					hasContents = true;
					output     += '<li>';
					output     += '<span class="prop">' + this.htmlEncode( prop ) + '</span>: ';
					output     += this.valueToHTML( json[prop] );
					output     += '</li>';
				}
				output += '</ul>}';
				if ( ! hasContents ) {
					output = '{ }';
				}
				return output;
			}, // Convert a whole JSON object into a formatted HTML document.
			jsonToHTML: function( json, callback, uri ) {
				var output = '';
				if ( callback ) {
					output += '<div class="callback">' + callback + ' (</div>';
					output += '<div id="json">';
				} else {
					output += '<div id="json">';
				}
				output += this.valueToHTML( json );
				output += '</div>';
				if ( callback ) {
					output += '<div class="callback">)</div>';
				}
				return this.toHTML( output, uri );
			}, // Produce an error document for when parsing fails.
			errorPage: function( error, data, uri ) {
				// var output = '<div id="error">' + this.stringbundle.GetStringFromName('errorParsing') + '</div>';
				// output += '<h1>' + this.stringbundle.GetStringFromName('docContents') + ':</h1>';.
				var output = '<div id="error">Error parsing JSON: ' + error.message + '</div>';
				output    += '<h1>' + error.stack + ':</h1>';
				output    += '<div id="json">' + this.htmlEncode( data ) + '</div>';
				return this.toHTML( output, uri + ' - Error' );
			}, // Wrap the HTML fragment in a full document. Used by jsonToHTML and errorPage.
			toHTML: function( content ) {
				return content;
			}
		};
		// Sanitize & output -- all magic from JSONView Firefox.
		this.jsonFormatter = new JSONFormatter();
		// This regex attempts to match a JSONP structure:
		// * Any amount of whitespace (including unicode nonbreaking spaces) between the start of the file and the callback name.
		// * Callback name (any valid JavaScript function name according to ECMA-262 Edition 3 spec).
		// * Any amount of whitespace (including unicode nonbreaking spaces).
		// * Open parentheses.
		// * Any amount of whitespace (including unicode nonbreaking spaces).
		// * Either { or [, the only two valid characters to start a JSON string.
		// * Any character, any number of times.
		// * Either } or ], the only two valid closing characters of a JSON string.
		// * Any amount of whitespace (including unicode nonbreaking spaces).
		// * A closing parenthesis, an optional semicolon, and any amount of whitespace (including unicode nonbreaking spaces) until the end of the file.
		// This will miss anything that has comments, or more than one callback, or requires modification before use.
		var outputDoc = '';
		// text = text.match(jsonp_regex)[1]; .
		var cleanData        = '', callback = '';
		var callback_results = jsonp_regex.exec( this.data );
		if ( callback_results && callback_results.length === 3 ) {
			if ( this.debug ) {
				console.log( 'THIS IS JSONp' );
			}
			callback  = callback_results[1];
			cleanData = callback_results[2];
		} else {
			if ( this.debug ) {
				console.log( 'Vanilla JSON' );
			}
			cleanData = this.data;
		}
		if ( this.debug ) {
			console.log( cleanData );
		}
		// Covert, and catch exceptions on failure.
		try {
			// var jsonObj = this.nativeJSON.decode(cleanData); .
			var jsonObj = JSON.parse( cleanData );
			if ( jsonObj ) {
				outputDoc = this.jsonFormatter.jsonToHTML( jsonObj, callback );
			} else {
				throw 'There was no object!';
			}
		} catch ( e ) {
			if ( this.debug ) {
				console.log( e );
			}
			outputDoc = this.jsonFormatter.errorPage( e, this.data );
		}
		var links = '<style type="text/css">.jsonViewOutput .prop{font-weight:700;}.jsonViewOutput .null{color:red;}.jsonViewOutput .string{color:green;}.jsonViewOutput .collapser{position:absolute;left:-1em;cursor:pointer;}.jsonViewOutput li{position:relative;}.jsonViewOutput li:after{content:\',\';}.jsonViewOutput li:last-child:after{content:\'\';}.jsonViewOutput #error{-moz-border-radius:8px;border:1px solid #970000;background-color:#F7E8E8;margin:.5em;padding:.5em;}.jsonViewOutput .errormessage{font-family:monospace;}.jsonViewOutput #json{font-family:monospace;font-size:1.1em;}.jsonViewOutput ul{list-style:none;margin:0 0 0 2em;padding:0;}.jsonViewOutput h1{font-size:1.2em;}.jsonViewOutput .callback + #json{padding-left:1em;}.jsonViewOutput .callback{font-family:monospace;color:#A52A2A;}.jsonViewOutput .bool,.jsonViewOutput .num{color:blue;}</style>';
		if ( this.targetType !== undefined ) {
			this.idType = this.targetType;
			this.id     = this.target;
		}
		var el;
		if ( this.idType === 'class' ) {
			el = document.getElementsByClassName( this.id );
			if ( el ) {
				el.className += el.className ? ' jsonViewOutput' : 'jsonViewOutput';
				el.innerHTML  = links + outputDoc;
			}
		} else if ( this.idType === 'id' ) {
			el = document.getElementById( this.id );
			if ( el ) {
				el.className += el.className ? ' jsonViewOutput' : 'jsonViewOutput';
				el.innerHTML  = links + outputDoc;
			}
			el.innerHTML = links + outputDoc;
		}
		var items = document.getElementsByClassName( 'collapsible' );
		var len   = items.length;

		for ( var i = 0; i < len; i ++ ) {
			addCollapser( items[i].parentNode );
		}
	} else {
		// console.log("JSONView: this is not json, not formatting."); .
	}
}
