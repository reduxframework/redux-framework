<?php
/**
 * Redux Core Class
 *
 * @class   Redux_Core
 * @version 4.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Core', false ) ) {

	/**
	 * Class Redux_Core
	 */
	class Redux_Core {

		/**
		 * Class instance.
		 *
		 * @var object
		 */
		public static $instance;

		/**
		 * Project version
		 *
		 * @var string
		 */
		public static $version;

		/**
		 * Project directory.
		 *
		 * @var string.
		 */
		public static $dir;

		/**
		 * Project URL.
		 *
		 * @var string.
		 */
		public static $url;

		/**
		 * Base directory path.
		 *
		 * @var string
		 */
		public static $redux_path;

		/**
		 * Absolute direction path to WordPress upload directory.
		 *
		 * @var null
		 */
		public static $upload_dir = null;

		/**
		 * Full URL to WordPress upload directory.
		 *
		 * @var string
		 */
		public static $upload_url = null;

		/**
		 * Set when Redux is run as a plugin.
		 *
		 * @var bool
		 */
		public static $is_plugin = true;

		/**
		 * Indicated in_theme or in_plugin.
		 *
		 * @var string
		 */
		public static $installed = '';

		/**
		 * Set when Redux is run as a plugin.
		 *
		 * @var bool
		 */
		public static $as_plugin = false;

		/**
		 * Set when Redux is embedded within a theme.
		 *
		 * @var bool
		 */
		public static $in_theme = false;

		/**
		 * Set when Redux Pro plugin is loaded and active.
		 *
		 * @var bool
		 */
		public static $pro_loaded = false;

		/**
		 * Pointer to updated google fonts array.
		 *
		 * @var array
		 */
		public static $google_fonts = array();

		/**
		 * List of files calling Redux.
		 *
		 * @var array
		 */
		public static $callers = array();

		/**
		 * Nonce.
		 *
		 * @var string
		 */
		public static $wp_nonce;

		/**
		 * Pointer to _SERVER global.
		 *
		 * @var null
		 */
		public static $server = null;

		/**
		 * Pointer to the third party fixes class.
		 *
		 * @var null
		 */
		public static $third_party_fixes = null;

		/**
		 * Redux Welcome screen object.
		 *
		 * @var null
		 */
		public static $welcome = null;

		/**
		 * Redux Appsero object.
		 *
		 * @var null
		 */
		public static $appsero = null;

		/**
		 * Redux Insights object.
		 *
		 * @var null
		 */
		public static $insights = null;

		/**
		 * Flag for Redux Template enabled status.
		 *
		 * @var bool
		 */
		public static $redux_templates_enabled = false;

		/**
		 * Flag for Extendify Template enabled status.
		 *
		 * @var bool
		 */
		public static $extendify_templates_enabled = true;

		/**
		 * Creates instance of class.
		 *
		 * @return Redux_Core
		 * @throws Exception Comment.
		 */
		public static function instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();

				self::$instance->includes();
				self::$instance->init();
				self::$instance->hooks();

				add_action( 'plugins_loaded', array( 'Redux_Core', 'plugins_loaded' ) );
			}

			return self::$instance;
		}

		/**
		 * Things to run after pluggable.php had loaded.
		 */
		public static function plugins_loaded() {
			Redux_Functions_Ex::pro_to_ext();
		}

		/**
		 * Class init.
		 */
		private function init() {

			self::$server = array(
				'SERVER_SOFTWARE' => '',
				'REMOTE_ADDR'     => Redux_Helpers::is_local_host() ? '127.0.0.1' : '',
				'HTTP_USER_AGENT' => '',
				'HTTP_HOST'       => '',
				'REQUEST_URI'     => '',
			);

			// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
			if ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
				self::$server['SERVER_SOFTWARE'] = sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) );
			}
			if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
				self::$server['REMOTE_ADDR'] = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
			}
			if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
				self::$server['HTTP_USER_AGENT'] = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
			}
			if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
				self::$server['HTTP_HOST'] = sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) );
			}
			if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
				self::$server['REQUEST_URI'] = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
			}

			// phpcs:enable

			self::$dir = trailingslashit( wp_normalize_path( dirname( realpath( __FILE__ ) ) ) );

			Redux_Functions_Ex::generator();

			if ( ! class_exists( 'ReduxAppsero\Client' ) ) {
				require_once Redux_Path::get_path( '/appsero/Client.php' );
			}

			if ( defined( 'REDUX_PLUGIN_FILE' ) ) {
				$client      = new ReduxAppsero\Client( 'f6b61361-757e-4600-bb0f-fe404ae9871b', 'Redux Framework', REDUX_PLUGIN_FILE );
				$plugin_info = Redux_Functions_Ex::is_inside_plugin( REDUX_PLUGIN_FILE );
			} else {
				$client = new ReduxAppsero\Client( 'f6b61361-757e-4600-bb0f-fe404ae9871b', 'Redux Framework', __FILE__ );
				// See if Redux is a plugin or not.

				$client->slug       = 'redux-framework';
				$client->textdomain = 'redux-framework';
				$client->version    = self::$version;
			}

			$plugin_info = Redux_Functions_Ex::is_inside_plugin( __FILE__ );

			if ( false !== $plugin_info ) {
				self::$installed = class_exists( 'Redux_Framework_Plugin' ) ? 'plugin' : 'in_plugin';
				self::$is_plugin = class_exists( 'Redux_Framework_Plugin' );
				self::$as_plugin = true;
				self::$url       = trailingslashit( dirname( $plugin_info['url'] ) );
				if ( isset( $plugin_info['slug'] ) && ! empty( $plugin_info['slug'] ) ) {
					$client->slug = $plugin_info['slug'];
				}
				$client->type = 'plugin';
			} else {
				$theme_info = Redux_Functions_Ex::is_inside_theme( __FILE__ );
				if ( false !== $theme_info ) {
					self::$url       = trailingslashit( dirname( $theme_info['url'] ) );
					self::$in_theme  = true;
					self::$installed = 'in_theme';
					if ( isset( $theme_info['slug'] ) && ! empty( $theme_info['slug'] ) ) {
						$client->slug = $theme_info['slug'];
					}
					$client->type = 'theme';
				}
			}

			self::$appsero = $client;

			// Activate insights.
			self::$insights = self::$appsero->insights();

			if ( class_exists( 'Redux_Pro' ) ) {
				self::$insights->add_extra(
					array(
						'pro'    => Redux_Pro::$version,
						'mokama' => Redux_Helpers::mokama(),
					)
				);
			}

			self::$insights->hide_notice()->init();
			if ( ! defined( 'REDUX_PLUGIN_FILE' ) ) {
				remove_action( 'admin_footer', array( self::$insights, 'deactivate_scripts' ) );
			}

			if ( ! self::$insights->tracking_allowed() ) {
				if ( ! self::$insights->notice_dismissed() ) {
					// Old tracking permissions.
					$tracking_options = get_option( 'redux-framework-tracking', array() );
					if ( ! empty( $tracking_options ) ) {
						if ( isset( $tracking_options['allow_tracking'] ) && 'yes' === $tracking_options['allow_tracking'] ) {
							Redux_Functions_Ex::set_activated();
						}
					}
				}
			}

			remove_action( 'redux_admin_notices_run', array( 'ReduxAppsero\Insights', 'admin_notice' ) );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			self::$url = apply_filters( 'redux/url', self::$url );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			self::$dir = apply_filters( 'redux/dir', self::$dir );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			self::$is_plugin = apply_filters( 'redux/is_plugin', self::$is_plugin );

			if ( ! function_exists( 'current_time' ) ) {
				require_once ABSPATH . '/wp-includes/functions.php';
			}

			$upload_dir       = wp_upload_dir();
			self::$upload_dir = $upload_dir['basedir'] . '/redux/';
			self::$upload_url = str_replace( array( 'https://', 'http://' ), '//', $upload_dir['baseurl'] . '/redux/' );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			self::$upload_dir = apply_filters( 'redux/upload_dir', self::$upload_dir );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			self::$upload_url = apply_filters( 'redux/upload_url', self::$upload_url );
		}

		/**
		 * Code to execute on framework __construct.
		 *
		 * @param object $parent Pointer to ReduxFramework object.
		 */
		public static function core_construct( $parent ) {
			self::$third_party_fixes = new Redux_ThirdParty_Fixes( $parent );

			Redux_ThemeCheck::get_instance();

		}

		/**
		 * Autoregister run.
		 *
		 * @throws Exception Comment.
		 */
		private function includes() {
			if ( class_exists( 'Redux_Pro' ) && isset( Redux_Pro::$dir ) ) {
				self::$pro_loaded = true;
			}

			require_once dirname( __FILE__ ) . '/inc/classes/class-redux-path.php';
			require_once dirname( __FILE__ ) . '/inc/classes/class-redux-functions-ex.php';
			require_once dirname( __FILE__ ) . '/inc/classes/class-redux-helpers.php';
			// require_once dirname( __FILE__ ) . '/inc/classes/class-redux-enable-gutenberg.php';
			require_once dirname( __FILE__ ) . '/inc/classes/class-redux-instances.php';
			Redux_Functions_Ex::register_class_path( 'Redux', dirname( __FILE__ ) . '/inc/classes' );
			Redux_Functions_Ex::register_class_path( 'Redux', dirname( __FILE__ ) . '/inc/welcome' );
			spl_autoload_register( array( $this, 'register_classes' ) );

			self::$welcome = new Redux_Welcome();
			new Redux_Rest_Api_Builder();

			add_action( 'admin_init', array( $this, 'admin_init' ) );

			add_filter( 'debug_information', array( $this, 'add_debug_info' ) );
			add_filter( 'redux/tracking/options', array( 'Redux_Helpers', 'redux_stats_additions' ) );
		}

		/**
		 * Add debug info for the WP Site Health screen.
		 *
		 * @param array $debug_info Debug data.
		 *
		 * @return array
		 * @noinspection PhpIncludeInspection
		 * @throws ReflectionException
		 */
		public function add_debug_info( array $debug_info ): array {

			// Get browser data.
			if ( ! class_exists( 'ReduxBrowser' ) ) {
				require_once self::$dir . 'inc/lib/browser.php';
			}

			$browser = new ReduxBrowser();

			$browser_data = array(
				'Agent'    => $browser->getUserAgent(),
				'Browser'  => $browser->getBrowser(),
				'Version'  => $browser->getVersion(),
				'Platform' => $browser->getPlatform(),
			);

			// Set Redux dir permission results to Site Health screen.
			$debug_info['wp-filesystem']['fields'][] = array(
				'label' => esc_html__( 'The Redux upload directory', 'redux-framework' ),
				'value' => wp_is_writable( self::$upload_dir ) ? 'Writable' : 'Not writable',
			);

			// Set Redux plugin results to Site Health screen.
			$debug_info['redux-framework'] = array(
				'label'       => esc_html__( 'Redux Framework', 'redux-framework' ),
				'description' => esc_html__( 'Debug information specific to Redux Framework.', 'redux-framework' ),
				'fields'      => array(
					'version'        => array(
						'label' => esc_html__( 'Version', 'redux-framework' ),
						'value' => self::$version,
					),
					'installation'   => array(
						'label' => esc_html__( 'Installation', 'redux-framework' ),
						'value' => self::$installed,
					),
					'data directory' => array(
						'label' => esc_html__( 'Data directory', 'redux-framework' ),
						'value' => self::$dir,
					),
					'browser'        => array(
						'label' => esc_html__( 'Browser', 'redux-framework' ),
						'value' => $browser_data,
					),
				),
			);

			$redux = Redux::all_instances();

			$extensions = array();

			if ( ! empty( $redux ) && is_array( $redux ) ) {
				foreach ( $redux as $inst => $data ) {
					Redux::init( $inst );

					$inst_name = ucwords( str_replace( array( '_', '-' ), ' ', $inst ) );
					$args      = $data->args;

					$ext = Redux::get_extensions( $inst );
					if ( ! empty( $ext ) && is_array( $ext ) ) {
						ksort( $ext );

						foreach ( $ext as $name => $arr ) {
							$ver = $arr['version'];

							$ex = esc_html( ucwords( str_replace( array( '_', '-' ), ' ', $name ) ) );

							$extensions[ $ex ] = esc_html( $ver );
						}
					}

					// Output Redux instances.
					$debug_info[ 'redux-instance-' . $inst ] = array(
						// translators: %s = Instance name.
						'label'       => sprintf( esc_html__( 'Redux Instance: %s', 'redux-framework' ), $inst_name ),
						// translators: %s = Instance name w/ HTML.
						'description' => sprintf( esc_html__( 'Debug information for the %s Redux instance.', 'redux-framework' ), '<code>' . $inst . '</code>' ),
						'fields'      => array(
							'opt_name'         => array(
								'label' => esc_html( 'opt_name' ),
								'value' => $args['opt_name'],
							),
							'global_variable'  => array(
								'label' => esc_html( 'global_variable' ),
								'value' => $args['global_variable'],
							),
							'dev_mode'         => array(
								'label' => esc_html( 'dev_mode' ),
								'value' => $args['dev_mode'] ? 'true' : 'false',
							),
							'ajax_save'        => array(
								'label' => esc_html( 'ajax_save' ),
								'value' => $args['ajax_save'] ? 'true' : 'false',
							),
							'page_slug'        => array(
								'label' => esc_html( 'page_slug' ),
								'value' => $args['page_slug'],
							),
							'page_permissions' => array(
								'label' => esc_html( 'page_permissions' ),
								'value' => $args['page_permissions'],
							),
							'menu_type'        => array(
								'label' => esc_html( 'menu_type' ),
								'value' => $args['menu_type'],
							),
							'page_parent'      => array(
								'label' => esc_html( 'page_parent' ),
								'value' => $args['page_parent'],
							),
							'compiler'         => array(
								'label' => esc_html( 'compiler' ),
								'value' => $args['compiler'] ? 'true' : 'false',
							),
							'output'           => array(
								'label' => esc_html( 'output' ),
								'value' => $args['output'] ? 'true' : 'false',
							),
							'output_tag'       => array(
								'label' => esc_html( 'output_tag' ),
								'value' => $args['output_tag'] ? 'true' : 'false',
							),
							'templates_path'   => array(
								'label' => esc_html( 'templates_path' ),
								'value' => $args['templates_path'],
							),
							'extensions'       => array(
								'label' => esc_html( 'extensions' ),
								'value' => $extensions,
							),
						),
					);
				}
			}

			return $debug_info;
		}

		/**
		 * Register callback for autoload.
		 *
		 * @param string $class_name name of class.
		 */
		public function register_classes( string $class_name ) {
			$class_name_test = self::strtolower( $class_name );

			if ( strpos( $class_name_test, 'redux' ) === false ) {
				return;
			}

			if ( ! class_exists( 'Redux_Functions_Ex' ) ) {
				require_once Redux_Path::get_path( '/inc/classes/class-redux-functions-ex.php' );
			}

			if ( ! class_exists( $class_name ) ) {
				// Backward compatibility for extensions sucks!
				if ( 'Redux_Instances' === $class_name ) {
					require_once Redux_Path::get_path( '/inc/classes/class-redux-instances.php' );
					require_once Redux_Path::get_path( '/inc/lib/redux-instances.php' );

					return;
				}

				// Load Redux APIs.
				if ( 'Redux' === $class_name ) {
					require_once Redux_Path::get_path( '/inc/classes/class-redux-api.php' );

					return;
				}

				// Redux extra theme checks.
				if ( 'Redux_ThemeCheck' === $class_name ) {
					require_once Redux_Path::get_path( '/inc/themecheck/class-redux-themecheck.php' );

					return;
				}

				if ( 'Redux_Welcome' === $class_name ) {
					require_once Redux_Path::get_path( '/inc/welcome/class-redux-welcome.php' );

					return;
				}

				$mappings = array(
					'ReduxFrameworkInstances'  => 'Redux_Instances',
					'reduxCorePanel'           => 'Redux_Panel',
					'reduxCoreEnqueue'         => 'Redux_Enqueue',
					'Redux_Abstract_Extension' => 'Redux_Extension_Abstract',
				);
				$alias    = false;
				if ( isset( $mappings[ $class_name ] ) ) {
					$alias      = $class_name;
					$class_name = $mappings[ $class_name ];
				}

				// Everything else.
				$file = 'class.' . $class_name_test . '.php';

				$class_path = Redux_Path::get_path( '/inc/classes/' . $file );

				if ( ! file_exists( $class_path ) ) {
					$class_file_name = str_replace( '_', '-', $class_name );
					$file            = 'class-' . $class_name_test . '.php';
					$class_path      = Redux_Path::get_path( '/inc/classes/' . $file );
				}

				if ( file_exists( $class_path ) && ! class_exists( $class_name ) ) {
					require_once $class_path;
				}
				if ( class_exists( $class_name ) && ! empty( $alias ) && ! class_exists( $alias ) ) {
					class_alias( $class_name, $alias );
				}
			}

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/core/includes', $this );
		}

		/**
		 * Hooks to run on instance creation.
		 */
		private function hooks() {
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/core/hooks', $this );
		}

		/**
		 * Display the connection banner.
		 */
		public function admin_init() {
			Redux_Connection_Banner::init();
		}

		/**
		 * Action to run on WordPress heartbeat.
		 *
		 * @return bool
		 */
		public static function is_heartbeat(): bool {
			// Disregard WP AJAX 'heartbeat'call.  Why waste resources?
			if ( isset( $_POST ) && isset( $_POST['_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_nonce'] ) ), 'heartbeat-nonce' ) ) {

				if ( isset( $_POST['action'] ) && 'heartbeat' === sanitize_text_field( wp_unslash( $_POST['action'] ) ) ) {

					// Hook, for purists.
					if ( has_action( 'redux/ajax/heartbeat' ) ) {
						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						do_action( 'redux/ajax/heartbeat' );
					}

					return true;
				}

				return false;
			}

			// Buh bye!
			return false;
		}

		/**
		 * Helper method to check for mb_strtolower or to use the standard strtolower.
		 *
		 * @param string|null $str String to make lowercase.
		 *
		 * @return string|null
		 */
		public static function strtolower( ?string $str ): string {
			if ( function_exists( 'mb_strtolower' ) && function_exists( 'mb_detect_encoding' ) ) {
				return mb_strtolower( $str, mb_detect_encoding( $str ) );
			} else {
				return strtolower( $str );
			}
		}
	}

	/*
	 * Backwards comparability alias
	 */
	class_alias( 'Redux_Core', 'redux-core' );
}
