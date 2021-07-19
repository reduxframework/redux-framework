<?php
/**
 * Redux Customizer Extension Class
 * Short description.
 *
 * @package ReduxFramework/Extentions
 * @class Redux_Extension_Customizer
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Customizer', false ) ) {

	/**
	 * Main ReduxFramework customizer extension class
	 *
	 * @since       1.0.0
	 */
	class Redux_Extension_Customizer extends Redux_Extension_Abstract {

		/**
		 * Set extension version.
		 *
		 * @var string
		 */
		public static $version = '4.0.0';

		/**
		 * Set the name of the field.  Ideally, this will also be your extension's name.
		 * Please use underscores and NOT dashes.
		 *
		 * @var string
		 */
		public $field_name = 'customizer';

		/**
		 * Set the friendly name of the extension.  This is for display purposes.  No underscores or dashes are required.
		 *
		 * @var string
		 */
		public $extension_name = 'Customizer';

		/**
		 * Set the minumum required version of Redux here (optional).
		 *
		 * Leave blank to require no minimum version.  This allows you to specify a minimum required version of
		 * Redux in the event you do not want to support older versions.
		 *
		 * @var string
		 */
		private $minimum_redux_version = '4.0.0';

		/**
		 * Original options.
		 *
		 * @var array
		 */
		private $orig_options = array();

		/**
		 * Post values.
		 *
		 * @var array
		 */
		private static $post_values = array();

		/**
		 * Options array.
		 *
		 * @var array
		 */
		public $options = array();

		/**
		 * Redux_Extension_my_extension constructor.
		 *
		 * @param object $parent ReduxFramework pointer.
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent, __FILE__ );

			if ( is_admin() && ! $this->is_minimum_version( $this->minimum_redux_version, self::$version, $this->extension_name ) ) {
				return;
			}

			$this->add_field( 'customizer' );

			$this->load();

		}

		/**
		 * The customizer load code
		 */
		private function load() {
			if ( false === $this->parent->args['customizer'] ) {
				return;
			}

			// Override the Redux_Core class.
			add_filter(
				"redux/extension/{$this->parent->args['opt_name']}/customizer",
				array(
					$this,
					'remove_core_customizer_class',
				)
			);

			global $pagenow, $wp_customize;

			if ( ! isset( $wp_customize ) && 'customize.php' !== $pagenow && 'admin-ajax.php' !== $pagenow ) {
				return;
			}

			self::get_post_values();

			// Create defaults array.
			$defaults = array();

			if ( isset( $_POST['wp_customize'] ) && 'on' === $_POST['wp_customize'] ) { // phpcs:ignore WordPress.Security.NonceVerification
				$this->parent->args['customizer_only'] = true;
			}

			if ( isset( $_POST['wp_customize'] ) && 'on' === $_POST['wp_customize'] && isset( $_POST['customized'] ) && ! empty( $_POST['customized'] ) && ! isset( $_POST['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				add_action(
					"redux/options/{$this->parent->args['opt_name']}/options",
					array(
						$this,
						'override_values',
					),
					100
				);
			}

			add_action( 'customize_register', array( $this, 'register_customizer_controls' ) ); // Create controls.
			add_action( 'wp_head', array( $this, 'customize_preview_init' ) );

			// phpcs:ignore Squiz.PHP.CommentedOutCode
			// add_action( 'customize_save', array( $this, 'customizer_save_before' ) ); // Before save.
			add_action( 'customize_save_after', array( &$this, 'customizer_save_after' ) ); // After save.

			// Add global controls CSS file.
			add_action( 'customize_controls_print_scripts', array( $this, 'enqueue_controls_css' ) );
			add_action( 'customize_controls_init', array( $this, 'enqueue_panel_css' ) );
			add_action( 'wp_enqueue_styles', array( $this, 'custom_css' ), 11 );
		}

		/**
		 * Enqueue extension scripts/styles.
		 */
		public function enqueue_controls_css() {
			$this->parent->enqueue_class->get_warnings_and_errors_array();
			$this->parent->enqueue_class->init();

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-extension-customizer',
					$this->extension_url . 'redux-extension-customizer.css',
					array(),
					self::$version
				);
			}

			wp_enqueue_script(
				'redux-extension-customizer-js',
				$this->extension_url . 'redux-extension-customizer' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'redux-js' ),
				self::$version,
				true
			);

			$custom_css  = '#' . $this->parent->core_thread . '{line-height:0;border:0;}';
			$custom_css .= '#' . $this->parent->core_instance . '{position:inherit!important;right:0!important;top:0!important;bottom:0!important;';
			$custom_css .= 'left:0!important;text-align:center;margin-bottom:0;line-height:0;-webkit-transition:left ease-in-out .18s;transition:left ease-in-out .18s;}';
			$custom_css .= '#' . $this->parent->core_instance . ' img{-webkit-transition:left ease-in-out .18s;transition:left ease-in-out .18s;}';

			wp_add_inline_style( 'redux-extension-customizer-css', $custom_css );

			wp_localize_script(
				'redux-extension-customizer',
				'redux_customizer',
				array(
					'body_class' => sanitize_html_class( 'admin-color-' . get_user_option( 'admin_color' ), 'fresh' ),
				)
			);
		}

		/**
		 * Enqueue panel CSS>
		 */
		public function enqueue_panel_css() {

		}

		/**
		 * Remove core customizer class.
		 *
		 * @param string $path Path to class.
		 *
		 * @return string
		 */
		public function remove_core_customizer_class( string $path ): string {
			return '';
		}

		/**
		 * Customize preview init.
		 */
		public function customize_preview_init() {
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/customizer/live_preview' );
		}

		/**
		 * Get post values.
		 */
		protected static function get_post_values() {
			if ( empty( self::$post_values ) && isset( $_POST['customized'] ) && ! empty( $_POST['customized'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				self::$post_values = json_decode( stripslashes_deep( sanitize_text_field( wp_unslash( $_POST['customized'] ) ) ), true ); // phpcs:ignore WordPress.Security.NonceVerification
			}
		}

		/**
		 * Override customizer values.
		 *
		 * @param array $data Values.
		 *
		 * @return array
		 */
		public function override_values( array $data ): array {
			self::get_post_values();

			if ( isset( $_POST['customized'] ) && ! empty( self::$post_values ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				if ( is_array( self::$post_values ) ) {
					foreach ( self::$post_values as $key => $value ) {
						if ( strpos( $key, $this->parent->args['opt_name'] ) !== false ) {

							$key          = str_replace( $this->parent->args['opt_name'] . '[', '', rtrim( $key, ']' ) );
							$data[ $key ] = $value;

							$GLOBALS[ $this->parent->args['global_variable'] ][ $key ] = $value;
							$this->parent->options[ $key ]                             = $value;
						}
					}
				}
			}

			return $data;
		}

		/**
		 * Render Redux fields.
		 *
		 * @param object $control .
		 */
		public function render( $control ) {
			$field_id = str_replace( $this->parent->args['opt_name'] . '-', '', $control->redux_id );
			$field    = $this->options[ $field_id ];

			if ( isset( $field['compiler'] ) && ! empty( $field['compiler'] ) ) {
				echo '<tr class="compiler">';
			} else {
				echo '<tr>';
			}
			echo '<th scope="row">' . wp_kses_post( $this->parent->field_head[ $field['id'] ] ) . '</th>';
			echo '<td>';

			$field['name'] = $field['id'];
			$this->parent->render_class->field_input( $field );
			echo '</td>';
			echo '</tr>';
		}

		// All sections, settings, and controls will be added here.

		/**
		 * Register customizer controls.
		 *
		 * @param WP_Customize_Manager $wp_customize .
		 */
		public function register_customizer_controls( WP_Customize_Manager $wp_customize ) {
			if ( ! class_exists( 'Redux_Customizer_Section' ) ) {
				require_once dirname( __FILE__ ) . '/inc/class-redux-customizer-section.php';
				if ( method_exists( $wp_customize, 'register_section_type' ) ) {
					$wp_customize->register_section_type( 'Redux_Customizer_Section' );
				}
			}
			if ( ! class_exists( 'Redux_Customizer_Panel' ) ) {
				require_once dirname( __FILE__ ) . '/inc/class-redux-customizer-panel.php';
				if ( method_exists( $wp_customize, 'register_panel_type' ) ) {
					$wp_customize->register_panel_type( 'Redux_Customizer_Panel' );
				}
			}
			if ( ! class_exists( 'Redux_Customizer_Control' ) ) {
				require_once dirname( __FILE__ ) . '/inc/class-redux-customizer-control.php';
			}

			require_once dirname( __FILE__ ) . '/inc/class-redux-customizer-fields.php';
			require_once dirname( __FILE__ ) . '/inc/class-redux-customizer-section-dev.php';
			require_once dirname( __FILE__ ) . '/inc/class-redux-customizer-control-dev.php';

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/extension/customizer/control/includes' );

			$order = array(
				'heading' => - 500,
				'option'  => - 500,
			);

			$defaults = array(
				'default-color'          => '',
				'default-image'          => '',
				'wp-head-callback'       => '',
				'admin-head-callback'    => '',
				'admin-preview-callback' => '',
			);

			$panel = '';

			$this->parent->args['options_api'] = false;
			$this->parent->_register_settings();

			$parent_section_id = null;
			$new_parent        = true;

			foreach ( $this->parent->sections as $key => $section ) {
				// Not a type that should go on the customizer.
				if ( isset( $section['type'] ) && ( 'divide' === $section['type'] ) ) {
					continue;
				}

				if ( isset( $section['id'] ) && 'import/export' === $section['id'] ) {
					continue;
				}

				// If section customizer is set to false.
				if ( isset( $section['customizer'] ) && false === $section['customizer'] ) {
					continue;
				}
				// if we are in a subsection and parent is set to customizer false !!!
				if ( ( isset( $section['subsection'] ) && $section['subsection'] ) ) {
					if ( $new_parent ) {
						$new_parent        = false;
						$parent_section_id = ( $key - 1 );
					}
				} else { // not a subsection reset.
					$parent_section_id = null;
					$new_parent        = true;
				}
				if ( isset( $parent_section_id ) && ( isset( $this->parent->sections[ $parent_section_id ]['customizer'] ) && false === $this->parent->sections[ $parent_section_id ]['customizer'] ) ) {
					continue;
				}

				$section['permissions'] = $section['permissions'] ?? 'edit_theme_options';

				// No errors please.
				if ( ! isset( $section['desc'] ) ) {
					$section['desc'] = '';
				}

				// Fill the description if there is a subtitle.
				if ( empty( $section['desc'] ) && ! empty( $section['subtitle'] ) ) {
					$section['desc'] = $section['subtitle'];
				}

				// Let's make a section ID from the title.
				if ( empty( $section['id'] ) ) {
					$section['id'] = Redux_Core::strtolower( str_replace( ' ', '', $section['title'] ) );
				}

				// No title is present, let's show what section is missing a title.
				if ( ! isset( $section['title'] ) ) {
					$section['title'] = '';
				}

				// Let's set a default priority.
				if ( empty( $section['priority'] ) ) {
					$section['priority'] = $order['heading'];
					$order['heading'] ++;
				}
				$section['id'] = $this->parent->args['opt_name'] . '-' . $section['id'];

				if ( method_exists( $wp_customize, 'add_panel' ) && ( ! isset( $section['subsection'] ) || ( isset( $section['subsection'] ) && true !== $section['subsection'] ) ) && isset( $this->parent->sections[ ( $key + 1 ) ]['subsection'] ) && $this->parent->sections[ ( $key + 1 ) ]['subsection'] ) {
					$this->add_panel(
						$this->parent->args['opt_name'] . '-' . $section['id'],
						array(
							'priority'    => $section['priority'],
							'capability'  => $section['permissions'],
							'title'       => $section['title'],
							'section'     => $section,
							'opt_name'    => $this->parent->args['opt_name'],
							'description' => '',
						),
						$wp_customize
					);

					$panel = $this->parent->args['opt_name'] . '-' . $section['id'];

					$this->add_section(
						$section['id'],
						array(
							'title'       => $section['title'],
							'priority'    => $section['priority'],
							'description' => $section['desc'],
							'section'     => $section,
							'opt_name'    => $this->parent->args['opt_name'],
							'capability'  => $section['permissions'],
							'panel'       => $panel,
						),
						$wp_customize
					);
				} else {
					if ( ! isset( $section['subsection'] ) || ( isset( $section['subsection'] ) && true !== $section['subsection'] ) ) {
						$panel = '';
					}

					$this->add_section(
						$section['id'],
						array(
							'title'       => $section['title'],
							'priority'    => $section['priority'],
							'description' => $section['desc'],
							'opt_name'    => $this->parent->args['opt_name'],
							'section'     => $section,
							'capability'  => $section['permissions'],
							'panel'       => $panel,
						),
						$wp_customize
					);
				}

				if ( ! isset( $section['fields'] ) || ( isset( $section['fields'] ) && empty( $section['fields'] ) ) ) {
					continue;
				}

				foreach ( $section['fields'] as $skey => $option ) {

					if ( isset( $option['customizer'] ) && false === $option['customizer'] ) {
						continue;
					}

					if ( false === $this->parent->args['customizer'] && ( ! isset( $option['customizer'] ) || true !== $option['customizer'] ) ) {
						continue;
					}

					$this->options[ $option['id'] ] = $option;
					add_action(
						'redux/advanced_customizer/control/render/' . $this->parent->args['opt_name'] . '-' . $option['id'],
						array(
							$this,
							'render',
						)
					);

					$option['permissions'] = $option['permissions'] ?? 'edit_theme_options';

					// Change the item priority if not set.
					if ( 'heading' !== $option['type'] && ! isset( $option['priority'] ) ) {
						$option['priority'] = $order['option'];
						$order['option'] ++;
					}

					if ( ! empty( $this->options_defaults[ $option['id'] ] ) ) {
						$option['default'] = $this->options_defaults['option']['id'];
					}

					if ( ! isset( $option['default'] ) ) {
						$option['default'] = '';
					}
					if ( ! isset( $option['title'] ) ) {
						$option['title'] = '';
					}

					$option['id'] = $this->parent->args['opt_name'] . '[' . $option['id'] . ']';

					if ( 'heading' !== $option['type'] && 'import_export' !== $option['type'] && ! empty( $option['type'] ) ) {

						$wp_customize->add_setting(
							$option['id'],
							array(
								'default'           => $option['default'],
								'transport'         => 'refresh',
								'opt_name'          => $this->parent->args['opt_name'],
								'sanitize_callback' => array( $this, 'field_validation' ),

								// phpcs:ignore Squiz.PHP.CommentedOutCode
								// 'type'              => 'option',
								// 'capabilities'     => $option['permissions'],
								// 'capabilities'      => 'edit_theme_options',
								// 'capabilities'   => $this->parent->args['page_permissions'],
								// 'theme_supports'    => '',
								// 'sanitize_callback' => '__return_false',
								// 'sanitize_js_callback' =>array( &$parent, '_field_input' ),
							)
						);
					}

					if ( ! empty( $option['data'] ) && empty( $option['options'] ) ) {
						if ( empty( $option['args'] ) ) {
							$option['args'] = array();
						}

						if ( 'elusive-icons' === $option['data'] || 'elusive-icon' === $option['data'] || 'elusive' === $option['data'] ) {
							$icons_file = Redux_Core::$dir . 'inc/fields/select/elusive-icons.php';

							// phpcs:ignore WordPress.NamingConventions.ValidHookName
							$icons_file = apply_filters( 'redux-font-icons-file', $icons_file );

							if ( file_exists( $icons_file ) ) {
								require_once $icons_file;
							}
						}

						$option['options'] = $this->parent->wordpress_data->get( $option['data'], $option['args'], $this->parent->args['opt_name'] );
					}

					$class_name = 'Redux_Customizer_Control_' . $option['type'];

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action( 'redux/extension/customizer/control_init', $option );

					if ( ! class_exists( $class_name ) ) {
						continue;
					}

					$wp_customize->add_control(
						new $class_name(
							$wp_customize,
							$option['id'],
							array(
								'label'           => $option['title'],
								'section'         => $section['id'],
								'settings'        => $option['id'],
								'type'            => 'redux-' . $option['type'],
								'field'           => $option,
								'ReduxFramework'  => $this->parent,
								'active_callback' => ( isset( $option['required'] ) && class_exists( 'Redux_Customizer_Active_Callback' ) ) ? array(
									'Redux_Customizer_Active_Callback',
									'evaluate',
								) : '__return_true',
								'priority'        => $option['priority'],
							)
						)
					);

					$section['fields'][ $skey ]['name'] = $option['id'];
					if ( ! isset( $section['fields'][ $skey ]['class'] ) ) { // No errors please.
						$section['fields'][ $skey ]['class'] = '';
					}

					$this->controls[ $section['fields'][ $skey ]['id'] ] = $section['fields'][ $skey ];

					add_action(
						'redux/advanced_customizer/render/' . $option['id'],
						array(
							$this,
							'field_render',
						),
						$option['priority']
					);
				}
			}
		}

		/**
		 * Add customizer section.
		 *
		 * @param string               $id           ID.
		 * @param array                $args         Args.
		 * @param WP_Customize_Manager $wp_customize .
		 */
		public function add_section( string $id, array $args, WP_Customize_Manager $wp_customize ) {

			if ( is_a( $id, 'WP_Customize_Section' ) ) {
				$section = $id;
			} else {
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$section_class = apply_filters( 'redux/customizer/section/class_name', 'Redux_Customizer_Section' );
				$section       = new $section_class( $wp_customize, $id, $args );
			}

			$wp_customize->add_section( $section, $args );
		}

		/**
		 * Add a customize panel.
		 *
		 * @param WP_Customize_Panel|string $id           Customize Panel object, or Panel ID.
		 * @param array                     $args         Optional. Panel arguments. Default empty array.
		 * @param WP_Customize_Manager      $wp_customize .
		 *
		 * @since  4.0.0
		 * @access public
		 */
		public function add_panel( $id, array $args, WP_Customize_Manager $wp_customize ) {
			if ( is_a( $id, 'WP_Customize_Panel' ) ) {
				$panel = $id;
			} else {
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$panel_class = apply_filters( 'redux/customizer/panel/class_name', 'Redux_Customizer_Panel' );
				$panel       = new $panel_class( $wp_customize, $id, $args );
			}

			$wp_customize->add_panel( $panel, $args );
		}

		/**
		 * Render Redux fields.
		 *
		 * @param array $option Option.
		 */
		public function field_render( array $option ) {
			echo '1';
			preg_match_all( '/\[([^\]]*)\]/', $option->id, $matches );
			$id = $matches[1][0];
			echo esc_url( $option->link() );

			$this->parent->render_class->field_input( $this->controls[ $id ] );
			echo '2';
		}

		/**
		 * Actions to take before customizer save.
		 *
		 * @param array $plugin_options .
		 */
		public function customizer_save_before( array $plugin_options ) {
			$this->before_save = $this->parent->options;
		}

		/**
		 * Actions to take after customizer save.
		 *
		 * @param WP_Customize_Manager $wp_customize .
		 */
		public function customizer_save_after( WP_Customize_Manager $wp_customize ) {
			if ( empty( $this->parent->options ) ) {
				$this->parent->get_options();
			}
			if ( empty( $this->orig_options ) && ! empty( $this->parent->options ) ) {
				$this->orig_options = $this->parent->options;
			}

			if ( isset( $_POST['customized'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$options = json_decode( sanitize_text_field( wp_unslash( $_POST['customized'] ) ), true ); // phpcs:ignore WordPress.Security.NonceVerification

				$compiler = false;
				$changed  = false;

				foreach ( $options as $key => $value ) {
					if ( strpos( $key, $this->parent->args['opt_name'] ) !== false ) {
						$key = str_replace( $this->parent->args['opt_name'] . '[', '', rtrim( $key, ']' ) );

						if ( ! isset( $this->orig_options[ $key ] ) || $value !== $this->orig_options[ $key ] || ( isset( $this->orig_options[ $key ] ) && ! empty( $this->orig_options[ $key ] ) && empty( $value ) ) ) {
							$this->parent->options[ $key ] = $value;
							$changed                       = true;
							if ( isset( $this->parent->compiler_fields[ $key ] ) ) {
								$compiler = true;
							}
						}
					}
				}

				if ( $changed ) {
					$this->parent->set_options( $this->parent->options );
					if ( $compiler ) {
						// Have to set this to stop the output of the CSS and typography stuff.
						$this->parent->no_output = true;
						$this->parent->output_class->enqueue();

						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						do_action( "redux/options/{$this->parent->args['opt_name']}/compiler", $this->parent->options, $this->parent->compilerCSS );

						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						do_action( "redux/options/{$this->args['opt_name']}/compiler/advanced", $this->parent );
					}
				}
			}
		}

		/**
		 * Enqueue CSS/JS for preview pane
		 *
		 * @since       1.0.0
		 * @access      public
		 * @global      $wp_styles
		 * @return      void
		 */
		public function enqueue_previewer() {
			wp_enqueue_script( 'redux-extension-previewer-js', $this->extension_url . 'assets/js/preview.js', array(), self::$version, true );

			$localize = array(
				'save_pending'   => esc_html__( 'You have changes that are not saved. Would you like to save them now?', 'redux-framework' ),
				'reset_confirm'  => esc_html__( 'Are you sure? Resetting will lose all custom values.', 'redux-framework' ),
				'preset_confirm' => esc_html__( 'Your current options will be replaced with the values of this preset. Would you like to proceed?', 'redux-framework' ),
				'opt_name'       => $this->args['opt_name'],
				'options'        => $this->parent->options,
				'defaults'       => $this->parent->options_defaults,

				// phpcs:ignore Squiz.PHP.CommentedOutCode
				// 'folds'             => $this->folds,
			);

			wp_localize_script( 'redux-extension-previewer-js', 'reduxPost', $localize );
		}

		/**
		 * Enqueue CSS/JS for the customizer controls
		 *
		 * @since       1.0.0
		 * @access      public
		 * @global      $wp_styles
		 * @return      void
		 */
		public function enqueue() {
			global $wp_styles;

			$localize = array(
				'save_pending'   => esc_html__( 'You have changes that are not saved.  Would you like to save them now?', 'redux-framework' ),
				'reset_confirm'  => esc_html__( 'Are you sure?  Resetting will lose all custom values.', 'redux-framework' ),
				'preset_confirm' => esc_html__( 'Your current options will be replaced with the values of this preset.  Would you like to proceed?', 'redux-framework' ),
				'opt_name'       => $this->args['opt_name'],
				'field'          => $this->parent->options,
				'defaults'       => $this->parent->options_defaults,

				// phpcs:ignore Squiz.PHP.CommentedOutCode
				// 'folds'             => $this->folds,
			);

			// Values used by the javascript.
			wp_localize_script( 'redux-js', 'redux_opts', $localize );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux-enqueue-' . $this->args['opt_name'] );

			foreach ( $this->sections as $section ) {
				if ( isset( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						if ( isset( $field['type'] ) ) {
							$field_classes = array( 'Redux_' . $field['type'], 'ReduxFramework_' . $field['type'] );

							$field_class = Redux_Functions::class_exists_ex( $field_classes );

							if ( false === $field_class ) {

								// phpcs:ignore WordPress.NamingConventions.ValidHookName
								$class_file = apply_filters( 'redux-typeclass-load', $this->path . 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field_class );

								if ( $class_file ) {
									require_once $class_file;

									$field_class = Redux_Functions::class_exists_ex( $field_classes );

								}
							}

							if ( class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) {
								$enqueue = new $field_class( '', '', $this );
								$enqueue->enqueue();
							}
						}
					}
				}
			}
		}

		/**
		 * Register Option for use
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function register_setting() {

		}

		/**
		 * Validate the options before insertion
		 *
		 * @param       array|string $value The options array.
		 *
		 * @return      string|array
		 * @since       3.0.0
		 * @access      public
		 */
		public function field_validation( $value ) {

			return $value;
		}

		/**
		 * HTML OUTPUT.
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function customizer_html_output() {

		}
	}

	if ( ! function_exists( 'redux_customizer_custom_validation' ) ) {
		/**
		 * Custom validation.
		 *
		 * @param mixed $field Field.
		 *
		 * @return mixed
		 */
		function redux_customizer_custom_validation( $field ) {
			return $field;
		}
	}

	if ( ! class_exists( 'ReduxFramework_extension_customizer' ) ) {
		class_alias( 'Redux_Extension_Customizer', 'ReduxFramework_extension_customizer' );
	}
}
