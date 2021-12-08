<?php
/**
 * Use this file to load in the SDK from another plugin
 * Example: require_once plugin_dir_path(__FILE__) . 'extendify-templates-sdk/loader.php';
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('extendifysdkCheckPluginInstalled')) {
    /**
     * Will be truthy if the plugin is installed.
     *
     * @param  string $name name of the plugin 'extendify-sdk'.
     * @return bool|string - will return path, ex. 'extendify-sdk/extendify-sdk.php'.
     */
    function extendifysdkCheckPluginInstalled($name)
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

// If the template SDK development build is installed, default to that.
$extendifysdkSdk = extendifysdkCheckPluginInstalled('extendify-sdk');

if ($extendifysdkSdk) {
    // Only if it's deactivated.
    if (is_plugin_active($extendifysdkSdk)) {
        return false;
    }
}

// If Editor Plus is installed, next default to that.
$extendifysdkEditorPlus = extendifysdkCheckPluginInstalled('editor_plus');
if ($extendifysdkEditorPlus) {
    // Only if it's deactivated.
    if (is_plugin_active($extendifysdkEditorPlus)) {
        // Only if we aren't currently inside Editor Plus.
        if (strpos(basename(dirname(__DIR__)), 'editorplus') === false) {
            return false;
        }
    }
}

// Next is first come, first serve.
if (class_exists('ExtendifySdk')) {
    return false;
}

require_once plugin_dir_path(__FILE__) . 'extendify-sdk.php';
