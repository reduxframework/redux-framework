<?php
/**
 * Adds a notice to the plugins page
 */

$extendifySdkNoticesKey = 'extendify_subscribe_to_extendify';
$extendifySdkUrl = add_query_arg(
    [
        'utm_source' => rawurlencode(sanitize_text_field(wp_unslash($GLOBALS['extendifySdkSourcePlugin']))),
        'utm_medium' => 'admin',
        'utm_campaign' => 'notice',
        'utm_content' => 'launch60',
    ],
    'https://extendify.com/pricing'
);
$extendifySdkNoticesNonce = wp_create_nonce($extendifySdkNoticesKey);

add_action(
    'admin_notices',
    function () use ($extendifySdkNoticesKey, $extendifySdkNoticesNonce, $extendifySdkUrl) {
        $currentPage = get_current_screen();
        if (!$currentPage || !in_array($currentPage->base, ['plugins'], true)) {
            return;
        }

        // In short, the notice will always show until they press dismiss.
        if (!get_user_option($extendifySdkNoticesKey)) { ?>
    <div id="<?php echo esc_attr($extendifySdkNoticesKey); ?>" class="notice notice-info"
        style="display:flex;align-items:stretch;justify-content:space-between;position:relative">
        <div style="display:flex;align-items:center;position:relative">
            <div style="margin-right:1.5rem;">
                <svg width="60" height="60" viewBox="0 0 103 103" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <title>Extendify Logo</title>
                    <rect y="25.75" width="70.8125" height="77.25" fill="black" />
                    <rect x="45.0625" width="57.9375" height="57.9375" fill="#37C2A2" />
                </svg>
            </div>
            <div>
                <h3 style="margin-bottom:0.25rem;">
                    <?php esc_html_e('Special offer: Save 60% off Extendify Pro', 'extendify-sdk'); ?></h3>
                <div style="max-width:850px;">
                <p>
                    <?php esc_html_e('Thank you for using Editor Plus by Extendify. For a limited time, sign up for Extendify Pro and save 60% using coupon code launch60. Extendify Pro gives full access to thousands of templates and patterns designed for the Gutenberg block editor.', 'extendify-sdk'); ?>
                </p>
                <p style="max-width:850px;">
                    <?php
                        // translators: %s surrounding the word 'here' and is wrapped with <a>.
                        printf(esc_html__('Click %1$shere%2$s to sign up today!', 'extendify-sdk'), '<a target="_blank" href="' . esc_url($extendifySdkUrl) . '">', '</a>'); ?>
                </p>
                </div>
            </div>
        </div>
        <div style="margin:5px -5px 0 0;">
            <button
                style="max-width:15px;border:0;background:0;color: #7b7b7b;white-space:nowrap;cursor: pointer;padding: 0"
                type="button"
                title="<?php esc_attr_e('Dismiss notice', 'extendify-sdk'); ?>"
                aria-label="<?php esc_attr_e('Dismiss Extendify notice', 'extendify-sdk'); ?>"
                onclick="jQuery('#<?php echo esc_attr($extendifySdkNoticesKey); ?>').remove();jQuery.post(window.ajaxurl, {action: 'handle_<?php echo esc_attr($extendifySdkNoticesKey); ?>', _wpnonce: '<?php echo esc_attr($extendifySdkNoticesNonce); ?>' });">
                <svg width="15" height="15" style="width:100%" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
            <?php
        }//end if
    }
);

add_action(
    'wp_ajax_handle_' . $extendifySdkNoticesKey,
    function () use ($extendifySdkNoticesKey) {
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), $extendifySdkNoticesKey)) {
            wp_send_json_error(
                ['message' => esc_html__('The security check failed. Please refresh the page and try again.', 'extendify-sdk')],
                401
            );
        }

        update_user_option(get_current_user_id(), $extendifySdkNoticesKey, time());
        wp_send_json_success();
    }
);
