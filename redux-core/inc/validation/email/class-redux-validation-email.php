<?php
/**
 * EMail validation
 *
 * @package     Redux Framework
 * @subpackage  Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_Email', false ) ) {

	/**
	 * Class Redux_Validation_Email
	 */
	class Redux_Validation_Email extends Redux_Validate {

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function validate() {
			$this->field['msg'] = $this->field['msg'] ?? esc_html__( 'You must provide a valid email for this option.', 'redux-framework' );

			if ( ! is_email( $this->value ) ) {
				$this->value            = ( isset( $this->current ) ) ? $this->current : '';
				$this->field['current'] = $this->value;

				$this->error = $this->field;
			}
		}
	}
}
