<?php
/**
 * Controls Taxonomies
 */

namespace Extendify\Library\Controllers;

use Extendify\Http;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for dealing with taxonomies
 */
class TaxonomyController
{
    /**
     * Return all taxonomies
     *
     * @return WP_REST_Response|WP_Error
     */
    public static function index()
    {
        $response = Http::get('/taxonomies', []);
        return new \WP_REST_Response(
            $response,
            wp_remote_retrieve_response_code($response)
        );
    }
}
