<?php // phpcs:ignore Squiz.Commenting.FileComment.Missing
/**
 * Redux, a simple, truly extensible and fully responsive option framework
 * for WordPress themes and plugins. Developed with WordPress coding
 * standards and PHP best practices in mind.
 *
 * Plugin Name:         Redux Framework
 * Plugin URI:          https://wordpress.org/plugins/redux-framework
 * GitHub URI:          reduxframework/redux-framework
 * Description:         Build better sites in WordPress fast!
 * Version:             4.5.6.1
 * Requires at least:   5.0
 * Requires PHP:        7.4
 * Author:              Team Redux
 * Author URI:          https://redux.io
 * License:             GPLv3 or later
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:         redux-framework
 * Provides:            ReduxFramework
 *
 * @package             ReduxFramework
 * @author              Kevin Provance, Dovy Paukstys
 * @license             GNU General Public License, version 3
 * @copyright           2012-2024 Redux.io
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'REDUX_PLUGIN_FILE' ) ) {
	define( 'REDUX_PLUGIN_FILE', __FILE__ );
}

// This must be required before vendor/autoload.php so QM can serve its own message about PHP compatibility.
require_once __DIR__ . '/redux-core/inc/classes/class-redux-php.php';

if ( ! Redux_PHP::version_met() ) {
	add_action( 'all_admin_notices', 'Redux_PHP::php_version_nope' );
	return;
}

// Require the main plugin class.
require_once plugin_dir_path( __FILE__ ) . 'class-redux-framework-plugin.php';

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'Redux_Framework_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Redux_Framework_Plugin', 'deactivate' ) );

// Get plugin instance.
Redux_Framework_Plugin::instance();
