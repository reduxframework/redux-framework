/*global jQuery, redux*/

(function ( $ ) {
	'use strict';

	redux.field_objects      = redux.field_objects || {};
	redux.field_objects.date = redux.field_objects.date || {};

	redux.field_objects.date.init = function ( selector ) {
		selector = $.redux.getSelector( selector, 'date' );

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

				el.find( '.redux-datepicker' ).each(
					function () {
						$( this ).datepicker(
							{
								'dateFormat': 'mm/dd/yy', beforeShow: function ( input, instance ) {
									const el      = $( '#ui-datepicker-div' );
									const popover = instance.dpDiv;

									$( this ).parent().append( el );

									el.hide();
									setTimeout(
										function () {
											popover.position(
												{ my: 'left top', at: 'left bottom', collision: 'none', of: input }
											);
										},
										1
									);
								}
							}
						);
					}
				);
			}
		);
	};
})( jQuery );
