/* global redux_change */
(function($){
    "use strict";

    $.redux = $.redux || {};

    $(document).ready(function () {
        //css builder functionality
        $.redux.css_builder();
    });

    $.redux.css_builder = function(){
        $('.redux-css-builder-remove').live('click', function() {
            redux_change($(this));
            $(this).prev('input[type="text"]').val('');
            $(this).parent().slideUp('medium', function(){
                $(this).remove();
            });
        });

        $('.redux-css-builder-add').click(function(){
            var number = parseInt($(this).attr('data-add_number'));
            var id = $(this).attr('data-id');
            var listindex = $('#'+id).find('li:visible').length;
            var name = $(this).attr('data-name');
            var default_params = {
                width: 'resolve',
                triggerChange: false,
                allowClear: true
            };

            for (var i = 0; i < number; i++) {
                var new_input = $('#'+id+' li:not(:visible)').clone();
                $('#'+id).append(new_input).show();
                $('#'+id+' li:last-child').removeAttr('style');
                $('#'+id+' li:last-child input[type="text"]')
                .val('')
                .attr('name', name + "[values][]")
                .attr('id', id + "-" + listindex);

                $('#'+id+' li:last-child select.redux-css-dummy-select')
                .removeClass("redux-css-dummy-select")
                .attr('name', name + "[properties][]")
                .attr('id', id + "-" + listindex)
                .addClass("redux-select-item")
                .select2(default_params);
            }

            $("select.redux-css-builder-select").each(function(){
                $(this).off("change").on("change",function(e){
                    $(this).parent().find("input[type='text']").attr("placeholder",$(this).find("option:selected").data("description"));
                });
            });
        });

        $("select.redux-css-builder-select").each(function(){
            $(this).on("change",function(e){
                $(this).parent().find("input[type='text']").attr("placeholder",$(this).find("option:selected").data("description"));
            }).trigger("change");
        });
    };

})(jQuery);
