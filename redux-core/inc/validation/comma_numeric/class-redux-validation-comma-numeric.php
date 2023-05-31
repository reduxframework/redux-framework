<?php
/**
 * Numeric with comma validation
 *
 * @package     ReduxFramework/Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_Comma_Numeric', false ) ) {

	/**
	 * Class Redux_Validation_Comma_Numeric
	 */
	class Redux_Validation_Comma_Numeric extends Redux_Validate {

		/**
		 * Field Validation Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function validate() {

			$this->value = preg_replace( '/\s/', '', $this->value );
			$parts       = explode( ',', $this->value );

			if ( empty( $this->value ) || 0 === $this->value || 1 === count( $parts ) ) {
				return;
			}

			$this->field['msg'] = $this->field['msg'] ?? esc_html__( 'You must provide a comma separated list of numerical values for this option.', 'redux-framework' );

			if ( ! is_numeric( str_replace( ',', '', $this->value ) ) || false === strpos( $this->value, ',' ) ) {
				$this->value            = ( isset( $this->current ) ) ? $this->current : '';
				$this->field['current'] = $this->value;

				$this->error = $this->field;
			}
		}
	}
}
