<?php
/**
 * Not Empty validation
 *
 * @package     Redux Framework
 * @subpackage  Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_Not_Empty', false ) ) {

	/**
	 * Class Redux_Validation_Not_Empty
	 */
	class Redux_Validation_Not_Empty extends Redux_Validate {

		/**
		 * Field Validation Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function validate() {
			$this->field['msg'] = ( isset( $this->field['msg'] ) ) ? $this->field['msg'] : esc_html__( 'This field cannot be empty. Please provide a value.', 'redux-framework' );

			if ( ! isset( $this->value ) || '' === $this->value || 0 === strlen( $this->value ) ) {
				$this->error = $this->field;
			}
		}
	}
}
