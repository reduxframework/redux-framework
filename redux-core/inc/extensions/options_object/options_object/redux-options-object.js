/*global redux, jsonView */

(function ( $ ) {
	redux.field_objects                = redux.field_objects || {};
	redux.field_objects.options_object = redux.field_objects.options_object || {};

	redux.field_objects.options_object.init = function ( selector ) {
		let parent;

		selector = $.redux.getSelector( selector, 'options_object' );

		parent = selector;

		if ( ! selector.hasClass( 'redux-field-container' ) ) {
			parent = selector.parents( '.redux-field-container:first' );
		}

		if ( parent.hasClass( 'redux-field-init' ) ) {
			parent.removeClass( 'redux-field-init' );
		} else {
			return;
		}

		$( '#consolePrintObject' ).on(
			'click',
			function ( e ) {
				e.preventDefault();
				console.log( JSON.parse( $( '#redux-object-json' ).html() ) );
			}
		);

		if ( 'function' === typeof jsonView ) {
			jsonView( '#redux-object-json', '#redux-object-browser' );
		}
	};
})( jQuery );
