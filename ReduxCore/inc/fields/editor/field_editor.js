/**
 * Redux Editor on change callback
 * Dependencies        : jquery
 * Feature added by    : Dovy Paukstys
 * Date                : 07 June 2014
 */

/*
SHOULD WORK WITHIN INIT, NOT HERE
*/
setTimeout(function () {
    for (var i = 0; i < tinymce.editors.length; i++) {
        tinymce.editors[i].on('change', function(e) {
            var el = jQuery( e.target.contentAreaContainer );
            if ( el.parents('.redux-container-editor:first' ).length !== 0 ) {
                redux_change( el );
            }
        });
    }
}, 1000);

/* global redux_change, wp, tinymce */
(function( $ ) {
    "use strict";

    $.reduxEditor = $.reduxEditor || {};

    $( document ).ready(function() {
        alert('here');
        $.reduxEditor.init();
    });

    $.reduxEditor.init = function( selector ) {
        setTimeout(function () {
            for (var i = 0; i < tinymce.editors.length; i++) {
                tinymce.editors[i].on('change', function(e) {
                    var el = jQuery( e.target.contentAreaContainer );
                    if ( el.parents('.redux-container-editor:first' ).length !== 0 ) {
                        redux_change( el );
                    }
                });
            }
        }, 1000);
    }
});
