/* global jQuery, document, redux, redux.args, confirm, relid:true, console, jsonView */

(function($) {
    'use strict';

    $.redux = $.redux || {};

    $(document).ready(
        function() {





            $.fn.isOnScreen = function() {
                if (!window) {
                    return;
                }

                var win = $(window);
                var viewport = {
                    top: win.scrollTop(),
                    left: win.scrollLeft()
                };

                viewport.right = viewport.left + win.width();
                viewport.bottom = viewport.top + win.height();

                var bounds = this.offset();

                bounds.right = bounds.left + this.outerWidth();
                bounds.bottom = bounds.top + this.outerHeight();

                return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
            };

            $.redux.checkRequired();
            $.redux.initEvents();
            $.redux.initQtip();
            $.redux.tabCheck();
            $.redux.notices();
            $.redux.tabControl();
            $.redux.devFunctions();
        }
    );

    $.redux.initEvents = function() {
        $('.redux-action_bar, .redux-presets-bar').on(
            'click', function() {
                window.onbeforeunload = null;
            }
        );

        $('#toplevel_page_' + redux.args.slug + ' .wp-submenu a, #wp-admin-bar-' + redux.args.slug + ' a.ab-item').click(
            function(e) {
                if ($('#toplevel_page_' + redux.args.slug).hasClass('wp-menu-open') || $(this).hasClass('ab-item')) {
                    e.preventDefault();

                    var url = $(this).attr('href').split('&tab=');

                    $('#' + url[1] + '_section_group_li_a').click();
                    return false;
                }
            }
        );


        // Default button clicked
        $('input[name="' + redux.args.opt_name + '[defaults]"]').click(
            function() {
                if (!confirm(redux.args.reset_confirm)) {
                    return false;
                }
                window.onbeforeunload = null;
            }
        );

        // Default button clicked
        $('input[name="' + redux.args.opt_name + '[defaults-section]"]').click(
            function() {
                if (!confirm(redux.args.reset_section_confirm)) {
                    return false;
                }

                window.onbeforeunload = null;
            }
        );

        $('.expand_options').click(
            function(e) {
                e.preventDefault();

                $.redux.expandOptions($(this).parents('.redux-container:first'));
                return false;
            }
        );

        if ($('.saved_notice').is(':visible')) {
            $('.saved_notice').slideDown();
        }

        $(document.body).on(
            'change', '.redux-field input, .redux-field textarea, .redux-field select', function() {
                if (!$(this).hasClass('noUpdate')) {
                    redux_change($(this));
                }
            }
        );

        var stickyHeight = $('#redux-footer').height();
        
        $('#redux-sticky-padder').css({
            height: stickyHeight
        });

        if ($('#redux-footer').length !== 0) {
            $(window).scroll(
                function() {
                    $.redux.stickyInfo();
                }
            );

            $(window).resize(
                function() {
                    $.redux.stickyInfo();
                }
            );
        }

        $('.saved_notice').delay(4000).slideUp();

        $('.redux-save').click(
            function() {
                window.onbeforeunload = null;
            }
        );

    };

    $.redux.checkRequired = function() {
        $.redux.required();

        $("body").on(
                'change',
                '.redux-main select, .redux-main radio, .redux-main input[type=checkbox], .redux-main input[type=hidden]',
                function(e) {
                    $.redux.check_dependencies(this);
                }
        );

        $("body").on(
                'check_dependencies', function(e, variable) {
                    $.redux.check_dependencies(variable);
                }
        );

        $('td > fieldset:empty,td > div:empty').parent().parent().hide();
    };

    $.redux.initQtip = function() {
        if ($().qtip) {
            // Shadow
            var shadow = '';
            var tip_shadow = redux.args.hints.tip_style.shadow;

            if (tip_shadow === true) {
                shadow = 'qtip-shadow';
            }

            // Color
            var color = '';
            var tip_color = redux.args.hints.tip_style.color;

            if (tip_color !== '') {
                color = 'qtip-' + tip_color;
            }

            // Rounded
            var rounded = '';
            var tip_rounded = redux.args.hints.tip_style.rounded;

            if (tip_rounded === true) {
                rounded = 'qtip-rounded';
            }

            // Tip style
            var style = '';
            var tip_style = redux.args.hints.tip_style.style;

            if (tip_style !== '') {
                style = 'qtip-' + tip_style;
            }

            var classes = shadow + ',' + color + ',' + rounded + ',' + style;
            classes = classes.replace(/,/g, ' ');

            // Get position data
            var myPos = redux.args.hints.tip_position.my;
            var atPos = redux.args.hints.tip_position.at;

            // Gotta be lowercase, and in proper format
            myPos = $.redux.verifyPos(myPos.toLowerCase(), true);
            atPos = $.redux.verifyPos(atPos.toLowerCase(), false);

            // Tooltip trigger action
            var showEvent = redux.args.hints.tip_effect.show.event;
            var hideEvent = redux.args.hints.tip_effect.hide.event;

            // Tip show effect
            var tipShowEffect = redux.args.hints.tip_effect.show.effect;
            var tipShowDuration = redux.args.hints.tip_effect.show.duration;

            // Tip hide effect
            var tipHideEffect = redux.args.hints.tip_effect.hide.effect;
            var tipHideDuration = redux.args.hints.tip_effect.hide.duration;

            $('div.redux-hint-qtip').each(
                function() {
                    $(this).qtip({
                        content: {
                            text: $(this).attr('qtip-content'),
                            title: $(this).attr('qtip-title')
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
                }
            );
            // });

            $('input[qtip-content]').each(
                function() {
                    $(this).qtip({
                        content: {
                            text: $(this).attr('qtip-content'),
                            title: $(this).attr('qtip-title')
                        },
                        show: 'focus',
                        hide: 'blur',
                        style: classes,
                        position: {
                            my: myPos,
                            at: atPos,
                        },
                    });
                }
            );
        }
    };

    $.redux.tabCheck = function() {
        $('.redux-group-tab-link-a').click(
            function() {

                var relid = $(this).data('rel'); // The group ID of interest
                var oldid = $('.redux-group-tab-link-li.active .redux-group-tab-link-a').data('rel');

                if (oldid === relid) {
                    return;
                }

                $('#currentSection').val(relid);
                if (!$(this).parents('.postbox-container:first').length) {
                    // Set the proper page cookie
                    $.cookie(
                        'redux_current_tab', relid, {
                            expires: 7,
                            path: '/'
                        }
                    );
                }

                if ($('#' + relid + '_section_group_li').parents('.redux-group-tab-link-li').length) {
                    var parentID = $('#' + relid + '_section_group_li').parents('.redux-group-tab-link-li').attr('id').split('_');
                    parentID = parentID[0];
                }

                $('#toplevel_page_' + redux.args.slug + ' .wp-submenu a.current').removeClass('current');
                $('#toplevel_page_' + redux.args.slug + ' .wp-submenu li.current').removeClass('current');

                $('#toplevel_page_' + redux.args.slug + ' .wp-submenu a').each(
                    function() {
                        var url = $(this).attr('href').split('&tab=');
                        if (url[1] == relid || url[1] == parentID) {
                            $(this).addClass('current');
                            $(this).parent().addClass('current');
                        }
                    }
                );

                if ($('#' + oldid + '_section_group_li').find('#' + oldid + '_section_group_li').length) {
                    //console.log('RELID is child of oldid');
                    $('#' + oldid + '_section_group_li').addClass('activeChild');
                    $('#' + relid + '_section_group_li').addClass('active').removeClass('activeChild');
                } else if ($('#' + relid + '_section_group_li').parents('#' + oldid + '_section_group_li').length || $('#' + oldid + '_section_group_li').parents('ul.subsection').find('#' + relid + '_section_group_li').length) {
                    //console.log('RELID is sibling or child of OLDID');
                    if ($('#' + relid + '_section_group_li').parents('#' + oldid + '_section_group_li').length) {
                        //console.log('child of oldid');
                        $('#' + oldid + '_section_group_li').addClass('activeChild').removeClass('active');
                    } else {
                        //console.log('sibling');
                        $('#' + relid + '_section_group_li').addClass('active');
                        $('#' + oldid + '_section_group_li').removeClass('active');
                    }
                    $('#' + relid + '_section_group_li').removeClass('activeChild').addClass('active');
                } else {
                    $('#' + relid + '_section_group_li').addClass('active').removeClass('activeChild').find('ul.subsection').slideDown();

                    if ($('#' + oldid + '_section_group_li').find('ul.subsection').length) {
                        //console.log('oldid is parent')
                        $('#' + oldid + '_section_group_li').find('ul.subsection').slideUp(
                            'fast', function() {
                                $('#' + oldid + '_section_group_li').removeClass('active').removeClass('activeChild');
                            }
                        );
                    } else if ($('#' + oldid + '_section_group_li').parents('ul.subsection').length) {
                        //console.log('oldid is a child');
                        if (!$('#' + oldid + '_section_group_li').parents('#' + relid + '_section_group_li').length) {
                            //console.log('oldid is child, but not of relid');
                            $('#' + oldid + '_section_group_li').parents('ul.subsection').slideUp(
                                'fast', function() {
                                    $('#' + oldid + '_section_group_li').removeClass('active');
                                    $('#' + oldid + '_section_group_li').parents('.redux-group-tab-link-li').removeClass('active').removeClass('activeChild');
                                }
                            );
                        } else {
                            $('#' + oldid + '_section_group_li').removeClass('active');
                        }
                    } else {
                        //console.log('Normal remove active from child');
                        $('#' + oldid + '_section_group_li').removeClass('active');
                        if ($('#' + relid + '_section_group_li').parents('.redux-group-tab-link-li').length) {
                            //console.log('here');
                            $('#' + relid + '_section_group_li').parents('.redux-group-tab-link-li').addClass('activeChild').find('ul.subsection').slideDown();
                            $('#' + relid + '_section_group_li').addClass('active');
                        }
                    }
                }

                // Show the group
                $('#' + oldid + '_section_group').hide();

                $('#' + relid + '_section_group').fadeIn(
                    200, function() {
                        if ($('#redux-footer').length !== 0) {
                            $.redux.stickyInfo(); // race condition fix
                        }
                        $.redux.initFields();
                    }
                );
            }
        );

        var tab = decodeURI((new RegExp('tab' + '=' + '(.+?)(&|$)').exec(location.search) || [, ''])[1]);

        if (tab !== "") {
            if ($.cookie("redux_current_tab_get") !== tab) {
                $.cookie(
                    'redux_current_tab', tab, {
                        expires: 7,
                        path: '/'
                    }
                );
                $.cookie(
                    'redux_current_tab_get', tab, {
                        expires: 7,
                        path: '/'
                    }
                );

                $('#' + tab + '_section_group_li').click();
            }
        } else if ($.cookie('redux_current_tab_get') !== "") {
            $.removeCookie('redux_current_tab_get');
        }

        var sTab = $('#' + $.cookie("redux_current_tab") + '_section_group_li_a');

        // Tab the first item or the saved one
        if ($.cookie("redux_current_tab") === null || typeof ($.cookie("redux_current_tab")) === "undefined" || sTab.length === 0) {
            $('.redux-group-tab-link-a:first').click();
        } else {
            sTab.click();
        }

    };

    $.redux.initFields = function() {
        $(".redux-field-init:visible" ).each(function() {
            var type = $(this).attr( 'data-type' );
            //console.log(type);
            if ( redux.field_objects[type]) {
                redux.field_objects[type].init();
            }
        });
    };

    $.redux.notices = function() {
        if (redux.errors !== undefined) {
            $.each(
                redux.errors.errors, function(sectionID, sectionArray) {
                    $.each(
                        sectionArray.errors, function(key, value) {
                            $("#" + redux.args.opt_name + '-' + value.id).addClass("redux-field-error");
                            if ($("#" + redux.args.opt_name + '-' + value.id).parent().find('.redux-th-error').length === 0) {
                                $("#" + redux.args.opt_name + '-' + value.id).append('<div class="redux-th-error">' + value.msg + '</div>');
                            }
                        }
                    );
                }
            );

            $('.redux-container').each(
                    function() {
                        var container = $(this);
                        var totalErrors = container.find('.redux-field-error').length;
                        if (totalErrors > 0) {
                            container.find(".redux-field-errors span").text(totalErrors);
                            container.find(".redux-field-errors").slideDown();
                            container.find('.redux-group-tab').each(
                                    function() {
                                        var total = $(this).find('.redux-field-error').length;
                                        if (total > 0) {
                                            var sectionID = $(this).attr('id').split('_');
                                            sectionID = sectionID[0];
                                            container.find('.redux-group-tab-link-a[data-key="' + sectionID + '"]').prepend('<span class="redux-menu-error">' + total + '</span>');
                                            container.find('.redux-group-tab-link-a[data-key="' + sectionID + '"]').addClass("hasError");
                                            var subParent = container.find('.redux-group-tab-link-a[data-key="' + sectionID + '"]').parents('.hasSubSections:first');
                                            if (subParent) {
                                                subParent.find('.redux-group-tab-link-a:first').addClass('hasError');
                                            }
                                        }
                                    }
                            );
                        }
                        var totalWarnings = container.find('.redux-field-warning').length;
                        if (totalWarnings > 0) {
                            container.find(".redux-field-warnings span").text(totalWarnings);
                            container.find(".redux-field-warnings").slideDown();
                            container.find('.redux-group-tab').each(
                                    function() {
                                        var warning = $(this).find('.redux-field-warning').length;
                                        if (warning > 0) {
                                            var sectionID = $(this).attr('id').split('_');
                                            sectionID = sectionID[0];
                                            container.find('.redux-group-tab-link-a[data-key="' + sectionID + '"]').prepend('<span class="redux-menu-warning">' + total + '</span>');
                                            container.find('.redux-group-tab-link-a[data-key="' + sectionID + '"]').addClass("hasWarning");
                                            var subParent = container.find('.redux-group-tab-link-a[data-key="' + sectionID + '"]').parents('.hasSubSections:first');
                                            if (subParent) {
                                                subParent.find('.redux-group-tab-link-a:first').addClass('hasWarning');
                                            }
                                        }
                                    }
                            );
                        }
                    }
            );
        }
    };

    $.redux.tabControl = function() {
        $('.redux-section-tabs div').hide();
        $('.redux-section-tabs div:first').show();
        $('.redux-section-tabs ul li:first').addClass('active');

        $('.redux-section-tabs ul li a').click(
                function() {
                    $('.redux-section-tabs ul li').removeClass('active');
                    $(this).parent().addClass('active');

                    var currentTab = $(this).attr('href');

                    $('.redux-section-tabs div').hide();
                    $(currentTab).fadeIn('medium', function() {
                        $.redux.initFields();
                    });

                    return false;
                }
        );
    };

    $.redux.devFunctions = function() {
        $('#consolePrintObject').on(
                'click', function() {
                    console.log($.parseJSON($("#redux-object-json").html()));
                }
        );

        if (typeof jsonView === 'function') {
            jsonView('#redux-object-json', '#redux-object-browser');
        }
    };

    $.redux.required = function() {

        // Hide the fold elements on load ,
        // It's better to do this by PHP but there is no filter in tr tag , so is not possible
        // we going to move each attributes we may need for folding to tr tag
        $.each(
            redux.folds, function(i, v) {
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
            }
        );
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

        $.each(
                redux.required[id], function(child, dependents) {

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

                tr.fadeIn(
                    300, function() {
                        $(this).removeClass('hide');
                        if (redux.required.hasOwnProperty(child)) {
                            $.redux.check_dependencies($('#' + redux.args.opt_name + '-' + child).children().first());
                        }
                        $.redux.initFields();
                    }
                );
                if (childFieldset.hasClass('redux-container-section') || childFieldset.hasClass('redux-container-info')) {
                    tr.css({display: 'none'});
                }
            } else if (show === false) {
                tr.fadeOut(
                    100, function() {
                        $(this).addClass('hide');
                        if (redux.required.hasOwnProperty(child)) {
                            //console.log('Now check, reverse: '+child);
                            $.redux.required_recursive_hide(child);
                        }
                    }
                );
            }

            current.find('select, radio, input[type=checkbox]').trigger('change');
        }
        );
    };

    $.redux.required_recursive_hide = function(id) {
        var toFade = $('#' + redux.args.opt_name + '-' + id).parents('tr:first');

        toFade.fadeOut(
            50, function() {
                $(this).addClass('hide');

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
                    $.each(
                            redux.required[id], function(child) {
                        $.redux.required_recursive_hide(child);
                    }
                    );
                }
            }
        );
    };

    $.redux.check_parents_dependencies = function(id) {
        var show = "";

        if (redux.required_child.hasOwnProperty(id)) {
            $.each(
                    redux.required_child[id], function(i, parentData) {
                if ($('#' + redux.args.opt_name + '-' + parentData.parent).parents('tr:first').hasClass('.hide')) {
                    show = false;
                } else {
                    if (show !== false) {
                        var parentValue = $.redux.get_container_value(parentData.parent);
                        show = $.redux.check_dependencies_visibility(parentValue, parentData);
                    }
                }
            }
            );
        } else {
            show = true;
        }
        return show;
    };

    $.redux.check_dependencies_visibility = function(parentValue, data) {
        var show = false,
                checkValue_array,
                checkValue = data.checkValue,
                operation = data.operation;

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

    $.redux.verifyPos = function(s, b) {

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
    };
    
    $.redux.stickyInfo = function() {
        var stickyWidth = $('#info_bar').width() - 2;

        if (!$('#info_bar').isOnScreen() && !$('#redux-footer-sticky').isOnScreen()) {
            $('#redux-sticky').addClass('sticky-save-warn');

            $('#redux-footer').css({
                position: 'fixed',
                bottom: '0',
                width: stickyWidth
            });

            $('#redux-footer').addClass('sticky-footer-fixed');
            $('.redux-save-warn').css('left', $('#redux-sticky').offset().left);
            $('#redux-sticky-padder').show();
        } else {
            $('#redux-sticky').removeClass('sticky-save-warn');

            $('#redux-footer').css({
                background: '#eee',
                position: 'inherit',
                bottom: 'inherit',
                width: 'inherit'
            });

            $('#redux-sticky-padder').hide();
            $('#redux-footer').removeClass('sticky-footer-fixed');
        }
    };
    
    $.redux.expandOptions = function(parent) {
        var trigger = parent.find('.expand_options');
        var width   = parent.find('.redux-sidebar').width();
        var id      = $('.redux-group-menu .active a').data('rel') + '_section_group';

        if (trigger.hasClass('expanded')) {
            trigger.removeClass('expanded');
            parent.find('.redux-main').removeClass('expand');
            
            parent.find('.redux-sidebar').stop().animate({
                'margin-left': '0px'
            }, 500 );

            parent.find('.redux-main').stop().animate({
                'margin-left': width
            }, 500);

            parent.find('.redux-group-tab').each(
                function() {
                    if ($(this).attr('id') !== id) {
                        $(this).fadeOut('fast');
                    }
                }
            );
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

            parent.find('.redux-group-tab').fadeIn('medium', function() {
                $.redux.initFields();
            });
        }
        return false;
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

function redux_change(variable) {
    jQuery('body').trigger('check_dependencies', variable);

    if (variable.hasClass('compiler')) {
        jQuery('#redux-compiler-hook').val(1);
    }

    window.onbeforeunload = confirmOnPageExit;

    var rContainer = jQuery(variable).parents('.redux-container:first');

    if (jQuery(variable).parents('fieldset.redux-field:first').hasClass('redux-field-error')) {
        jQuery(variable).parents('fieldset.redux-field:first').removeClass('redux-field-error');
        jQuery(variable).parent().find('.redux-th-error').slideUp();

        var parentID = jQuery(variable).closest('.redux-group-tab').attr('id');


        var errorCount = (parseInt(rContainer.find('.redux-field-errors span').text()) - 1);
        var warningCount = (parseInt(rContainer.find('.redux-field-warnings span').text()) - 1);
        
        if (errorCount <= 0) {
            console.log('HERE');
            jQuery('#' + parentID + '_li .redux-menu-error').fadeOut('fast').remove();
            jQuery('#' + parentID + '_li .redux-group-tab-link-a').removeClass('hasError');

            jQuery('#' + parentID + '_li').parents('.inside:first').find('.redux-field-errors').slideUp();
            jQuery(variable).parents('.redux-container:first').find('.redux-field-errors').slideUp();
            jQuery('#redux_metaboxes_errors').slideUp();
        } else {
            // Let's count down the errors now. Fancy.  ;)
            var id = parentID.split('_');
            id = id[0];

            var th = rContainer.find('.redux-group-tab-link-a[data-key="' + id + '"]').parents('.redux-group-tab-link-li:first');

            var errorsLeft = (parseInt(th.find('.redux-menu-error:first').text()) - 1);
            if (errorsLeft <= 0) {
                th.find('.redux-menu-error:first').fadeOut().remove();
            } else {
                th.find('.redux-menu-error:first').text(errorsLeft);
            }

            var warningsLeft = (parseInt(th.find('.redux-menu-warning:first').text()) - 1);
            if (warningsLeft <= 0) {
                th.find('.redux-menu-warning:first').fadeOut().remove();
            } else {
                th.find('.redux-menu-warning:first').text(warningsLeft);
            }

            rContainer.find('.redux-field-errors span').text(errorCount);
            rContainer.find('.redux-field-warning span').text(warningCount);

        }
        var subParent = jQuery('#' + parentID + '_li').parents('.hasSubSections:first');
        if (subParent.length !== 0) {
            if (subParent.find('.redux-menu-error').length === 0) {
                subParent.find('.hasError').removeClass('hasError');
            }
        }
    }
    if (!redux.args.disable_save_warn) {
        rContainer.find('.redux-save-warn').slideDown();
    }
}