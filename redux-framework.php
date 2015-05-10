<?php
/**
 * The Redux Framework Plugin
 *
 * A simple, truly extensible and fully responsive options framework
 * for WordPress themes and plugins. Developed with WordPress coding
 * standards and PHP best practices in mind.
 *
 * Plugin Name:     Redux Framework
 * Plugin URI:      http://wordpress.org/plugins/redux-framework
 * Github URI:      https://github.com/ReduxFramework/redux-framework
 * Description:     Redux is a simple, truly extensible options framework for WordPress themes and plugins.
 * Author:          Team Redux
 * Author URI:      http://reduxframework.com
 * Version:         3.5.4.8
 * Text Domain:     redux-framework
 * License:         GPL3+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:     /ReduxFramework/ReduxCore/languages
 *
 * @package         ReduxFramework
 * @author          Dovy Paukstys <dovy@reduxframework.com>
 * @author          Kevin Provance <kevin@reduxframework.com>
 * @author          Daniel J Griffiths <ghost1227@reduxframework.com>
 * @license         GNU General Public License, version 3
 * @copyright       2012-2015 Redux Framework
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( ! defined( 'REDUX_PATH' ) ) {
    define( 'REDUX_PATH', dirname( __FILE__ ) );
}
/**
 * The Redux framework class autoloader.
 * Finds the path to a class that we're requiring and includes the file.
 */
function redux_autoload_classes( $class_name ) {

    // No need to procedd if the class already exists
    if ( class_exists( $class_name ) ) {
        return;
    }

	if ( 0 === stripos( $class_name, 'Redux' ) ) {

		$foldername = ( 0 === stripos( $class_name, 'Redux_Validation_' ) ) ? 'validation'  : '';
		$foldername = ( '' != $foldername ) ? $foldername . DIRECTORY_SEPARATOR : '';

        if ( 'Redux' == $class_name ) {
            $class_path = REDUX_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'class-redux.php';
        } else {
            $class_path = REDUX_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . $foldername . 'class-' . strtolower( str_replace( '_', '-', $class_name ) ) . '.php';
        }

		if ( file_exists( $class_path ) ) {
			include $class_path;
		}

	}

}
// Run the autoloader
spl_autoload_register( 'redux_autoload_classes' );

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'Redux_Framework_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Redux_Framework_Plugin', 'deactivate' ) );

// Get plugin instance
//add_action( 'plugins_loaded', array( 'Redux_Framework_Plugin', 'instance' ) );

// The above line prevents ReduxFramework from instancing until all plugins have loaded.
// While this does not matter for themes, any plugin using Redux will not load properly.
// Waiting until all plugins have been loaded prevents the ReduxFramework class from
// being created, and fails the !class_exists('ReduxFramework') check in the sample_config.php,
// and thus prevents any plugin using Redux from loading their config file.
Redux_Framework_Plugin::instance();
