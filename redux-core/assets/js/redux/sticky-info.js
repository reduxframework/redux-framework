(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.stickyInfo = function() {
		var sticky      = $( '#redux-sticky' );
		var infoBar     = $( '#info_bar' );
		var reduxFooter = $( '#redux-footer' );
		var stickyWidth = $( '.redux-main' ).innerWidth() - 20;
		var $width      = sticky.offset().left;

		$( '.redux-save-warn' ).css( 'left', $width + 'px' );

		if ( ! infoBar.isOnScreen() && ! $( '#redux-footer-sticky' ).isOnScreen() ) {
			reduxFooter.css(
				{ position: 'fixed', bottom: '0', width: stickyWidth, right: 21 }
			);

			reduxFooter.addClass( 'sticky-footer-fixed' );
			$( '#redux-sticky-padder' ).show();
		} else {
			reduxFooter.css(
				{ background: '#eee', position: 'inherit', bottom: 'inherit', width: 'inherit' }
			);

			$( '#redux-sticky-padder' ).hide();
			reduxFooter.removeClass( 'sticky-footer-fixed' );
		}
		if ( ! infoBar.isOnScreen() ) {
			sticky.addClass( 'sticky-save-warn' );
		} else {
			sticky.removeClass( 'sticky-save-warn' );
		}
	};
})( jQuery );
