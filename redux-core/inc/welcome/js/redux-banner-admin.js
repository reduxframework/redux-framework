/* global console:true, ajaxurl */

(function( $ ) {
    $.redux_banner = $.redux_banner || {};
    $( document ).ready( function() {
    	var post_data = {
		    'action': 'redux_activation',
		    'nonce': $( '#redux-connect-message' ).data( 'nonce' )
	    };
		$( '.redux-connection-banner-action' ).on( 'click', function ( e ) {
			$( '#redux-connect-message' ).hide();
			e.preventDefault();
			post_data['activate'] = $(this).data( 'activate' );
			$.post( $( this ).data('url'), post_data );
		});
		jQuery('.redux-insights-data-we-collect').on('click', function( e ) {
			e.preventDefault();
			jQuery( this ).parents('.updated').find('p.description').slideToggle('fast');
		});
    });
})( jQuery );
