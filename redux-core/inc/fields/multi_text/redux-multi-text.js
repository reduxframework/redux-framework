/*global redux_change, redux*/

(function( $ ) {
	'use strict';

	redux.field_objects            = redux.field_objects || {};
	redux.field_objects.multi_text = redux.field_objects.multi_text || {};

	redux.field_objects.multi_text.remove = function( el ) {
		el.find( '.redux-multi-text-remove' ).on(
			'click',
			function() {
				var id;
				var lis;
				var add;
				var name;

				redux_change( $( this ) );

				$( this ).prev( 'input[type="text"]' ).val( '' );

				id = $( this ).attr( 'data-id' );

				$( this ).parent().slideUp(
					'medium',
					function() {
						$( this ).remove();

						lis = el.find( '#' + id + ' li' ).length;
						if ( 1 === lis ) {
							add  = el.find( '.redux-multi-text-add' );
							name = add.attr( 'data-name' );

							el.find( '#' + id + ' li:last-child input[type="text"]' ).attr( 'name', name );
						}
					}
				);
			}
		);
	};

	redux.field_objects.multi_text.init = function( selector ) {
		selector = $.redux.getSelector( selector, 'multi_text' );

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

				redux.field_objects.multi_text.remove( el );

				el.find( '.redux-multi-text-add' ).on(
					'click',
					function() {
						var i;
						var lis;
						var css;
						var input;
						var new_input;

						var number = parseInt( $( this ).attr( 'data-add_number' ) );
						var id     = $( this ).attr( 'data-id' );
						var name   = $( this ).attr( 'data-name' ) + '[]';

						for ( i = 0; i < number; i += 1 ) {
							new_input = $( '#' + id + ' li:last-child' ).clone();

							el.find( '#' + id ).append( new_input );
							el.find( '#' + id + ' li:last-child' ).removeAttr( 'style' );
							el.find( '#' + id + ' li:last-child input[type="text"]' ).val( '' );
							el.find( '#' + id + ' li:last-child input[type="text"]' ).attr( 'name', name );
						}

						lis = el.find( '#' + id + ' li' ).length;

						if ( lis > 1 ) {
							el.find( '#' + id + ' li' ).each(
								function() {
									css = $( this ).css( 'display' );
									if ( 'none' === css ) {
										input = $( this ).find( 'input[type="text"]' );
										input.attr( 'name', '' );
									}
								}
							);
						}

						redux.field_objects.multi_text.remove( el );
					}
				);
			}
		);
	};
})( jQuery );
