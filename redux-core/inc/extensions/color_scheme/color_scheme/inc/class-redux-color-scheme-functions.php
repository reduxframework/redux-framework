<?php
/**
 * Color Scheme Helper library.
 *
 * @package     Redux Color Schemes Extension
 * @subpackage  Redux Extensions
 * @author      Kevin Provance (kprovance)
 * @copyright   Kevin Provance.  All rights Reserved.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Color_Scheme_Functions' ) ) {
	/**
	 * Class Redux_Color_Scheme_Functions
	 */
	class Redux_Color_Scheme_Functions {

		/**
		 * ReduxFramework object.
		 *
		 * @var object
		 */
		public static $parent;

		/**
		 * Field ID
		 *
		 * @var string
		 */
		public static $field_id;

		/**
		 * Field class.
		 *
		 * @var string
		 */
		public static $field_class;

		/**
		 * Field array.
		 *
		 * @var string
		 */
		public static $field;

		/**
		 * WP Upload directory.
		 *
		 * @var string
		 */
		public static $upload_dir;

		/**
		 * WP Upload URI
		 *
		 * @var string
		 */
		public static $upload_url;

		/**
		 * Select fields.
		 *
		 * @var array
		 */
		public static $select;

		/**
		 * Class init.
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
			self::$upload_dir = Redux_Functions_Ex::wp_normalize_path( ReduxFramework::$_upload_dir . 'color-schemes/' );

			// Make sanitized upload dir URL.
			self::$upload_url = Redux_Functions_Ex::wp_normalize_path( ReduxFramework::$_upload_url . 'color-schemes/' );

			Redux_Functions::init_wp_filesystem();
		}

		/**
		 * Checks if tooltips are in use.
		 *
		 * @param array $field Field array.
		 *
		 * @return bool
		 */
		public static function tooltips_in_use( array $field ): bool {
			$blocks = $field['default'];

			foreach ( $blocks as $arr ) {
				if ( isset( $arr['tooltip'] ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Convert DB values.
		 */
		public static function convert_to_db() {
			$upload_dir = Redux_Functions_Ex::wp_normalize_path( ReduxFramework::$_upload_dir . 'color-schemes/' );

			$cur_scheme_file = Redux_Functions_Ex::wp_normalize_path( $upload_dir . '/' . self::$parent->args['opt_name'] . '_' . self::$field_id . '.json' );

			if ( is_dir( $upload_dir ) ) {
				if ( file_exists( $cur_scheme_file ) ) {
					$data = self::$parent->filesystem->execute( 'get_contents', $cur_scheme_file );
					if ( ! empty( $data ) ) {
						$data = json_decode( $data, true );

						update_option( self::get_scheme_key(), $data );

						self::$parent->filesystem->execute( 'delete', $cur_scheme_file );
					}
				}
			}
		}

		/**
		 * Get scheme key.
		 *
		 * @return string
		 */
		public static function get_scheme_key(): string {
			return 'redux_cs_' . self::$parent->args['opt_name'] . '_' . self::$field_id;
		}

		/**
		 * Get the list of groups names for the color scheme table.
		 *
		 * @since       2.0.0
		 * @access      public static
		 * @return      array Array of group names.
		 */
		public static function get_group_names(): array {
			if ( empty( self::$field ) ) {
				self::$field = self::get_field();
			}

			if ( isset( self::$field['groups'] ) ) {
				if ( is_array( self::$field['groups'] ) && ! empty( self::$field['groups'] ) ) {
					return self::$field['groups'];
				}
			}

			return array();
		}

		/**
		 * Get output transparent value.
		 *
		 * @return mixed
		 */
		public static function get_output_transparent_val() {
			if ( empty( self::$field ) ) {
				self::$field = self::get_field();
			}

			if ( isset( self::$field['output_transparent'] ) ) {
				if ( ! empty( self::$field['output_transparent'] ) ) {
					return self::$field['output_transparent'];
				}
			}

			return false;
		}

		/**
		 * Get select field name.
		 *
		 * @return array
		 */
		private static function get_select_names(): array {
			if ( empty( self::$field ) ) {
				self::$field = self::get_field();
			}

			if ( isset( self::$field['select'] ) ) {
				if ( is_array( self::$field['select'] ) && ! empty( self::$field['select'] ) ) {
					return self::$field['select'];
				}
			}

			return array();
		}

		/**
		 * Get color scheme field.
		 *
		 * @param object|array $redux ReduxFramework pointer.
		 *
		 * @return mixed
		 */
		public static function get_field( $redux = array() ) {
			if ( ! empty( $redux ) ) {
				self::$parent = $redux;
			}

			if ( isset( $parent->field_sections['color_scheme'] ) ) {
				return reset( $parent->field_sections['color_scheme'] );
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
												if ( 'color_scheme' === $x ) {
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

			return null;
		}

		/**
		 * Output scheme dropdown selector.
		 *
		 * @param       string $selected Selected scheme name.
		 *
		 * @return      string HTML of dropdown selector.
		 * @since       1.0.0
		 * @access      public static
		 */
		public static function get_scheme_select_html( string $selected ): string {

			$html  = '<select name="' . esc_attr( self::$parent->args['opt_name'] ) . '[redux-scheme-select]" id="redux-scheme-select-' . esc_attr( self::$field_id ) . '" class="redux-scheme-select">';
			$html .= self::get_scheme_list_html( $selected );
			$html .= '</select>';

			return $html;
		}

		/**
		 * Set current scheme ID, if one isn't specified.
		 *
		 * @param       string $id Scheme name to set.
		 *
		 * @return      void
		 * @since       1.0.0
		 * @access      public static
		 */
		public static function set_current_scheme_id( string $id ) {

			// Get opt name, for database.
			$opt_name = self::$parent->args['opt_name'];

			// Get all options from database.
			$redux_options = get_option( $opt_name, array() );
			if ( ! is_array( $redux_options ) ) {
				$redux_options = array();
			}
			// Append ID to variable that holds the current scheme ID data.
			$redux_options['redux-scheme-select'] = $id;

			// Save the modified settings.
			update_option( $opt_name, $redux_options );
		}

		/**
		 * Get tooltip toggle state.
		 *
		 * @return bool
		 */
		public static function get_tooltip_toggle_state(): bool {

			// Retrieve the opt_name, needed for database.
			$opt_name = self::$parent->args['opt_name'];

			// Get the entire options array.
			$redux_options = get_option( $opt_name );

			return $redux_options['redux-color-scheme-tooltip-toggle'] ?? true;
		}

		/**
		 * Gets the current schem ID from the database.
		 *
		 * @since       1.0.0
		 * @access      public static
		 *
		 * @return      string Current scheme ID.
		 */
		public static function get_current_scheme_id(): string {

			// Retrieve the opt_name, needed for databasae.
			$opt_name = self::$parent->args['opt_name'];

			// Get the entire options array.
			$redux_options = get_option( $opt_name );

			// If the current scheme key exists...
			return $redux_options['redux-scheme-select'] ?? 'Default';
		}

		/**
		 * Get the list of schemes for the selector.
		 *
		 * @param       string $sel Scheme name to select.
		 *
		 * @return      string HTML option values.
		 * @since       1.0.0
		 * @access      static private
		 */
		private static function get_scheme_list_html( string $sel = '' ): string {
			// no errors, please.
			$html = '';

			// Retrieves the list of saved schemes into an array variable.
			$dropdown_values = self::get_scheme_names();

			// If the dropdown array has items...
			if ( ! empty( $dropdown_values ) ) {

				// Sort them alphbetically.
				asort( $dropdown_values );
			}

			// trim the selected item.
			$sel = trim( $sel );

			// If it's empty.
			if ( '' === $sel ) {

				// Make the current scheme id the selected value.
				$selected = self::get_current_scheme_id();
			} else {

				// Otherwise, set it to the value passed to this function.
				$selected = $sel;
			}

			// Enum through the dropdown array and append the necessary HTML for the selector.
			foreach ( $dropdown_values as $k ) {
				$html .= '<option value="' . $k . '" ' . selected( $k, $selected, false ) . '>' . $k . '</option>';
			}

			// Send it all packin'.
			return $html;
		}

		/**
		 * Returns select HTML.
		 *
		 * @param array $arr  Array of select fields to render.
		 * @param array $data Array of scheme data.
		 *
		 * @return      string HTML of select fields.
		 * @since       1.0.4
		 * @access      static private
		 */
		private static function render_selects( array $arr, array $data ): string {

			$html = '';
			foreach ( $arr as $v ) {
				$id = $v['id'];

				if ( isset( $v['width'] ) && ! empty( $v['width'] ) ) {
					$size = $v['width'];
				} else {
					$size = '40%';
				}

				$width = ' style="width: ' . $size . ';"';

				$html .= '<span class="redux-label redux-color-scheme-opt-select-title">' . $v['title'] . '</span>';

				$html .= '<select name="' . self::$parent->args['opt_name'] . '[' . self::$field_id . '][' . $id . ']" id="redux-color-scheme-opt-select-' . $id . '"' . $width . ' class="redux-color-scheme-opt-select">';

				foreach ( $v['options'] as $opt_id => $opt_val ) {
					$data[ $id ]['value'] = $data[ $id ]['value'] ?? '';
					$html                .= '<option value="' . $opt_id . '" ' . selected( $opt_id, $data[ $id ]['value'], false ) . '>' . $opt_val . '</option>';
				}

				$html .= '</select>';
				$html .= '<span class="redux-label redux-color-scheme-opt-select-desc">' . $v['desc'] . '</span>';
				$html .= '<hr class="redux-color-scheme-select-close-hr">';
				$html .= '<br/>';
			}

			return $html;
		}

		/**
		 * Do diff.
		 *
		 * @param array $first_array  Array one.
		 * @param array $second_array Array two.
		 *
		 * @return array
		 */
		private static function do_diff( array $first_array, array $second_array ): array {

			/**
			 * Serialize callback.
			 *
			 * @param array $arr Array.
			 */
			function my_serialize( array &$arr ) {
				$arr = maybe_serialize( $arr );
			}

			/**
			 * Unserialize callback.
			 *
			 * @param array $arr Array.
			 */
			function my_unserialize( &$arr ) {
				$arr = maybe_unserialize( $arr );
			}

			// make a copy.
			$first_array_s  = $first_array;
			$second_array_s = $second_array;

			// serialize all sub-arrays.
			array_walk( $first_array_s, 'my_serialize' );
			array_walk( $second_array_s, 'my_serialize' );

			// array_diff the serialized versions.
			$diff = array_diff( $first_array_s, $second_array_s );

			// unserialize the result.
			array_walk( $diff, 'my_unserialize' );

			// you've got it!
			return $diff;
		}

		/**
		 * Returns colour pickers HTML table.
		 *
		 * @since       1.0.0
		 * @access      public static
		 *
		 * @param       string $scheme_id Scheme name of HTML to return.
		 *
		 * @return      string HTML of colour picker table.
		 */
		public static function get_current_color_scheme_html( $scheme_id = false ): string {

			// If scheme_id is false.
			if ( ! $scheme_id ) {

				// Attempt to get the current scheme.
				$scheme_id = self::get_current_scheme_id();

				// dummy check, because this shit happens!
				$arr_schemes = self::get_scheme_names();

				if ( ! in_array( $scheme_id, $arr_schemes, true ) ) {
					$scheme_id = 'Default';
					self::set_current_scheme_id( 'Default' );
				}
			}

			// Set oft used variables.
			$opt_name    = esc_attr( self::$parent->args['opt_name'] );
			$field_id    = esc_attr( self::$field_id );
			$field_class = esc_attr( self::$field_class );

			// Get the default options.
			$field = self::get_field();

			$field['output_transparent'] = $field['output_transparent'] ?? '';
			$is_accordion                = $field['accordion'] ?? true;

			$def_opts = $field['default'];

			// Create array of element ids from default options.
			if ( ! empty( $def_opts ) ) {
				$id_arr = array();

				foreach ( $def_opts as $vv ) {
					$id_arr[] = $vv['id'];
				}
			}

			// Get last saved default.
			$saved_def = get_option( 'redux_' . $opt_name . '_' . $field_id . '_color_scheme' );

			// Compare key counts between saved and current defaults to check
			// for changes in color scheme.
			if ( false !== $saved_def && is_array( $saved_def ) ) {

				// Get the new color inputs.
				$arr_diff = self::do_diff( $def_opts, $saved_def );

				if ( ! empty( $arr_diff ) ) {
					update_option( 'redux_' . $opt_name . '_' . $field_id . '_color_scheme', $def_opts );
				}                //}
			} else {
				update_option( 'redux_' . $opt_name . '_' . $field_id . '_color_scheme', $def_opts );
			}

			// get current scheme data.
			$scheme = self::get_scheme_data( $scheme_id );

			if ( false === $scheme ) {
				return '';
			}

			// If new color inputs exist...
			if ( ! empty( $arr_diff ) ) {
				foreach ( $arr_diff as $val ) {
					if ( ! empty( $val ) && isset( $val['id'] ) ) {

						$val['title'] = $val['title'] ?? $val['id'];
						$val['color'] = $val['color'] ?? '';
						$val['alpha'] = $val['alpha'] ?? 1;

						$trans        = $field['output_transparent'];
						$res          = ( '' === $val['color'] || 'transparent' === $val['color'] ) ? $trans : Redux_Helpers::hex2rgba( $val['color'], $val['alpha'] );
						$val['rgba']  = $val['rgba'] ?? $res;
						$val['group'] = $val['group'] ?? '';

						$scheme[ $val['id'] ] = $val;
					}
				}

				// Get list of scheme names.
				$scheme_names = self::get_scheme_names();

				// Update is saved scheme with new picker data.
				foreach ( $scheme_names as $name ) {
					self::set_scheme_data( $name, $scheme );
				}

				// update the database.
				self::set_database_data( $scheme_id );
			}

			// If it's not empty then...
			if ( ! empty( $scheme ) ) {

				// init arrays.
				$groups     = array();
				$grp_desc   = array();
				$groups[''] = array();
				$sel_grps   = array();

				if ( ! isset( self::$select ) ) {
					self::$select = self::get_select_names();
				}

				// Enum select fields into groups array for later render.
				if ( isset( self::$select ) ) {
					foreach ( self::$select as $sel_arr ) {
						$sel_grp = $sel_arr['group'];
						if ( ! array_key_exists( $sel_grp, $sel_grps ) ) {
							$sel_grps[ $sel_grp ] = array();
						}
						$sel_grps[ $sel_grp ][] = $sel_arr;
					}
				}

				// Enum groups names.
				$group_arr = self::get_group_names();

				foreach ( $group_arr as $group_name => $description ) {
					$groups[ $group_name ] = array();

					if ( is_array( $description ) ) {
						$grp_desc[ $group_name ]           = $description['desc'] ?? '';
						$grp_grpdesc[ $group_name ]        = $description['group_desc'] ?? '';
						$grp_hidden[ $group_name ]         = $description['hidden'] ?? false;
						$grp_accordion_open[ $group_name ] = $description['accordion_open'] ?? false;

					} else {
						$grp_desc[ $group_name ]           = $description;
						$grp_hidden[ $group_name ]         = false;
						$grp_accordion_open[ $group_name ] = false;
						$grp_grpdesc[ $group_name ]        = false;
					}
				}

				// Assing color pickers to their specified group.
				foreach ( $scheme as $arr ) {
					if ( is_array( $arr ) ) {
						if ( ! empty( $arr['group'] ) ) {
							if ( array_key_exists( $arr['group'], $group_arr ) ) {
								$groups[ $arr['group'] ][] = $arr;
							} else {
								$groups[''][] = $arr;
							}
						} else {
							$groups[''][] = $arr;
						}
					}
				}

				$open_icon  = '';
				$close_icon = '';

				if ( $is_accordion ) {
					$open_icon  = apply_filters( 'redux/extension/color_scheme/' . self::$parent->args['opt_name'] . '/icon/open', 'dashicons dashicons-arrow-down' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
					$close_icon = apply_filters( 'redux/extension/color_scheme/' . self::$parent->args['opt_name'] . '/icon/close', 'dashicons dashicons-arrow-up' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
				}

				// open the list.
				$html = '<ul class="redux-scheme-layout" data-open-icon="' . $open_icon . '" data-close-icon="' . $close_icon . '">';

				// Enumerate groups.
				foreach ( $groups as $title => $scheme_arr ) {

					if ( '' === $title ) {
						if ( empty( $scheme_arr ) ) {
							continue;
						}

						$kill_me = false;
						foreach ( $scheme_arr as $data ) {
							if ( ! array_key_exists( 'color', $data ) ) {
								$kill_me = true;
								break;
							}
						}
						if ( $kill_me ) {
							continue;
						}
					}

					$add_hr     = false;
					$is_hidden  = false;
					$class_hide = '';
					$is_open    = '';

					if ( isset( $grp_hidden[ $title ] ) && '' !== $grp_hidden[ $title ] ) {
						$is_hidden  = $grp_hidden[ $title ];
						$class_hide = ( true === $is_hidden ) ? ' hidden ' : '';
						$is_open    = $grp_accordion_open[ $title ];
					}

					$add_class = '';
					if ( $is_accordion ) {
						$add_class = ' accordion ';
					}

					$html .= '<div class="redux-color-scheme-group' . $add_class . $class_hide . '">';

					if ( ! $is_hidden ) {

						if ( $is_accordion ) {
							$html .= '<div class="redux-color-scheme-accordion">';
						}
						$icon_class = '';

						// apply group title, if any.
						if ( '' !== $title ) {
							$html .= '<br><span class="redux-label redux-layout-group-label">' . esc_attr( $title ) . '</span>';

							if ( $is_accordion ) {
								$icon_class = ' titled';
							}
							$add_hr = true;
						} elseif ( $is_accordion ) {
							$icon_class = ' not-titled';
						}

						// apply group description, if any.
						if ( isset( $grp_desc[ $title ] ) && '' !== $grp_desc[ $title ] ) {
							$html  .= '<span class="redux-label redux-layout-group-desc-label' . $icon_class . '">' . esc_attr( $grp_desc[ $title ] ) . '</label>';
							$add_hr = true;

							if ( $is_accordion ) {
								$icon_class .= ' subtitled';
							}
						} else {
							$icon_class .= ' not-subtitled';
						}

						if ( $is_accordion ) {
							$html .= '<span class="' . esc_attr( $open_icon ) . $icon_class . '"></span>';
						}

						// Add HR, if needed.
						if ( true === $add_hr ) {
							if ( ! $is_accordion ) {
								$html .= '<hr>';
							}
						}

						if ( $is_accordion ) {
							$html .= '</div>';
							$html .= '<div class="redux-color-scheme-accordion-section" data-state="' . esc_attr( $is_open ) . '">';
							if ( false !== $grp_grpdesc ) {
								$html .= '<div class="redux-color-scheme-group-desc">';
								$html .= esc_attr( $grp_grpdesc[ $title ] );
								$html .= '</div>';
							}
						}

						// Select box render.
						if ( array_key_exists( $title, $sel_grps ) ) {
							$html .= self::render_selects( $sel_grps[ $title ], $scheme );
						}
					} elseif ( $is_accordion ) {
						$html .= '<div class="redux-color-scheme-accordion-section">';
					}

					$html .= "<ul class='redux-scheme-layout'>";

					// Enum through each element/id.
					foreach ( $scheme_arr as $v ) {
						if ( in_array( $v['id'], $id_arr, true ) ) {

							// If no title, use ID.
							$v['title'] = $v['title'] ?? $v['id'];

							// If no alpha, use 1 (solid).
							$v['alpha'] = $v['alpha'] ?? 1;

							// Fuck forbid no colour, set to white.
							$v['color'] = $v['color'] ?? '';

							// RGBA..
							$trans     = $field['output_transparent'];
							$res       = ( '' === $v['color'] || 'transparent' === $v['color'] ) ? $trans : Redux_Helpers::hex2rgba( $v['color'], $v['alpha'] );
							$v['rgba'] = $v['rgba'] ?? $res;

							// group name.
							$v['group'] = $v['group'] ?? '';

							$v['class'] = self::get_color_block_class( $field, $v['id'] );

							$block_hide = self::get_block_hidden( $field, $v['id'] ) ? 'hidden' : '';

							// tooltips.
							$tip_title = '';
							$tip_text  = '';

							$tooltip_data = self::get_tooltip_data( $field, $v['id'] );
							if ( false !== $tooltip_data ) {
								$tip_title = $tooltip_data['title'] ?? '';
								$tip_text  = $tooltip_data['text'] ?? '';
							}

							// Begin the layout.
							$html .= '<li class="redux-scheme-layout ' . $class_hide . ' redux-cs-qtip ' . $block_hide . '" qtip-title="' . esc_attr( $tip_title ) . '" qtip-content="' . esc_attr( $tip_text ) . '">';
							$html .= '<div class="redux-scheme-layout-container" data-id="' . $field_id . '-' . $v['id'] . '">';

							if ( '' === $v['color'] || 'transparent' === $v['color'] ) {
								$color = '';
							} else {
								$color = 'rgba(' . $v['rgba'] . ')';
							}

							// colour picker dropdown.
							$html .= '<input
                                        id="' . $field_id . '-' . esc_attr( $v['id'] ) . '-color"
                                        class="' . $field_class . ' ' . esc_attr( $v['class'] ) . '"
                                        type="text"
                                        data-color="' . esc_attr( $color ) . '"
                                        data-hex-color="' . esc_attr( $v['color'] ) . '"
                                        data-alpha="' . esc_attr( $v['alpha'] ) . '"
                                        data-rgba="' . esc_attr( $v['rgba'] ) . '"
                                        data-title="' . esc_attr( $v['title'] ) . '"
                                        data-id="' . esc_attr( $v['id'] ) . '"
                                        data-group="' . esc_attr( $v['group'] ) . '"
                                        data-current-color="' . esc_attr( $v['color'] ) . '"
                                        data-block-id="' . $field_id . '-' . esc_attr( $v['id'] ) . '"
                                        data-output-transparent="' . esc_attr( $field['output_transparent'] ) . '"
                                      />';

							$scheme_data = self::get_scheme_data( $scheme_id );
							if ( false === $scheme_data ) {
								return '';
							}

							$picker_data = $scheme_data[ $v['id'] ];

							// Hidden input for data string.
							$html .= '<input
                                        type="hidden"
                                        class="redux-hidden-data"
                                        name="' . esc_attr( $opt_name ) . '[' . esc_attr( $field_id ) . '][' . esc_attr( $v['id'] ) . '][data]"
                                        id="' . $field_id . '-' . esc_attr( $v['id'] ) . '-data"
                                        value="' . rawurlencode( wp_json_encode( $picker_data ) ) . '"
                                      />';

							// closing html tags.
							$html .= '</div>';
							$html .= '<span class="redux-label redux-layout-label">' . esc_attr( $v['title'] ) . '</span>';
							$html .= '</li>';
						}
					}
					$html .= '</ul>';

					$html .= '<hr class="redux-color-scheme-blank-hr">';

					if ( $is_accordion ) {
						$html .= '</div>';
					}

					$html .= '</div>';
				}

				// Close list.
				$html .= '</ul>';
			}

			// html var not empty, return it.
			if ( ! empty( $html ) ) {
				return $html;
			}

			return '';
		}

		/**
		 * Get color block class.
		 *
		 * @param array  $field Field array.
		 * @param string $id    Field ID.
		 *
		 * @return string
		 */
		private static function get_color_block_class( array $field, string $id ): string {
			$def = $field['default'];

			if ( ! empty( $def ) ) {
				foreach ( $def as $arr ) {
					if ( $arr['id'] === $id ) {
						if ( isset( $arr['class'] ) ) {
							return $arr['class'];
						}
					}
				}
			}

			return '';
		}

		/**
		 * Get tooltip data.
		 *
		 * @param array  $field Field array.
		 * @param string $id    Field ID.
		 *
		 * @return mixed
		 */
		private static function get_tooltip_data( array $field, string $id ) {
			$def = $field['default'];

			if ( ! empty( $def ) ) {
				foreach ( $def as $arr ) {
					if ( $arr['id'] === $id ) {
						if ( isset( $arr['tooltip'] ) ) {
							return $arr['tooltip'];
						}
					}
				}
			}

			return false;
		}

		/**
		 * Get hidden blocks.
		 *
		 * @param array  $field Field ID.
		 * @param string $id    Field ID.
		 *
		 * @return bool
		 */
		private static function get_block_hidden( array $field, string $id ): bool {
			$def = $field['default'];

			if ( ! empty( $def ) ) {
				foreach ( $def as $arr ) {
					if ( $arr['id'] === $id ) {
						if ( isset( $arr['hidden'] ) ) {
							return $arr['hidden'];
						}
					}
				}
			}

			return false;
		}

		/**
		 * Returns scheme file contents.
		 *
		 * @since       1.0.0
		 * @access      public static
		 *
		 * @return      array Array of scheme data.
		 */
		public static function read_scheme_file() {
			$key  = self::get_scheme_key();
			$data = get_option( $key );

			if ( empty( $data ) ) {
				$arr_data = false;
			} else {
				$arr_data = $data;
			}

			return $arr_data;
		}

		/**
		 * Sets scheme file contents.
		 *
		 * @param       array $arr_data PHP array of data to encode.
		 *
		 * @return      bool Result of write function.
		 * @since       1.0.0
		 * @access      public static
		 */
		public static function write_scheme_file( array $arr_data ): bool {
			$key = self::get_scheme_key();

			return update_option( $key, $arr_data );
		}

		/**
		 * Gets individual scheme data from scheme JSON file.
		 *
		 * @param       string $scheme_name Name of scheme.
		 *
		 * @return      mixed PHP array of scheme data.
		 * @since       1.0.0
		 * @access      public static
		 */
		public static function get_scheme_data( string $scheme_name ) {
			$data = self::read_scheme_file();

			if ( false === $data ) {
				return false;
			}

			return $data[ $scheme_name ];
		}

		/**
		 * Sets individual scheme data to scheme JSON file.
		 *
		 * @param string $name  Name of a scheme to save.
		 * @param array  $arr   Scheme data to encode.
		 *
		 * @return      bool Result of file written.
		 * @since       1.0.0
		 * @access      public static
		 */
		public static function set_scheme_data( string $name, array $arr ): bool {

			// Create blank array.
			$new_scheme = array();

			// If name is present.
			if ( $name ) {

				// then add the name at the new array's key.
				$new_scheme['color_scheme_name'] = $name;

				// Enum through values and assign them to new array.
				foreach ( $arr as $val ) {
					if ( isset( $val['id'] ) ) {
						$new_scheme[ $val['id'] ] = $val;
					}
				}

				// read the contents of the current scheme file.
				$schemes = self::read_scheme_file();

				// If returned false (not there) then create a new array.
				if ( false === $schemes ) {
					$schemes = array();
				}

				$scheme_data = $schemes[ $name ] ?? '';

				if ( $scheme_data !== $new_scheme ) {

					// Add new scheme to array that will be saved.
					$schemes[ $name ] = $new_scheme;

					// Write the data to the JSON file.
					return self::write_scheme_file( $schemes );
				}
			}

			// !success
			return false;
		}

		/**
		 * Enumerate the scheme names from the JSON store file.
		 *
		 * @since       1.0.0
		 * @access      public static
		 * @return      array Array of stored scheme names.
		 */
		public static function get_scheme_names(): array {

			// Read the JSON file, which returns a PHP array.
			$schemes = self::read_scheme_file();

			// Create a new array.
			$output = array();

			if ( false !== $schemes ) {

				// If the schemes array IS an array (versus false), then...
				if ( is_array( $schemes ) ) {

					// Enum them.
					foreach ( $schemes as $scheme ) {

						// If the color_scheme_name key is set...
						if ( isset( $scheme['color_scheme_name'] ) ) {

							// Push it onto the array stack.
							$output[] = $scheme['color_scheme_name'];
						}
					}
				}
			}

			// Kick the full array out the door.
			return $output;
		}

		/**
		 * Get data array from scheme.
		 *
		 * @param string $scheme Scheme name.
		 *
		 * @return array
		 */
		public static function data_array_from_scheme( string $scheme ): array {

			// Get scheme data from JSON file.
			$data = self::get_scheme_data( $scheme );
			if ( false === $data ) {
				return array();
			}

			// Don't need to save select arrays to database,
			// just the id => value.
			if ( ! empty( $data ) ) {
				foreach ( $data as $k => $v ) {
					if ( isset( $v['type'] ) ) {
						$val = $v['value'];

						unset( $data[ $k ] );

						$data[ $k ] = $val;
					}
				}
			}

			return $data;
		}

		/**
		 * Sets current scheme to database.
		 *
		 * @param       string $scheme Current scheme name.
		 *
		 * @return      void
		 * @since       1.0.0
		 * @access      private
		 */
		public static function set_database_data( string $scheme = 'Default' ) {

			$data = self::data_array_from_scheme( $scheme );

			// Get opt name, for database.
			$opt_name = self::$parent->args['opt_name'];

			// Get all options from database.
			$redux_options = get_option( $opt_name );

			if ( empty( self::$field_id ) ) {
				self::$field    = self::get_field();
				self::$field_id = self::$field['id'];
			}

			// Append ID to variable that holds the current scheme ID data.
			$redux_options[ self::$field_id ] = $data;

			// Save the modified settings.
			update_option( $opt_name, $redux_options );
		}
	}
}
