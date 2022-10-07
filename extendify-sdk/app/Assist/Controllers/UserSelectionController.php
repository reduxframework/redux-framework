<?php
/**
 * Controls User Selection Data
 */

namespace Extendify\Assist\Controllers;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for plugin dependency checking, etc
 */
class UserSelectionController
{

    /**
     * Return the data
     *
     * @return \WP_REST_Response
     */
    public static function get()
    {
        $data = get_option('extendify_user_selections', []);
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
        update_option('extendify_user_selections', $data);
        return new \WP_REST_Response($data);
    }
}
