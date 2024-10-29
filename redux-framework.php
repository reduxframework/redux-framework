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
 * Version:             4.5.0.1
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

if ( ! is_php_version_compatible( '7.4' ) ) {
	printf(
		'<div id="redux-php-nope" class="notice notice-error"><p>%s</p></div>',
		wp_kses(
			sprintf(
			/* translators: 1: Redux Framework, 2: Required PHP version number, 3: Current PHP version number, 4: URL of PHP update help page */
				__( 'The %1$s plugin requires PHP version %2$s or higher. This site is running PHP version %3$s. The theme/plugin that relies on Redux will not run properly without a PHP update. <a href="%4$s">Learn about updating PHP</a>.', 'query-monitor' ),
				'Redux Framework',
				'<strong>7.4.0</strong>',
				'<strong>' . PHP_VERSION . '</strong>',
				'https://wordpress.org/support/update-php/'
			),
			array(
				'a'      => array(
					'href' => array(),
				),
				'strong' => array(),
			)
		)
	);

	return;
}

// Require the main plugin class.
require_once plugin_dir_path( __FILE__ ) . 'class-redux-framework-plugin.php';

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'Redux_Framework_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Redux_Framework_Plugin', 'deactivate' ) );

// Get plugin instance.
Redux_Framework_Plugin::instance();
