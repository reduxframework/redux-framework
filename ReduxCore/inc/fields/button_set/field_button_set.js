/*
 Field Button Set (button_set)
 */

/*global jQuery, document*/

(function( $ ) {
    "use strict";

    $.reduxButtonSet = $.reduxButtonSet || {};

    $( document ).ready(
        function() {
            $.reduxButtonSet.init();
        }
    );

    $.reduxButtonSet.init = function() {
        $( '.buttonset' ).each(
            function() {
                if ( $( this ).is( ':checkbox' ) ) {
                    $( this ).find( '.buttonset-item' ).button();
                }

                $( this ).buttonset();
            }
        );
    };
})( jQuery );