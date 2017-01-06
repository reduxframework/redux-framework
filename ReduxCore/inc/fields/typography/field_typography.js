/*global redux_change, redux*/

/**
 * Typography
 * Dependencies:        google.com, jquery, select2
 * Feature added by:    Dovy Paukstys - http://simplerain.com/
 * Date:                06.14.2013
 *
 * Rewrite:             Kevin Provance (kprovance)
 * Date:                May 25, 2014
 */

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.typography = redux.field_objects.typography || {};

    var selVals = [];
    var isSelecting = false;

    var default_params = {
        width: '100%',
        //triggerChange: true,
        allowClear: true,
        theme:'classic'
    };

    $( document ).ready(
        function() {
            //redux.field_objects.typography.init();
        }
    );

    redux.field_objects.typography.init = function( selector, skipCheck) {

        if ( !selector ) {
            selector = $( document ).find( ".redux-group-tab:visible" ).find( '.redux-container-typography:visible' );
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

                var fontClear;

                el.each(
                    function() {
                        // init each typography field
                        $( this ).find( '.redux-typography-container' ).each(
                            function() {
                                
                                var family = $( this ).find( '.redux-typography-family' );
                                
                                if ( family.attr( 'data-value' ) === undefined ) {
                                    family = $( this );
                                } else if ( family.attr( 'data-value' ) !== "" ) {
                                    
                                    $( family ).val( family.attr( 'data-value' )).trigger('change');
                                }
                                
                                
                                var select2_handle = $( this ).find( '.select2_params' );
                                if ( select2_handle.size() > 0 ) {
                                    var select2_params = select2_handle.val();

                                    select2_params = JSON.parse( select2_params );
                                    default_params = $.extend( {}, default_params, select2_params );
                                }
                                
                                fontClear = Boolean( $( this ).find( '.redux-font-clear' ).val() );
                                
                                // select2 magic, to load font-family dynamically
                                var data = [{id: 'none', text: 'none'}];

                                var thisID = $(this).find( '.redux-typography-family' ).parents( '.redux-container-typography:first' ).attr( 'data-id' );

                                // User included fonts?
                                var isUserFonts = $( '#' + thisID + ' .redux-typography-font-family' ).data( 'user-fonts' );
                                isUserFonts = isUserFonts ? 1 : 0;

                                // Google font isn use?
                                var usingGoogleFonts = $( '#' + thisID + ' .redux-typography-google' ).val();
                                usingGoogleFonts = usingGoogleFonts ? 1 : 0;

                                // Set up data array
                                var buildData = [];

                                // If custom fonts, push onto array
                                if ( redux.customfonts !== undefined ) {
                                    buildData.push( redux.customfonts );
                                }

                                // If standard fonts, push onto array
                                if ( redux.stdfonts !== undefined && isUserFonts === 0 ) {
                                    buildData.push( redux.stdfonts );
                                }

                                // If user fonts, pull from localize and push into array
                                if ( isUserFonts == 1 ) {
                                    var fontKids = [];

                                    // <option>
                                    for ( var key in redux.typography[thisID] ) {
                                        var obj = redux.typography[thisID].std_font;

                                        for ( var prop in obj ) {
                                            if ( obj.hasOwnProperty( prop ) ) {
                                                fontKids.push(
                                                    {
                                                        id: prop,
                                                        text: prop,
                                                        'data-google': 'false'
                                                    }
                                                );
                                            }
                                        }
                                    }

                                    // <optgroup>
                                    var fontData = {
                                        text: 'Standard Fonts',
                                        children: fontKids
                                    };

                                    buildData.push( fontData );
                                }

                                // If googfonts on and had data, push into array
                                if ( usingGoogleFonts == 1 || usingGoogleFonts === true && redux.googlefonts !== undefined ) {
                                    buildData.push( redux.googlefonts );
                                }

                                // output data to drop down
                                data = buildData;
                                
                                var default_params2, placeholder;
                                default_params2 = default_params;
                                placeholder = $( this ).find( ".redux-typography-family" ).attr('placeholder');
                                var val = $( this ).find( ".redux-typography-family" ).attr('data-value');
                                //console.log(placeholder);  
                                //var default_params2 = default_params;
                                default_params2['data'] = data;
                                default_params2['placeholder'] = placeholder;
                                default_params2['allowClear'] = fontClear;

                                $( this ).find( ".redux-typography-family" ).select2(default_params2);
                                default_params2['data'] = null;
                                
                                $( this ).find( ".redux-typography-family" ).val(val).trigger('change');
                                
                                var xx = el.find( ".redux-typography-family" );
                                if ( !xx.hasClass( 'redux-typography-family' ) ) {
                                    
                                    placeholder = $(this).find( ".redux-typography-align" ).attr('placeholder');
                                    default_params2['placeholder'] = placeholder;
                                    el.find( ".redux-typography-style" ).select2( default_params2 );
                                   
                                }
                                
                                placeholder = $(this).find( ".redux-typography-align" ).attr('placeholder');
                                default_params2['placeholder'] = placeholder;
                                $(this).find( ".redux-typography-align" ).select2( default_params2 );
                                
                                placeholder = $(this).find( ".redux-typography-family-backup" ).attr('placeholder');
                                default_params2['placeholder'] = placeholder;
                                $(this).find( ".redux-typography-family-backup" ).select2( default_params2 );
                                
                                placeholder = $(this).find( ".redux-typography-transform" ).attr('placeholder');
                                default_params2['placeholder'] = placeholder;
                                $(this).find( ".redux-typography-transform" ).select2( default_params2 );
                                
                                placeholder = $(this).find( ".redux-typography-font-variant" ).attr('placeholder');
                                default_params2['placeholder'] = placeholder;
                                $(this).find( ".redux-typography-font-variant" ).select2( default_params2 );
                                
                                placeholder = $(this).find( ".redux-typography-decoration" ).attr('placeholder');
                                default_params2['placeholder'] = placeholder;
                                $(this).find( ".redux-typography-decoration" ).select2( default_params2 );
                                
                                redux.field_objects.typography.select( family, true, false, null, true );

                        //init when value is changed
                        $( this ).find( '.redux-typography-family, .redux-typography-family-backup, .redux-typography-style, .redux-typography-subsets, .redux-typography-align' ).on(
                            'change', function(val) {
                                
                                var thisID = $(this).attr('id');
                                
                                if($(this).hasClass('redux-typography-family')){
                                    if($( '#' + thisID ).val()){
                                    var getVals = $(this).select2('data');
                                    
                                    var fontName;
                                    if(getVals){
                                        fontName = getVals[0]['text'];
                                    }
                                    else{
                                        fontName = null;
                                    }
                                    
                                    var familyID = $( '#'+ thisID );

                                    $( '#' + thisID ).attr( 'data-value', fontName );
                                   
                                    selVals = getVals[0];
                                    isSelecting = true;
                                    
                                    redux.field_objects.typography.select( $( '#'+ thisID ), true, false, fontName, true);
                                    }
                            
                                }
                                else{
                                    var val = $('#' + thisID ).val();
                                    $( '#' + thisID ).attr( 'data-value', val );
                                    
                                    if($(this).hasClass('redux-typography-align') || $(this).hasClass('redux-typography-subsets') || $(this).hasClass('redux-typography-family-backup')  || $(this).hasClass('redux-typography-transform') || $(this).hasClass('redux-typography-font-variant') || $(this).hasClass('redux-typography-decoration')) {
                                    
                                        $( '#' + thisID + ' option[selected="selected"]').removeAttr('selected');
                                    
                                        $( '#' + thisID + ' option[value="' + val + '"]' ).attr('selected','selected');
                                   
                                    }
                                    
                                    if($(this).hasClass('redux-typography-subsets') ) {
                                        $('#' + thisID ).siblings('.typography-subsets').val(val);  
                                    }
                                    
                                    redux.field_objects.typography.select( $( this ), true, false, null, false);
                                }
                            }
                        );
                        
                        
                        //init when value is changed
                        $( this ).find( '.redux-typography-size, .redux-typography-height, .redux-typography-word, .redux-typography-letter' ).keyup(
                            function() {
                                redux.field_objects.typography.select( $( this ).parents( '.redux-container-typography:first' ));
                            }
                        );
                        
                        // Have to redeclare the wpColorPicker to get a callback function
                        $( this ).find( '.redux-typography-color' ).wpColorPicker(
                            {
                                change: function( e, ui ) {
                                    $( this ).val( ui.color.toString() );
                                    redux.field_objects.typography.select( $( this ).parents( '.redux-container-typography:first' ) );
                                }
                            }
                        );

                        // Don't allow negative numbers for size field
                        $( this ).find( ".redux-typography-size" ).numeric(
                            {
                                allowMinus: false
                            }
                        );

                        // Allow negative numbers for indicated fields
                        $( this ).find( ".redux-typography-height, .redux-typography-word, .redux-typography-letter" ).numeric(
                            {
                                allowMinus: true
                            }
                        );
                        
                        $( this ).find( ".redux-typography" ).on(
                            'select2:unselecting', function( val, choice ) {
                                
                                var thisID = $( this ).attr( 'id' );

                                $( '#' + thisID ).attr( 'data-value', '' );
                                
                                
                                if($(this).hasClass('redux-typography-family')){

                                    $(this).val(null).trigger('change');
                                    
                                    redux.field_objects.typography.select( $( '#'+ thisID ), true, false, null, true);
                                }
                                else{

                                    if($(this).hasClass('redux-typography-align') || $(this).hasClass('redux-typography-subsets') || $(this).hasClass('redux-typography-family-backup')  || $(this).hasClass('redux-typography-transform') || $(this).hasClass('redux-typography-font-variant') || $(this).hasClass('redux-typography-decoration')) {
                                    
                                        $( '#' + thisID + ' option[selected="selected"]').removeAttr('selected');                                  
                                    }
                                    
                                    if($(this).hasClass('redux-typography-subsets') ) {
                                        $('#' + thisID ).siblings('.typography-subsets').val('');  
                                    }
                                    
                                    if($(this).hasClass('redux-typography-family-backup')){
                                        $('#' + thisID ).val(null).trigger('change');
                                    }
                                    
                                    redux.field_objects.typography.select( $( this ), true, false, null, false);
                                }
                            }
                        );
                        
                        
                                window.onbeforeunload = null;
                            }
                        );
                    }
                );
            }
        );
    };

    // Return font size
    redux.field_objects.typography.size = function( obj ) {
        var size = 0,
            key;

        for ( key in obj ) {
            if ( obj.hasOwnProperty( key ) ) {
                size++;
            }
        }

        return size;
    };

    // Return proper bool value
    redux.field_objects.typography.makeBool = function( val ) {
        if ( val == 'false' || val == '0' || val === false || val === 0 ) {
            return false;
        } else if ( val == 'true' || val == '1' || val === true || val == 1 ) {
            return true;
        }
    };

    redux.field_objects.typography.contrastColour = function( hexcolour ) {
        // default value is black.
        var retVal = '#444444';

        // In case - for some reason - a blank value is passed.
        // This should *not* happen.  If a function passing a value
        // is canceled, it should pass the current value instead of
        // a blank.  This is how the Windows Common Controls do it.  :P
        if ( hexcolour !== '' ) {

            // Replace the hash with a blank.
            hexcolour = hexcolour.replace( '#', '' );

            var r = parseInt( hexcolour.substr( 0, 2 ), 16 );
            var g = parseInt( hexcolour.substr( 2, 2 ), 16 );
            var b = parseInt( hexcolour.substr( 4, 2 ), 16 );
            var res = ((r * 299) + (g * 587) + (b * 114)) / 1000;

            // Instead of pure black, I opted to use WP 3.8 black, so it looks uniform.  :) - kp
            retVal = (res >= 128) ? '#444444' : '#ffffff';
        }

        return retVal;
    };


    //  Sync up font options
    redux.field_objects.typography.select = function( selector, skipCheck, destroy, fontName, active ) {
        var mainID;
        
        // Main id for selected field
        mainID = $( selector ).parents( '.redux-container-typography:first' ).attr( 'data-id' );
        if (mainID === undefined) {
            mainID = $(selector).attr( 'data-id' );
        }
        
        var parent = $( selector ).parents( '.redux-container-typography:first' );
        var data = [];
        //$.each(parent.find('.redux-typography-field'), function() {
        //    console.log();
        //});
        //console.log( selector );
        // Set all the variables to be checked against
        var family = $( '#' + mainID + '-family' ).val();

        if ( !family ) {
            family = null; //"inherit";
        }
        
        if(fontName){
            family = fontName;
        }

        var familyBackup = $( '#' + mainID + ' select.redux-typography-family-backup' ).val();
        var size = $( '#' + mainID + ' .redux-typography-size' ).val();
        var height = $( '#' + mainID + ' .redux-typography-height' ).val();
        var word = $( '#' + mainID + ' .redux-typography-word' ).val();
        var letter = $( '#' + mainID + ' .redux-typography-letter' ).val();
        var align = $( '#' + mainID + ' select.redux-typography-align' ).val();
        var transform = $( '#' + mainID + ' select.redux-typography-transform' ).val();
        var fontVariant = $( '#' + mainID + ' select.redux-typography-font-variant' ).val();
        var decoration = $( '#' + mainID + ' select.redux-typography-decoration' ).val();
        var style = $( '#' + mainID + ' select.redux-typography-style' ).val();
        var script = $( '#' + mainID + ' select.redux-typography-subsets' ).val();
        var color = $( '#' + mainID + ' .redux-typography-color' ).val();
        var units = $( '#' + mainID ).data( 'units' );
        //console.log('here3');
        //console.log(color);

        //var output = family;
        
        //add
        if(active){

        // Is selected font a google font?
        var google;
        if ( isSelecting === true ) {
            google = redux.field_objects.typography.makeBool( selVals['data-google'] );
            $( '#' + mainID + ' .redux-typography-google-font' ).val( google );
        } else {
            google = redux.field_objects.typography.makeBool( $( '#' + mainID + ' .redux-typography-google-font' ).val() ); // Check if font is a google font
        }

        // Page load. Speeds things up memory wise to offload to client
        if ( !$( '#' + mainID ).hasClass( 'typography-initialized' ) ) {
            //style = $( '#' + mainID + ' select.redux-typography-style' ).data( 'value' );
            //script = $( '#' + mainID + ' select.redux-typography-subsets' ).data( 'value' );
            style = $( '#' + mainID + ' select.redux-typography-style' ).attr( 'data-value' );
            script = $( '#' + mainID + ' select.redux-typography-subsets' ).attr( 'data-value' );

            if ( style !== "" ) {
                style = String( style );
            }

            if ( typeof (script) !== undefined ) {
                script = String( script );
            }
        }

        // Something went wrong trying to read google fonts, so turn google off
        if ( redux.fonts.google === undefined ) {
            google = false;
        }

        // Get font details
        var details = '';
        if ( google === true && ( family in redux.fonts.google) ) {
            details = redux.fonts.google[family];
            console.log('details '+details);
        } else {
            details = {
                '400': 'Normal 400',
                '700': 'Bold 700',
                '400italic': 'Normal 400 Italic',
                '700italic': 'Bold 700 Italic'
            };
        }

        if ( $( selector ).hasClass( 'redux-typography-subsets' ) ) {
            $( '#' + mainID + ' input.typography-subsets' ).val( script );
        }
            
        } //add active

        // If we changed the font
        if ( $( selector ).hasClass( 'redux-typography-family' ) ) {
            var html = '<option value=""></option>';

            // Google specific stuff
            if ( google === true ) {

                // STYLES
                var selected = "";
                $.each(
                    details.variants, function( index, variant ) {
                        if ( variant.id === style || redux.field_objects.typography.size( details.variants ) === 1 ) {
                            selected = ' selected="selected"';
                            style = variant.id;
                        } else {
                            selected = "";
                        }

                        html += '<option value="' + variant.id + '"' + selected + '>' + variant.name.replace(
                            /\+/g, " "
                        ) + '</option>';
                    }
                );

                // destroy select2
                if(destroy){
                $( '#' + mainID + ' .redux-typography-style' ).select2( "destroy" );
                }

                // Instert new HTML
                $( '#' + mainID + ' .redux-typography-style' ).html( html );
                
                var placeholder = $( '#' + mainID + ' .redux-typography-style' ).attr('placeholder');
                var default_params2 = default_params;                
                default_params2['placeholder'] = placeholder;

                // Init select2
                $( '#' + mainID + ' .redux-typography-style' ).select2( default_params2 );
                
                default_params2['placeholder'] = null;

                // SUBSETS
                selected = "";
                html = '<option value=""></option>';

                $.each(
                    details.subsets, function( index, subset ) {
                        if ( subset.id === script || redux.field_objects.typography.size( details.subsets ) === 1 ) {
                            selected = ' selected="selected"';
                            script = subset.id;
                            $( '#' + mainID + ' input.typography-subsets' ).val( script );
                        } else {
                            selected = "";
                        }
                        html += '<option value="' + subset.id + '"' + selected + '>' + subset.name.replace(
                            /\+/g, " "
                        ) + '</option>';
                    }
                );

                //if (typeof (familyBackup) !== "undefined" && familyBackup !== "") {
                //    output += ', ' + familyBackup;
                //}

                // Destroy select2
                if(destroy){
                    $( '#' + mainID + ' .redux-typography-subsets' ).select2( "destroy" );
                }
                // Inset new HTML
                $( '#' + mainID + ' .redux-typography-subsets' ).html( html );
                
                placeholder = $( '#' + mainID + ' .redux-typography-subsets' ).attr('placeholder');
                var default_params2 = default_params;                
                default_params2['placeholder'] = placeholder;
                
                // Init select2
                $( '#' + mainID + ' .redux-typography-subsets' ).select2( default_params2 );
                
                default_params2['placeholder'] = null;

                $( '#' + mainID + ' .redux-typography-subsets' ).parent().fadeIn( 'fast' );
                $( '#' + mainID + ' .typography-family-backup' ).fadeIn( 'fast' );
            } else {
                if ( details ) {
                    $.each(
                        details, function( index, value ) {
                            if ( index === style || index === "normal" ) {
                                selected = ' selected="selected"';
                                $( '#' + mainID + ' .typography-style select2-selection__rendered' ).text( value );
                            } else {
                                selected = "";
                            }

                            html += '<option value="' + index + '"' + selected + '>' + value.replace(
                                '+', ' '
                            ) + '</option>';
                        }
                    );

                    // Destory select2
                    if(destroy){
                        $( '#' + mainID + ' .redux-typography-style' ).select2( "destroy" );
                    }
                    // Insert new HTML
                    $( '#' + mainID + ' .redux-typography-style' ).html( html );

                    placeholder = $( '#' + mainID + ' .redux-typography-style' ).attr('placeholder');
                    var default_params2 = default_params;                
                    default_params2['placeholder'] = placeholder;
                    
                    // Init select2
                    $( '#' + mainID + ' .redux-typography-style' ).select2( default_params2 );
                    
                    default_params2['placeholder'] = null;

                    // Prettify things
                    $( '#' + mainID + ' .redux-typography-subsets' ).parent().fadeOut( 'fast' );
                    $( '#' + mainID + ' .typography-family-backup' ).fadeOut( 'fast' );
                }
            }

            $( '#' + mainID + ' .redux-typography-font-family' ).val( family );
            
            
        } else if ( $( selector ).hasClass( 'redux-typography-family-backup' ) && familyBackup !== "" ) {
            $( '#' + mainID + ' .redux-typography-font-family-backup' ).val( familyBackup );
        }
        
        //add
        if(active){    
            
        // Check if the selected value exists. If not, empty it. Else, apply it.
        if ( $( '#' + mainID + " select.redux-typography-style option[value='" + style + "']" ).length === 0 ) {
            style = "";
            $( '#' + mainID + ' select.redux-typography-style' ).val( '' ).trigger('change');
        } else if ( style === "400" ) {
            $( '#' + mainID + ' select.redux-typography-style' ).val( style ).trigger('change');
        }

        // Handle empty subset select
        if ( $( '#' + mainID + " select.redux-typography-subsets option[value='" + script + "']" ).length === 0 ) {
            script = "";
            $( '#' + mainID + ' select.redux-typography-subsets' ).val( '' ).trigger('change');
            $( '#' + mainID + ' input.typography-subsets' ).val( script );
        }
            
        }//add $active

        var _linkclass = 'style_link_' + mainID;

        //remove other elements crested in <head>
        $( '.' + _linkclass ).remove();
        if ( family !== null && family !== "inherit" && $( '#' + mainID ).hasClass( 'typography-initialized' ) ) {

            //replace spaces with "+" sign
            var the_font = family.replace( /\s+/g, '+' );
            if ( google === true ) {

                //add reference to google font family
                var link = the_font;

                if ( style && style !== "" ) {
                    link += ':' + style.replace( /\-/g, " " );
                }

                if ( script && script !== "" ) {
                    link += '&subset=' + script;
                }

                if (isSelecting === false) { 
                    if ( typeof (WebFont) !== "undefined" && WebFont ) {
                        WebFont.load( {google: {families: [link]}} );
                    }
                }
                $( '#' + mainID + ' .redux-typography-google' ).val( true );
            } else {
                $( '#' + mainID + ' .redux-typography-google' ).val( false );
            }
        }
        
        //console.log(style);
        
        // Weight and italic
        if ( style.indexOf( "italic" ) !== -1 ) {
            $( '#' + mainID + ' .typography-preview' ).css( 'font-style', 'italic' );
            $( '#' + mainID + ' .typography-font-style' ).val( 'italic' );
            style = style.replace( 'italic', '' );
        } else {
            $( '#' + mainID + ' .typography-preview' ).css( 'font-style', "normal" );
            $( '#' + mainID + ' .typography-font-style' ).val( '' );
        }

        $( '#' + mainID + ' .typography-font-weight' ).val( style );

        if ( !height ) {
            height = size;
        }

        if ( size === '' || size === undefined ) {
            $( '#' + mainID + ' .typography-font-size' ).val( '' );
        } else {
            $( '#' + mainID + ' .typography-font-size' ).val( size + units );
        }

        if ( height === '' || height === undefined ) {
            $( '#' + mainID + ' .typography-line-height' ).val( '' );
        } else {
            $( '#' + mainID + ' .typography-line-height' ).val( height + units );
        }

        if ( word === '' || word === undefined ) {
            $( '#' + mainID + ' .typography-word-spacing' ).val( '' );
        } else {
            $( '#' + mainID + ' .typography-word-spacing' ).val( word + units );
        }

        if ( letter === '' || letter === undefined ) {
            $( '#' + mainID + ' .typography-letter-spacing' ).val( '' );
        } else {
            $( '#' + mainID + ' .typography-letter-spacing' ).val( letter + units );
        }
        
        // Show more preview stuff
        if ( $( '#' + mainID ).hasClass( 'typography-initialized' ) ) {
            //console.log('here2');
            var isPreviewSize = $( '#' + mainID + ' .typography-preview' ).data( 'preview-size' );

            if ( isPreviewSize == '0' ) {
                $( '#' + mainID + ' .typography-preview' ).css( 'font-size', size + units );
            }

            $( '#' + mainID + ' .typography-preview' ).css( 'font-weight', style );

            //show in the preview box the font
            $( '#' + mainID + ' .typography-preview' ).css( 'font-family', family + ', sans-serif' );

            if ( family === 'none' && family === '' ) {
                //if selected is not a font remove style "font-family" at preview box
                $( '#' + mainID + ' .typography-preview' ).css( 'font-family', 'inherit' );
            }

            $( '#' + mainID + ' .typography-preview' ).css( 'line-height', height + units );
            $( '#' + mainID + ' .typography-preview' ).css( 'word-spacing', word + units );
            $( '#' + mainID + ' .typography-preview' ).css( 'letter-spacing', letter + units );

            if ( color ) {
                $( '#' + mainID + ' .typography-preview' ).css( 'color', color );
                $( '#' + mainID + ' .typography-preview' ).css(
                    'background-color', redux.field_objects.typography.contrastColour( color )
                );
            }

            $( '#' + mainID + ' .typography-style select2-selection__rendered' ).text( $( '#' + mainID + ' .redux-typography-style option:selected' ).text() );
            $( '#' + mainID + ' .typography-script select2-selection__rendered' ).text( $( '#' + mainID + ' .redux-typography-subsets option:selected' ).text() );

            if ( align ) {
                $( '#' + mainID + ' .typography-preview' ).css( 'text-align', align );
            }

            if ( transform ) {
                $( '#' + mainID + ' .typography-preview' ).css( 'text-transform', transform );
            }

            if ( fontVariant ) {
                $( '#' + mainID + ' .typography-preview' ).css( 'font-variant', fontVariant );
            }

            if ( decoration ) {
                $( '#' + mainID + ' .typography-preview' ).css( 'text-decoration', decoration );
            }
            $( '#' + mainID + ' .typography-preview' ).slideDown();
        }
        // end preview stuff

        // if not preview showing, then set preview to show
        if ( !$( '#' + mainID ).hasClass( 'typography-initialized' ) ) {
            $( '#' + mainID ).addClass( 'typography-initialized' );
        }

        isSelecting = false;

        if ( ! skipCheck ) {
            redux_change( selector );
        }
    
    }
    
})( jQuery );