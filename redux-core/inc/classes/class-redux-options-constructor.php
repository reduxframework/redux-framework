<?php
/**
 * Redux Options Class
 *
 * @class Redux_Options
 * @version 3.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Options_Object', false ) ) {

	/**
	 * Class Redux_Options
	 */
	class Redux_Options_Constructor extends Redux_Class {

		/**
		 * Array to hold single panel data.
		 *
		 * @var array
		 */
		public $no_panel = array();

		/**
		 * Array to hold single panel sections.
		 *
		 * @var array
		 */
		private $no_panel_section = array();

		/**
		 * Array to hold hidden fields.
		 *
		 * @var array
		 */
		private $hidden_perm_fields = array();

		/**
		 * Array to hold hidden sections.
		 *
		 * @var array
		 */
		public $hidden_perm_sections = array();

		/**
		 * Redux_Options constructor.
		 *
		 * @param object $parent ReduxFramework pointer.
		 */
		public function __construct( $parent ) {

			parent::__construct( $parent );
			add_action( 'admin_init', array( $this, 'register' ) );

		}

		/**
		 * If we switch language in wpml the id of the post/page selected will be in the wrong language
		 * So it won't appear as selected in the list of options and will be lost on next save, this fixes this by translating this id
		 * Bonus it also gives the user the id of the post in the right language when they retrieve it
		 * The recursion allows for it to work in a repeatable field.
		 *
		 * @param array $sections Sections array.
		 * @param array $options_values Values array.
		 */
		private function translate_data_field_recursive( &$sections, &$options_values ) {
			foreach ( $sections as $key => $section ) {
				if ( 'fields' === $key ) {
					foreach ( $section as $field ) {
						if ( ! empty( $field['id'] ) && ! empty( $field['data'] ) && ! empty( $options_values[ $field['id'] ] ) && \Redux_Helpers::is_integer( $options_values[ $field['id'] ] ) ) {
							$options_values[ $field['id'] ] = apply_filters( 'wpml_object_id', $options_values[ $field['id'] ], $field['data'], true );
						}
					}
				} elseif ( is_array( $section ) && ! empty( $section ) ) {
					$this->translate_data_field_recursive( $section, $options_values );
				}
			}
		}

		/**
		 * Retrieves the options.
		 */
		public function get() {
			$core = $this->core();

			$defaults = false;

			if ( ! empty( $core->defaults ) ) {
				$defaults = $core->defaults;
			}

			if ( empty( $core->args ) ) {
				return;
			}

			switch ( $core->args['database'] ) {
				case 'transient':
					$result = get_transient( $core->args['opt_name'] . '-transient' );
					break;
				case 'theme_mods':
					$result = get_theme_mod( $core->args['opt_name'] . '-mods' );
					break;
				case 'theme_mods_expanded':
					$result = get_theme_mods();
					break;
				case 'network':
					$result = get_site_option( $core->args['opt_name'], array() );
					break;
				default:
					$result = get_option( $core->args['opt_name'], array() );

			}

			if ( empty( $result ) && empty( $defaults ) ) {
				return;
			}

			if ( empty( $result ) && ! empty( $defaults ) ) {
				$results = $defaults;
				$this->set( $results );
			} else {
				$core->options = $result;
			}

			// Don't iterate unnecessarily.
			if ( has_filter( 'wpml_object_id' ) ) {
				$this->translate_data_field_recursive( $core->sections, $core->options );
			}

			/**
			 * Action 'redux/options/{opt_name}/options'
			 *
			 * @param mixed $value option values
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$core->options = apply_filters( "redux/options/{$core->args['opt_name']}/options", $core->options, $core->sections );

			// Get transient values.
			$core->transient_class->get();

			// Set a global variable by the global_variable argument.
			$this->set_global_variable( $core );
		}

		/**
		 * ->set_options(); This is used to set an arbitrary option in the options array
		 *
		 * @since ReduxFramework 3.0.0
		 *
		 * @param mixed $value the value of the option being added.
		 */
		public function set( $value = '' ) {
			$core = $this->core();

			$core->transients['last_save'] = time();

			if ( ! empty( $value ) ) {
				$core->options = $value;

				switch ( $core->args['database'] ) {
					case 'transient':
						set_transient( $core->args['opt_name'] . '-transient', $value, $core->args['transient_time'] );
						break;
					case 'theme_mods':
						set_theme_mod( $core->args['opt_name'] . '-mods', $value );
						break;
					case 'theme_mods_expanded':
						foreach ( $value as $k => $v ) {
							set_theme_mod( $k, $v );
						}
						break;
					case 'network':
						update_site_option( $core->args['opt_name'], $value );
						break;
					default:
						update_option( $core->args['opt_name'], $value );

				}

				// Store the changed values in the transient.
				if ( $value !== $core->options ) {
					foreach ( $value as $k => $v ) {
						if ( ! isset( $core->options[ $k ] ) ) {
							$core->options[ $k ] = '';
						} elseif ( $v === $core->options[ $k ] ) {
							unset( $core->options[ $k ] );
						}
					}

					$core->transients['changed_values'] = $core->options;
				}

				$core->options = $value;

				// Set a global variable by the global_variable argument.
				$this->set_global_variable( $core );

				// Saving the transient values.
				$core->transient_class->set();
			}
		}

		/**
		 * Set a global variable by the global_variable argument
		 *
		 * @param object $core ReduxFramework core object.
		 *
		 * @since   3.1.5
		 *
		 * @return  bool          (global was set)
		 */
		private function set_global_variable( $core ) {
			if ( ! empty( $core->args['global_variable'] ) ) {
				$options_global = $core->args['global_variable'];

				/**
				 * Filter 'redux/options/{opt_name}/global_variable'
				 *
				 * @param array $value option value to set global_variable with
				 */

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$GLOBALS[ $options_global ] = apply_filters( "redux/options/{$core->args['opt_name']}/global_variable", $core->options );

				// Last save key.
				if ( isset( $core->transients['last_save'] ) ) {
					$GLOBALS[ $options_global ]['REDUX_LAST_SAVE'] = $core->transients['last_save'];
				}

				// Last compiler hook key.
				if ( isset( $core->transients['last_compiler'] ) ) {
					$GLOBALS[ $options_global ]['REDUX_LAST_COMPILER'] = $core->transients['last_compiler'];
				}

				return true;
			}

			return false;
		}

		/**
		 * Register Option for use
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function register() {
			$core = $this->core();

			if ( ! is_object( $core ) ) {
				return;
			}

			if ( true === $core->args['options_api'] ) {
				register_setting(
					$core->args['opt_name'] . '_group',
					$core->args['opt_name'],
					array(
						$this,
						'validate_options',
					)
				);
			}

			if ( is_null( $core->sections ) ) {
				return;
			}

			if ( empty( $core->options_defaults ) ) {
				$core->options_defaults = $core->_default_values();
			}

			$run_update = false;

			foreach ( $core->sections as $k => $section ) {
				if ( isset( $section['type'] ) && 'divide' === $section['type'] ) {
					continue;
				}

				$display = true;

				if ( isset( $_GET['page'] ) && $_GET['page'] === $core->args['page_slug'] ) { // phpcs:ignore WordPress.Security.NonceVerification
					if ( isset( $section['panel'] ) && false === $section['panel'] ) {
						$display = false;
					}
				}

				/**
				 * Filter 'redux/options/{opt_name}/section/{section.id}'
				 *
				 * @param array $section section configuration
				 */
				if ( isset( $section['id'] ) ) {
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$section = apply_filters( "redux/options/{$core->args['opt_name']}/section/{$section['id']}", $section );
				}

				if ( empty( $section ) ) {
					unset( $core->sections[ $k ] );
					continue;
				}

				if ( ! isset( $section['title'] ) ) {
					$section['title'] = '';
				}

				if ( isset( $section['customizer_only'] ) && true === $section['customizer_only'] ) {
					$section['panel']     = false;
					$core->sections[ $k ] = $section;
				}

				$heading = isset( $section['heading'] ) ? $section['heading'] : $section['title'];

				if ( isset( $section['permissions'] ) && false !== $section['permissions'] ) {
					if ( ! Redux_Helpers::current_user_can( $section['permissions'] ) ) {
						$core->hidden_perm_sections[] = $section['title'];

						foreach ( $section['fields'] as $num => $field_data ) {
							$field_type = $field_data['type'];

							if ( 'section' !== $field_type || 'divide' !== $field_type || 'info' !== $field_type || 'raw' !== $field_type ) {
								$field_id = $field_data['id'];
								$default  = isset( $core->options_defaults[ $field_id ] ) ? $core->options_defaults[ $field_id ] : '';
								$data     = isset( $core->options[ $field_id ] ) ? $core->options[ $field_id ] : $default;

								$this->hidden_perm_fields[ $field_id ] = $data;
							}
						}

						continue;
					}
				}

				if ( ! $display || ! function_exists( 'add_settings_section' ) ) {
					$this->no_panel_section[ $k ] = $section;
				} else {
					add_settings_section(
						$core->args['opt_name'] . $k . '_section',
						$heading,
						array(
							$core->render_class,
							'section_desc',
						),
						$core->args['opt_name'] . $k . '_section_group'
					);
				}

				$section_ident = false;
				if ( isset( $section['fields'] ) ) {
					foreach ( $section['fields'] as $fieldk => $field ) {
						if ( ! isset( $field['type'] ) ) {
							continue; // You need a type!
						}

						if ( 'info' === $field['type'] && isset( $field['raw_html'] ) && true === $field['raw_html'] ) {
							$field['type']                             = 'raw';
							$field['content']                          = $field['desc'];
							$field['desc']                             = '';
							$core->sections[ $k ]['fields'][ $fieldk ] = $field;
						} elseif ( 'info' === $field['type'] ) {
							if ( ! isset( $field['full_width'] ) ) {
								$field['full_width']                       = true;
								$core->sections[ $k ]['fields'][ $fieldk ] = $field;
							}
						}

						if ( 'raw' === $field['type'] ) {
							if ( isset( $field['align'] ) ) {
								$field['full_width'] = $field['align'] ? false : true;
								unset( $field['align'] );
							} elseif ( ! isset( $field['full_width'] ) ) {
								$field['full_width'] = true;
							}
							$core->sections[ $k ]['fields'][ $fieldk ] = $field;
						}

						/**
						 * Filter 'redux/options/{opt_name}/field/{field.id}'
						 *
						 * @param array $field field config
						 */

						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						$field = apply_filters( "redux/options/{$core->args['opt_name']}/field/{$field['id']}/register", $field );

						$core->field_types[ $field['type'] ] = isset( $core->field_types[ $field['type'] ] ) ? $core->field_types[ $field['type'] ] : array();

						$core->field_sections[ $field['type'] ][ $field['id'] ] = $k;

						$display = true;

						if ( isset( $_GET['page'] ) && $core->args['page_slug'] === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification
							if ( isset( $field['panel'] ) && false === $field['panel'] ) {
								$display = false;
							}
						}
						if ( isset( $field['customizer_only'] ) && true === $field['customizer_only'] ) {
							$display = false;
						}

						if ( isset( $section['customizer'] ) ) {
							$field['customizer']                       = $section['customizer'];
							$core->sections[ $k ]['fields'][ $fieldk ] = $field;
						}

						if ( isset( $field['permissions'] ) && false !== $field['permissions'] ) {
							if ( ! Redux_Helpers::current_user_can( $field['permissions'] ) ) {
								$data = isset( $core->options[ $field['id'] ] ) ? $core->options[ $field['id'] ] : $core->options_defaults[ $field['id'] ];

								$this->hidden_perm_fields[ $field['id'] ] = $data;

								continue;
							}
						}

						if ( ! isset( $field['id'] ) ) {
							echo '<br /><h3>No field ID is set.</h3><pre>';

							// phpcs:ignore WordPress.PHP.DevelopmentFunctions
							print_r( $field );

							echo '</pre><br />';

							continue;
						}

						if ( isset( $field['type'] ) && 'section' === $field['type'] ) {
							if ( isset( $field['indent'] ) && true === $field['indent'] ) {
								$section_ident = true;
							} else {
								$section_ident = false;
							}
						}

						if ( isset( $field['type'] ) && 'info' === $field['type'] && $section_ident ) {
							$field['indent'] = $section_ident;
						}

						$th = $core->render_class->get_header_html( $field );

						$field['name'] = $core->args['opt_name'] . '[' . $field['id'] . ']';

						// Set the default value if present.
						$core->options_defaults[ $field['id'] ] = isset( $core->options_defaults[ $field['id'] ] ) ? $core->options_defaults[ $field['id'] ] : '';

						// Set the defaults to the value if not present.
						$do_update = false;

						// Check fields for values in the default parameter.
						if ( ! isset( $core->options[ $field['id'] ] ) && isset( $field['default'] ) ) {
							$core->options_defaults[ $field['id'] ] = $field['default'];
							$core->options[ $field['id'] ]          = $field['default'];
							$do_update                              = true;

							// Check fields that hae no default value, but an options value with settings to
							// be saved by default.
						} elseif ( ! isset( $core->options[ $field['id'] ] ) && isset( $field['options'] ) ) {

							// If sorter field, check for options as save them as defaults.
							if ( 'sorter' === $field['type'] || 'sortable' === $field['type'] ) {
								$core->options_defaults[ $field['id'] ] = $field['options'];
								$core->options[ $field['id'] ]          = $field['options'];
								$do_update                              = true;
							}
						}

						// CORRECT URLS if media URLs are wrong, but attachment IDs are present.
						if ( 'media' === $field['type'] ) {
							if ( isset( $core->options[ $field['id'] ]['id'] ) && isset( $core->options[ $field['id'] ]['url'] ) && ! empty( $core->options[ $field['id'] ]['url'] ) && strpos( $core->options[ $field['id'] ]['url'], str_replace( 'http://', '', WP_CONTENT_URL ) ) === false ) {
								$data = wp_get_attachment_url( $core->options[ $field['id'] ]['id'] );

								if ( isset( $data ) && ! empty( $data ) ) {
									$core->options[ $field['id'] ]['url'] = $data;

									$data = wp_get_attachment_image_src(
										$core->options[ $field['id'] ]['id'],
										array(
											150,
											150,
										)
									);

									$core->options[ $field['id'] ]['thumbnail'] = $data[0];
									$do_update                                  = true;
								}
							}
						}

						if ( 'background' === $field['type'] ) {
							if ( isset( $core->options[ $field['id'] ]['media']['id'] ) && isset( $core->options[ $field['id'] ]['background-image'] ) && ! empty( $core->options[ $field['id'] ]['background-image'] ) && strpos( $core->options[ $field['id'] ]['background-image'], str_replace( array( 'http://', 'https://' ), '', WP_CONTENT_URL ) ) === false ) {
								$data = wp_get_attachment_url( $core->options[ $field['id'] ]['media']['id'] );

								if ( isset( $data ) && ! empty( $data ) ) {
									$core->options[ $field['id'] ]['background-image'] = $data;

									$data = wp_get_attachment_image_src(
										$core->options[ $field['id'] ]['media']['id'],
										array(
											150,
											150,
										)
									);

									$core->options[ $field['id'] ]['media']['thumbnail'] = $data[0];
									$do_update = true;
								}
							}
						}

						if ( 'slides' === $field['type'] ) {
							if ( isset( $core->options[ $field['id'] ] ) && is_array( $core->options[ $field['id'] ] ) && isset( $core->options[ $field['id'] ][0]['attachment_id'] ) && isset( $core->options[ $field['id'] ][0]['image'] ) && ! empty( $core->options[ $field['id'] ][0]['image'] ) && strpos( $core->options[ $field['id'] ][0]['image'], str_replace( array( 'http://', 'https://' ), '', WP_CONTENT_URL ) ) === false ) {
								foreach ( $core->options[ $field['id'] ] as $key => $val ) {
									$data = wp_get_attachment_url( $val['attachment_id'] );

									if ( isset( $data ) && ! empty( $data ) ) {
										$core->options[ $field['id'] ][ $key ]['image'] = $data;

										$data = wp_get_attachment_image_src(
											$val['attachment_id'],
											array(
												150,
												150,
											)
										);

										$core->options[ $field['id'] ][ $key ]['thumb'] = $data[0];
										$do_update                                      = true;
									}
								}
							}
						}
						// END -> CORRECT URLS if media URLs are wrong, but attachment IDs are present.
						if ( true === $do_update && ! isset( $core->never_save_to_db ) ) {
							if ( $core->args['save_defaults'] ) { // Only save that to the DB if allowed to.
								$run_update = true;
							}
						}

						if ( ! isset( $field['class'] ) ) { // No errors please.
							$field['class'] = '';
						}
						$id = $field['id'];

						/**
						 * Filter 'redux/options/{opt_name}/field/{field.id}'.
						 *
						 * @param array $field field config
						 */

						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						$field = apply_filters( "redux/options/{$core->args['opt_name']}/field/{$field['id']}", $field );

						if ( empty( $field ) || ! $field || false === $field ) {
							unset( $core->sections[ $k ]['fields'][ $fieldk ] );
							continue;
						}

						if ( ! empty( $core->folds[ $field['id'] ]['parent'] ) ) { // This has some fold items, hide it by default.
							$field['class'] .= ' fold';
						}

						if ( ! empty( $core->folds[ $field['id'] ]['children'] ) ) { // Sets the values you shoe fold children on.
							$field['class'] .= ' fold-parent';
						}

						if ( ! empty( $field['compiler'] ) ) {
							$field['class']                       .= ' compiler';
							$core->compiler_fields[ $field['id'] ] = 1;
						}

						if ( isset( $field['unit'] ) && ! isset( $field['units'] ) ) {
							$field['units'] = $field['unit'];
							unset( $field['unit'] );
						}

						$core->sections[ $k ]['fields'][ $fieldk ] = $field;

						if ( isset( $core->args['display_source'] ) ) {
							// phpcs:ignore WordPress.PHP.DevelopmentFunctions
							$th .= '<div id="' . $field['id'] . '-settings" style="display:none;"><pre>' . var_export( $core->sections[ $k ]['fields'][ $fieldk ], true ) . '</pre></div>';
							$th .= '<br /><a href="#TB_inline?width=600&height=800&inlineId=' . $field['id'] . '-settings" class="thickbox"><small>View Source</small></a>';
						}

						/**
						 * Action 'redux/options/{opt_name}/field/field.type}/register'
						 */

						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						do_action( "redux/options/{$core->args['opt_name']}/field/{$field['type']}/register", $field );

						$core->required_class->check_dependencies( $field );
						$core->field_head[ $field['id'] ] = $th;

						if ( ! $display || isset( $this->no_panel_section[ $k ] ) ) {
							$this->no_panel[] = $field['id'];
						} else {
							if ( isset( $field['disabled'] ) && $field['disabled'] ) {
								$field['label_for'] = 'redux_disable_field';
							}

							if ( isset( $field['hidden'] ) && $field['hidden'] ) {
								$field['label_for'] = 'redux_hide_field';
							}

							if ( true === $core->args['options_api'] ) {
								add_settings_field(
									"{$fieldk}_field",
									$th,
									array(
										$core->render_class,
										'field_input',
									),
									"{$core->args['opt_name']}{$k}_section_group",
									"{$core->args['opt_name']}{$k}_section",
									$field
								);
							}
						}
					}
				}
			}

			/**
			 * Action 'redux/options/{opt_name}/register'
			 *
			 * @param array option sections
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/options/{$core->args['opt_name']}/register", $core->sections );

			if ( $run_update && ! isset( $core->never_save_to_db ) ) { // Always update the DB with new fields.
				$this->set( $core->options );
			}

			if ( isset( $core->transients['run_compiler'] ) && $core->transients['run_compiler'] ) {

				$core->no_output = true;
				$temp            = $core->args['output_variables_prefix'];
				// Allow the override of variables prefix for use by SCSS or LESS.
				if ( isset( $core->args['compiler_output_variables_prefix'] ) ) {
					$core->args['output_variables_prefix'] = $core->args['compiler_output_variables_prefix'];
				}
				$core->output_class->enqueue();
				$core->args['output_variables_prefix'] = $temp;

				/**
				 * Action 'redux/options/{opt_name}/compiler'
				 *
				 * @param array  options
				 * @param string CSS that get sent to the compiler hook
				 */

				// phpcs:ignore WordPress.NamingConventions.ValidVariableName
				$compiler_css = $core->compilerCSS;

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action( "redux/options/{$core->args['opt_name']}/compiler", $core->options, $compiler_css, $core->transients['changed_values'], $core->output_variables );

				/**
				 * Action 'redux/options/{opt_name}/compiler/advanced'
				 *
				 * @param array  options
				 * @param string CSS that get sent to the compiler hook, which sends the full Redux object
				 */

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action( "redux/options/{$core->args['opt_name']}/compiler/advanced", $core );

				unset( $core->transients['run_compiler'] );
				$core->transient_class->set();
			}
		}

		/**
		 * Get default options into an array suitable for the settings API
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      array $this->options_defaults
		 */
		public function default_values() {
			$core = $this->core();

			if ( ! is_null( $core->sections ) && is_null( $core->options_defaults ) ) {
				$core->options_defaults = $core->options_defaults_class->default_values( $core->args['opt_name'], $core->sections, $core->wordpress_data );
			}

			/**
			 * Filter 'redux/options/{opt_name}/defaults'
			 *
			 * @param array $defaults option default values
			 */

			$core->transients['changed_values'] = isset( $core->transients['changed_values'] ) ? $core->transients['changed_values'] : array();

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$core->options_defaults = apply_filters( "redux/options/{$core->args['opt_name']}/defaults", $core->options_defaults, $core->transients['changed_values'] );

			return $core->options_defaults;
		}

		/**
		 * Validate the Options options before insertion
		 *
		 * @since       3.0.0
		 * @access      public
		 *
		 * @param       array $plugin_options The options array.
		 *
		 * @return array|mixed|string
		 */
		public function validate_options( $plugin_options ) {
			$core = $this->core();

			if ( isset( $core->validation_ran ) ) {
				return $plugin_options;
			}

			$core->validation_ran = 1;

			// Save the values not in the panel.
			if ( isset( $plugin_options['redux-no_panel'] ) ) {
				$keys = explode( '|', $plugin_options['redux-no_panel'] );
				foreach ( $keys as $key ) {
					$plugin_options[ $key ] = $core->options[ $key ];
				}
				if ( isset( $plugin_options['redux-no_panel'] ) ) {
					unset( $plugin_options['redux-no_panel'] );
				}
			}

			if ( is_array( $this->hidden_perm_fields ) && ! empty( $this->hidden_perm_fields ) ) {
				foreach ( $this->hidden_perm_fields as $id => $data ) {
					$plugin_options[ $id ] = $data;
				}
			}

			if ( $plugin_options === $core->options ) {
				return $plugin_options;
			}

			$time = time();

			// Sets last saved time.
			$core->transients['last_save'] = $time;

			$imported_options = array();

			if ( isset( $plugin_options['import_link'] ) && '' !== $plugin_options['import_link'] && ! ! wp_http_validate_url( $plugin_options['import_link'] ) ) {
				$import           = wp_remote_retrieve_body( wp_remote_get( $plugin_options['import_link'] ) );
				$imported_options = json_decode( $import, true );
			}
			if ( isset( $plugin_options['import_code'] ) && '' !== $plugin_options['import_code'] ) {
				$imported_options = json_decode( $plugin_options['import_code'], true );
			}

			// Import.
			$core->transients['last_save_mode'] = 'import'; // Last save mode.
			$core->transients['last_compiler']  = $time;
			$core->transients['last_import']    = $time;
			$core->transients['run_compiler']   = 1;

			if ( is_array( $imported_options ) && ! empty( $imported_options ) && isset( $imported_options['redux-backup'] ) && ( 1 === $imported_options['redux-backup'] || '1' === $imported_options['redux-backup'] ) ) {
				$core->transients['changed_values'] = array();
				foreach ( $plugin_options as $key => $value ) {
					if ( isset( $imported_options[ $key ] ) && $value !== $imported_options[ $key ] ) {
						$plugin_options[ $key ]                     = $value;
						$core->transients['changed_values'][ $key ] = $value;
					}
				}

				/**
				 * Action 'redux/options/{opt_name}/import'.
				 *
				 * @param  &array [&$plugin_options, redux_options]
				 */

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action_ref_array(
					"redux/options/{$core->args['opt_name']}/import", // phpcs:ignore WordPress.NamingConventions.ValidHookName
					array(
						&$plugin_options,
						$imported_options,
						$core->transients['changed_values'],
					)
				);

				setcookie( 'redux_current_tab_' . $core->args['opt_name'], '', 1, '/', $time + 1000, '/' );
				$_COOKIE[ 'redux_current_tab_' . $core->args['opt_name'] ] = 1;

				unset( $plugin_options['defaults'], $plugin_options['compiler'], $plugin_options['import'], $plugin_options['import_code'] );
				if ( in_array( $core->args['database'], array( 'transient', 'theme_mods', 'theme_mods_expanded', 'network' ), true ) ) {
					$this->set( $plugin_options );

					return;
				}

				$plugin_options = wp_parse_args( $imported_options, $plugin_options );

				$core->transient_class->set();

				return $plugin_options;
			}

			// Reset all to defaults.
			if ( ! empty( $plugin_options['defaults'] ) ) {
				if ( empty( $core->options_defaults ) ) {
					$core->options_defaults = $core->_default_values();
				}

				/**
				 * Filter: 'redux/validate/{opt_name}/defaults'.
				 *
				 * @param  &array [ $this->options_defaults, $plugin_options]
				 */

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$plugin_options = apply_filters( "redux/validate/{$core->args['opt_name']}/defaults", $core->options_defaults );

				$core->transients['changed_values'] = array();

				if ( empty( $core->options ) ) {
					$core->options = $core->options_defaults;
				}

				foreach ( $core->options as $key => $value ) {
					if ( isset( $plugin_options[ $key ] ) && $plugin_options[ $key ] !== $value ) {
						$core->transients['changed_values'][ $key ] = $value;
					}
				}

				$core->transients['run_compiler']   = 1;
				$core->transients['last_save_mode'] = 'defaults'; // Last save mode.

				$core->transient_class->set();

				return $plugin_options;
			}

			// Section reset to defaults.
			if ( ! empty( $plugin_options['defaults-section'] ) ) {
				if ( isset( $plugin_options['redux-section'] ) && isset( $core->sections[ $plugin_options['redux-section'] ]['fields'] ) ) {
					if ( empty( $core->options_defaults ) ) {
						$core->options_defaults = $core->_default_values();
					}

					foreach ( $core->sections[ $plugin_options['redux-section'] ]['fields'] as $field ) {
						if ( isset( $core->options_defaults[ $field['id'] ] ) ) {
							$plugin_options[ $field['id'] ] = $core->options_defaults[ $field['id'] ];
						} else {
							$plugin_options[ $field['id'] ] = '';
						}

						if ( isset( $field['compiler'] ) ) {
							$compiler = true;
						}
					}

					/**
					 * Filter: 'redux/validate/{opt_name}/defaults_section'.
					 *
					 * @param  &array [ $this->options_defaults, $plugin_options]
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$plugin_options = apply_filters( "redux/validate/{$core->args['opt_name']}/defaults_section", $plugin_options );
				}

				$core->transients['changed_values'] = array();
				foreach ( $core->options as $key => $value ) {
					if ( isset( $plugin_options[ $key ] ) && $plugin_options[ $key ] !== $value ) {
						$core->transients['changed_values'][ $key ] = $value;
					}
				}

				if ( isset( $compiler ) ) {
					$core->transients['last_compiler'] = $time;
					$core->transients['run_compiler']  = 1;
				}

				$core->transients['last_save_mode'] = 'defaults_section'; // Last save mode.

				unset( $plugin_options['defaults'], $plugin_options['defaults_section'], $plugin_options['import'], $plugin_options['import_code'], $plugin_options['import_link'], $plugin_options['compiler'], $plugin_options['redux-section'] );

				$core->transient_class->set();

				return $plugin_options;
			}

			$core->transients['last_save_mode'] = 'normal'; // Last save mode.

			/**
			 * Filter: 'redux/validate/{opt_name}/before_validation'
			 *
			 * @param  &array [&$plugin_options, redux_options]
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$plugin_options = apply_filters( "redux/validate/{$core->args['opt_name']}/before_validation", $plugin_options, $core->options );

			// Validate fields (if needed).
			$plugin_options = $core->validate_class->validate( $plugin_options, $core->options, $core->sections );

			// Sanitize options, if needed.
			$plugin_options = $core->sanitize_class->sanitize( $plugin_options, $core->options, $core->sections );

			if ( ! empty( $core->errors ) || ! empty( $core->warnings ) || ! empty( $core->sanitize ) ) {
				$core->transients['notices'] = array(
					'errors'   => $core->errors,
					'warnings' => $core->warnings,
					'sanitize' => $core->sanitize,
				);
			}

			if ( ! isset( $core->transients['changed_values'] ) ) {
				$core->transients['changed_values'] = array();
			}

			/**
			 * Action 'redux/options/{opt_name}/validate'
			 *
			 * @param  &array [&$plugin_options, redux_options]
			 */
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action_ref_array(
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
				"redux/options/{$core->args['opt_name']}/validate",
				array(
					&$plugin_options,
					$core->options,
					$core->transients['changed_values'],
				)
			);

			if ( ! empty( $plugin_options['compiler'] ) ) {
				unset( $plugin_options['compiler'] );

				$core->transients['last_compiler'] = $time;
				$core->transients['run_compiler']  = 1;
			}

			$core->transients['changed_values'] = array(); // Changed values since last save.

			if ( ! empty( $core->options ) ) {
				foreach ( $core->options as $key => $value ) {
					if ( isset( $plugin_options[ $key ] ) && $plugin_options[ $key ] !== $value ) {
						$core->transients['changed_values'][ $key ] = $value;
					}
				}
			}

			unset( $plugin_options['defaults'], $plugin_options['defaults_section'], $plugin_options['import'], $plugin_options['import_code'], $plugin_options['import_link'], $plugin_options['compiler'], $plugin_options['redux-section'] );
			if ( in_array( $core->args['database'], array( 'transient', 'theme_mods', 'theme_mods_expanded' ), true ) ) {
				$core->set( $core->args['opt_name'], $plugin_options );
				return;
			}

			if ( defined( 'WP_CACHE' ) && WP_CACHE && class_exists( 'W3_ObjectCache' ) && function_exists( 'w3_instance' ) ) {
				$w3_inst = w3_instance( 'W3_ObjectCache' );
				$w3      = $w3_inst->instance();
				$key     = $w3->_get_cache_key( $core->args['opt_name'] . '-transients', 'transient' );
				$w3->delete( $key, 'transient', true );
			}

			$core->transient_class->set();

			return $plugin_options;
		}

		/**
		 * ->get_default(); This is used to return the default value if default_show is set.
		 *
		 * @since       1.0.1
		 * @access      public
		 *
		 * @param       string $opt_name The option name to return.
		 * @param       mixed  $default  (null)  The value to return if default not set.
		 *
		 * @return      mixed $default
		 */
		public function get_default( $opt_name, $default = null ) {
			if ( true === $this->args['default_show'] ) {

				if ( empty( $this->options_defaults ) ) {
					$this->default_values(); // fill cache.
				}

				$default = array_key_exists( $opt_name, $this->options_defaults ) ? $this->options_defaults[ $opt_name ] : $default;
			}

			return $default;
		}

		/**
		 * Get the default value for an option
		 *
		 * @since  3.3.6
		 * @access public
		 *
		 * @param string $key       The option's ID.
		 * @param string $array_key The key of the default's array.
		 *
		 * @return mixed
		 */
		public function get_default_value( $key, $array_key = false ) {
			if ( empty( $this->options_defaults ) ) {
				$this->options_defaults = $this->default_values();
			}

			$defaults = $this->options_defaults;
			$value    = '';

			if ( isset( $defaults[ $key ] ) ) {
				if ( false !== $array_key && isset( $defaults[ $key ][ $array_key ] ) ) {
					$value = $defaults[ $key ][ $array_key ];
				} else {
					$value = $defaults[ $key ];
				}
			}

			return $value;
		}

	}

}
