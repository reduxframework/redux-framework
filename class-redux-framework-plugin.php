<?php
/**
 * Redux_Framework_Plugin main class
 *
 * @package     Redux Framework
 * @since       3.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Framework_Plugin', false ) ) {

	/**
	 * Main Redux_Framework_Plugin class
	 *
	 * @since       3.0.0
	 */
	class Redux_Framework_Plugin {

		/**
		 * Option array for demo mode.
		 *
		 * @access      protected
		 * @var         array $options Array of config options, used to check for demo mode
		 * @since       3.0.0
		 */
		protected $options = array();

		/**
		 * Use this value as the text domain when translating strings from this plugin. It should match
		 * the Text Domain field set in the plugin header, as well as the directory name of the plugin.
		 * Additionally, text domains should only contain letters, number and hyphens, not underscores
		 * or spaces.
		 *
		 * @access      protected
		 * @var         string $plugin_slug The unique ID (slug) of this plugin
		 * @since       3.0.0
		 */
		protected $plugin_slug = 'redux-framework';

		/**
		 * Set on network activate.
		 *
		 * @access      protected
		 * @var         string $plugin_network_activated Check for plugin network activation
		 * @since       3.0.0
		 */
		protected $plugin_network_activated = null;

		/**
		 * Class instance.
		 *
		 * @access      private
		 * @var         Redux_Framework_Plugin $instance The one true Redux_Framework_Plugin
		 * @since       3.0.0
		 */
		private static $instance;

		/**
		 * Crash flag.
		 *
		 * @access      private
		 * @var         Redux_Framework_Plugin $crash Crash flag if inside a crash.
		 * @since       4.1.15
		 */
		public static $crash = false;

		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       3.1.3
		 * @return      self::$instance The one true Redux_Framework_Plugin
		 */
		public static function instance(): ?Redux_Framework_Plugin {
			$path = REDUX_PLUGIN_FILE;
			$res  = false;

			if ( function_exists( 'get_plugin_data' ) && file_exists( $path ) ) {
				$data = get_plugin_data( $path );

				if ( isset( $data['Version'] ) && '' !== $data['Version'] ) {
					$res = version_compare( $data['Version'], '4', '<' );
				}

				if ( is_plugin_active( 'redux-framework/redux-framework.php' ) && true === $res ) {
					echo '<div class="error"><p>' . esc_html__( 'Redux Framework version 4 is activated but not loaded. Redux Framework version 3 is still installed and activated.  Please deactivate Redux Framework version 3.', 'redux-framework' ) . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
					return null;
				}
			}

			if ( ! self::$instance ) {
				self::$instance = new self();
				if ( class_exists( 'ReduxFramework' ) ) {
					self::$instance->load_first();
				} else {
					self::$instance->get_redux_options();
					self::$instance->includes();
					self::$instance->hooks();
				}
			}

			return self::$instance;
		}

		/**
		 * Shim for getting instance
		 *
		 * @access      public
		 * @since       4.0.1
		 * @return      self::$instance The one true Redux_Framework_Plugin
		 */
		public static function get_instance(): ?Redux_Framework_Plugin {
			return self::instance();
		}

		/**
		 * Get Redux options
		 *
		 * @access      public
		 * @since       3.1.3
		 * @return      void
		 */
		public function get_redux_options() {

			// Setup defaults.
			$defaults = array(
				'demo' => false,
			);

			// If multisite is enabled.
			if ( is_multisite() ) {

				// Get network activated plugins.
				$plugins = get_site_option( 'active_sitewide_plugins' );

				foreach ( $plugins as $file => $plugin ) {
					if ( strpos( $file, 'redux-framework.php' ) !== false ) {
						$this->plugin_network_activated = true;
						$this->options                  = get_site_option( 'ReduxFrameworkPlugin', $defaults );
					}
				}
			}

			// If options aren't set, grab them now!
			if ( empty( $this->options ) ) {
				$this->options = get_option( 'ReduxFrameworkPlugin', $defaults );
			}
		}

		/**
		 * Include necessary files
		 *
		 * @access      public
		 * @since       3.1.3
		 * @return      void
		 */
		public function includes() {

			// Include Redux_Core.
			if ( file_exists( __DIR__ . '/redux-core/framework.php' ) ) {
				require_once __DIR__ . '/redux-core/framework.php';
			}

			if ( file_exists( __DIR__ . '/redux-templates/redux-templates.php' ) ) {
				require_once __DIR__ . '/redux-templates/redux-templates.php';
			}

			if ( isset( Redux_Core::$as_plugin ) ) {
				Redux_Core::$as_plugin = true;
			}

			add_action( 'setup_theme', array( $this, 'load_sample_config' ) );
		}

		/**
		 * Loads the sample config after everything is loaded.
		 *
		 * @access      public
		 * @since       4.0.2
		 * @return      void
		 */
		public function load_sample_config() {
			// Include demo config, if demo mode is active.
			if ( $this->options['demo'] && file_exists( __DIR__ . '/sample/sample-config.php' ) ) {
				require_once __DIR__ . '/sample/sample-config.php';
			}
		}

		/**
		 * Run action and filter hooks
		 *
		 * @access      private
		 * @since       3.1.3
		 * @return      void
		 */
		private function hooks() {
			add_action( 'activated_plugin', array( $this, 'load_first' ) );
			add_action( 'wp_loaded', array( $this, 'options_toggle_check' ) );

			// Activate plugin when a new blog is added.
			add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

			// Display admin notices.
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );

			// Edit plugin metalinks.
			add_filter( 'plugin_row_meta', array( $this, 'plugin_metalinks' ), null, 2 );
			add_filter( 'network_admin_plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );
			add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/plugin/hooks', $this );
		}

		/**
		 * Pushes Redux to the top of plugin load list, so it initializes before any plugin that may use it.
		 */
		public function load_first() {
			if ( ! class_exists( 'Redux_Functions_Ex' ) ) {
				require_once __DIR__ . '/redux-core/inc/classes/class-redux-functions-ex.php';
			}

			$plugin_dir = Redux_Functions_Ex::wp_normalize_path( WP_PLUGIN_DIR ) . '/';
			$self_file  = Redux_Functions_Ex::wp_normalize_path( __FILE__ );

			$path = str_replace( $plugin_dir, '', $self_file );
			$path = str_replace( 'class-redux-framework-plugin.php', 'redux-framework.php', $path );

			$plugins = get_option( 'active_plugins' );

			if ( $plugins ) {
				$key = array_search( $path, $plugins, true );

				if ( false !== $key ) {
					array_splice( $plugins, $key, 1 );
					array_unshift( $plugins, $path );
					update_option( 'active_plugins', $plugins );
				}
			}
		}

		/**
		 * Fired on plugin activation
		 *
		 * @access      public
		 * @return      void
		 * @since       3.0.0
		 */
		public static function activate() {
			delete_site_transient( 'update_plugins' );
		}

		/**
		 * Fired when plugin is deactivated
		 *
		 * @access      public
		 * @since       3.0.0
		 *
		 * @param       boolean $network_wide True if plugin is network activated, false otherwise.
		 *
		 * @return      void
		 */
		public static function deactivate( ?bool $network_wide ) {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				if ( $network_wide ) {
					// Get all blog IDs.
					$blog_ids = self::get_blog_ids();

					foreach ( $blog_ids as $blog_id ) {
						switch_to_blog( $blog_id );
						self::single_deactivate();
					}
					restore_current_blog();
				} else {
					self::single_deactivate();
				}
			} else {
				self::single_deactivate();
			}

			delete_option( 'ReduxFrameworkPlugin' );
		}

		/**
		 * Fired when a new WPMU site is activated
		 *
		 * @access      public
		 *
		 * @param       int $blog_id The ID of the new blog.
		 *
		 * @return      void
		 * @since       3.0.0
		 */
		public function activate_new_site( int $blog_id ) {
			if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
				return;
			}

			switch_to_blog( $blog_id );
			self::single_activate();
			restore_current_blog();
		}

		/**
		 * Get all IDs of blogs that are not activated, not spam, and not deleted
		 *
		 * @access      private
		 * @since       3.0.0
		 * @global      object $wpdb
		 * @return      array|false Array of IDs or false if none are found
		 */
		private static function get_blog_ids() {
			global $wpdb;

			$var = '0';

			// Get an array of IDs (We have to do it this way because WordPress says so, however redundant).
			$result = wp_cache_get( 'redux-blog-ids' );
			if ( false === $result ) {

				// WordPress says get_col is discouraged?  I found no alternative.  So...ignore! - kp.
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$result = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM $wpdb->blogs WHERE archived = %s AND spam = %s AND deleted = %s", $var, $var, $var ) );

				wp_cache_set( 'redux-blog-ids', $result );
			}

			return $result;
		}

		/**
		 * Fired for each WPMS blog on plugin activation
		 *
		 * @access      private
		 * @since       3.0.0
		 * @return      void
		 */
		private static function single_activate() {
			$nonce = wp_create_nonce( 'redux_framework_demo' );

			$notices   = get_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES', array() );
			$notices[] = esc_html__( 'Redux Framework has an embedded demo.', 'redux-framework' ) . ' <a href="./plugins.php?redux-framework-plugin=demo&nonce=' . $nonce . '">' . esc_html__( 'Click here to activate the sample config file.', 'redux-framework' ) . '</a>';

			update_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES', $notices );
		}

		/**
		 * Display admin notices
		 *
		 * @access      public
		 * @since       3.0.0
		 * @return      void
		 */
		public function admin_notices() {
			do_action( 'redux_framework_plugin_admin_notice' );
			$notices = get_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES', '' );
			if ( ! empty( $notices ) ) {
				foreach ( $notices as $notice ) {
					echo '<div class="updated notice is-dismissible"><p>' . $notice . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
				}

				delete_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES' );
			}
		}

		/**
		 * Fired for each blog when the plugin is deactivated
		 *
		 * @access      private
		 * @since       3.0.0
		 * @return      void
		 */
		private static function single_deactivate() {
			delete_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES' );
		}

		/**
		 * Turn on or off
		 *
		 * @access      public
		 * @since       3.0.0
		 * @return      void
		 */
		public function options_toggle_check() {
			if ( isset( $_GET['nonce'] ) && wp_verify_nonce( sanitize_key( $_GET['nonce'] ), 'redux_framework_demo' ) ) {
				if ( isset( $_GET['redux-framework-plugin'] ) && 'demo' === $_GET['redux-framework-plugin'] ) {
					$url = admin_url( add_query_arg( array( 'page' => 'redux-framework' ), 'options-general.php' ) );

					if ( false === $this->options['demo'] ) {
						$this->options['demo'] = true;
						$url                   = admin_url( add_query_arg( array( 'page' => 'redux_demo' ), 'admin.php' ) );
					} else {
						$this->options['demo'] = false;
					}

					if ( is_multisite() && $this->plugin_network_activated ) {
						update_site_option( 'ReduxFrameworkPlugin', $this->options );
					} else {
						update_option( 'ReduxFrameworkPlugin', $this->options );
					}

					wp_safe_redirect( esc_url( $url ) );

					exit();
				}
			}
		}


		/**
		 * Add a settings link to the Redux entry in the plugin overview screen
		 *
		 * @param array  $links Links array.
		 * @param string $file  Plugin filename/slug.
		 *
		 * @return array
		 * @see   filter:plugin_action_links
		 * @since 1.0
		 */
		public function add_settings_link( array $links, string $file ): array {
			return $links;
		}

		/**
		 * Edit plugin metalinks
		 *
		 * @access      public
		 *
		 * @param array  $links The current array of links.
		 * @param string $file  A specific plugin row.
		 *
		 * @return      array The modified array of links
		 * @since       3.0.0
		 */
		public function plugin_metalinks( array $links, string $file ): array {
			if ( strpos( $file, 'redux-framework.php' ) !== false && is_plugin_active( $file ) ) {
				$links[] = '<a href="' . esc_url( admin_url( add_query_arg( array( 'page' => 'redux-framework' ), 'options-general.php' ) ) ) . '">' . esc_html__( 'What is this?', 'redux-framework' ) . '</a>';
			}

			return $links;
		}
	}
	if ( ! class_exists( 'ReduxFrameworkPlugin' ) ) {
		class_alias( 'Redux_Framework_Plugin', 'ReduxFrameworkPlugin' );
	}
}
