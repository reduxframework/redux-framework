<?php
/**
 * Controls User info
 */

namespace Extendify\ExtendifySdk\Controllers;

use Extendify\ExtendifySdk\SiteSettings;

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
        return new \WP_REST_Response(SiteSettings::data());
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
}
