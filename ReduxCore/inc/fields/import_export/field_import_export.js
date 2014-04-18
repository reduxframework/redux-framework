(function($) {
    "use strict";
    
    $(document).ready(function() {
        $('#redux-import').click(function(e) {
            if ($('#import-code-value').val() === "" && $('#import-link-value').val() === "") {
                e.preventDefault();
                return false;
            }
            window.onbeforeunload = null;
        });      
        
        $('#redux-import-code-button').click(function() {
            if ($('#redux-import-link-wrapper').is(':visible')) {
                $('#redux-import-link-wrapper').hide();
                $('#import-link-value').val('');
            }
            $('#redux-import-code-wrapper').fadeIn('fast');
        });
        
        $('#redux-import-link-button').click(function() {
            if ($('#redux-import-code-wrapper').is(':visible')) {
                $('#redux-import-code-wrapper').hide();
                $('#import-code-value').val('');
            }
            $('#redux-import-link-wrapper').fadeIn('fast');
        });
        
        $('#redux-export-code-copy').click(function() {
            if ($('#redux-export-link-value').is(':visible')) {
                $('#redux-export-link-value').hide();
            }
            $('#redux-export-code').fadeIn('fast');
        });
        
        $('#redux-export-link').click(function() {
            if ($('#redux-export-code').is(':visible')) {
                $('#redux-export-code').hide();
            }
            $('#redux-export-link-value').fadeIn('fast');
        }); 
        
    });
})(jQuery);
