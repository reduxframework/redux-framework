/*global jQuery, document, redux_change */

(function($) {
    "use strict";
    
    $(document).ready(function() {
        
        var scroll = '';
        var $scrollable = $(".redux-sorter");
        function scrolling(){
            if (scroll == 'up') {
                $scrollable.scrollTop($scrollable.scrollTop()-20);
                setTimeout(scrolling,50);
            } else if (scroll == 'down'){
                $scrollable.scrollTop($scrollable.scrollTop()+20);
                setTimeout(scrolling,50);
            }
        }          
        
        $(".redux-sortable").sortable({
            handle: ".drag",
            placeholder:    "placeholder",
            opacity: 0.7,
            scroll: false,
            out: function( event, ui ) {
                if (!ui.helper) return;
                if (ui.offset.top>0) {
                    scroll='down';
                } else {
                    scroll='up';
                }
                scrolling();
            },
            over: function( event, ui ) {
                scroll='';
            },
            deactivate:function( event, ui ) {
                scroll='';
            },                            
            update: function() {
                redux_change($(this));
            }
        });

        $('.checkbox_sortable').on('click', function() {
            if ($(this).is(":checked")) {
                $('#' + $(this).attr('rel')).val(1);
            } else {
                $('#' + $(this).attr('rel')).val('');
            }
        });
    });
})(jQuery);