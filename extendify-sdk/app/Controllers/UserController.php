<?php
/**
 * Controls User info
 */

namespace Extendify\Library\Controllers;

use Extendify\Library\Http;
use Extendify\Library\User;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for managing user data like API keys, etc
 */
class UserController
{

    /**
     * Return the current user state
     *
     * @return array
     */
    public static function show()
    {
        return new \WP_REST_Response(User::state());
    }

    /**
     * Return meta info about the current user
     *
     * @param \WP_REST_Request $request - The request.
     * @return array
     */
    public static function meta($request)
    {
        $key = \sanitize_text_field(\wp_unslash($request->get_param('key')));
        return new \WP_REST_Response(User::data($key));
    }

    /**
     * Persist the data
     *
     * @param \WP_REST_Request $request - The request.
     * @return array
     */
    public static function store($request)
    {
        $userData = json_decode($request->get_param('data'), true);
        // Keep this key for historical reasons.
        \update_user_meta(\get_current_user_id(), 'extendifysdk_user_data', $userData);

        return new \WP_REST_Response(User::state());
    }

    /**
     * Delete the data
     *
     * @return array
     */
    public static function delete()
    {
        \delete_user_meta(\get_current_user_id(), 'extendifysdk_user_data');
        return new \WP_REST_Response(User::state());
    }

    /**
     * Sign up the user to the mailing list.
     *
     * @param \WP_REST_Request $request - The request.
     * @return WP_REST_Response|WP_Error
     */
    public static function mailingList($request)
    {
        $response = Http::post('/register-mailing-list', $request->get_params());
        return new \WP_REST_Response($response);
    }

    /**
     * Get the max imports
     *
     * @return WP_REST_Response|WP_Error
     */
    public static function maxImports()
    {
        $response = Http::get('/max-free-imports');
        return new \WP_REST_Response($response);
    }
}
