<?php
/**
 * Controls Plugins
 */

namespace Extendify\Library\Controllers;

use Extendify\Library\Plugin;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * The controller for plugin dependency checking, etc
 */
class PluginController
{

    /**
     * Return all plugins
     *
     * @return array
     */
    public static function index()
    {
        if (! function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return \get_plugins();
    }

    /**
     * List active plugins
     *
     * @return array
     */
    public static function active()
    {
        return \get_option('active_plugins');
    }

    /**
     * Install plugins
     *
     * @param \WP_REST_Request $request - The request.
     * @return bool|WP_Error
     */
    public static function install($request)
    {
        if (!\current_user_can('activate_plugins')) {
            return new \WP_Error('not_allowed', __('You are not allowed to activate plugins on this site.', 'extendify'));
        }

        $requiredPlugins = json_decode($request->get_param('plugins'), true);
        foreach ($requiredPlugins as $plugin) {
            $status = Plugin::install_and_activate_plugin($plugin);
            if (\is_wp_error($status)) {
                // Return first error encountered.
                return $status;
            }
        }

        return true;
    }
}
