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
