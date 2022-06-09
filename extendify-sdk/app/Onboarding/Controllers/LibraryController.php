<?php
/**
 * Library Controller
 */

namespace Extendify\Onboarding\Controllers;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for handling items associated with the library
 */
class LibraryController
{
    /**
     * Update the site type.
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function updateSiteType($request)
    {
        $siteType = $request->get_param('siteType');
        if (defined('EXTENDIFY_PATH') && is_readable(EXTENDIFY_PATH . 'app/User.php')) {
            include_once EXTENDIFY_PATH . 'app/User.php';
        }

        if (method_exists('Extendify\User', 'updateSiteType')) {
            \Extendify\User::updateSiteType($siteType);
        }

        return new \WP_REST_Response(['success' => true], 200);
    }
}
