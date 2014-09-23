/*
 Field Layout (layout)
 */

/*global redux_change, wp, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.layout = redux.field_objects.layout || {};

    $( document ).ready(
        function() {
            
        }
    );

    redux.field_objects.layout.init = function( selector ) {
        if ( !selector ) {
            selector = $( document ).find( '.redux-container-layout' );
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
                
                el.find( ".redux-layout-top, .redux-layout-right, .redux-layout-bottom, .redux-layout-left, .redux-layout-all" ).numeric({
                    allowMinus: false
                });

                var default_params = {
                    triggerChange: true,
                    allowClear: true
                };

                var select2_handle = el.find( '.redux-container-layout' ).find( '.select2_params' );

                if ( select2_handle.size() > 0 ) {
                    var select2_params = select2_handle.val();

                    select2_params = JSON.parse( select2_params );
                    default_params = $.extend( {}, default_params, select2_params );
                }

                el.find( ".redux-layout-style" ).select2( default_params );

                el.find( '.redux-layout-input' ).on(
                    'change', function() {
                        var units = $( this ).parents( '.redux-field:first' ).find( '.field-units' ).val();
                        if ( $( this ).parents( '.redux-field:first' ).find( '.redux-layout-units' ).length !== 0 ) {
                            units = $( this ).parents( '.redux-field:first' ).find( '.redux-layout-units option:selected' ).val();
                        }
                        var value = $( this ).val();
                        if ( typeof units !== 'undefined' && value ) {
                            value += units;
                        }
                        if ( $( this ).hasClass( 'redux-layout-all' ) ) {
                            $( this ).parents( '.redux-field:first' ).find( '.redux-layout-value' ).each(
                                function() {
                                    $( this ).val( value );
                                }
                            );
                        } else {
                            $( '#' + $( this ).attr( 'rel' ) ).val( value );
                        }
                    }
                );
        
                el.find( '.redux-layout-units' ).on(
                    'change', function() {
                        $( this ).parents( '.redux-field:first' ).find( '.redux-layout-input' ).change();
                    }
                );

                el.find( '.redux-color-init' ).wpColorPicker({
                    change: function( u ) {
                        redux_change( $( this ) );
                        el.find( '#' + u.target.getAttribute( 'data-id' ) + '-transparency' ).removeAttr( 'checked' );
                    },
                    
                    clear: function() {
                        redux_change( $( this ).parent().find( '.redux-color-init' ) );
                    }
                });

                el.find( '.redux-color' ).on(
                    'keyup', function() {
                        var color = colorValidate( this );

                        if ( color && color !== $( this ).val() ) {
                            $( this ).val( color );
                        }
                    }
                );

                // Replace and validate field on blur
                el.find( '.redux-color' ).on(
                    'blur', function() {
                        var value = $( this ).val();

                        if ( colorValidate( this ) === value ) {
                            if ( value.indexOf( "#" ) !== 0 ) {
                                $( this ).val( $( this ).data( 'oldcolor' ) );
                            }
                        }
                    }
                );

                // Store the old valid color on keydown
                el.find( '.redux-color' ).on(
                    'keydown', function() {
                        $( this ).data( 'oldkeypress', $( this ).val() );
                    }
                );
            }
        );
    };
})( jQuery );