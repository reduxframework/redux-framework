/* global redux */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$( document ).ready(
		function() {
			if ( 'undefined' !== typeof redux ) {
				$.redux.flyoutSubmenus();
			}
		}
	);

	$.redux.flyoutSubmenus = function() {

		// Close flyouts when a new menu item is activated.
		$( '.redux-group-tab-link-li a' ).on(
			'click',
			function() {
				if ( true === redux.optName.args.flyout_submenus ) {
					$( '.redux-group-tab-link-li' ).removeClass( 'redux-section-hover' );
				}
			}
		);

		if ( true === redux.optName.args.flyout_submenus ) {

			// Submenus flyout when a main menu item is hovered.
			$( '.redux-group-tab-link-li.hasSubSections' ).each(
				function() {
					$( this ).on(
						'mouseenter',
						function() {
							if ( ! $( this ).hasClass( 'active' ) && ! $( this ).hasClass( 'activeChild' ) ) {
								$( this ).addClass( 'redux-section-hover' );
							}
						}
					);

					$( this ).on(
						'mouseleave',
						function() {
							$( this ).removeClass( 'redux-section-hover' );
						}
					);
				}
			);
		}
	};
})( jQuery );
