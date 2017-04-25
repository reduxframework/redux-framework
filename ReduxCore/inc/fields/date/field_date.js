/*global jQuery, document, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.date = redux.field_objects.date || {};

    $( document ).ready(
        function() {
            //redux.field_objects.date.init();
        }
    );

    redux.field_objects.date.init = function( selector ) {
        if ( !selector ) {
            selector = $( document ).find( '.redux-container-date:visible' );
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

                el.find( '.redux-datepicker' ).each( function() {
                    $( this ).datepicker({
                        "dateFormat":"mm/dd/yy",
                        beforeShow: function(input, instance){
                            var el = $('#ui-datepicker-div');
                            var popover = instance.dpDiv;
                            //$('.redux-container:first').append(el);
                            $(this).parent().append(el);
                            $('#ui-datepicker-div').hide();
                            setTimeout(function() {
                                popover.position({
                                    my: 'left top',
                                    at: 'left bottom',
                                    collision: 'none',
                                    of: input
                                });
                            }, 1);
                        } 
                    });
                });
            }
        );


    };
})( jQuery );