<?php
/**
 * Redux Transients Class
 *
 * @class Redux_Transients
 * @version 4.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Transients', false ) ) {

	/**
	 * Class Redux_Transients
	 */
	class Redux_Transients extends Redux_Class {

		/**
		 * Get transients from database.
		 */
		public function get() {
			$core = $this->core();

			if ( empty( $core->transients ) ) {
				$core->transients = get_option( $core->args['opt_name'] . '-transients', array() );
			}
		}

		/**
		 * Set transients in database.
		 */
		public function set() {
			$core = $this->core();

			if ( ! isset( $core->transients ) || ! isset( $core->transients_check ) || $core->transients_check !== $core->transients ) {
				update_option( $core->args['opt_name'] . '-transients', $core->transients );
			}
		}
	}
}
