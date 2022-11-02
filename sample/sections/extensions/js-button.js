/* jshint unused:false */

function redux_add_date() {
	(function( $ ) {

		var date = new Date();
		var text = $( '#opt-blank-text' );

		text.val( date.toString() );
	})( jQuery );
}

function redux_show_alert() {
	alert( 'You clicked the Alert button!' );
}
