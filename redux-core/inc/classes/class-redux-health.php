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
		 * Returns entire arguments array.
		 *
		 * @var array|mixed
		 */
		public $get = array();

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
				if ( ! class_exists( 'WP_Debug_Data' ) ) {
					require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
				}

				WP_Debug_Data::check_for_updates();

				$info = WP_Debug_Data::debug_data();

				echo wp_json_encode(
					array(
						'status' => 'success',
						'action' => '',
					)
				);
			}

			die();
		}
	}
}
