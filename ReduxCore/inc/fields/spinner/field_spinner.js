/*global redux_change, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.spinner = redux.field_objects.spinner || {};

    $( document ).ready(
        function() {
            //redux.field_objects.spinner.init();
        }
    );

    redux.field_objects.spinner.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( '.redux-container-spinner' );
        }

        $( selector ).each(
            function() {
                var el = $( this );
                var parent = el;
                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( '.redux-field-container:first' );
                }
                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' );
                } else {
                    return;
                }
                el.find( '.redux_spinner' ).each(
                    function() {
                        //slider init
                        var spinner = redux.spinner[$( this ).attr( 'rel' )];

                        el.find( "#" + spinner.id ).spinner(
                            {
                                value: parseInt( spinner.val, null ),
                                min: parseInt( spinner.min, null ),
                                max: parseInt( spinner.max, null ),
                                step: parseInt( spinner.step, null ),
                                range: "min",

                                slide: function( event, ui ) {
                                    var input = $( "#" + spinner.id );
                                    input.val( ui.value );
                                    redux_change( input );
                                }
                            }
                        );

                        // Limit input for negative
                        var neg = false;
                        if ( parseInt( spinner.min, null ) < 0 ) {
                            neg = true;
                        }

                        el.find( "#" + spinner.id ).numeric(
                            {
                                allowMinus: neg,
                                min: spinner.min,
                                max: spinner.max
                            }
                        );

                    }
                );

                // Update the slider from the input and vice versa
                el.find( ".spinner-input" ).keyup(
                    function() {
                        $( this ).addClass( 'spinnerInputChange' );
                    }
                );

                el.find( ".spinner-input" ).focus(
                    function() {
                        redux.field_objects.spinner.clean(
                            $( this ).val(), $( this ), redux.spinner[$( this ).attr( 'id' )]
                        );
                    }
                );

                el.find( '.spinner-input' ).typeWatch(
                    {
                        callback: function( value ) {
                            redux.field_objects.spinner.clean(
                                value, $( this ), redux.spinner[$( this ).attr( 'id' )]
                            );
                        },

                        wait: 500,
                        highlight: false,
                        captureLength: 1
                    }
                );
            }
        );
    };

    redux.field_objects.spinner.clean = function( value, selector, spinner ) {
        if ( !selector.hasClass( 'spinnerInputChange' ) ) {
            return;
        }
        selector.removeClass( 'spinnerInputChange' );

        if ( value === "" || value === null ) {
            value = spinner.min;
        } else if ( value >= parseInt( spinner.max ) ) {
            value = spinner.max;
        } else if ( value <= parseInt( spinner.min ) ) {
            value = spinner.min;
        } else {
            value = Math.round( value / spinner.step ) * spinner.step;
        }

        $( "#" + spinner.id ).val( value );
    };

})( jQuery );