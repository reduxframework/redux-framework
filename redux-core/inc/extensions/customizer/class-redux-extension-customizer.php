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
		public static $version = '4.3.11';

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
		 * Controls array.
		 *
		 * @var array
		 */
		public $controls = array();

		/**
		 * Before save array.
		 *
		 * @var array
		 */
		public $before_save = array();

		/**
		 * Redux object.
		 *
		 * @var object
		 */
		protected $redux;

		/**
		 * Field array.
		 *
		 * @var array
		 */
		private $redux_fields = array();

		/**
		 * Redux_Extension_my_extension constructor.
		 *
		 * @param ReduxFramework $parent ReduxFramework pointer.
		 */
		public function __construct( $parent ) {
			global $pagenow;
			global $wp_customize;

			parent::__construct( $parent, __FILE__ );

			if ( is_admin() && ! isset( $wp_customize ) && 'customize.php' !== $pagenow && 'admin-ajax.php' !== $pagenow ) {
				return;
			}

			$this->add_field( 'customizer' );

			$this->load();
		}

		/**
		 * The customizer load code
		 */
		private function load() {
			global $pagenow, $wp_customize;

			if ( false === $this->parent->args['customizer'] ) {
				return;
			}

			// Override the Redux_Core class.
			add_filter( "redux/extension/{$this->parent->args['opt_name']}/customizer", array( $this, 'remove_core_customizer_class' ) );

			if ( ! isset( $wp_customize ) && 'customize.php' !== $pagenow && 'admin-ajax.php' !== $pagenow ) {
				return;
			}

			self::get_post_values();

			if ( isset( $_POST['wp_customize'] ) && 'on' === $_POST['wp_customize'] ) { // phpcs:ignore WordPress.Security.NonceVerification
				$this->parent->args['customizer_only'] = true;
			}

			if ( isset( $_POST['wp_customize'] ) && 'on' === $_POST['wp_customize'] && isset( $_POST['customized'] ) && ! empty( $_POST['customized'] ) && ! isset( $_POST['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				add_action( "redux/options/{$this->parent->args['opt_name']}/options", array( $this, 'override_values' ), 100 );
			}

			add_action( 'customize_register', array( $this, 'register_customizer_controls' ) ); // Create controls.
			add_action( 'wp_head', array( $this, 'customize_preview_init' ) );

			add_action( 'customize_save_after', array( &$this, 'customizer_save_after' ) ); // After save.

			// Add global controls CSS file.
			add_action( 'customize_controls_print_scripts', array( $this, 'enqueue_controls_css' ) );
			add_action( 'customize_controls_init', array( $this, 'enqueue_panel_css' ) );
			add_action( 'wp_enqueue_styles', array( $this, 'custom_css' ), 11 );

			add_action( 'redux/extension/customizer/control_init', array( $this, 'create_field_classes' ), 1, 2 );

			add_action( 'wp_ajax_' . $this->parent->args['opt_name'] . '_customizer_save', array( $this, 'customizer' ) );
			add_action( 'customize_controls_print_styles', array( $this, 'add_nonce_html' ) );
		}

		/**
		 * Add nonce HTML for AJAX.
		 */
		public function add_nonce_html() {
			$nonce = wp_create_nonce( 'redux_customer_nonce' );

			?>
			<div class="redux-customizer-nonce" data-nonce="<?php echo esc_attr( $nonce ); ?>"></div>
			<?php
		}

		/**
		 * AJAX callback for customizer save...to make sanitize/validate work.
		 */
		public function customizer() {
			try {
				$return_array = array();

				if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'redux_customer_nonce' ) && isset( $_POST['opt_name'] ) && '' !== $_POST['opt_name'] ) {
					$redux = Redux::instance( sanitize_text_field( wp_unslash( $_POST['opt_name'] ) ) );

					$post_data = wp_unslash( $_POST['data'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

					// New method to avoid input_var nonsense.  Thanks @harunbasic.
					$values = Redux_Functions_Ex::parse_str( $post_data );

					$all_options = get_option( sanitize_text_field( wp_unslash( $_POST['opt_name'] ) ) );

					$values = wp_parse_args( $values, $all_options );

					$redux->options_class->set( $redux->options_class->validate_options( $values ) );

					$redux->enqueue_class->get_warnings_and_errors_array();

					$return_array = array(
						'status'   => 'success',
						'options'  => $redux->options,
						'errors'   => $redux->enqueue_class->localize_data['errors'] ?? null,
						'warnings' => $redux->enqueue_class->localize_data['warnings'] ?? null,
						'sanitize' => $redux->enqueue_class->localize_data['sanitize'] ?? null,
					);
				}
			} catch ( Exception $e ) {
				$return_array = array( 'status' => $e->getMessage() );
			}

			echo wp_json_encode( $return_array );

			die;
		}

		/**
		 * Field classes.
		 *
		 * @param array $option Option.
		 */
		public function create_field_classes( array $option ) {
			if ( empty( $this->redux_fields ) ) {
				$file_paths = glob( Redux_Core::$dir . 'inc/fields/*' );

				foreach ( $file_paths as $file ) {
					if ( 'section' !== $file && 'divide' !== $file && 'editor' !== $file ) {
						$this->redux_fields[] = str_replace( Redux_Core::$dir . 'inc/fields/', '', $file );
					}
				}
			}

			$class_name = 'Redux_Customizer_Control_' . $option['type'];

			if ( ! class_exists( $class_name ) && ( in_array( $option['type'], $this->redux_fields, true ) || ( isset( $option['customizer_enabled'] ) && $option['customizer_enabled'] ) ) ) {
				$upload_dir = Redux_Core::$upload_dir;

				if ( ! file_exists( $upload_dir . $option['type'] . '.php' ) ) {
					if ( ! is_dir( $upload_dir ) ) {
						$this->parent->filesystem->execute( 'mkdir', $upload_dir );
					}

					$template = str_replace( '{{type}}', $option['type'], '<?php' . PHP_EOL . '   class Redux_Customizer_Control_{{type}} extends Redux_Customizer_Control {' . PHP_EOL . '     public $type = "redux-{{type}}";' . PHP_EOL . '   }' );

					$this->parent->filesystem->execute( 'put_contents', $upload_dir . $option['type'] . '.php', array( 'content' => $template ) );
				}

				if ( file_exists( $upload_dir . $option['type'] . '.php' ) ) {
					include_once $upload_dir . $option['type'] . '.php';
				}
			}
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
				'redux-extension-customizer',
				$this->extension_url . 'redux-extension-customizer' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'redux-js' ),
				self::$version,
				true
			);

			$custom_css  = '#' . $this->parent->core_thread . '{line-height:0;border:0;}';
			$custom_css .= '#' . $this->parent->core_instance . '{position:inherit!important;right:0!important;top:0!important;bottom:0!important;';
			$custom_css .= 'left:0!important;text-align:center;margin-bottom:0;line-height:0;-webkit-transition:left ease-in-out .18s;transition:left ease-in-out .18s;}';
			$custom_css .= '#' . $this->parent->core_instance . ' img{-webkit-transition:left ease-in-out .18s;transition:left ease-in-out .18s;}';

			wp_add_inline_style( 'redux-extension-customizer', $custom_css );

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
		public function enqueue_panel_css() {}

		/**
		 * Remove core customizer class.
		 *
		 * @return string
		 */
		public function remove_core_customizer_class(): string {
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

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/extension/customizer/control/includes' );

			$order = array(
				'heading' => - 500,
				'option'  => - 500,
			);

			$panel = '';

			$this->parent->args['options_api'] = false;
			$this->parent->options_class->register();

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

				// No title is present, let's show what section is missing a title.
				if ( ! isset( $section['title'] ) ) {
					$section['title'] = '';
				}

				// Let's make a section ID from the title.
				if ( empty( $section['id'] ) ) {
					$section['id'] = Redux_Core::strtolower( str_replace( ' ', '', $section['title'] ) );
				}

				// Let's set a default priority.
				if ( empty( $section['priority'] ) ) {
					$section['priority'] = $order['heading'];
					$order['heading'] ++;
				}
				$section['id'] = $this->parent->args['opt_name'] . '-' . $section['id'];

				if ( method_exists( $wp_customize, 'add_panel' ) && ( ! isset( $section['subsection'] ) || ( true !== $section['subsection'] ) ) && isset( $this->parent->sections[ ( $key + 1 ) ]['subsection'] ) && $this->parent->sections[ ( $key + 1 ) ]['subsection'] ) {
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
					if ( ! isset( $section['subsection'] ) || ( true !== $section['subsection'] ) ) {
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

				if ( ( empty( $section['fields'] ) ) ) {
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
					add_action( 'redux/customizer/control/render/' . $this->parent->args['opt_name'] . '-' . $option['id'], array( $this, 'render' ) );

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
						$option['options'] = $this->parent->wordpress_data->get( $option['data'], $option['args'] );
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
				$section = new Redux_Customizer_Section( $wp_customize, $id, $args );
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
				$panel = new Redux_Customizer_Panel( $wp_customize, $id, $args );
			}

			$wp_customize->add_panel( $panel, $args );
		}

		/**
		 * Actions to take after customizer save.
		 */
		public function customizer_save_after() {
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
					$this->parent->options_class->set( $this->parent->options );
					if ( $compiler ) {
						// Have to set this to stop the output of the CSS and typography stuff.
						$this->parent->no_output = true;
						$this->parent->output_class->enqueue();

						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						do_action( "redux/options/{$this->parent->args['opt_name']}/compiler", $this->parent->options, $this->parent->compilerCSS );

						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						do_action( "redux/options/{$this->parent->args['opt_name']}/compiler/advanced", $this->parent );
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
				'opt_name'       => $this->parent->args['opt_name'],
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
			$localize = array(
				'save_pending'   => esc_html__( 'You have changes that are not saved.  Would you like to save them now?', 'redux-framework' ),
				'reset_confirm'  => esc_html__( 'Are you sure?  Resetting will lose all custom values.', 'redux-framework' ),
				'preset_confirm' => esc_html__( 'Your current options will be replaced with the values of this preset.  Would you like to proceed?', 'redux-framework' ),
				'opt_name'       => $this->parent->args['opt_name'],
				'field'          => $this->parent->options,
				'defaults'       => $this->parent->options_defaults,
				'folds'          => $this->parent->folds,
			);

			// Values used by the javascript.
			wp_localize_script( 'redux-js', 'redux_opts', $localize );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux-enqueue-' . $this->parent->args['opt_name'] );

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
		 * @param array|string $value The options array.
		 *
		 * @return array|string $value
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
}
