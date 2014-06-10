/*
 Field Button Set (button_set)
 */

/*global jQuery, document, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.button_set = redux.field_objects.button_set || {};

    $( document ).ready(
        function() {
            //redux.field_objects.button_set.init();
        }
    );

    redux.field_objects.button_set.init = function( selector ) {
        if ( !selector ) {
            selector = $( document ).find( '.redux-container-button_set' );
        }

        $( selector ).each(
            function() {
                var el = $( this );
                el.find( '.buttonset' ).each(
                    function() {
                        if ( $( this ).is( ':checkbox' ) ) {
                            $( this ).find( '.buttonset-item' ).button();
                        }

                        $( this ).buttonset();
                    }
                );
            }
        );


    };
})( jQuery );