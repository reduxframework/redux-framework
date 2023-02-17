<?php
/**
 * Controls Tasks
 */

namespace Extendify\Assist\Controllers;

use Extendify\Http;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for tracking tour progress info
 */
class TourController
{
    /**
     * Return tasks from either database or source.
     *
     * @return \WP_REST_Response
     */
    public static function fetchTours()
    {
        $response = Http::get('/tours');
        return new \WP_REST_Response(
            $response,
            wp_remote_retrieve_response_code($response)
        );
    }

    /**
     * Return the data
     *
     * @return \WP_REST_Response
     */
    public static function get()
    {
        $data = get_option('extendify_assist_tour_progress', []);
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
        update_option('extendify_assist_tour_progress', $data);
        return new \WP_REST_Response($data);
    }
}
