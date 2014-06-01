(function($){
    'use strict';

    $.redux = $.redux || {};

    $(document).ready(function(){

        // Load colour picker
        $.redux.color_scheme();

        // get nonce, ajax image, and field id
        var nonce       = $('.redux-color-scheme-container').data('nonce');
        var field_id    = $('.redux-color-scheme-container').data('id');
        var dev_mode    = $('.redux-color-scheme-container').data('dev-mode');
        
        // Add tag to footer, dev mode only.
        if (dev_mode == true) {
            var ver         = $('.redux-color-scheme-container').data('version');
            var dev_html = $('div.redux-timer').html();
            $('div.redux-timer').html(dev_html + '<br/>Color Scheme extension v.' + ver);
        }

        // Auto select text in input box
        $('input.redux-scheme-input-' + field_id).click(function(e){
            this.focus();
            this.select();
            e.preventDefault();
        });

        // Set scheme input box to selected value
        $('input.redux-scheme-input-' + field_id).val($('#redux-scheme-select-' + field_id).val());

        // Select change
        $("#redux-scheme-select-" + field_id).on('change', function() {
            selectChange();
        });

        /*
         * Import scheme
         */
        var myUpload    = $('#redux-' + field_id + '-import-scheme-button');
        var submit      = $('#redux-' + field_id + '-import-scheme-button').data('submit');

        myUpload.upload({
            name:       'file',
            method:     'post',
            action:     submit + 'attachment.php',
            enctype:    'multipart/form-data',
            fileType:   '.json',
            params:     {
                type:       'import',
                field_id:   field_id
            },
            autoSubmit: true,
            onSubmit:   function() {},
            onComplete: function(data) {

                // parse JSON
                data = JSON.parse(data);

                // Set success/fail message
                $('#redux-' + field_id + '-scheme-message-notice h2').html(data['data']);

                // Show message
                $.blockUI({
                    message: $('#redux-' + field_id + '-scheme-message-notice'),
                    theme: false,
                    css: {
                        width: '500px',
                        padding: '5px'
                    }
                });

                // Click OK
                $('#redux-' + field_id + '-scheme-ok').click(function() {

                    // Unload modal
                    $.unblockUI();

                    // Reload window on success
                    if (true === data['result']) {
                        window.onbeforeunload = "";
                        location.reload();
                    }

                    // bail out!
                    return false;
                });
            },

            // unused
            onSelect: function() {}
        });

        /*
         * Delete scheme
         */
        $('#redux-' + field_id + '-delete-scheme-button').click(function(event) {

            // prevent default action
            event.preventDefault();

            // Retrieve selected scheme name
            var select_name = $('#redux-scheme-select-' + field_id).val();

            // Check for 'default', as we don't want to delete it.
            if (select_name.toLowerCase() === 'default'){

                // Set on screen message
                $('#redux-' + field_id + '-scheme-message-notice h2').html('Cannot delete the <strong>Default</strong> scheme, as it is reserved.');

                // Show message
                $.blockUI({
                    message: $('#redux-' + field_id + '-scheme-message-notice'),
                    theme: false,
                    css: {
                        width: '500px',
                        padding: '5px'
                    }
                });

                // Clicked OK
                $('#redux-' + field_id + '-scheme-ok').click(function() {
                    $.unblockUI();
                    return false;
                });

                return false;
            }

            // Load delete question modal
            $.blockUI({
                message: $('#redux-' + field_id + '-delete-scheme-question'),
                theme: false,
                css: {
                    width: '500px',
                    padding: '5px'
                }
            });

            // Clicked yes
            $('#redux-' + field_id + '-delete-scheme-yes').click(function() {

                // Unload question dialog
                //$.unblockUI();

                // If selected scheme name is valid...
                if (select_name) {

                    // set ajax parameters
                    var data = {
                        action:     'redux_color_schemes',
                        nonce:      nonce,
                        type:       'delete',
                        scheme_id:  select_name,
                        field_id:   field_id
                    };

                    // Load Please wait message
                    var wait_msg = $('#redux-' + field_id + '-scheme-wait-message h1').html();
                    $.blockUI({ 
                        message: '<h1>' +  wait_msg + 'deleting scheme.<h1/>',
                        theme: false,
                        css: {
                            width: '500px',
                            padding: '5px'
                        }
                    });

                    // Post ajax
                    $.post(redux_ajax_script.ajaxurl, data, function(response) {
                        
                        // Unload waiting modal
                        //$.unblockUI();
                        
                        // Successful delete
                        if (response === "success") {

                            // Remove deleted scheme from selector
                            $("#redux-scheme-select-" + field_id + " option[value='" + select_name + "']").remove();

                            // Clear selector (default)
                            $("#redux-scheme-select-" + field_id).find('option:contains("Default")').attr("selected",true);

                            // clear input box
                            $('input.redux-scheme-input-' + field_id).val('');

                            // Update modal message text
                            $('#redux-' + field_id + '-scheme-message-notice h2').html('The <strong>' + select_name + '</strong> scheme has been removed.<br/><br/>The color table has been reset to default values.');

                            // Display the notice
                            $.blockUI({ 
                                message: $('#redux-' + field_id + '-scheme-message-notice'), 
                                theme: false, 
                                css: { 
                                    width: '500px', 
                                    padding: '5px' 
                                }
                            });
                            
                            // Click OK, unload msg, bail out.
                            $('#redux-' + field_id + '-scheme-ok').click(function() {
                                $.unblockUI();
                                
                                // update the HTML preview
                                updateSchemeHTML(0);
                                
                                return false;
                            });
                        } else {
                            // Update modal message with fail response.
                            $('#redux-' + field_id + '-scheme-message-notice h2').html('Delete failed: ' + response);

                            // Display the notice
                            $.blockUI({ 
                                message: $('#redux-' + field_id + '-scheme-message-notice'), 
                                theme: false, 
                                css: { 
                                    width: '500px', 
                                    padding: '5px' 
                                }
                            });

                            // Click OK, unload msg, bail out.
                            $('#redux-' + field_id + '-scheme-ok').click(function() {
                                $.unblockUI();
                                return false;
                            });
                        }
                    });
                }
            });

            // Clicked no
            $('#redux-' + field_id + '-delete-scheme-no').click(function() {
                $.unblockUI();
                return false;
            });
        });

        /*
         * Save Scheme
         */
        $('#redux-' + field_id + '-save-scheme-button').click(function(event) {
            event.preventDefault();

            // prevent blank input
            var input_text = $('input.redux-scheme-input-' + field_id).val();

            // trim
            input_text = input_text.replace(/^\s+|\s+$/gm,'');

            // allow only alphanumeric, space, dash, and underscore
            input_text = input_text.replace(/[^a-z0-9\s\_\-]/gi, '');

            // Do nothing if input_text is empty.
            if (input_text === '') {
                return false
            }

            // Check for default scheme
            if (input_text.toLowerCase() === 'default') {
                
                // Update notice message
                $('#redux-' + field_id + '-scheme-message-notice h2').html('The name <strong>Default</strong> cannot be used as it is reserved.');
                
                // Display msg.
                $.blockUI({
                    message: $('#redux-' + field_id + '-scheme-message-notice'),
                    theme: false,
                    css: {
                        width: '500px',
                        padding: '5px'
                    }
                });

                // Clicked OK.  Close message and exit
                $('#redux-' + field_id + '-scheme-ok').click(function() {
                    $.unblockUI();
                    return false;
                });

                // Bail out.
                return false;
            }

            // Create color picker data array
            var arrData = [];

            // Enum through them all and collect data
            $('.redux-scheme-layout-container').each(function() {
                var title       = $(this).children('.redux-color-scheme').data('title');
                var id          = $(this).children('.redux-color-scheme').data('id');
                var color       = $(this).children('.redux-hidden-color').attr('value');
                var alpha       = $(this).children('.redux-hidden-alpha').attr('value');
                var selector    = $(this).children('.redux-hidden-selector').attr('value');
                var mode        = $(this).children('.redux-hidden-mode').attr('value');
                var important   = $(this).children('.redux-hidden-important').attr('value');

                // Push data into the array
                arrData.push({
                    id:         id,
                    title:      title,
                    color:      color,
                    alpha:      alpha,
                    selector:   selector,
                    mode:       mode,
                    important:  important
                });
            });

            // Get scheme name from text box
            var scheme_name = $(".redux-scheme-input-" + field_id).val();

            // If one exists, proceed
            if (scheme_name) {

                // Set ajax parameters
                var data = {
                    action:         'redux_color_schemes',
                    nonce:          nonce,
                    type:           'save',
                    scheme_name:    scheme_name,
                    scheme_data:    arrData,
                    field_id:       field_id
                };

                // Get default wait message.
                var wait_msg = $('#redux-' + field_id + '-scheme-wait-message h1').html();
                
                // Load wait message
                $.blockUI({ message: '<h1>' + wait_msg + 'saving scheme.</h1>',
                    theme: false,
                    css: {
                        width: '500px',
                        padding: '5px'
                    }
                });

                // post ajax
                $.post(redux_ajax_script.ajaxurl, data, function(response) {

                    // Replace selector with updated values
                    $("#redux-scheme-select-" + field_id).replaceWith(response);

                    // New selector change hook.
                    $("#redux-scheme-select-" + field_id).on('change', function() {
                        selectChange();
                    });

                    // Update notice message
                    $('#redux-' + field_id + '-scheme-message-notice h2').html('The scheme <strong>' + scheme_name + '</strong> has been added to your scheme list.');

                    // Display notice message
                    $.blockUI({
                        message: $('#redux-' + field_id + '-scheme-message-notice'),
                        theme: false,
                        css: {
                            width: '500px',
                            padding: '5px'
                        }
                    });

                    // Clicked OK.  Unload and exit.
                    $('#redux-' + field_id + '-scheme-ok').click(function() {
                        $.unblockUI();
                        return false;
                    });
                });
            }
        });

        /*
         * Select Change
         */
        function selectChange(){
            // fade out the colour pickers
            $("ul.redux-scheme-layout").fadeOut();

            // Get selected value
            var selected = $("#redux-scheme-select-" + field_id).val();

            // Remove default blank value, if any
            $("#redux-scheme-select-" + field_id + " option[value='']").remove();

            // Get scheme name from input box
            $('input.redux-scheme-input-' + field_id).val(selected);

            // Update colour pickers
            updateSchemeHTML(selected);

        }

        /*
         * Update color pickers
         */
        function updateSchemeHTML(selected) {

            // Get default wait msg.
            var wait_msg = $('#redux-' + field_id + '-scheme-wait-message h1').html();

            // Display appended msg.
            $.blockUI({
                message: '<h1>' + wait_msg + 'updating scheme.</h1>',
                theme: false,
                css: {
                    width: '500px',
                    padding: '5px'
                }
            });
            
            // Get field class.  Needed for custom classes from field array
            var field_class = $('.redux-color-scheme').attr('class');

            // Set ajax parameters
            var data = {
                action:         'redux_color_schemes',
                nonce:          nonce,
                type:           'update',
                scheme_id:      selected,
                field_id:       field_id,
                field_class:    field_class
            };

            // Post ajax
            $.post(redux_ajax_script.ajaxurl, data, function(response) {

                // Replace colour picker layout
                $("ul.redux-scheme-layout").replaceWith(response);

                // Re-init colour pickers.
                $.redux.color_scheme();

                // Fade colour pickers back in
                $("ul.redux-scheme-layout").fadeIn();
                
                // Remove waiting msg.
                $.unblockUI();
                
                // Set flags for compiler.
                redux_change($(this));
            });
        }
    });             // document ready

    // Initialize colour picker
    $.redux.color_scheme = function(){
        
        // Get field ID
        var field_id    = $('.redux-color-scheme-container').data('id');

        // Get the color scheme container
        var colorpickerInput = $('.redux-color-scheme');

        // Get alpha value and sanitize it
        var currentAlpha    = colorpickerInput.data('current-alpha');
        currentAlpha        = Number((currentAlpha === null || currentAlpha === undefined) ? 1 : currentAlpha);

        // Get colour value and sanitize it
        var currentColor    = colorpickerInput.data('current-color');
        currentColor        = (currentColor === '') ? '' : currentColor;

        // Color picker arguments
        var container   = $('.redux-color-scheme-container');

        // Get, decode and parse palette.
        var palette = container.data('palette');
        palette     = decodeURIComponent(palette);
        palette     = JSON.parse(palette);

        // Default palette
        if (palette === null) {
            palette = [
                ["#000000", "#434343", "#666666", "#999999", "#b7b7b7", "#cccccc", "#d9d9d9", "#efefef", "#f3f3f3", "#ffffff"],
                ["#980000", "#ff0000", "#ff9900", "#ffff00", "#00ff00", "#00ffff", "#4a86e8", "#0000ff", "#9900ff", "#ff00ff"],
                ["#e6b8af", "#f4cccc", "#fce5cd", "#fff2cc", "#d9ead3", "#d9ead3", "#c9daf8", "#cfe2f3", "#d9d2e9", "#ead1dc"],
                ["#dd7e6b", "#ea9999", "#f9cb9c", "#ffe599", "#b6d7a8", "#a2c4c9", "#a4c2f4", "#9fc5e8", "#b4a7d6", "#d5a6bd"],
                ["#cc4125", "#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6d9eeb", "#6fa8dc", "#8e7cc3", "#c27ba0"],
                ["#a61c00", "#cc0000", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3c78d8", "#3d85c6", "#674ea7", "#a64d79"],
                ["#85200c", "#990000", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#1155cc", "#0b5394", "#351c75", "#741b47"],
                ["#5b0f00", "#660000", "#783f04", "#7f6000", "#274e13", "#0c343d", "#1c4587", "#073763", "#20124d", "#4c1130"]
            ];
        }

        // Get and sanitize show input argument
        var showInput               = container.data('show-input');
        showInput                   = Boolean((showInput == '') ? false : showInput);

        // Get and sanitize show initial argument
        var showInitial             = container.data('show-initial');
        showInitial                 = Boolean((showInitial == '') ? false : showInitial);

        // Get and sanitize show alpha argument
        var showAlpha               = container.data('show-alpha');
        showAlpha                   = Boolean((showAlpha == '') ? false : showAlpha);

        // Get and sanitize allow empty argument
        var allowEmpty              = container.data('allow-empty');
        allowEmpty                  = Boolean((allowEmpty == '') ? false : allowEmpty);

        // Get and sanitize show palette argument
        var showPalette             = container.data('show-palette');
        showPalette                 = Boolean((showPalette == '') ? false : showPalette);

        // Get and sanitize show palette only argument
        var showPaletteOnly         = container.data('show-palette-only');
        showPaletteOnly             = Boolean((showPaletteOnly == '') ? false : showPaletteOnly);

        // Get and sanitize show selection palette argument
        var showSelectionPalette    = container.data('show-selection-palette');
        showSelectionPalette        = Boolean((showSelectionPalette == '') ? false : showSelectionPalette);

        // Get max palette size
        var maxPaletteSize          = Number(container.data('max-palette-size'));

        // Get and sanitize clickout fires change argument
        var clickoutFiresChange     = container.data('clickout-fires-change');
        clickoutFiresChange         = Boolean((clickoutFiresChange == '') ? false : clickoutFiresChange);

        // Get choose button text
        var chooseText              = String(container.data('choose-text'));

        // Get cancel button text
        var cancelText              = String(container.data('cancel-text'));

        // Get and sanitize show buttons argument
        var showButtons             = container.data('show-buttons');
        showButtons                 = Boolean((showButtons == '') ? false : showButtons);

        // Get container class
        var containerClass          = String(container.data('container-class'));

        // Get replacer class
        var replacerClass           = String(container.data('replacer-class'));

        // Color picker options
        colorpickerInput.spectrum({
            color:                  '#ffffff',
            showAlpha:              showAlpha,
            showInput:              showInput,
            allowEmpty:             allowEmpty,
            className:              'redux-full-spectrum',
            showInitial:            showInitial,
            showPalette:            showPalette,
            showSelectionPalette:   showSelectionPalette,
            maxPaletteSize:         maxPaletteSize,
            showPaletteOnly:        showPaletteOnly,
            clickoutFiresChange:    clickoutFiresChange,
            chooseText:             chooseText,
            cancelText:             cancelText,
            showButtons:            showButtons,
            containerClassName:     containerClass,
            replacerClassName:      replacerClass,
            preferredFormat:        'hex6',
            localStorageKey:        'redux.spectrum.' + field_id,
            palette:                palette,

            // on change
            change: function(color) {
                var colorVal, alphaVal;

                if (color === null) {
                    colorVal = null;
                    alphaVal = null;
                } else {
                    colorVal = color.toHexString();
                    alphaVal = color.alpha;
                }

                // Update HTML color value
                $('input#' + $(this).data('block-id') + '-color').val(colorVal);

                // Update HTML alpha value
                $('input#' + $(this).data('block-id') + '-alpha').val(alphaVal);

                //redux_change($(this));
                //console.log($(this).closest('label').find('input[type="text"]'));
                redux_change($(this).parents('.redux-field-container:first').find('input'));
            },

            // on move (unsed?)
            move: function (color) {
            },

            // on show (unused?)
            show: function () {

            },

            // before show (unused?)
            beforeShow: function () {

            },

            // on hide (unused?)
            hide: function (color) {
            },
        });
    };
})(jQuery);

