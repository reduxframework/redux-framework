<?php
/**
 * WP Controller
 */

namespace Extendify\Assist\Controllers;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for interacting with WordPress.
 */
class WPController
{
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

        return new \WP_REST_Response(['success' => true]);
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
        ]);
    }
}
