<?php
/**
 * No HTML validation
 *
 * @package     Redux Framework
 * @subpackage  Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_No_Html', false ) ) {

	/**
	 * Class Redux_Validation_No_Html
	 */
	class Redux_Validation_No_Html extends Redux_Validate {

		/**
		 * Validate Function.
		 * Takes the vars and validates them
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function validate() {
			$this->field['msg'] = $this->field['msg'] ?? esc_html__( 'You must not enter any HTML in this field.  All HTML has been removed.', 'redux-framework' );

			$newvalue = wp_strip_all_tags( $this->value );

			if ( $this->value !== $newvalue ) {
				$this->field['current'] = $newvalue;
				$this->warning          = $this->field;
			}

			$this->value = $newvalue;
		}
	}
}
