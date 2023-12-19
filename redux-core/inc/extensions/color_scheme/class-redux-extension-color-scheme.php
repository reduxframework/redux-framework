<?php
/**
 * Redux Color Scheme Extension Class
 *
 * @package Redux
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Color_Scheme
 *
 * @version 4.4.10
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Color_Scheme' ) ) {

	/**
	 * Class Redux_Extension_Color_Scheme
	 */
	class Redux_Extension_Color_Scheme extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.4.10';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extension_name = 'Color Schemes';

		/**
		 * Field ID.
		 *
		 * @var string
		 */
		public $field_id = '';

		/**
		 * Transparent output bit.
		 *
		 * @var bool
		 */
		public $output_transparent = false;

		/**
		 * Extension field name.
		 *
		 * @var string
		 */
		public $field_name = '';

		/**
		 * Class Constructor. Defines the args for the extensions class
		 *
		 * @since       1.0.0
		 * @access      public
		 *
		 * @param       object $redux Parent settings.
		 *
		 * @return      void
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			$this->add_field( 'color_scheme' );
			$this->field_name = 'color_scheme';

			add_filter( "redux/options/{$this->parent->args['opt_name']}/defaults", array( $this, 'set_defaults' ) );

			// Ajax hooks.
			add_action( 'wp_ajax_redux_color_schemes', array( $this, 'parse_ajax' ) );
			add_action( 'wp_ajax_nopriv_redux_color_schemes', array( $this, 'parse_ajax' ) );

			// Reset hooks.
			add_action( 'redux/validate/' . $this->parent->args['opt_name'] . '/defaults', array( $this, 'reset_defaults' ), 0, 3 );
			add_action( 'redux/validate/' . $this->parent->args['opt_name'] . '/defaults_section', array( $this, 'reset_defaults_section' ), 0, 3 );

			// Save filter.
			add_action( 'redux/validate/' . $this->parent->args['opt_name'] . '/before_validation', array( $this, 'save_hook' ), 0, 3 );

			// Register hook - to get field id and prep helper.
			add_action( 'redux/options/' . $this->parent->args['opt_name'] . '/field/' . $this->field_name . '/register', array( $this, 'register_field' ) );

			include_once $this->extension_dir . 'color_scheme/inc/class-redux-color-scheme-functions.php';
			Redux_Color_Scheme_Functions::init( $redux );

			$field = Redux_Color_Scheme_Functions::get_field( $redux );

			if ( ! is_array( $field ) ) {
				return;
			}

			$this->field_id = $field['id'];

			// Prep storage.
			$upload_dir = Redux_Color_Scheme_Functions::$upload_dir;

			// Create uploads/redux_scheme_colors/ folder.
			if ( ! is_dir( $upload_dir ) ) {
				$redux->filesystem->execute( 'mkdir', $upload_dir );
			}
		}

		/**
		 * Set default values after reset.
		 *
		 * @param array $defaults Default values.
		 *
		 * @return array
		 */
		public function set_defaults( array $defaults = array() ): array {
			if ( empty( $this->field_id ) ) {
				return $defaults;
			}

			$x            = get_option( $this->parent->args['opt_name'] );
			$color_opts   = $x[ $this->field_id ] ?? array();
			$wrong_format = false;

			if ( ! isset( $color_opts['color_scheme_name'] ) ) {
				$wrong_format = true;

				$data = Redux_Color_Scheme_Functions::data_array_from_scheme( 'Default' );

				if ( ! empty( $data ) && isset( $x[ $this->field_id ] ) ) {
					$x[ $this->field_id ] = $data;

					update_option( $this->parent->args['opt_name'], $x );
				}
			}

			Redux_Color_Scheme_Functions::$parent = $this->parent;

			$ot_val                   = Redux_Color_Scheme_Functions::get_output_transparent_val();
			$this->output_transparent = $ot_val;

			Redux_Color_Scheme_Functions::convert_to_db();

			$scheme_key  = Redux_Color_Scheme_Functions::get_scheme_key();
			$scheme_data = get_option( $scheme_key );

			$scheme_data_exists = ! empty( $scheme_data );

			$default_exists = in_array( 'default', array_map( 'strtolower', Redux_Color_Scheme_Functions::get_scheme_names() ), true );

			if ( ! $scheme_data_exists || ! $default_exists || $wrong_format ) {
				$data = $this->get_default_data();

				// Add to (and/or create) JSON scheme file.
				Redux_Color_Scheme_Functions::set_scheme_data( 'Default', $data );

				// Set default scheme.
				Redux_Color_Scheme_Functions::set_current_scheme_id( 'Default' );

				$data = Redux_Color_Scheme_Functions::data_array_from_scheme( 'Default' );

				$this->parent->options[ $this->field_id ] = $data;

				$defaults[ $this->field_id ] = $data;
			}

			return $defaults;
		}

		/**
		 * Field Register. Sets the whole smash up.
		 *
		 * @param       array $data Field data.
		 *
		 * @return      void
		 * @since       1.0.0
		 * @access      public
		 */
		public function register_field( array $data ) {

			// Include color_scheme helper.
			include_once $this->extension_dir . 'color_scheme/inc/class-redux-color-scheme-functions.php';

			if ( isset( $data['output_transparent'] ) ) {
				$this->output_transparent = $data['output_transparent'];
			}

			$this->field_id                         = $data['id'];
			Redux_Color_Scheme_Functions::$field_id = $data['id'];

			// Set helper parent object.
			Redux_Color_Scheme_Functions::$parent = $this->parent;

			// Prep storage.
			$upload_dir = Redux_Color_Scheme_Functions::$upload_dir;

			// Set upload_dir cookie.
			setcookie( 'redux_color_scheme_upload_dir', $upload_dir, 0, '/' );
		}

		/**
		 * Reset defaults.
		 *
		 * @param array $defaults Default values.
		 *
		 * @return array
		 */
		public function reset_defaults( array $defaults = array() ): array {
			if ( Redux_Helpers::is_field_in_use( $this->parent, 'color_scheme' ) ) {
				// Check if reset_all was fired.
				$this->reset_all();
				$defaults[ $this->field_id ] = Redux_Color_Scheme_Functions::data_array_from_scheme( 'Default' );
			}

			return $defaults;
		}

		/**
		 * Reset section defaults.
		 *
		 * @param array $defaults Default values.
		 *
		 * @return array
		 */
		public function reset_defaults_section( array $defaults = array() ): array {
			if ( Redux_Helpers::is_field_in_use( $this->parent, 'color_scheme' ) ) {
				// Get the current tab/section number.
				if ( isset( $_COOKIE['redux_current_tab'] ) ) {
					$cur_tab = sanitize_text_field( wp_unslash( $_COOKIE['redux_current_tab'] ) );

					// Get the tab/section number field is used on.
					$tab_num = $this->parent->field_sections['color_scheme'][ $this->field_id ];

					// Match...
					if ( $cur_tab === $tab_num ) {

						// Reset data.
						$this->reset_all();
					}
					$defaults[ $this->field_id ] = Redux_Color_Scheme_Functions::data_array_from_scheme( 'Default' );
				}
			}

			return $defaults;
		}

		/**
		 * Save Changes Hook. What to do when changes are saved
		 *
		 * @param array $saved_options Saved data.
		 * @param array $old_options   Previous data.
		 *
		 * @return      array
		 * @since       1.0.0
		 * @access      public
		 */
		public function save_hook( array $saved_options = array(), array $old_options = array() ): array {
			if ( ! isset( $saved_options[ $this->field_id ] ) || empty( $saved_options[ $this->field_id ] ) || ( is_array( $saved_options[ $this->field_id ] ) && $old_options === $saved_options ) || ! array_key_exists( $this->field_id, $saved_options ) ) {
				return $saved_options;
			}

			// We'll use the reset hook instead.
			if ( ! empty( $saved_options['defaults'] ) || ! empty( $saved_options['defaults-section'] ) ) {
				return $saved_options;
			}

			$first_value = reset( $saved_options[ $this->field_id ] ); // First Element's Value.

			// Parse the JSON to an array.
			if ( isset( $first_value['data'] ) ) {

				Redux_Color_Scheme_Functions::$parent   = $this->parent;
				Redux_Color_Scheme_Functions::$field_id = $this->field_id;

				Redux_Color_Scheme_Functions::set_current_scheme_id( $saved_options['redux-scheme-select'] );

				// Get the current field ID.
				$raw_data = $saved_options[ $this->field_id ];

				// Create a new array.
				$save_data = array();

				// Enum through saved data.
				foreach ( $raw_data as $id => $val ) {

					if ( 'color_scheme_name' !== $id ) {
						if ( is_array( $val ) ) {

							if ( ! isset( $val['data'] ) ) {
								continue;
							}

							$data = json_decode( rawurldecode( $val['data'] ), true );

							// Sanitize everything.
							$color = $data['color'] ?? '';
							$alpha = $data['alpha'] ?? 1;

							$id    = $data['id'] ?? $id;
							$title = $data['title'] ?? $id;

							$grp = $data['group'] ?? '';

							if ( '' === $color || 'transparent' === $color ) {
								$rgba = $this->output_transparent ? 'transparent' : '';
							} else {
								$rgba = Redux_Helpers::hex2rgba( $color, $alpha );
							}

							// Create an array of saved data.
							$save_data[] = array(
								'id'    => $id,
								'title' => $title,
								'color' => $color,
								'alpha' => $alpha,
								'group' => $grp,
								'rgba'  => $rgba,
							);
						} else {
							$save_data[] = array(
								'id'    => $id,
								'value' => $val,
								'type'  => 'select',
							);
						}
					}
				}

				$new_scheme = array();

				$new_scheme['color_scheme_name'] = Redux_Color_Scheme_Functions::get_current_scheme_id();

				// Enum through values and assign them to a new array.
				foreach ( $save_data as $val ) {
					if ( isset( $val['id'] ) ) {
						$new_scheme[ $val['id'] ] = $val;
					}
				}

				// Filter for DB save
				// Doesn't need to save select arrays to a database,
				// just the id => value.
				$database_data = $new_scheme;

				foreach ( $database_data as $k => $v ) {
					if ( isset( $v['type'] ) ) {
						$val = $v['value'];

						unset( $database_data[ $k ] );

						$database_data[ $k ] = $val;
					}
				}

				$saved_options[ $this->field_id ] = $database_data;

				// Check if we should save this compared to the old data.
				$save_scheme = false;

				// Doesn't exist or is empty.
				if ( ! isset( $old_options[ $this->field_id ] ) || ( isset( $old_options[ $this->field_id ] ) && ! empty( $old_options[ $this->field_id ] ) ) ) {
					$save_scheme = true;
				}

				// Isn't empty and isn't the same as the new array.
				if ( ! empty( $old_options[ $this->field_id ] ) && $saved_options[ $this->field_id ] !== $old_options[ $this->field_id ] ) {
					$save_scheme = true;
				}

				if ( $save_scheme ) {
					$scheme = Redux_Color_Scheme_Functions::get_current_scheme_id();
					Redux_Color_Scheme_Functions::set_scheme_data( $scheme, $save_data );
				}
			}

			return $saved_options;
		}

		/**
		 * Reset data. Restores colour picker to default values
		 *
		 * @since       1.0.0
		 * @access      private
		 * @return      void
		 */
		private function reset_data() {
			Redux_Color_Scheme_Functions::$parent   = $this->parent;
			Redux_Color_Scheme_Functions::$field_id = $this->field_id;

			// Get default data.
			$data = $this->get_default_data();

			// Add to (and/or create) JSON scheme file.
			Redux_Color_Scheme_Functions::set_scheme_data( 'Default', $data );

			// Set default scheme.
			Redux_Color_Scheme_Functions::set_current_scheme_id( 'Default' );
		}

		/**
		 * Reset All Hook. Todo list when all data is reset
		 *
		 * @return      void
		 * @since       1.0.0
		 * @access      public
		 */
		public function reset_all() {
			if ( ! empty( $this->field_id ) && isset( $this->parent->options_defaults[ $this->field_id ] ) && ! empty( $this->parent->options_defaults[ $this->field_id ] ) ) {
				Redux_Color_Scheme_Functions::$parent   = $this->parent;
				Redux_Color_Scheme_Functions::$field_id = $this->field_id;

				$this->reset_data();
			}
		}

		/**
		 * AJAX evaluator. Determine course of action based on AJAX callback
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function parse_ajax() {
			if ( isset( $_REQUEST['nonce'] ) && isset( $_REQUEST['opt_name'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['nonce'] ) ), 'redux_' . sanitize_text_field( wp_unslash( $_REQUEST['opt_name'] ) ) . '_color_schemes' ) ) {
				$parent = $this->parent;

				// Do action.
				if ( isset( $_REQUEST['type'] ) ) {

					// Save scheme.
					if ( 'save' === $_REQUEST['type'] ) {
						$this->save_scheme( $parent );

						// Delete scheme.
					} elseif ( 'delete' === $_REQUEST['type'] ) {
						$this->delete_scheme( $parent );

						// Scheme change.
					} elseif ( 'update' === $_REQUEST['type'] ) {
						$this->get_scheme_html( $parent );

						// Export scheme file.
					} elseif ( 'export' === $_REQUEST['type'] ) {
						$this->download_schemes();
					}
				}
			} else {
				wp_die( esc_html__( 'Invalid Security Credentials.  Please reload the page and try again.', 'redux-framework' ) );
			}
		}

		/**
		 * Download Scheme File.
		 *
		 * @since       1.0.0
		 * @access      private
		 * @return      void
		 */
		private function download_schemes() {
			Redux_Color_Scheme_Functions::$parent   = $this->parent;
			Redux_Color_Scheme_Functions::$field_id = $this->field_id;

			// Read contents of scheme file.
			$content = Redux_Color_Scheme_Functions::read_scheme_file();
			$content = wp_json_encode( $content );

			// Set header info.
			header( 'Content-Description: File Transfer' );
			header( 'Content-type: application/txt' );
			header( 'Content-Disposition: attachment; filename="redux_schemes_' . $this->parent->args['opt_name'] . '_' . $this->field_id . '_' . gmdate( 'm-d-Y' ) . '.json"' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate' );
			header( 'Pragma: public' );

			// File download.
			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput

			// 2B ~! 2B
			die;
		}

		/**
		 * Save Scheme. Saved an individual scheme to JSON scheme file.
		 *
		 * @param       object $redux ReduxFramework object.
		 *
		 * @since       1.0.0
		 * @access      private
		 * @return      void
		 */
		private function save_scheme( $redux ) {
			Redux_Color_Scheme_Functions::$parent   = $redux;
			Redux_Color_Scheme_Functions::$field_id = $this->field_id;

			// Get the scheme name.
			if ( isset( $_REQUEST['scheme_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$scheme_name = sanitize_text_field( wp_unslash( $_REQUEST['scheme_name'] ) ); // phpcs:ignore WordPress.Security.NonceVerification

				// Check for duplicates.
				$names = Redux_Color_Scheme_Functions::get_scheme_names();
				foreach ( $names as $name ) {
					$name     = strtolower( $name );
					$tmp_name = strtolower( $scheme_name );

					if ( $name === $tmp_name ) {
						echo 'fail';
						die();
					}
				}

				// Get scheme data.
				if ( isset( $_REQUEST['scheme_data'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$scheme_data = wp_unslash( $_REQUEST['scheme_data'] ); // phpcs:ignore WordPress.Security

					// Get field ID.
					if ( isset( $_REQUEST['field_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
						$scheme_data = rawurldecode( $scheme_data );
						$scheme_data = json_decode( $scheme_data, true );

						// Save scheme to file.  If successful...
						if ( true === Redux_Color_Scheme_Functions::set_scheme_data( $scheme_name, $scheme_data ) ) {

							// Update scheme selector.
							echo Redux_Color_Scheme_Functions::get_scheme_select_html( $scheme_name ); // phpcs:ignore WordPress.Security.EscapeOutput
						}
					}
				}
			}

			die(); // a horrible death!
		}

		/**
		 * Delete Scheme. Delete individual scheme from JSON scheme file.
		 *
		 * @param       object $redux ReduxFramework object.
		 *
		 * @since       1.0.0
		 * @access      private
		 * @return      void
		 */
		private function delete_scheme( $redux ) {

			// Get deleted scheme ID.
			if ( isset( $_REQUEST['scheme_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$scheme_id = sanitize_text_field( wp_unslash( $_REQUEST['scheme_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification

				// Get field ID.
				if ( isset( $_REQUEST['field_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$field_id = sanitize_text_field( wp_unslash( $_REQUEST['field_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification

					// If scheme ID was passed (and why wouldn't it be?? Hmm??).
					if ( $scheme_id ) {
						Redux_Color_Scheme_Functions::$field_id = $field_id;
						Redux_Color_Scheme_Functions::$parent   = $redux;

						// Get the entire scheme file.
						$schemes = Redux_Color_Scheme_Functions::read_scheme_file();

						// If we got a good read...
						if ( false !== $schemes ) {

							// If scheme name exists...
							if ( isset( $schemes[ $scheme_id ] ) ) {

								// Unset it.
								unset( $schemes[ $scheme_id ] );

								// Save the scheme data, minus the deleted scheme.  Upon success...
								if ( true === Redux_Color_Scheme_Functions::write_scheme_file( $schemes ) ) {

									// Set default scheme.
									Redux_Color_Scheme_Functions::set_current_scheme_id( 'Default' );

									// Update field ID.
									Redux_Color_Scheme_Functions::$field_id = $field_id;

									// Meh TODO.
									Redux_Color_Scheme_Functions::set_database_data();

									echo 'success';
								} else {
									echo 'Failed to write JSON file to server.';
								}
							} else {
								echo 'Scheme name does not exist in JSON string.  Aborting.';
							}
						} else {
							echo 'Failed to read JSON scheme file, or file is empty.';
						}
					} else {
						echo 'No scheme ID passed.  Aborting.';
					}
				}
			}

			die(); // rolled a two.
		}

		/**
		 * Gets the new scheme based on selection.
		 *
		 * @param       object $redux ReduxFramework object.
		 *
		 * @since       1.0.0
		 * @access      private
		 * @return      void
		 */
		private function get_scheme_html( $redux ) {
			if ( isset( $_POST['scheme_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification

				// Get the selected scheme name.
				$scheme_id = sanitize_text_field( wp_unslash( $_POST['scheme_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification

				if ( isset( $_POST['field_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification

					// Get the field ID.
					$field_id = sanitize_text_field( wp_unslash( $_POST['field_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification

					// Get the field class.
					$field_class = isset( $_POST['field_class'] ) ? sanitize_text_field( wp_unslash( $_POST['field_class'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

					Redux_Color_Scheme_Functions::$parent = $redux;

					// Set the updated field ID.
					Redux_Color_Scheme_Functions::$field_id = $field_id;

					// Set the updated field class.
					Redux_Color_Scheme_Functions::$field_class = $field_class;

					// Get the color picket layout HTML.
					$html = Redux_Color_Scheme_Functions::get_current_color_scheme_html( $scheme_id );

					// Print!
					echo $html; // phpcs:ignore WordPress.Security.EscapeOutput
				}
			}

			die(); // another day.
		}


		/**
		 * Retrieves an array of default data for color picker.
		 *
		 * @since       1.0.0
		 * @access      private
		 * @return      array Default values from config.
		 */
		private function get_default_data(): array {
			$def_opts = $this->parent->options_defaults[ $this->field_id ];
			$sections = $this->parent->sections;
			$data     = array();

			foreach ( $sections as $arr ) {
				if ( isset( $arr['fields'] ) ) {
					foreach ( $arr['fields'] as $arr2 ) {
						if ( $arr2['id'] === $this->field_id ) {

							// Select fields.
							if ( isset( $arr2['select'] ) ) {
								foreach ( $arr2['select'] as $v ) {
									$data[] = array(
										'id'    => $v['id'],
										'value' => $v['default'],
										'type'  => 'select',
									);
								}
							}
						}
					}
				}
			}

			foreach ( $def_opts as $v ) {
				$title = $v['title'] ?? $v['id'];
				$color = $v['color'] ?? '';
				$alpha = $v['alpha'] ?? 1;
				$grp   = $v['group'] ?? '';

				if ( '' === $color || 'transparent' === $color ) {
					$rgba = $this->output_transparent ? 'transparent' : '';
				} else {
					$rgba = Redux_Helpers::hex2rgba( $color, $alpha );
				}

				$data[] = array(
					'id'    => $v['id'],
					'title' => $title,
					'color' => $color,
					'alpha' => $alpha,
					'group' => $grp,
					'rgba'  => $rgba,
				);
			}

			return $data;
		}
	}
}

class_alias( 'Redux_Extension_Color_Scheme', 'ReduxFramework_Extension_Color_Scheme' );
