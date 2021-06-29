/* global redux_check_intro */

/**
 * Description
 */

( function( $ ) {
	'use strict';

	$(function() {
			$( '#theme-check > h2' ).html( $( '#theme-check > h2' ).html() + ' with Redux Theme-Check' );

			if ( 'undefined' !== typeof redux_check_intro ) {
				$( '#theme-check .theme-check' ).append( redux_check_intro.text );
			}

			$( '#theme-check form' ).append(
				'&nbsp;&nbsp;<input name="redux_wporg" type="checkbox">  Extra WP.org Requirements.'
			);
		}
	);
}( jQuery ) );
