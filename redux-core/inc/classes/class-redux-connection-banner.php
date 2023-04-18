<?php
/**
 * Redux Connection Banner Class
 *
 * @class Redux_Core
 * @version 4.0.0
 * @package Redux Framework
 */

// @codingStandardsIgnoreStart
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Connection_Banner', false ) ) {

	/**
	 * Class Redux_Connection_Banner
	 */
	class Redux_Connection_Banner {
		/**
		 * Plugin version, used for cache-busting of style and script file references.
		 *
		 * @since   1.0.0
		 * @var     string
		 */
		protected $version = '1.0.0';

		/**
		 * Singleton instance.
		 *
		 * @var Redux_Connection_Banner
		 **/
		private static $instance = null;

		/**
		 * Register option.
		 *
		 * @var string $register_option
		 */
		private $register_option = 'redux-connection-register';
		/**
		 * Dismiss option.
		 *
		 * @var string $dismiss_option
		 */
		private $dismiss_option = 'redux-connection-dismiss';

		/**
		 * Nonce slug.
		 *
		 * @var string $dismiss_options
		 */
		private $nonce = 'redux-connection-nonce';

		/**
		 * URLs.
		 *
		 * @var array $urls
		 */
		private $urls = array();

		/**
		 * Init function.
		 *
		 * @return Redux_Connection_Banner
		 */
		public static function init(): ?Redux_Connection_Banner {
			if ( is_null( self::$instance ) ) {
				self::$instance = new Redux_Connection_Banner();
			}

			return self::$instance;
		}

		/**
		 * Redux_Connection_Banner constructor.
		 *
		 * Since we call the Redux_Connection_Banner:init() method from the `Redux` class, and after
		 * the admin_init action fires, we know that the admin is initialized at this point.
		 */
		private function __construct() {
			$clean_get = $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $clean_get['_wpnonce'] ) && wp_verify_nonce( $clean_get['_wpnonce'], $this->nonce ) ) {
				if ( isset( $clean_get[ $this->register_option ] ) ) {
					Redux_Functions_Ex::set_activated();
					return;
				}
				if ( isset( $clean_get[ $this->dismiss_option ] ) ) {
					Redux_Functions_Ex::set_deactivated();
					update_option( 'redux-framework_extendify_plugin_notice', 'hide' );
					return;
				}
			}

			add_action( 'wp_ajax_redux_activation', array( $this, 'admin_ajax' ) ); // Executed when logged in.
			add_action( 'current_screen', array( $this, 'maybe_initialize_hooks' ) );
		}

		/**
		 * Get the URL for the current page to fallback if JS is broken.
		 *
		 * @param bool|string $location Used to determine the location of the banner for account creation.
		 * @since 4.1.21
		 * @return array
		 */
		private function get_urls( $location = true ): array {
			if ( ! empty( $this->urls ) ) {
				return $this->urls;
			}

			global $pagenow;

			$clean_get = $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $clean_get[ $this->register_option ] ) ) {
				unset( $clean_get[ $this->register_option ] );
			}
			if ( isset( $clean_get[ $this->dismiss_option ] ) ) {
				unset( $clean_get[ $this->dismiss_option ] );
			}
			$base_url = admin_url( add_query_arg( $clean_get, $pagenow ) );

			return array(
				'dismiss'  => wp_nonce_url( add_query_arg( $this->dismiss_option, true, $base_url ), $this->nonce ),
				'register' => wp_nonce_url( add_query_arg( $this->register_option, $location, $base_url ), $this->nonce ),
			);
		}

		/**
		 * AJAX callback for dismissing the notice.
		 */
		public function admin_ajax() {

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ) : '';

			if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, $this->nonce ) ) {
				die( __( 'Security check failed.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			if ( 'false' === $_REQUEST['activate'] ) {
				echo wp_json_encode(
					array(
						'type' => 'close',
						'msg'  => '',
					)
				);

				update_option( 'redux-framework_extendify_plugin_notice', 'hide' );

				die();
			}

			$res = $this->install_extendify();

			if ( true === $res ) {
				update_option( 'redux-framework_extendify_plugin_notice', 'hide' );
			}

			die();
		}

		/**
		 * Install and activate Extendify Plugin.
		 *
		 * @return bool
		 */
		private function install_extendify(): bool {
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/misc.php';
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			$api = plugins_api(
				'plugin_information',
				array(
					'slug'   => 'extendify',
					'fields' => array(
						'short_description' => false,
						'sections'          => false,
						'requires'          => false,
						'rating'            => false,
						'ratings'           => false,
						'downloaded'        => false,
						'last_updated'      => false,
						'added'             => false,
						'tags'              => false,
						'compatibility'     => false,
						'homepage'          => false,
						'donate_link'       => false,
					),
				)
			);

			$download_link = $api->download_link;

			if ( empty( $download_link ) ) {
				echo wp_json_encode(
					array(
						'type' => 'error',
						'msg'  => esc_html__( 'Error: Install URL for Extendify could not be located.', 'redux-framework' ),
					)
				);

				return false;
			}

			ob_start();

			$skin     = new Redux_Installer_Muter( array( 'api' => $api ) );
			$upgrader = new Plugin_Upgrader( $skin );
			$install  = $upgrader->install( $download_link );

			if ( ob_get_contents() ) {
				ob_end_clean();
			}

			if ( true !== $install ) {
				echo wp_json_encode(
					array(
						'type' => 'error',
						'msg'  => esc_html__( 'Install process for Extendify failed.', 'redux-framework' ),
					)
				);

				return false;
			}

			$plugin_dir = WP_PLUGIN_DIR . '/extendify/extendify.php';

			$activate = activate_plugin( $plugin_dir );

			if ( is_wp_error( $activate ) ) {
				echo wp_json_encode(
					array(
						'type' => 'error',
						'msg'  => esc_html__( 'Extendify activation failed.', 'redux-framework' ),
					)
				);
			}

			echo wp_json_encode(
				array(
					'type' => 'success',
					'msg'  => esc_html__( 'Extendify installed and activated.', 'redux-framework' ),
				)
			);

			return true;
		}

		/**
		 * AJAX callback for dismissing the notice.
		 *
		 * @param string|null $admin_body_class Class string.
		 *
		 * @return string
		 */
		public function admin_body_class( ?string $admin_body_class = '' ): string {
			$classes = explode( ' ', trim( $admin_body_class ) );

			$classes[] = false ? 'redux-connected' : 'redux-disconnected';

			$admin_body_class = implode( ' ', array_unique( $classes ) );
			return " $admin_body_class ";
		}

		/**
		 * Will initialize hooks to display the new (as of 4.4) connection banner if the current user can
		 * connect Redux, if Redux has not been deactivated, and if the current page is the plugins page.
		 *
		 * This method should not be called if the site is connected to WordPress.com or if the site is in development mode.
		 *
		 * @since 4.4.0
		 * @since 4.5.0 Made the new (as of 4.4) connection banner display to everyone by default.
		 * @since 5.3.0 Running another split test between 4.4 banner and a new one in 5.3.
		 * @since 7.2   B test was removed.
		 *
		 * @param $current_screen
		 */
		public function maybe_initialize_hooks( $current_screen ) {
			if ( Redux_Functions_Ex::is_plugin_installed( 'extendify' ) || 'hide' === get_option( 'redux-framework_extendify_plugin_notice', null ) ) {
				return;
			}

			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			// Don't show the connect notice anywhere but the plugins.php after activating
			if ( 'plugins' !== $current_screen->base && 'dashboard' !== $current_screen->base ) {
				add_action( 'admin_head', array( $this, 'admin_head' ) );

				return;
			}

			// Only show this notice when the plugin is installed.
			if ( class_exists( 'Redux_Framework_Plugin' ) && false === Redux_Framework_Plugin::$crash ) {
				add_action( 'admin_notices', array( $this, 'render_banner' ) );
				add_action( 'network_admin_notices', array( $this, 'network_connect_notice' ) );
				add_action( 'admin_head', array( $this, 'admin_head' ) );
				add_filter( 'admin_body_class', array( $this, 'admin_body_class' ), 20 );
			}
		}

		/**
		 * Hide Individual Dashboard Pages
		 *
		 * @access public
		 * @since  4.0.0
		 * @return void
		 */
		public function admin_head() {
			?>

			<link
				rel='stylesheet' id='redux-banner-css' <?php // phpcs:ignore WordPress.WP.EnqueuedResources ?>
				href='<?php echo esc_url( Redux_Core::$url ); ?>inc/welcome/css/redux-banner.css'
				type='text/css' media='all'/>
			<script
				id="redux-banner-admin-js"
				src='<?php echo esc_url( Redux_Core::$url ); ?>inc/welcome/js/redux-banner-admin.js'>
			</script>
			<?php
		}

		/**
		 * Renders the new connection banner as of 4.4.0.
		 *
		 * @since 7.2   Copy and visual elements reduced to show the new focus of Redux on Security and Performance.
		 * @since 4.4.0
		 */
		public function render_banner() {

			$urls = $this->get_urls( 'main_banner' );

			?>
			<div id="redux-connect-message" class="updated redux-banner-container" data-nonce="<?php echo wp_create_nonce( $this->nonce ); ?>">
				<!-- <div class="redux-banner-container-top-text">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect x="0" fill="none" width="24" height="24"/><g><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-2h2v2zm0-4h-2l-.5-6h3l-.5 6z"/></g></svg>
					<span>
						<strong><?php // esc_html_e( 'You’re almost done. Finish setting up the Gutenberg pattern and template library to unlock more amazing features.', 'redux-framework' ); ?></strong>
					</span>
				</div> -->
				<div class="redux-banner-inner-container">
					<a href="<?php echo esc_url( $urls['dismiss'] ); ?>" data-url="<?php echo admin_url( 'admin-ajax.php' ); ?>"
					   class="notice-dismiss redux-banner-svg-dismiss redux-connection-banner-action"
					   title="<?php esc_attr_e( 'Dismiss this notice', 'redux-framework' ); ?>" data-activate="false">
					</a>

					<div class="redux-banner-content-container">

						<!-- slide 1: intro -->
						<div class="redux-banner-slide redux-banner-slide-one redux-slide-is-active">

							<div class="redux-banner-content-icon redux-illo">
								<a href="<?php echo esc_url( 'https://redux.io/?utm_source=plugin&utm_medium=extendify-plugin&utm_campaign=redux_banner_logo' ); ?>" target="_blank"><img
										src="<?php echo esc_url( Redux_Core::$url ); ?>assets/img/logo-color.svg"
										class="redux-banner-content-logo"
										alt="
									<?php
										esc_attr_e(
											'Visit our website to learn more!',
											'redux-framework'
										);
										?>
									" height="auto"
									/></a>
							</div>

							<div class="redux-banner-slide-text">
								<h2><?php esc_html_e( 'Action needed to continue using the template library.', 'redux-framework' ); ?></h2>
								<p>
									<?php
									esc_html_e(
										"The Extendify Library of Patterns and Templates has been unbundled from the Redux Options Framework to give our users more control over their WordPress experience. To continue using the Extendify Library click Install & Activate below.",
										'redux_framework'
									);
									?>
								</p>
								<div class="redux-banner-button-container">
									<span class="redux-banner-tos-blurb"><?php // echo self::tos_blurb( 'plugin_dashboard' ); ?></span>
									<a href="<?php echo esc_url( $urls['register'] ); ?>" data-url="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-activate="main_banner"
									   class="button button-primary button-large redux-alt-connect-button redux-connection-banner-action">
										<?php esc_html_e( 'Install and Activate Extendify', 'redux-framework' ); ?>
									</a>
								</div>

							</div>
						</div> <!-- end slide 1 -->
					</div>
				</div>
			</div>
			<noscript><style>#redux-connect-message{display:none;}</style></noscript>
			<?php
		}

		/**
		 * Renders the legacy network connection banner.
		 */
		public function network_connect_notice() {
			?>
			<div id="message" class="updated Redux-message">
				<div class="squeezer">
					<h2>
						<?php
						echo wp_kses(
							__(
								'<strong>Redux is activated!</strong> Each site on your network must be connected individually by an admin on that site.',
								'Redux'
							),
							array( 'strong' => array() )
						);
						?>
					</h2>
				</div>
			</div>
			<noscript><style>#message{display:none;}</style></noscript>
			<?php
		}
	}
}

// @codingStandardsIgnoreEnd
