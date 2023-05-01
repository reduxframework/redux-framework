/* global redux, jQuery */

( function( $ ) {
	'use strict';

	redux.field_objects             = redux.field_objects || {};
	redux.field_objects.icon_select = redux.field_objects.icon_select || {};

	redux.field_objects.icon_select.getIconArray = function( el ) {
		var iconSelect = el.find( '.redux-icon-select' );

		return iconSelect.data( 'options' );
	};

	redux.field_objects.icon_select.reloadIcons = function( el, button, modal, value, text ) {
		window.wp.ajax.post(
			'redux_get_icons',
			{
				icon_set: value,
				select_text: text,
				nonce: button.data( 'nonce' ),
				data: redux.field_objects.icon_select.getIconArray( el )
			}
		).done(
			function( response ) {
				modal.find( '.redux-modal-loading' ).hide();

				modal.find( '.redux-modal-load' ).html( response.content );
			}
		).fail(
			function( response, status, error ) {
				modal.find( '.redux-modal-loading' ).hide();
				modal.find( '.redux-modal-load' ).html( error );

				modal.on(
					'click',
					function() {
						modal.addClass( 'hidden' );
						modal.off( 'click' );

						$( '.redux-icon-select-font' ).empty();
						modal.find( '.redux-modal-load' ).empty();
					}
				);
			}
		);
	};

	redux.field_objects.icon_select.init = function( selector ) {
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-icon_select:visible' );
		}

		$( selector ).each(
			function() {
				var el     = $( this );
				var parent = el;

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

				el.find( '.redux-icon-add' ).on(
					'click',
					function( e ) {
						var iconModalLoaded = false;
						var button          = $( this );
						var modal           = $( '#redux-modal-icon' );
						var select          = modal.find( '.redux-icon-select-font' );
						var selectVal;
						var selectText;
						var iconSets;

						e.preventDefault();

						// Extract icon set data.
						iconSets = el.find( '.redux-icon-select' ).data( 'icon-sets' );
						iconSets = JSON.parse( decodeURIComponent( iconSets ) );

						// Fill <select> with icon set options.
						$.each(
							iconSets,
							function ( i, item ) {
								select.append(
									$( '<option>', { value: i, text : item } )
								);
							}
						);

						modal.removeClass( 'hidden' );

						selectVal  = select.val();
						selectText = select.find( ':selected' ).text();

						if ( ! iconModalLoaded ) {
							modal.find( '.redux-modal-loading' ).show();

							window.wp.ajax.post(
								'redux_get_icons',
								{
									icon_set: selectVal,
									select_text: selectText,
									nonce: button.data( 'nonce' ),
									data: redux.field_objects.icon_select.getIconArray( el )
								}
							).done(
								function( response ) {
									modal.find( '.redux-modal-loading' ).hide();

									iconModalLoaded = true;

									var load = modal.find( '.redux-modal-load' ).html( response.content );

									load.off( 'click' );

									load.on(
										'click',
										'i',
										function( e ) {
											e.preventDefault();

											var icon = $( this ).attr( 'title' );

											el.find( '.redux-icon-select-preview i' ).removeAttr( 'class' ).addClass( icon );
											el.find( '.redux-icon-select-preview' ).removeClass( 'hidden' );
											el.find( '.redux-icon-remove' ).removeClass( 'hidden' );
											el.find( 'input' ).val( icon ).trigger( 'change' );

											modal.addClass( 'hidden' );

											select.empty();
											load.empty();
										}
									);

									modal.off( 'change' );

									modal.on(
										'change',
										'.redux-icon-select-font',
										function() {
											var value = $( this ).val();
											var text  = $( this ).find( ':selected' ).text();

											modal.find( '.redux-modal-loading' ).show();

											redux.field_objects.icon_select.reloadIcons( el, button, modal, value, text );
										}
									);

									modal.on(
										'change keyup',
										'.redux-icon-search',
										function() {
											var value = $( this ).val();
											var icons = load.find( 'i' );

											icons.each(
												function() {
													var elem = $( this );

													if ( elem.attr( 'title' ).search( new RegExp( value, 'i' ) ) < 0 ) {
														elem.hide();
													} else {
														elem.show();
													}
												}
											);
										}
									);

									modal.on(
										'click',
										'.redux-modal-close, .redux-modal-overlay',
										function() {
											modal.addClass( 'hidden' );

											select.empty();
											load.empty();
										}
									);
								}
							).fail(
								function( response, status, error ) {
									modal.find( '.redux-modal-loading' ).hide();
									modal.find( '.redux-modal-load' ).html( error );

									modal.on(
										'click',
										function() {
											select.empty();
											el.find( '.redux-modal-load' ).empty();

											modal.off( 'click' );
										}
									);
								}
							);
						}
					}
				);

				el.find( '.redux-icon-remove' ).on(
					'click',
					function( e ) {
						e.preventDefault();
						el.find( '.redux-icon-select-preview' ).addClass( 'hidden' );
						el.find( 'input' ).val( '' ).trigger( 'change' );
						$( this ).addClass( 'hidden' );
					}
				);
			}
		);
	};
} )( jQuery );
