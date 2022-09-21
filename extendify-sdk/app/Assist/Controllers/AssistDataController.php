<?php
/**
 * Controls Pages
 */

namespace Extendify\Assist\Controllers;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for plugin dependency checking, etc
 */
class AssistDataController
{

    /**
     * Return pages created via Launch.
     *
     * @return \WP_REST_Response
     */
    public static function getLaunchPages()
    {
        $pages = \get_pages(['sort_column' => 'post_title']);
        $pages = array_filter($pages, function ($page) {
            $meta = \metadata_exists( 'post', $page->ID, 'made_with_extendify_launch' );
            return $meta === true;
        });

        $data = array_map(function ($page) {
            $page->permalink = \get_the_permalink($page->ID);
            $path = \wp_parse_url($page->permalink)['path'];
            $parseSiteUrl = \wp_parse_url(\get_site_url());
            $page->url = $parseSiteUrl['scheme'] . '://' . $parseSiteUrl['host'] . $path;
            $page->madeWithLaunch = true;
            return $page;
        }, $pages);

        return new \WP_REST_Response([
            'success' => true,
            'data' => array_values($data),
        ]);
    }
}
