/*
 Field Border (border)
 */

(function( $ ) {
    "use strict";

    $.reduxBorder = $.reduxBorder || {};

    $( document ).ready(
        function() {
            $.reduxBorder.init();
        }
    );

    $.reduxBorder.init = function() {
        $( ".redux-border-top, .redux-border-right, .redux-border-bottom, .redux-border-left, .redux-border-all" ).numeric(
            {
                allowMinus: false,
            }
        );

        var default_params = {
            triggerChange: true,
            allowClear: true
        };


        var select2_handle = $( '.redux-container-border' ).find( '.select2_params' );

        if ( select2_handle.size() > 0 ) {
            var select2_params = select2_handle.val();

            select2_params = JSON.parse( select2_params );
            default_params = $.extend( {}, default_params, select2_params );
        }

        $( ".redux-border-style" ).select2( default_params );

        $( '.redux-border-input' ).on(
            'change', function() {
                var units = $( this ).parents( '.redux-field:first' ).find( '.field-units' ).val();
                if ( $( this ).parents( '.redux-field:first' ).find( '.redux-border-units' ).length !== 0 ) {
                    units = $( this ).parents( '.redux-field:first' ).find( '.redux-border-units option:selected' ).val();
                }
                var value = $( this ).val();
                if ( typeof units !== 'undefined' && value ) {
                    value += units;
                }
                if ( $( this ).hasClass( 'redux-border-all' ) ) {
                    $( this ).parents( '.redux-field:first' ).find( '.redux-border-value' ).each(
                        function() {
                            $( this ).val( value );
                        }
                    );
                } else {
                    $( '#' + $( this ).attr( 'rel' ) ).val( value );
                }
            }
        );
        $( '.redux-border-units' ).on(
            'change', function() {
                $( this ).parents( '.redux-field:first' ).find( '.redux-border-input' ).change();
            }
        );
    };
})( jQuery );