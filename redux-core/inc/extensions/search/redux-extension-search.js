/* global jQuery, reduxSearch */

(function( $ ) {
	$( document ).ready(
		function() {
			$( '.redux-container' ).each(
				function( ) {
					if ( ! $( this ).hasClass( 'redux-no-sections' ) ) {
						$( this ).find( '.redux-main' ).prepend( '<input class="redux_field_search" type="text" placeholder="' + reduxSearch.search + '"/>' );
					}
				}
			);

			$( '.redux_field_search' ).on(
				'keypress',
				function( evt ) {

					// Determine where our character code is coming from within the event.
					var charCode = evt.charCode || evt.keyCode;

					if ( 13 === charCode ) { // Enter key's keycode.
						return false;
					}
				}
			).typeWatch(
				{
					callback: function( searchString ) {
						var searchArray;
						var parent;
						var expanded_options;

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
							function() {
								if ( '' !== searchString ) {
									$( this ).parents( 'tr:first' ).hide();
								} else {
									$( this ).parents( 'tr:first' ).show();
								}
							}
						);

						parent.find( '.form-table tr' ).filter(
							function() {
								var isMatch = true, text = $( this ).find( '.redux_field_th' ).text().toLowerCase();

								if ( ! text || '' === text ) {
									return false;
								}

								$.each(
									searchArray,
									function( i, searchStr ) {
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
	);
} )( jQuery );
