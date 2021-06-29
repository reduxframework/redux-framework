(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.stickyInfo = function() {
		var stickyWidth = $( '.redux-main' ).innerWidth() - 20;
		var $width      = $( '#redux-sticky' ).offset().left;

		$( '.redux-save-warn' ).css( 'left', $width + 'px' );

		if ( ! $( '#info_bar' ).isOnScreen() && ! $( '#redux-footer-sticky' ).isOnScreen() ) {
			$( '#redux-footer' ).css(
				{ position: 'fixed', bottom: '0', width: stickyWidth, right: 21 }
			);

			$( '#redux-footer' ).addClass( 'sticky-footer-fixed' );
			$( '#redux-sticky-padder' ).show();
		} else {
			$( '#redux-footer' ).css(
				{ background: '#eee', position: 'inherit', bottom: 'inherit', width: 'inherit' }
			);

			$( '#redux-sticky-padder' ).hide();
			$( '#redux-footer' ).removeClass( 'sticky-footer-fixed' );
		}
		if ( ! $( '#info_bar' ).isOnScreen() ) {
			$( '#redux-sticky' ).addClass( 'sticky-save-warn' );
		} else {
			$( '#redux-sticky' ).removeClass( 'sticky-save-warn' );
		}
	};
})( jQuery );
