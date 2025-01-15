/* global redux, redux_change, jQuery */
// noinspection JSUnresolvedReference

(function ( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.initEvents = function ( el ) {
		let stickyHeight;

		el.find( '.redux-presets-bar' ).on(
			'click',
			function () {
				window.onbeforeunload = null;
			}
		);

		if ( true === redux.optName.args.search ) {
			const url          = window.location.href;
			const wordAfterUrl = url.split( '/' ).pop();

			if ( 'profile.php' === wordAfterUrl || 0 === wordAfterUrl.indexOf( 'edit-tags.php' ) ) {
				return false;
			}

			if ( 0 === $( '#customize-controls' ).length ) {
				$( '.redux-container' ).each(
					function ( ) {
						if ( ! $( this ).hasClass( 'redux-no-sections' ) ) {
							$( this ).find( '.redux-main' ).prepend( '<input class="redux_field_search" id="redux_field_search" type="text" placeholder="' + redux.optName.search + '"/>' );
						}
					}
				);

				$( '.redux_field_search' ).on(
					'keypress',
					function ( evt ) {

						// Determine where our character code is coming from within the event.
						const charCode = evt.charCode || evt.keyCode;

						if ( 13 === charCode ) { // Enter key's keycode.
							return false;
						}
					}
				).typeWatch(
					{
						callback: function ( searchString ) {
							let searchArray;
							let parent;
							let expanded_options;

							searchString = searchString.toLowerCase();

							searchArray = searchString.split( ' ' );
							parent      = $( this ).parents( '.redux-container:first' );

							expanded_options = parent.find( '.expand_options' );

							if ( '' !== searchString ) {
								if ( ! expanded_options.hasClass( 'expanded' ) ) {
									expanded_options.trigger( 'click' );
									parent.find( '.redux-main' ).addClass( 'redux-search' );
								}
							} else {
								if ( expanded_options.hasClass( 'expanded' ) ) {
									expanded_options.trigger( 'click' );
									parent.find( '.redux-main' ).removeClass( 'redux-search' );
								}
								parent.find( '.redux-section-field, .redux-info-field, .redux-notice-field, .redux-container-group, .redux-section-desc, .redux-group-tab h3' ).show();
							}

							parent.find( '.redux-field-container' ).each(
								function () {
									if ( '' !== searchString ) {
										$( this ).parents( 'tr:first' ).hide();
									} else {
										$( this ).parents( 'tr:first' ).show();
									}
								}
							);

							parent.find( '.form-table tr' ).filter(
								function () {
									let isMatch = true, text = $( this ).find( '.redux_field_th' ).text().toLowerCase();

									if ( ! text || '' === text ) {
										return false;
									}

									$.each(
										searchArray,
										function ( i, searchStr ) {
											if ( -1 === text.indexOf( searchStr ) ) {
												isMatch = false;
											}
										}
									);

									if ( isMatch ) {
										$( this ).show();
									}

									return isMatch;
								}
							).show();
						},
						wait: 400,
						highlight: false,
						captureLength: 0
					}
				);
			}
		}

		// Customizer save hook.
		el.find( '#customize-save-button-wrapper #save' ).on(
			'click',
			function () {

			}
		);

		el.find( '#toplevel_page_' + redux.optName.args.slug + ' .wp-submenu a, #wp-admin-bar-' + redux.optName.args.slug + ' a.ab-item' ).on(
			'click',
			function ( e ) {
				let url;

				if ( ( el.find( '#toplevel_page_' + redux.optName.args.slug ).hasClass( 'wp-menu-open' ) ||
					$( this ).hasClass( 'ab-item' ) ) &&
					! $( this ).parents( 'ul.ab-submenu:first' ).hasClass( 'ab-sub-secondary' ) &&
					$( this ).attr( 'href' ).toLowerCase().indexOf( redux.optName.args.slug + '&tab=' ) >= 0 ) {

					url = $( this ).attr( 'href' ).split( '&tab=' );

					e.preventDefault();

					el.find( '#' + url[1] + '_section_group_li_a' ).trigger( 'click' );

					$( this ).parents( 'ul:first' ).find( '.current' ).removeClass( 'current' );
					$( this ).addClass( 'current' );
					$( this ).parent().addClass( 'current' );

					return false;
				}
			}
		);

		// Save button clicked.
		el.find( '.redux-action_bar input, #redux-import-action input' ).on(
			'click',
			function ( e ) {
				if ( $( this ).attr( 'name' ) === redux.optName.args.opt_name + '[defaults]' ) {

					// Defaults button clicked.
					if ( ! confirm( redux.optName.args.reset_confirm ) ) {
						return false;
					}
				} else if ( $( this ).attr( 'name' ) === redux.optName.args.opt_name + '[defaults-section]' ) {

					// Default section clicked.
					if ( ! confirm( redux.optName.args.reset_section_confirm ) ) {
						return false;
					}
				} else if ( 'import' === $( this ).attr( 'name' ) ) {
					if ( ! confirm( redux.optName.args.import_section_confirm ) ) {
						return false;
					}
				}

				window.onbeforeunload = null;

				if ( true === redux.optName.args.ajax_save ) {
					$.redux.ajax_save( $( this ) );
					e.preventDefault();
				} else {
					location.reload( true );
				}
			}
		);

		$( '.expand_options' ).on(
			'click',
			function ( e ) {
				let tab;
				const container = el;

				e.preventDefault();

				if ( $( container ).hasClass( 'fully-expanded' ) ) {
					$( container ).removeClass( 'fully-expanded' );

					tab = $.cookie( 'redux_current_tab_' + redux.optName.args.opt_name );

					el.find( '#' + tab + '_section_group' ).fadeIn(
						200,
						function () {
							if ( 0 !== el.find( '#redux-footer' ).length ) {
								$.redux.stickyInfo(); // Race condition fix.
							}

							$.redux.initFields();
						}
					);
				}

				$.redux.expandOptions( $( this ).parents( '.redux-container:first' ) );

				return false;
			}
		);

		if ( el.find( '.saved_notice' ).is( ':visible' ) ) {
			el.find( '.saved_notice' ).slideDown();
		}

		$( document.body ).on(
			'change',
			'.redux-field input, .redux-field textarea, .redux-field select',
			function () {
				if ( $( '.redux-container-typography select' ).hasClass( 'ignore-change' ) ) {
					return;
				}
				if ( ! $( this ).hasClass( 'noUpdate' ) && ! $( this ).hasClass( 'no-update' ) ) {
					redux_change( $( this ) );
				}
			}
		);

		stickyHeight = el.find( '#redux-footer' ).height();

		el.find( '#redux-sticky-padder' ).css(
			{ height: stickyHeight }
		);

		el.find( '#redux-footer-sticky' ).removeClass( 'hide' );

		if ( 0 !== el.find( '#redux-footer' ).length ) {
			$( window ).on(
				'scroll',
				function () {
					$.redux.stickyInfo();
				}
			);

			$( window ).on(
				'resize',
				function () {
					$.redux.stickyInfo();
				}
			);
		}

		el.find( '.saved_notice' ).delay( 4000 ).slideUp();
	};
})( jQuery );
