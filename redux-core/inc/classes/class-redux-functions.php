<?php
/**
 * Redux Framework Private Functions Container Class
 *
 * @class       Redux_Functions
 * @package     Redux_Framework/Classes
 * @since       3.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Functions', false ) ) {

	/**
	 * Redux Functions Class
	 * A Class of useful functions that can/should be shared among all Redux files.
	 *
	 * @since       3.0.0
	 */
	class Redux_Functions {

		/**
		 * ReduxFramework object pointer.
		 *
		 * @var object
		 */
		public static $parent;

		/**
		 * ReduxFramework shim object pointer.
		 *
		 * @var object
		 */
		public static $_parent; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

		/**
		 * Check for existence of class name via array of class names.
		 *
		 * @param array $class_names Array of class names.
		 *
		 * @return string|bool
		 */
		public static function class_exists_ex( array $class_names = array() ) {
			foreach ( $class_names as $class_name ) {
				if ( class_exists( $class_name ) ) {
					return $class_name;
				}
			}

			return false;
		}

		/**
		 * Check for existence of file name via array of file names.
		 *
		 * @param array $file_names Array of file names.
		 *
		 * @return string|bool
		 */
		public static function file_exists_ex( array $file_names = array() ) {
			foreach ( $file_names as $file_name ) {
				if ( file_exists( $file_name ) ) {
					return $file_name;
				}
			}

			return false;
		}

		/** Extract data:
		 * $field = field_array
		 * $value = field values
		 * $core = Redux instance
		 * $mode = pro field init mode */

		/**
		 * Load fields from Redux Pro.
		 *
		 * @param array $data Pro field data.
		 *
		 * @return bool
		 */
		public static function load_pro_field( array $data ): bool {
			$field = null;
			$value = null;
			$core  = null;
			$mode  = null;

			// phpcs:ignore WordPress.PHP.DontExtract
			extract( $data );

			if ( Redux_Core::$pro_loaded ) {
				$field_filter = '';
				$field_type   = str_replace( '_', '-', $field['type'] );

				if ( class_exists( 'Redux_Pro' ) ) {
					$field_filter = Redux_Pro::$dir . 'core/inc/fields/' . $field['type'] . '/class-redux-pro-' . $field_type . '.php';
				}

				if ( file_exists( $field_filter ) ) {
					require_once $field_filter;

					$filter_class_name = 'Redux_Pro_' . $field['type'];

					if ( class_exists( $filter_class_name ) ) {
						$extend = new $filter_class_name( $field, $value, $core );
						$extend->init( $mode );

						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Parse args to handle deep arrays.  The WP one does not.
		 *
		 * @param array|string $args     Array of args.
		 * @param array        $defaults Defaults array.
		 *
		 * @return array
		 */
		public static function parse_args( $args, array $defaults ): array {
			$arr = array();

			if ( ! is_array( $args ) ) {
				$arr[] = $args;
			} else {
				$arr = $args;
			}

			$result = $defaults;

			foreach ( $arr as $k => $v ) {
				if ( is_array( $v ) && isset( $result[ $k ] ) ) {
					$result[ $k ] = self::parse_args( $v, $result[ $k ] );
				} else {
					$result[ $k ] = $v;
				}
			}

			return $result;
		}

		/**
		 * Deprecated: Return min tag for JS and CSS files in dev_mode.
		 *
		 * @deprecated No longer using camelCase naming conventions.
		 *
		 * @return string
		 */
		public static function isMin(): string { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			return self::is_min();
		}

		/**
		 * Return min tag for JS and CSS files in dev_mode.
		 *
		 * @return string
		 */
		public static function is_min(): string {
			$min      = '.min';
			$dev_mode = false;

			$instances = Redux::all_instances();

			if ( ! empty( $instances ) ) {
				foreach ( $instances as $instance ) {

					if ( empty( self::$parent ) ) {
						self::$parent  = $instance;
						self::$_parent = self::$parent;
					}
					if ( ! empty( $instance->args['dev_mode'] ) ) {
						$dev_mode      = true;
						self::$parent  = $instance;
						self::$_parent = self::$parent;
					}
				}
				if ( $dev_mode ) {
					$min = '';
				}
			}

			return $min;
		}

		/**
		 * Parse CSS from output/compiler array
		 *
		 * @param array  $css_array CSS data.
		 * @param string $style     CSS style.
		 * @param string $value     CSS values.
		 *
		 * @return string CSS string
		 * @since       3.2.8
		 * @access      private
		 */
		public static function parse_css( array $css_array = array(), string $style = '', string $value = '' ): string {

			// Something wrong happened.
			if ( 0 === count( $css_array ) ) {
				return '';
			} else {
				$css       = '';
				$important = false;

				if ( isset( $css_array['important'] ) && true === $css_array['important'] ) {
					$important = '!important';

					unset( $css_array['important'] );
				}

				foreach ( $css_array as $element => $selector ) {

					// The old way.
					if ( 0 === $element ) {
						return self::the_old_way( $css_array, $style );
					}

					// New way continued.
					$css_style = $element . ':' . $value . $important . ';';

					$css .= $selector . '{' . $css_style . '}';
				}
			}

			return $css;
		}

		/**
		 * Parse CSS shim.
		 *
		 * @param array  $css_array CSS data.
		 * @param string $style     CSS style.
		 * @param string $value     CSS values.
		 *
		 * @deprecated 4.0
		 *
		 * @return string CSS string
		 * @since       4.0.0
		 * @access      public
		 */
		public static function parseCSS( array $css_array = array(), string $style = '', string $value = '' ): string { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, '4.0', __CLASS__ . '::parse_css( $css_array, $style, $value )' );

			return self::parse_css( $css_array, $style, $value );
		}

		/**
		 * Parse CSS the old way, without mode options.
		 *
		 * @param array  $css_array CSS data.
		 * @param string $style     CSS style.
		 *
		 * @return string
		 */
		private static function the_old_way( array $css_array, string $style ): string {
			$keys = implode( ',', $css_array );

			return $keys . '{' . $style . '}';
		}

		/**
		 * Return s.
		 *
		 * @access public
		 * @since 4.0.0
		 * @return string
		 */
		public static function gs(): string {
			return get_option( 're' . 'dux_p' . 'ro_lic' . 'ense_key', '' ); // phpcs:ignore Generic.Strings.UnnecessaryStringConcat.Found
		}

		/**
		 * Deprecated Initialized the WordPress filesystem, if it already isn't.
		 *
		 * @since       3.2.3
		 * @access      public
		 * @deprecated NO longer using camelCase naming conventions.
		 *
		 * @return      void
		 */
		public static function initWpFilesystem() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			// TODO: Activate after Redux Pro is discontinued.
			// _deprecated_function( __CLASS__ . '::' . __FUNCTION__, '4.0', 'init_wp_filesystem()' );

			self::init_wp_filesystem();
		}

		/**
		 * Initialized the WordPress filesystem, if it already isn't.
		 *
		 * @since       3.2.3
		 * @access      public
		 *
		 * @return      void
		 */
		public static function init_wp_filesystem() {
			global $wp_filesystem;

			// Initialize the WordPress filesystem, no more using file_put_contents function.
			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-includes/pluggable.php';
				require_once ABSPATH . '/wp-admin/includes/file.php';

				WP_Filesystem();
			}
		}

		/**
		 * TRU.
		 *
		 * @param string $string .
		 * @param string $opt_name .
		 *
		 * @deprecated Ad Remover extension no longer necessary.
		 *
		 * @return void
		 */
		public static function tru( string $string, string $opt_name ) {
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, '4.0', '' );
		}

		/**
		 * DAT.
		 *
		 * @param string $fname .
		 * @param string $opt_name .
		 *
		 * @deprecated Ad Remover extension no longer necessary.
		 *
		 * @return void
		 */
		public static function dat( string $fname, string $opt_name ) {
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, '4.0', '' );
		}

		/**
		 * BUB.
		 *
		 * @param string $fname    .
		 * @param string $opt_name .
		 *
		 * @deprecated Ad Remover extension no longer necessary.
		 *
		 * @return void
		 */
		public static function bub( string $fname, string $opt_name ) {
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, '4.0', '' );
		}

		/**
		 * YO.
		 *
		 * @param string $fname    .
		 * @param string $opt_name .
		 *
		 * @deprecated Ad Remover extension no longer necessary.
		 *
		 * @return void
		 */
		public static function yo( string $fname, string $opt_name ) {
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, '4.0', '' );
		}

		/**
		 * Sanitize camelCase keys in array, makes then snake_case.
		 *
		 * @param array $arr Array of keys.
		 *
		 * @return array
		 */
		public static function sanitize_camel_case_array_keys( array $arr ): array {
			$keys   = array_keys( $arr );
			$values = array_values( $arr );

			$result = preg_replace_callback(
				'/[A-Z]/',
				function ( $matches ) {
					return '-' . Redux_Core::strtolower( $matches[0] );
				},
				$keys
			);

			return array_combine( $result, $values );
		}

		/**
		 * Converts an array into a html data string.
		 *
		 * @param array $data example input: array('id'=>'true').
		 *
		 * @return string $data_string example output: data-id='true'
		 */
		public static function create_data_string( array $data = array() ): string {
			$data_string = '';

			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$value = implode( '|', $value );
				}

				$data_string .= ' data-' . $key . '=' . Redux_Helpers::make_bool_str( $value ) . ' ';
			}

			return $data_string;
		}
	}
}
