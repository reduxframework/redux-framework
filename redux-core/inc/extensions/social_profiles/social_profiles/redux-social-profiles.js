/* global redux, redux_change, reduxSocialDefaults */

( function ( $ ) {
	'use strict';

	redux.field_objects                         = redux.field_objects || {};
	redux.field_objects.social_profiles         = redux.field_objects.social_profiles || {};
	redux.field_objects.social_profiles.fieldID = '';
	redux.field_objects.social_profiles.optName = '';

	redux.field_objects.social_profiles.init = function ( selector ) {
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-social_profiles:visible' );
		}

		$( selector ).each(
			function () {
				const el   = $( this );
				let parent = el;

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

				redux.field_objects.social_profiles.modInit( el );
				redux.field_objects.social_profiles.sortListByOrder( el );
				redux.field_objects.social_profiles.sortEnableListByOrder( el );
				redux.field_objects.social_profiles.initializeResetButtons( el );
				redux.field_objects.social_profiles.showEnabledDetails( el );
			}
		);
	};

	redux.field_objects.social_profiles.modInit = function ( el ) {
		redux.field_objects.social_profiles.fieldID = el.find( '.redux-social-profiles-container' ).data( 'id' );
		redux.field_objects.social_profiles.optName = el.find( '.redux-social-profiles-container' ).data( 'opt-name' );

		el.find( '#redux-social-profiles-list' ).sortable(
			{
				revert: 'invalid',
				cursor: 'move',
				helper: 'clone',
				handle: '.redux-icon-preview',
				placeholder: 'sortable-placeholder',
				stop: function () {
					redux.field_objects.social_profiles.reorderSocialItems( el );

					redux_change( el.find( '.redux-social-profiles-container' ) );
				}
			}
		);

		el.find( '#redux-social-profiles-selector-list' ).sortable(
			{
				revert: 'invalid',
				cursor: 'move',
				helper: 'clone',
				placeholder: 'sortable-placeholder',
				stop: function () {
					redux.field_objects.social_profiles.reorderSocialEnable( el );

					redux_change( el.find( '.redux-social-profiles-container' ) );
				}
			}
		);

		el.find( '.redux-social-profiles-url-text' ).on(
			'blur',
			function () {
				const key = $( this ).data( 'key' );
				const val = $( this ).val();

				redux.field_objects.social_profiles.updateDataString( el, key, 'url', val );
			}
		);

		el.find( '.redux-social-profiles-item-enable' ).on(
			'click',
			function () {
				const key = $( this ).data( 'key' );

				redux.field_objects.social_profiles.toggleEnabled( el, key );
			}
		);

		el.find( '.redux-social-profiles-item-enabled input' ).on(
			'click',
			function ( e ) {
				let item;
				let key;

				e.preventDefault();

				item = $( this );
				key  = item.data( 'key' );

				redux.field_objects.social_profiles.toggleEnabled( el, key );
			}
		);
	};

	redux.field_objects.social_profiles.valueFromDataString = function ( el, key ) {
		let theData;

		const dataEl = el.find( '.redux-social-profiles-hidden-data-' + key );
		let rawData  = dataEl.val();

		rawData = decodeURIComponent( rawData );
		rawData = JSON.parse( rawData );

		theData = rawData.name;

		return theData;
	};

	redux.field_objects.social_profiles.updateDataString = function ( el, key, name, value ) {
		const dataEl = el.find( '.redux-social-profiles-hidden-data-' + key );
		let rawData  = dataEl.val();

		rawData = decodeURIComponent( rawData );
		rawData = JSON.parse( rawData );

		rawData[name] = value;

		rawData = JSON.stringify( rawData );
		rawData = encodeURIComponent( rawData );

		dataEl.val( rawData );
	};

	redux.field_objects.social_profiles.sortListByOrder = function ( el ) {
		const ul = el.find( 'ul#redux-social-profiles-list ' );
		const li = ul.children( 'li' );

		li.detach();
		[].sort.call(
			li,
			function ( a, b ) {
				return $( a ).find( '.redux-social-profiles-item-order input' ).val() - $( b ).find( '.redux-social-profiles-item-order input' ).val();
			}
		);

		ul.append( li );
	};

	redux.field_objects.social_profiles.sortEnableListByOrder = function ( el ) {
		const ul = el.find( 'ul#redux-social-profiles-selector-list' );
		const li = ul.children( 'li' );

		li.detach();
		[].sort.call(
			li,
			function ( a, b ) {
				return $( a ).data( 'order' ) - $( b ).data( 'order' );
			}
		);

		ul.append( li );
	};

	redux.field_objects.social_profiles.initializeResetButtons = function ( el ) {
		el.find( '.redux-social-profiles-item-reset a' ).on(
			'click',
			function () {
				let itemToReset;

				const buttonClicked = $( this );

				if ( buttonClicked.length > 0 ) {
					itemToReset = buttonClicked.data( 'value' );

					redux.field_objects.social_profiles.resetItem( el, itemToReset );
				}
			}
		);
	};

	redux.field_objects.social_profiles.resetItem = function ( el, itemID ) {
		const defaultTextColor       = reduxSocialDefaults[itemID].color;
		const defaultBackgroundColor = reduxSocialDefaults[itemID].background;

		el.find( '.redux-social-profiles-color-picker-' + itemID + '.text' ).spectrum( 'set', defaultTextColor );
		el.find( '.redux-social-profiles-color-picker-' + itemID + '.background' ).spectrum( 'set', defaultBackgroundColor );

		redux.field_objects.social_profiles.updateDataString( el, itemID, 'color', defaultTextColor );
		redux.field_objects.social_profiles.updateDataString( el, itemID, 'background', defaultBackgroundColor );

		redux.field_objects.social_profiles.updatePreview( el, itemID );
	};

	redux.field_objects.social_profiles.updatePreview = function ( el, itemID ) {
		const textColorInput       = redux.field_objects.social_profiles.valueFromDataString( el, itemID, 'color' );
		const backgroundColorInput = redux.field_objects.social_profiles.valueFromDataString( el, itemID, 'background' );

		const icon   = reduxSocialDefaults[itemID].icon;
		const symbol = el.find( '#redux-social-item-' + itemID + ' i.' + icon );

		symbol.css( 'background-color', backgroundColorInput );
		symbol.css( 'color', textColorInput );
	};

	redux.field_objects.social_profiles.toggleEnabled = function ( el, itemID ) {
		const itemEnable = el.find( '#redux-social-profiles-item-enable-' + itemID );
		const enabled    = itemEnable.hasClass( 'enabled' );

		let enabledBool;

		if ( enabled ) {
			itemEnable.removeClass( 'enabled' );
			enabledBool = false;
		} else {
			itemEnable.addClass( 'enabled' );
			enabledBool = true;
		}

		redux.field_objects.social_profiles.updateDataString( el, itemID, 'enabled', enabledBool );

		redux_change( el.find( '.redux-social-profiles-container' ) );

		redux.field_objects.social_profiles.showEnabledDetails( el );
	};

	redux.field_objects.social_profiles.showEnabledDetails = function ( el ) {
		let palette;

		const socialItems = el.find( 'li.redux-social-profiles-item-enable' );

		if ( socialItems.length > 0 ) {

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

			socialItems.each(
				function () {
					let enabledInput;
					let hidden;

					let item  = $( this );
					const key = item.data( 'key' );

					if ( item.hasClass( 'enabled' ) ) {

						el.find( '.redux-social-profiles-color-picker-' + key ).spectrum(
							{
								showAlpha: true,
								showInput: true,
								allowEmpty: true,
								className: 'redux-full-spectrum',
								showInitial: true,
								showPalette: true,
								showSelectionPalette: true,
								clickoutFiresChange: true,
								preferredFormat: 'rgb',
								localStorageKey: 'redux.social-profiles.spectrum',
								palette: palette,
								change: function ( color ) {
									let className;

									if ( $( this ).hasClass( 'text' ) ) {
										className = 'color';
									} else {
										className = 'background';
									}

									if ( null === color ) {
										color = '';
									} else {
										color = color.toRgbString();
									}

									redux.field_objects.social_profiles.updateDataString( el, key, className, color );
									redux.field_objects.social_profiles.updatePreview( el, key );
								}
							}
						);

						el.find( 'li#redux-social-item-' + key ).slideDown();

						enabledInput = el.find( 'input.checkbox-' + key );
						hidden       = $( enabledInput ).parent().find( '.checkbox-check-' + key );

						enabledInput.prop( 'checked', true );
						hidden.val( 1 );
					} else {
						item = el.find( 'li#redux-social-item-' + key );

						if ( item.is( ':hidden' ) ) {
							return;
						}

						item.slideUp( 'medium' );

						enabledInput = el.find( 'input.checkbox-' + key );
						hidden       = $( enabledInput ).parent().find( '.checkbox-check-' + key );

						enabledInput.prop( 'checked', false );
						hidden.val( 0 );
					}
				}
			);
		}
	};

	redux.field_objects.social_profiles.reorderSocialItems = function ( el ) {
		const socialItems = el.find( 'ul#redux-social-profiles-list li' );

		if ( socialItems.length > 0 ) {
			socialItems.each(
				function ( index ) {
					const item       = $( this );
					const key        = item.data( 'key' );
					const orderInput = item.find( '.redux-social-profiles-item-order input' );

					orderInput.val( index );
					redux.field_objects.social_profiles.updateDataString( el, key, 'order', index );

					el.find( '#redux-social-profiles-item-enable-' + key ).data( 'order', index );
				}
			);

			redux.field_objects.social_profiles.sortEnableListByOrder( el );
		}

	};

	redux.field_objects.social_profiles.reorderSocialEnable = function ( el ) {
		const socialItems = el.find( 'ul#redux-social-profiles-selector-list li' );

		if ( socialItems.length > 0 ) {
			socialItems.each(
				function ( index ) {
					const item       = $( this );
					const key        = item.data( 'key' );
					const control    = el.find( 'li#redux-social-item-' + key );
					const orderInput = control.find( '.redux-social-profiles-item-order input' );

					item.data( 'order', index );

					orderInput.val( index );

					redux.field_objects.social_profiles.updateDataString( el, key, 'order', index );
				}
			);
		}

		redux.field_objects.social_profiles.sortListByOrder( el );
	};
} )( jQuery );
