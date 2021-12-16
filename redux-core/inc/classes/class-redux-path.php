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
		public static function init() {

		}

		/**
		 * Gets Redux path.
		 *
		 * @param string $relative_path Self-explanatory.
		 *
		 * @return string
		 */
		public static function get_path( string $relative_path ): string {
			$path = Redux_Core::$redux_path . $relative_path;

			if ( Redux_Core::$pro_loaded ) {
				$pro_path = '';

				if ( class_exists( 'Redux_Pro' ) ) {
					$pro_path = Redux_Pro::$dir . '/core' . $relative_path;
				}

				if ( file_exists( $pro_path ) ) {
					$path = $pro_path;
				}
			}

			return $path;
		}

		/**
		 * Require class.
		 *
		 * @param string $relative_path Path.
		 */
		public static function require_class( string $relative_path ) {
			$path = self::get_path( $relative_path );

			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}

	Redux_Path::init();
}
