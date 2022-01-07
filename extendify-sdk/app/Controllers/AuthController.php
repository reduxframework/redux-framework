<?php
/**
 * Controls Auth
 */

namespace Extendify\Library\Controllers;

use Extendify\Library\Http;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for dealing registration and authentication
 */
class AuthController
{

    /**
     * Login a user to extendify - it will return the API key
     *
     * @param \WP_REST_Request $request - The request.
     * @return WP_REST_Response|WP_Error
     */
    public static function login($request)
    {
        $response = Http::post('/login', $request->get_params());
        return new \WP_REST_Response($response);
    }

    /**
     * Handle registration - It will return the API key.
     *
     * @param \WP_REST_Request $request - The request.
     * @return WP_REST_Response|WP_Error
     */
    public static function register($request)
    {
        $response = Http::post('/register', $request->get_params());
        return new \WP_REST_Response($response);
    }
}
