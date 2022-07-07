<?php
/**
 * Data Controller
 */

namespace Extendify\Onboarding\Controllers;

use Extendify\Http;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for handling general data
 */
class DataController
{
    /**
     * Get Site type information.
     *
     * @return \WP_REST_Response
     */
    public static function getSiteTypes()
    {
        $response = Http::get('/site-types');
        return new \WP_REST_Response($response);
    }

    /**
     * Get styles with code template.
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function getStyles($request)
    {
        $siteType = $request->get_param('siteType');
        $styles = $request->get_param('styles');
        $response = Http::get('/styles', [
            'siteType' => $siteType,
            'styles' => $styles,
        ]);
        return new \WP_REST_Response($response);
    }
    /**
     * Get styles with code template.
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function getTemplate($request)
    {
        $response = Http::get('/templates', $request->get_params());
        if (\is_wp_error($response)) {
            // TODO: Maybe handle errors better here, or higher up in the Http class.
            wp_send_json_error(['message' => $response->get_error_message()], 400);
        }

        return new \WP_REST_Response($response);
    }

    /**
     * Get Site type information.
     *
     * @return \WP_REST_Response
     */
    public static function getLayoutTypes()
    {
        $response = Http::get('/layout-types');
        return new \WP_REST_Response($response);
    }

    /**
     * Get Goals information.
     *
     * @return \WP_REST_Response
     */
    public static function getGoals()
    {
        $response = Http::get('/goals');
        return new \WP_REST_Response($response);
    }

    /**
     * Get Goals information.
     *
     * @return \WP_REST_Response
     */
    public static function getSuggestedPlugins()
    {
        $response = Http::get('/suggested-plugins');
        return new \WP_REST_Response($response);
    }

    /**
     * Create an order.
     *
     * @return \WP_REST_Response
     */
    public static function createOrder()
    {
        $response = Http::post('/create-order');
        return new \WP_REST_Response($response);
    }
}
