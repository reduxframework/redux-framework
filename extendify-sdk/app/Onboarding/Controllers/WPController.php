<?php
/**
 * WP Controller
 */

namespace Extendify\Onboarding\Controllers;

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

        $themeJson = new \WP_Theme_JSON(json_decode($request->get_param('themeJson'), true), '');
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
