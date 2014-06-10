/*
 Field Color Gradient
 */

/*global jQuery, document, redux_change, redux*/

(function( $ ) {
    'use strict';

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.color_gradient = redux.field_objects.color_gradient || {};

    $( document ).ready(
        function() {
            //        setTimeout(function () {
            //            redux.field_objects.color.init();
            //        }, 1000);
        }
    );

    redux.field_objects.color_gradient.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( '.redux-container-color_gradient' );
        }

        $( selector ).each(
            function() {
                var el = $( this );
                var parent = el;
                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( '.redux-field-container:first' );
                }
                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' )
                } else {
                    return;
                }
                redux.field_objects.color.init(el);
            }
        );
    };
})( jQuery );