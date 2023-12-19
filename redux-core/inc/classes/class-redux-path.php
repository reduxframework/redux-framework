<?php
/**
 * Redux Path Class
 *
 * @class Redux_Path
 * @version 4.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Path', false ) ) {

	/**
	 * Class Redux_Path
	 */
	class Redux_Path {

		/**
		 * Class init
		 */
		public static function init() {}

		/**
		 * Gets Redux path.
		 *
		 * @param string $relative_path Self-explanatory.
		 *
		 * @return string
		 */
		public static function get_path( string $relative_path ): string {
			return Redux_Core::$redux_path . $relative_path;
		}
	}

	Redux_Path::init();
}
