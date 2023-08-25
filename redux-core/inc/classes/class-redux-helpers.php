<?php
/**
 * Redux Helper Class
 *
 * @noinspection PhpUndefinedFieldInspection
 * @noinspection PhpUnused
 *
 * @class   Redux_Helpers
 * @version 3.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Helpers', false ) ) {

	/**
	 * Redux Helpers Class
	 * A Class of useful functions that can/should be shared among all Redux files.
	 *
	 * @since       3.0.0
	 */
	class Redux_Helpers {

		/**
		 * Reusable supported unit array.
		 *
		 * @var array
		 */
		public static $array_units = array( '', '%', 'in', 'cm', 'mm', 'em', 'rem', 'ex', 'pt', 'pc', 'px', 'vh', 'vw', 'vmin', 'vmax', 'ch' );

		/**
		 * Retrieve the section array from field ID.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $field_id Field ID.
		 */
		public static function section_from_field_id( string $opt_name = '', string $field_id = '' ) {
			if ( '' !== $opt_name ) {
				$redux = Redux::instance( $opt_name );

				if ( is_object( $redux ) ) {
					$sections = $redux->sections;

					if ( is_array( $sections ) && ! empty( $sections ) ) {
						foreach ( $sections as $section ) {
							if ( ! empty( $section['fields'] ) ) {
								foreach ( $section['fields'] as $field ) {
									if ( is_array( $field ) && ! empty( $field ) ) {
										if ( isset( $field['id'] ) && $field['id'] === $field_id ) {
											return $section;
										}
									}
								}
							}
						}
					}
				}
			}

			return null;
		}

		/**
		 * Verify integer value.
		 *
		 * @param mixed $val Value to test.
		 *
		 * @return bool|false|int
		 */
		public static function is_integer( $val ) {
			if ( ! is_scalar( $val ) || is_bool( $val ) ) {
				return false;
			}

			return is_float( $val ) ? false : preg_match( '~^([+\-]?[0-9]+)$~', $val );
		}

		/**
		 * Deprecated. Gets panel tab number from the specified field.
		 *
		 * @param object       $redux ReduxFramework object.
		 * @param array|string $field  Field array.
		 *
		 * @return int|string
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function tabFromField( $redux, $field ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0', 'Redux_Helpers::tab_from_field( $parent, $field )' );

			return self::tab_from_field( $redux, $field );
		}

		/**
		 * Gets panel tab number from the specified field.
		 *
		 * @param object       $redux ReduxFramework object.
		 * @param array|string $field  Field array.
		 *
		 * @return int|string
		 */
		public static function tab_from_field( $redux, $field ) {
			foreach ( $redux->sections as $k => $section ) {
				if ( ! isset( $section['title'] ) ) {
					continue;
				}

				if ( ! empty( $section['fields'] ) ) {
					if ( self::recursive_array_search( $field, $section['fields'] ) ) {
						return $k;
					}
				}
			}

			return null;
		}

		/**
		 * Deprecated. Verifies if a specified field type is in use.
		 *
		 * @param array $fields Field arrays.
		 * @param array $field  Field array.
		 *
		 * @return bool
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function isFieldInUseByType( array $fields, array $field = array() ): bool { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			// phpcs:ignore Squiz.PHP.CommentedOutCode
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0', 'Redux_Helpers::is_field_in_use_by_type( $parent, $field )' );
			return self::is_field_in_use_by_type( $fields, $field );
		}

		/**
		 * Verifies if a specified field type is in use.
		 *
		 * @param array $fields Field arrays.
		 * @param array $field  Field arrays to check.
		 *
		 * @return bool
		 */
		public static function is_field_in_use_by_type( array $fields, array $field = array() ): bool {
			foreach ( $field as $name ) {
				if ( array_key_exists( $name, $fields ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Deprecated Verifies if field is in use.
		 *
		 * @param object $redux ReduxFramework object.
		 * @param string $field  Field type.
		 *
		 * @return bool
		 * @deprecated No longer using camelCase function names.
		 */
		public static function isFieldInUse( $redux, string $field ): bool { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0', 'Redux_Helpers::is_field_in_use( $parent, $field )' );

			return self::is_field_in_use( $redux, $field );
		}

		/**
		 * Verifies if field is in use.
		 *
		 * @param object $redux ReduxFramework object.
		 * @param string $field  Field type.
		 *
		 * @return bool
		 */
		public static function is_field_in_use( $redux, string $field ): bool {
			if ( empty( $redux->sections ) ) {
				return false;
			}

			foreach ( $redux->sections as $section ) {
				if ( ! isset( $section['title'] ) ) {
					continue;
				}

				if ( ! empty( $section['fields'] ) ) {
					if ( self::recursive_array_search( $field, $section['fields'] ) ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Returns major version from version number.
		 *
		 * @param string $v Version number.
		 *
		 * @return string
		 */
		public static function major_version( string $v ): string {
			$version = explode( '.', $v );
			if ( count( $version ) > 1 ) {
				return $version[0] . '.' . $version[1];
			} else {
				return $v;
			}
		}


		/**
		 * Deprecated. Checks for localhost environment.
		 *
		 * @return bool
		 * @deprecated No longer using camelCase naming convention.
		 * @since      4.0
		 */
		public static function isLocalHost(): bool { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0', 'Redux_Helpers::is_local_host()' );

			return self::is_local_host();
		}

		/**
		 * Checks for localhost environment.
		 *
		 * @return bool
		 */
		public static function is_local_host(): bool {
			$is_local = false;

			$domains_to_check = array_unique(
				array(
					'siteurl' => wp_parse_url( get_site_url(), PHP_URL_HOST ),
					'homeurl' => wp_parse_url( get_home_url(), PHP_URL_HOST ),
				)
			);

			$forbidden_domains = array(
				'wordpress.com',
				'localhost',
				'localhost.localdomain',
				'127.0.0.1',
				'::1',
				'local.wordpress.test',         // VVV pattern.
				'local.wordpress-trunk.test',   // VVV pattern.
				'src.wordpress-develop.test',   // VVV pattern.
				'build.wordpress-develop.test', // VVV pattern.
			);

			foreach ( $domains_to_check as $domain ) {
				// If it's empty, just fail out.
				if ( ! $domain ) {
					$is_local = true;
					break;
				}

				// None of the explicit localhosts.
				if ( in_array( $domain, $forbidden_domains, true ) ) {
					$is_local = true;
					break;
				}

				// No .test or .local domains.
				if ( preg_match( '#\.(test|local)$#i', $domain ) ) {
					$is_local = true;
					break;
				}
			}

			return $is_local;
		}

		/**
		 * Deprecated. Checks if WP_DEBUG is enabled.
		 *
		 * @return bool::is_wp_debug()
		 * @deprecated No longer using camelCase naming convention.
		 * @since      4.0
		 */
		public static function isWpDebug(): bool { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0', 'Redux_Functions_Ex::is_wp_debug()' );

			return self::is_wp_debug();
		}

		/**
		 * Checks if WP_DEBUG is enabled.
		 *
		 * @return bool
		 */
		public static function is_wp_debug(): bool {
			return ( defined( 'WP_DEBUG' ) && true === WP_DEBUG );
		}

		/**
		 * Deprecated. Determines if theme is parent.
		 *
		 * @param string $file Path to file.
		 *
		 * @return bool
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function isParentTheme( string $file ): bool { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0.0', 'Redux_Instances::is_parent_theme( $file )' );

			return self::is_parent_theme( $file );
		}

		/**
		 * Determines if theme is parent.
		 *
		 * @param string $file Path to theme dir.
		 *
		 * @return bool
		 */
		public static function is_parent_theme( string $file ): bool {
			$file = Redux_Functions_Ex::wp_normalize_path( $file );
			$dir  = Redux_Functions_Ex::wp_normalize_path( get_template_directory() );

			$file = str_replace( '//', '/', $file );
			$dir  = str_replace( '//', '/', $dir );

			if ( strpos( $file, $dir ) !== false ) {
				return true;
			}

			return false;
		}

		/**
		 * Deprecated. Moved to another class.
		 *
		 * @param string $file Path to file.
		 *
		 * @return string
		 * @deprecated Moved to another class.
		 */
		public static function wp_normalize_path( string $file ): string { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0.0', 'Redux_Functions_Ex::wp_normalize_path( $file )' );

			return Redux_Functions_Ex::wp_normalize_path( $file );
		}

		/**
		 * Deprecated. Determines if the theme is child.
		 *
		 * @param string $file Path to file.
		 *
		 * @return bool
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function isChildTheme( string $file ): bool { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0.0', 'Redux_Instances::is_child_theme( $file )' );

			return self::is_child_theme( $file );
		}

		/**
		 * Determines if the theme is child.
		 *
		 * @param string $file Path to theme dir.
		 *
		 * @return bool
		 */
		public static function is_child_theme( string $file ): bool {
			$file = Redux_Functions_Ex::wp_normalize_path( $file );
			$dir  = Redux_Functions_Ex::wp_normalize_path( get_stylesheet_directory() );

			$file = str_replace( '//', '/', $file );
			$dir  = str_replace( '//', '/', $dir );

			if ( strpos( $file, $dir ) !== false ) {
				return true;
			}

			return false;
		}

		/**
		 * Deprecated. Determines if file is a theme.
		 *
		 * @param string $file Path to file.
		 *
		 * @return bool
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function isTheme( string $file ): bool { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			// phpcs:ignore Squiz.PHP.CommentedOutCode
			// _deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0.0', 'Redux_Instances::is_theme( $file )' );

			return self::is_theme( $file );
		}

		/**
		 * Determines if file is a theme.
		 *
		 * @param string $file Path to fle to test.
		 *
		 * @return bool
		 */
		public static function is_theme( string $file ): bool {
			if ( true === self::is_child_theme( $file ) || true === self::is_parent_theme( $file ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Determines deep array status.
		 *
		 * @param array|string $needle   array to test.
		 * @param array        $haystack Array to search.
		 *
		 * @return bool
		 */
		public static function array_in_array( $needle, array $haystack ): bool {
			// Make sure $needle is an array for foreach.
			if ( ! is_array( $needle ) ) {
				$needle = array( $needle );
			}
			// For each value in $needle, return TRUE if in $haystack.
			foreach ( $needle as $pin ) {
				if ( in_array( $pin, $haystack, true ) ) {
					return true;
				}
			}

			// Return FALSE if none of the values from $needle are found in $haystack.
			return false;
		}

		/**
		 * Enum through an entire deep array.
		 *
		 * @param string|array $needle   String to search for.
		 * @param array        $haystack Array in which to search.
		 *
		 * @return bool
		 */
		public static function recursive_array_search( $needle, array $haystack ): bool {
			foreach ( $haystack as $value ) {
				if ( $needle === $value || ( is_array( $value ) && self::recursive_array_search( $needle, $value ) !== false ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Take a path and return it clean.
		 *
		 * @param string $path Path to clean.
		 *
		 * @return string
		 * @deprecated Replaced with wp_normalize_path.
		 * @since      3.1.7
		 */
		public static function cleanFilePath( string $path ): string { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			// TODO: Uncomment this after Redux Pro is discontinued.
			// phpcs:ignore Squiz.PHP.CommentedOutCode
			// _deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0', 'Redux_Functions_Ex::wp_normalize_path( $path )' );
			return Redux_Functions_Ex::wp_normalize_path( $path );
		}

		/**
		 * Create unique hash.
		 *
		 * @return string
		 */
		public static function get_hash(): string {
			$remote_addr = Redux_Core::$server['REMOTE_ADDR'] ?? '127.0.0.1';
			return md5( network_site_url() . '-' . $remote_addr );
		}

		/**
		 * Get info for specified file.
		 *
		 * @param string $file File to check.
		 *
		 * @return array|bool
		 */
		public static function path_info( string $file ) {
			$theme_info  = Redux_Functions_Ex::is_inside_theme( $file );
			$plugin_info = Redux_Functions_Ex::is_inside_plugin( $file );

			if ( false !== $theme_info ) {
				return $theme_info;
			} elseif ( false !== $plugin_info ) {
				return $plugin_info;
			}

			return array();
		}

		/**
		 * Compiles caller data for Redux.
		 *
		 * @param bool $simple Mode.
		 *
		 * @return array
		 */
		public static function process_redux_callers( bool $simple = false ): array {
			$data = array();

			foreach ( Redux_Core::$callers as $opt_name => $callers ) {
				foreach ( $callers as $caller ) {
					$plugin_info = self::is_inside_plugin( $caller );
					$theme_info  = self::is_inside_theme( $caller );

					if ( $theme_info ) {
						if ( ! isset( $data['theme'][ $theme_info['slug'] ] ) ) {
							$data['theme'][ $theme_info['slug'] ] = array();
						}
						if ( ! isset( $data['theme'][ $theme_info['slug'] ][ $opt_name ] ) ) {
							$data['theme'][ $theme_info['slug'] ][ $opt_name ] = array();
						}
						if ( $simple ) {
							$data['theme'][ $theme_info['slug'] ][ $opt_name ][] = $theme_info['basename'];
						} else {
							$data['theme'][ $theme_info['slug'] ][ $opt_name ][] = $theme_info;
						}
					} elseif ( $plugin_info ) {
						if ( ! isset( $data['plugin'][ $plugin_info['slug'] ] ) ) {
							$data['plugin'][ $plugin_info['slug'] ] = array();
						}
						if ( ! in_array( $opt_name, $data['plugin'][ $plugin_info['slug'] ], true ) ) {
							if ( ! isset( $data['plugin'][ $plugin_info['slug'] ][ $opt_name ] ) ) {
								$data['plugin'][ $plugin_info['slug'] ][ $opt_name ] = array();
							}
							if ( $simple ) {
								$data['plugin'][ $plugin_info['slug'] ][ $opt_name ][] = $plugin_info['basename'];
							} else {
								$data['plugin'][ $plugin_info['slug'] ][ $opt_name ][] = $plugin_info;
							}
						}
					}
				}
			}

			return $data;
		}

		/**
		 * Field Render Function.
		 * Takes the color hex value and converts to a rgba.
		 *
		 * @param string $hex   Color value.
		 * @param string $alpha Alpha value.
		 *
		 * @since ReduxFramework 3.0.4
		 */
		public static function hex2rgba( string $hex, string $alpha = '' ): string {
			$hex = ltrim( $hex, '#' );
			$hex = sanitize_hex_color_no_hash( $hex );

			if ( '' === $hex ) {
				return '';
			}

			if ( 3 === strlen( $hex ) ) {
				$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
				$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
				$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
			} else {
				$r = hexdec( substr( $hex, 0, 2 ) );
				$g = hexdec( substr( $hex, 2, 2 ) );
				$b = hexdec( substr( $hex, 4, 2 ) );
			}

			$rgb = $r . ',' . $g . ',' . $b;

			if ( '' === $alpha ) {
				return $rgb;
			} else {
				$alpha = floatval( $alpha );

				return 'rgba(' . $rgb . ',' . $alpha . ')';
			}
		}

		/**
		 * Deprecated. Returns string boolean value.
		 *
		 * @param mixed $variable String to convert to true boolean.
		 *
		 * @return mixed|array
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function makeBoolStr( $variable ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0.0', 'Redux_Instances::make_bool_str( $var )' );

			return self::make_bool_str( $variable );
		}

		/**
		 * Returns string boolean value.
		 *
		 * @param mixed $variable true|false to convert.
		 *
		 * @return mixed|array
		 */
		public static function make_bool_str( $variable ) {
			if ( 'false' === $variable || empty( $variable ) ) {
				return 'false';
			} elseif ( true === $variable || 'true' === $variable || 1 === $variable || '1' === $variable ) {
				return 'true';
			} else {
				return $variable;
			}
		}

		/**
		 * Compile a localized array.
		 *
		 * @param array $localize Array of localized strings.
		 *
		 * @return array
		 */
		public static function localize( array $localize ): array {

			return $localize;
		}

		/**
		 * Check mokama.
		 *
		 * @access public
		 * @since 4.0.0
		 * @return bool
		 */
		public static function mokama(): bool {
			if ( defined( 'RDX_MOKAMA' ) ) {
				return Redux_Functions_Ex::s();
			}

			return false;
		}

		/**
		 * Retrieves template version.
		 *
		 * @param string $file Path to template file.
		 *
		 * @return string
		 */
		public static function get_template_version( string $file ): string {
			$filesystem = Redux_Filesystem::get_instance();
			// Avoid notices if file does not exist.
			if ( ! file_exists( $file ) ) {
				return '';
			}

			$data = get_file_data( $file, array( 'version' ), 'plugin' );

			if ( ! empty( $data[0] ) ) {
				return $data[0];
			} else {
				$file_data = $filesystem->get_contents( $file );

				$file_data = str_replace( "\r", "\n", $file_data );
				$version   = '1.0.0';

				if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( '@version', '/' ) . '(.*)$/mi', $file_data, $match ) && $match[1] ) {
					$version = _cleanup_header_comment( $match[1] );
				}

				return $version;
			}
		}

		/**
		 * Create HTML attribute string.
		 *
		 * @param array $attributes Array of attributes.
		 */
		public static function html_attributes( array $attributes = array() ): string {
			return join(
				' ',
				array_map(
					function ( $key ) use ( $attributes ) {
						if ( is_bool( $attributes[ $key ] ) ) {
							return $attributes[ $key ] ? $key : '';
						}

						return $key . '="' . $attributes[ $key ] . '"';
					},
					array_keys( $attributes )
				)
			) . ' ';
		}

		/**
		 * Normalize extensions dir.
		 *
		 * @param string $dir Path to extensions.
		 *
		 * @return string
		 */
		public static function get_extension_dir( string $dir ): string {
			return trailingslashit( Redux_Functions_Ex::wp_normalize_path( dirname( $dir ) ) );
		}

		/**
		 * Normalize extensions URL.
		 *
		 * @param string $dir Path to extensions.
		 *
		 * @return array|string|string[]
		 */
		public static function get_extension_url( string $dir ) {
			$ext_dir = self::get_extension_dir( $dir );

			return str_replace( Redux_Functions_Ex::wp_normalize_path( WP_CONTENT_DIR ), WP_CONTENT_URL, $ext_dir );
		}

		/**
		 * Checks a nested capabilities array or string to determine if the current user meets the requirements.
		 *
		 * @param string|array $caps Permission string or array to check. See self::user_can() for details.
		 *
		 * @return bool Whether the user meets the requirements. False on invalid user.
		 * @since 3.6.3.4
		 */
		public static function current_user_can( $caps ): bool { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
			$current_user = wp_get_current_user();

			if ( empty( $current_user ) ) {
				return false;
			}

			$name_arr = func_get_args();
			$args     = array_merge( array( $current_user ), $name_arr );

			return call_user_func_array( array( __CLASS__, 'user_can' ), $args );
		}

		/**
		 * Checks a nested capabilities array or string to determine if the user meets the requirements.
		 * You can pass in a simple string like 'edit_posts' or an array of conditions.
		 * The capability 'relation' is reserved for controlling the relation mode (AND/OR), which defaults to AND.
		 * Max depth of 30 levels.  False is returned for any conditions exceeding max depth.
		 * If you want to check meta caps, you must also pass the object ID on which to check against.
		 * If you get the error: PHP Notice: Undefined offset: 0 in /wp-includes/capabilities.php, you didn't
		 * pass the required $object_id.
		 *
		 * @param int|WP_User  $user          User ID or WP_User object to check. Defaults to the current user.
		 * @param string|array $capabilities  Capability string or array to check. The array lets you use multiple
		 *                                    conditions to determine if a user has permission.
		 *                                    Invalid conditions are skipped (conditions which aren't a string/array/bool/number(cast to bool)).
		 *                                    Example array where the user needs to have either the 'edit_posts' capability OR doesn't have the
		 *                                    'delete_pages' cap OR has the 'update_plugins' AND 'add_users' capabilities.
		 *                                    array(
		 *                                    'relation'     => 'OR',      // Optional, defaults to AND.
		 *                                    'edit_posts',                // Equivalent to 'edit_posts' => true,
		 *                                    'delete_pages' => false,     // Tests that the user DOESN'T have this capability
		 *                                    array(                       // Nested conditions array (up to 30 nestings)
		 *                                    'update_plugins',
		 *                                    'add_users',
		 *                                    ),
		 *                                    ).
		 * @param int|null     $object_id         (Optional) ID of the specific object to check against if capability is a "meta" cap.
		 *                                    e.g. 'edit_post', 'edit_user', 'edit_page', etc.
		 *
		 * @return bool Whether the user meets the requirements.
		 *              Will always return false for:
		 *              - Invalid/missing user
		 *              - If the $capabilities is not a string or array
		 *              - Max nesting depth exceeded (for that level)
		 * @since 3.6.3.4
		 * @example
		 *        user_can( 42, 'edit_pages' );                        // Checks if user ID 42 has the 'edit_pages' cap.
		 *        user_can( 42, 'edit_page', 17433 );                  // Checks if user ID 42 has the 'edit_page' cap for post-ID 17433.
		 *        user_can( 42, array( 'edit_pages', 'edit_posts' ) ); // Checks if user ID 42 has both the 'edit_pages' and 'edit_posts' caps.
		 */
		public static function user_can( $user, $capabilities, int $object_id = null ): bool {
			static $depth = 0;

			if ( $depth >= 30 ) {
				return false;
			}

			if ( empty( $user ) ) {
				return false;
			}

			if ( ! is_object( $user ) ) {
				$user = get_userdata( $user );
			}

			if ( is_string( $capabilities ) ) {
				// Simple string capability check.
				$args = array( $user, $capabilities );

				if ( null !== $object_id ) {
					$args[] = $object_id;
				}

				return call_user_func_array( 'user_can', $args );
			} elseif ( ! is_array( $capabilities ) ) {
				// Only strings and arrays are allowed as valid capabilities.
				return false;
			}

			// Capability array check.
			$or = false;

			foreach ( $capabilities as $key => $value ) {
				if ( 'relation' === $key ) {
					if ( 'OR' === $value ) {
						$or = true;
					}

					continue;
				}

				/**
				 * Rules can be in 4 different formats:
				 * [
				 *   [0]      => 'foobar',
				 *   [1]      => array(...),
				 *   'foobar' => false,
				 *   'foobar' => array(...),
				 * ]
				 */
				if ( is_numeric( $key ) ) {
					// Numeric key.
					if ( is_string( $value ) ) {
						// Numeric key with a string value is the capability string to check
						// [0] => 'foobar'.
						$args = array( $user, $value );

						if ( null !== $object_id ) {
							$args[] = $object_id;
						}

						$expression_result = call_user_func_array( 'user_can', $args ) === true;
					} elseif ( is_array( $value ) ) {
						++$depth;

						$expression_result = self::user_can( $user, $value, $object_id );

						--$depth;
					} else {
						// Invalid types are skipped.
						continue;
					}
				} else {
					// Non-numeric key.
					if ( is_scalar( $value ) ) {
						$args = array( $user, $key );

						if ( null !== $object_id ) {
							$args[] = $object_id;
						}

						$expression_result = call_user_func_array( 'user_can', $args ) === (bool) $value;
					} elseif ( is_array( $value ) ) {
						++$depth;

						$expression_result = self::user_can( $user, $value, $object_id );

						--$depth;
					} else {
						// Invalid types are skipped.
						continue;
					}
				}

				// Check after every evaluation if we know enough to return a definitive answer.
				if ( $or ) {
					if ( $expression_result ) {
						// If the relation is OR, return on the first true expression.
						return true;
					}
				} elseif ( ! $expression_result ) {
					// If the relation is AND, return on the first false expression.
					return false;
				}
			}

			// If we get this far on an OR, then it failed.
			// If we get this far on an AND, then it succeeded.
			return ! $or;
		}

		/**
		 * Check if Google font update is needed.
		 *
		 * @return bool
		 */
		public static function google_fonts_update_needed(): bool {
			$path = trailingslashit( Redux_Core::$upload_dir ) . 'google_fonts.json';
			$now  = time();
			$secs = 60 * 60 * 24 * 7;

			if ( file_exists( $path ) ) {
				if ( ( $now - filemtime( $path ) ) < $secs ) {
					return false;
				}
			}

			return true;
		}

		/**
		 * Retrieve an updated Google font array.
		 *
		 * @param bool $download Flag to download to file.
		 *
		 * @return array|WP_Error
		 */
		public static function google_fonts_array( bool $download = false ) {
			if ( ! empty( Redux_Core::$google_fonts ) && ! self::google_fonts_update_needed() ) {
				return Redux_Core::$google_fonts;
			}

			$filesystem = Redux_Filesystem::get_instance();

			$path = trailingslashit( Redux_Core::$upload_dir ) . 'google_fonts.json';

			if ( ! file_exists( $path ) || ( file_exists( $path ) && $download && self::google_fonts_update_needed() ) ) {
				if ( $download ) {
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$url = apply_filters( 'redux/typography/google_fonts/url', 'https://raw.githubusercontent.com/reduxframework/google-fonts/master/google_fonts.json' );

					$request = wp_remote_get(
						$url,
						array(
							'timeout' => 20,
						)
					);

					if ( ! is_wp_error( $request ) ) {
						$body = wp_remote_retrieve_body( $request );
						if ( ! empty( $body ) ) {
							$filesystem->put_contents( $path, $body );
							Redux_Core::$google_fonts = json_decode( $body, true );
						}
					} else {
						return $request;
					}
				}
			} elseif ( file_exists( $path ) ) {
				Redux_Core::$google_fonts = json_decode( $filesystem->get_contents( $path ), true );
				if ( empty( Redux_Core::$google_fonts ) ) {
					$filesystem->unlink( $path );
				}
			}

			return Redux_Core::$google_fonts;
		}

		/**
		 * Deprecated. Gets all Redux instances
		 *
		 * @return array
		 * @deprecated No longer using camelCase naming convention and moved to a different class.
		 */
		public static function getReduxInstances(): array { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0.0', 'Redux_Instances::get_all_instances()' );

			return Redux_Instances::get_all_instances();
		}

		/**
		 * Is Inside Plugin
		 *
		 * @param string $file File name.
		 *
		 * @return array|bool
		 */
		public static function is_inside_plugin( string $file ) {

			// phpcs:ignore Squiz.PHP.CommentedOutCode
			// if ( substr( strtoupper( $file ), 0, 2 ) === 'C:' ) {
			// $file = ltrim( $file, 'C:' );
			// $file = ltrim( $file, 'c:' );
			// } .
			//
			$plugin_basename = plugin_basename( $file );

			if ( Redux_Functions_Ex::wp_normalize_path( $file ) !== '/' . $plugin_basename ) {
				$slug = explode( '/', $plugin_basename );
				$slug = $slug[0];

				return array(
					'slug'      => $slug,
					'basename'  => $plugin_basename,
					'path'      => Redux_Functions_Ex::wp_normalize_path( $file ),
					'url'       => plugins_url( $plugin_basename ),
					'real_path' => Redux_Functions_Ex::wp_normalize_path( dirname( realpath( $file ) ) ),
				);
			}

			return false;
		}

		/**
		 * Is inside theme.
		 *
		 * @param string $file File name.
		 *
		 * @return array|bool
		 */
		public static function is_inside_theme( string $file = '' ) {
			$theme_paths = array(
				Redux_Functions_Ex::wp_normalize_path( get_template_directory() )   => get_template_directory_uri(),
				Redux_Functions_Ex::wp_normalize_path( get_stylesheet_directory() ) => get_stylesheet_directory_uri(),
			);

			$theme_paths = array_unique( $theme_paths );

			$file_path = Redux_Functions_Ex::wp_normalize_path( $file );
			$filename  = explode( '/', $file_path );
			$filename  = end( $filename );
			foreach ( $theme_paths as $theme_path => $url ) {

				$real_path = Redux_Functions_Ex::wp_normalize_path( realpath( $theme_path ) );

				if ( strpos( $file_path, trailingslashit( $real_path ) ) !== false ) {
					$slug = explode( '/', Redux_Functions_Ex::wp_normalize_path( $theme_path ) );
					if ( empty( $slug ) ) {
						continue;
					}
					$slug          = end( $slug );
					$relative_path = explode( $slug, dirname( $file_path ) );

					if ( 1 === count( $relative_path ) ) {
						$relative_path = $file_path;
					} else {
						$relative_path = $relative_path[1];
					}
					$relative_path = ltrim( $relative_path, '/' );

					$data = array(
						'slug'      => $slug,
						'path'      => trailingslashit( trailingslashit( $theme_path ) . $relative_path ) . $filename,
						'real_path' => trailingslashit( trailingslashit( $real_path ) . $relative_path ) . $filename,
						'url'       => trailingslashit( trailingslashit( $url ) . $relative_path ) . $filename,
					);

					$basename         = explode( $data['slug'], $data['path'] );
					$basename         = end( $basename );
					$basename         = ltrim( $basename, '/' );
					$data['basename'] = trailingslashit( $data['slug'] ) . $basename;

					if ( is_child_theme() ) {
						$parent              = get_template_directory();
						$data['parent_slug'] = explode( '/', $parent );
						$data['parent_slug'] = end( $data['parent_slug'] );
						if ( $data['slug'] === $data['parent_slug'] ) {
							unset( $data['parent_slug'] );
						}
					}

					return $data;
				}
			}

			return false;
		}


		/**
		 * Get plugin options.
		 *
		 * @return array|mixed|void
		 */
		public static function get_plugin_options() {
			$defaults = array(
				'demo' => false,
			);
			$options  = array();

			// If multisite is enabled.
			if ( is_multisite() ) {

				// Get network activated plugins.
				$plugins = get_site_option( 'active_sitewide_plugins' );

				foreach ( $plugins as $file => $plugin ) {
					if ( strpos( $file, 'redux-framework.php' ) !== false ) {
						$options = get_site_option( 'ReduxFrameworkPlugin', $defaults );
					}
				}
			}

			// If options aren't set, grab them now!
			if ( empty( $options ) ) {
				$options = get_option( 'ReduxFrameworkPlugin', $defaults );
			}

			return $options;
		}

		/**
		 * Sanitize array keys and values.
		 *
		 * @param array $arr Array to sanitize.
		 */
		public static function sanitize_array( array $arr ): array {
			return self::array_map_r( 'wp_kses_post', $arr );
		}

		/**
		 * Recursive array map.
		 *
		 * @param string $func function to run.
		 * @param array  $arr  Array to clean.
		 *
		 * @return array
		 */
		private static function array_map_r( string $func, array $arr ): array {
			$new_arr = array();

			foreach ( $arr as $key => $value ) {
				$new_arr[ $key ] = ( is_array( $value ) ? self::array_map_r( $func, $value ) : ( is_array( $func ) ? call_user_func_array( $func, $value ) : $func( $value ) ) );
			}

			return $new_arr;
		}

		/**
		 * Material design colors.
		 *
		 * @param string $context Mode to use.
		 *
		 * @return array
		 */
		public static function get_material_design_colors( string $context = 'primary' ): array {
			$colors = array(
				'primary'     => array( '#FFFFFF', '#000000', '#F44336', '#E91E63', '#9C27B0', '#673AB7', '#3F51B5', '#2196F3', '#03A9F4', '#00BCD4', '#009688', '#4CAF50', '#8BC34A', '#CDDC39', '#FFEB3B', '#FFC107', '#FF9800', '#FF5722', '#795548', '#9E9E9E', '#607D8B' ),
				'red'         => array( '#FFEBEE', '#FFCDD2', '#EF9A9A', '#E57373', '#EF5350', '#F44336', '#E53935', '#D32F2F', '#C62828', '#B71C1C', '#FF8A80', '#FF5252', '#FF1744', '#D50000' ),
				'pink'        => array( '#FCE4EC', '#F8BBD0', '#F48FB1', '#F06292', '#EC407A', '#E91E63', '#D81B60', '#C2185B', '#AD1457', '#880E4F', '#FF80AB', '#FF4081', '#F50057', '#C51162' ),
				'purple'      => array( '#F3E5F5', '#E1BEE7', '#CE93D8', '#BA68C8', '#AB47BC', '#9C27B0', '#8E24AA', '#7B1FA2', '#6A1B9A', '#4A148C', '#EA80FC', '#E040FB', '#D500F9', '#AA00FF' ),
				'deep-purple' => array( '#EDE7F6', '#D1C4E9', '#B39DDB', '#9575CD', '#7E57C2', '#673AB7', '#5E35B1', '#512DA8', '#4527A0', '#311B92', '#B388FF', '#7C4DFF', '#651FFF', '#6200EA' ),
				'indigo'      => array( '#E8EAF6', '#C5CAE9', '#9FA8DA', '#7986CB', '#5C6BC0', '#3F51B5', '#3949AB', '#303F9F', '#283593', '#1A237E', '#8C9EFF', '#536DFE', '#3D5AFE', '#304FFE' ),
				'blue'        => array( '#E3F2FD', '#BBDEFB', '#90CAF9', '#64B5F6', '#42A5F5', '#2196F3', '#1E88E5', '#1976D2', '#1565C0', '#0D47A1', '#82B1FF', '#448AFF', '#2979FF', '#2962FF' ),
				'light-blue'  => array( '#E1F5FE', '#B3E5FC', '#81D4fA', '#4fC3F7', '#29B6FC', '#03A9F4', '#039BE5', '#0288D1', '#0277BD', '#01579B', '#80D8FF', '#40C4FF', '#00B0FF', '#0091EA' ),
				'cyan'        => array( '#E0F7FA', '#B2EBF2', '#80DEEA', '#4DD0E1', '#26C6DA', '#00BCD4', '#00ACC1', '#0097A7', '#00838F', '#006064', '#84FFFF', '#18FFFF', '#00E5FF', '#00B8D4' ),
				'teal'        => array( '#E0F2F1', '#B2DFDB', '#80CBC4', '#4DB6AC', '#26A69A', '#009688', '#00897B', '#00796B', '#00695C', '#004D40', '#A7FFEB', '#64FFDA', '#1DE9B6', '#00BFA5' ),
				'green'       => array( '#E8F5E9', '#C8E6C9', '#A5D6A7', '#81C784', '#66BB6A', '#4CAF50', '#43A047', '#388E3C', '#2E7D32', '#1B5E20', '#B9F6CA', '#69F0AE', '#00E676', '#00C853' ),
				'light-green' => array( '#F1F8E9', '#DCEDC8', '#C5E1A5', '#AED581', '#9CCC65', '#8BC34A', '#7CB342', '#689F38', '#558B2F', '#33691E', '#CCFF90', '#B2FF59', '#76FF03', '#64DD17' ),
				'lime'        => array( '#F9FBE7', '#F0F4C3', '#E6EE9C', '#DCE775', '#D4E157', '#CDDC39', '#C0CA33', '#A4B42B', '#9E9D24', '#827717', '#F4FF81', '#EEFF41', '#C6FF00', '#AEEA00' ),
				'yellow'      => array( '#FFFDE7', '#FFF9C4', '#FFF590', '#FFF176', '#FFEE58', '#FFEB3B', '#FDD835', '#FBC02D', '#F9A825', '#F57F17', '#FFFF82', '#FFFF00', '#FFEA00', '#FFD600' ),
				'amber'       => array( '#FFF8E1', '#FFECB3', '#FFE082', '#FFD54F', '#FFCA28', '#FFC107', '#FFB300', '#FFA000', '#FF8F00', '#FF6F00', '#FFE57F', '#FFD740', '#FFC400', '#FFAB00' ),
				'orange'      => array( '#FFF3E0', '#FFE0B2', '#FFCC80', '#FFB74D', '#FFA726', '#FF9800', '#FB8C00', '#F57C00', '#EF6C00', '#E65100', '#FFD180', '#FFAB40', '#FF9100', '#FF6D00' ),
				'deep-orange' => array( '#FBE9A7', '#FFCCBC', '#FFAB91', '#FF8A65', '#FF7043', '#FF5722', '#F4511E', '#E64A19', '#D84315', '#BF360C', '#FF9E80', '#FF6E40', '#FF3D00', '#DD2600' ),
				'brown'       => array( '#EFEBE9', '#D7CCC8', '#BCAAA4', '#A1887F', '#8D6E63', '#795548', '#6D4C41', '#5D4037', '#4E342E', '#3E2723' ),
				'gray'        => array( '#FAFAFA', '#F5F5F5', '#EEEEEE', '#E0E0E0', '#BDBDBD', '#9E9E9E', '#757575', '#616161', '#424242', '#212121', '#000000', '#ffffff' ),
				'blue-gray'   => array( '#ECEFF1', '#CFD8DC', '#B0BBC5', '#90A4AE', '#78909C', '#607D8B', '#546E7A', '#455A64', '#37474F', '#263238' ),
			);

			$mui_arr = array(
				'50',
				'100',
				'200',
				'300',
				'400',
				'500',
				'600',
				'700',
				'800',
				'900',
				'A100',
				'A200',
				'A400',
				'A700',
			);

			if ( in_array( $context, $mui_arr, true ) ) {
				$key = absint( $context ) / 100;

				if ( 'A100' === $context ) {
					$key = 10;
					unset( $colors['grey'] );
				} elseif ( 'A200' === $context ) {
					$key = 11;
					unset( $colors['grey'] );
				} elseif ( 'A400' === $context ) {
					$key = 12;
					unset( $colors['grey'] );
				} elseif ( 'A700' === $context ) {
					$key = 13;
					unset( $colors['grey'] );
				}

				unset( $colors['primary'] );

				$position_colors = array();
				foreach ( $colors as $color_family ) {
					if ( isset( $color_family[ $key ] ) ) {
						$position_colors[] = $color_family[ $key ];
					}
				}

				return $position_colors;
			} elseif ( 'all' === $context ) {
				unset( $colors['primary'] );

				$all_colors = array();
				foreach ( $colors as $color_family ) {
					foreach ( $color_family as $color ) {
						$all_colors[] = $color;
					}
				}

				return $all_colors;
			} elseif ( 'primary' === $context ) {
				return $colors['primary'];
			} else {
				if ( isset( $colors[ $context ] ) ) {
					return $colors[ $context ];
				}

				return $colors['primary'];
			}
		}
	}
}
