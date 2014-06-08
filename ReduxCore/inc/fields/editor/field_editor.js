/**
 * Redux Editor on change callback
 * Dependencies        : jquery
 * Feature added by    : Dovy Paukstys
 *                     : Kevin Provance (who helped)  :P 
 * Date                : 07 June 2014
 */

/* global redux_change, wp, tinymce */
(function( $ ) {
    "use strict";

    $.reduxEditor = $.reduxEditor || {};
    
    $( document ).ready(
        function() {
            $.reduxEditor.init();
        }
    );

    $.reduxEditor.init = function( selector ) {
        setTimeout(function () {
            for (var i = 0; i < tinymce.editors.length; i++) {
                $.reduxEditor.onChange(i);
            }
        }, 1000);
    };
    
    $.reduxEditor.onChange = function (i) {
        tinymce.editors[i].on('change', function(e) {
            var el = jQuery( e.target.contentAreaContainer );
            if ( el.parents('.redux-container-editor:first' ).length !== 0 ) {
                redux_change( $('.wp-editor-area') );
            }
        });
    };
})( jQuery );
