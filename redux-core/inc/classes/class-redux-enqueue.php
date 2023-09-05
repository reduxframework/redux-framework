<?php
/**
 * Redux Primary Enqueue Class
 *
 * @class Redux_Core
 * @version 4.0.0
 * @package Redux Framework/Classes
 * @noinspection PhpIgnoredClassAliasDeclaration
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Enqueue', false ) ) {

	/**
	 * Class Redux_Enqueue
	 */
	class Redux_Enqueue extends Redux_Class {

		/**
		 * Data to localize.
		 *
		 * @var array
		 */
		public $localize_data = array();

		/**
		 * Min string for .min files.
		 *
		 * @var string
		 */
		private $min = '';

		/**
		 * Timestamp for file versions.
		 *
		 * @var string
		 */
		private $timestamp = '';

		/**
		 * Localize data required for the repeater extension.
		 *
		 * @var array
		 */
		private $repeater_data = array();

		/**
		 * Redux_Enqueue constructor.
		 *
		 * @param     object $redux ReduxFramework pointer.
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux );

			// Enqueue the admin page CSS and JS.
			if ( isset( $_GET['page'] ) && $_GET['page'] === $redux->args['page_slug'] ) { // phpcs:ignore WordPress.Security.NonceVerification
				add_action( 'admin_enqueue_scripts', array( $this, 'init' ), 1 );
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_init' ), 10 );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/{$redux->args['opt_name']}/enqueue/construct", $this );
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/enqueue/construct', $this );
		}

		/**
		 * Scripts to enqueue on the frontend
		 */
		public function frontend_init() {
			$core = $this->core();

			if ( $core->args['elusive_frontend'] ) {
				Redux_Functions_Ex::enqueue_elusive_font();
			}

			if ( $core->args['fontawesome_frontend'] ) {
				Redux_Functions_Ex::enqueue_font_awesome();
			}
		}

		/**
		 * Class init functions.
		 */
		public function init() {
			$core = $this->core();

			Redux_Functions::$parent = $core;
			Redux_CDN::$parent       = $core;

			$this->min = Redux_Functions::is_min();

			$this->timestamp = Redux_Core::$version;
			if ( $core->args['dev_mode'] ) {
				$this->timestamp .= '.' . time();
			}

			$this->register_styles( $core );
			$this->register_scripts();

			add_thickbox();

			$this->enqueue_fields( $core );

			add_filter( "redux/{$core->args['opt_name']}/localize", array( 'Redux_Helpers', 'localize' ) );

			$this->set_localized_data( $core );

			/**
			 * Action 'redux/page/{opt_name}/enqueue'
			 */
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/page/{$core->args['opt_name']}/enqueue" );
		}

		/**
		 * Register all core framework styles.
		 *
		 * @param     object $core ReduxFramework object.
		 */
		private function register_styles( $core ) {

			/**
			 * Redux Admin CSS
			 */
			if ( 'wordpress' === $core->args['admin_theme'] || 'wp' === $core->args['admin_theme'] ) { // phpcs:ignore WordPress.WP.CapitalPDangit
				$color_scheme = get_user_option( 'admin_color' );
			} elseif ( 'classic' === $core->args['admin_theme'] || '' === $core->args['admin_theme'] ) {
				$color_scheme = 'classic';
			} else {
				$color_scheme = $core->args['admin_theme'];
			}

			if ( ! file_exists( Redux_Core::$dir . "assets/css/colors/$color_scheme/colors$this->min.css" ) ) {
				$color_scheme = 'fresh';
			}

			$css = Redux_Core::$url . "assets/css/colors/$color_scheme/colors$this->min.css";

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$css = apply_filters( 'redux/enqueue/' . $core->args['opt_name'] . '/args/admin_theme/css_url', $css );

			wp_register_style(
				'redux-admin-theme',
				$css,
				array(),
				$this->timestamp
			);

			wp_enqueue_style(
				'redux-admin-css',
				Redux_Core::$url . "assets/css/redux-admin$this->min.css",
				array( 'redux-admin-theme' ),
				$this->timestamp
			);

			/**
			 * Redux Fields CSS
			 */
			if ( ! $core->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-fields',
					Redux_Core::$url . 'assets/css/redux-fields.min.css',
					array(),
					$this->timestamp
				);
			}

			/**
			 * Select2 CSS
			 */
			wp_enqueue_style(
				'select2-css',
				Redux_Core::$url . 'assets/css/vendor/select2.min.css',
				array(),
				'4.1.0'
			);

			/**
			 * Spectrum CSS
			 */
			wp_register_style(
				'redux-spectrum-css',
				Redux_Core::$url . "assets/css/vendor/spectrum$this->min.css",
				array(),
				'1.3.3'
			);

			/**
			 * Elusive Icon CSS
			 */
			Redux_Functions_Ex::enqueue_elusive_font();

			/**
			 * Font Awesome for Social Profiles and Icon Select
			 */
			Redux_Functions_Ex::enqueue_font_awesome();

			/**
			 * QTip CSS
			 */
			wp_enqueue_style(
				'qtip',
				Redux_Core::$url . "assets/css/vendor/qtip$this->min.css",
				array(),
				'3.0.3'
			);

			/**
			 * JQuery UI CSS
			 */
			wp_enqueue_style(
				'jquery-ui',
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				apply_filters(
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
					"redux/page/{$core->args['opt_name']}/enqueue/jquery-ui-css",
					Redux_Core::$url . 'assets/css/vendor/jquery-ui-1.10.0.custom.css'
				),
				array(),
				$this->timestamp
			);

			/**
			 * Iris CSS
			 */
			wp_enqueue_style( 'wp-color-picker' );

			if ( $core->args['dev_mode'] ) {

				/**
				 * Media CSS
				 */
				wp_enqueue_style(
					'redux-field-media',
					Redux_Core::$url . 'assets/css/media.css',
					array(),
					$this->timestamp
				);
			}

			/**
			 * RTL CSS
			 */
			if ( is_rtl() ) {
				wp_enqueue_style(
					'redux-rtl',
					Redux_Core::$url . 'assets/css/rtl.css',
					array(),
					$this->timestamp
				);
			}
		}

		/**
		 * Register all core framework scripts.
		 */
		private function register_scripts() {
			// *****************************************************************
			// JQuery / JQuery UI JS
			// *****************************************************************
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-dialog' );

			/**
			 * Select2 Sortable JS
			 */
			wp_register_script(
				'redux-select2-sortable',
				Redux_Core::$url . 'assets/js/vendor/select2-sortable/redux.select2.sortable' . $this->min . '.js',
				array( 'jquery', 'jquery-ui-sortable' ),
				$this->timestamp,
				true
			);

			/**
			 * Select2
			 */
			wp_enqueue_script(
				'select2-js',
				Redux_Core::$url . 'assets/js/vendor/select2/select2' . $this->min . '.js`',
				array( 'jquery', 'redux-select2-sortable' ),
				'4.1.0',
				true
			);

			/**
			 * QTip JS
			 */
			wp_enqueue_script(
				'qtip',
				Redux_Core::$url . 'assets/js/vendor/qtip/qtip' . $this->min . '.js',
				array( 'jquery' ),
				'3.0.3',
				true
			);

			/**
			 * Iris alpha color picker
			 */
			if ( ! wp_script_is( 'redux-wp-color-picker-alpha' ) ) {
				wp_enqueue_style( 'wp-color-picker' );

				wp_register_script(
					'redux-wp-color-picker-alpha',
					Redux_Core::$url . 'assets/js/vendor/wp-color-picker-alpha/wp-color-picker-alpha' . $this->min . '.js',
					array( 'jquery', 'wp-color-picker' ),
					'3.0.0',
					true
				);
			}

			/**
			 * Block UI (used by Custom Fonts and Color Schemes).
			 */
			wp_register_script(
				'redux-block-ui',
				Redux_Core::$url . 'assets/js/vendor/block-ui/jquery.blockUI' . $this->min . '.js',
				array( 'jquery' ),
				'2.70.0',
				true
			);

			/**
			 * Spectrum JS
			 */
			wp_register_script(
				'redux-spectrum-js',
				Redux_Core::$url . 'assets/js/vendor/spectrum/redux-spectrum' . $this->min . '.js',
				array( 'jquery' ),
				'1.3.3',
				true
			);

			/**
			 * Vendor JS
			 */
			wp_register_script(
				'redux-vendor',
				Redux_Core::$url . 'assets/js/redux-vendors' . $this->min . '.js',
				array( 'jquery' ),
				$this->timestamp,
				true
			);

			/**
			 * Redux JS
			 */
			wp_register_script(
				'redux-js',
				Redux_Core::$url . 'assets/js/redux' . $this->min . '.js',
				array( 'jquery', 'redux-vendor' ),
				$this->timestamp,
				true
			);
		}

		/**
		 * Enqueue fields that are in use.
		 *
		 * @param object $core  ReduxFramework object.
		 * @param array  $field Field array.
		 */
		public function enqueue_field( $core, array $field ) {
			if ( isset( $field['type'] ) && 'callback' !== $field['type'] ) {
				$field_type = str_replace( '_', '-', $field['type'] );
				$core_path  = Redux_Core::$dir . "inc/fields/{$field['type']}/class-redux-$field_type.php";

				// Shim for v3 extension class names.
				if ( ! file_exists( $core_path ) ) {
					$core_path = Redux_Core::$dir . "inc/fields/{$field['type']}/field_{$field['type']}.php";
				}

				if ( Redux_Core::$pro_loaded ) {
					$pro_path = '';

					if ( class_exists( 'Redux_Pro' ) ) {
						$pro_path = Redux_Pro::$dir . "core/inc/fields/{$field['type']}/class-redux-$field_type.php";
					}

					if ( file_exists( $pro_path ) ) {
						$filter_path = $pro_path;
					} else {
						$filter_path = $core_path;
					}
				} else {
					$filter_path = $core_path;
				}

				/**
				 * Field class file
				 * filter 'redux/{opt_name}/field/class/{field.type}
				 *
				 * @param     string    $filter_path Field class file path
				 * @param     array     $field       Field config data
				 */
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$class_file = apply_filters(
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
					"redux/{$core->args['opt_name']}/field/class/{$field['type']}",
					$filter_path,
					$field
				);

				$field_classes = array( 'Redux_' . $field['type'], 'ReduxFramework_' . $field['type'] );

				if ( $class_file ) {
					$field_class = Redux_Functions::class_exists_ex( $field_classes );
					if ( false === $field_class ) {
						if ( file_exists( $class_file ) ) {
							require_once $class_file;

							$field_class = Redux_Functions::class_exists_ex( $field_classes );
						} else {
							return;
						}
					}

					if ( false !== $field_class && ( ( method_exists( $field_class, 'enqueue' ) ) || method_exists( $field_class, 'localize' ) ) ) {
						if ( ! isset( $core->options[ $field['id'] ] ) ) {
							$core->options[ $field['id'] ] = '';
						}

						$data = array(
							'field' => $field,
							'value' => $core->options[ $field['id'] ],
							'core'  => $core,
							'mode'  => 'enqueue',
						);

						Redux_Functions::load_pro_field( $data );

						$the_field = new $field_class( $field, $core->options[ $field['id'] ], $core );

						if ( Redux_Core::$pro_loaded ) {
							$field_filter = '';

							if ( class_exists( 'Redux_Pro' ) ) {
								$field_filter = Redux_Pro::$dir . 'core/inc/fields/' . $field['type'] . '/class-redux-pro-' . $field_type . '.php';
							}

							if ( file_exists( $field_filter ) ) {
								require_once $field_filter;

								$filter_class_name = 'Redux_Pro_' . $field['type'];

								if ( class_exists( $filter_class_name ) ) {
									$extend = new $filter_class_name( $field, $core->options[ $field['id'] ], $core );
									$extend->init( 'enqueue' );
								}
							}
						}

						// Move dev_mode check to a new if/then block.
						if ( ! wp_script_is( 'redux-field-' . $field_type ) && ( class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) ) {
							$the_field->enqueue();
						}

						if ( class_exists( $field_class ) && method_exists( $field_class, 'always_enqueue' ) ) {
							$the_field->always_enqueue();
						}

						if ( method_exists( $field_class, 'localize' ) ) {
							$the_field->localize( $field );

							if ( ! isset( $this->localize_data[ $field['type'] ] ) ) {
								$this->localize_data[ $field['type'] ] = array();
							}

							$localize_data = $the_field->localize( $field );

							$shims = array( 'repeater' );

							// phpcs:ignore WordPress.NamingConventions.ValidHookName
							$shims = apply_filters( 'redux/' . $core->args['opt_name'] . '/localize/shims', $shims );

							if ( is_array( $shims ) && in_array( $field['type'], $shims, true ) ) {
								$this->repeater_data[ $field['type'] ][ $field['id'] ] = $localize_data;
							}

							$this->localize_data[ $field['type'] ][ $field['id'] ] = $localize_data;
						}

						unset( $the_field );
					}
				}
			}
		}

		/**
		 * Enqueue field files.
		 *
		 * @param     object $core ReduxFramework object.
		 */
		private function enqueue_fields( $core ) {
			foreach ( $core->sections as $section ) {
				if ( isset( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						$this->enqueue_field( $core, $field );
					}
				}
			}
		}

		/**
		 * Build a localized array from field functions, if any.
		 *
		 * @param object $core ReduxFramework object.
		 * @param string $type Field type.
		 */
		private function build_local_array( $core, string $type ) {
			if ( isset( $core->transients['last_save_mode'] ) && ! empty( $core->transients['notices'][ $type ] ) ) {
				$the_total = 0;
				$messages  = array();

				foreach ( $core->transients['notices'][ $type ] as $msg ) {
					if ( is_array( $msg ) && ! empty( $msg ) ) {
						$messages[ $msg['section_id'] ][ $type ][] = $msg;

						if ( ! isset( $messages[ $msg['section_id'] ]['total'] ) ) {
							$messages[ $msg['section_id'] ]['total'] = 0;
						}

						++$messages[ $msg['section_id'] ]['total'];
						++$the_total;
					}
				}

				$this->localize_data[ $type ] = array(
					'total' => $the_total,
					"$type" => $messages,
				);

				unset( $core->transients['notices'][ $type ] );
			}
		}

		/**
		 * Compile panel errors and wearings for a localized array.
		 */
		public function get_warnings_and_errors_array() {
			$core = $this->core();

			$this->build_local_array( $core, 'errors' );
			$this->build_local_array( $core, 'warnings' );
			$this->build_local_array( $core, 'sanitize' );

			if ( empty( $core->transients['notices'] ) ) {
				if ( isset( $core->transients['notices'] ) ) {
					unset( $core->transients['notices'] );
				}
			}
		}

		/**
		 * Commit localized data to global array.
		 *
		 * @param     object $core ReduxFramework object.
		 */
		private function set_localized_data( $core ) {
			if ( ! empty( $core->args['last_tab'] ) ) {
				$this->localize_data['last_tab'] = $core->args['last_tab'];
			}

			$this->localize_data['font_weights'] = $this->args['font_weights'];

			$this->localize_data['required'] = $core->required;
			$this->repeater_data['fonts']    = $core->fonts;
			if ( ! isset( $this->repeater_data['opt_names'] ) ) {
				$this->repeater_data['opt_names'] = array();
			}
			$this->repeater_data['opt_names'][]    = $core->args['opt_name'];
			$this->repeater_data['folds']          = array();
			$this->localize_data['required_child'] = $core->required_child;
			$this->localize_data['fields']         = $core->fields;

			if ( isset( $core->font_groups['google'] ) ) {
				$this->repeater_data['googlefonts'] = $core->font_groups['google'];
			}

			if ( isset( $core->font_groups['std'] ) ) {
				$this->repeater_data['stdfonts'] = $core->font_groups['std'];
			}

			if ( isset( $core->font_groups['customfonts'] ) ) {
				$this->repeater_data['customfonts'] = $core->font_groups['customfonts'];
			}

			if ( isset( $core->font_groups['typekitfonts'] ) ) {
				$this->repeater_data['typekitfonts'] = $core->font_groups['typekitfonts'];
			}

			$this->localize_data['folds'] = $core->folds;

			// Make sure the children are all hidden properly.
			foreach ( $core->fields as $key => $value ) {
				if ( in_array( $key, $core->fields_hidden, true ) ) {
					foreach ( $value as $k => $v ) {
						if ( ! in_array( $k, $core->fields_hidden, true ) ) {
							$core->fields_hidden[] = $k;
							$core->folds[ $k ]     = 'hide';
						}
					}
				}
			}

			$this->localize_data['fields_hidden'] = $core->fields_hidden;
			$this->localize_data['options']       = $core->options;
			$this->localize_data['defaults']      = $core->options_defaults;

			/**
			 * Save pending string
			 * filter 'redux/{opt_name}/localize/save_pending
			 *
			 * @param string $msg Save_pending string
			 */
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$save_pending = apply_filters(
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
				"redux/{$core->args['opt_name']}/localize/save_pending",
				esc_html__(
					'You have changes that are not saved. Would you like to save them now?',
					'redux-framework'
				)
			);

			/**
			 * Reset all string
			 * filter 'redux/{opt_name}/localize/reset
			 *
			 * @param string $msg Reset all string.
			 */
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$reset_all = apply_filters(
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
				"redux/{$core->args['opt_name']}/localize/reset",
				esc_html__(
					'Are you sure? Resetting will lose all custom values.',
					'redux-framework'
				)
			);

			/**
			 * Reset section string
			 * filter 'redux/{opt_name}/localize/reset_section
			 *
			 * @param string $msg Reset section string.
			 */
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$reset_section = apply_filters(
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
				"redux/{$core->args['opt_name']}/localize/reset_section",
				esc_html__(
					'Are you sure? Resetting will lose all custom values in this section.',
					'redux-framework'
				)
			);

			/**
			 * Preset confirm string
			 * filter 'redux/{opt_name}/localize/preset
			 *
			 * @param string $msg Preset confirm string.
			 */
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$preset_confirm = apply_filters(
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
				"redux/{$core->args['opt_name']}/localize/preset",
				esc_html__(
					'Your current options will be replaced with the values of this preset. Would you like to proceed?',
					'redux-framework'
				)
			);

			/**
			 * Import confirm string
			 * filter 'redux/{opt_name}/localize/import
			 *
			 * @param string $msg Import confirm string.
			 */
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$import_confirm = apply_filters(
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
				"redux/{$core->args['opt_name']}/localize/import",
				esc_html__(
					'Your current options will be replaced with the values of this import. Would you like to proceed?',
					'redux-framework'
				)
			);

			global $pagenow;

			$this->localize_data['args'] = array(
				'dev_mode'               => $core->args['dev_mode'],
				'save_pending'           => $save_pending,
				'reset_confirm'          => $reset_all,
				'reset_section_confirm'  => $reset_section,
				'preset_confirm'         => $preset_confirm,
				'import_section_confirm' => $import_confirm,
				'please_wait'            => esc_html__( 'Please Wait', 'redux-framework' ),
				'opt_name'               => $core->args['opt_name'],
				'flyout_submenus'        => $core->args['flyout_submenus'] ?? false,
				'slug'                   => $core->args['page_slug'],
				'hints'                  => $core->args['hints'],
				'disable_save_warn'      => $core->args['disable_save_warn'],
				'class'                  => $core->args['class'],
				'ajax_save'              => $core->args['ajax_save'],
				'menu_search'            => $pagenow . '?page=' . $core->args['page_slug'] . '&tab=',
			);

			$this->localize_data['ajax'] = array(
				'console' => esc_html__(
					'There was an error saving. Here is the result of your action:',
					'redux-framework'
				),
				'alert'   => esc_html__(
					'There was a problem with your action. Please try again or reload the page.',
					'redux-framework'
				),
			);

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$this->localize_data = apply_filters( "redux/{$core->args['opt_name']}/localize", $this->localize_data );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$this->repeater_data = apply_filters( "redux/{$core->args['opt_name']}/repeater", $this->repeater_data );

			$this->get_warnings_and_errors_array();

			if ( ! isset( $core->repeater_data ) ) {
				$core->repeater_data = array();
			}
			$core->repeater_data = Redux_Functions_Ex::nested_wp_parse_args(
				$this->repeater_data,
				$core->repeater_data
			);

			if ( ! isset( $core->localize_data ) ) {
				$core->localize_data = array();
			}
			$core->localize_data = Redux_Functions_Ex::nested_wp_parse_args(
				$this->localize_data,
				$core->localize_data
			);

			// Shim for extension compatibility.
			if ( Redux::$extension_compatibility ) {
				$this->repeater_data = Redux_Functions_Ex::nested_wp_parse_args(
					$this->repeater_data,
					$core->localize_data
				);
			}

			wp_localize_script(
				'redux-js',
				'redux',
				$this->repeater_data
			);

			wp_localize_script(
				'redux-js',
				'redux_' . str_replace( '-', '_', $core->args['opt_name'] ),
				$this->localize_data
			);

			wp_enqueue_script( 'redux-js' ); // Enqueue the JS now.
		}
	}
}

if ( ! class_exists( 'reduxCoreEnqueue' ) ) {
	class_alias( 'Redux_Enqueue', 'reduxCoreEnqueue' );
}
