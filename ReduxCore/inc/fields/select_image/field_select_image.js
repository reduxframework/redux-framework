(function( $ ) {
    "use strict";

    $.reduxSelectImage = $.reduxSelectImage || {};

    $( document ).ready(
        function() {
            $.reduxSelectImage.init();
        }
    );

    $.reduxSelectImage.init = function() {
        
        var default_params = {
            width: 'resolve',
            triggerChange: true,
            allowClear: true
        };

        var select2_handle = $( '.redux-container-select_image' ).find( '.select2_params' );

        if ( select2_handle.size() > 0 ) {
            var select2_params = select2_handle.val();

            select2_params = JSON.parse( select2_params );
            default_params = $.extend( {}, default_params, select2_params );
        }        
        
        $( 'select.redux-select-images' ).select2( default_params );
        
        $( '.redux-select-images' ).on(
            'change', function() {
                var preview = $( this ).parents( '.redux-field:first' ).find( '.redux-preview-image' );

                if ( $( this ).val() === "" ) {
                    preview.fadeOut(
                        'medium', function() {
                            preview.attr( 'src', '' );
                        }
                    );
                } else {
                    preview.attr( 'src', $( this ).val() );
                    preview.fadeIn().css( 'visibility', 'visible' );
                }
            }
        );
    };
})( jQuery );