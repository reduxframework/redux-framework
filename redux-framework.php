<?php // phpcs:ignore Squiz.Commenting.FileComment.Missing
/**
 * Redux, a simple, truly extensible and fully responsive option framework
 * for WordPress themes and plugins. Developed with WordPress coding
 * standards and PHP best practices in mind.
 *
 * Plugin Name:     Redux
 * Plugin URI:      http://wordpress.org/plugins/redux-framework
 * GitHub URI:      reduxframework/redux-framework
 * Description:     Build better sites in WordPress fast
 * Author:          Redux.io
 * Author URI:      http://redux.io
 * Version:         4.2.14
 * Text Domain:     redux-framework
 * License:         GPLv3 or later
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Provides:        ReduxFramework
 *
 * @package         ReduxFramework
 * @author          Redux.io by Dovy Paukstys <dovy@redux.io>
 * @license         GNU General Public License, version 3
 * @copyright       2012-2020 Redux.io
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'REDUX_PLUGIN_FILE' ) ) {
	define( 'REDUX_PLUGIN_FILE', __FILE__ );
}

// Including extendify sdk.
if ( file_exists( plugin_dir_path( REDUX_PLUGIN_FILE ) . 'extendify-sdk/loader.php' ) ) {
	$GLOBALS['extendifySdkSourcePlugin'] = 'Redux';
    require_once plugin_dir_path( __FILE__ ) . 'extendify-sdk/loader.php';
}

// Require the main plugin class.
require_once plugin_dir_path( __FILE__ ) . 'class-redux-framework-plugin.php';

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'Redux_Framework_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Redux_Framework_Plugin', 'deactivate' ) );

// Get plugin instance.
Redux_Framework_Plugin::instance();
