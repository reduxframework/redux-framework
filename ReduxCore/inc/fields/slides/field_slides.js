/* global redux_change, wp */

(function($) {
    "use strict";

    $(document).ready(function() {

        $('.redux-slides-remove').live('click', function() {
            redux_change(jQuery(this));
            $(this).parent().siblings().find('input[type="text"]').val('');
            $(this).parent().siblings().find('textarea').val('');
            $(this).parent().siblings().find('input[type="hidden"]').val('');

            var slideCount = $(this).parents('.redux-container-slides:first').find('.redux-slides-accordion-group').length;

            if (slideCount > 1) {
                $(this).parents('.redux-slides-accordion-group:first').slideUp('medium', function() {
                    $(this).remove();
                });
            } else {
                $(this).parents('.redux-slides-accordion-group:first').find('.remove-image').click();
                $(this).parents('.redux-container-slides:first').find('.redux-slides-accordion-group:last').find('.redux-slides-header').text("New Slide");
            }
        });

        $('.redux-slides-add').click(function() {

            var newSlide = $(this).prev().find('.redux-slides-accordion-group:last').clone(true);
            var slideCount = $(newSlide).find('.slide-title').attr("name").match(/[0-9]+(?!.*[0-9])/);
            var slideCount1 = slideCount * 1 + 1;

            $(newSlide).find('input[type="text"], input[type="hidden"], textarea').each(function() {

                $(this).attr("name", jQuery(this).attr("name").replace(/[0-9]+(?!.*[0-9])/, slideCount1)).attr("id", $(this).attr("id").replace(/[0-9]+(?!.*[0-9])/, slideCount1));
                $(this).val('');
                if ($(this).hasClass('slide-sort')) {
                    $(this).val(slideCount1);
                }
            });

            $(newSlide).find('.screenshot').removeAttr('style');
            $(newSlide).find('.screenshot').addClass('hide');
            $(newSlide).find('.screenshot a').attr('href', '');
            $(newSlide).find('.remove-image').addClass('hide');
            $(newSlide).find('.redux-slides-image').attr('src', '').removeAttr('id');
            $(newSlide).find('h3').text('').append('<span class="redux-slides-header">New slide</span><span class="ui-accordion-header-icon ui-icon ui-icon-plus"></span>');
            $(this).prev().append(newSlide);
        });

        $('.slide-title').keyup(function(event) {
            var newTitle = event.target.value;
            $(this).parents().eq(3).find('.redux-slides-header').text(newTitle);
        });

        $(function() {
            $(".redux-slides-accordion")
                .accordion({
                    header: "> div > fieldset > h3",
                    collapsible: true,
                    active: false,
                    heightStyle: "content",
                    icons: {"header": "ui-icon-plus", "activeHeader": "ui-icon-minus"}
                })
                .sortable({
                    axis: "y",
                    handle: "h3",
                    connectWith: ".redux-slides-accordion",
                    start: function(e, ui) {
                        ui.placeholder.height(ui.item.height());
                        ui.placeholder.width(ui.item.width());
                    },
                    placeholder: "ui-state-highlight",
                    stop: function(event, ui) {
                        // IE doesn't register the blur when sorting
                        // so trigger focusout handlers to remove .ui-state-focus
                        ui.item.children("h3").triggerHandler("focusout");
                        var inputs = $('input.slide-sort');
                        inputs.each(function(idx) {
                            $(this).val(idx);
                        });
                    }
                });
        });
    });
})(jQuery);