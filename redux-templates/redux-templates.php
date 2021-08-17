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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define Version.
define( 'REDUXTEMPLATES_VERSION', Redux_Core::$version );

// Define File DIR.
define( 'REDUXTEMPLATES_FILE', __FILE__ );

// Define Dir URL.
define( 'REDUXTEMPLATES_DIR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

// Define Physical Path.
define( 'REDUXTEMPLATES_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

// Version Check & Include Core.
if ( version_compare( PHP_VERSION, '7.1', '>=' ) && version_compare( get_bloginfo( 'version' ), '5.4', '>=' ) ) {
	Redux_Functions_Ex::register_class_path( 'ReduxTemplates', REDUXTEMPLATES_DIR_PATH . 'classes/' );
	new ReduxTemplates\Init();
}
