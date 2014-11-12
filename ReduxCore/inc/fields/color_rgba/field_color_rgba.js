/*global jQuery, document, redux_change */

(function( $ ) {
    'use strict';

    $.reduxColorRGBA = $.reduxColorRGBA || {};

    $( document ).ready(
        function() {
            $.reduxColorRGBA.color_rgba();
        }
    );

    $.reduxColorRGBA.color_rgba = function() {

        $( '.redux-color_rgba-init' ).minicolors(
            {
                animationSpeed: 50,
                animationEasing: 'swing',
                inline: false,
                letterCase: 'lowercase',
                position: 'bottom left',
                theme: 'default',
                opacity: true,
                //theme: 'bootstrap',
                change: function( hex, opacity ) {
                    redux_change( $( this ) );

                    $( '#' + $( this ).data( 'id' ) + '-transparency' ).removeAttr( 'checked' );
                    $( '#' + $( this ).data( 'id' ) + '-alpha' ).val( opacity );
                }
            }
        );

        $( '.redux-color_rgba' ).on(
            'focus', function() {
                $( this ).data( 'oldcolor', $( this ).val() );
            }
        );

        $( '.redux-color_rgba' ).on(
            'keyup', function() {
                var value = $( this ).val();
                var color = $.reduxColorRGBA.validate( this );
                var id = '#' + $( this ).attr( 'id' );

                if ( value === "transparent" ) {
                    $( '#' + $( this ).data( 'id' ) ).parent().parent().find( '.minicolors-swatch-color' ).attr(
                        'style', ''
                    );
                    $( id + '-transparency' ).attr( 'checked', 'checked' );
                } else {
                    $( id + '-transparency' ).removeAttr( 'checked' );
                    if ( color && color !== $( this ).val() ) {
                        $( this ).val( color );
                    }
                }
            }
        );

        // Replace and validate field on blur
        $( '.redux-color_rgba' ).on(
            'blur', function() {
                var value = $( this ).val();
                var id = '#' + $( this ).attr( 'id' );

                if ( value === "transparent" ) {
                    $( '#' + $( this ).data( 'id' ) ).parent().parent().find( '.minicolors-swatch-color' ).attr(
                        'style', ''
                    );
                    $( id + '-transparency' ).attr( 'checked', 'checked' );
                } else {
                    if ( $.reduxColorRGBA.validate( this ) === value ) {
                        if ( value.indexOf( "#" ) !== 0 ) {
                            $( this ).val( $( this ).data( 'oldcolor' ) );
                        }
                    }
                    $( id + '-transparency' ).removeAttr( 'checked' );
                }
            }
        );

        // Store the old valid color on keydown
        $( '.redux-color_rgba' ).on(
            'keydown', function() {
                $( this ).data( 'oldkeypress', $( this ).val() );
            }
        );

        $( '.color_rgba-transparency' ).on(
            'click', function() {
                // Getting the specific input based from field ID
                var pfs = $( this ).parent().parent().data( 'id' );
                var op = $( this ).parent().parent().find( '.minicolors-swatch-color' ).css( 'opacity' ).substring(
                    0, 4
                );

                if ( $( this ).is( ":checked" ) ) {

                    //Set data-opacity attribute to 0.00 when transparent checkbox is check
                    $( '#' + $( this ).data( 'id' ) ).attr( 'data-opacity', '0.00' );

                    //Set hidded input value alpha opacity to 0.00 when transparent checkbox is check
                    $( '#' + pfs + '-alpha' ).val( '0.00' );

                    //Hide .minicolors-swatch-color SPAN when its check
                    $( '#' + $( this ).data( 'id' ) ).parent().parent().find( '.minicolors-swatch-color' ).css(
                        'display', 'none'
                    );
                } else {

                    //might need to restore data-opacity attribute and hidden input alpha value when uncheck
                    $( '#' + $( this ).data( 'id' ) ).attr( 'data-opacity', op );
                    $( '#' + pfs + '-alpha' ).val( op );

                    //Unhide .minicolors-swatch-color SPAN when its check
                    $( '#' + $( this ).data( 'id' ) ).parent().parent().find( '.minicolors-swatch-color' ).css(
                        'display', ''
                    );
                }
            }
        );

        //Unhide .minicolors-swatch-color SPAN when its check on redux-color_rgba input focus
        $( '.redux-color_rgba' ).on(
            'focus', function() {

                var op = $( this ).parent().find( '.minicolors-swatch-color' ).css( 'opacity' ).substring( 0, 4 );

                // re-store data-opacity value of the input field
                $( this ).attr( 'data-opacity', op );

                // re-store alpha hidden input value (not really nescessary)
                $( '#' + $( this ).parent().parent().data( 'id' ) + '-alpha' ).val( op );

                //unhide .mini-swatch-color
                $( this ).parent().find( '.minicolors-swatch-color' ).css( 'display', '' );
            }
        );
    };

    $.reduxColorRGBA.validate = function( field ) {
        var value = jQuery( field ).val();

        return value;
    };
})( jQuery );