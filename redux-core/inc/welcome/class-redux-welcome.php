<?php
/**
 * Redux Welcome Class
 *
 * @class Redux_Core
 * @version 4.0.0
 * @package Redux Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Redux_Welcome', false ) ) {

	/**
	 * Class Redux_Welcome
	 */
	class Redux_Welcome {

		/**
		 * Min capacity.
		 *
		 * @var string The capability users should have to view the page
		 */
		public $minimum_capability = 'manage_options';

		/**
		 * Display version.
		 *
		 * @var string
		 */
		public $display_version = '';

		/**
		 * Is loaded.
		 *
		 * @var bool
		 */
		public $redux_loaded = false;

		/**
		 * Get things started
		 *
		 * @since 1.4
		 */
		public function __construct() {
			// Load the welcome page even if a Redux panel isn't running.
			add_action( 'init', array( $this, 'init' ), 999 );
			//add_action( 'admin_init', array( $this, 'register_options' ) );
		}

		/**
		 * Class init.
		 */
		public function init() {
			if ( $this->redux_loaded ) {
				return;
			}

			$this->redux_loaded = true;
			add_action( 'admin_menu', array( $this, 'admin_menus' ) );

			if ( isset( $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				if ( 'redux-' === substr( sanitize_text_field( wp_unslash( $_GET['page'] ) ), 0, 6 ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$version               = explode( '.', Redux_Core::$version );
					$this->display_version = $version[0] . '.' . $version[1];
					add_filter( 'admin_footer_text', array( $this, 'change_wp_footer' ) );
					add_action( 'admin_head', array( $this, 'admin_head' ) );
				}
			}
		}

		/**
		 * Do Redirect.
		 */
		public function do_redirect() {
			if ( ! defined( 'WP_CLI' ) ) {
				wp_safe_redirect( esc_url( admin_url( add_query_arg( array( 'page' => 'redux-framework' ), 'options-general.php' ) ) ) );
				exit();
			}
		}

		/**
		 * Change Footer.
		 */
		public function change_wp_footer() {
			echo esc_html__( 'If you like', 'redux-framework' ) . ' <strong>Redux</strong> ' . esc_html__( 'please leave us a', 'redux-framework' ) . ' <a href="https://wordpress.org/support/view/plugin-reviews/redux-framework?filter=5#postform" target="_blank" class="redux-rating-link" data-rated="Thanks :)">&#9733;&#9733;&#9733;&#9733;&#9733;</a> ' . esc_html__( 'rating. A huge thank you in advance!', 'redux-framework' );
		}



		/**
		 * Register the Dashboard Pages which are later hidden but these pages
		 * are used to render the What's Redux pages.
		 *
		 * @access public
		 * @since  1.4
		 * @return void
		 */
		public function admin_menus() {
			$page = 'add_options_page';

			// About Page.
			$page( esc_html__( 'What is Redux Framework?', 'redux-framework' ), esc_html__( 'Redux', 'redux-framework' ), $this->minimum_capability, 'redux-framework', array( $this, 'about_screen' ) );

			// Templates Page.
			//if ( Redux_Core::$is_plugin ) {
			//	$page( esc_html__( 'Templates', 'redux-framework' ), esc_html__( 'Templates', 'redux-framework' ), $this->minimum_capability, 'redux-templates', array( $this, 'templates' ) );
			//}

			//remove_submenu_page( 'options-general.php', 'redux-templates' );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			//do_action( 'redux/pro/welcome/admin/menu', $page, $this );
		}

		/**
		 * Hide Individual Dashboard Pages
		 *
		 * @access public
		 * @since  1.4
		 * @return void
		 */
		public function admin_head() {
			?>
			<link
				rel='stylesheet' id='elusive-icons' <?php // phpcs:ignore WordPress.WP.EnqueuedResources ?>
				href='<?php echo esc_url( Redux_Core::$url ); ?>assets/css/vendor/elusive-icons.css'
				type='text/css' media='all'/>

			<link
				rel='stylesheet' id='redux-welcome-css' <?php // phpcs:ignore WordPress.WP.EnqueuedResources ?>
				href='<?php echo esc_url( Redux_Core::$url ); ?>inc/welcome/css/redux-welcome.css'
				type='text/css' media='all'/>

			<style>
				.redux-badge:before {
				<?php echo is_rtl() ? 'right' : 'left'; ?>: 0;
				}

				.about-wrap .redux-badge {
				<?php echo is_rtl() ? 'left' : 'right'; ?>: 0;
				}

				.about-wrap .feature-rest div {
					padding- <?php echo is_rtl() ? 'left' : 'right'; ?>: 100px;
				}

				.about-wrap .feature-rest div.last-feature {
					padding- <?php echo is_rtl() ? 'left' : 'right'; ?>: 0;
				}

				.about-wrap .feature-rest div.icon:before {
					margin: <?php echo is_rtl() ? '0 -100px 0 0' : '0 0 0 -100px'; ?>;
				}
			</style>
			<?php
		}

		/**
		 * Navigation tabs
		 *
		 * @access public
		 * @since  1.9
		 * @return void
		 */
		public function tabs() {
			$selected = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : 'redux-framework'; // phpcs:ignore WordPress.Security.NonceVerification
			$nonce    = wp_create_nonce( 'redux-support-hash' );

			?>
			<input type="hidden" id="redux_support_nonce" value="<?php echo esc_attr( $nonce ); ?>"/>
			<h2 class="nav-tab-wrapper">
				<a
					class="nav-tab <?php echo( 'redux-framework' === $selected ? 'nav-tab-active' : '' ); ?>"
					href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-framework' ), 'options-general.php' ) ) ); ?>">
					<?php esc_attr_e( 'What is Redux?', 'redux-framework' ); ?>
				</a>
			</h2>
			<?php
		}

		/**
		 * Render About Screen
		 *
		 * @access public
		 * @since  1.4
		 * @return void
		 */
		public function about_screen() {
			// Stupid hack for WordPress alerts and warnings.
			echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';

			require_once 'views/about.php';
		}

		/**
		 * Action.
		 */
		public function actions() {
			?>
			<p class="redux-actions">
				<a href="http://devs.redux.io/" class="docs button button-primary">Docs</a>
				<a
					href="https://wordpress.org/support/view/plugin-reviews/redux-framework?filter=5#postform"
					class="review-us button button-primary"
					target="_blank">Review Us</a>
				<a
					href="https://twitter.com/share"
					class="twitter-share-button"
					data-url="https://redux.io"
					data-text="Supercharge your WordPress experience with Redux.io, the world's most powerful and widely used WordPress interface builder."
					data-via="ReduxFramework" data-size="large" data-hashtags="Redux">Tweet</a>
				<?php
				$options = Redux_Helpers::get_plugin_options();
				$nonce   = wp_create_nonce( 'redux_framework_demo' );

				$query_args = array(
					'page'                   => 'redux-framework',
					'redux-framework-plugin' => 'demo',
					'nonce'                  => $nonce,
				);

				if ( $options['demo'] ) {
					?>
					<a
						href="<?php echo esc_url( admin_url( add_query_arg( $query_args, 'options-general.php' ) ) ); ?>"
						class=" button-text button-demo"><?php echo esc_html__( 'Disable Panel Demo', 'redux-framework' ); ?></a>
					<?php
				} else {
					?>
					<a
						href="<?php echo esc_url( admin_url( add_query_arg( $query_args, 'options-general.php' ) ) ); ?>"
						class=" button-text button-demo active"><?php echo esc_html__( 'Enable Panel Demo', 'redux-framework' ); ?></a>
					<?php
				}

				?>
				<script>
					!function( d, s, id ) {
						var js, fjs = d.getElementsByTagName( s )[0],
							p = /^http:/.test( d.location ) ? 'http' : 'https';
						if ( !d.getElementById( id ) ) {
							js = d.createElement( s );
							js.id = id;
							js.src = p + '://platform.twitter.com/widgets.js';
							fjs.parentNode.insertBefore( js, fjs );
						}
					}( document, 'script', 'twitter-wjs' );
				</script>
			</p>
			<?php
		}
	}
}
