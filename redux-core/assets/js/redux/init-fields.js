/* global redux */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.initFields = function() {
		$( '.redux-group-tab:visible' ).find( '.redux-field-init:visible' ).each(
			function() {
				var tr;
				var th;

				var type = $( this ).attr( 'data-type' );

				if ( 'undefined' !== typeof redux.field_objects && redux.field_objects[type] && redux.field_objects[type] ) {
					redux.field_objects[type].init();
				}

				if ( ! redux.customizer && $( this ).hasClass( 'redux_remove_th' ) ) {
					tr = $( this ).parents( 'tr:first' );
					th = tr.find( 'th:first' );

					if ( th.html() && th.html().length > 0 ) {
						$( this ).prepend( th.html() );
						$( this ).find( '.redux_field_th' ).css( 'padding', '0 0 10px 0' );
					}

					$( this ).parent().attr( 'colspan', '2' );

					th.remove();
				}
			}
		);
	};
})( jQuery );
