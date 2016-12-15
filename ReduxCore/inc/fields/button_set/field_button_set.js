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
            if ( $.fn.button.noConflict !== undefined ) {
                var btn = $.fn.button.noConflict();
                $.fn.btn = btn;
            }
        }
    );

    redux.field_objects.button_set.init = function( selector ) {
        if ( !selector ) {
            selector = $( document ).find( ".redux-group-tab:visible" ).find( '.redux-container-button_set:visible' );
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
                
                el.find( '.buttonset' ).each(
                    function() {
                        if ( $( this ).is( ':checkbox' ) ) {
                            $( this ).find( '.buttonset-item' ).button();
                        }

                        $( this ).buttonset();
                    }
                );
        
                el.find( '.buttonset-item.multi' ).on(
                    'click', function( e ) {
                        var val     = '';
                        var name    = '';
                        
                        var id          = $(this).attr('id');
                        var empty       = $( this ).parent().find( '.buttonset-empty' );
                        var idName      = empty.attr( 'data-name' );
                        var isChecked   = false;
                        
                        $( this ).parent().find('.buttonset-item').each(function(){
                            if ($( this ).is( ':checked' )) {
                                isChecked = true;
                            }
                        });
                        
                        if (isChecked) {
                            empty.attr('name', '');
                        } else {
                            empty.attr('name', idName);
                        }
                        
                        if ( $( this ).is( ':checked' ) ) {
                            val     = $( this ).attr( 'data-val' );
                            name    = idName + '[]';
                            
                        }

                        $( this ).parent().find( '#' + id + '-hidden.buttonset-check' ).val( val );
                        $( this ).parent().find( '#' + id + '-hidden.buttonset-check' ).attr( 'name', name );
                        
                        redux_change( $( this ) );
                    }
                );        
            }
        );
    };
})( jQuery );