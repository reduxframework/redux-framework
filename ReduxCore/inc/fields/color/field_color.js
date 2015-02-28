/*
 Field Color (color)
 */

/*global jQuery, document, redux_change, redux*/

(function( $ ) {
    'use strict';

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.color = redux.field_objects.color || {};

    redux.field_objects.color.hexToRGBA = function( hex, alpha ) {
        var result;
        
        if (hex === null) {
            result = '';
        } else {
            hex = hex.replace('#', '');
            var r = parseInt(hex.substring(0, 2), 16);
            var g = parseInt(hex.substring(2, 4), 16);
            var b = parseInt(hex.substring(4, 6), 16);

            result = 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
        }
        
        return result;        
    };

    $( document ).ready(
        function() {

        }
    );

    redux.field_objects.color.init = function( selector ) {
        
        if ( !selector ) {
            selector = $( document ).find( ".redux-group-tab:visible" ).find( '.redux-container-color:visible' );
        }

        $( selector ).each(
            function() {

                var el = $( this );
                var parent = el;
                
                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( '.redux-field-container:first' );
                }
                if ( parent.is( ":hidden" ) ) { // Skip hidden fields
                    return;
                }
                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' );
                } else {
                    return;
                }
                
                el.find( '.redux-color-init' ).wpColorPicker({
                    change: function( u ) {
                        redux_change( $( this ) );
                        el.find( '#' + u.target.getAttribute( 'data-id' ) + '-transparency' ).removeAttr( 'checked' );

                        var linked = $( this ).data( 'linked' );
                        if( linked !== undefined && linked !== null ) {
                            $.each(linked, function( index, value ) {
                                if( value.color !== undefined && value.color !== null ) {
                                    var field = $('#' + redux.args.opt_name + '-' + index);
                                    var color = value.color === true ? ui.color.toString() : value.color;

                                    switch( field.data('type') ) {
                                        case 'color':
                                            field.find( '.redux-color-init' ).wpColorPicker('color', color);
                                            break;
                                        case 'color_rgba':
                                            var alpha = value.alpha === true ? $('#' + index + '-alpha').val() : value.alpha;
                                            var rgba = color != 'transparent' ? redux.field_objects.color.hexToRGBA(color, alpha) : 'transparent';

                                            field.find('.redux-color-rgba').spectrum('set', rgba);
                                            break;
                                        default:
                                            break;
                                    }
                                }
                            });
                        }
                    },
                    clear: function() {
                        redux_change( $( this ).parent().find( '.redux-color-init' ) );
                    }
                });

                el.find( '.redux-color' ).on(
                    'focus', function() {
                        $( this ).data( 'oldcolor', $( this ).val() );
                    }
                );

                el.find( '.redux-color' ).on(
                    'keyup', function() {
                        var value = $( this ).val();
                        var color = colorValidate( this );
                        var id = '#' + $( this ).attr( 'id' );

                        if ( value === "transparent" ) {
                            $( this ).parent().parent().find( '.wp-color-result' ).css(
                                'background-color', 'transparent'
                            );
                    
                            el.find( id + '-transparency' ).attr( 'checked', 'checked' );
                        } else {
                            el.find( id + '-transparency' ).removeAttr( 'checked' );

                            if ( color && color !== $( this ).val() ) {
                                $( this ).val( color );
                            }
                        }
                    }
                );

                // Replace and validate field on blur
                el.find( '.redux-color' ).on(
                    'blur', function() {
                        var value = $( this ).val();
                        var id = '#' + $( this ).attr( 'id' );

                        if ( value === "transparent" ) {
                            $( this ).parent().parent().find( '.wp-color-result' ).css(
                                'background-color', 'transparent'
                            );
                    
                            el.find( id + '-transparency' ).attr( 'checked', 'checked' );
                        } else {
                            if ( colorValidate( this ) === value ) {
                                if ( value.indexOf( "#" ) !== 0 ) {
                                    $( this ).val( $( this ).data( 'oldcolor' ) );
                                }
                            }

                            el.find( id + '-transparency' ).removeAttr( 'checked' );
                        }
                    }
                );

                // Store the old valid color on keydown
                el.find( '.redux-color' ).on(
                    'keydown', function() {
                        $( this ).data( 'oldkeypress', $( this ).val() );
                    }
                );

                // When transparency checkbox is clicked
                el.find( '.color-transparency' ).on(
                    'click', function() {
                        if ( $( this ).is( ":checked" ) ) {
                            
                            el.find( '.redux-saved-color' ).val( $( '#' + $( this ).data( 'id' ) ).val() );
                            el.find( '#' + $( this ).data( 'id' ) ).val( 'transparent' );
                            el.find( '#' + $( this ).data( 'id' ) ).parent().parent().find( '.wp-color-result' ).css(
                                'background-color', 'transparent'
                            );
                        } else {
                            if ( el.find( '#' + $( this ).data( 'id' ) ).val() === 'transparent' ) {
                                var prevColor = $( '.redux-saved-color' ).val();

                                if ( prevColor === '' ) {
                                    prevColor = $( '#' + $( this ).data( 'id' ) ).data( 'default-color' );
                                }

                                el.find( '#' + $( this ).data( 'id' ) ).parent().parent().find( '.wp-color-result' ).css(
                                    'background-color', prevColor
                                );
                        
                                el.find( '#' + $( this ).data( 'id' ) ).val( prevColor );
                            }
                        }
                    }
                );
            }
        );
    };
})( jQuery );