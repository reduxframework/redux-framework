/* global redux, redux_opts */
/*
 * Field Sorter jquery function
 * Based on
 * [SMOF - Slightly Modded Options Framework](http://aquagraphite.com/2011/09/slightly-modded-options-framework/)
 * Version 1.4.2
 */

(function($) {
    "use strict";
    
    $(function() {
        /**	Sorter (Layout Manager) */
        $('.redux-sorter').each(function() {
            var id = $(this).attr('id');
            
            $('#' + id).find('ul').sortable({
                items:          'li',
                placeholder:    "placeholder",
                connectWith:    '.sortlist_' + id,
                opacity:        0.8,
                stop: function(event, ui) {
                    var sorter = redux.sorter[$(this).attr('data-id')];
                    var id = $(this).find('h3').text();

                    if (sorter.limits && id && sorter.limits[id]) {
                        if ($(this).children('li').length >= sorter.limits[id]) {
                            $(this).addClass('filled');
                            if ($(this).children('li').length > sorter.limits[id]) {
                                $(ui.sender).sortable('cancel');
                            }
                        } else {
                            $(this).removeClass('filled');
                        }
                    }
                },
                update: function(event, ui) {
                    var sorter  = redux.sorter[$(this).attr('data-id')];
                    var id      = $(this).find('h3').text();
                    
                    if (sorter.limits && id && sorter.limits[id]) {
                        if ($(this).children('li').length >= sorter.limits[id]) {
                            $(this).addClass('filled');
                            if ($(this).children('li').length > sorter.limits[id]) {
                                $(ui.sender).sortable('cancel');
                            }
                        } else {
                            $(this).removeClass('filled');
                        }
                    }

                    $(this).find('.position').each(function() {
                        var listID      = $(this).parent().attr('id');
                        var parentID    = $(this).parent().parent().attr('data-group-id');
                        
                        redux_change($(this));
                        
                        var optionID = $(this).parent().parent().parent().attr('id');
                        
                        $(this).prop("name", redux.args.opt_name + '[' + optionID + '][' + parentID + '][' + listID + ']');
                    });
                }
            });
        });
    });
})(jQuery);