/* global redux */

(function ( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.sanitize = function () {
		if ( redux.optName.sanitize && redux.optName.sanitize.sanitize ) {
			$.each(
				redux.optName.sanitize.sanitize,
				function ( sectionID, sectionArray ) {
					sectionID = null;
					$.each(
						sectionArray.sanitize,
						function ( key, value ) {
							$.redux.fixInput( key, value );
						}
					);
				}
			);
		}
	};

	$.redux.fixInput = function ( key, value ) {
		let val;
		let input;
		let inputVal;
		let ul;
		let li;

		if ( 'multi_text' === value.type ) {
			ul = $( '#' + value.id + '-ul' );
			li = $( ul.find( 'li' ) );

			li.each(
				function () {
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

	$.redux.notices = function () {
		if ( redux.optName.errors && redux.optName.errors.errors ) {
			$.each(
				redux.optName.errors.errors,
				function ( sectionID, sectionArray ) {
					sectionID = null;
					$.each(
						sectionArray.errors,
						function ( key, value ) {
							const fieldset = $( '#' + redux.optName.args.opt_name + '-' + value.id );

							if ( '' !== value.msg ) {
								fieldset.addClass( 'redux-field-error' );
							}

							if ( 0 === fieldset.parent().find( '.redux-th-error' ).length ) {
								fieldset.append( '<div class="redux-th-error">' + value.msg + '</div>' );
							} else {
								fieldset.parent().find( '.redux-th-error' ).html( value.msg ).css( 'display', 'block' );
							}

							$.redux.fixInput( key, value );
						}
					);
				}
			);

			$( '.redux-container' ).each(
				function () {
					let totalErrors;
					const container = $( this );

					// Ajax cleanup.
					container.find( '.redux-menu-error' ).remove();

					totalErrors = container.find( '.redux-field-error' ).length;

					if ( totalErrors > 0 ) {
						container.find( '.redux-field-errors span' ).text( totalErrors );
						container.find( '.redux-field-errors' ).slideDown();
						container.find( '.redux-group-tab' ).each(
							function () {
								let sectionID;
								let subParent;

								const total = $( this ).find( '.redux-field-error' ).length;

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
				function ( sectionID, sectionArray ) {
					sectionID = null;
					$.each(
						sectionArray.warnings,
						function ( key, value ) {
							const fieldset = $( '#' + redux.optName.args.opt_name + '-' + value.id );

							if ( '' !== value.msg ) {
								fieldset.addClass( 'redux-field-warning' );
							}

							if ( 0 === fieldset.parent().find( '.redux-th-warning' ).length ) {
								fieldset.append( '<div class="redux-th-warning">' + value.msg + '</div>' );
							} else {
								fieldset.parent().find( '.redux-th-warning' ).html( value.msg ).css( 'display', 'block' );
							}

							$.redux.fixInput( key, value );
						}
					);
				}
			);

			$( '.redux-container' ).each(
				function () {
					let sectionID;
					let subParent;
					let total;
					let totalWarnings;

					const container = $( this );

					// Ajax cleanup.
					container.find( '.redux-menu-warning' ).remove();

					totalWarnings = container.find( '.redux-field-warning' ).length;

					if ( totalWarnings > 0 ) {
						container.find( '.redux-field-warnings span' ).text( totalWarnings );
						container.find( '.redux-field-warnings' ).slideDown();
						container.find( '.redux-group-tab' ).each(
							function () {
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
