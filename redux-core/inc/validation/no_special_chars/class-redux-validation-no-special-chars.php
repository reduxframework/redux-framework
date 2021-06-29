<?php
/**
 * No Special Chars validation
 *
 * @package     Redux Framework
 * @subpackage  Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_No_Special_Chars', false ) ) {

	/**
	 * Class Redux_Validation_No_Special_Chars
	 */
	class Redux_Validation_No_Special_Chars extends Redux_Validate {

		/**
		 * Field Render Function.
		 * Takes the vars and validates them
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function validate() {
			$this->field['msg'] = ( isset( $this->field['msg'] ) ) ? $this->field['msg'] : esc_html__( 'You must not enter any special characters in this field, all special characters have been removed.', 'redux-framework' );

			if ( 0 === ! preg_match( '/[^a-zA-Z0-9_ -]/s', $this->value ) ) {
				$this->field['current'] = $this->current;

				$this->warning = $this->field;
			}

			$this->value = preg_replace( '/[^a-zA-Z0-9_ -]/s', '', $this->value );
		}
	}
}
