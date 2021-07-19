<?php
/**
 * Redux Framework Admin Notice Class
 * Makes instantiating a Redux object an absolute piece of cake.
 *
 * @package     Redux_Framework
 * @author      Kevin Provance & Dovy Paukstys
 * @subpackage  Core
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Admin_Notices', false ) ) {

	/**
	 * Redux Admin Notices Class
	 *
	 * @since       3.0.0
	 */
	class Redux_Admin_Notices extends Redux_Class {

		/**
		 * WordPress admin notice array.
		 *
		 * @var array
		 * @access private
		 */
		private static $notices = array();

		/**
		 * Redux_Admin_Notices constructor.
		 *
		 * @param array $parent ReduxFramework object.
		 * @access public
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent );

			add_action( 'wp_ajax_redux_hide_admin_notice', array( $this, 'ajax' ) );
			add_action( 'admin_notices', array( $this, 'notices' ), 99 );
			add_action( 'admin_init', array( $this, 'dismiss' ), 9 );
		}

		/**
		 * Display nices stored in notices array.
		 *
		 * @access public
		 */
		public function notices() {
			$this->admin_notices( self::$notices );
		}

		/**
		 * Dismisses admin notice
		 *
		 * @access public
		 */
		public function dismiss() {
			$this->dismiss_admin_notice();
		}

		/**
		 * Sets an admin notice for display.
		 *
		 * @param array $data Notice data.
		 */
		public static function set_notice( array $data ) {
			$type    = null;
			$msg     = null;
			$id      = null;
			$dismiss = null;

			// phpcs:ignore WordPress.PHP.DontExtract
			extract( $data );

			self::$notices[ $parent->args['page_slug'] ][] = array(
				'type'    => $type,
				'msg'     => $msg,
				'id'      => $id . '_' . $parent->args['opt_name'],
				'dismiss' => $dismiss,
				'color'   => $color ?? '#00A2E3',
			);
		}

		/**
		 * Evaluates user dismiss option for displaying admin notices.
		 *
		 * @param array $notices Array of stored notices to display.
		 *
		 * @return      void
		 * @since       3.2.0
		 * @access      public
		 */
		public function admin_notices( array $notices = array() ) {
			global $current_user, $pagenow;

			$core = $this->core();
			if ( isset( $_GET ) && isset( $_GET['page'] ) && $core->args['page_slug'] === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification
				do_action( 'redux_admin_notices_run', $core->args );

				// Check for an active admin notice array.
				if ( ! empty( $notices ) ) {
					if ( isset( $notices[ $core->args['page_slug'] ] ) ) {
						// Enum admin notices.
						foreach ( $notices[ $core->args['page_slug'] ] as $notice ) {

							$add_style = '';
							if ( strpos( $notice['type'], 'redux-message' ) !== false ) {
								$add_style = 'style="border-left: 4px solid ' . esc_attr( $notice['color'] ) . '!important;"';
							}

							if ( true === $notice['dismiss'] ) {

								// Get user ID.
								$userid = $current_user->ID;

								if ( ! get_user_meta( $userid, 'ignore_' . $notice['id'] ) ) {
									global $wp_version;

									// Print the notice with the dismiss link.
									if ( version_compare( $wp_version, '4.2', '>' ) ) {
										$css_id    = esc_attr( $notice['id'] );
										$css_class = esc_attr( $notice['type'] ) . ' redux-notice notice is-dismissible redux-notice';

										$nonce = wp_create_nonce( $notice['id'] . $userid . 'nonce' );

										echo '<div ' . $add_style . ' id="' . esc_attr( $css_id ) . '" class="' . esc_attr( $css_class ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput
										echo '<input type="hidden" class="dismiss_data" id="' . esc_attr( $css_id ) . '" value="' . esc_attr( $nonce ) . '">';
										echo '<p>' . wp_kses_post( $notice['msg'] ) . '</p>';
										echo '</div>';
									} else {
										echo '<div ' . esc_html( $add_style ) . ' class="' . esc_attr( $notice['type'] ) . ' notice is-dismissable"><p>' . wp_kses_post( $notice['msg'] ) . '&nbsp;&nbsp;<a href="?dismiss=true&amp;id=' . esc_attr( $css_id ) . '">' . esc_html__( 'Dismiss', 'redux-framework' ) . '</a>.</p></div>';
									}
								}
							} else {
								// Standard notice.
								echo '<div ' . esc_html( $add_style ) . ' class="' . esc_attr( $notice['type'] ) . ' notice"><p>' . wp_kses_post( $notice['msg'] ) . '</a>.</p></div>';
							}
							?>
							<script>
								jQuery( document ).ready( function( $ ) {
									$( document.body ).on(
										'click', '.redux-notice.is-dismissible .notice-dismiss', function( e ) {
											e.preventDefault();
											var $data = $( this ).parent().find( '.dismiss_data' );
											$.post(
												ajaxurl, {
													action: 'redux_hide_admin_notice',
													id: $data.attr( 'id' ),
													nonce: $data.val()
												}
											);
										} );
								} );
							</script>
							<?php

						}
					}
				}
				// Clear the admin notice array.
				self::$notices[ $core->args['opt_name'] ] = array();
			}
		}

		/**
		 * Updates user meta to store dismiss notice preference.
		 *
		 * @since       3.2.0
		 * @access      private
		 * @return      void
		 */
		private function dismiss_admin_notice() {
			global $current_user;

			// Verify the dismiss and id parameters are present.
			if ( isset( $_GET['dismiss'] ) && isset( $_GET['id'] ) ) {
				if ( isset( $_GET['nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_GET['nonce'] ) ), 'redux_hint_toggle' ) ) {
					if ( 'true' === $_GET['dismiss'] || 'false' === $_GET['dismiss'] ) {

						// Get the user id.
						$userid = $current_user->ID;

						// Get the notice id.
						$id  = sanitize_text_field( wp_unslash( $_GET['id'] ) );
						$val = sanitize_text_field( wp_unslash( $_GET['dismiss'] ) );

						// Add the dismiss request to the user meta.
						update_user_meta( $userid, 'ignore_' . $id, $val );
					}
				} else {
					wp_nonce_ays( 'redux_hint_toggle' );
				}
			}
		}

		/**
		 * Updates user meta to store dismiss notice preference
		 *
		 * @since       3.2.0
		 * @access      public
		 * @return      void
		 */
		public function ajax() {
			global $current_user;

			if ( isset( $_POST['id'] ) ) {
				// Get the notice id.
				$id = explode( '&', sanitize_text_field( wp_unslash( $_POST['id'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
				$id = $id[0];

				// Get the user id.
				$userid = $current_user->ID;

				if ( ! isset( $_POST['nonce'] ) || ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), $id . $userid . 'nonce' ) ) ) {
					die( 0 );
				} else {
					// Add the dismiss request to the user meta.
					update_user_meta( $userid, 'ignore_' . $id, true );
				}
			}
		}
	}
}
