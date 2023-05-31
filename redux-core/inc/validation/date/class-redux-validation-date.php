<?php
/**
 * Date validation
 *
 * @package     Redux Framework
 * @subpackage  Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_Date', false ) ) {

	/**
	 * Class Redux_Validation_Date
	 */
	class Redux_Validation_Date extends Redux_Validate {

		/**
		 * Field Validation Function.
		 * Takes the vars and validates them.
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function validate() {
			$this->field['msg'] = $this->field['msg'] ?? esc_html__( 'This field must be a valid date.', 'redux-framework' );

			$string = str_replace( '/', '', $this->value );

			if ( ! is_numeric( $string ) ) {
				$this->value            = ( isset( $this->current ) ) ? $this->current : '';
				$this->field['current'] = $this->value;
				$this->error            = $this->field;

				return;
			}

			if ( '/' !== $this->value[2] ) {
				$this->value            = ( isset( $this->current ) ) ? $this->current : '';
				$this->field['current'] = $this->value;
				$this->error            = $this->field;

				return;
			}

			if ( '/' !== $this->value[5] ) {
				$this->value            = ( isset( $this->current ) ) ? $this->current : '';
				$this->field['current'] = $this->value;
				$this->error            = $this->field;
			}
		}
	}
}
