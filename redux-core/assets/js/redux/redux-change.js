/* jshint unused:false */
/* global redux */

var confirmOnPageExit = function( e ) {

	// Return; // ONLY FOR DEBUGGING.
	// If we haven't been passed the event get the window.event.
	'use strict';

	var message;

	e = e || window.event;

	message = redux.optName.args.save_pending;

	// For IE6-8 and Firefox prior to version 4.
	if ( e ) {
		e.returnValue = message;
	}

	window.onbeforeunload = null;

	// For Chrome, Safari, IE8+ and Opera 12+.
	return message;
};

function redux_change( variable ) {
	'use strict';

	(function( $ ) {
		var rContainer;
		var opt_name;
		var parentID;
		var id;
		var th;
		var li;
		var subParent;
		var errorCount;
		var errorsLeft;
		var warningCount;
		var warningsLeft;

		variable = $( variable );

		rContainer = $( variable ).parents( '.redux-container:first' );

		if ( redux.customizer ) {
			opt_name = $( '.redux-customizer-opt-name' ).data( 'opt-name' );
		} else {
			opt_name = $.redux.getOptName( rContainer );
		}

		$( 'body' ).trigger( 'check_dependencies', variable );

		if ( variable.hasClass( 'compiler' ) ) {
			$( '#redux-compiler-hook' ).val( 1 );
		}

		parentID = $( variable ).closest( '.redux-group-tab' ).attr( 'id' );

		// Let's count down the errors now. Fancy.  ;).
		id = parentID.split( '_' );

		id = id[0];

		th        = rContainer.find( '.redux-group-tab-link-a[data-key="' + id + '"]' ).parents( '.redux-group-tab-link-li:first' );
		li        = $( '#' + parentID + '_li' );
		subParent = li.parents( '.hasSubSections:first' );

		if ( $( variable ).parents( 'fieldset.redux-field:first' ).hasClass( 'redux-field-error' ) ) {
			$( variable ).parents( 'fieldset.redux-field:first' ).removeClass( 'redux-field-error' );
			$( variable ).parents().find( '.redux-th-error' ).slideUp();

			errorCount = ( parseInt( rContainer.find( '.redux-field-errors span' ).text(), 0 ) - 1 );

			if ( errorCount <= 0 ) {
				$( '#' + parentID + '_li .redux-menu-error' ).fadeOut( 'fast' ).remove();
				$( '#' + parentID + '_li .redux-group-tab-link-a' ).removeClass( 'hasError' );
				li.parents( '.inside:first' ).find( '.redux-field-errors' ).slideUp();
				$( variable ).parents( '.redux-container:first' ).find( '.redux-field-errors' ).slideUp();
				$( '#redux_metaboxes_errors' ).slideUp();
			} else {
				errorsLeft = ( parseInt( th.find( '.redux-menu-error:first' ).text(), 0 ) - 1 );

				if ( errorsLeft <= 0 ) {
					th.find( '.redux-menu-error:first' ).fadeOut().remove();
				} else {
					th.find( '.redux-menu-error:first' ).text( errorsLeft );
				}

				rContainer.find( '.redux-field-errors span' ).text( errorCount );
			}

			if ( 0 !== subParent.length ) {
				if ( 0 === subParent.find( '.redux-menu-error' ).length ) {
					subParent.find( '.hasError' ).removeClass( 'hasError' );
				}
			}
		}

		if ( $( variable ).parents( 'fieldset.redux-field:first' ).hasClass( 'redux-field-warning' ) ) {
			$( variable ).parents( 'fieldset.redux-field:first' ).removeClass( 'redux-field-warning' );
			$( variable ).parent().find( '.redux-th-warning' ).slideUp();

			warningCount = ( parseInt( rContainer.find( '.redux-field-warnings span' ).text(), 0 ) - 1 );

			if ( warningCount <= 0 ) {
				$( '#' + parentID + '_li .redux-menu-warning' ).fadeOut( 'fast' ).remove();
				$( '#' + parentID + '_li .redux-group-tab-link-a' ).removeClass( 'hasWarning' );
				li.parents( '.inside:first' ).find( '.redux-field-warnings' ).slideUp();
				$( variable ).parents( '.redux-container:first' ).find( '.redux-field-warnings' ).slideUp();
				$( '#redux_metaboxes_warnings' ).slideUp();
			} else {

				// Let's count down the warnings now. Fancy.  ;).
				warningsLeft = ( parseInt( th.find( '.redux-menu-warning:first' ).text(), 0 ) - 1 );

				if ( warningsLeft <= 0 ) {
					th.find( '.redux-menu-warning:first' ).fadeOut().remove();
				} else {
					th.find( '.redux-menu-warning:first' ).text( warningsLeft );
				}

				rContainer.find( '.redux-field-warning span' ).text( warningCount );
			}

			if ( 0 !== subParent.length ) {
				if ( 0 === subParent.find( '.redux-menu-warning' ).length ) {
					subParent.find( '.hasWarning' ).removeClass( 'hasWarning' );
				}
			}
		}

		// Don't show the changed value notice while save_notice is visible.
		if ( rContainer.find( '.saved_notice:visible' ).length > 0 ) {
			return;
		}

		if ( ! redux.optName.args.disable_save_warn ) {
			rContainer.find( '.redux-save-warn' ).slideDown();
			window.onbeforeunload = confirmOnPageExit;
		}
	})( jQuery );
}
