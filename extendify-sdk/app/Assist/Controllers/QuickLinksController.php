<?php
/**
 * Controls Quick Links
 */

namespace Extendify\Assist\Controllers;

use Extendify\Http;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for fetching quick links
 */
class QuickLinksController
{
    /**
     * Return quick links from source.
     *
     * @return \WP_REST_Response
     */
    public static function fetchQuickLinks()
    {
        $response = Http::get('/quicklinks');
        return new \WP_REST_Response(
            $response,
            wp_remote_retrieve_response_code($response)
        );
    }
}
