<?php
/**
 * Controls Support Articles
 */

namespace Extendify\Assist\Controllers;

use Extendify\Http;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for fetching support articles
 */
class SupportArticlesController
{
    /**
     * Return support articles from source.
     *
     * @return \WP_REST_Response
     */
    public static function articles()
    {
        $response = Http::get('/support-articles');
        return new \WP_REST_Response(
            $response,
            wp_remote_retrieve_response_code($response)
        );
    }

    /**
     * Return support article categories from source.
     *
     * @return \WP_REST_Response
     */
    public static function categories()
    {
        $response = Http::get('/support-article-categories');
        return new \WP_REST_Response(
            $response,
            wp_remote_retrieve_response_code($response)
        );
    }

    /**
     * Return the selected support article from source.
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function article($request)
    {
        $url = add_query_arg(
            'slug',
            $request->get_param('slug'),
            'https://wordpress.org/documentation/wp-json/wp/v2/articles'
        );
        $response = \wp_remote_get($url);
        if (\is_wp_error($response)) {
            wp_send_json_error($response->get_error_message(), 404);
        }

        return new \WP_REST_Response(
            ['data' => json_decode(\wp_remote_retrieve_body($response), true)],
            \wp_remote_retrieve_response_code($response)
        );
    }

    /**
     * Attempts to find a redirect URL from the old docs site
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function getRedirect($request)
    {
        $url = 'https://wordpress.org' . $request->get_param('path');
        $response = \wp_remote_head($url);
        $location = \wp_remote_retrieve_header($response, 'location');
        if (\is_wp_error($response)) {
            wp_send_json_error(\__('Page not found', 'extendify'), 404);
        }

        // No redirect, we're done.
        if (empty($location)) {
            return new \WP_REST_Response(['data' => $url], 200);
        }

        // Keep going until no more redirects.
        $request->set_param('path', \wp_parse_url($location, PHP_URL_PATH));
        return self::getRedirect($request);
    }
}
