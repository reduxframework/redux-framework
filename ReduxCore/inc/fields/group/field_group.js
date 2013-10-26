(function($){
    "use strict";
    
    $.redux.group = $.group || {};
	
    $(document).ready(function () {
        //Group functionality
        $.redux.group();
    

    });
    
    $.redux.group = function(){
        $("#redux-groups-accordion")
        .accordion({
            header: "> div > h3",
            collapsible: true,
            active: false,
            heightStyle: "content",
            icons: {
                "header": "ui-icon-plus",
                "activeHeader": "ui-icon-minus"
            }
        })
        .sortable({
            axis: "y",
            handle: "h3",
            stop: function (event, ui) {
                // IE doesn't register the blur when sorting
                // so trigger focusout handlers to remove .ui-state-focus
                ui.item.children("h3").triggerHandler("focusout");
                var inputs = $('input.slide-sort');
                inputs.each(function(idx) {
                    $(this).val(idx);
                });
            }
        });

        $(".redux-container-group").on('hover', 'h3 span.redux-groups-header', function () {
            $(this).addClass('editHover');
        }, function() {
            $(this).removeClass('editHover');
        });

        $(".redux-container-group").each(function(){
            $(this).on('click', 'h3 span.redux-groups-header', function () {
                groupTitleInlineEdit($(this).parent());
            });            
        });

        $(".redux-container-group").on('click', '.redux-groups-remove', function () {
            var parent = $('#'+$(this).attr('rel'));
            parent.find('input[type="text"]').val('');
            parent.find('input[type="hidden"]').val('');
            parent.slideUp('medium', function () {
                $(this).remove();
            });
        });

        $(".redux-container-group").on('click', '.redux-groups-add', function () {


            var slideCount = $(this).attr('data-count');
            var slideCountNew = parseInt(slideCount) + 1;
            $(this).attr('data-count', slideCountNew);
            $(".redux-container-group").find('.redux-groups-add').attr('data-count', slideCountNew);

            var newSlide = $(this).prev().find('.redux-groups-accordion-group:last').clone(true);

            $(newSlide).find('h3 .redux-groups-header').text('New');
            $(newSlide).find('h3 .slide-title').val('New');
            $(newSlide).attr('id', 'group-group-'+slideCount);

            $(this).prev().append(newSlide);
            $(".redux-container-group").on('click', 'h3 span.redux-groups-header', function () {
                groupTitleInlineEdit($(this).parent());
            });

            //we need to add slideCount data-id in fieldset , to make it work with fold
            $(newSlide).find('fieldset').each(function(){
                var fieldset = $(this),
                    data_id  = fieldset.data('id');//,
                    //tr       = fieldset.parents('tr:eq(0)');
                 if (typeof data_id !== 'undefined' && data_id !== false){
                    //fieldset.attr("data-id", data_id.replace(/\d+/, slideCount) );
                    //tr.attr('data-check-id' , data_id.replace(/\d+/, slideCount) );
                    //console.log(tr.attr('data-check-field'));
                    //alert("eee");
                    //tr.attr('data-check-field' , tr.attr('data-check-field')+'-'+slideCount );
                 }
                //console.log($(this).attr("data-id"));
                //$(this).attr("data-id", $(this).data('id').replace(/\d+/, slideCount) );
            });
            $(newSlide).find('input[type="text"], input[type="hidden"], textarea , select').each(function(){
                var attr_name = $(this).attr('name');
                var attr_id = $(this).attr('id');
                var attr_data_id = $(this).attr('id');
                
                // For some browsers, `attr` is undefined; for others,
                // `attr` is false.  Check for both.
                if (typeof attr_id !== 'undefined' && attr_id !== false) {
                    var id = $(this).attr("id");
                    $(this).attr("id", $(this).attr("id").replace(/\d+(?!.*\d+)/, slideCount) );
                }


                if (typeof attr_name !== 'undefined' && attr_name !== false)
                    $(this).attr("name", $(this).attr("name").replace(/\d+(?!.*\d+)/, slideCount) );

                if($(this).prop("tagName") === 'SELECT') {
                    //we clean select2 first
                    $(newSlide).find('.select2-container').remove();
                    $(newSlide).find('select').removeClass('select2-offscreen');

                    //we rebind the select2
                    //$(newSlide).find('.redux-select-item').addClass('xxxxxxxxxxxxxxxx');
                    
                    //$.redux.select();
                }
                //$(this).attr("name", $(this).attr("name").replace(/\d+/, slideCount) ).attr("id", $(this).attr("id").replace(/\d+/, slideCount) );
                $(this).val('');
                if ($(this).hasClass('slide-sort')){
                    $(this).val(slideCount);
                }
            });

            
        });
    };

    function groupTitleInlineEdit(selector) {
        console.log(selector);

        var id = $(selector).parents().attr('id');
        console.log(id);
        var title = $('#'+id+' .redux-group-header');
        console.log(title);
        var input = $('#'+id+' .slide-title');
        console.log(input);

        $('#'+id+' h3 span').hide();

        $(input).attr('type','text');
        $(input).attr('data-orig',$(input).val());
        
        input.focus();
        
        input.keydown(function (e){
            if(e.keyCode === 13){
                input.blur();
            }
        });
        
        input.blur(function() {
            if ($(this).val() !== "") {
                $(this).attr('data-orig', $(this).val());
                console.log('#'+id+' .redux-groups-header');
                $('#'+id+' .redux-groups-header').text($(this).val());
            } else {
                $(this).val($(this).attr('data-orig'));
            }
            $(input).attr('type','hidden');
            $('#'+id+' .redux-groups-header').show();
        });
    }

      

})(jQuery);