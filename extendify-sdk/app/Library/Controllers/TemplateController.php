<?php
/**
 * Controls Http requests
 */

namespace Extendify\Library\Controllers;

use Extendify\Http;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for dealing with templates
 */
class TemplateController
{

    /**
     * Return info about a template
     *
     * @param \WP_REST_Request $request - The request.
     * @return WP_REST_Response|WP_Error
     */
    public static function index($request)
    {
        $response = Http::post('/templates', $request->get_params());
        return new \WP_REST_Response($response);
    }

    /**
     * Send data about a specific template
     *
     * @param \WP_REST_Request $request - The request.
     * @return WP_REST_Response|WP_Error
     */
    public static function ping($request)
    {
        $response = Http::post('/templates', $request->get_params());
        return new \WP_REST_Response($response);
    }
}
