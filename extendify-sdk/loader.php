<?php
/**
 * This file is used to help side load the library.
 * Be sure to remove the front matter from extendify.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('extendifyCheckPluginInstalled')) {
    /**
     * Will be truthy if the plugin is installed.
     *
     * @param  string $name name of the plugin 'extendify'.
     * @return bool|string - will return path, ex. 'extendify/extendify.php'.
     */
    function extendifyCheckPluginInstalled($name)
    {
        if (!function_exists('get_plugins')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        foreach (get_plugins() as $plugin => $data) {
            if ($data['TextDomain'] === $name) {
                return $plugin;
            }
        }

        return false;
    }
}//end if

$extendifyPluginName = extendifyCheckPluginInstalled('extendify');
if ($extendifyPluginName) {
    // Exit if the library is installed and active.
    // Remember, this file is only loaded by partner plugins.
    if (is_plugin_active($extendifyPluginName)) {
        // If the SDK is active then ignore the partner plugins.
        $GLOBALS['extendify_sdk_partner'] = '';
        return false;
    }
}

// Next is first come, first serve. The later class is left in for historical reasons.
if (class_exists('Extendify') || class_exists('ExtendifySdk')) {
    return false;
}

require_once plugin_dir_path(__FILE__) . 'extendify.php';
