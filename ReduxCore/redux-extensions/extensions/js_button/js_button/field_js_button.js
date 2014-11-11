/**
 * JS Button library
 *
 * @author Kevin Provance (kprovance)
 */

(function($){
    'use strict';

    redux.field_objects                     = redux.field_objects || {};
    redux.field_objects.js_button           = redux.field_objects.js_button || {};
    redux.field_objects.js_button.mainID    = '';
    
/*******************************************************************************
 * init Function
 * 
 * Runs when library is loaded.
 ******************************************************************************/
    redux.field_objects.js_button.init = function( selector ) {
        
        // If no selector is passed, grab one from the HTML
        if ( !selector ) {
            selector = $( document ).find( '.redux-container-js_button' );
        }

        // Enum instances of our object
        $( selector ).each(
            function() {
                var el      = $( this );
                var parent  = el;

                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( '.redux-field-container:first' );
                }

                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' );
                } else {
                    return;
                }

                // Do module level init
                redux.field_objects.js_button.modInit(el);
                
                // Get the button handle
                var button = $(el).find('input#' + redux.field_objects.js_button.mainID);
                
                // Get the button's function name
                var funcName = $(el).find('div#' + redux.field_objects.js_button.mainID).data('function');
                

                // React to button click
                $(button).on("click", function(e) {

                    // Not really needed, but just in case.
                    e.preventDefault();

                    if (funcName !== '') {
                        // Ensure custom function exists
                        if (typeof(window[funcName]) === "function") {

                            // Add it to the window object and execute
                            window[funcName]();
                        } else {

                            // Let the dev know he fucked up someplace.
                            throw("JS Button Error.  Function " + funcName + " does not exist.");
                        }
                    }
                });
            }
        );
    };

/*******************************************************************************
 * modInit Function
 * 
 * Module level init
 ******************************************************************************/
    redux.field_objects.js_button.modInit = function(el) {
        
        // ID of the fieldset
        redux.field_objects.js_button.mainID  = el.attr('data-id');
        
        // dev_mode status
        var dev_mode = Boolean(el.find('.redux-js-button-container').data('dev-mode'));

        // Add ext version info to footer, dev mode only.
        if (dev_mode === true) {
            var ver         = el.find('.redux-js-button-container').data('version');
            var dev_html    = $('div.redux-timer').html();
            var pos         = dev_html.indexOf('JS Button');
            
            if (pos === -1) {
                $('div.redux-timer').html(dev_html + '<br/>JS Button extension v.' + ver);
            }
        }
    };
})(jQuery);