/* global redux_change */

/**
 * Typography
 * Dependencies:        google.com, jquery, select2
 * Feature added by:    Dovy Paukstys - http://simplerain.com/
 * Date:                06.14.2013
 * 
 * Rewrite:             Kevin Provance (kprovance)
 * Date:                May 25, 2014
 */

(function($) {
    "use strict";

    $.reduxTypography = $.reduxTypography || {};

    var selVals     = [];
    var isSelecting = false;

    $(document).ready(function() {
        $.reduxTypography.init();
    });
    
    $.reduxTypography.init = function() {
        $('.redux-typography-container').each(function() {
            var family = $(this).find('.redux-typography-family');

            if (family.data('value') !== "") {
                $(family).val(family.data('value'));
            }

            $.reduxTypography.select(family);
            //typographySelect(family);

            window.onbeforeunload = null;
        });

        //init when value is changed
        $('.redux-typography').on('change', function() {
            $.reduxTypography.select($(this));
            //typographySelect(this);
        });

        //init when value is changed
        $('.redux-typography-size, .redux-typography-height, .redux-typography-word, .redux-typography-letter, .redux-typography-align, .redux-typography-transform, .redux-typography-font-variant, .redux-typography-decoration').keyup(function() {
            $.reduxTypography.select ($(this));
            //typographySelect(this);
        });

        // Have to redeclare the wpColorPicker to get a callback function
        $('.redux-typography-color').wpColorPicker({
            change: function(event, ui) {
                redux_change($(this));
                $(this).val(ui.color.toString());
                $.reduxTypography.select($(this));
                //typographySelect($(this));
            }
        });

        $(".redux-typography-size").numeric({
            allowMinus: false,
        });

        $(".redux-typography-height, .redux-typography-word, .redux-typography-letter").numeric({
            allowMinus: true,
        });        
        
        var data = [{id:'none', text: 'none'}];
        $(".redux-typography-family").select2({
            matcher: function (term, text) {
                return text.toUpperCase().indexOf(term.toUpperCase()) === 0;
            },

            query: function (query) {
                return window.Select2.query.local(data)(query);
            },

            initSelection : function(element, callback) {
                var data = {id: element.val(), text: element.val()};
                callback(data); 
            },

        }).on("select2-opening", function(e) {

            // Get field ID
            var thisID = $(this).parents('.redux-container-typography:first').attr('data-id');

            var isUserFonts = $('#' + thisID + ' .redux-typography-font-family').data('user-fonts');
            isUserFonts = isUserFonts ? 1 : 0;

            // Google font isn use?
            var usingGoogleFonts = $('#' + thisID + ' .redux-typography-google').val();
            usingGoogleFonts = usingGoogleFonts ? 1 : 0;

            // Set up data array
            var buildData = [];

            // If custom fonts, push onto array
            if (redux.customfonts !== undefined) {
                buildData.push(redux.customfonts);
            }

            // If standard fonts, push onto array
            if (redux.stdfonts !== undefined && isUserFonts === 0 ) {
                buildData.push(redux.stdfonts);
            }

            // If user fonts, pull from localize and push into array
            if (isUserFonts == 1) {
                var fontKids = [];

                for (var key in redux.typography[thisID]) {
                   var obj = redux.typography[thisID].std_font;

                   for (var prop in obj) {
                      if(obj.hasOwnProperty(prop)){
                        fontKids.push({
                            id:             prop, 
                            text:           prop, 
                            'data-google':  'false'
                        });
                      }
                   }
                }                

                var fontData = {
                    text:       'Standard Fonts',
                    children:   fontKids
                };

                buildData.push(fontData);
            }

            // If googfonts on and had data, push into array
            if (usingGoogleFonts == 1 || usingGoogleFonts === true && redux.googlefonts !== undefined) {
                buildData.push(redux.googlefonts);
            }

            // output data to drop down
            data = buildData;

            // get placeholder
            var selFamily = $('#' + thisID + ' select.redux-typography-family').val();
            if (!selFamily) {
                selFamily = null;
            }        

            // select current font
            $(".redux-typography-family").select2('val', selFamily);

        }).on('select2-selecting', function(val, object) {
            selVals     = val;
            isSelecting = true;
        });


        $(" .redux-typography-family-backup, .redux-typography-align, .redux-typography-transform, .redux-typography-font-variant, .redux-typography-decoration").select2({
            width:          'resolve',
            triggerChange:  true,
            allowClear:     true
        });
        
    };
    
    $.reduxTypography.size = function(obj) {
        var size = 0,
            key;
    
        for (key in obj) {
            if (obj.hasOwnProperty(key)) {
                size++;
            }
        }
        
        return size;
    };
    
    $.reduxTypography.makeBool = function(val) {
        if (val == 'false' || val == '0' || val === false || val === 0) {
            return false;
        } else if (val == 'true' || val == '1' || val === true || val == 1) {
            return true;
        }        
    };
    
    $.reduxTypography.select = function(selector) {

        var mainID          = $(selector).parents('.redux-container-typography:first').attr('data-id');
        
        // Set all the variables to be checked against
        var family          = $('#' + mainID + ' div.redux-typography-family').val();
        if (!family) {
            family = null; //"inherit";
        }
        
        var familyBackup    = $('#' + mainID + ' select.redux-typography-family-backup').val();
        var size            = $('#' + mainID + ' .redux-typography-size').val();
        var height          = $('#' + mainID + ' .redux-typography-height').val();
        var word            = $('#' + mainID + ' .redux-typography-word').val(); // New Word-Spacing
        var letter          = $('#' + mainID + ' .redux-typography-letter').val(); // New Letter-Spacing
        var align           = $('#' + mainID + ' select.redux-typography-align').val(); // text-align
        var transform       = $('#' + mainID + ' select.redux-typography-transform').val();
	var fontVariant     = $('#' + mainID + ' select.redux-typography-font-variant').val(); // New Font Variant
	var decoration      = $('#' + mainID + ' select.redux-typography-decoration').val(); // New Text Decoration
        var style           = $('#' + mainID + ' select.redux-typography-style').val();
        var script          = $('#' + mainID + ' select.redux-typography-subsets').val();
        var color           = $('#' + mainID + ' .redux-typography-color').val();
        var units           = $('#' + mainID).data('units');
        
        var output = family;
        
        var google;
        if (isSelecting === true) {
            google = $.reduxTypography.makeBool(selVals.object['data-google']);
            $('#' + mainID + ' .redux-typography-google-font').val(google);
        } else {
            google = $.reduxTypography.makeBool($('#' + mainID + ' .redux-typography-google-font').val()); // Check if font is a google font
        }

        // Page load. Speeds things up memory wise to offload to client
        if (!$('#' + mainID).hasClass('typography-initialized')) {
            style   = $('#' + mainID + ' select.redux-typography-style').data('value');
            script  = $('#' + mainID + ' select.redux-typography-subsets').data('value');
            
            if (style !== "") {
                style = String(style);
            }
            
            if (typeof (script) !== undefined) {
                script = String(script);
            }
        }
        
        // Get the styles and such from the font
        var details = "";

        // Something went wrong trying to read google fonts, so turn google off
        if (redux.fonts.google === undefined) {
            google = false;
        }
        
        if (google === true && ( family in redux.fonts.google)) {
            details = redux.fonts.google[family];
        } else {
            details = {
                '400': 'Normal 400',
                '700': 'Bold 700',
                '400italic': 'Normal 400 Italic',
                '700italic': 'Bold 700 Italic'                
            };
        }            

        // If we changed the font
        if ($(selector).hasClass('redux-typography-family')) {
            var html = '<option value=""></option>';

            if (google === true) { // Google specific stuff
                
                // STYLES
                var selected = "";
                $.each(details.variants, function(index, variant) {
                    if (variant.id === style || $.reduxTypography.size(details.variants) === 1) {
                        selected = ' selected="selected"';
                        style = variant.id;
                    } else {
                        selected = "";
                    }
                    
                    html += '<option value="' + variant.id + '"' + selected + '>' + variant.name.replace(/\+/g, " ") + '</option>';
                });

                // destory select2
                $('.redux-typography-style').select2("destroy");
                
                // Instert new HTML
                $('#' + mainID + ' .redux-typography-style').html(html);
                
                // Init select2
                $('#' + mainID +  ' .redux-typography-style').select2({
                    width:          'resolve',
                    triggerChange:  true,
                    allowClear:     true
                });
                
                
                // SUBSETS
                selected = "";
                html = '<option value=""></option>';
                
                $.each(details.subsets, function(index, subset) {
                    if (subset.id === script || $.reduxTypography.size(details.subsets) === 1) {
                        selected = ' selected="selected"';
                        script = subset.id;
                    } else {
                        selected = "";
                    }
                    
                    html += '<option value="' + subset.id + '"' + selected + '>' + subset.name.replace(/\+/g, " ") + '</option>';
                });
                
                if (typeof (familyBackup) !== "undefined" && familyBackup !== "") {
                    output += ', ' + familyBackup;
                }

                // Destory select2
                $('.redux-typography-subsets').select2("destroy");
                
                // Inset new HTML
                $('#' + mainID + ' .redux-typography-subsets').html(html);
                
                // Init select2
                $('#' + mainID +  ' .redux-typography-subsets').select2({
                    width: 'resolve',
                    triggerChange: true,
                    allowClear: true
                });
                
                $('#' + mainID + ' .redux-typography-subsets').parent().fadeIn('fast');
                $('#' + mainID + ' .typography-family-backup').fadeIn('fast');
            } else {
                if (details) {
                    $.each(details, function(index, value) {
                        if (index === style || index === "normal") {
                            selected = ' selected="selected"';
                            $('#' + mainID + ' .typography-style .select2-chosen').text(value);
                        } else {
                            selected = "";
                        }
                        
                        html += '<option value="' + index + '"' + selected + '>' + value.replace('+', ' ') + '</option>';
                    });
                    
                    // Destory select2
                    $('.redux-typography-style').select2("destroy");
                    
                    // Insert new HTML
                    $('#' + mainID + ' .redux-typography-style').html(html);
                    
                    // Init select2
                    $('#' + mainID + ' .redux-typography-style').select2({
                        width: 'resolve',
                        triggerChange: true,
                        allowClear: true
                    });
                    
                    // Prettify things
                    $('#' + mainID + ' .redux-typography-subsets').parent().fadeOut('fast');
                    $('#' + mainID + ' .typography-family-backup').fadeOut('fast');
                }
            }
        } else if ($(selector).hasClass('redux-typography-family-backup') && familyBackup !== "") {
            $('#' + mainID + ' .redux-typography-font-family').val(output);
        }

        // Check if the selected value exists. If not, empty it. Else, apply it.
        if ($('#' + mainID + " select.redux-typography-style option[value='" + style + "']").length === 0) {
            style = "";
            $('#' + mainID + ' select.redux-typography-style').select2('val', '');
        } else if (style === "400") {
            $('#' + mainID +  ' select.redux-typography-style').select2('val', style);
        }
        
        // Handle empty subset select
        if ($('#' + mainID + " select.redux-typography-subsets option[value='" + script + "']").length === 0) {
            script = "";
            $('#' + mainID + ' select.redux-typography-subsets').select2('val', '');
        }
        
        var _linkclass = 'style_link_' + mainID;

        //remove other elements crested in <head>
        $('.' + _linkclass).remove();
        if (family !== null && family !== "inherit" && $('#' + mainID).hasClass('typography-initialized')) {
            
            //replace spaces with "+" sign
            var the_font = family.replace(/\s+/g, '+');
            if (google === true) {
                
                //add reference to google font family
                var link = the_font;
                
                if (style) {
                    link += ':' + style.replace(/\-/g, " ");
                }
                
                if (script) {
                    link += '&subset=' + script;
                }

                if (typeof (WebFont) !== "undefined" && WebFont) {
                    WebFont.load({google: {families: [link]}});
                }
                
                $('#' + mainID + ' .redux-typography-google').val(true);
            } else {
                $('#' + mainID + ' .redux-typography-google').val(false);
            }
        }

        // Weight and italic
        if (style.indexOf("italic") !== -1) {
            $('#' + mainID + ' .typography-preview').css('font-style', 'italic');
            $('#' + mainID + ' .typography-font-style').val('italic');
            style = style.replace('italic', '');
        } else {
            $('#' + mainID + ' .typography-preview').css('font-style', "normal");
        }
        
        $('#' + mainID + ' .typography-font-weight').val(style);

        if (!height) {
            height = size;
        }

        if (size === '') {
            $('#' + mainID + ' .typography-font-size').val('');
        } else {
            $('#' + mainID + ' .typography-font-size').val(size + units);
        }
        
        if (height === '') {
            $('#' + mainID + ' .typography-line-height').val('');
        } else {
            $('#' + mainID + ' .typography-line-height').val(height + units);
        }
        
        $('#' + mainID + ' .typography-word-spacing').val(word + units);
        $('#' + mainID + ' .typography-letter-spacing').val(letter + units);

        // Show more preview stuff
        if ($('#' + mainID).hasClass('typography-initialized')) {
            var isPreviewSize = $('#' + mainID + ' .typography-preview').data('preview-size');
            
            if (isPreviewSize == '0') {
                $('#' + mainID + ' .typography-preview').css('font-size', size + units);
            }
            
            $('#' + mainID + ' .typography-preview').css('font-weight', style);
            
            //show in the preview box the font
            $('#' + mainID + ' .typography-preview').css('font-family', family + ', sans-serif');
            
            if (family === 'none' && family === '') {
                //if selected is not a font remove style "font-family" at preview box
                $('#' + mainID + ' .typography-preview').css('font-family', 'inherit');
            }
            
            $('#' + mainID + ' .typography-preview').css('line-height', height + units);
            $('#' + mainID + ' .typography-preview').css('word-spacing', word + units);
            $('#' + mainID + ' .typography-preview').css('letter-spacing', letter + units);

            if (color) {
                $('#' + mainID + ' .typography-preview').css('color', color);
                $('#' + mainID + ' .typography-preview').css('background-color', getContrastColour(color));
            }

            $('#' + mainID + ' .redux-typography-font-family').val(output);
            $('#' + mainID + ' .typography-style .select2-chosen').text($('#' + mainID + ' .redux-typography-style option:selected').text());
            $('#' + mainID + ' .typography-script .select2-chosen').text($('#' + mainID + ' .redux-typography-subsets option:selected').text());

            if (align) {
                $('#' + mainID + ' .typography-preview').css('text-align', align);
            }

            if (transform) {
                $('#' + mainID + ' .typography-preview').css('text-transform', transform);
            }

            if (fontVariant) {
                $('#' + mainID + ' .typography-preview').css('font-variant', fontVariant);
            }

            if (decoration) {
                $('#' + mainID + ' .typography-preview').css('text-decoration', decoration);
            }
            $('#' + mainID + ' .typography-preview').slideDown();
        }
        // end preview stuff
        
        // if not preview showing, then set preview to show
        if (!$('#' + mainID).hasClass('typography-initialized')) {
            $('#' + mainID).addClass('typography-initialized');
        }
        
        isSelecting = false;
        
    };
})(jQuery);