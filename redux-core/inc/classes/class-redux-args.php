<?php
/**
 * Redux Framework Args Class
 *
 * @package     Redux_Framework/Classes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Args', false ) ) {

	/**
	 * Class Redux_Args
	 */
	class Redux_Args {

		/**
		 * Returns entire arguments array.
		 *
		 * @var array|mixed
		 */
		public $get = array();

		/**
		 * ReduxFramework object.
		 *
		 * @var null
		 */
		private $parent = null;

		/**
		 * Switch to omit social icons if dev_mode is set to true and Redux defaults are used.
		 *
		 * @var bool
		 */
		public $omit_icons = false;

		/**
		 * Switch to omit support menu items if dev_mode is set to true and redux defaults are used.
		 *
		 * @var bool
		 */
		public $omit_items = false;

		/**
		 * Flag to force dev_mod to true if in localhost or WP_DEBUG is set to true.
		 *
		 * @var bool
		 */
		public $dev_mode_forced = false;

		/**
		 * Redux_Args constructor.
		 *
		 * @param     object $parent ReduxFramework object.
		 * @param     array  $args Global arguments array.
		 */
		public function __construct( $parent, $args ) {
			$this->parent = $parent;

			$default = array(
				'opt_name'                         => '',
				'last_tab'                         => '',
				'menu_icon'                        => '',
				'menu_title'                       => '',
				'page_title'                       => '',
				'page_slug'                        => '',
				'page_permissions'                 => 'manage_options',
				'menu_type'                        => 'menu',
				'page_parent'                      => 'themes.php',
				'page_priority'                    => null,
				'allow_sub_menu'                   => true,
				'save_defaults'                    => true,
				'footer_credit'                    => '',
				'async_typography'                 => false,
				'disable_google_fonts_link'        => false,
				'class'                            => '',
				'admin_bar'                        => true,
				'admin_bar_priority'               => 999,
				'admin_bar_icon'                   => '',
				'help_tabs'                        => array(),
				'help_sidebar'                     => '',
				'database'                         => '',
				'customizer'                       => false,
				'global_variable'                  => '',
				'output'                           => true,
				'output_variables_prefix'          => '--',
				'compiler_output_variables_prefix' => '$',
				'compiler'                         => true,
				'output_tag'                       => true,
				'output_location'                  => array( 'frontend' ),
				'transient_time'                   => '',
				'default_show'                     => false,
				'default_mark'                     => '',
				'disable_save_warn'                => false,
				'open_expanded'                    => false,
				'hide_expand'                      => false,
				'network_admin'                    => false,
				'network_sites'                    => true,
				'hide_reset'                       => false,
				'hide_save'                        => false,
				'hints'                            => array(
					'icon'          => 'el el-question-sign',
					'icon_position' => 'right',
					'icon_color'    => 'lightgray',
					'icon_size'     => 'normal',
					'tip_style'     => array(
						'color'   => 'light',
						'shadow'  => true,
						'rounded' => false,
						'style'   => '',
					),
					'tip_position'  => array(
						'my' => 'top_left',
						'at' => 'bottom_right',
					),
					'tip_effect'    => array(
						'show' => array(
							'effect'   => 'slide',
							'duration' => '500',
							'event'    => 'mouseover',
						),
						'hide' => array(
							'effect'   => 'fade',
							'duration' => '500',
							'event'    => 'click mouseleave',
						),
					),
				),
				'font_weights'                     => array(
					array(
						'id'   => '400',
						'name' => __( 'Regular 400', 'redux-framework' ),
					),
					array(
						'id'   => '400italic',
						'name' => __( 'Regular 400 Italic', 'redux-framework' ),
					),
					array(
						'id'   => '700',
						'name' => __( 'Bold 700', 'redux-framework' ),
					),
					array(
						'id'   => '700italic',
						'name' => __( 'Bold 700 Italic', 'redux-framework' ),
					),
				),
				'show_import_export'               => true,
				'show_options_object'              => true,
				'dev_mode'                         => true,
				'templates_path'                   => '',
				'ajax_save'                        => true,
				'use_cdn'                          => true,
				'cdn_check_time'                   => 1440,
				'options_api'                      => true,
				'allow_tracking'                   => true,
				'admin_theme'                      => 'wp',
				'elusive_frontend'                 => false,
				'pro'                              => array(),
				'font_display'                     => 'swap', // block|swap|fallback|optional.
			);

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$default = apply_filters( 'redux/pro/args/defaults', $default );

			$args = Redux_Functions::parse_args( $args, $default );

			$args = $this->args( $args );
			$args = $this->default_cleanup( $args );

			if ( ! in_array( $args['font_display'], array( 'block', 'swap', 'fallback', 'optional' ), true ) ) {
				$args['font_display'] = 'swap';
			}
			if ( $args['async_typography'] ) { // TODO: Disable this for now. We'll rip it out completely shortly.
				$args['async_typography'] = false;
			}

			$this->get = $args;

			$this->parent->args = $args;

			if ( 'redux_extensions_demo' !== $args['opt_name'] && 'redux_demo' !== $args['opt_name'] ) {
				$this->change_demo_defaults( $args );
			}
		}

		/**
		 * Builds and sanitizes global args array.
		 *
		 * @param     array $args Global args.
		 *
		 * @return mixed|void
		 */
		private function args( $args ) {
			$args = $this->no_errors_please( $args );

			$this->parent->old_opt_name = $args['opt_name'];

			$args = $this->filters( $args );

			if ( ! function_exists( 'wp_rand' ) ) {
				require_once ABSPATH . '/wp-includes/pluggable.php';
			}

			$this->parent->core_instance = chr( 64 + wp_rand( 1, 26 ) ) . time() . '_' . wp_rand( 0, 1000000 );
			$this->parent->core_thread   = chr( 64 + wp_rand( 1, 26 ) ) . time() . '_' . wp_rand( 0, 1000000 );

			if ( $args['opt_name'] === $this->parent->old_opt_name ) {
				$this->parent->old_opt_name = null;
				unset( $this->parent->old_opt_name );
			}

			// Do not save the defaults if we're on a live preview!
			if ( 'customize' === $GLOBALS['pagenow'] && isset( $_GET['customize_theme'] ) && ! empty( $_GET['customize_theme'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$args['save_defaults'] = false;
			}

			$args = $this->shim( $args );

			return $args;
		}

		/**
		 * Apply filters to arg data.
		 *
		 * @param     array $args Global args.
		 *
		 * @return mixed|void
		 */
		private function filters( $args ) {
			/**
			 * Filter 'redux/args/{opt_name}'
			 *
			 * @param     array     $args ReduxFramework configuration
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$args = apply_filters( "redux/args/{$args['opt_name']}", $args );

			/**
			 * Filter 'redux/options/{opt_name}/args'
			 *
			 * @param     array     $args ReduxFramework configuration
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$args = apply_filters( "redux/options/{$args['opt_name']}/args", $args );

			return $args;
		}

		/**
		 * Sanitize args that should not be empty.
		 *
		 * @param     array $args Global args.
		 *
		 * @return mixed
		 */
		private function no_errors_please( $args ) {
			if ( empty( $args['transient_time'] ) ) {
				$args['transient_time'] = 60 * MINUTE_IN_SECONDS;
			}

			if ( empty( $args['footer_credit'] ) ) {

				$footer_text = sprintf(
				/* translators: 1: Redux, 2: Link to plugin review */
					__( 'Enjoyed %1$s? Please leave us a %2$s rating. We really appreciate your support!', 'redux-framework' ),
					'<strong>' . __( 'Redux', 'redux-framework' ) . '</strong>',
					'<a href="https://wordpress.org/support/plugin/redux-framework/reviews/?filter=5/#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
				);
				$args['footer_credit'] = '<span id="footer-thankyou">' . $footer_text . '</span>';
			}

			if ( empty( $args['menu_title'] ) ) {
				$args['menu_title'] = esc_html__( 'Options', 'redux-framework' );
			}

			if ( empty( $args['page_title'] ) ) {
				$args['page_title'] = esc_html__( 'Options', 'redux-framework' );
			}

			// Auto create the page_slug appropriately.
			if ( empty( $args['page_slug'] ) ) {
				if ( ! empty( $args['display_name'] ) ) {
					$args['page_slug'] = sanitize_html_class( $args['display_name'] );
				} elseif ( ! empty( $args['page_title'] ) ) {
					$args['page_slug'] = sanitize_html_class( $args['page_title'] );
				} elseif ( ! empty( $args['menu_title'] ) ) {
					$args['page_slug'] = sanitize_html_class( $args['menu_title'] );
				} else {
					$args['page_slug'] = str_replace( '-', '_', $args['opt_name'] );
				}
			}

			return $args;
		}

		/**
		 * Shims for much older v3 configs.
		 *
		 * @param     array $args Global args.
		 *
		 * @return mixed
		 */
		private function shim( $args ) {
			/**
			 * SHIM SECTION
			 * Old variables and ways of doing things that need correcting.  ;)
			 * */
			// Variable name change.
			if ( ! empty( $args['page_cap'] ) ) {
				$args['page_permissions'] = $args['page_cap'];
				unset( $args['page_cap'] );
			}

			if ( ! empty( $args['page_position'] ) ) {
				$args['page_priority'] = $args['page_position'];
				unset( $args['page_position'] );
			}

			if ( ! empty( $args['page_type'] ) ) {
				$args['menu_type'] = $args['page_type'];
				unset( $args['page_type'] );
			}

			return $args;
		}

		/**
		 * Verify to see if dev has bothered to change admin bar links and share icons from demo data to their own.
		 *
		 * @param     array $args Global args.
		 */
		private function change_demo_defaults( $args ) {
			if ( $args['dev_mode'] || true === Redux_Helpers::is_local_host() ) {
				if ( ! empty( $args['admin_bar_links'] ) ) {
					foreach ( $args['admin_bar_links'] as $idx => $arr ) {
						if ( is_array( $arr ) && ! empty( $arr ) ) {
							foreach ( $arr as $x => $y ) {
								if ( strpos( Redux_Core::strtolower( $y ), 'redux' ) !== false ) {
									$msg = '<strong>' . esc_html__(
										'Redux Framework Notice',
										'redux-framework'
									) . ' </strong>' . esc_html__(
										'There are references to the Redux Framework support site in your config\'s ',
										'redux-framework'
									) . '<code>admin_bar_links</code> ' . esc_html__(
										'argument.  This is sample data.  Please change or remove this data before shipping your product.',
										'redux-framework'
									);

									$this->omit_items = true;
									continue;
								}
							}
						}
					}
				}

				if ( ! empty( $args['share_icons'] ) ) {
					foreach ( $args['share_icons'] as $idx => $arr ) {
						if ( is_array( $arr ) && ! empty( $arr ) ) {
							foreach ( $arr as $x => $y ) {
								if ( strpos( Redux_Core::strtolower( $y ), 'redux' ) !== false ) {
									$msg = '<strong>' . esc_html__(
										'Redux Framework Notice:',
										'redux-framework'
									) . '</strong>' . esc_html__(
										'There are references to the Redux Framework support site in your config\'s',
										'redux-framework'
									) . ' <code>share_icons</code> ' . esc_html__(
										'argument.  This is sample data.  Please change or remove this data before shipping your product.',
										'redux-framework'
									);

									$this->omit_icons = true;
								}
							}
						}
					}
				}
			}
		}

		/**
		 * Fix other arg criteria that sometimes gets hosed up.
		 *
		 * @param     array $args Global args.
		 *
		 * @return mixed
		 */
		private function default_cleanup( $args ) {

			// Fix the global variable name.
			if ( '' === $args['global_variable'] && false !== $args['global_variable'] ) {
				$args['global_variable'] = str_replace( '-', '_', $args['opt_name'] );
			}

			if ( isset( $args['customizer_only'] ) && $args['customizer_only'] ) {
				$args['menu_type']      = 'hidden';
				$args['customizer']     = true;
				$args['admin_bar']      = false;
				$args['allow_sub_menu'] = false;
			}

			// Check if the Airplane Mode plugin is installed.
			if ( class_exists( 'Airplane_Mode_Core' ) ) {
				$airplane = Airplane_Mode_Core::getInstance();
				if ( method_exists( $airplane, 'enabled' ) ) {
					if ( $airplane->enabled() ) {
						$args['use_cdn'] = false;
					}
				} elseif ( 'on' === $airplane->check_status() ) {
					$args['use_cdn'] = false;
				}
			}

			return $args;
		}
	}
}
