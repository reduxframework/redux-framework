/* global redux, redux_ajax_script,redux_change, reduxColorSchemeAfterUpdateHTML */

( function ( $ ) {
	'use strict';

	redux.field_objects                             = redux.field_objects || {};
	redux.field_objects.color_scheme                = redux.field_objects.color_scheme || {};
	redux.field_objects.color_scheme.nonce          = '';
	redux.field_objects.color_scheme.fieldID        = '';
	redux.field_objects.color_scheme.optName        = '';
	redux.field_objects.color_scheme.el             = '';
	redux.field_objects.color_scheme.showTooltips   = '';
	redux.field_objects.color_scheme.default_params = {};

	redux.field_objects.color_scheme.hexToRGBA = function ( hex, alpha ) {
		var result;
		var r;
		var b;
		var g;

		if ( null === hex ) {
			result = '';
		} else {
			hex = hex.replace( '#', '' );

			r = parseInt( hex.substring( 0, 2 ), 16 );
			g = parseInt( hex.substring( 2, 4 ), 16 );
			b = parseInt( hex.substring( 4, 6 ), 16 );

			result = 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
		}

		return result;
	};

	redux.field_objects.color_scheme.init = function ( selector ) {
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-color_scheme:visible' );
		}

		$( selector ).each(
			function () {
				var el                              = $( this );
				var parent                          = el;
				redux.field_objects.color_scheme.el = el;

				if ( ! el.hasClass( 'redux-field-container' ) ) {
					parent = el.parents( '.redux-field-container:first' );
				}

				if ( parent.is( ':hidden' ) ) {
					return;
				}

				if ( parent.hasClass( 'redux-field-init' ) ) {
					parent.removeClass( 'redux-field-init' );
				} else {
					return;
				}

				redux.field_objects.color_scheme.modInit( el );
				redux.field_objects.color_scheme.setAccordions( el );
				redux.field_objects.color_scheme.import( el );
				redux.field_objects.color_scheme.delete( el );
				redux.field_objects.color_scheme.save( el );
				redux.field_objects.color_scheme.qtip( el );
			}
		);
	};

	redux.field_objects.color_scheme.qtip = function ( el ) {
		var tooltips;
		var shadow;
		var rounded;
		var color;
		var style;
		var myPos;
		var atPos;
		var classes;

		var destroy = false;

		if ( false === redux.field_objects.color_scheme.showTooltips ) {
			destroy = true;
		}

		tooltips = el.find( '.redux-color-scheme-container' ).data( 'tooltips' );

		if ( '' === tooltips ) {
			return;
		}

		tooltips = decodeURIComponent( tooltips );
		tooltips = JSON.parse( tooltips );

		shadow = '';
		if ( true === tooltips.style.shadow ) {
			shadow = 'qtip-shadow';
		}

		rounded = '';
		if ( true === tooltips.style.rounded ) {
			rounded = 'qtip-rounded';
		}

		color = '';
		if ( '' !== tooltips.style.color ) {
			color = 'qtip-' + tooltips.style.color;
		}

		style = '';
		if ( '' !== tooltips.style.style ) {
			style = 'qtip-' + tooltips.style.style;
		}

		classes = shadow + ',' + color + ',' + rounded + ',' + style;
		classes = classes.replace( /,/g, ' ' );

		myPos = tooltips.position.my;
		atPos = tooltips.position.at;

		myPos = $.redux.verifyPos( myPos.toLowerCase(), true );
		atPos = $.redux.verifyPos( atPos.toLowerCase(), false );

		$( 'li.redux-cs-qtip' ).each(
			function () {
				var content = $( this ).attr( 'qtip-content' );
				var title   = $( this ).attr( 'qtip-title' );

				if ( '' === content && '' === title ) {
					return;
				}

				if ( true === destroy ) {
					$( this ).qtip( 'destroy', true );
				} else {
					$( this ).qtip(
						{
							content: {
								text: content, title: title
							},
							show: {
								effect: function () {
									if ( 'slide' === tooltips.effect.show_effect ) {
										$( this ).slideDown( tooltips.effect.show_duration );
									} else if ( 'fade' === tooltips.effect.show_effect ) {
										$( this ).fadeIn( tooltips.effect.show_duration );
									} else {
										$( this ).show();
									}
								}, event: tooltips.effect.show_event
							},
							hide: {
								effect: function () {
									if ( 'slide' === tooltips.effect.hide_effect ) {
										$( this ).slideUp( tooltips.effect.hide_duration );
									} else if ( 'fade' === tooltips.effect.hide_effect ) {
										$( this ).fadeOut( tooltips.effect.hide_duration );
									} else {
										$( this ).hide();
									}
								}, event: tooltips.effect.hide_event
							},
							style: {
								classes: classes
							},
							position: {
								my: myPos, at: atPos
							}
						}
					);
				}
			}
		);
	};

	redux.field_objects.color_scheme.setAccordions = function ( el ) {
		var accordionSection;
		var isOpen;

		var ulContainer = el.find( 'ul.redux-scheme-layout' );

		var openIcon  = ulContainer.data( 'open-icon' );
		var closeIcon = ulContainer.data( 'close-icon' );

		var isAccordion = Boolean( el.find( '.redux-color-scheme-container' ).data( 'accordion' ) );

		if ( true === isAccordion ) {
			accordionSection = el.find( '.redux-color-scheme-accordion-section' );

			accordionSection.each(
				function () {
					isOpen = Boolean( $( this ).data( 'state' ) );

					if ( false === isOpen ) {
						$( this ).hide();
					} else {
						$( this ).prev( '.redux-color-scheme-accordion' ).find( '.el' ).removeClass( openIcon ).addClass( closeIcon );
						redux.field_objects.color_scheme.initColorPicker( $( this ) );
					}
				}
			);

			el.find( '.redux-color-scheme-accordion' ).on(
				'click',
				function ( e ) {
					var nextAccordion;

					e.preventDefault();

					nextAccordion = $( this ).next( '.redux-color-scheme-accordion-section' );

					if ( nextAccordion.is( ':visible' ) ) {
						$( this ).find( '.el' ).removeClass( closeIcon ).addClass( openIcon );
						nextAccordion.slideUp();
					} else {
						$( this ).find( '.el' ).removeClass( openIcon ).addClass( closeIcon );

						redux.field_objects.color_scheme.initColorPicker( nextAccordion );

						nextAccordion.slideDown();
					}
				}
			);
		} else {
			redux.field_objects.color_scheme.initColorPicker( el );
		}
	};

	redux.field_objects.color_scheme.modInit = function ( el ) {
		var select2_handle;
		var select2_params;

		redux.field_objects.color_scheme.nonce        = el.find( '.redux-color-scheme-container' ).data( 'nonce' );
		redux.field_objects.color_scheme.fieldID      = el.find( '.redux-color-scheme-container' ).data( 'id' );
		redux.field_objects.color_scheme.optName      = el.find( '.redux-color-scheme-container' ).data( 'opt-name' );
		redux.field_objects.color_scheme.showTooltips = el.find( '.redux-color-scheme-container' ).data( 'show-tooltips' );

		redux.field_objects.color_scheme.default_params = {
			triggerChange: true, allowClear: false
		};

		select2_handle = el.find( '.redux-container-color_scheme' ).find( '.select2_params' );

		if ( select2_handle.length > 0 ) {
			select2_params = select2_handle.val();
			select2_params = JSON.parse( select2_params );

			redux.field_objects.color_scheme.default_params = $.extend( {}, redux.field_objects.color_scheme.default_params, select2_params );
		}

		// Init select2 for scheme select.
		el.find( '.redux-scheme-select' ).select2( redux.field_objects.color_scheme.default_params );

		// Init select2 for select boxes.
		el.find( 'select.redux-color-scheme-opt-select' ).select2( redux.field_objects.color_scheme.default_params );

		// Auto select text in input box.
		el.find( 'input.redux-scheme-input-' + redux.field_objects.color_scheme.fieldID ).on(
			'click',
			function ( e ) {
				this.focus();
				this.select();
				e.preventDefault();
			}
		);

		// Set scheme input box to selected value.
		el.find( 'input.redux-scheme-input-' + redux.field_objects.color_scheme.fieldID ).val( el.find( '#redux-scheme-select-' + redux.field_objects.color_scheme.fieldID ).val() );

		// Select change.
		el.find( '#redux-scheme-select-' + redux.field_objects.color_scheme.fieldID ).on(
			'change',
			function () {
				redux.field_objects.color_scheme.selectChange( el );
			}
		);

		el.find( '#redux-' + redux.field_objects.color_scheme.fieldID + '-tooltip-checkbox' ).on(
			'change',
			function () {
				var checked = $( this ).is( ':checked' );
				$( this ).val( checked );

				redux.field_objects.color_scheme.showTooltips = checked;
				redux.field_objects.color_scheme.qtip( el );
			}
		);
	};

	redux.field_objects.color_scheme.import = function ( el ) {
		var fieldID  = redux.field_objects.color_scheme.fieldID;
		var optName  = redux.field_objects.color_scheme.optName;
		var myUpload = el.find( '#redux-' + fieldID + '-import-scheme-button' );
		var submit   = myUpload.data( 'submit' );
		var nonce    = myUpload.data( 'nonce' );

		myUpload.upload(
			{
				name: 'file',
				method: 'post',
				action: submit + 'class-redux-color-scheme-import.php',
				nonce: nonce,
				enctype: 'multipart/form-data',
				fileType: '.json',
				params: {
					type: 'import',
					field_id: fieldID,
					opt_name: optName,
					nonce: nonce
				},
				autoSubmit: true,
				onSubmit: function () {},
				onComplete: function ( data ) {

					// Parse JSON.
					data = JSON.parse( data );

					// Set success/fail message.
					el.find( '#redux-' + fieldID + '-scheme-message-notice h2' ).html( data.data );

					// Show message.
					$.blockUI(
						{
							message: el.find( '#redux-' + fieldID + '-scheme-message-notice' ),
							theme: false,
							css: {
								width: '500px', padding: '5px'
							}
						}
					);

					// Click OK.
					$( '#redux-' + fieldID + '-scheme-ok' ).on(
						'click',
						function () {

							// Unload modal.
							$.unblockUI();

							// Reload window on success.
							if ( true === data.result ) {
								window.onbeforeunload = '';
								location.reload();
							}

							// Bail out!
							return false;
						}
					);
				},

				// Unused.
				onSelect: function () {
				}
			}
		);
	};

	redux.field_objects.color_scheme.delete = function ( el ) {
		var field_id = redux.field_objects.color_scheme.fieldID;

		el.find( '#redux-' + field_id + '-delete-scheme-button' ).on(
			'click',
			function ( event ) {
				var select_name;

				// Prevent default action.
				event.preventDefault();

				// Retrieve selected scheme name.
				select_name = el.find( '#redux-scheme-select-' + field_id ).val();

				// Check for 'default', as we don't want to delete it.
				if ( 'default' === select_name.toLowerCase() ) {

					// Set on screen message.
					el.find( '#redux-' + field_id + '-scheme-message-notice h2' ).html( 'Cannot delete the <strong>Default</strong> scheme, as it is reserved.' );

					// Show message.
					$.blockUI(
						{
							message: el.find( '#redux-' + field_id + '-scheme-message-notice' ),
							theme: false,
							css: {
								width: '500px',
								padding: '5px'
							}
						}
					);

					// Clicked OK.
					$( '#redux-' + field_id + '-scheme-ok' ).on(
						'click',
						function () {
							$.unblockUI();
							return false;
						}
					);

					return false;
				}

				// Load delete question modal.
				$.blockUI(
					{
						message: el.find( '#redux-' + field_id + '-delete-scheme-question' ),
						theme: false,
						css: {
							width: '500px',
							padding: '5px'
						}
					}
				);

				// Clicked yes.
				$( '#redux-' + field_id + '-delete-scheme-yes' ).on(
					'click',
					function () {
						var data;
						var wait_msg;

						// If selected scheme name is valid...
						if ( select_name ) {

							// Set ajax parameters.
							data = {
								action: 'redux_color_schemes',
								nonce: redux.field_objects.color_scheme.nonce,
								opt_name: redux.field_objects.color_scheme.optName,
								type: 'delete',
								scheme_id: select_name,
								field_id: field_id
							};

							// Load Please wait message.
							wait_msg = el.find( '#redux-' + field_id + '-scheme-wait-message h1' ).html();
							$.blockUI(
								{
									message: '<h1>' + wait_msg + 'deleting scheme.<h1/>',
									theme: false,
									css: {
										width: '500px',
										padding: '5px'
									}
								}
							);

							// Post ajax.
							$.post(
								redux_ajax_script.ajaxurl,
								data,
								function ( response ) {

									// Successful delete.
									if ( 'success' === response ) {

										// Remove deleted scheme from selector.
										el.find( '#redux-scheme-select-' + field_id + ' option[value=\'' + select_name + '\']' ).remove();

										// Clear selector (default).
										el.find( '.redux-scheme-select' ).select2();
										el.find( '.redux-scheme-select' ).val( 'Default' ).trigger( 'change' );

										// Clear input box.
										el.find( 'input.redux-scheme-input-' + field_id ).val( '' );

										// Update modal message text.
										el.find( '#redux-' + field_id + '-scheme-message-notice h2' ).html( 'The <strong>' + select_name + '</strong> scheme has been removed.<br/><br/>The color table has been reset to default values.' );

										// Display the notice.
										$.blockUI(
											{
												message: el.find( '#redux-' + field_id + '-scheme-message-notice' ),
												theme: false,
												css: {
													width: '500px',
													padding: '5px'
												}
											}
										);

										// Click OK, unload msg, bail out.
										$( '#redux-' + field_id + '-scheme-ok' ).on(
											'click',
											function () {
												$.unblockUI();

												// Update the HTML preview.
												redux.field_objects.color_scheme.updateSchemeHTML( 0, el );

												return false;
											}
										);
									} else {

										// Update modal message with fail response.
										el.find( '#redux-' + field_id + '-scheme-message-notice h2' ).html( 'Delete failed: ' + response );

										// Display the notice.
										$.blockUI(
											{
												message: el.find( '#redux-' + field_id + '-scheme-message-notice' ),
												theme: false,
												css: {
													width: '500px',
													padding: '5px'
												}
											}
										);

										// Click OK, unload msg, bail out.
										$( '#redux-' + field_id + '-scheme-ok' ).on(
											'click',
											function () {
												$.unblockUI();
												return false;
											}
										);
									}
								}
							);
						}
					}
				);

				// Clicked no.
				$( '#redux-' + field_id + '-delete-scheme-no' ).on(
					'click',
					function () {
						$.unblockUI();
						return false;
					}
				);
			}
		);
	};

	redux.field_objects.color_scheme.save = function ( el ) {
		var field_id = redux.field_objects.color_scheme.fieldID;

		el.find( '#redux-' + field_id + '-save-scheme-button' ).on(
			'click',
			function ( event ) {
				var input_text;
				var scheme_name;
				var data;
				var wait_msg;

				var arrData = [];

				event.preventDefault();

				// Prevent blank input.
				input_text = el.find( 'input.redux-scheme-input-' + field_id ).val();

				// Trim.
				input_text = input_text.replace( /^\s+|\s+$/gm, '' );

				// Allow only alphanumeric, space, dash, and underscore.
				input_text = input_text.replace( /[^a-z0-9\s\-]/gi, '' );

				// Do nothing if input_text is empty.
				if ( '' === input_text ) {
					return false;
				}

				// Check for default scheme.
				if ( 'default' === input_text.toLowerCase() ) {

					// Update notice message.
					el.find( '#redux-' + field_id + '-scheme-message-notice h2' ).html( 'The name <strong>Default</strong> cannot be used as it is reserved.' );

					// Display msg.
					$.blockUI(
						{
							message: el.find( '#redux-' + field_id + '-scheme-message-notice' ),
							theme: false,
							css: {
								width: '500px',
								padding: '5px'
							}
						}
					);

					// Clicked OK.  Close message and exit.
					$( '#redux-' + field_id + '-scheme-ok' ).on(
						'click',
						function () {
							$.unblockUI();
							return false;
						}
					);

					// Bail out.
					return false;
				}

				// Enum through them all and collect data.
				el.find( '.redux-scheme-layout-container' ).each(
					function () {
						var obj = $( this ).children( '.redux-color-scheme' );

						var title = obj.data( 'title' );
						var id    = obj.data( 'id' );
						var color = obj.data( 'hex-color' );
						var alpha = obj.data( 'alpha' );
						var rgba  = obj.data( 'rgba' );
						var group = obj.data( 'group' );

						// Push data into the array.
						arrData.push(
							{
								id: id,
								title: title,
								color: color,
								alpha: alpha,
								rgba: rgba,
								group: group
							}
						);
					}
				);

				arrData = JSON.stringify( arrData );
				arrData = encodeURIComponent( arrData );

				// Get scheme name from text box.
				scheme_name = el.find( '.redux-scheme-input-' + field_id ).val();

				// If one exists, proceed.
				if ( scheme_name ) {

					// Set ajax parameters.
					data = {
						action: 'redux_color_schemes',
						nonce: redux.field_objects.color_scheme.nonce,
						opt_name: redux.field_objects.color_scheme.optName,
						type: 'save',
						scheme_name: scheme_name,
						scheme_data: arrData,
						field_id: field_id
					};

					// Get default wait message.
					wait_msg = el.find( '#redux-' + field_id + '-scheme-wait-message h1' ).html();

					// Load wait message.
					$.blockUI(
						{
							message: '<h1>' + wait_msg + 'saving scheme.</h1>',
							theme: false,
							css: {
								width: '500px',
								padding: '5px'
							}
						}
					);

					el.find( '.redux-scheme-select' ).select2( 'destroy' );

					// Post ajax.
					$.post(
						redux_ajax_script.ajaxurl,
						data,
						function ( response ) {

							// New selector change hook.
							el.find( '#redux-scheme-select-' + field_id ).on(
								'change',
								function () {
									redux.field_objects.color_scheme.selectChange( el );
								}
							);

							if ( 'fail' === response ) {
								el.find( '#redux-' + field_id + '-scheme-message-notice h2' ).html( 'The scheme <strong>' + scheme_name + '</strong> already exists and cannot be added again.' );
							} else {

								// Replace selector with updated values.
								el.find( '#redux-scheme-select-' + field_id ).replaceWith( response );

								// Update notice message.
								el.find( '#redux-' + field_id + '-scheme-message-notice h2' ).html( 'The scheme <strong>' + scheme_name + '</strong> has been added to your scheme list.' );
							}

							// Display notice message.
							$.blockUI(
								{
									message: el.find( '#redux-' + field_id + '-scheme-message-notice' ),
									theme: false,
									css: {
										width: '500px',
										padding: '5px'
									}
								}
							);

							// Clicked OK.  Unload and exit.
							$( '#redux-' + field_id + '-scheme-ok' ).on(
								'click',
								function () {
									$.unblockUI();
									return false;
								}
							);

							//redux.field_objects.color_scheme.init( el );
							el.find( '#redux-scheme-select-' + redux.field_objects.color_scheme.fieldID ).on(
								'change',
								function () {
									redux.field_objects.color_scheme.selectChange( el );
								}
							);

							el.find( '.redux-scheme-select' ).select2( redux.field_objects.color_scheme.default_params );
							el.find( 'select.redux-color-scheme-opt-select' ).select2( redux.field_objects.color_scheme.default_params );
						}
					);
				}
			}
		);
	};

	redux.field_objects.color_scheme.selectChange = function ( el ) {
		var selected;

		var field_id = redux.field_objects.color_scheme.fieldID;

		// Fade out the colour pickers.
		el.find( 'ul.redux-scheme-layout' ).fadeOut();

		// Get selected value.
		selected = el.find( '#redux-scheme-select-' + field_id ).val();

		// Remove default blank value, if any.
		el.find( '#redux-scheme-select-' + field_id + ' option[value=\'\']' ).remove();

		// Get scheme name from input box.
		el.find( 'input.redux-scheme-input-' + field_id ).val( selected );

		// Update colour pickers.
		redux.field_objects.color_scheme.updateSchemeHTML( selected, el );
	};

	redux.field_objects.color_scheme.updateSchemeHTML = function ( selected, el ) {
		var field_class;
		var data;

		var field_id = redux.field_objects.color_scheme.fieldID;

		// Get default wait msg.
		var wait_msg = el.find( '#redux-' + field_id + '-scheme-wait-message h1' ).html();

		// Display appended msg.
		$.blockUI(
			{
				message: '<h1>' + wait_msg + 'updating scheme.</h1>',
				theme: false,
				css: {
					width: '500px',
					padding: '5px'
				}
			}
		);

		// Get field class.  Needed for custom classes from field array.
		field_class = el.find( '.redux-color-scheme' ).attr( 'class' );

		// Set ajax parameters.
		data = {
			action: 'redux_color_schemes',
			nonce: redux.field_objects.color_scheme.nonce,
			opt_name: redux.field_objects.color_scheme.optName,
			type: 'update',
			scheme_id: selected,
			field_id: field_id,
			field_class: field_class
		};

		// Post ajax.
		$.post(
			redux_ajax_script.ajaxurl,
			data,
			function ( response ) {

				// Replace colour picker layout.
				el.find( 'ul.redux-scheme-layout' ).replaceWith( response );

				// Re-init colour pickers.
				redux.field_objects.color_scheme.setAccordions( el );

				// Fade colour pickers back in.
				el.find( 'ul.redux-scheme-layout' ).fadeIn();

				// Remove waiting msg.
				$.unblockUI();

				// Set flags for compiler.
				redux_change( el.find( '.redux-color-scheme-container' ) );

				el.find( 'select.redux-color-scheme-opt-select' ).select2( redux.field_objects.color_scheme.default_params );

				if ( 'function' === typeof reduxColorSchemeAfterUpdateHTML ) {
					reduxColorSchemeAfterUpdateHTML( $( this ), el );
				}

			}
		);
	};

	// Initialize colour picker.
	redux.field_objects.color_scheme.initColorPicker = function ( el ) {

		// Get field ID.
		var field_id = redux.field_objects.color_scheme.fieldID;

		// Get the color scheme container.
		var colorpickerInput = el.find( '.redux-color-scheme' );

		// Get alpha value and sanitize it.
		var currentAlpha = colorpickerInput.data( 'current-alpha' );

		// Get colour value and sanitize it.
		var currentColor = colorpickerInput.data( 'current-color' );

		var outputTransparent = colorpickerInput.data( 'output-transparent' );

		// Color picker arguments.
		var container = redux.field_objects.color_scheme.el.find( '.redux-color-scheme-container' );

		// Get, decode and parse palette.
		var palette = container.data( 'palette' );

		var pickerGap = container.data( 'picker-gap' );

		var pickerFontSize = container.data( 'picker-font-size' );

		// Get and sanitize show input argument.
		var showInput = container.data( 'show-input' );

		// Get and sanitize show initial argument.
		var showInitial = container.data( 'show-initial' );

		// Get and sanitize show alpha argument.
		var showAlpha = container.data( 'show-alpha' );

		// Get and sanitize allow empty argument.
		var allowEmpty = container.data( 'allow-empty' );

		// Get and sanitize show palette argument.
		var showPalette = container.data( 'show-palette' );

		// Get and sanitize show palette only argument.
		var showPaletteOnly = container.data( 'show-palette-only' );

		// Get and sanitize show selection palette argument.
		var showSelectionPalette = container.data( 'show-selection-palette' );

		// Get max palette size.
		var maxPaletteSize = Number( container.data( 'max-palette-size' ) );

		// Get and sanitize clickout fires change argument.
		var clickoutFiresChange = container.data( 'clickout-fires-change' );

		// Get choose button text.
		var chooseText = String( container.data( 'choose-text' ) );

		// Get cancel button text.
		var cancelText = String( container.data( 'cancel-text' ) );

		// Get and sanitize show buttons argument.
		var showButtons = container.data( 'show-buttons' );

		// Get container class.
		var containerClass = String( container.data( 'container-class' ) );

		// Get replacer class.
		var replacerClass = String( container.data( 'replacer-class' ) );

		// Picker gap css.
		el.find( 'li.redux-scheme-layout' ).css( 'width', pickerGap );

		// Picker font size.
		el.find( '.redux-layout-label' ).attr( 'style', 'font-size: ' + pickerFontSize + '!important' );

		currentAlpha      = Number( ( null === currentAlpha || undefined === currentAlpha ) ? 1 : currentAlpha );
		currentColor      = ( '' === currentColor || 'transparent' === currentColor ) ? '' : currentColor;
		outputTransparent = Boolean( ( '' === outputTransparent ) ? false : outputTransparent );

		palette = decodeURIComponent( palette );
		palette = JSON.parse( palette );

		// Default palette.
		if ( null === palette ) {
			palette = [
				['#000000', '#434343', '#666666', '#999999', '#b7b7b7', '#cccccc', '#d9d9d9', '#efefef', '#f3f3f3', '#ffffff'],
				['#980000', '#ff0000', '#ff9900', '#ffff00', '#00ff00', '#00ffff', '#4a86e8', '#0000ff', '#9900ff', '#ff00ff'],
				['#e6b8af', '#f4cccc', '#fce5cd', '#fff2cc', '#d9ead3', '#d9ead3', '#c9daf8', '#cfe2f3', '#d9d2e9', '#ead1dc'],
				['#dd7e6b', '#ea9999', '#f9cb9c', '#ffe599', '#b6d7a8', '#a2c4c9', '#a4c2f4', '#9fc5e8', '#b4a7d6', '#d5a6bd'],
				['#cc4125', '#e06666', '#f6b26b', '#ffd966', '#93c47d', '#76a5af', '#6d9eeb', '#6fa8dc', '#8e7cc3', '#c27ba0'],
				['#a61c00', '#cc0000', '#e69138', '#f1c232', '#6aa84f', '#45818e', '#3c78d8', '#3d85c6', '#674ea7', '#a64d79'],
				['#85200c', '#990000', '#b45f06', '#bf9000', '#38761d', '#134f5c', '#1155cc', '#0b5394', '#351c75', '#741b47'],
				['#5b0f00', '#660000', '#783f04', '#7f6000', '#274e13', '#0c343d', '#1c4587', '#073763', '#20124d', '#4c1130']
			];
		}

		pickerGap            = String( ( '' === pickerGap ) ? '60px' : pickerGap );
		pickerFontSize       = String( ( '' === pickerFontSize ) ? '11px' : pickerFontSize );
		showInput            = Boolean( ( '' === showInput ) ? false : showInput );
		showInitial          = Boolean( ( '' === showInitial ) ? false : showInitial );
		showAlpha            = Boolean( ( '' === showAlpha ) ? false : showAlpha );
		allowEmpty           = Boolean( ( '' === allowEmpty ) ? false : allowEmpty );
		showPalette          = Boolean( ( '' === showPalette ) ? false : showPalette );
		showPaletteOnly      = Boolean( ( '' === showPaletteOnly ) ? false : showPaletteOnly );
		showSelectionPalette = Boolean( ( '' === showSelectionPalette ) ? false : showSelectionPalette );
		clickoutFiresChange  = Boolean( ( '' === clickoutFiresChange ) ? false : clickoutFiresChange );
		showButtons          = Boolean( ( '' === showButtons ) ? false : showButtons );

		// Color picker options.
		colorpickerInput.spectrum(
			{
				color: currentColor,
				showAlpha: showAlpha,
				showInput: showInput,
				allowEmpty: allowEmpty,
				className: 'redux-full-spectrum',
				showInitial: showInitial,
				showPalette: showPalette,
				showSelectionPalette: showSelectionPalette,
				maxPaletteSize: maxPaletteSize,
				showPaletteOnly: showPaletteOnly,
				clickoutFiresChange: clickoutFiresChange,
				chooseText: chooseText,
				cancelText: cancelText,
				showButtons: showButtons,
				containerClassName: containerClass,
				replacerClassName: replacerClass,
				preferredFormat: 'hex6',
				localStorageKey: 'redux.spectrum.' + field_id,
				palette: palette,

				// On change.
				change: function ( color ) {
					var colorVal;
					var alphaVal;
					var rgbaVal;
					var blockID;
					var dataBlock;
					var rawData;

					if ( null === color ) {
						if ( true === outputTransparent ) {
							colorVal = 'transparent';
						} else {
							colorVal = null;
						}
						alphaVal = null;
					} else {
						colorVal = color.toHexString();
						alphaVal = color.getAlpha();
					}

					if ( 'transparent' !== colorVal ) {
						rgbaVal = redux.field_objects.color_scheme.hexToRGBA( colorVal, alphaVal );
					} else {
						rgbaVal = 'transparent';
					}

					blockID   = $( this ).data( 'block-id' );
					dataBlock = el.find( 'input#' + blockID + '-data' );
					rawData   = dataBlock.val();

					rawData = decodeURIComponent( rawData );
					rawData = JSON.parse( rawData );

					rawData.color = colorVal;
					rawData.alpha = alphaVal;
					rawData.rgba  = rgbaVal;

					rawData = JSON.stringify( rawData );
					rawData = encodeURIComponent( rawData );

					dataBlock.val( rawData );

					redux_change( redux.field_objects.color_scheme.el.find( '.redux-color-scheme-container' ) );
				}
			}
		);
	};
} )( jQuery );
