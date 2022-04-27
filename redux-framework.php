<?php // phpcs:ignore Squiz.Commenting.FileComment.Missing
/**
 * Redux, a simple, truly extensible and fully responsive option framework
 * for WordPress themes and plugins. Developed with WordPress coding
 * standards and PHP best practices in mind.
 *
 * Plugin Name:         Template Library and Redux Framework
 * Plugin URI:          http://wordpress.org/plugins/redux-framework
 * GitHub URI:          reduxframework/redux-framework
 * Description:         Build better sites in WordPress fast!
 * Version:             4.3.13
 * Requires at least:   4.0
 * Requires PHP:        7.1
 * Author:              Extendify
 * Author URI:          https://extendify.com/?utm_source=redux&utm_medium=plugins-page&utm_campaign=by
 * License:             GPLv3 or later
 * License URI:         http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:         redux-framework
 * Provides:            ReduxFramework
 *
 * @package             ReduxFramework
 * @author              Extendify
 * @license             GNU General Public License, version 3
 * @copyright           2012-2021 Redux.io
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'REDUX_PLUGIN_FILE' ) ) {
	define( 'REDUX_PLUGIN_FILE', __FILE__ );
}

// Require the main plugin class.
require_once plugin_dir_path( __FILE__ ) . 'class-redux-framework-plugin.php';

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'Redux_Framework_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Redux_Framework_Plugin', 'deactivate' ) );

// Get plugin instance.
Redux_Framework_Plugin::instance();
