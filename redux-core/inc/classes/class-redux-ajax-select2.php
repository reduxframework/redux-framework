<?php
/**
 * Redux Select2 AJAX Class
 *
 * @class   Redux_AJAX_Select2
 * @version 4.0.0
 * @package Redux Framework/Classes
 * @noinspection PhpConditionCheckedByNextConditionInspection
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_AJAX_Select2', false ) ) {

	/**
	 * Class Redux_AJAX_Select2
	 */
	class Redux_AJAX_Select2 extends Redux_Class {

		/**
		 * Redux_AJAX_Select2 constructor.
		 *
		 * @param object $redux ReduxFramework object pointer.
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux );
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			add_action( "wp_ajax_redux_{$redux->args['opt_name']}_select2", array( $this, 'ajax' ) );
		}

		/**
		 * AJAX callback for select2 match search.
		 */
		public function ajax() {
			$core = $this->core();

			if ( isset( $_REQUEST['nonce'] ) && isset( $_REQUEST['action'] ) ) {
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) ) ) {
					wp_send_json_error( esc_html__( 'Invalid security credential.  Please reload the page and try again.', 'redux-framework' ) );
				}

				if ( ! Redux_Helpers::current_user_can( $this->parent->args['page_permissions'] ) ) {
					wp_send_json_error( esc_html__( 'Invalid user capability.  Please reload the page and try again.', 'redux-framework' ) );
				}

				if ( isset( $_REQUEST['data'] ) ) {

					$args = isset( $_REQUEST['data_args'] ) ? json_decode( sanitize_text_field( wp_unslash( $_REQUEST['data_args'] ) ), true ) : array();
					$args = wp_parse_args(
						$args,
						array(
							's' => isset( $_REQUEST['q'] ) && ! empty( $_REQUEST['q'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['q'] ) ) : '',
						)
					);

					if ( isset( $_REQUEST['q'] ) && ! empty( $_REQUEST['q'] ) ) {
						$criteria  = sanitize_text_field( wp_unslash( $_REQUEST['q'] ) );
						$args['s'] = $criteria;
					}

					$return = $core->wordpress_data->get( sanitize_text_field( wp_unslash( $_REQUEST['data'] ) ), $args );

					if ( is_array( $return ) && ! empty( $_REQUEST['action'] ) ) {
						if ( ! empty( $args['s'] ) ) {
							$keys   = array_keys( $return );
							$values = array_values( $return );

							$to_json = array();

							// Search all the values.
							$search_values = preg_grep( '~' . $args['s'] . '~i', $values );
							if ( ! empty( $search_values ) ) {
								foreach ( $search_values as $id => $val ) {
									$to_json[ $keys[ $id ] ] = array(
										'id'   => $keys[ $id ],
										'text' => $val,
									);
								}
							}
							// Search all the keys.
							$search_keys = preg_grep( '~' . $args['s'] . '~i', $keys );
							if ( ! empty( $search_keys ) ) {
								foreach ( $search_keys as $id => $val ) {
									$to_json[ $val ] = array(
										'id'   => $val,
										'text' => $values[ $id ],
									);
								}
							}
							wp_send_json_success( array_values( $to_json ) );
						}
					}
				}
			}
		}
	}
}
