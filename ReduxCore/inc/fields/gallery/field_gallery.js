/* global redux_change, wp */

/*global redux_change, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.gallery = redux.field_objects.gallery || {};

    redux.field_objects.gallery.init = function( selector ) {


        if ( !selector ) {
            selector = $( document ).find( '.redux-container-gallery:visible' );
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
                // When the user clicks on the Add/Edit gallery button, we need to display the gallery editing
                el.on(
                    {
                        click: function( event ) {
                            //console.log(event);
                            // hide gallery settings used for posts/pages
                            wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
//                                render: function(){
//                                    console.log(wp.media.view);
//                                    this.update.apply( this, ['size'] );
//                                    return this;
//                                },
                                template: function(view){
                                    //console.log(view);
                                    
                                  return;// wp.media.template('gallery-settings')(view);
                                }
                            });       
                            
                            var current_gallery = $( this ).closest( 'fieldset' );

                            if ( event.currentTarget.id === 'clear-gallery' ) {
                                //remove value from input

                                var rmVal = current_gallery.find( '.gallery_values' ).val( '' );

                                //remove preview images
                                current_gallery.find( ".screenshot" ).html( "" );

                                return;

                            }

                            // Make sure the media gallery API exists
                            if ( typeof wp === 'undefined' || !wp.media || !wp.media.gallery ) {
                                return;
                            }
                            event.preventDefault();

                            // Activate the media editor
                            var $$ = $( this );

                            var val = current_gallery.find( '.gallery_values' ).val();
                            var final;

                            if ( !val ) {
                                final = '[gallery ids="0"]';
                            } else {
                                final = '[gallery ids="' + val + '"]';
                            }


                            var frame = wp.media.gallery.edit( final );
                            
                            if (!val) {
                                var uploader = $('body').find('#' + frame.el.id);
                                var inline = uploader.find('.uploader-inline');
                                var spinner = uploader.find('.media-toolbar .spinner');
                                
                                setTimeout(
                                    function(){ 
                                        if (inline.hasClass('hidden')) {
                                            inline.removeClass('hidden');
                                            spinner.removeClass('is-active');
                                        }
                                    }, 400
                                );
                            }

                            // When the gallery-edit state is updated, copy the attachment ids across
                            frame.state( 'gallery-edit' ).on(
                                'update', function( selection ) {

                                    //clear screenshot div so we can append new selected images
                                    current_gallery.find( ".screenshot" ).html( "" );

                                    var element, preview_html = "", preview_img;
                                    var ids = selection.models.map(
                                        function( e ) {
                                            element = e.toJSON();
                                            //preview_img = typeof element.sizes.thumbnail !== 'undefined' ? element.sizes.thumbnail.url : element.url;
                                            preview_img = (typeof element.sizes !== "undefined" && typeof element.sizes.thumbnail !== 'undefined') ? element.sizes.thumbnail.url : element.url;

                                            preview_html = "<a class='of-uploaded-image' href='" + preview_img + "'><img class='redux-option-image' src='" + preview_img + "' alt='' /></a>";
                                            current_gallery.find( ".screenshot" ).append( preview_html );

                                            return e.id;
                                        }
                                    );

                                    current_gallery.find( '.gallery_values' ).val( ids.join( ',' ) );
                                    redux_change( current_gallery.find( '.gallery_values' ) );
                                    frame.detach();
                                }
                            );

                            return false;
                        }
                    }, '.gallery-attachments'
                );
            }
        );

    };
})( jQuery );