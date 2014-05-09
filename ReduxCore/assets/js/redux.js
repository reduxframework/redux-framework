/* global jQuery, document, redux, redux.args, confirm, relid:true, console, jsonView */

(function($) {
    'use strict';

    $.redux = $.redux || {};

    $(document).ready(function() {
        // Intense debug  ;)
        //jQuery('input[type="hidden"]').attr("type","text");
        //console.log(redux);

        jQuery.fn.isOnScreen = function() {
            if (!window) {
                return;
            }

            var win = jQuery(window);
            var viewport = {
                top:    win.scrollTop(),
                left:   win.scrollLeft()
            };

            viewport.right  = viewport.left + win.width();
            viewport.bottom = viewport.top + win.height();

            var bounds = this.offset();

            bounds.right    = bounds.left + this.outerWidth();
            bounds.bottom   = bounds.top + this.outerHeight();

            return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
        };

        $.redux.required();

        $("body").on('change', '.redux-main select, .redux-main radio, .redux-main input[type=checkbox], .redux-main input[type=hidden]', function(e) {
            $.redux.check_dependencies(this);
        });

        $("body").on('check_dependencies', function(e, variable) {
            $.redux.check_dependencies(variable);
        });

        $('td > fieldset:empty,td > div:empty').parent().parent().hide();
    });

    $.redux.required = function() {

        // Hide the fold elements on load ,
        // It's better to do this by PHP but there is no filter in tr tag , so is not possible
        // we going to move each attributes we may need for folding to tr tag
        $.each(redux.folds, function(i, v) {
            var fieldset = $('#' + redux.args.opt_name + '-' + i);

            fieldset.parents('tr:first').addClass('fold');

            if (v == "hide") {
                fieldset.parents('tr:first').addClass('hide');

                if (fieldset.hasClass('redux-container-section')) {
                    var div = $('#section-' + i);
                    if (div.hasClass('redux-section-indent-start')) {
                        $('#section-table-' + i).hide().addClass('hide');
                        div.hide().addClass('hide');
                    }
                }

                if (fieldset.hasClass('redux-container-info')) {
                    $('#info-' + i).hide().addClass('hide');
                }

                if (fieldset.hasClass('redux-container-divide')) {
                    $('#divide-' + i).hide().addClass('hide');
                }
            }
        });
    };

    $.redux.get_container_value = function(id) {
        var value = $('#' + redux.args.opt_name + '-' + id).serializeForm();

        if (value !== null && typeof value === 'object' && value.hasOwnProperty(redux.args.opt_name)) {
            //console.log('object');
            //console.log(value);
            value = value[redux.args.opt_name][id];
        }
        //console.log(value);
        return value;
    };

    $.redux.check_dependencies = function(variable) {
        if (redux.required === null) {
            return;
        }
        var current = $(variable),
            id = current.parents('.redux-field:first').data('id');

        if (!redux.required.hasOwnProperty(id)) {
            return;
        }

        var container   = current.parents('.redux-field-container:first'),
            is_hidden   = container.parents('tr:first').hasClass('.hide'),
            hadSections = false;

        $.each(redux.required[id], function(child, dependents) {

            var current         = $(this),
                show            = false,
                childFieldset   = $('#' + redux.args.opt_name + '-' + child),
                tr              = childFieldset.parents('tr:first');

            if (!is_hidden) {
                show = $.redux.check_parents_dependencies(child);
            }

            if (show === true) {
                // Shim for sections
                if (childFieldset.hasClass('redux-container-section')) {
                    var div = $('#section-' + child);

                    if (div.hasClass('redux-section-indent-start') && div.hasClass('hide')) {
                        $('#section-table-' + child).fadeIn(300).removeClass('hide');
                        div.fadeIn(300).removeClass('hide');
                    }
                }

                if (childFieldset.hasClass('redux-container-info')) {
                    $('#info-' + child).fadeIn(300).removeClass('hide');
                }

                if (childFieldset.hasClass('redux-container-divide')) {
                    $('#divide-' + child).fadeIn(300).removeClass('hide');
                }

                tr.fadeIn(300, function() {
                    jQuery(this).removeClass('hide');
                    if (redux.required.hasOwnProperty(child)) {
                        $.redux.check_dependencies($('#' + redux.args.opt_name + '-' + child).children().first());
                    }
                });
            } else if (show === false) {
                tr.fadeOut(100, function() {
                    jQuery(this).addClass('hide');
                    if (redux.required.hasOwnProperty(child)) {
                        //console.log('Now check, reverse: '+child);
                        $.redux.required_recursive_hide(child);
                    }
                });
            }

            current.find('select, radio, input[type=checkbox]').trigger('change');
        });
    };

    $.redux.required_recursive_hide = function(id) {
        var toFade = $('#' + redux.args.opt_name + '-' + id).parents('tr:first');

        toFade.fadeOut(50, function() {
            jQuery(this).addClass('hide');

            if ($('#' + redux.args.opt_name + '-' + id).hasClass('redux-container-section')) {
                var div = $('#section-' + id);
                if (div.hasClass('redux-section-indent-start')) {
                    $('#section-table-' + id).fadeOut(50).addClass('hide');
                    div.fadeOut(50).addClass('hide');
                }
            }

            if ($('#' + redux.args.opt_name + '-' + id).hasClass('redux-container-info')) {
                $('#info-' + id).fadeOut(50).addClass('hide');
            }

            if ($('#' + redux.args.opt_name + '-' + id).hasClass('redux-container-divide')) {
                $('#divide-' + id).fadeOut(50).addClass('hide');
            }

            if (redux.required.hasOwnProperty(id)) {
                $.each(redux.required[id], function(child) {
                    $.redux.required_recursive_hide(child);
                });
            }
        });
    };

    $.redux.check_parents_dependencies = function(id) {
        var show = "";

        if (redux.required_child.hasOwnProperty(id)) {
            $.each(redux.required_child[id], function(i, parentData) {
                if ($('#' + redux.args.opt_name + '-' + parentData.parent).parents('tr:first').hasClass('.hide')) {
                    show = false;
                } else {
                    if (show !== false) {
                        var parentValue = $.redux.get_container_value(parentData.parent);
                        show = $.redux.check_dependencies_visibility(parentValue, parentData);
                    }
                }
            });
        } else {
            show = true;
        }
        return show;
    };

    $.redux.check_dependencies_visibility = function(parentValue, data) {
        var show        = false,
            checkValue_array,
            checkValue  = data.checkValue,
            operation   = data.operation;

        switch (operation) {
            case '=':
            case 'equals':
                //if value was array
                if ($.isArray(checkValue)) {
                    if ($.inArray(parentValue, checkValue) != -1) {
                        show = true;
                    }
                } else {
                    if (parentValue == checkValue) {
                        show = true;
                    } else if ($.isArray(parentValue)) {
                        if ($.inArray(checkValue, parentValue) != -1) {
                            show = true;
                        }
                    }
                }
                break;
            case '!=':
            case 'not':
                //if value was array
                if ($.isArray(checkValue)) {
                    //if (checkValue.toString().indexOf('|') !== -1) {
                    //    checkValue_array = checkValue.split('|');
                    if ($.inArray(parentValue, checkValue) == -1) {
                        show = true;
                    }
                } else {
                    if (parentValue != checkValue) {
                        show = true;
                    } else if ($.isArray(parentValue)) {
                        if ($.inArray(checkValue, parentValue) == -1) {
                            show = true;
                        }
                    }
                }
                break;
            case '>':
            case 'greater':
            case 'is_larger':
                if (parseFloat(parentValue) > parseFloat(checkValue))
                    show = true;
                break;
            case '>=':
            case 'greater_equal':
            case 'is_larger_equal':
                if (parseFloat(parentValue) >= parseFloat(checkValue))
                    show = true;
                break;
            case '<':
            case 'less':
            case 'is_smaller':
                if (parseFloat(parentValue) < parseFloat(checkValue))
                    show = true;
                break;
            case '<=':
            case 'less_equal':
            case 'is_smaller_equal':
                if (parseFloat(parentValue) <= parseFloat(checkValue))
                    show = true;
                break;
            case 'contains':
                if (parentValue.toString().indexOf(checkValue) != -1)
                    show = true;
                break;
            case 'doesnt_contain':
            case 'not_contain':
                if (parentValue.toString().indexOf(checkValue) == -1)
                    show = true;
                break;
            case 'is_empty_or':
                if (parentValue === "" || parentValue == checkValue)
                    show = true;
                break;
            case 'not_empty_and':
                if (parentValue !== "" && parentValue != checkValue)
                    show = true;
                break;
            case 'is_empty':
            case 'empty':
            case '!isset':
                if (!parentValue || parentValue === "" || parentValue === null)
                    show = true;
                break;
            case 'not_empty':
            case '!empty':
            case 'isset':
                if (parentValue && parentValue !== "" && parentValue !== null)
                    show = true;
                break;
        }
        return show;

    };

})(jQuery);

jQuery.noConflict();

var confirmOnPageExit = function(e) {
    //return; // ONLY FOR DEBUGGING
    // If we haven't been passed the event get the window.event
    e = e || window.event;

    var message = redux.args.save_pending;

    // For IE6-8 and Firefox prior to version 4
    if (e) {
        e.returnValue = message;
    }

    window.onbeforeunload = null;

    // For Chrome, Safari, IE8+ and Opera 12+
    return message;
};

function verifyPos(s, b) {

    // trim off spaces
    s = s.replace(/^\s+|\s+$/gm, '');

    // position value is blank, set the default
    if (s === '' || s.search(' ') == -1) {
        if (b === true) {
            return 'top left';
        } else {
            return 'bottom right';
        }
    }

    // split string into array
    var split = s.split(' ');

    // Evaluate first string.  Must be top, center, or bottom
    var paramOne = b ? 'top' : 'bottom';
    if (split[0] == 'top' || split[0] == 'center' || split[0] == 'bottom') {
        paramOne = split[0];
    }

    // Evaluate second string.  Must be left, center, or right.
    var paramTwo = b ? 'left' : 'right';
    if (split[1] == 'left' || split[1] == 'center' || split[1] == 'right') {
        paramTwo = split[1];
    }

    return paramOne + ' ' + paramTwo;
}

function getContrastColour(hexcolour) {
    // default value is black.
    retVal = '#444444';

    // In case - for some reason - a blank value is passed.
    // This should *not* happen.  If a function passing a value
    // is canceled, it should pass the current value instead of
    // a blank.  This is how the Windows Common Controls do it.  :P
    if (hexcolour !== '') {

        // Replace the hash with a blank.
        hexcolour = hexcolour.replace('#', '');

        var r = parseInt(hexcolour.substr(0, 2), 16);
        var g = parseInt(hexcolour.substr(2, 2), 16);
        var b = parseInt(hexcolour.substr(4, 2), 16);
        var res = ((r * 299) + (g * 587) + (b * 114)) / 1000;

        // Instead of pure black, I opted to use WP 3.8 black, so it looks uniform.  :) - kp
        retVal = (res >= 128) ? '#444444' : '#ffffff';
    }

    return retVal;
}

function verify_fold(item) {

    jQuery(document).ready(function($) {
        //console.log(verify_fold);

        if (item.hasClass('redux-info') || item.hasClass('redux-typography')) {
            return;
        }

        var id      = item.parents('.redux-field:first').data('id');
        var itemVal = item.val();

        if (redux.folds[ id ]) {

            /*
             if ( redux.folds[ id ].parent && jQuery( '#' + redux.folds[ id ].parent ).is('hidden') ) {
             console.log('Going to parent: '+redux.folds[ id ].parent+' for field: '+id);
             //verify_fold( jQuery( '#' + redux.folds[ id ].parent ) );
             }
             */
            if (redux.folds[ id ].children) {
                //console.log('Children for: '+id);

                var theChildren = {};
                $.each(redux.folds[ id ].children, function(index, value) {
                    $.each(value, function(index2, value2) { // Each of the children for this value
                        if (!theChildren[value2]) { // Create an object if it's not there
                            theChildren[value2] = {show: false, hidden: false};
                        }

                        //console.log('id: '+id+' childID: '+value2+' parent value: '+index+' itemVal: '+itemVal);

                        if (index == itemVal || theChildren[value2] === true) { // Check to see if it's in the criteria
                            theChildren[value2].show = true;
                            //console.log('theChildren['+value2+'].show = true');
                        }

                        if (theChildren[value2].show === true && jQuery('#' + id).parents("tr:first").hasClass("hiddenFold")) {
                            theChildren[value2].show = false; // If this item is hidden, hide this child
                            //console.log('set '+value2+' false');
                        }

                        if (theChildren[value2].show === true && jQuery('#' + redux.folds[ id ].parent).hasClass('hiddenFold')) {
                            theChildren[value2].show = false; // If the parent of the item is hidden, hide this child
                            //console.log('set '+value2+' false2');
                        }

                        // Current visibility of this child node
                        theChildren[value2].hidden = jQuery('#' + value2).parents("tr:first").hasClass("hiddenFold");
                    });
                });

                //console.log(theChildren);

                $.each(theChildren, function(index) {
                    var parent = jQuery('#' + index).parents("tr:first");

                    if (theChildren[index].show === true) {
                        //console.log('FadeIn '+index);

                        parent.fadeIn('medium', function() {
                            parent.removeClass('hiddenFold');
                            if (redux.folds[ index ] && redux.folds[ index ].children) {
                                //verify_fold(jQuery('#'+index)); // Now iterate the children
                            }
                        });

                    } else if (theChildren[index].hidden === false) {
                        //console.log('FadeOut '+index);

                        parent.fadeOut('medium', function() {
                            parent.addClass('hiddenFold');
                            if (redux.folds[ index ].children) {
                                //verify_fold(jQuery('#'+index)); // Now iterate the children
                            }
                        });
                    }
                });
            }
        }
    });
}

function redux_change(variable) {
    //We need this for switch and image select fields , jquery dosn't catch it on fly
    //if(variable.is('input[type=hidden]') || variable.hasClass('spinner-input') || variable.hasClass('slider-input') || variable.hasClass('upload') || jQuery(variable).parents('fieldset:eq(0)').is('.redux-container-image_select') ) {

    jQuery('body').trigger('check_dependencies', variable);
    //}

    if (variable.hasClass('compiler')) {
        jQuery('#redux-compiler-hook').val(1);
        //console.log('Compiler init');
    }


    if (variable.hasClass('foldParent')) {
        //verify_fold(variable);
    }

    window.onbeforeunload = confirmOnPageExit;

    var rContainer = jQuery(variable).parents('.redux-container:first');

    if (jQuery(variable).parents('fieldset.redux-field:first').hasClass('redux-field-error')) {
        jQuery(variable).parents('fieldset.redux-field:first').removeClass('redux-field-error');
        jQuery(variable).parent().find('.redux-th-error').slideUp();

        var parentID    = jQuery(variable).closest('.redux-group-tab').attr('id');


        var errorCount = ( parseInt( rContainer.find( '.redux-field-errors span').text() ) - 1 );
        var warningCount = ( parseInt( rContainer.find( '.redux-field-warnings span').text() ) - 1 );
        console.log(parentID + ' - ' + errorCount);
        if (errorCount <= 0) {
            console.log('HERE');
            jQuery('#' + parentID + '_li .redux-menu-error').fadeOut('fast').remove();
            jQuery('#' + parentID + '_li .redux-group-tab-link-a').removeClass('hasError');

            //
            jQuery('#' + parentID + '_li').parents('.inside:first').find('.redux-field-errors').slideUp();
            jQuery(variable).parents('.redux-container:first').find('.redux-field-errors').slideUp();
            jQuery('#redux_metaboxes_errors').slideUp();
        } else {
            // Let's count down the errors now. Fancy.  ;)
            var id = parentID.split('_');
            id = id[0];


            var th = rContainer.find('.redux-group-tab-link-a[data-key="'+id+'"]').parents('.redux-group-tab-link-li:first');

            var errorsLeft = ( parseInt( th.find('.redux-menu-error:first').text() ) - 1 );
            if ( errorsLeft <= 0 ) {
                th.find('.redux-menu-error:first').fadeOut().remove();
            } else {
                th.find('.redux-menu-error:first').text( errorsLeft );
            }

            var warningsLeft = ( parseInt( th.find('.redux-menu-warning:first').text() ) - 1 );
            if ( warningsLeft <= 0 ) {
                th.find('.redux-menu-warning:first').fadeOut().remove();
            } else {
                th.find('.redux-menu-warning:first').text( warningsLeft );
            }

            rContainer.find( '.redux-field-errors span').text( errorCount );
            rContainer.find( '.redux-field-warning span').text( warningCount );

        }
        var subParent = jQuery('#' + parentID + '_li').parents('.hasSubSections:first');
        if ( subParent.length !== 0 ) {
            if ( subParent.find('.redux-menu-error').length === 0 ) {
                subParent.find('.hasError').removeClass('hasError');
            }
        }
    }
    if ( !redux.args.disable_save_warn ) {
        rContainer.find('.redux-save-warn').slideDown();
    }
}

jQuery(document).ready(function($) {
    jQuery('.redux-action_bar, .redux-presets-bar').on('click', function() {
        window.onbeforeunload = null;
    });

    if (jQuery().qtip) {
        // Shadow
        var shadow      = '';
        var tip_shadow  = redux.args.hints.tip_style.shadow;

        if (tip_shadow === true) {
            shadow = 'qtip-shadow';
        }

        // Color
        var color       = '';
        var tip_color   = redux.args.hints.tip_style.color;

        if (tip_color !== '') {
            color = 'qtip-' + tip_color;
        }

        // Rounded
        var rounded     = '';
        var tip_rounded = redux.args.hints.tip_style.rounded;

        if (tip_rounded === true) {
            rounded = 'qtip-rounded';
        }

        // Tip style
        var style       = '';
        var tip_style   = redux.args.hints.tip_style.style;

        if (tip_style !== '') {
            style = 'qtip-' + tip_style;
        }

        var classes = shadow + ',' + color + ',' + rounded + ',' + style;
        classes     = classes.replace(/,/g, ' ');

        // Get position data
        var myPos = redux.args.hints.tip_position.my;
        var atPos = redux.args.hints.tip_position.at;

        // Gotta be lowercase, and in proper format
        myPos = verifyPos(myPos.toLowerCase(), true);
        atPos = verifyPos(atPos.toLowerCase(), false);

        // Tooltip trigger action
        var showEvent = redux.args.hints.tip_effect.show.event;
        var hideEvent = redux.args.hints.tip_effect.hide.event;

        // Tip show effect
        var tipShowEffect   = redux.args.hints.tip_effect.show.effect;
        var tipShowDuration = redux.args.hints.tip_effect.show.duration;

        // Tip hide effect
        var tipHideEffect   = redux.args.hints.tip_effect.hide.effect;
        var tipHideDuration = redux.args.hints.tip_effect.hide.duration;

        $('div.redux-qtip').each(function() {
            $(this).qtip({
                content: {
                    text:   $(this).attr('qtip-content'),
                    title:  $(this).attr('qtip-title')
                },
                show: {
                    effect: function() {
                        switch (tipShowEffect) {
                            case 'slide':
                                $(this).slideDown(tipShowDuration);
                                break;
                            case 'fade':
                                $(this).fadeIn(tipShowDuration);
                                break;
                            default:
                                $(this).show();
                                break;
                        }
                    },
                    event: showEvent,
                },
                hide: {
                    effect: function() {
                        switch (tipHideEffect) {
                            case 'slide':
                                $(this).slideUp(tipHideDuration);
                                break;
                            case 'fade':
                                $(this).fadeOut(tipHideDuration);
                                break;
                            default:
                                $(this).show(tipHideDuration);
                                break;
                        }
                    },
                    event: hideEvent,
                },
                style: {
                    classes: classes,
                },
                position: {
                    my: myPos,
                    at: atPos,
                },
            });
        });
        // });

        $('input[qtip-content]').each(function() {
            $(this).qtip({
                content: {
                    text:   $(this).attr('qtip-content'),
                    title:  $(this).attr('qtip-title')
                },
                show:   'focus',
                hide:   'blur',
                style:  classes,
                position: {
                    my: myPos,
                    at: atPos,
                },
            });
        });
    }

    $('#toplevel_page_' + redux.args.slug + ' .wp-submenu a, #wp-admin-bar-' + redux.args.slug + ' a.ab-item').click(function(e) {
        if ($('#toplevel_page_' + redux.args.slug).hasClass('wp-menu-open') || $(this).hasClass('ab-item')) {
            e.preventDefault();

            var url = $(this).attr('href').split('&tab=');

            $('#' + url[1] + '_section_group_li_a').click();
            return false;
        }
    });

    /**
     Current tab checks, based on cookies
     **/
    jQuery('.redux-group-tab-link-a').click(function() {
        var relid = jQuery(this).data('rel'); // The group ID of interest
        var oldid = jQuery('.redux-group-tab-link-li.active .redux-group-tab-link-a').data('rel');

        if (oldid === relid) {
            return;
        }
        jQuery('#currentSection').val(relid);
        if (!$(this).parents('.postbox-container:first').length) {
            // Set the proper page cookie
            $.cookie('redux_current_tab', relid, {
                expires:    7,
                path:       '/'
            });
        }

        if (jQuery('#' + relid + '_section_group_li').parents('.redux-group-tab-link-li').length) {
            var parentID = jQuery('#' + relid + '_section_group_li').parents('.redux-group-tab-link-li').attr('id').split('_');
            parentID = parentID[0];
        }

        $('#toplevel_page_' + redux.args.slug + ' .wp-submenu a.current').removeClass('current');
        $('#toplevel_page_' + redux.args.slug + ' .wp-submenu li.current').removeClass('current');

        $('#toplevel_page_' + redux.args.slug + ' .wp-submenu a').each(function() {
            var url = $(this).attr('href').split('&tab=');
            if (url[1] == relid || url[1] == parentID) {
                $(this).addClass('current');
                $(this).parent().addClass('current');
            }
        });

        /**
         if (RELID is child of oldid) {
         oldid activeChild, relid active
         } else if (RELID is sibling of OLDID) {
         remove active of old id, add to relid
         } else {
         if relid is parent {
         slidedown realid
         }
         if oldid is parent {
         slideup oldid
         } else if oldid is child {
         slideup oldid parent and remove class
         } else {
         normal oldid remove active
         }
         }
         **/

        if (jQuery('#' + oldid + '_section_group_li').find('#' + oldid + '_section_group_li').length) {
            //console.log('RELID is child of oldid');
            jQuery('#' + oldid + '_section_group_li').addClass('activeChild');
            jQuery('#' + relid + '_section_group_li').addClass('active').removeClass('activeChild');
        } else if (jQuery('#' + relid + '_section_group_li').parents('#' + oldid + '_section_group_li').length || jQuery('#' + oldid + '_section_group_li').parents('ul.subsection').find('#' + relid + '_section_group_li').length) {
            //console.log('RELID is sibling or child of OLDID');
            if (jQuery('#' + relid + '_section_group_li').parents('#' + oldid + '_section_group_li').length) {
                //console.log('child of oldid');
                jQuery('#' + oldid + '_section_group_li').addClass('activeChild').removeClass('active');
            } else {
                //console.log('sibling');
                jQuery('#' + relid + '_section_group_li').addClass('active');
                jQuery('#' + oldid + '_section_group_li').removeClass('active');
            }
            jQuery('#' + relid + '_section_group_li').removeClass('activeChild').addClass('active');
        } else {
            jQuery('#' + relid + '_section_group_li').addClass('active').removeClass('activeChild').find('ul.subsection').slideDown();
            if (jQuery('#' + oldid + '_section_group_li').find('ul.subsection').length) {
                //console.log('oldid is parent')
                jQuery('#' + oldid + '_section_group_li').find('ul.subsection').slideUp('fast', function() {
                    jQuery('#' + oldid + '_section_group_li').removeClass('active').removeClass('activeChild');
                });
            } else if (jQuery('#' + oldid + '_section_group_li').parents('ul.subsection').length) {
                //console.log('oldid is a child');
                if (!jQuery('#' + oldid + '_section_group_li').parents('#' + relid + '_section_group_li').length) {
                    //console.log('oldid is child, but not of relid');
                    jQuery('#' + oldid + '_section_group_li').parents('ul.subsection').slideUp('fast', function() {
                        jQuery('#' + oldid + '_section_group_li').removeClass('active');
                        jQuery('#' + oldid + '_section_group_li').parents('.redux-group-tab-link-li').removeClass('active').removeClass('activeChild');
                    });
                } else {
                    jQuery('#' + oldid + '_section_group_li').removeClass('active');
                }

            } else {
                //console.log('Normal remove active from child');
                jQuery('#' + oldid + '_section_group_li').removeClass('active');
                if (jQuery('#' + relid + '_section_group_li').parents('.redux-group-tab-link-li').length) {
                    //console.log('here');
                    jQuery('#' + relid + '_section_group_li').parents('.redux-group-tab-link-li').addClass('activeChild').find('ul.subsection').slideDown();
                    jQuery('#' + relid + '_section_group_li').addClass('active');
                }
            }
        }

        // Show the group
        jQuery('#' + oldid + '_section_group').hide();
        jQuery('#' + relid + '_section_group').fadeIn(200, function() {
            if (jQuery('#redux-footer').length !== 0) {
                stickyInfo(); // race condition fix
            }
        });

    });

    // Get the URL parameter for tab
    function getURLParameter(name) {
        return decodeURI((new RegExp(name + '=' + '(.+?)(&|$)').exec(location.search) || [, ''])[1]);
    }

    // If the $_GET param of tab is set, use that for the tab that should be open
    var tab = getURLParameter('tab');

    if (tab !== "") {
        if ($.cookie("redux_current_tab_get") !== tab) {
            $.cookie('redux_current_tab', tab, {
                expires:    7,
                path:       '/'
            });
            $.cookie('redux_current_tab_get', tab, {
                expires:    7,
                path:       '/'
            });

            jQuery('#' + tab + '_section_group_li').click();
        }
    } else if ($.cookie('redux_current_tab_get') !== "") {
        $.removeCookie('redux_current_tab_get');
    }

    var sTab = jQuery('#' + $.cookie("redux_current_tab") + '_section_group_li_a');

    // Tab the first item or the saved one
    if ($.cookie("redux_current_tab") === null || typeof ($.cookie("redux_current_tab")) === "undefined" || sTab.length === 0) {
        jQuery('.redux-group-tab-link-a:first').click();
    } else {
        sTab.click();
    }

    // Default button clicked
    jQuery('input[name="' + redux.args.opt_name + '[defaults]"]').click(function() {
        if (!confirm(redux.args.reset_confirm)) {
            return false;
        }
        window.onbeforeunload = null;
    });

    // Default button clicked
    jQuery('input[name="' + redux.args.opt_name + '[defaults-section]"]').click(function() {
        if (!confirm(redux.args.reset_section_confirm)) {
            return false;
        }

        window.onbeforeunload = null;
    });

    function redux_expand_options(parent) {
        //console.log('here');
        var trigger = parent.find('.expand_options');
        var width   = parent.find('.redux-sidebar').width();
        var id      = jQuery('.redux-group-menu .active a').data('rel') + '_section_group';

        if (trigger.hasClass('expanded')) {
            trigger.removeClass('expanded');
            parent.find('.redux-main').removeClass('expand');
            parent.find('.redux-sidebar').stop().animate({
                'margin-left': '0px'
            }, 500);

            parent.find('.redux-main').stop().animate({
                'margin-left': width
            }, 500);

            parent.find('.redux-group-tab').each(function() {
                if (jQuery(this).attr('id') !== id) {
                    jQuery(this).fadeOut('fast');
                }
            });
            // Show the only active one
        } else {
            trigger.addClass('expanded');
            parent.find('.redux-main').addClass('expand');
            parent.find('.redux-sidebar').stop().animate({
                'margin-left': -width - 102
            }, 500);

            parent.find('.redux-main').stop().animate({
                'margin-left': '0px'
            }, 500);

            parent.find('.redux-group-tab').fadeIn();
        }
        return false;
    }

    jQuery('.expand_options').click(function(e) {
        e.preventDefault();

        redux_expand_options(jQuery(this).parents('.redux-container:first'));
        return false;
    });

    if (jQuery('.saved_notice').is(':visible')) {
        jQuery('.saved_notice').slideDown();
    }

    jQuery(document.body).on('change', '.redux-field input, .redux-field textarea, .redux-field select', function() {
        if (!jQuery(this).hasClass('noUpdate')) {
            redux_change(jQuery(this));
        }
    });

    /**
     BEGIN Sticky footer bar
     **/
    var stickyHeight = jQuery('#redux-footer').height();
    jQuery('#redux-sticky-padder').css({
        height: stickyHeight
    });

    window.onresize = function(event) {
        if (jQuery('#redux-sticky').hasClass('sticky-save-warn')) {
            //console.log('resize');
            //   jQuery('.redux-save-warn').css('left', jQuery('#redux-intro-text').width())
        }
    };

    function stickyInfo() {
        var stickyWidth = jQuery('#info_bar').width() - 2;

        if (!jQuery('#info_bar').isOnScreen() && !jQuery('#redux-footer-sticky').isOnScreen()) {
            jQuery('#redux-sticky').addClass('sticky-save-warn');

            jQuery('#redux-footer').css({
                position:   'fixed',
                bottom:     '0',
                width:      stickyWidth
            });

            jQuery('#redux-footer').addClass('sticky-footer-fixed');
            jQuery('.redux-save-warn').css('left', jQuery('#redux-sticky').offset().left);
            jQuery('#redux-sticky-padder').show();
        } else {
            jQuery('#redux-sticky').removeClass('sticky-save-warn');

            jQuery('#redux-footer').css({
                background: '#eee',
                position:   'inherit',
                bottom:     'inherit',
                width:      'inherit'
            });

            jQuery('#redux-sticky-padder').hide();
            jQuery('#redux-footer').removeClass('sticky-footer-fixed');
        }
    }

    if (jQuery('#redux-footer').length !== 0) {
        jQuery(window).scroll(function() {
            stickyInfo();
        });

        jQuery(window).resize(function() {
            stickyInfo();
        });
    }
    jQuery('.saved_notice').delay(4000).slideUp();
    //jQuery('.redux-field-errors').delay(8000).slideUp();
    jQuery('.redux-save').click(function() {
        window.onbeforeunload = null;
    });
    /**
     END Sticky footer bar
     **/

    /**
     BEGIN dev_mode commands
     **/
    $('#consolePrintObject').on('click', function() {
        console.log(jQuery.parseJSON(jQuery("#redux-object-json").html()));
    });

    if (typeof jsonView === 'function') {
        jsonView('#redux-object-json', '#redux-object-browser');
    }
    /**
     END dev_mode commands
     **/

    /**
     BEGIN error and warning notices
     **/
    if (redux.errors !== undefined) {
        jQuery.each(redux.errors.errors, function(sectionID, sectionArray) {
            jQuery.each(sectionArray.errors, function(key, value) {
                jQuery("#" + redux.args.opt_name + '-' + value.id).addClass("redux-field-error");
                if (jQuery("#" + redux.args.opt_name + '-' + value.id).parent().find('.redux-th-error').length === 0) {
                    jQuery("#" + redux.args.opt_name + '-' + value.id).append('<div class="redux-th-error">' + value.msg + '</div>');
                }
            });
        });
        $('.redux-container').each(function () {
            var container = $(this);
            var totalErrors = container.find('.redux-field-error').length;
            if (totalErrors > 0) {
                container.find(".redux-field-errors span").text(totalErrors);
                container.find(".redux-field-errors").slideDown();
                container.find('.redux-group-tab').each(function () {
                    var total = $(this).find('.redux-field-error').length;
                    if (total > 0 ) {
                        var sectionID = $(this).attr('id').split('_');
                        sectionID = sectionID[0];
                        container.find('.redux-group-tab-link-a[data-key="'+sectionID+'"]').prepend('<span class="redux-menu-error">' + total + '</span>');
                        container.find('.redux-group-tab-link-a[data-key="'+sectionID+'"]').addClass("hasError");
                        var subParent = container.find('.redux-group-tab-link-a[data-key="'+sectionID+'"]').parents('.hasSubSections:first');
                        if ( subParent ) {
                            subParent.find('.redux-group-tab-link-a:first').addClass('hasError');
                        }
                    }
                });
            }
            var totalWarnings = container.find('.redux-field-warning').length;
            if (totalWarnings > 0) {
                container.find(".redux-field-warnings span").text(totalWarnings);
                container.find(".redux-field-warnings").slideDown();
                container.find('.redux-group-tab').each(function () {
                    var warning = $(this).find('.redux-field-warning').length;
                    if (warning > 0 ) {
                        var sectionID = $(this).attr('id').split('_');
                        sectionID = sectionID[0];
                        container.find('.redux-group-tab-link-a[data-key="'+sectionID+'"]').prepend('<span class="redux-menu-warning">' + total + '</span>');
                        container.find('.redux-group-tab-link-a[data-key="'+sectionID+'"]').addClass("hasWarning");
                        var subParent = container.find('.redux-group-tab-link-a[data-key="'+sectionID+'"]').parents('.hasSubSections:first');
                        if ( subParent ) {
                            subParent.find('.redux-group-tab-link-a:first').addClass('hasWarning');
                        }
                    }
                });

            }
        });
    }

    /**
     END error and warning notices
     **/

    /**
     BEGIN Control the tabs of the site to the left. Eventually (perhaps) across the top too.
     **/
    //jQuery( ".redux-section-tabs" ).tabs();
    jQuery('.redux-section-tabs div').hide();
    jQuery('.redux-section-tabs div:first').show();
    jQuery('.redux-section-tabs ul li:first').addClass('active');

    jQuery('.redux-section-tabs ul li a').click(function() {
        jQuery('.redux-section-tabs ul li').removeClass('active');
        jQuery(this).parent().addClass('active');

        var currentTab = $(this).attr('href');

        jQuery('.redux-section-tabs div').hide();
        jQuery(currentTab).fadeIn();

        return false;
    });
    /**
     END Control the tabs of the site to the left. Eventually (perhaps) across the top too.
     **/
});