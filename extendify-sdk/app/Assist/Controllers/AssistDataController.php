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
     * @return array
     */
    public static function getLaunchPages()
    {
        $pages = \get_pages(['sort_column' => 'post_title']);
        $newPages = [];

        foreach ($pages as $page) {
            $meta = \metadata_exists( 'post', $page->ID, 'made_with_extendify_launch' );
            if ($meta === true) {
                $page->permalink = \get_the_permalink($page->ID);
                $path = \wp_parse_url($page->permalink)['path'];
                $parseSiteUrl = \wp_parse_url(\get_site_url());
                $page->url = $parseSiteUrl['scheme'] . '://' . $parseSiteUrl['host'] . $path;
                $page->madeWithLaunch = true;
                array_push($newPages, $page);
            }
        }

        return $newPages;
    }
}
