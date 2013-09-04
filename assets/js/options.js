/*global $, jQuery, document, tabid:true, redux_opts, confirm, relid:true*/

jQuery(document).ready(function () {

    if (jQuery('#last_tab').val() === '') {
        jQuery('.redux-opts-group-tab:first').slideDown('fast');
        jQuery('#redux-opts-group-menu li:first').addClass('active');
    } else {
        tabid = jQuery('#last_tab').val();
        jQuery('#' + tabid + '_section_group').slideDown('fast');
        jQuery('#' + tabid + '_section_group_li').addClass('active');
    }

    jQuery('input[name="' + redux_opts.opt_name + '[defaults]"]').click(function () {
        if (!confirm(redux_opts.reset_confirm)) {
            return false;
        }
    });

    jQuery('.redux-opts-group-tab-link-a').click(function () {
        relid = jQuery(this).attr('data-rel');

        jQuery('#last_tab').val(relid);

        jQuery('.redux-opts-group-tab').each(function () {
            if (jQuery(this).attr('id') === relid + '_section_group') {
                jQuery(this).delay(400).fadeIn(1200);
            } else {
                jQuery(this).fadeOut('fast');
            }
        });

        jQuery('.redux-opts-group-tab-link-li').each(function () {
            if (jQuery(this).attr('id') !== relid + '_section_group_li' && jQuery(this).hasClass('active')) {
                jQuery(this).removeClass('active');
            }
            if (jQuery(this).attr('id') === relid + '_section_group_li') {
                jQuery(this).addClass('active');
            }
        });
    });

    if (jQuery('#redux-opts-save').is(':visible')) {
        jQuery('#redux-opts-save').delay(4000).slideUp('slow');
    }

    if (jQuery('#redux-opts-imported').is(':visible')) {
        jQuery('#redux-opts-imported').delay(4000).slideUp('slow');
    }

    jQuery('#redux-opts-form-wrapper').on('change', 'input, textarea, select', function () {
        if(this.id === 'google_webfonts' && this.value === '') return;
        jQuery('#redux-opts-save-warn').slideDown('slow');
    });

    jQuery('#redux-opts-import-code-button').click(function () {
        if (jQuery('#redux-opts-import-link-wrapper').is(':visible')) {
            jQuery('#redux-opts-import-link-wrapper').fadeOut('fast');
            jQuery('#import-link-value').val('');
        }
        jQuery('#redux-opts-import-code-wrapper').fadeIn('slow');
    });

    jQuery('#redux-opts-import-link-button').click(function () {
        if (jQuery('#redux-opts-import-code-wrapper').is(':visible')) {
            jQuery('#redux-opts-import-code-wrapper').fadeOut('fast');
            jQuery('#import-code-value').val('');
        }
        jQuery('#redux-opts-import-link-wrapper').fadeIn('slow');
    });

    jQuery('#redux-opts-export-code-copy').click(function () {
        if (jQuery('#redux-opts-export-link-value').is(':visible')) {jQuery('#redux-opts-export-link-value').fadeOut('slow'); }
        jQuery('#redux-opts-export-code').toggle('fade');
    });

    jQuery('#redux-opts-export-link').click(function () {
        if (jQuery('#redux-opts-export-code').is(':visible')) {jQuery('#redux-opts-export-code').fadeOut('slow'); }
        jQuery('#redux-opts-export-link-value').toggle('fade');
    });
});
