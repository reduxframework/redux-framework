/*global jQuery, redux_change, redux*/

(function ( $ ) {
	'use strict';

	let scrollDir = '';

	redux.field_objects          = redux.field_objects || {};
	redux.field_objects.sortable = redux.field_objects.sortable || {};

	redux.field_objects.sortable.init = function ( selector ) {
		selector = $.redux.getSelector( selector, 'sortable' );

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

				el.find( '.redux-sortable' ).sortable(
					{
						handle: '.drag',
						placeholder: 'placeholder',
						opacity: 0.7,
						scroll: false,
						out: function ( event, ui ) {
							event = null;

							if ( ! ui.helper ) {
								return;
							}

							if ( ui.offset.top > 0 ) {
								scrollDir = 'down';
							} else {
								scrollDir = 'up';
							}

							redux.field_objects.sortable.scrolling( $( this ).parents( '.redux-field-container:first' ) );
						},
						over: function () {
							scrollDir = '';
						},
						deactivate: function () {
							scrollDir = '';
						},
						update: function () {
							redux_change( $( this ) );
						}
					}
				);

				el.find( '.redux-sortable i.visibility' ).on(
					'click',
					function () {
						let val;
						let hiddenInput;

						const li = $( this ).parents( 'li' );

						if ( li.hasClass( 'invisible' ) ) {
							li.removeClass( 'invisible' );
							val = 1;
						} else {
							li.addClass( 'invisible' );
							val = '';
						}

						hiddenInput = li.find( 'input[type="hidden"]' );

						hiddenInput.val( val );
					}
				);
			}
		);
	};

	redux.field_objects.sortable.scrolling = function ( selector ) {
		let $scrollable;

		if ( undefined === selector ) {
			return;
		}

		$scrollable = selector.find( '.redux-sorter' );

		if ( 'up' === scrollDir ) {
			$scrollable.scrollTop( $scrollable.scrollTop() - 20 );
			setTimeout( redux.field_objects.sortable.scrolling, 50 );
		} else if ( 'down' === scrollDir ) {
			$scrollable.scrollTop( $scrollable.scrollTop() + 20 );
			setTimeout( redux.field_objects.sortable.scrolling, 50 );
		}
	};
})( jQuery );
