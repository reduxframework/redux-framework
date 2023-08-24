<?php
/**
 * Social Profiles Helper library.
 *
 * @package     Redux
 * @subpackage  Extensions
 * @author      Kevin Provance (kprovance)
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Social_Profiles_Functions' ) ) {

	/**
	 * Class Redux_Social_Profiles_Functions
	 */
	class Redux_Social_Profiles_Functions {
		/**
		 * ReduxFramework object pointer.
		 *
		 * @var object
		 */
		public static $parent;

		/**
		 * Field ID.
		 *
		 * @var string
		 */
		public static $field_id;

		/**
		 * Field array.
		 *
		 * @var array
		 */
		public static $field;

		/**
		 * WordPress upload directory.
		 *
		 * @var string
		 */
		public static $upload_dir;

		/**
		 * WordPress upload URI.
		 *
		 * @var string
		 */
		public static $upload_url;

		/**
		 * Init helper library.
		 *
		 * @param object $redux ReduxFramework object.
		 */
		public static function init( $redux ) {
			self::$parent = $redux;

			if ( empty( self::$field_id ) ) {
				self::$field = self::get_field( $redux );

				if ( ! is_array( self::$field ) ) {
					return;
				}

				self::$field_id = self::$field['id'];
			}

			// Make sanitized upload dir DIR.
			self::$upload_dir = Redux_Functions_Ex::wp_normalize_path( Redux_Core::$upload_dir . 'social-profiles/' );

			// Make sanitized upload dir URL.
			self::$upload_url = Redux_Functions_Ex::wp_normalize_path( Redux_Core::$upload_url . 'social-profiles/' );

			Redux_Functions::initWpFilesystem();
		}

		/**
		 * Read data file.
		 *
		 * @return array|bool|mixed|object
		 */
		public static function read_data_file() {
			$file = self::get_data_path();

			if ( file_exists( $file ) ) {

				// Get the contents of the file and stuff it in a variable.
				$data = self::$parent->filesystem->execute( 'get_contents', $file );

				// Error or null, set the result to false.
				if ( false === $data || null === $data ) {
					$arr_data = false;

					// Otherwise, decode the json object and return it.
				} else {
					$arr      = json_decode( $data, true );
					$arr_data = $arr;
				}
			} else {
				$arr_data = false;
			}

			return $arr_data;
		}

		/**
		 * Write data file.
		 *
		 * @param array  $arr_data Data.
		 * @param string $file     Filename.
		 *
		 * @return bool
		 */
		public static function write_data_file( array $arr_data, string $file = '' ): bool {
			if ( ! is_dir( self::$upload_dir ) ) {
				return false;
			}

			$file = ( '' === $file ) ? self::get_data_path() : self::$upload_dir . $file;

			// Encode the array data.
			$data = wp_json_encode( $arr_data );

			// Write to its file on the server, return the return value
			// True on success, false on error.
			return self::$parent->filesystem->execute( 'put_contents', $file, array( 'content' => $data ) );
		}

		/**
		 * Get the data path.
		 *
		 * @return mixed|Redux_Functions_Ex|string
		 */
		public static function get_data_path() {
			return Redux_Functions_Ex::wp_normalize_path( self::$upload_dir . '/' . self::$parent->args['opt_name'] . '-' . self::$field_id . '.json' );
		}

		/**
		 * Get field.
		 *
		 * @param array|ReduxFramework $redux ReduxFramework object.
		 *
		 * @return mixed
		 */
		public static function get_field( $redux = array() ) {
			global $pagenow;

			if ( is_admin() && ( 'post-new.php' === $pagenow || 'post.php' === $pagenow ) ) {
				$inst = Redux_Instances::get_instance( self::$parent->args['opt_name'] );

				$ext = $inst->extensions;

				if ( isset( $ext['metaboxes'] ) ) {
					$obj   = $ext['metaboxes'];
					$boxes = ( $obj->boxes );

					foreach ( $boxes as $sections ) {
						foreach ( $sections['sections'] as $fields ) {
							if ( isset( $fields['fields'] ) ) {
								foreach ( $fields['fields'] as $f ) {
									if ( 'social_profiles' === $f['type'] ) {
										return $f;
									}

									if ( 'repeater' === $f['type'] ) {
										foreach ( $f['fields'] as $r ) {
											if ( 'social_profiles' === $r['type'] ) {
												return $r;
											}
										}
									}
								}
							}
						}
					}
				}
			} else {
				if ( ! empty( $redux ) ) {
					self::$parent = $redux;
				}

				if ( isset( self::$parent->field_sections['social_profiles'] ) ) {
					return reset( self::$parent->field_sections['social_profiles'] );
				}

				$arr = self::$parent;

				foreach ( $arr as $part => $bla ) {
					if ( 'sections' === $part ) {
						foreach ( $bla as $field ) {

							foreach ( $field as $arg => $val ) {
								if ( 'fields' === $arg ) {
									foreach ( $val as $v ) {
										if ( ! empty( $v ) ) {
											foreach ( $v as $id => $x ) {
												if ( 'type' === $id ) {
													if ( 'social_profiles' === $x ) {
														return $v;
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}

			return '';
		}

		/**
		 * Add extra icons.
		 *
		 * @param array $defaults Default values.
		 *
		 * @return array
		 */
		public static function add_extra_icons( array $defaults ): array {
			if ( empty( self::$field ) ) {
				self::$field = self::get_field();
			}

			if ( isset( self::$field['icons'] ) && ! empty( self::$field['icons'] ) ) {
				$cur_count = count( $defaults );

				foreach ( self::$field['icons'] as $arr ) {

					$skip_add = false;
					foreach ( $defaults as $i => $v ) {
						if ( $arr['id'] === $v['id'] ) {

							$defaults[ $i ] = array_replace( $v, $arr );
							$skip_add       = true;
							break;
						}
					}

					if ( ! $skip_add ) {
						$arr['order']           = $cur_count;
						$defaults[ $cur_count ] = $arr;
						++$cur_count;
					}
				}
			}

			return $defaults;
		}

		/**
		 * Get Included files.
		 *
		 * @param array $val Value.
		 *
		 * @return array
		 */
		private static function get_includes( array $val ): array {
			if ( empty( self::$field ) ) {
				self::$field = self::get_field();
			}

			if ( isset( self::$field['include'] ) && is_array( self::$field['include'] ) && ! empty( self::$field['include'] ) ) {
				$icons = self::$field['include'];

				$new_arr = array();

				$idx = 0;
				foreach ( $val as $arr ) {
					foreach ( $icons as $icon ) {
						if ( $icon === $arr['id'] ) {
							$arr['order']    = $idx;
							$new_arr[ $idx ] = $arr;
							++$idx;
							break;
						}
					}
				}
			} else {
				$new_arr = $val;
			}

			return $new_arr;
		}

		/**
		 * Returns default data from config.
		 *
		 * @return array
		 */
		public static function get_default_data(): array {
			$data = Redux_Social_Profiles_Defaults::get_social_media_defaults();
			$data = self::get_includes( $data );

			return self::add_extra_icons( $data );
		}

		/**
		 * Static function to render the social icon.
		 *
		 * @param string $icon       Icon css.
		 * @param string $color      Hex color.
		 * @param string $background Background color.
		 * @param string $title      Icon title.
		 * @param bool   $output     Print or echo.
		 *
		 * @return string|void
		 */
		public static function render_icon( string $icon, string $color, string $background, string $title, bool $output = true ) {
			if ( $color || $background ) {
				if ( '' === $color ) {
					$color = 'transparent';
				}

				if ( '' === $background ) {
					$background = 'transparent';
				}

				$inline = 'style="color:' . esc_attr( $color ) . ';background-color:' . esc_attr( $background ) . ';"';
			} else {
				$inline = '';
			}

			$str = '<i class="fa ' . $icon . '" ' . $inline . ' title="' . $title . '"></i>';

			if ( $output ) {
				echo $str; // phpcs:ignore WordPress.Security.EscapeOutput
			} else {
				return $str;
			}
		}
	}
}
