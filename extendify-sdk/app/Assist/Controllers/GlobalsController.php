<?php
/**
 * Controls Global Settings
 */

namespace Extendify\Assist\Controllers;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for plugin dependency checking, etc
 */
class GlobalsController
{

    /**
     * Return the data
     *
     * @return \WP_REST_Response
     */
    public static function get()
    {
        $data = get_option('extendify_assist_globals', []);
        return new \WP_REST_Response($data);
    }

    /**
     * Persist the data
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function store($request)
    {
        $data = json_decode($request->get_param('data'), true);
        update_option('extendify_assist_globals', $data);
        return new \WP_REST_Response($data);
    }
}
