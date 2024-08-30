(function ( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.expandOptions = function ( parent ) {
		const trigger = parent.find( '.expand_options' );
		const width   = parent.find( '.redux-sidebar' ).width() - 1;
		const id      = $( '.redux-group-menu .active a' ).data( 'rel' ) + '_section_group';

		if ( trigger.hasClass( 'expanded' ) ) {
			trigger.removeClass( 'expanded' );
			parent.find( '.redux-main' ).removeClass( 'expand' );

			parent.find( '.redux-sidebar' ).stop().animate(
				{ 'margin-left': '0px' },
				500
			);

			parent.find( '.redux-main' ).stop().animate(
				{ 'margin-left': width },
				500,
				function () {
					parent.find( '.redux-main' ).attr( 'style', '' );
				}
			);

			parent.find( '.redux-group-tab' ).each(
				function () {
					if ( $( this ).attr( 'id' ) !== id ) {
						$( this ).fadeOut( 'fast' );
					}
				}
			);

			// Show the only active one.
		} else {
			trigger.addClass( 'expanded' );
			parent.find( '.redux-main' ).addClass( 'expand' );

			parent.find( '.redux-sidebar' ).stop().animate(
				{ 'margin-left': - width - 113 },
				500
			);

			parent.find( '.redux-main' ).stop().animate(
				{ 'margin-left': '-1px' },
				500
			);

			parent.find( '.redux-group-tab' ).fadeIn(
				'medium',
				function () {
					$.redux.initFields();
				}
			);
		}

		return false;
	};
})( jQuery );
