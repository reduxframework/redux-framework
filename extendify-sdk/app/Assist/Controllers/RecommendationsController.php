<?php
/**
 * Controls Recommendations
 */

namespace Extendify\Assist\Controllers;

use Extendify\Http;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for fetching recommendations
 */
class RecommendationsController
{
    /**
     * Return recommendations from source.
     *
     * @return \WP_REST_Response
     */
    public static function fetchRecommendations()
    {
        $response = Http::get('/recommendations');
        return new \WP_REST_Response(
            $response,
            wp_remote_retrieve_response_code($response)
        );
    }
}
