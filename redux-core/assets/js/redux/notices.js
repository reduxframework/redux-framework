/* global redux */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.sanitize = function() {
		if ( redux.optName.sanitize && redux.optName.sanitize.sanitize ) {
			$.each(
				redux.optName.sanitize.sanitize,
				function( sectionID, sectionArray ) {
					sectionID = null;
					$.each(
						sectionArray.sanitize,
						function( key, value ) {
							$.redux.fixInput( key, value );
						}
					);
				}
			);
		}
	};

	$.redux.fixInput = function( key, value ) {
		var val;
		var input;
		var inputVal;
		var ul;
		var li;

		if ( 'multi_text' === value.type ) {
			ul = $( '#' + value.id + '-ul' );
			li = $( ul.find( 'li' ) );

			li.each(
				function() {
					input    = $( this ).find( 'input' );
					inputVal = input.val();

					if ( inputVal === value.old ) {
						input.val( value.current );
					}
				}
			);

			return;
		}

		input = $( 'input#' + value.id + '-' + key );

		if ( 0 === input.length ) {
			input = $( 'input#' + value.id );
		}

		if ( 0 === input.length ) {
			input = $( 'textarea#' + value.id + '-textarea' );
		}

		if ( input.length > 0 ) {
			val = '' === value.current ? value.default : value.current;

			$( input ).val( val );
		}
	};

	$.redux.notices = function() {
		if ( redux.optName.errors && redux.optName.errors.errors ) {
			$.each(
				redux.optName.errors.errors,
				function( sectionID, sectionArray ) {
					sectionID = null;
					$.each(
						sectionArray.errors,
						function( key, value ) {
							$( '#' + redux.optName.args.opt_name + '-' + value.id ).addClass( 'redux-field-error' );
							if ( 0 === $( '#' + redux.optName.args.opt_name + '-' + value.id ).parent().find( '.redux-th-error' ).length ) {
								$( '#' + redux.optName.args.opt_name + '-' + value.id ).append( '<div class="redux-th-error">' + value.msg + '</div>' );
							} else {
								$( '#' + redux.optName.args.opt_name + '-' + value.id ).parent().find( '.redux-th-error' ).html( value.msg ).css( 'display', 'block' );
							}

							$.redux.fixInput( key, value );
						}
					);
				}
			);

			$( '.redux-container' ).each(
				function() {
					var totalErrors;

					var container = $( this );

					// Ajax cleanup.
					container.find( '.redux-menu-error' ).remove();

					totalErrors = container.find( '.redux-field-error' ).length;

					if ( totalErrors > 0 ) {
						container.find( '.redux-field-errors span' ).text( totalErrors );
						container.find( '.redux-field-errors' ).slideDown();
						container.find( '.redux-group-tab' ).each(
							function() {
								var sectionID;
								var subParent;

								var total = $( this ).find( '.redux-field-error' ).length;
								if ( total > 0 ) {
									sectionID = $( this ).attr( 'id' ).split( '_' );

									sectionID = sectionID[0];
									container.find( '.redux-group-tab-link-a[data-key="' + sectionID + '"]' ).prepend( '<span class="redux-menu-error">' + total + '</span>' );
									container.find( '.redux-group-tab-link-a[data-key="' + sectionID + '"]' ).addClass( 'hasError' );

									subParent = container.find( '.redux-group-tab-link-a[data-key="' + sectionID + '"]' ).parents( '.hasSubSections:first' );

									if ( subParent ) {
										subParent.find( '.redux-group-tab-link-a:first' ).addClass( 'hasError' );
									}
								}
							}
						);
					}
				}
			);
		}

		if ( redux.optName.warnings && redux.optName.warnings.warnings ) {
			$.each(
				redux.optName.warnings.warnings,
				function( sectionID, sectionArray ) {
					sectionID = null;
					$.each(
						sectionArray.warnings,
						function( key, value ) {
							$( '#' + redux.optName.args.opt_name + '-' + value.id ).addClass( 'redux-field-warning' );

							if ( 0 === $( '#' + redux.optName.args.opt_name + '-' + value.id ).parent().find( '.redux-th-warning' ).length ) {
								$( '#' + redux.optName.args.opt_name + '-' + value.id ).append( '<div class="redux-th-warning">' + value.msg + '</div>' );
							} else {
								$( '#' + redux.optName.args.opt_name + '-' + value.id ).parent().find( '.redux-th-warning' ).html( value.msg ).css( 'display', 'block' );
							}

							$.redux.fixInput( key, value );
						}
					);
				}
			);

			$( '.redux-container' ).each(
				function() {
					var sectionID;
					var subParent;
					var total;
					var totalWarnings;

					var container = $( this );

					// Ajax cleanup.
					container.find( '.redux-menu-warning' ).remove();

					totalWarnings = container.find( '.redux-field-warning' ).length;

					if ( totalWarnings > 0 ) {
						container.find( '.redux-field-warnings span' ).text( totalWarnings );
						container.find( '.redux-field-warnings' ).slideDown();
						container.find( '.redux-group-tab' ).each(
							function() {
								total = $( this ).find( '.redux-field-warning' ).length;

								if ( total > 0 ) {
									sectionID = $( this ).attr( 'id' ).split( '_' );

									sectionID = sectionID[0];
									container.find( '.redux-group-tab-link-a[data-key="' + sectionID + '"]' ).prepend( '<span class="redux-menu-warning">' + total + '</span>' );
									container.find( '.redux-group-tab-link-a[data-key="' + sectionID + '"]' ).addClass( 'hasWarning' );

									subParent = container.find( '.redux-group-tab-link-a[data-key="' + sectionID + '"]' ).parents( '.hasSubSections:first' );

									if ( subParent ) {
										subParent.find( '.redux-group-tab-link-a:first' ).addClass( 'hasWarning' );
									}
								}
							}
						);
					}
				}
			);
		}
	};
})( jQuery );
