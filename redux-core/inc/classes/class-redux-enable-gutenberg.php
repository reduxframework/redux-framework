<?php
/**
 * Redux Enable Gutenberg Class
 *
 * @class   Redux_Enable_Gutenberg
 * @version 4.0.0
 * @package Redux Framework
 * @author  Dovy Pauksts of Redux.io
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


if ( ! class_exists( 'Redux_Enable_Gutenberg', false ) ) {

	/**
	 * Main Feedback Notice Class
	 */
	class Redux_Enable_Gutenberg {

		/**
		 * Slug.
		 *
		 * @var string $slug
		 */
		private $slug;

		/**
		 * Name.
		 *
		 * @var string $name
		 */
		private $name;

		/**
		 * No Bug Option.
		 *
		 * @var string $nobug_option
		 */
		public $nobug_option;

		/**
		 * Auto Enable Option.
		 *
		 * @var string $autoenable_option
		 */
		public $autoenable_option;

		/**
		 * Auto deactivate Option.
		 *
		 * @var string $decativate_option
		 */
		public $decativate_option;

		/**
		 * Nonce string.
		 *
		 * @var string $nonce
		 */
		public $nonce;

		/**
		 * Disabled by the theme.
		 *
		 * @var bool
		 */
		protected static $theme_disabled = false;

		/**
		 * Disabled at all.
		 *
		 * @var bool
		 */
		public static $is_disabled = false;

		/**
		 * Quick fix known plugins that disable.
		 *
		 * @var array
		 */
		protected static $known_plugins = array();

		/**
		 * Class constructor.
		 *
		 * @param array $args Arguments.
		 */
		public function __construct( $args = array() ) {
			global $pagenow;

			$defaults = array(
				'slug' => '',
				'name' => '',
			);
			$args     = wp_parse_args( $args, $defaults );

			if ( empty( $args['slug'] ) ) {
				echo 'You must pass a slug to the Redux_Enable_Gutenberg() constructor.';

				return;
			}

			if ( strpos( $args['slug'], 'gutenberg' ) === false ) {
				$args['slug'] .= '-gutenberg';
			}
			$this->slug              = $args['slug'];
			$this->name              = $args['name'];
			$this->nobug_option      = $this->slug . '-no-bug';
			$this->nonce             = $this->slug . '-nonce';
			$this->autoenable_option = $this->slug . '-force-enable';
			$this->decativate_option = $this->slug . '-deactivate-plugins';

			if ( is_admin() && ! self::$is_disabled && ( 'post-new.php' === $pagenow || 'post.php' === $pagenow ) && ! get_site_option( $this->nobug_option, false ) ) {
				// We only want to do this for posts or pages.
				if ( ! isset( $_GET['post_type'] ) || ( isset( $_GET['post_type'] ) && 'page' === $_GET['post_type'] ) ) { // phpcs:ignore
					add_action( 'init', array( $this, 'check_init' ), 998 );
					add_action( 'init', array( $this, 'run_user_actions' ), 999 );
					if ( ! get_site_option( $this->nobug_option, false ) ) {
						add_action( 'plugins_loaded', array( $this, 'check_plugin' ), 999 );
						add_action( 'after_setup_theme', array( $this, 'check_theme' ), 999 );
						add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
					}
				}
			}
		}

		/**
		 * Cleanup for plugin deactivation.
		 *
		 * @param string $slug Slug for instance.
		 */
		public static function cleanup_options( $slug = '' ) {
			if ( ! empty( $slug ) ) {
				$obj = new Redux_Enable_Gutenberg(
					array(
						'slug' => $slug,
						'name' => '',
					)
				);
				delete_site_option( $obj->autoenable_option );
				delete_site_option( $obj->nobug_option );
			}
		}

		/**
		 * Display the admin notice.
		 */
		public function display_admin_notice() {
			if ( ! self::$is_disabled ) {
				return;
			}
			global $pagenow;

			$clean_get = $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $clean_get[ $this->nobug_option ] ) ) {
				unset( $clean_get[ $this->nobug_option ] );
			}
			if ( isset( $clean_get[ $this->autoenable_option ] ) ) {
				unset( $clean_get[ $this->autoenable_option ] );
			}
			if ( isset( $clean_get[ $this->decativate_option ] ) ) {
				unset( $clean_get[ $this->decativate_option ] );
			}
			$base_url = admin_url( add_query_arg( $clean_get, $pagenow ) );

			$no_bug_url      = wp_nonce_url( add_query_arg( $this->nobug_option, true, $base_url ), $this->nonce );
			$auto_enable_url = wp_nonce_url( add_query_arg( $this->autoenable_option, true, $base_url ), $this->nonce );
			$deativate_url   = wp_nonce_url( add_query_arg( $this->decativate_option, true, $base_url ), $this->nonce );

			$data = array(
				'url'     => '',
				'content' => '',
				'header'  => __( 'Gutenberg is currently disabled!', 'redux-framework' ),
				'button'  => '',
			);

			if ( isset( $_GET[ $this->decativate_option ] ) || empty( self::$known_plugins ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

				if ( isset( $_GET[ $this->decativate_option ] ) ) {  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					// That didn't work.
					$data['header']  = __( 'Hmm, it seems something else is disabling Gutenberg...', 'redux-framework' );
					$data['content'] = sprintf( '<p>Well seems like we have more to do. Don\'t worry, we can still fix this! Click the <strong>Enable Gutenberg</strong> button and Redux will enable Gutenberg for you.</p>' );
				} elseif ( self::$theme_disabled ) {
					$data['header']   = __( 'Your theme author has disabled Gutenberg!', 'redux-framework' );
					$data['content'] .= sprintf( '<p>It looks like your theme has disabled Gutenberg. Don\'t panic though! Click the <strong>Enable Gutenberg</strong> button to the right and Redux will enable Gutenberg for you.</p>' );
				} else {
					$data['header']   = __( 'Looks like something has disabled Gutenberg?', 'redux-framework' );
					$data['content'] .= sprintf( '<p>Did you know that Gutenberg is disabled? If that\'s intended you can dismiss this notice, not what you intended? Click <strong>Enable Gutenberg</strong> and Redux will automatically fix this for you.</p>' );
				}

				$data['url']    = $auto_enable_url;
				$data['button'] = __( 'Enable Gutenberg', 'redux-framework' );

			} elseif ( empty( self::$known_plugins ) ) {
				// Disabled by the theme or other.
				$data['header'] = __( 'Your theme', 'redux-framework' );
				$data['url']    = $auto_enable_url;
				$data['button'] = __( 'Enable Gutenberg', 'redux-framework' );
			} else {
				// Disable Plugins!
				$all_plugins = get_plugins();

				$plugins = '';

				foreach ( self::$known_plugins as $slug ) {
					if ( isset( $all_plugins[ $slug ] ) ) {
						if ( ! empty( $plugins ) ) {
							$plugins .= ', ';
						}
						$plugins .= '<code>' . esc_html( $all_plugins[ $slug ]['Name'] ) . '</code>';
					}
				}

				$data['url'] = $deativate_url;
				if ( 1 === count( self::$known_plugins ) ) {
					$data['button']  = __( 'Disable Plugin', 'redux-framework' );
					$data['content'] = sprintf( '<p>The following plugin is preventing Gutenberg from working: %s. To automatically fix the issue click the <strong>Disable Plugin</strong> button on the right and Redux will enable it for you.</p>', $plugins, esc_url( 'https://kinsta.com/blog/gutenberg-wordpress-editor/' ) );
				} else {
					$data['button']  = __( 'Disable Plugins', 'redux-framework' );
					$data['content'] = sprintf( '<p>The following plugin is preventing Gutenberg from working: %s. To automatically fix the issue click the <strong>Disable Plugins</strong> button on the right and Redux will enable it for you.</p>', $plugins, esc_url( 'https://kinsta.com/blog/gutenberg-wordpress-editor/' ) );
				}
			}

			?>
			<style>
				.notice.redux-notice {
					border-left-color: #24b0a6 !important;
					padding: 20px;
				}

				.rtl .notice.redux-notice {
					border-right-color: #19837c !important;
				}

				.notice.notice.redux-notice .redux-notice-inner {
					display: table;
					width: 100%;
				}

				.notice.redux-notice .redux-notice-inner .redux-notice-icon,
				.notice.redux-notice .redux-notice-inner .redux-notice-content,
				.notice.redux-notice .redux-notice-inner .redux-install-now {
					display: table-cell;
					vertical-align: middle;
				}

				.notice.redux-notice .redux-notice-icon {
					color: #509ed2;
					font-size: 13px;
					width: 60px;
				}

				.notice.redux-notice .redux-notice-icon img {
					width: 64px;
				}

				.notice.redux-notice .redux-notice-content {
					padding: 0 40px 0 20px;
				}

				.notice.redux-notice p {
					padding: 0;
					margin: 0;
				}

				.notice.redux-notice h3 {
					margin: 0 0 5px;
				}

				.notice.redux-notice .redux-install-now {
					text-align: center;
					width: 20%;
				}

				.notice.redux-notice .redux-install-now .redux-install-button {
					padding: 6px 50px;
					height: auto;
					line-height: 20px;
					background: #24b0a6;
					border-color: transparent;
					font-weight: bold;
				}

				.notice.redux-notice .redux-install-now .redux-install-button:hover {
					background: #19837c;
				}

				.notice.redux-notice a.no-thanks {
					display: block;
					margin-top: 10px;
					color: #72777c;
					text-decoration: none;
				}

				.notice.redux-notice a.no-thanks:hover {
					color: #444;
				}

				@media (max-width: 767px) {

					.notice.notice.redux-notice .redux-notice-inner {
						display: block;
					}

					.notice.redux-notice {
						padding: 20px !important;
					}

					.notice.redux-noticee .redux-notice-inner {
						display: block;
					}

					.notice.redux-notice .redux-notice-inner .redux-notice-content {
						display: block;
						padding: 0;
					}

					.notice.redux-notice .redux-notice-inner .redux-notice-icon {
						display: none;
					}

					.notice.redux-notice .redux-notice-inner .redux-install-now {
						margin-top: 20px;
						display: block;
						text-align: left;
					}

					.notice.redux-notice .redux-notice-inner .no-thanks {
						display: inline-block;
						margin-left: 15px;
					}
				}
			</style>
			<div class="notice updated redux-notice">
				<div class="redux-notice-inner">
					<div class="redux-notice-icon">
						<?php /* translators: 1. Name */ ?>
						<img src="<?php echo esc_url( Redux_Core::$url . '/assets/img/icon--color.svg' ); ?>" alt="<?php echo esc_attr__( 'Redux WordPress Plugin', 'redux-framework' ); ?>"/>
					</div>
					<div class="redux-notice-content">
						<?php /* translators: 1. Name */ ?>
						<h3><?php printf( esc_html( $data['header'] ) ); ?></h3>
						<?php printf( $data['content'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="redux-install-now">
						<?php printf( '<a href="%1$s" class="button button-primary redux-install-button">%2$s</a>', esc_url( $data['url'] ), esc_html( $data['button'] ) ); ?>
						<a href="<?php echo esc_url( $no_bug_url ); ?>" class="no-thanks"><?php echo esc_html__( 'No Thanks', 'redux-framework' ); ?></a>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Set the plugin to no longer bug users if user asks not to be.
		 */
		public function run_user_actions() {
			// Bail out if not on correct page.
			// phpcs:ignore
			if ( ! isset( $_GET['_wpnonce'] ) || ( ! wp_verify_nonce( $_GET['_wpnonce'], $this->nonce ) || ! is_admin() || ! current_user_can( 'manage_options' ) ) ) {
				return;
			}

			if ( isset( $_GET[ $this->nobug_option ] ) ) { // User doesn't want to see this anymore.
				add_site_option( $this->nobug_option, true );
			} elseif ( isset( $_GET[ $this->autoenable_option ] ) ) { // User has opted to just auto-enable Gutenberg.
				unset( $_GET[ $this->autoenable_option ] );
				add_site_option( $this->autoenable_option, true );
			} elseif ( isset( $_GET[ $this->decativate_option ] ) && ! empty( self::$known_plugins ) ) { // User has opted to disable known gutenberg plugins.
				deactivate_plugins( self::$known_plugins );
			}
			global $pagenow;
			unset( $_GET['_wpnonce'] );
			$url = admin_url( add_query_arg( $_GET, $pagenow ) );
			wp_safe_redirect( $url );

			exit();

		}

		/**
		 * Set the plugin to no longer bug users if user asks not to be.
		 */
		public function set_auto_disable() {

			// Bail out if not on correct page.
			// phpcs:ignore
			if ( ! isset( $_GET['_wpnonce'] ) || ( ! wp_verify_nonce( $_GET['_wpnonce'], $this->nonce ) || ! is_admin() || ! isset( $_GET[ $this->autoenable_option ] ) || ! current_user_can( 'manage_options' ) ) ) {
				return;
			}

			add_site_option( $this->autoenable_option, true );
		}

		/**
		 * Check for filter method.
		 */
		private function check_for_filter() {
			if ( version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' ) ) {
				if ( has_filter( 'use_block_editor_for_post_type', '__return_false' ) ) {
					return true;
				}
			} else {
				if ( has_filter( 'gutenberg_can_edit_post_type', '__return_false' ) ) {
					return true; // WP < 5 beta.
				}
			}

			return false;
		}

		/**
		 * Remove the Gutenberg disable filter for posts and pages only.
		 */
		private function remove_filter() {
			global $pagenow;

			if ( is_admin() && ( 'post-new.php' === $pagenow || 'post.php' === $pagenow ) ) {
				// We only want to do this for posts or pages.
				if ( ! isset( $_GET['post_type'] ) || ( isset( $_GET['post_type'] ) && 'page' === $_GET['post_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					if ( version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' ) ) {
						// WP > 5 beta.
						remove_filter( 'use_block_editor_for_post_type', '__return_false' );
					} else {
						// WP < 5 beta.
						remove_filter( 'gutenberg_can_edit_post_type', '__return_false' );
					}
				}
			}
		}

		/**
		 * Quick checks against known plugins for disabling.
		 */
		public function quick_checks() {
			// Testing for known plugins if they're loaded, save on filters and performance.
			if ( class_exists( 'Classic_Editor' ) ) {
				$a                     = new \ReflectionClass( 'Classic_Editor' );
				self::$known_plugins[] = plugin_basename( $a->getFileName() );
			}

			if ( defined( 'DISABLE_GUTENBERG_FILE' ) ) {
				self::$known_plugins[] = plugin_basename( DISABLE_GUTENBERG_FILE );
			}

			if ( defined( 'ADE_PLUGIN_DIR_PATH' ) ) {
				if ( class_exists( 'CodePopular_disable_gutenburg' ) ) {
					$a                     = new \ReflectionClass( 'CodePopular_disable_gutenburg' );
					self::$known_plugins[] = plugin_basename( $a->getFileName() );
				} else {
					self::$known_plugins[] = plugin_basename( ADE_PLUGIN_DIR_PATH ) . '/auto-disable-gutenberg.php';
				}
			}
			self::$known_plugins[] = 'no-gutenberg/no-gutenberg.php';
			self::$known_plugins[] = 'enable-classic-editor/enable-classic-editor.php';

			$plugins             = get_option( 'active_plugins' );
			$results             = array_intersect( $plugins, self::$known_plugins );
			self::$known_plugins = $results;
			if ( ! empty( self::$known_plugins ) ) {
				self::$is_disabled = true;
			}
		}

		/**
		 * Check if plugins have the disable filter.
		 */
		public function check_plugin() {
			$this->quick_checks();
			if ( ! self::$is_disabled && $this->check_for_filter() ) {
				self::$is_disabled = true;
			}
		}

		/**
		 * Check if the theme have the disable filter.
		 */
		public function check_theme() {
			if ( ! self::$is_disabled && $this->check_for_filter() ) {
				self::$theme_disabled = true;
				self::$is_disabled    = true;
			}
		}

		/**
		 * Check if init hook still has the disable filter.
		 */
		public function check_init() {
			if ( ! self::$is_disabled ) {
				if ( $this->check_for_filter() ) {
					self::$is_disabled = true;
				}
			}

			if ( self::$is_disabled && get_site_option( $this->autoenable_option, false ) ) {
				$this->remove_filter();
			}

		}
	}

	/*
	 * Instantiate the Redux_Enable_Gutenberg class.
	 */
	new Redux_Enable_Gutenberg(
		array(
			'slug' => 'redux-framework',
			'name' => __( 'Redux', 'redux-framework' ),
		)
	);

}
