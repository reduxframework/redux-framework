<?php
/**
 * Plugin Name:       Extendify
 * Description:       Extendify is the platform of site design and creation tools for people that want to build a beautiful WordPress website with a library of patterns and full page layouts for the Gutenberg block editor.
 * Plugin URI:        https://extendify.com/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Author:            Extendify
 * Author URI:        https://extendify.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 * Version:           0.8.0
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       extendify
 *
 * Extendify is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Extendify is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if (!defined('ABSPATH')) {
	exit;
}

/** ExtendifySdk is the previous class name used */
if (!class_exists('ExtendifySdk') && !class_exists('Extendify')) :

	/**
	 * The Extendify Library
	 */
	// phpcs:ignore Squiz.Classes.ClassFileName.NoMatch,Squiz.Commenting.ClassComment.Missing,PEAR.Commenting.ClassComment.Missing
	final class Extendify
	{

		/**
		 * Var to make sure we only load once
		 *
		 * @var boolean $loaded
		 */
		public static $loaded = false;

		/**
		 * Set up the Library
		 *
		 * @return void
		 */
		public function __invoke()
		{
			// Allow users to disable the libary. The latter is left in for historical reasons.
			if (!apply_filters('extendify_load_library', true) || !apply_filters('extendifysdk_load_library', true)) {
				return;
			}

			if (version_compare(PHP_VERSION, '5.6', '<') || version_compare($GLOBALS['wp_version'], '5.5', '<')) {
				return;
			}

			if (!self::$loaded) {
				self::$loaded = true;
				require dirname(__FILE__) . '/bootstrap.php';
				if (!defined('EXTENDIFY_BASE_URL')) {
					define('EXTENDIFY_BASE_URL', plugin_dir_url(__FILE__));
				}
			}
		}
		// phpcs:ignore Squiz.Classes.ClassDeclaration.SpaceBeforeCloseBrace
	}

	$extendify = new Extendify();
	$extendify();
endif;
