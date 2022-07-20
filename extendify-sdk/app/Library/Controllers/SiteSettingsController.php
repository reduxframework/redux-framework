<?php
/**
 * Controls User info
 */

namespace Extendify\Library\Controllers;

use Extendify\Library\SiteSettings;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for managing Extendify SiteSettings.
 */
class SiteSettingsController
{

    /**
     * Return Current SiteSettings meta data
     *
     * @return array
     */
    public static function show()
    {
        $siteSettings = json_decode(SiteSettings::data(), true);
        // Keep the user sitetype in sync across all users.
        $siteType = \get_option('extendify_siteType', false);
        if ($siteType) {
            $siteSettings['state']['siteType'] = $siteType;
        }

        return new \WP_REST_Response(wp_json_encode($siteSettings));
    }

    /**
     * Persist the data
     *
     * @param \WP_REST_Request $request - The request.
     * @return array
     */
    public static function store($request)
    {
        $settingsData = json_decode($request->get_param('data'), true);
        \update_option(SiteSettings::key(), $settingsData, true);
        return new \WP_REST_Response(SiteSettings::data());
    }
    /**
     * Persist the data
     *
     * @param \WP_REST_Request $request - The request.
     * @return \WP_REST_Response
     */
    public static function updateOption($request)
    {
        $params = $request->get_json_params();
        \update_option($params['option'], $params['value']);
        return new \WP_REST_Response(['success' => true], 200);
    }
}
