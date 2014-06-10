/*global jQuery, document, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.ace_editor = redux.field_objects.ace_editor || {};

    $( document ).ready(
        function() {
            //redux.field_objects.ace_editor.init();
        }
    );


    redux.field_objects.ace_editor.init = function( selector ) {
        if ( !selector ) {
            selector = $( document ).find( '.redux-container-ace_editor' );
        }

        $( selector ).each(
            function() {
                var el = $( this );
                el.find( '.ace-editor' ).each(
                    function( index, element ) {
                        var area = element;
                        var editor = $( element ).attr( 'data-editor' );

                        var aceeditor = ace.edit( editor );
                        aceeditor.setTheme( "ace/theme/" + jQuery( element ).attr( 'data-theme' ) );
                        aceeditor.getSession().setMode( "ace/mode/" + $( element ).attr( 'data-mode' ) );

                        aceeditor.on(
                            'change', function( e ) {
                                $( '#' + area.id ).val( aceeditor.getSession().getValue() );
                                redux_change( $( element ) );
                            }
                        );
                    }
                );
            }
        );
    };
})( jQuery );