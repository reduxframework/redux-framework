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

defined( 'ABSPATH' ) || exit;

// Define Physical Path.
define( 'REDUXTEMPLATES_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

// Version Check & Include Core.
Redux_Functions_Ex::register_class_path( 'ReduxTemplates', REDUXTEMPLATES_DIR_PATH . 'classes/' );
new ReduxTemplates\Init();
