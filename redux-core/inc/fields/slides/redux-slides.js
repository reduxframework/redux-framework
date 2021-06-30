/*global redux_change, redux, jQuery */

(function( $ ) {
	'use strict';

	redux.field_objects        = redux.field_objects || {};
	redux.field_objects.slides = redux.field_objects.slides || {};

	redux.field_objects.slides.init = function( selector ) {
		selector = $.redux.getSelector( selector, 'slides' );

		$( selector ).each(
			function() {
				var el     = $( this );
				var parent = el;

				redux.field_objects.media.init( el );

				if ( ! el.hasClass( 'redux-field-container' ) ) {
					parent = el.parents( '.redux-field-container:first' );
				}

				if ( parent.is( ':hidden' ) ) {
					return;
				}

				if ( parent.hasClass( 'redux-container-slides' ) ) {
					parent.addClass( 'redux-field-init' );
				}

				if ( parent.hasClass( 'redux-field-init' ) ) {
					parent.removeClass( 'redux-field-init' );
				} else {
					return;
				}

				el.find( '.redux-slides-remove' ).on(
					'click',
					function() {
						var slideCount;
						var contentNewTitle;

						redux_change( $( this ) );

						$( this ).parent().siblings().find( 'input[type="text"]' ).val( '' );
						$( this ).parent().siblings().find( 'textarea' ).val( '' );
						$( this ).parent().siblings().find( 'input[type="hidden"]' ).val( '' );

						slideCount = $( this ).parents( '.redux-container-slides:first' ).find( '.redux-slides-accordion-group' ).length;

						if ( slideCount > 1 ) {
							$( this ).parents( '.redux-slides-accordion-group:first' ).slideUp(
								'medium',
								function() {
									$( this ).remove();
								}
							);
						} else {
							contentNewTitle = $( this ).parent( '.redux-slides-accordion' ).data( 'new-content-title' );

							$( this ).parents( '.redux-slides-accordion-group:first' ).find( '.remove-image' ).trigger( 'click' );
							$( this ).parents( '.redux-container-slides:first' ).find( '.redux-slides-accordion-group:last' ).find( '.redux-slides-header' ).text( contentNewTitle );
						}
					}
				);

				el.find( '.redux-slides-add' ).off( 'click' ).on(
					'click',
					function() {
						var contentNewTitle;

						var newSlide    = $( this ).prev().find( '.redux-slides-accordion-group:last' ).clone( true );
						var slideCount  = $( newSlide ).find( '.slide-title' ).attr( 'name' ).match( /[0-9]+(?!.*[0-9])/ );
						var slideCount1 = slideCount * 1 + 1;

						$( newSlide ).find( 'input[type="text"], input[type="hidden"], textarea' ).each(
							function() {
								$( this ).attr( 'name', $( this ).attr( 'name' ).replace( /[0-9]+(?!.*[0-9])/, slideCount1 ) ).attr( 'id', $( this ).attr( 'id' ).replace( /[0-9]+(?!.*[0-9])/, slideCount1 ) );

								$( this ).val( '' );

								if ( $( this ).hasClass( 'slide-sort' ) ) {
									$( this ).val( slideCount1 );
								}
							}
						);

						contentNewTitle = $( this ).prev().data( 'new-content-title' );

						$( newSlide ).find( '.screenshot' ).removeAttr( 'style' );
						$( newSlide ).find( '.screenshot' ).addClass( 'hide' );
						$( newSlide ).find( '.screenshot a' ).attr( 'href', '' );
						$( newSlide ).find( '.remove-image' ).addClass( 'hide' );
						$( newSlide ).find( '.redux-slides-image' ).attr( 'src', '' ).removeAttr( 'id' );
						$( newSlide ).find( 'h3' ).text( '' ).append( '<span class="redux-slides-header">' + contentNewTitle + '</span><span class="ui-accordion-header-icon ui-icon ui-icon-plus"></span>' );
						$( this ).prev().append( newSlide );
					}
				);

				el.find( '.slide-title' ).on(
					'keyup',
					function( event ) {
						var newTitle = event.target.value;
						$( this ).parents().eq( 3 ).find( '.redux-slides-header' ).text( newTitle );
					}
				);

				el.find( '.redux-slides-accordion' ).accordion(
					{
						header: '> div > fieldset > h3',
						collapsible: true,
						active: false,
						heightStyle: 'content',
						icons: {
							'header': 'ui-icon-plus',
							'activeHeader': 'ui-icon-minus'
						}
					}
				).sortable(
					{
						axis: 'y',
						handle: 'h3',
						connectWith: '.redux-slides-accordion',
						start: function( e, ui ) {
							e = null;
							ui.placeholder.height( ui.item.height() );
							ui.placeholder.width( ui.item.width() );
						},
						placeholder: 'ui-state-highlight',
						stop: function( event, ui ) {
							var inputs;

							event = null;

							// IE doesn't register the blur when sorting
							// so trigger focusout handlers to remove .ui-state-focus.
							ui.item.children( 'h3' ).triggerHandler( 'focusout' );
							inputs = $( 'input.slide-sort' );
							inputs.each(
								function( idx ) {
									$( this ).val( idx );
								}
							);
						}
					}
				);
			}
		);
	};
})( jQuery );
