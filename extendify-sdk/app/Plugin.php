<?php
// phpcs:ignoreFile
// This class was copied from JetPack (mostly)
// so will be a bit of work to refactor
/**
 * Manage plugin dependencies
 */

namespace Extendify\Library;

class Plugin
{
    /**
     * Will return info about a plugin
     *
     * @param string $identifier The key of the plugin info.
     * @param string $plugin_id The plugin identifier string.
     * @return string
     */
    public static function getPluginInfo($identifier, $plugin_id)
    {
        if (!function_exists('get_plugins')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        foreach (get_plugins() as $plugin => $data) {
            if ($data[$identifier] === $plugin_id) {
                return $plugin;
            }
        }

        return false;
    }
}
