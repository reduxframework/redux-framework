(function($) {
    "use strict";
    
    $(document).ready(function() {
        $('#redux-import').click(function(e) {
            if ($('#import-code-value').val() === "" && $('#import-link-value').val() === "") {
                e.preventDefault();
                return false;
            }
        });      
        
        $('#redux-import-code-button').click(function() {
            if ($('#redux-import-link-wrapper').is(':visible')) {
                $('#redux-import-link-wrapper').fadeOut('fast');
                $('#import-link-value').val('');
            }
            $('#redux-import-code-wrapper').fadeIn('slow');
        });
        
        $('#redux-import-link-button').click(function() {
            if ($('#redux-import-code-wrapper').is(':visible')) {
                $('#redux-import-code-wrapper').fadeOut('fast');
                $('#import-code-value').val('');
            }
            $('#redux-import-link-wrapper').fadeIn('slow');
        });
        
        $('#redux-export-code-copy').click(function() {
            if ($('#redux-export-link-value').is(':visible')) {
                $('#redux-export-link-value').fadeOut('slow');
            }
            $('#redux-export-code').toggle('fade');
        });
        
        $('#redux-export-link').click(function() {
            if ($('#redux-export-code').is(':visible')) {
                $('#redux-export-code').fadeOut('slow');
            }
            $('#redux-export-link-value').toggle('fade');
        }); 
        
    });
})(jQuery);
