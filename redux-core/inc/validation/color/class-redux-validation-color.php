<?php
/**
 * Color Validation
 *
 * @package     Redux Framework/Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_Color', false ) ) {

	/**
	 * Class Redux_Validation_Color
	 */
	class Redux_Validation_Color extends Redux_Validate {

		/**
		 * Field Validate Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 3.0.0
		 */
		public function validate() {

			if ( empty( $this->value ) || ( 'transparent' === $this->value ) || is_array( $this->value ) ) {
				return;
			}

			$test = str_replace( '#', '', Redux_Core::strtolower( trim( $this->value ) ) );
			if ( ! in_array( strlen( $test ), array( 3, 6 ), true ) ) {
				// translators: %1$s: sanitized value.  %2$s: Old value.
				$this->field['msg'] = $this->field['msg'] ?? sprintf( esc_html__( 'Invalid HTML color code %1$s. Please enter a valid code. No value was saved.', 'redux-framework' ), '<code>' . $this->value . '</code>' );

				$this->warning = $this->field;
				$this->value   = '';

				return;
			} else {
				$this->field['msg'] = '';
				$this->warning      = $this->field;

			}

			$sanitized_value = Redux_Colors::sanitize_color( $this->value );

			if ( $sanitized_value !== $this->value ) {
				// translators: %1$s: sanitized value.  %2$s: Old value.
				$this->field['msg'] = $this->field['msg'] ?? sprintf( esc_html__( 'Sanitized value and saved as %1$s instead of %2$s.', 'redux-framework' ), '<code>' . $sanitized_value . '</code>', '<code>' . $this->value . '</code>' );

				$this->field['old']     = $this->value;
				$this->field['current'] = $sanitized_value;

				$this->warning = $this->field;
			}

			$this->value = strtoupper( $sanitized_value );
		}
	}
}
