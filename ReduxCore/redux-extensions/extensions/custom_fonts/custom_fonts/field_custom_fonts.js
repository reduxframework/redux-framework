/* global redux_change, wp */
// Add a file via the wp.media function
function redux_add_font( event, selector ) {
    event.preventDefault();
    var frame;
    var jQueryel = jQuery( this );
    // If the media frame already exists, reopen it.
    if ( frame ) {
        frame.open();
        return;
    }
    // Create the media frame.
    frame = wp.media( {
        multiple: true,
        library: {
            type: [ 'application', 'font' ] //Only allow zip files
        },
        // Set the title of the modal.
        title: jQueryel.data( 'choose' ),
        // Customize the submit button.
        button: {
            // Set the text of the button.
            text: jQueryel.data( 'update' )
            // Tell the button not to close the modal, since we're
            // going to refresh the page when the image is selected.
        }
    } );
    frame.on( 'click', function() {
        //console.log( 'Hello' );
    } );
    // When an image is selected, run a callback.
    frame.on( 'select', function() {
        // Grab the selected attachment.
        var attachment = frame.state().get( 'selection' ).first();
        
        frame.close();
        if ( attachment.attributes.type !== 'application' && attachment.attributes.type !== 'font' ) {
            return;
        }

        var nonce = jQuery( selector ).find( '.media_add_font' ).attr( "data-nonce" );
        var data = {
            action: "redux_custom_fonts",
            nonce: nonce,
            attachment_id: attachment.id,
            title: attachment.attributes.title,
            mime: attachment.attributes.mime,
        };
        if (data.mime == "application/zip ") {
            var status = "Unzipping archive and generating any missing font files.";
        } else {
            var status = "Converting font file.";
        }
        jQuery.blockUI({ message: '<h1>'+redux.args.please_wait+'...</h1>' }); 
        
        jQuery.post( ajaxurl, data, function( response ) {
            console.log(response);
            response = jQuery.parseJSON( response );
            if (response.type == "success") {
                window.onbeforeunload = "";
                location.reload();
            } else if (response.type == "error") {
                alert('There was an error converting your font: '+response.msg);
            }
            //console.log('here');
        } );
    } );
    // Finally, open the modal.
    frame.open();
}
// Function to remove the image on click. Still requires a save
function redux_remove_font( selector ) {
    // This shouldn't have been run...
    if ( !selector.find( '.remove-image' ).addClass( 'hide' ) ) {
        return;
    }
}
( function( $ ) {
    "use strict";
    $.redux = $.redux || {};
    $( document ).ready( function() {
        $.redux.custom_fonts();
    } );
    $.redux.custom_fonts = function() {
        // Remove the image button
        $( '.remove-font' ).unbind( 'click' ).on( 'click', function() {
            redux_remove_font( $( this ).parents( 'fieldset.redux-field:first' ) );
        } );
        // Upload media button
        $( '.media_add_font' ).unbind().on( 'click', function( event ) {
            redux_add_font( event, $( this ).parents( 'fieldset.redux-field:first' ) );
        } );

        $( '.fontDelete' ).on( 'click', function(e) {
            e.preventDefault();
            var parent = jQuery(this).parents('td:first');
            parent.find('.spinner').show();
            var data = jQuery(this).data();
            data.action = "redux_custom_fonts";
            data.nonce = jQuery( this ).parents( '.redux-container-custom_font:first' ).find( '.media_add_font' ).attr( "data-nonce" );
            jQuery.post( ajaxurl, data, function( response ) {
                response = jQuery.parseJSON( response );
                if (response.type && response.type == "success") {
                    var rowCount = parent.parents('table:first tr').length;
                    console.log(rowCount);
                    if (rowCount == 1) {
                        parent.parents('table:first').fadeOut().remove();
                    } else {
                        parent.parents('tr:first').fadeOut().remove();    
                    }
                } else {
                    alert('There was an error deleting your font: '+response.msg);
                    parent.find('.spinner').hide();
                }
            } );       
            return false;
        });



    };
} )( jQuery );
