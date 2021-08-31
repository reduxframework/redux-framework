<?php
/**
 * Redux Framework Health Class
 *
 * @package     Redux_Framework/Classes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Health', false ) ) {

	/**
	 * Class Redux_Health
	 */
	class Redux_Health extends Redux_Class {

		/**
		 * Redux_Args constructor.
		 *
		 * @param     object $parent ReduxFramework object.
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent );

			add_action( 'wp_ajax_redux_submit_support_data', array( $this, 'ajax' ) );
		}

		/**
		 * AJAX
		 */
		public function ajax() {
			if ( isset( $_POST ) && isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'redux_sumbit_support' ) ) {
				$nonce = wp_remote_post(
					'http://127.0.0.1/redux-4/wp-admin/admin-ajax.php',
					array(
						'user-agent' => 'Redux v.' . Redux_Core::$version,
						'timeout'    => 300,
						'body'       => array(
							'action' => 'svl_support_create_nonce',
							'nonce'  => 'redux_support_token',
						),
					)
				);

				if ( is_wp_error( $nonce ) || empty( $nonce['body'] ) ) {
					echo wp_json_encode(
						array(
							'status' => 'error',
							'data'   => esc_html__( 'Security token', 'redux-framework' ) . ' ' . wp_remote_retrieve_response_code( $report ) . ': ' . wp_remote_retrieve_response_message( $report ),
						)
					);

					die();
				}

				if ( ! class_exists( 'WP_Debug_Data' ) ) {
					require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
				}

				WP_Debug_Data::check_for_updates();

				$info = WP_Debug_Data::debug_data();

				$report = wp_remote_post(
					'http://127.0.0.1/redux-4/wp-admin/admin-ajax.php',
					array(
						'user-agent' => 'Redux v.' . Redux_Core::$version,
						'timeout'    => 300,
						'body'       => array(
							'action'   => 'svl_support_create_report',
							'nonce'    => $nonce['body'],
							'data'     => wp_json_encode( $info ),
							'opt_name' => $this->parent->args['opt_name'],
							'product'  => 'Redux',
						),
					)
				);

				if ( ! is_wp_error( $report ) && 200 === wp_remote_retrieve_response_code( $report ) && ! empty( $report['body'] ) ) {
					$status = 'success';
					$data   = wp_remote_retrieve_body( $report );
				} else {
					$status = 'error';
					$data   = esc_html__( 'Data transmit', 'redux-framework' ) . ' ' . wp_remote_retrieve_response_code( $report ) . ': ' . wp_remote_retrieve_response_message( $report );
				}

				echo wp_json_encode(
					array(
						'status' => $status,
						'data'   => $data,
					)
				);
			}

			die();
		}
	}
}
