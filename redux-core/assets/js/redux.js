/* global redux, tinyMCE, ajaxurl */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.ajax_save = function( button ) {
		var $data;
		var $nonce;

		var overlay           = $( document.getElementById( 'redux_ajax_overlay' ) );
		var $notification_bar = $( document.getElementById( 'redux_notification_bar' ) );
		var $parent           = $( button ).parents( '.redux-wrap-div' ).find( 'form' ).first();

		overlay.fadeIn();

		// Add the loading mechanism.
		$( '.redux-action_bar .spinner' ).addClass( 'is-active' );
		$( '.redux-action_bar input' ).prop( 'disabled', true );

		$notification_bar.slideUp();

		$( '.redux-save-warn' ).slideUp();
		$( '.redux_ajax_save_error' ).slideUp(
			'medium',
			function() {
				$( this ).remove();
			}
		);

		// Editor field doesn't auto save. Have to call it. Boo.
		if ( redux.optName.hasOwnProperty( 'editor' ) ) {
			$.each(
				redux.optName.editor,
				function( $key ) {
					var editor;

					if ( 'undefined' !== typeof ( tinyMCE ) ) {
						editor = tinyMCE.get( $key );

						if ( editor ) {
							editor.save();
						}
					}
				}
			);
		}

		$data = $parent.serialize();

		// Add values for checked and unchecked checkboxes fields.
		$parent.find( 'input[type=checkbox]' ).each(
			function() {
				var chkVal;

				if ( 'undefined' !== typeof $( this ).attr( 'name' ) ) {
					chkVal = $( this ).is( ':checked' ) ? $( this ).val() : '0';

					$data += '&' + $( this ).attr( 'name' ) + '=' + chkVal;
				}
			}
		);

		if ( 'redux_save' !== button.attr( 'name' ) ) {
			$data += '&' + button.attr( 'name' ) + '=' + button.val();
		}

		$nonce = $parent.attr( 'data-nonce' );

		$.ajax(
			{ type: 'post',
				dataType: 'json',
				url: ajaxurl,
				data: {
					action:     redux.optName.args.opt_name + '_ajax_save',
					nonce:      $nonce,
					'opt_name': redux.optName.args.opt_name,
					data:       $data
				},
				error: function( response ) {
					$( '.redux-action_bar input' ).prop( 'disabled', false );

					if ( true === redux.optName.args.dev_mode ) {
						console.log( response.responseText );

						overlay.fadeOut( 'fast' );
						$( '.redux-action_bar .spinner' ).removeClass( 'is-active' );
						alert( redux.optName.ajax.alert );
					} else {
						redux.optName.args.ajax_save = false;

						$( button ).trigger( 'click' );
						$( '.redux-action_bar input' ).prop( 'disabled', true );
					}
				},
				success: function( response ) {
					var $save_notice;

					if ( response.action && 'reload' === response.action ) {
						location.reload( true );
					} else if ( 'success' === response.status ) {
						$( '.redux-action_bar input' ).prop( 'disabled', false );
						overlay.fadeOut( 'fast' );
						$( '.redux-action_bar .spinner' ).removeClass( 'is-active' );
						redux.optName.options  = response.options;
						redux.optName.errors   = response.errors;
						redux.optName.warnings = response.warnings;
						redux.optName.sanitize = response.sanitize;

						$notification_bar.html( response.notification_bar ).slideDown( 'fast' );
						if ( null !== response.errors || null !== response.warnings ) {
							$.redux.notices();
						}

						if ( null !== response.sanitize ) {
							$.redux.sanitize();
						}

						$save_notice = $( document.getElementById( 'redux_notification_bar' ) ).find( '.saved_notice' );

						$save_notice.slideDown();
						$save_notice.delay( 4000 ).slideUp();
					} else {
						$( '.redux-action_bar input' ).prop( 'disabled', false );
						$( '.redux-action_bar .spinner' ).removeClass( 'is-active' );
						overlay.fadeOut( 'fast' );
						$( '.wrap h2:first' ).parent().append( '<div class="error redux_ajax_save_error" style="display:none;"><p>' + response.status + '</p></div>' );
						$( '.redux_ajax_save_error' ).slideDown();
						$( 'html, body' ).animate(
							{ scrollTop: 0 },
							'slow'
						);
					}
				}
			}
		);

		return false;
	};
})( jQuery );

/* jshint unused:false */

function colorValidate( field ) {
	'use strict';

	var value = jQuery( field ).val();

	var hex = colorNameToHex( value );
	if ( hex !== value.replace( '#', '' ) ) {
		return hex;
	}

	return value;
}

function colorNameToHex( colour ) {
	'use strict';

	var tcolour = colour.replace( /^\s\s*/, '' ).replace( /\s\s*$/, '' ).replace( '#', '' );

	var colours = {
		'aliceblue': '#f0f8ff',
		'antiquewhite': '#faebd7',
		'aqua': '#00ffff',
		'aquamarine': '#7fffd4',
		'azure': '#f0ffff',
		'beige': '#f5f5dc',
		'bisque': '#ffe4c4',
		'black': '#000000',
		'blanchedalmond': '#ffebcd',
		'blue': '#0000ff',
		'blueviolet': '#8a2be2',
		'brown': '#a52a2a',
		'burlywood': '#deb887',
		'cadetblue': '#5f9ea0',
		'chartreuse': '#7fff00',
		'chocolate': '#d2691e',
		'coral': '#ff7f50',
		'cornflowerblue': '#6495ed',
		'cornsilk': '#fff8dc',
		'crimson': '#dc143c',
		'cyan': '#00ffff',
		'darkblue': '#00008b',
		'darkcyan': '#008b8b',
		'darkgoldenrod': '#b8860b',
		'darkgray': '#a9a9a9',
		'darkgreen': '#006400',
		'darkkhaki': '#bdb76b',
		'darkmagenta': '#8b008b',
		'darkolivegreen': '#556b2f',
		'darkorange': '#ff8c00',
		'darkorchid': '#9932cc',
		'darkred': '#8b0000',
		'darksalmon': '#e9967a',
		'darkseagreen': '#8fbc8f',
		'darkslateblue': '#483d8b',
		'darkslategray': '#2f4f4f',
		'darkturquoise': '#00ced1',
		'darkviolet': '#9400d3',
		'deeppink': '#ff1493',
		'deepskyblue': '#00bfff',
		'dimgray': '#696969',
		'dodgerblue': '#1e90ff',
		'firebrick': '#b22222',
		'floralwhite': '#fffaf0',
		'forestgreen': '#228b22',
		'fuchsia': '#ff00ff',
		'gainsboro': '#dcdcdc',
		'ghostwhite': '#f8f8ff',
		'gold': '#ffd700',
		'goldenrod': '#daa520',
		'gray': '#808080',
		'green': '#008000',
		'greenyellow': '#adff2f',
		'honeydew': '#f0fff0',
		'hotpink': '#ff69b4',
		'indianred ': '#cd5c5c',
		'indigo ': '#4b0082',
		'ivory': '#fffff0',
		'khaki': '#f0e68c',
		'lavender': '#e6e6fa',
		'lavenderblush': '#fff0f5',
		'lawngreen': '#7cfc00',
		'lemonchiffon': '#fffacd',
		'lightblue': '#add8e6',
		'lightcoral': '#f08080',
		'lightcyan': '#e0ffff',
		'lightgoldenrodyellow': '#fafad2',
		'lightgrey': '#d3d3d3',
		'lightgreen': '#90ee90',
		'lightpink': '#ffb6c1',
		'lightsalmon': '#ffa07a',
		'lightseagreen': '#20b2aa',
		'lightskyblue': '#87cefa',
		'lightslategray': '#778899',
		'lightsteelblue': '#b0c4de',
		'lightyellow': '#ffffe0',
		'lime': '#00ff00',
		'limegreen': '#32cd32',
		'linen': '#faf0e6',
		'magenta': '#ff00ff',
		'maroon': '#800000',
		'mediumaquamarine': '#66cdaa',
		'mediumblue': '#0000cd',
		'mediumorchid': '#ba55d3',
		'mediumpurple': '#9370d8',
		'mediumseagreen': '#3cb371',
		'mediumslateblue': '#7b68ee',
		'mediumspringgreen': '#00fa9a',
		'mediumturquoise': '#48d1cc',
		'mediumvioletred': '#c71585',
		'midnightblue': '#191970',
		'mintcream': '#f5fffa',
		'mistyrose': '#ffe4e1',
		'moccasin': '#ffe4b5',
		'navajowhite': '#ffdead',
		'navy': '#000080',
		'oldlace': '#fdf5e6',
		'olive': '#808000',
		'olivedrab': '#6b8e23',
		'orange': '#ffa500',
		'orangered': '#ff4500',
		'orchid': '#da70d6',
		'palegoldenrod': '#eee8aa',
		'palegreen': '#98fb98',
		'paleturquoise': '#afeeee',
		'palevioletred': '#d87093',
		'papayawhip': '#ffefd5',
		'peachpuff': '#ffdab9',
		'peru': '#cd853f',
		'pink': '#ffc0cb',
		'plum': '#dda0dd',
		'powderblue': '#b0e0e6',
		'purple': '#800080',
		'red': '#ff0000',
		'redux': '#01a3e3',
		'rosybrown': '#bc8f8f',
		'royalblue': '#4169e1',
		'saddlebrown': '#8b4513',
		'salmon': '#fa8072',
		'sandybrown': '#f4a460',
		'seagreen': '#2e8b57',
		'seashell': '#fff5ee',
		'sienna': '#a0522d',
		'silver': '#c0c0c0',
		'skyblue': '#87ceeb',
		'slateblue': '#6a5acd',
		'slategray': '#708090',
		'snow': '#fffafa',
		'springgreen': '#00ff7f',
		'steelblue': '#4682b4',
		'tan': '#d2b48c',
		'teal': '#008080',
		'thistle': '#d8bfd8',
		'tomato': '#ff6347',
		'turquoise': '#40e0d0',
		'violet': '#ee82ee',
		'wheat': '#f5deb3',
		'white': '#ffffff',
		'whitesmoke': '#f5f5f5',
		'yellow': '#ffff00',
		'yellowgreen': '#9acd32'
	};

	if ( 'undefined' !== colours[tcolour.toLowerCase()] ) {
		return colours[tcolour.toLowerCase()];
	}

	return colour;
}

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.expandOptions = function( parent ) {
		var trigger = parent.find( '.expand_options' );
		var width   = parent.find( '.redux-sidebar' ).width() - 1;
		var id      = $( '.redux-group-menu .active a' ).data( 'rel' ) + '_section_group';

		if ( trigger.hasClass( 'expanded' ) ) {
			trigger.removeClass( 'expanded' );
			parent.find( '.redux-main' ).removeClass( 'expand' );

			parent.find( '.redux-sidebar' ).stop().animate(
				{ 'margin-left': '0px' },
				500
			);

			parent.find( '.redux-main' ).stop().animate(
				{ 'margin-left': width },
				500,
				function() {
					parent.find( '.redux-main' ).attr( 'style', '' );
				}
			);

			parent.find( '.redux-group-tab' ).each(
				function() {
					if ( $( this ).attr( 'id' ) !== id ) {
						$( this ).fadeOut( 'fast' );
					}
				}
			);

			// Show the only active one.
		} else {
			trigger.addClass( 'expanded' );
			parent.find( '.redux-main' ).addClass( 'expand' );

			parent.find( '.redux-sidebar' ).stop().animate(
				{ 'margin-left': - width - 113 },
				500
			);

			parent.find( '.redux-main' ).stop().animate(
				{ 'margin-left': '-1px' },
				500
			);

			parent.find( '.redux-group-tab' ).fadeIn(
				'medium',
				function() {
					$.redux.initFields();
				}
			);
		}

		return false;
	};
})( jQuery );

/* global redux, redux_change, jQuery, pagenow, ajaxurl */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.initEvents = function( el ) {
		var stickyHeight;
		var search  = window.location.search;
		var curPage = pagenow;
		var dialog;
		var messageDialog;

		if ( 'string' === typeof search && 'string' === typeof curPage && true === redux.optName.args.dev_mode ) {
			search  = search.replace( '?page=', '' );
			curPage = curPage.replace( 'toplevel_page_', '' );

			if ( search === curPage ) {
				document.addEventListener(
					'keydown',
					function( event ) {
						if ( event.ctrlKey && event.shiftKey && 'H' === event.key ) {
							dialog.dialog( 'open' );
						}
					}
				);

				messageDialog = $( '#redux-dialog-message' ).dialog(
					{
						classes: {
							'ui-dialog': 'redux-message-dialog',
							'ui-dialog-buttonpane': 'redux-message-dialog-buttonpane',
							'ui-dialog-title': 'redux-message-dialog-title',
							'ui-dialog-content': 'redux-message-dialog-content'
						},
						modal: true,
						autoOpen: false,
						resizable: false,
						height: 'auto',
						width: 400,
						buttons: {
							Ok: function() {
								$( this ).dialog( 'close' );
							}
						}
					}
				);

				dialog = $( '#redux-dialog-confirm' ).dialog(
					{
						modal: true,
						classes: {
							'ui-dialog': 'redux-support-dialog'
						},
						autoOpen: false,
						resizable: false,
						height: 'auto',
						width: 400,
						buttons: {
							Submit: function() {
								var buttonPane  = $( '.redux-message-dialog-buttonpane' );
								var dialogTitle = $( '.redux-message-dialog-title' );
								var content     = $( '.redux-message-dialog-content .redux-message-p' );

								$.ajax(
									{ type: 'post',
										dataType: 'json',
										url: ajaxurl,
										data: {
											action:     'redux_submit_support_data',
											nonce:      $( '#redux-dialog-confirm' ).data( 'nonce' )
										},
										beforeSend: function() {
											buttonPane.css( { 'display': 'none' } );
											$( '#redux-dialog-message .spinner' ).css( { 'visibility': 'visible' } );

											messageDialog.dialog( 'open' );
										},
										error: function( response ) {
											buttonPane.css( { 'display': 'block' } );
											dialogTitle.text( 'Error' );

											console.log( response );
										},
										success: function( response ) {
											buttonPane.css( { 'display': 'block' } );

											if ( response.status && 'success' === response.status ) {
												dialogTitle.text( 'Information Sent' );
												content.html( 'Your support data has been transmitted.  The reference number for this transmission is: <strong>' + response.data + '</strong>' );
											} else {
												dialogTitle.text( 'Error' );
												content.text( response.data );
											}
										}
									}
								);

								$( this ).dialog( 'close' );
							},
							Cancel: function() {
								$( this ).dialog( 'close' );
							}
						}
					}
				);
			}
		}

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

/* global redux */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.initFields = function() {
		$( '.redux-group-tab:visible' ).find( '.redux-field-init:visible' ).each(
			function() {
				var tr;
				var th;

				var type = $( this ).attr( 'data-type' );

				if ( 'undefined' !== typeof redux.field_objects && redux.field_objects[type] && redux.field_objects[type] ) {
					redux.field_objects[type].init();
				}

				if ( 'undefined' !== typeof redux.field_objects.pro && ! $.isEmptyObject( redux.field_objects.pro[type] ) && redux.field_objects.pro[type] ) {
					redux.field_objects.pro[type].init();
				}

				if ( ! redux.customizer && $( this ).hasClass( 'redux_remove_th' ) ) {
					tr = $( this ).parents( 'tr:first' );
					th = tr.find( 'th:first' );

					if ( th.html() && th.html().length > 0 ) {
						$( this ).prepend( th.html() );
						$( this ).find( '.redux_field_th' ).css( 'padding', '0 0 10px 0' );
					}

					$( this ).parent().attr( 'colspan', '2' );

					th.remove();
				}
			}
		);
	};
})( jQuery );

/* global redux, document */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$( document ).ready(
		function() {
			var opt_name;
			var tempArr = [];

			$.fn.isOnScreen = function() {
				var win;
				var viewport;
				var bounds;

				if ( ! window ) {
					return;
				}

				win      = $( window );
				viewport = {
					top: win.scrollTop()
				};

				viewport.right  = viewport.left + win.width();
				viewport.bottom = viewport.top + win.height();

				bounds = this.offset();

				bounds.right  = bounds.left + this.outerWidth();
				bounds.bottom = bounds.top + this.outerHeight();

				return ( ! ( viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom ) );
			};

			$( 'fieldset.redux-container-divide' ).css( 'display', 'none' );

			// Weed out multiple instances of duplicate Redux instance.
			if ( redux.customizer ) {
				$( '.wp-full-overlay-sidebar' ).addClass( 'redux-container' );
			}

			$( '.redux-container' ).each(
				function() {
					opt_name = $.redux.getOptName( this );

					if ( $.inArray( opt_name, tempArr ) === -1 ) {
						tempArr.push( opt_name );
						$.redux.checkRequired( $( this ) );
						$.redux.initEvents( $( this ) );
					}
				}
			);

			$( '.redux-container' ).on(
				'click',
				function() {
					opt_name = $.redux.getOptName( this );
				}
			);

			if ( undefined !== redux.optName ) {
				$.redux.disableFields();
				$.redux.hideFields();
				$.redux.disableSections();
				$.redux.initQtip();
				$.redux.tabCheck();
				$.redux.notices();

				if ( 'undefined' === typeof $.redux.flyoutSubmenus ) {
					$.redux.flyoutSubmenu();
				}
			}
		}
	);

	$.redux.flyoutSubmenu = function() {

		// Close flyouts when a new menu item is activated.
		$( '.redux-group-tab-link-li a' ).on(
			'click',
			function() {
				if ( true === redux.optName.args.flyout_submenus ) {
					$( '.redux-group-tab-link-li' ).removeClass( 'redux-section-hover' );
				}
			}
		);

		if ( true === redux.optName.args.flyout_submenus ) {

			// Submenus flyout when a main menu item is hovered.
			$( '.redux-group-tab-link-li.hasSubSections' ).each(
				function() {
					$( this ).on(
						'mouseenter',
						function() {
							if ( ! $( this ).hasClass( 'active' ) && ! $( this ).hasClass( 'activeChild' ) ) {
								$( this ).addClass( 'redux-section-hover' );
							}
						}
					);

					$( this ).on(
						'mouseleave',
						function() {
							$( this ).removeClass( 'redux-section-hover' );
						}
					);
				}
			);
		}
	};

	$.redux.disableSections = function() {
		$( '.redux-group-tab' ).each(
			function() {
				if ( $( this ).hasClass( 'disabled' ) ) {
					$( this ).find( 'input, select, textarea' ).attr( 'name', '' );
				}
			}
		);
	};

	$.redux.disableFields = function() {
		$( 'label[for="redux_disable_field"]' ).each(
			function() {
				$( this ).parents( 'tr' ).find( 'fieldset:first' ).find( 'input, select, textarea' ).attr( 'name', '' );
			}
		);
	};

	$.redux.hideFields = function() {
		$( 'label[for="redux_hide_field"]' ).each(
			function() {
				var tr = $( this ).parent().parent();

				$( tr ).addClass( 'hidden' );
			}
		);
	};

	$.redux.getOptName = function( el ) {
		var metabox;
		var li;
		var optName;
		var item = $( el );

		if ( redux.customizer ) {
			optName = item.find( '.redux-customizer-opt-name' ).data( 'opt-name' );
		} else {
			optName = $( el ).parents( '.redux-wrap-div' ).data( 'opt-name' );
		}

		// Compatibility for metaboxes
		if ( undefined === optName ) {
			metabox = $( el ).parents( '.postbox' );
			if ( 0 === metabox.length ) {
				metabox = $( el ).parents( '.redux-metabox' );
			}
			if ( 0 !== metabox.length ) {
				optName = metabox.attr( 'id' ).replace( 'redux-', '' ).split( '-metabox-' )[0];
				if ( undefined === optName ) {
					optName = metabox.attr( 'class' )
					.replace( 'redux-metabox', '' )
					.replace( 'postbox', '' )
					.replace( 'redux-', '' )
					.replace( 'hide', '' )
					.replace( 'closed', '' )
					.trim();
				}
			} else {
				optName = $( '.redux-ajax-security' ).data( 'opt-name' );
			}
		}
		if ( undefined === optName ) {
			optName = $( el ).find( '.redux-form-wrapper' ).data( 'opt-name' );
		}

		// Shim, let's just get an opt_name shall we?!
		if ( undefined === optName ) {
			optName = redux.opt_names[0];
		}

		if ( undefined !== optName ) {
			redux.optName = window['redux_' + optName.replace( /\-/g, '_' )];
		}

		return optName;
	};

	$.redux.getSelector = function( selector, fieldType ) {
		if ( ! selector ) {
			selector = '.redux-container-' + fieldType + ':visible';
			if ( redux.customizer ) {
				selector = $( document ).find( '.control-section-redux.open' ).find( selector );
			} else {
				selector = $( document ).find( '.redux-group-tab:visible' ).find( selector );
			}
		}
		return selector;
	};
})( jQuery );

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

/* global redux */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.initQtip = function() {
		var classes;

		// Shadow.
		var shadow    = '';
		var tipShadow = redux.optName.args.hints.tip_style.shadow;

		// Color.
		var color    = '';
		var tipColor = redux.optName.args.hints.tip_style.color;

		// Rounded.
		var rounded    = '';
		var tipRounded = redux.optName.args.hints.tip_style.rounded;

		// Tip style.
		var style    = '';
		var tipStyle = redux.optName.args.hints.tip_style.style;

		// Get position data.
		var myPos = redux.optName.args.hints.tip_position.my;
		var atPos = redux.optName.args.hints.tip_position.at;

		// Tooltip trigger action.
		var showEvent = redux.optName.args.hints.tip_effect.show.event;
		var hideEvent = redux.optName.args.hints.tip_effect.hide.event;

		// Tip show effect.
		var tipShowEffect   = redux.optName.args.hints.tip_effect.show.effect;
		var tipShowDuration = redux.optName.args.hints.tip_effect.show.duration;

		// Tip hide effect.
		var tipHideEffect   = redux.optName.args.hints.tip_effect.hide.effect;
		var tipHideDuration = redux.optName.args.hints.tip_effect.hide.duration;

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
				function() {
					$( this ).qtip(
						{
							content: {
								text: $( this ).attr( 'qtip-content' ),
								title: $( this ).attr( 'qtip-title' )
							}, show: {
								effect: function() {
									$( this ).slideDown( 500 );
								},
								event: 'mouseover'
							}, hide: {
								effect: function() {
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
				function() {
					$( this ).qtip(
						{
							content: {
								text: $( this ).attr( 'qtip-content' ),
								title: $( this ).attr( 'qtip-title' )
							}, show: {
								effect: function() {
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
								effect: function() {
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
				function() {
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

	$.redux.verifyPos = function( s, b ) {
		var split;
		var paramOne;
		var paramTwo;

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
		subParent = $( '#' + parentID + '_li' ).parents( '.hasSubSections:first' );

		if ( $( variable ).parents( 'fieldset.redux-field:first' ).hasClass( 'redux-field-error' ) ) {
			$( variable ).parents( 'fieldset.redux-field:first' ).removeClass( 'redux-field-error' );
			$( variable ).parent().find( '.redux-th-error' ).slideUp();

			errorCount = ( parseInt( rContainer.find( '.redux-field-errors span' ).text(), 0 ) - 1 );

			if ( errorCount <= 0 ) {
				$( '#' + parentID + '_li .redux-menu-error' ).fadeOut( 'fast' ).remove();
				$( '#' + parentID + '_li .redux-group-tab-link-a' ).removeClass( 'hasError' );
				$( '#' + parentID + '_li' ).parents( '.inside:first' ).find( '.redux-field-errors' ).slideUp();
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
				$( '#' + parentID + '_li' ).parents( '.inside:first' ).find( '.redux-field-warnings' ).slideUp();
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

/* jshint unused:false */

function redux_hook( object, functionName, callback, before ) {
	'use strict';

	(function( originalFunction ) {
		object[functionName] = function() {
			var returnValue;

			if ( true === before ) {
				callback.apply( this, [returnValue, originalFunction, arguments] );
			}

			returnValue = originalFunction.apply( this, arguments );

			if ( true !== before ) {
				callback.apply( this, [returnValue, originalFunction, arguments] );
			}

			return returnValue;
		};
	}( object[functionName] ) );
}

/* global redux */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.makeBoolStr = function( val ) {
		if ( 'false' === val || false === val || '0' === val || 0 === val || null === val || '' === val ) {
			return 'false';
		} else if ( 'true' === val || true === val || '1' === val || 1 === val ) {
			return 'true';
		} else {
			return val;
		}
	};

	$.redux.checkRequired = function( el ) {
		$.redux.required();

		$( 'body' ).on(
			'change',
			'.redux-main select, .redux-main radio, .redux-main input[type=checkbox], .redux-main input[type=hidden]',
			function() {
				$.redux.check_dependencies( this );
			}
		);

		$( 'body' ).on(
			'check_dependencies',
			function( e, variable ) {
				e = null;
				$.redux.check_dependencies( variable );
			}
		);

		if ( redux.customizer ) {
			el.find( '.customize-control.redux-field.hide' ).hide();
		}

		el.find( '.redux-container td > fieldset:empty,td > div:empty' ).parent().parent().hide();
	};

	$.redux.required = function() {

		// Hide the fold elements on load.
		// It's better to do this by PHP but there is no filter in tr tag , so is not possible
		// we going to move each attributes we may need for folding to tr tag.
		$.each(
			redux.opt_names,
			function( x ) {
				$.each(
					window['redux_' + redux.opt_names[x].replace( /\-/g, '_' )].folds,
					function( i, v ) {
						var div;
						var rawTable;

						var fieldset = $( '#' + redux.opt_names[x] + '-' + i );

						fieldset.parents( 'tr:first, li:first' ).addClass( 'fold' );

						if ( 'hide' === v ) {
							fieldset.parents( 'tr:first, li:first' ).addClass( 'hide' );

							if ( fieldset.hasClass( 'redux-container-section' ) ) {
								div = $( '#section-' + i );

								if ( div.hasClass( 'redux-section-indent-start' ) ) {
									$( '#section-table-' + i ).hide().addClass( 'hide' );
									div.hide().addClass( 'hide' );
								}
							}

							if ( fieldset.hasClass( 'redux-container-info' ) ) {
								$( '#info-' + i ).hide().addClass( 'hide' );
							}

							if ( fieldset.hasClass( 'redux-container-divide' ) ) {
								$( '#divide-' + i ).hide().addClass( 'hide' );
							}

							if ( fieldset.hasClass( 'redux-container-raw' ) ) {
								rawTable = fieldset.parents().find( 'table#' + redux.opt_names[x] + '-' + i );
								rawTable.hide().addClass( 'hide' );
							}
						}
					}
				);
			}
		);
	};

	$.redux.getContainerValue = function( id ) {
		var value = $( '#' + redux.optName.args.opt_name + '-' + id ).serializeForm();

		if ( null !== value && 'object' === typeof value && value.hasOwnProperty( redux.optName.args.opt_name ) ) {
			value = value[redux.optName.args.opt_name][id];
		}

		if ( $( '#' + redux.optName.args.opt_name + '-' + id ).hasClass( 'redux-container-media' ) ) {
			value = value.url;
		}

		return value;
	};

	$.redux.check_dependencies = function( variable ) {
		var current;
		var id;
		var container;
		var isHidden;

		if ( null === redux.optName.required ) {
			return;
		}

		current = $( variable );
		id      = current.parents( '.redux-field:first' ).data( 'id' );

		if ( ! redux.optName.required.hasOwnProperty( id ) ) {
			return;
		}

		container = current.parents( '.redux-field-container:first' );
		isHidden  = container.parents( 'tr:first' ).hasClass( 'hide' );

		if ( ! container.parents( 'tr:first' ).length ) {
			isHidden = container.parents( '.customize-control:first' ).hasClass( 'hide' );
		}

		$.each(
			redux.optName.required[id],
			function( child ) {
				var div;
				var rawTable;
				var tr;

				var current       = $( this );
				var show          = false;
				var childFieldset = $( '#' + redux.optName.args.opt_name + '-' + child );

				tr = childFieldset.parents( 'tr:first' );

				if ( 0 === tr.length ) {
					tr = childFieldset.parents( 'li:first' );
				}

				if ( ! isHidden ) {
					show = $.redux.check_parents_dependencies( child );
				}

				if ( true === show ) {

					// Shim for sections.
					if ( childFieldset.hasClass( 'redux-container-section' ) ) {
						div = $( '#section-' + child );

						if ( div.hasClass( 'redux-section-indent-start' ) && div.hasClass( 'hide' ) ) {
							$( '#section-table-' + child ).fadeIn( 300 ).removeClass( 'hide' );
							div.fadeIn( 300 ).removeClass( 'hide' );
						}
					}

					if ( childFieldset.hasClass( 'redux-container-info' ) ) {
						$( '#info-' + child ).fadeIn( 300 ).removeClass( 'hide' );
					}

					if ( childFieldset.hasClass( 'redux-container-divide' ) ) {
						$( '#divide-' + child ).fadeIn( 300 ).removeClass( 'hide' );
					}

					if ( childFieldset.hasClass( 'redux-container-raw' ) ) {
						rawTable = childFieldset.parents().find( 'table#' + redux.optName.args.opt_name + '-' + child );
						rawTable.fadeIn( 300 ).removeClass( 'hide' );
					}

					tr.fadeIn(
						300,
						function() {
							$( this ).removeClass( 'hide' );
							if ( redux.optName.required.hasOwnProperty( child ) ) {
								$.redux.check_dependencies( $( '#' + redux.optName.args.opt_name + '-' + child ).children().first() );
							}

							$.redux.initFields();
						}
					);

					if ( childFieldset.hasClass( 'redux-container-section' ) || childFieldset.hasClass( 'redux-container-info' ) ) {
						tr.css( { display: 'none' } );
					}
				} else if ( false === show ) {
					tr.fadeOut(
						100,
						function() {
							$( this ).addClass( 'hide' );
							if ( redux.optName.required.hasOwnProperty( child ) ) {
								$.redux.required_recursive_hide( child );
							}
						}
					);
				}

				current.find( 'select, radio, input[type=checkbox]' ).trigger( 'change' );
			}
		);
	};

	$.redux.required_recursive_hide = function( id ) {
		var div;
		var rawTable;
		var toFade;

		toFade = $( '#' + redux.optName.args.opt_name + '-' + id ).parents( 'tr:first' );
		if ( 0 === toFade ) {
			toFade = $( '#' + redux.optName.args.opt_name + '-' + id ).parents( 'li:first' );
		}

		toFade.fadeOut(
			50,
			function() {
				$( this ).addClass( 'hide' );

				if ( $( '#' + redux.optName.args.opt_name + '-' + id ).hasClass( 'redux-container-section' ) ) {
					div = $( '#section-' + id );

					if ( div.hasClass( 'redux-section-indent-start' ) ) {
						$( '#section-table-' + id ).fadeOut( 50 ).addClass( 'hide' );
						div.fadeOut( 50 ).addClass( 'hide' );
					}
				}

				if ( $( '#' + redux.optName.args.opt_name + '-' + id ).hasClass( 'redux-container-info' ) ) {
					$( '#info-' + id ).fadeOut( 50 ).addClass( 'hide' );
				}

				if ( $( '#' + redux.optName.args.opt_name + '-' + id ).hasClass( 'redux-container-divide' ) ) {
					$( '#divide-' + id ).fadeOut( 50 ).addClass( 'hide' );
				}

				if ( $( '#' + redux.optName.args.opt_name + '-' + id ).hasClass( 'redux-container-raw' ) ) {
					rawTable = $( '#' + redux.optName.args.opt_name + '-' + id ).parents().find( 'table#' + redux.optName.args.opt_name + '-' + id );
					rawTable.fadeOut( 50 ).addClass( 'hide' );
				}

				if ( redux.optName.required.hasOwnProperty( id ) ) {
					$.each(
						redux.optName.required[id],
						function( child ) {
							$.redux.required_recursive_hide( child );
						}
					);
				}
			}
		);
	};

	$.redux.check_parents_dependencies = function( id ) {
		var show = '';

		if ( redux.optName.required_child.hasOwnProperty( id ) ) {
			$.each(
				redux.optName.required_child[id],
				function( i, parentData ) {
					var parentValue;

					i = null;

					if ( $( '#' + redux.optName.args.opt_name + '-' + parentData.parent ).parents( 'tr:first' ).hasClass( 'hide' ) ) {
						show = false;
					} else if ( $( '#' + redux.optName.args.opt_name + '-' + parentData.parent ).parents( 'li:first' ).hasClass( 'hide' ) ) {
						show = false;
					} else {
						if ( false !== show ) {
							parentValue = $.redux.getContainerValue( parentData.parent );

							show = $.redux.check_dependencies_visibility( parentValue, parentData );
						}
					}
				}
			);
		} else {
			show = true;
		}

		return show;
	};

	$.redux.check_dependencies_visibility = function( parentValue, data ) {
		var show       = false;
		var checkValue = data.checkValue;
		var operation  = data.operation;
		var arr;

		if ( $.isPlainObject( parentValue ) ) {
			parentValue = Object.keys( parentValue ).map(
				function( key ) {
					return [key, parentValue[key]];
				}
			);
		}

		switch ( operation ) {
			case '=':
			case 'equals':
				if ( Array.isArray( parentValue ) ) {
					$( parentValue[0] ).each(
						function( idx, val ) {
							idx = null;

							if ( Array.isArray( checkValue ) ) {
								$( checkValue ).each(
									function( i, v ) {
										i = null;
										if ( $.redux.makeBoolStr( val ) === $.redux.makeBoolStr( v ) ) {
											show = true;

											return true;
										}
									}
								);
							} else {
								if ( $.redux.makeBoolStr( val ) === $.redux.makeBoolStr( checkValue ) ) {
									show = true;

									return true;
								}
							}
						}
					);
				} else {
					if ( Array.isArray( checkValue ) ) {
						$( checkValue ).each(
							function( i, v ) {
								i = null;

								if ( $.redux.makeBoolStr( parentValue ) === $.redux.makeBoolStr( v ) ) {
									show = true;
								}
							}
						);
					} else {
						if ( $.redux.makeBoolStr( parentValue ) === $.redux.makeBoolStr( checkValue ) ) {
							show = true;
						}
					}
				}
				break;

			case '!=':
			case 'not':
				if ( Array.isArray( parentValue ) ) {
					$( parentValue[0] ).each(
						function( idx, val ) {
							idx = null;

							if ( Array.isArray( checkValue ) ) {
								$( checkValue ).each(
									function( i, v ) {
										i = null;

										if ( $.redux.makeBoolStr( val ) !== $.redux.makeBoolStr( v ) ) {
											show = true;

											return true;
										}
									}
								);
							} else {
								if ( $.redux.makeBoolStr( val ) !== $.redux.makeBoolStr( checkValue ) ) {
									show = true;

									return true;
								}
							}
						}
					);
				} else {
					if ( Array.isArray( checkValue ) ) {
						$( checkValue ).each(
							function( i, v ) {
								i = null;

								if ( $.redux.makeBoolStr( parentValue ) !== $.redux.makeBoolStr( v ) ) {
									show = true;
								}
							}
						);
					} else {
						if ( $.redux.makeBoolStr( parentValue ) !== $.redux.makeBoolStr( checkValue ) ) {
							show = true;
						}
					}
				}
				break;

			case '>':
			case 'greater':
			case 'is_larger':
				if ( parseFloat( parentValue ) > parseFloat( checkValue ) ) {
					show = true;
				}
				break;

			case '>=':
			case 'greater_equal':
			case 'is_larger_equal':
				if ( parseFloat( parentValue ) >= parseFloat( checkValue ) ) {
					show = true;
				}
				break;

			case '<':
			case 'less':
			case 'is_smaller':
				if ( parseFloat( parentValue ) < parseFloat( checkValue ) ) {
					show = true;
				}
				break;

			case '<=':
			case 'less_equal':
			case 'is_smaller_equal':
				if ( parseFloat( parentValue ) <= parseFloat( checkValue ) ) {
					show = true;
				}
				break;

			case 'contains':
				if ( $.isPlainObject( parentValue ) ) {
					parentValue = Object.keys( parentValue ).map(
						function( key ) {
							return [key, parentValue[key]];
						}
					);
				}

				if ( $.isPlainObject( checkValue ) ) {
					checkValue = Object.keys( checkValue ).map(
						function( key ) {
							return [key, checkValue[key]];
						}
					);
				}

				if ( Array.isArray( checkValue ) ) {
					$( checkValue ).each(
						function( idx, val ) {
							var breakMe = false;
							var toFind  = val[0];
							var findVal = val[1];

							idx = null;

							$( parentValue ).each(
								function( i, v ) {
									var toMatch  = v[0];
									var matchVal = v[1];

									i = null;

									if ( toFind === toMatch ) {
										if ( findVal === matchVal ) {
											show    = true;
											breakMe = true;

											return false;
										}
									}
								}
							);

							if ( true === breakMe ) {
								return false;
							}
						}
					);
				} else {
					if ( parentValue.toString().indexOf( checkValue ) !== - 1 ) {
						show = true;
					}
				}
				break;

			case 'doesnt_contain':
			case 'not_contain':
				if ( $.isPlainObject( parentValue ) ) {
					arr = Object.keys( parentValue ).map(
						function( key ) {
							return parentValue[key];
						}
					);

					parentValue = arr;
				}

				if ( $.isPlainObject( checkValue ) ) {
					arr = Object.keys( checkValue ).map(
						function( key ) {
							return checkValue[key];
						}
					);

					checkValue = arr;
				}

				if ( Array.isArray( checkValue ) ) {
					$( checkValue ).each(
						function( idx, val ) {
							idx = null;

							if ( parentValue.toString().indexOf( val ) === - 1 ) {
								show = true;
							}
						}
					);
				} else {
					if ( parentValue.toString().indexOf( checkValue ) === - 1 ) {
						show = true;
					}
				}
				break;

			case 'is_empty_or':
				if ( '' === parentValue || checkValue === parentValue ) {
					show = true;
				}
				break;

			case 'not_empty_and':
				if ( '' !== parentValue && checkValue !== parentValue ) {
					show = true;
				}
				break;

			case 'is_empty':
			case 'empty':
			case '!isset':
				if ( ! parentValue || '' === parentValue || null === parentValue ) {
					show = true;
				}
				break;

			case 'not_empty':
			case '!empty':
			case 'isset':
				if ( parentValue && '' !== parentValue && null !== parentValue ) {
					show = true;
				}
				break;
		}

		return show;
	};
})( jQuery );

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.stickyInfo = function() {
		var stickyWidth = $( '.redux-main' ).innerWidth() - 20;
		var $width      = $( '#redux-sticky' ).offset().left;

		$( '.redux-save-warn' ).css( 'left', $width + 'px' );

		if ( ! $( '#info_bar' ).isOnScreen() && ! $( '#redux-footer-sticky' ).isOnScreen() ) {
			$( '#redux-footer' ).css(
				{ position: 'fixed', bottom: '0', width: stickyWidth, right: 21 }
			);

			$( '#redux-footer' ).addClass( 'sticky-footer-fixed' );
			$( '#redux-sticky-padder' ).show();
		} else {
			$( '#redux-footer' ).css(
				{ background: '#eee', position: 'inherit', bottom: 'inherit', width: 'inherit' }
			);

			$( '#redux-sticky-padder' ).hide();
			$( '#redux-footer' ).removeClass( 'sticky-footer-fixed' );
		}
		if ( ! $( '#info_bar' ).isOnScreen() ) {
			$( '#redux-sticky' ).addClass( 'sticky-save-warn' );
		} else {
			$( '#redux-sticky' ).removeClass( 'sticky-save-warn' );
		}
	};
})( jQuery );

/* global redux */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.tabCheck = function() {
		var link;
		var tab;
		var sTab;
		var cookieName;
		var opt_name;

		$( '.redux-group-tab-link-a' ).on(
			'click',
			function() {
				var elements;
				var index;
				var el;
				var relid;
				var oldid;
				var cookieName;
				var boxIndex;
				var parentID;
				var newParent;

				link = $( this );

				if ( link.parent().hasClass( 'empty_section' ) && link.parent().hasClass( 'hasSubSections' ) ) {
					elements = $( this ).closest( 'ul' ).find( '.redux-group-tab-link-a' );
					index    = elements.index( this );

					link = elements.slice( index + 1, index + 2 );
				}

				el    = link.parents( '.redux-container:first' );
				relid = link.data( 'rel' ); // The group ID of interest.
				oldid = el.find( '.redux-group-tab-link-li.active:first .redux-group-tab-link-a' ).data( 'rel' );
				opt_name = $.redux.getOptName( el );

				if ( oldid === relid ) {
					return;
				}

				cookieName = '';

				if ( ! link.parents( '.postbox-container:first' ).length ) {
					$( '#currentSection' ).val( relid );

					cookieName = 'redux_current_tab_' + redux.optName.args.opt_name;
				} else {
					el.prev( '#currentSection' ).val( relid );

					boxIndex = el.data( 'index' );

					if ( '' !== boxIndex ) {
						cookieName = 'redux_metabox_' + boxIndex + '_current_tab_' + redux.optName.args.opt_name;
					}
				}

				// Set the proper page cookie.
				$.cookie(
					cookieName,
					relid,
					{
						expires: 7,
						path: '/'
					}
				);

				if ( el.find( '#' + relid + '_section_group_li' ).parents( '.redux-group-tab-link-li' ).length ) {
					parentID = el.find( '#' + relid + '_section_group_li' ).parents( '.redux-group-tab-link-li' ).attr( 'id' ).split( '_' );
					parentID = parentID[0];
				}

				el.find( '#toplevel_page_' + redux.optName.args.slug + ' .wp-submenu a.current' ).removeClass( 'current' );
				el.find( '#toplevel_page_' + redux.optName.args.slug + ' .wp-submenu li.current' ).removeClass( 'current' );

				el.find( '#toplevel_page_' + redux.optName.args.slug + ' .wp-submenu a' ).each(
					function() {
						var url = $( this ).attr( 'href' ).split( '&tab=' );

						if ( url[1] === relid || url[1] === parentID ) {
							$( this ).addClass( 'current' );
							$( this ).parent().addClass( 'current' );
						}
					}
				);

				if ( el.find( '#' + oldid + '_section_group_li' ).find( '#' + oldid + '_section_group_li' ).length ) {
					el.find( '#' + oldid + '_section_group_li' ).addClass( 'activeChild' );
					el.find( '#' + relid + '_section_group_li' ).addClass( 'active' ).removeClass( 'activeChild' );
				} else if ( el.find( '#' + relid + '_section_group_li' ).parents( '#' + oldid + '_section_group_li' ).length || el.find( '#' + oldid + '_section_group_li' ).parents( 'ul.subsection' ).find( '#' + relid + '_section_group_li' ).length ) {
					if ( el.find( '#' + relid + '_section_group_li' ).parents( '#' + oldid + '_section_group_li' ).length ) {
						el.find( '#' + oldid + '_section_group_li' ).addClass( 'activeChild' ).removeClass( 'active' );
					} else {
						el.find( '#' + relid + '_section_group_li' ).addClass( 'active' );
						el.find( '#' + oldid + '_section_group_li' ).removeClass( 'active' );
					}
					el.find( '#' + relid + '_section_group_li' ).removeClass( 'activeChild' ).addClass( 'active' );
				} else {
					setTimeout(
						function() {
							el.find( '#' + relid + '_section_group_li' ).addClass( 'active' ).removeClass( 'activeChild' ).find( 'ul.subsection' ).slideDown();
						},
						1
					);

					if ( el.find( '#' + oldid + '_section_group_li' ).find( 'ul.subsection' ).length ) {
						el.find( '#' + oldid + '_section_group_li' ).find( 'ul.subsection' ).slideUp(
							'fast',
							function() {
								el.find( '#' + oldid + '_section_group_li' ).removeClass( 'active' ).removeClass( 'activeChild' );
							}
						);

						newParent = el.find( '#' + relid + '_section_group_li' ).parents( '.hasSubSections:first' );

						if ( newParent.length > 0 ) {
							el.find( '#' + relid + '_section_group_li' ).removeClass( 'active' );
							relid = newParent.find( '.redux-group-tab-link-a:first' ).data( 'rel' );

							if ( newParent.hasClass( 'empty_section' ) ) {
								newParent.find( '.subsection li:first' ).addClass( 'active' );
								el.find( '#' + relid + '_section_group_li' ).removeClass( 'active' ).addClass( 'activeChild' ).find( 'ul.subsection' ).slideDown();
								newParent = newParent.find( '.subsection li:first' );
								relid     = newParent.find( '.redux-group-tab-link-a:first' ).data( 'rel' );
							} else {
								el.find( '#' + relid + '_section_group_li' ).addClass( 'active' ).removeClass( 'activeChild' ).find( 'ul.subsection' ).slideDown();
							}
						}
					} else if ( el.find( '#' + oldid + '_section_group_li' ).parents( 'ul.subsection' ).length ) {
						if ( ! el.find( '#' + oldid + '_section_group_li' ).parents( '#' + relid + '_section_group_li' ).length ) {
							el.find( '#' + oldid + '_section_group_li' ).parents( 'ul.subsection' ).slideUp(
								'fast',
								function() {
									el.find( '#' + oldid + '_section_group_li' ).removeClass( 'active' );
									el.find( '#' + oldid + '_section_group_li' ).parents( '.redux-group-tab-link-li' ).removeClass( 'active' ).removeClass( 'activeChild' );
									el.find( '#' + relid + '_section_group_li' ).parents( '.redux-group-tab-link-li' ).addClass( 'activeChild' ).find( 'ul.subsection' ).slideDown();
									el.find( '#' + relid + '_section_group_li' ).addClass( 'active' );
								}
							);
						} else {
							el.find( '#' + oldid + '_section_group_li' ).removeClass( 'active' );
						}
					} else {
						el.find( '#' + oldid + '_section_group_li' ).removeClass( 'active' );

						if ( el.find( '#' + relid + '_section_group_li' ).parents( '.redux-group-tab-link-li' ).length ) {
							setTimeout(
								function() {
									el.find( '#' + relid + '_section_group_li' ).parents( '.redux-group-tab-link-li' ).addClass( 'activeChild' ).find( 'ul.subsection' ).slideDown();
								},
								50
							);

							el.find( '#' + relid + '_section_group_li' ).addClass( 'active' );
						}
					}
				}

				// Show the group.
				el.find( '#' + oldid + '_section_group' ).hide();

				el.find( '#' + relid + '_section_group' ).fadeIn(
					200,
					function() {
						if ( 0 !== el.find( '#redux-footer' ).length ) {
							$.redux.stickyInfo(); // Race condition fix.
						}

						$.redux.initFields();
					}
				);

				$( '#toplevel_page_' + redux.optName.args.slug ).find( '.current' ).removeClass( 'current' );
			}
		);

		if ( undefined !== redux.optName.last_tab ) {
			$( '#' + redux.optName.last_tab + '_section_group_li_a' ).trigger( 'click' );

			return;
		}

		tab = decodeURI( ( new RegExp( 'tab=(.+?)(&|$)' ).exec( location.search ) || [''])[1] );

		if ( '' !== tab ) {
			if ( $.cookie( 'redux_current_tab_get' ) !== tab ) {
				$.cookie(
					'redux_current_tab',
					tab,
					{
						expires: 7,
						path: '/'
					}
				);

				$.cookie(
					'redux_current_tab_get',
					tab,
					{
						expires: 7,
						path: '/'
					}
				);

				$.cookie(
					'redux_current_tab_' + redux.optName.args.opt_name,
					tab,
					{
						expires: 7,
						path: '/'
					}
				);

				$( '#' + tab + '_section_group_li' ).trigger( 'click' );
			}
		} else if ( '' !== $.cookie( 'redux_current_tab_get' ) ) {
			$.removeCookie( 'redux_current_tab_get' );
		}

		$( '.redux-container' ).each(
			function() {
				var boxIndex;

				if ( ! $( this ).parents( '.postbox-container:first' ).length ) {
					opt_name = $( '.redux-ajax-security' ).data( 'opt-name' );

					cookieName = 'redux_current_tab_' + opt_name;

					sTab = $( this ).find( '#' + $.cookie( cookieName ) + '_section_group_li_a' );
				} else {
					opt_name = $.redux.getOptName( this );

					boxIndex = $( this ).data( 'index' );

					if ( '' === boxIndex ) {
						boxIndex = 0;
					}

					cookieName = 'redux_metabox_' + boxIndex + '_current_tab_' + opt_name;

					sTab = $( this ).find( '#' + $.cookie( cookieName ) + '_section_group_li_a' );
				}

				// Tab the first item or the saved one.
				if ( null === $.cookie( cookieName ) || 'undefined' === typeof ( $.cookie( cookieName ) ) || 0 === sTab.length ) {
					$( this ).find( '.redux-group-tab-link-a:first' ).trigger( 'click' );
				} else {
					sTab.trigger( 'click' );
				}
			}
		);
	};
})( jQuery );
