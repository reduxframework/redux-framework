/* global jQuery, document, redux */

( function ( $ ) {
	'use strict';

	redux.field_objects           = redux.field_objects || {};
	redux.field_objects.accordion = redux.field_objects.accordion || {};

	redux.field_objects.accordion.init = function ( selector ) {
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-accordion:visible' );
		}

		$( selector ).each(
			function () {
				var $id;
				var group;
				var test;
				var accordionMarker;
				var openIcon;
				var closeIcon;
				var table;

				var el     = $( this );
				var parent = el;

				if ( ! el.hasClass( 'redux-field-container' ) ) {
					parent = el.parents( '.redux-field-container:first' );
				}
				if ( parent.hasClass( 'redux-field-init' ) ) {
					parent.removeClass( 'redux-field-init' );
				} else {
					return;
				}

				$id   = el.attr( 'data-id' );
				table = $( '#accordion-' + $id + '-marker' );

				table.parents( 'tr:first' ).css( { display: 'none' } ).prev( 'tr' ).css( 'border-bottom', 'none' );

				group = table.parents( '.redux-group-tab:first' );

				if ( ! group.hasClass( 'accordionsChecked' ) ) {
					group.addClass( 'accordionsChecked' );
					test = group.find( '.redux-accordion-indent-start h3' );

					$.each(
						test,
						function ( key, value ) {
							key = null;

							$( value ).css( 'margin-top', '20px' );
						}
					);

					if ( '20px' === group.find( 'h3:first' ).css( 'margin-top' ) ) {
						group.find( 'h3:first' ).css( 'margin-top', '0' );
					}

					accordionMarker = table;
					openIcon        = accordionMarker.data( 'open-icon' );
					closeIcon       = accordionMarker.data( 'close-icon' );

					group.find( '.redux-accordion-field' ).on(
						'click',
						function ( e ) {
							var id    = $( this ).attr( 'id' );
							var table = $( '#accordion-table-' + id );

							e.preventDefault();

							if ( table.closest( 'div' ).is( ':visible' ) ) {
								$( this ).find( '.el' ).removeClass( closeIcon ).addClass( openIcon );
								table.closest( 'div' ).slideUp();
							} else {
								table.closest( 'div' ).slideDown();
								$.redux.initFields();
								$( this ).find( '.el' ).removeClass( openIcon ).addClass( closeIcon );
							}
						}
					);

					group.find( '.redux-accordion-field' ).each(
						function () {
							var id;
							var state;
							var table;
							var position = $( this ).data( 'position' );

							if ( 'start' === position ) {
								id    = $( this ).attr( 'id' );
								state = Boolean( $( this ).data( 'state' ) );
								table = $( '#accordion-table-' + id );

								table.wrapAll( '<div class="redux-accordion-wrap"/>' );

								if ( false === state ) {
									table.closest( 'div' ).hide();
								} else {
									$( this ).find( '.el' ).removeClass( openIcon ).addClass( closeIcon );
								}
							}
						}
					);
				}
			}
		);
	};
} )( jQuery );
