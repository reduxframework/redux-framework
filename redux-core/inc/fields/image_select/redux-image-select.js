/* global redux, redux_change, jQuery */

(function ( $ ) {
	'use strict';

	redux.field_objects              = redux.field_objects || {};
	redux.field_objects.image_select = redux.field_objects.image_select || {};

	redux.field_objects.image_select.init = function ( selector ) {
		selector = $.redux.getSelector( selector, 'image_select' );

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

				// On label click, change the input and class.
				el.find( '.redux-image-select label img, .redux-image-select label .tiles' ).on(
					'click',
					function ( e ) {
						let presets;
						let data;
						let merge;
						let importCodeValue;

						const id = $( this ).closest( 'label' ).attr( 'for' );

						$( this ).parents( 'fieldset:first' ).find( '.redux-image-select-selected' )
						.removeClass( 'redux-image-select-selected' ).find( 'input[type="radio"]' ).prop( 'checked', false );

						$( this ).closest( 'label' ).find( 'input[type="radio"]' ).prop( 'checked' );

						if ( $( this ).closest( 'label' ).hasClass( 'redux-image-select-preset-' + id ) ) { // If they clicked on a preset, import!
							e.preventDefault();

							presets = $( this ).closest( 'label' ).find( 'input' );
							data    = presets.data( 'presets' );
							merge   = presets.data( 'merge' );

							if ( undefined !== merge && null !== merge ) {
								if ( 'string' === typeof( merge ) ) {
									merge = merge.split( '|' );
								}

								$.each(
									data,
									function ( index ) {
										if ( 'object' === typeof( redux.optName.options[index] ) && (
											true === merge || -1 !== $.inArray( index, merge ) )
										) {
											data[index] = $.extend( redux.optName.options[index], data[index] );
										}
									}
								);
							}

							if ( undefined !== presets && null !== presets ) {
								el.find( 'label[for="' + id + '"]' ).addClass( 'redux-image-select-selected' )
								.find( 'input[type="radio"]' ).attr( 'checked', true );

								window.onbeforeunload = null;

								importCodeValue = $( 'textarea[name="' + redux.optName.args.opt_name + '[import_code]"' );

								if ( 0 === importCodeValue.length ) {
									$( this ).append( '<textarea id="import-code-value" style="display:none;" name="' + redux.optName.args.opt_name + '[import_code]">' + JSON.stringify( data ) + '</textarea>' );
								} else {
									importCodeValue.val( JSON.stringify( data ) );
								}

								if ( 0 !== $( '#publishing-action #publish' ).length ) {
									$( '#publish' ).trigger( 'click' );
								} else {
									$( '#redux-import' ).trigger( 'click' );
								}
							}

							return false;
						} else {
							el.find( 'label[for="' + id + '"]' ).addClass( 'redux-image-select-selected' ).find( 'input[type="radio"]' ).prop( 'checked', true ).trigger( 'change' );

							redux_change( $( this ).closest( 'label' ).find( 'input[type="radio"]' ) );
						}
					}
				);

				// Used to display a full image preview of a tile/pattern.
				el.find( '.tiles' ).qtip(
					{
						content: {
							text: function () {
								return '<img src="' + $( this ).attr( 'rel' ) + '" style="max-width:150px;" alt=" />';
							}
						}, style: 'qtip-tipsy', position: {
							my: 'top center', // Position my top left...
							at: 'bottom center' // At the bottom right of...
						}
					}
				);
			}
		);
	};
})( jQuery );
