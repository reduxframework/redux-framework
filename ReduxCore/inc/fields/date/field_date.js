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
//                        var someArr = []
//                        someArr = i;
//                        console.log(someArr);
                
//                var str = JSON.parse('{"redux_demo[opt-multi-check]":{"redux_demo[opt-multi-check][1]":"1","redux_demo[opt-multi-check][2]":"","redux_demo[opt-multi-check][3]":""}}');
//                console.log (str);
//                
//                $.each(str, function(idx, val){
//                    var tmpArr = new Object();
//                    var count = 1;
//                    
//                    $.each(val, function (i, v){
//                        
//                        tmpArr[count] = v;
//                        count++;
//                    });
//
//                    var newArr = {};
//                    newArr[idx] = tmpArr;
//                    var newJSON = JSON.stringify(newArr)
//                    //console.log(newJSON);
//                });
                
                el.find( '.redux-datepicker' ).each( function() {
                    
                    $( this ).datepicker({
                        beforeShow: function(textbox, instance){
                            var el = $('#ui-datepicker-div');
                            //$('#ui-datepicker-div').remove();
                            //$('.redux-main:first').append(el);
                            //instance.dpDiv.css({marginTop: -31 + 'px', marginLeft: -200 + 'px'});
                        } 
                    });
                });
            }
        );


    };
})( jQuery );