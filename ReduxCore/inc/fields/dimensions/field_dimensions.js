(function( $ ) {
    "use strict";

    $.reduxDimensions = $.reduxDimensions || {};

    $( document ).ready(
        function() {
            $.reduxDimensions.init();
        }
    );

    $.reduxDimensions.init = function() {
        var default_params = {
            width: 'resolve',
            triggerChange: true,
            allowClear: true
        };

        var select2_handle = $( '.redux-dimensions-container' ).find( '.select2_params' );
        if ( select2_handle.size() > 0 ) {
            var select2_params = select2_handle.val();

            select2_params = JSON.parse( select2_params );
            default_params = $.extend( {}, default_params, select2_params );
        }

        $( ".redux-dimensions-units" ).select2( default_params );

        $( '.redux-dimensions-input' ).on(
            'change', function() {
                var units = $( this ).parents( '.redux-field:first' ).find( '.field-units' ).val();
                if ( $( this ).parents( '.redux-field:first' ).find( '.redux-dimensions-units' ).length !== 0 ) {
                    units = $( this ).parents( '.redux-field:first' ).find( '.redux-dimensions-units option:selected' ).val();
                }
                if ( typeof units !== 'undefined' ) {
                    $( '#' + $( this ).attr( 'rel' ) ).val( $( this ).val() + units );
                } else {
                    $( '#' + $( this ).attr( 'rel' ) ).val( $( this ).val() );
                }
            }
        );

        $( '.redux-dimensions-units' ).on(
            'change', function() {
                $( this ).parents( '.redux-field:first' ).find( '.redux-dimensions-input' ).change();
            }
        );
    };
})( jQuery );