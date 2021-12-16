<?php
/**
 * Redux Framework CDN Container Class
 *
 * @author      Kevin Provance (kprovance)
 * @package     Redux_Framework/Classes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_CDN', false ) ) {
	/**
	 * Class Redux_CDN
	 */
	class Redux_CDN {

		/**
		 * Pointer to ReduxFramework object.
		 *
		 * @var object
		 */
		public static $parent;

		/**
		 * Flag to check for set status.
		 *
		 * @var bool
		 */
		private static $set;

		/**
		 * Check for enqueued status of style/script.
		 *
		 * @param string $handle    File handle.
		 * @param string $list      Mode to check.
		 * @param bool   $is_script Flag for scrip/style.
		 *
		 * @return bool
		 */
		private static function is_enqueued( string $handle, string $list, bool $is_script = true ): bool {
			if ( $is_script ) {
				return wp_script_is( $handle, $list );
			} else {
				return wp_style_is( $handle, $list );
			}
		}

		/**
		 * Register script/style.
		 *
		 * @param string $handle File handle.
		 * @param string $src_cdn CDN source.
		 * @param array  $deps File deps.
		 * @param string $ver File version.
		 * @param mixed  $footer_or_media True or 'all'.
		 * @param bool   $is_script Script or style.
		 */
		private static function register( string $handle, string $src_cdn, array $deps, string $ver, $footer_or_media, bool $is_script = true ) {
			if ( $is_script ) {
				wp_register_script( $handle, $src_cdn, $deps, $ver, $footer_or_media );
			} else {
				wp_register_style( $handle, $src_cdn, $deps, $ver, $footer_or_media );
			}
		}

		/**
		 * Enqueue script or style.
		 *
		 * @param      string $handle File handle.
		 * @param      string $src_cdn CDN source.
		 * @param      array  $deps File deps.
		 * @param      string $ver File version.
		 * @param      mixed  $footer_or_media True or 'all'.
		 * @param      bool   $is_script Script or style.
		 */
		private static function enqueue( string $handle, string $src_cdn, array $deps, string $ver, $footer_or_media, bool $is_script = true ) {
			if ( $is_script ) {
				wp_enqueue_script( $handle, $src_cdn, $deps, $ver, $footer_or_media );
			} else {
				wp_enqueue_style( $handle, $src_cdn, $deps, $ver, $footer_or_media );
			}
		}

		/**
		 * Enqueue/Register CDN
		 *
		 * @param      bool   $register Register or enqueue.
		 * @param      string $handle File handle.
		 * @param      string $src_cdn CDN source.
		 * @param      array  $deps File deps.
		 * @param      string $ver File version.
		 * @param      mixed  $footer_or_media True or 'all'.
		 * @param      bool   $is_script Script or style.
		 */
		private static function cdn( bool $register, string $handle, string $src_cdn, array $deps, string $ver, $footer_or_media, bool $is_script ) {
			$tran_key = '_style_cdn_is_up';
			if ( $is_script ) {
				$tran_key = '_script_cdn_is_up';
			}

			$cdn_is_up = get_transient( $handle . $tran_key );
			if ( $cdn_is_up ) {
				if ( $register ) {
					self::register( $handle, $src_cdn, $deps, $ver, $footer_or_media, $is_script );
				} else {
					self::enqueue( $handle, $src_cdn, $deps, $ver, $footer_or_media, $is_script );
				}
			} else {

				$prefix = '/' === $src_cdn[1] ? 'http:' : '';

				// phpcs:ignore WordPress.PHP.NoSilencedErrors
				$cdn_response = @wp_remote_get( $prefix . $src_cdn );

				if ( is_wp_error( $cdn_response ) || 200 !== wp_remote_retrieve_response_code( $cdn_response ) ) {
					if ( class_exists( 'Redux_VendorURL' ) ) {
						$src = Redux_VendorURL::get_url( $handle );

						if ( $register ) {
							self::register( $handle, $src, $deps, $ver, $footer_or_media, $is_script );
						} else {
							self::enqueue( $handle, $src, $deps, $ver, $footer_or_media, $is_script );
						}
					} else {
						if ( ! self::is_enqueued( $handle, 'enqueued', $is_script ) ) {
							$msg = esc_html__( 'Please wait a few minutes, then try refreshing the page. Unable to load some remotely hosted scripts.', 'redux-framework' );
							if ( self::$parent->args['dev_mode'] ) {
								// translators: %s: URL.
								$msg = sprintf( esc_html__( 'If you are developing offline, please download and install the %s plugin/extension to bypass our CDN and avoid this warning', 'redux-framework' ), '<a href="https://github.com/reduxframework/redux-vendor-support" target="_blank">Redux Vendor Support</a>' );
							}

							// translators: %s: CDN handle.
							$msg = '<strong>' . esc_html__( 'Redux Framework Warning', 'redux-framework' ) . '</strong><br/>' . sprintf( esc_html__( '%s CDN unavailable.  Some controls may not render properly.', 'redux-framework' ), $handle ) . '  ' . $msg;

							$data = array(
								'parent'  => self::$parent,
								'type'    => 'error',
								'msg'     => $msg,
								'id'      => $handle . $tran_key,
								'dismiss' => false,
							);

							Redux_Admin_Notices::set_notice( $data );
						}
					}
				} else {
					set_transient( $handle . $tran_key, true, MINUTE_IN_SECONDS * self::$parent->args['cdn_check_time'] );

					if ( $register ) {
						self::register( $handle, $src_cdn, $deps, $ver, $footer_or_media, $is_script );
					} else {
						self::enqueue( $handle, $src_cdn, $deps, $ver, $footer_or_media, $is_script );
					}
				}
			}
		}

		/**
		 * Register/enqueue file from vendor library.
		 *
		 * @param      bool   $register Register or enqueue.
		 * @param      string $handle File handle.
		 * @param      string $src_cdn CDN source.
		 * @param      array  $deps File deps.
		 * @param      string $ver File version.
		 * @param      mixed  $footer_or_media True or 'all'.
		 * @param      bool   $is_script Script or style.
		 */
		private static function vendor_plugin( bool $register, string $handle, string $src_cdn, array $deps, string $ver, $footer_or_media, bool $is_script ) {
			if ( class_exists( 'Redux_VendorURL' ) ) {
				$src = Redux_VendorURL::get_url( $handle );

				if ( $register ) {
					self::register( $handle, $src, $deps, $ver, $footer_or_media, $is_script );
				} else {
					self::enqueue( $handle, $src, $deps, $ver, $footer_or_media, $is_script );
				}
			} else {
				if ( ! self::$set ) {
					// translators: %s: Vendor support URL. %s: Admin plugins page.
					$msg = sprintf( esc_html__( 'The %1$s (or extension) is either not installed or not activated and thus, some controls may not render properly.  Please ensure that it is installed and %2$s', 'redux-framework' ), '<a href="https://github.com/reduxframework/redux-vendor-support">Vendor Support plugin</a>', '<a href="' . admin_url( 'plugins.php' ) . '">' . esc_html__( 'activated.', 'redux-framework' ) . '</a>' );

					$data = array(
						'parent'  => self::$parent,
						'type'    => 'error',
						'msg'     => $msg,
						'id'      => $handle,
						'dismiss' => false,
					);

					Redux_Admin_Notices::set_notice( $data );

					self::$set = true;
				}
			}
		}

		/**
		 * Register style CDN or local.
		 *
		 * @param string $handle  File handle.
		 * @param string $src_cdn CDN source.
		 * @param array  $deps    File deps.
		 * @param string $ver     File version.
		 * @param string $media   True or 'all'.
		 */
		public static function register_style( string $handle, string $src_cdn, array $deps = array(), string $ver = '', string $media = 'all' ) {
			if ( empty( self::$parent ) || self::$parent->args['use_cdn'] ) {
				self::cdn( true, $handle, $src_cdn, $deps, $ver, $media, false );
			} else {
				self::vendor_plugin( true, $handle, $src_cdn, $deps, $ver, $media, false );
			}
		}

		/** Register script CDN or local.
		 *
		 * @param string $handle    File handle.
		 * @param string $src_cdn   CDN source.
		 * @param array  $deps      File deps.
		 * @param string $ver       File version.
		 * @param bool   $in_footer Script in footer.
		 */
		public static function register_script( string $handle, string $src_cdn, array $deps = array(), string $ver = '', bool $in_footer = false ) {
			if ( empty( self::$parent ) || self::$parent->args['use_cdn'] ) {
				self::cdn( true, $handle, $src_cdn, $deps, $ver, $in_footer, true );
			} else {
				self::vendor_plugin( true, $handle, $src_cdn, $deps, $ver, $in_footer, true );
			}
		}

		/**
		 * Enqueue style CDN or local.
		 *
		 * @param string $handle  File handle.
		 * @param string $src_cdn CDN source.
		 * @param array  $deps    File deps.
		 * @param string $ver     File version.
		 * @param string $media   Media type.
		 */
		public static function enqueue_style( string $handle, string $src_cdn, array $deps = array(), string $ver = '', string $media = 'all' ) {
			if ( self::$parent->args['use_cdn'] ) {
				self::cdn( false, $handle, $src_cdn, $deps, $ver, $media, false );
			} else {
				self::vendor_plugin( false, $handle, $src_cdn, $deps, $ver, $media, false );
			}
		}

		/**
		 * Enqueue script CDN or local.
		 *
		 * @param string $handle    File handle.
		 * @param string $src_cdn   CDN source.
		 * @param array  $deps      File seps.
		 * @param string $ver       File version.
		 * @param bool   $in_footer Script in footer.
		 */
		public static function enqueue_script( string $handle, string $src_cdn, array $deps = array(), string $ver = '', bool $in_footer = false ) {
			if ( self::$parent->args['use_cdn'] ) {
				self::cdn( false, $handle, $src_cdn, $deps, $ver, $in_footer, true );
			} else {
				self::vendor_plugin( false, $handle, $src_cdn, $deps, $ver, $in_footer, true );
			}
		}
	}
}
