<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     Redux_Templates
 * @subpackage  Core
 * @subpackage  Core
 * @author      Redux.io + Dovy Paukstys
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define Version.
define('REDUXTEMPLATES_VERSION', Redux_Core::$version);

// Define File DIR.
define('REDUXTEMPLATES_FILE', __FILE__);

// Define Dir URL.
define('REDUXTEMPLATES_DIR_URL', trailingslashit(plugin_dir_url(__FILE__)));

// Define Physical Path.
define('REDUXTEMPLATES_DIR_PATH', trailingslashit(plugin_dir_path(__FILE__)));

// Version Check & Include Core.
if (version_compare(PHP_VERSION, '5.4', '>=') && version_compare(get_bloginfo('version'), '5.4', '>=')) {
    Redux_Functions_Ex::register_class_path('ReduxTemplates', REDUXTEMPLATES_DIR_PATH . 'classes/');
    new ReduxTemplates\Init();

    // If they are a pro user, convert their key to use with Extendify
    if (! function_exists('get_userdata')) {
        require_once ABSPATH . '/wp-includes/pluggable.php';
    }
    $reduxProKey = get_option('redux_pro_license_key');
    if ($reduxProKey && !get_user_option('extendifysdk_redux_key_moved')) {
        try {
            $extendifyUserState = get_user_meta(get_current_user_id(), 'extendifysdk_user_data', false);
            if (!isset($extendifyUserState[0])) {
                $extendifyUserState[0] = '{}';
            }

            $extendifyUserData = json_decode($extendifyUserState[0], true);
            $extendifyUserData['state']['apiKey'] = $reduxProKey;
            update_user_meta(get_current_user_id(), 'extendifysdk_user_data', wp_json_encode($extendifyUserData));
        } catch (Exception $e) {
            // Just have it fail gracefully
        }
        // Run this regardless. If the try/catch failed, better not to keep trying as something else is wrong.
        // In that case we can expect them to come to support and we can give them a fresh key
        update_user_option(get_current_user_id(), 'extendifysdk_redux_key_moved', true);
    }

    // Load the Extendify Library
    if (is_readable(dirname(__FILE__) . '/extendify-sdk/loader.php')) {
        $GLOBALS['extendifySdkSourcePlugin'] = 'Redux';
        require plugin_dir_path(__FILE__) . 'extendify-sdk/loader.php';
    }
}
