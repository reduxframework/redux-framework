/*global jQuery, document*/

(function($) {
    "use strict";
    $(document).ready(function() {
        $('.ace-editor').each(function(index, element) {
            var area = element;
            var editor = $(element).attr('data-editor');

            var aceeditor = ace.edit(editor);
            aceeditor.setTheme("ace/theme/" + jQuery(element).attr('data-theme'));
            aceeditor.getSession().setMode("ace/mode/" + $(element).attr('data-mode'));

            aceeditor.on('change', function(e) {
                $('#' + area.id).val(aceeditor.getSession().getValue());
                redux_change($(element));
            });
        });
    });
})(jQuery);