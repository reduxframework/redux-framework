/* global redux, redux_change, jQuery */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.initEvents = function( el ) {
		var stickyHeight;

		el.find( '.redux-presets-bar' ).on(
			'click',
			function() {
				window.onbeforeunload = null;
			}
		);

		// Customizer save hook.
		el.find( '#customize-save-button-wrapper #save' ).on(
			'click',
			function() {

			}
		);

		el.find( '#toplevel_page_' + redux.optName.args.slug + ' .wp-submenu a, #wp-admin-bar-' + redux.optName.args.slug + ' a.ab-item' ).on(
			'click',
			function( e ) {
				var url;

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
			function( e ) {
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
			function( e ) {
				var tab;

				var container = el;

				e.preventDefault();

				if ( $( container ).hasClass( 'fully-expanded' ) ) {
					$( container ).removeClass( 'fully-expanded' );

					tab = $.cookie( 'redux_current_tab_' + redux.optName.args.opt_name );

					el.find( '#' + tab + '_section_group' ).fadeIn(
						200,
						function() {
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
			function() {
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
				function() {
					$.redux.stickyInfo();
				}
			);

			$( window ).on(
				'resize',
				function() {
					$.redux.stickyInfo();
				}
			);
		}

		el.find( '.saved_notice' ).delay( 4000 ).slideUp();
	};
})( jQuery );
