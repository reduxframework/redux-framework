/**
 * Multi Media Selector library
 *
 * @author Kevin Provance (kprovance)
 */

(function($){
    'use strict';

    redux.field_objects                     = redux.field_objects || {};
    redux.field_objects.multi_media         = redux.field_objects.multi_media || {};
    redux.field_objects.multi_media.mainID  = '';
    
    var l10n;

/*******************************************************************************
 * init Function
 * 
 * Runs when library is loaded.
 ******************************************************************************/
    redux.field_objects.multi_media.init = function( selector ) {
        
        // If no selector is passed, grab one from the HTML
        if ( !selector ) {
            selector = $( document ).find( '.redux-container-multi_media' );
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

                // Handle clicking up the upload button
                el.find( '.redux_remove_file_button' ).unbind( 'click' ).on(
                    'click', function(event) {
                        redux.field_objects.multi_media.removeFile(event, $( this ).parents( 'fieldset.redux-field:first' ), $(this) );
                    }
                );

                // Handle clicking of the delete icon
                el.find( '.redux_upload_button' ).unbind().on(
                    'click', function( event ) {
                        redux.field_objects.multi_media.addFile( event, $( this ).parents( 'fieldset.redux-field:first' ), $(this) );
                    }
                );

                // Init module level code
                redux.field_objects.multi_media.modInit(el);
            }
        );
    };

/*******************************************************************************
 * modInit Function
 * 
 * Module level init
 ******************************************************************************/
    redux.field_objects.multi_media.modInit = function(el) {
        
        // Localization variable
        l10n = redux_multi_media_l10;

        // MainID into global variable.
        redux.field_objects.multi_media.mainID  = el.attr('data-id');

        // dev_mode status
        var dev_mode = Boolean(el.find('.redux-multi-media-container').data('dev-mode'));

        // Add ext version info to footer, dev mode only.
        if (dev_mode === true) {
            var ver         = el.find('.redux-multi-media-container').data('version');
            var dev_html    = $('div.redux-timer').html();
            var pos         = dev_html.indexOf('Multi Media Selector');
            
            if (pos === -1) {
                $('div.redux-timer').html(dev_html + '<br/>Multi Media Selector extension v.' + ver);
            }
        }
    };

/*******************************************************************************
 * removeErrMsgs Function
 * 
 * Removes all error messages after clicking upload 
 * button.
 ******************************************************************************/

    // Removes error message(s) when clicking the Upload button
    redux.field_objects.multi_media.removeErrMsgs = function() {
        
        // Enumerate and remove existing 'file exists' messages
        $( '#' + redux.field_objects.multi_media.mainID + ' .attach_list li.redux-file-exists').each(function(idx, li){
            $(li).remove();
        });
        
        // Enumerate and remove existing 'max upload' messages
        $( '#' + redux.field_objects.multi_media.mainID + ' .attach_list li.redux-max-limit').each(function(idx, li) {
            $(li).remove();
        });
    };


    // Checks for duplicate after file selection
    redux.field_objects.multi_media.selExists = function(item) {
        var val = false;
        
        // Enumerate existing files
        $( '#' + redux.field_objects.multi_media.mainID + ' .attach_list li').each(function(idx,li){
            
            // Check for duplicate based on ID
            var len = $(li).find('input#filelist-' + item);
            
            // If it exists, exit .each
            if (len.length !== 0) {
                val = true;
                return;
            }
        });

        // Return value
        return val;
    };

/*******************************************************************************
 * addFile Function
 * 
 * Runs when upload button is clicked.
 ******************************************************************************/
    redux.field_objects.multi_media.addFile = function( event, selector, self ) {
        
        // Prevent default action
        event.preventDefault();

        // Variables
        var frame;
        var libFilter;
        var isList          = true;
        var uploadStatus    = true;
        
        // Get input ID
        var inputID         = self.prev('input').attr('id');
        
        // Make form field ID
        var $formfield      = $('#' + inputID);
        
        // Get form name
        var formName        = $formfield.attr('name');
        
        // If the media frame already exists, reopen it.
        if ( frame ) {
            frame.open();
            return;
        }

        // Remove existing error messages
        redux.field_objects.multi_media.removeErrMsgs();
        
        // Get library filter data
        var filter          = $( selector ).find('.library-filter').data('lib-filter');
        
        // Get max file upload number
        var maxFileUpload   = $( selector ).find('.redux-multi-media-container').data('max-file-upload');
        
        // Library filter MUST exist to do decoding
        if (filter !== undefined) {
            if (filter !== ''){
                libFilter   = [];
                filter      = decodeURIComponent(filter);
                filter      = JSON.parse(filter);

                // Enum file extensions
                $.each(filter, function(index, value) {
                    libFilter.push(value);
                });
            }
        }

        // Create the media frame.
        frame = wp.media({
            multiple:   isList ? true : false,
            title:      l10n.title,
            
            library: {
                type:   libFilter
            },

            button: {
                text:   l10n.upload_file
            }
        });

        // When an image is selected, run a callback.
        frame.on('select', function() {
            
            var addCount    = 0;
            var doChange;
            
            // Grab the selected attachment.
            var selection = frame.state().get( 'selection' );

            // Get all of our selected files
            var attachment = selection.toJSON();

            $formfield.val(attachment.url);
            $('#' + inputID + '_id').val(attachment.id);

            // Setup our fileGroup array
            var fileGroup   = [];
            var fileArr     = [];
            var imgArr      = [];
            var msgArr      = [];
            
            // Get existing file count
            var childCount  = $( '#' + redux.field_objects.multi_media.mainID + ' .attach_list').children().length;
                
            // Enum through each attachment
            $( attachment ).each( function() {

                // Respect max upload limit
                if ( maxFileUpload <= 0 || (addCount + childCount) < maxFileUpload ) {
                    
                    // Check for duplicates and format duplicate message
                    if (redux.field_objects.multi_media.selExists(this.id)) {
                        var dupMsg = l10n.dup_warn;

                        dupMsg = dupMsg.replace('%s', '<strong>' + this.filename + '</strong>');
                        uploadStatus = '<li class="redux-file-exists">' + dupMsg + '</li>';
                        //fileGroup.push( uploadStatus );
                        msgArr.push(uploadStatus);
                        
                        // If only file, then don't ask to save changes.
                        doChange = false;
                        
                        // continue equivilent
                        return true;
                    }

                    // Handle images
                    if ( this.type && this.type === 'image' ) {
                        
                        // image preview
                        uploadStatus = 
                        '<li class="img_status">' +
                            '<img width="50" height="50" src="' + this.url + '" class="attachment-50x50" alt="' + this.filename + '">'+
                            '<p><a href="#" class="redux_remove_file_button" rel="'+ inputID +'['+ this.id +']">' + l10n.remove_image +'</a></p>'+
                            '<input type="hidden" id="filelist-'+ this.id +'" name="' + formName + '[' + this.id +']" value="' + this.url + '">' +
                        '</li>';

                        // Add our file to our fileGroup array
                        //fileGroup.push( uploadStatus );
                        imgArr.push(uploadStatus);

                        // Set change flag
                        doChange = true;
                        
                    // Handle everything else
                    } else {
                        
                        // Standard generic output if it's not an image.
                        uploadStatus = 
                        '<li>'+ l10n.file +' <strong>'+ this.filename +'</strong>&nbsp;&nbsp;&nbsp; (<a href="' + this.url + '" target="_blank" rel="external">' + l10n.download + '</a> / <a href="#" class="redux_remove_file_button" rel="'+ inputID + '['+ this.id +']">' + l10n.remove_file +'</a>)' +
                            '<input type="hidden" id="filelist-' + this.id + '" name="' + formName + '[' + this.id + ']" value="' + this.url + '">' +
                        '</li>';

                        fileArr.push(uploadStatus);
                    }

                    // Increment count of added files
                    addCount++;
                    
                // If max file upload reached, generate error message
                } else {
                    var maxMsg = l10n.max_warn;

                    maxMsg = maxMsg.replace('%s', '<strong>' + maxFileUpload + '</strong>' );
                    uploadStatus = '<li class="redux-max-limit">' + maxMsg + '</li>';

                    //fileGroup.push( uploadStatus );
                    msgArr.push(uploadStatus);
                    
                    // Bail out of .each for good!
                    return;
                }
            });

            // Push images files onto end of stack.
            if (!$.isEmptyObject(imgArr)) {
                $(imgArr).each(function(idx, val) {
                    fileGroup.push (val);
                    doChange = true;
                });
            }

            // Push none image files onto end of stack.
            if (!$.isEmptyObject(fileArr)) {
                $(fileArr).each(function(idx, val) {
                    fileGroup.push (val);
                    doChange = true;
                });
            }

            // Push errors onto end of stack
            if (!$.isEmptyObject(msgArr)) {
                $(msgArr).each(function(idx, val) {
                    fileGroup.push (val);
                });
            }

            // Append each item from our fileGroup array to .redux_media_status
            $( fileGroup ).each( function() {
                $formfield.siblings('.redux_media_status').slideDown().append(this);
            });

            // Close mefia frame
            frame.close();

            // Prompt for save changes, if necessary
            if (doChange === true) {
                redux_change( $( selector ).find( '.redux_media_status' ) );
            }
        });

        // Finally, open the modal.
        frame.open();
    };

/*******************************************************************************
 * removeFile Function
 * 
 * Runs when the delete icon or remove link is clicked.
 ******************************************************************************/
    redux.field_objects.multi_media.removeFile = function(event, selector, self ) {
        
        // Prevent default action
        event.preventDefault();
        
        var $self = self;
        
        // If delete icon is clicked
        if ( $self.is( '.attach_list .redux_remove_file_button' ) ){
            
            // remove image from page
            $self.parents('li').remove();
            
            // prompt for save changes
            redux_change( $( selector ).find( '.redux_media_status' ) );
            
            // bail out.
            return false;
        }
        
        // Remove file link from page
        var inputID     = $self.attr('rel');
        var $container  = $self.parents('.img_status');

        selector.find('input#' + inputID).val('');
        selector.find('input#' + inputID + '_id').val('');
        
        if ( ! $container.length ) {
            $self.parents('.redux_media_status').html('');
        } else {
            $container.html('');
        }
        
        return false;        
    };
})(jQuery);