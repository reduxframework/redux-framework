/* global console:true, ajaxurl */

(function( $ ) {
	$.redux_banner = $.redux_banner || {};

	$( document ).ready(
		function() {
			var post_data = {
				'action': 'redux_activation',
				'nonce': $( '#redux-connect-message' ).data( 'nonce' )
			};

			$( '.redux-connection-banner-action' ).on(
				'click',
				function( e ) {
					var status = $( '.redux-banner-tos-blurb' );

					$( this ).addClass( 'disabled' );

					status.html( 'Installing Extendify plugin...' );

					e.preventDefault();

					post_data['activate'] = $( this ).data( 'activate' );

					$.post(
						$( this ).data( 'url' ),
						post_data,
						function( response ) {
							var point = response.indexOf( '{"type":' )

							if ( point > 0 ) {
								response = response.substring( point );
							}

							response = JSON.parse( response );

							console.log( response.msg );
							status.html( response.msg );

							if ( 'close' === response.type ) {
								$( '#redux-connect-message' ).slideUp();
							}
						}
					);
				}
			);
		}
	);
})( jQuery );
