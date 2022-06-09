<?php
/**
 * WP Controller
 */

namespace Extendify\Onboarding\Controllers;

use Extendify\Onboarding\Helpers\ThemeJson;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for interacting with WordPress.
 */
class WPController
{

    /**
     * Parse theme.json file.
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function parseThemeJson($request)
    {
        if (!$request->get_param('themeJson')) {
            return new \WP_Error('invalid_theme_json', __('Invalid Theme.json file', 'extendify'));
        }

        $themeJson = new ThemeJson(json_decode($request->get_param('themeJson'), true), '');
        return new \WP_REST_Response([
            'success' => true,
            'styles' => $themeJson->get_stylesheet(),
        ], 200);
    }

    /**
     * Persist the data
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function saveThemeJson($request)
    {
        if (!$request->get_param('themeJson')) {
            return new \WP_Error('invalid_theme_json', __('Invalid Theme.json file', 'extendify'));
        }

        $themeJson = ThemeJson::justSanitize(json_decode($request->get_param('themeJson'), true));

        require_once ABSPATH . '/wp-admin/includes/file.php';
        $maybeError = \wp_edit_theme_plugin_file([
            'theme' => \get_option('template'),
            'file' => 'theme.json',
            'newcontent' => \wp_json_encode($themeJson, JSON_PRETTY_PRINT),
            'nonce' => \wp_create_nonce('edit-theme_' . \get_option('template') . '_theme.json'),
        ]);

        if (\is_wp_error($maybeError)) {
            return new \WP_Error('invalid_theme_json', __('Error creating theme.json file', 'extendify'));
        }

        return new \WP_REST_Response(['success' => true], 200);
    }

    /**
     * Persist the data
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function updateOption($request)
    {
        $params = $request->get_json_params();
        \update_option($params['option'], $params['value']);
        return new \WP_REST_Response(['success' => true], 200);
    }

    /**
     * Get a setting from the options table
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function getOption($request)
    {
        $value = \get_option($request->get_param('option'), null);
        return new \WP_REST_Response([
            'success' => true,
            'data' => $value,
        ], 200);
    }
}
