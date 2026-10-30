<?php
/**
 * CSS validation
 *
 * @package     Redux Framework
 * @subpackage  Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_Css', false ) ) {

	/**
	 * Class Redux_Validation_Css
	 */
	class Redux_Validation_Css extends Redux_Validate {

		/**
		 * Field Validation Function.
		 * Takes the vars and validates them
		 *
		 * @since ReduxFramework 3.0.0
		 */
		public function validate() {
			$this->field['msg'] = $this->field['msg'] ?? esc_html__( 'Unsafe strings were found in your CSS and have been filtered out.', 'redux-framework' );

			$data = $this->sanitize_css_value( $this->value );

			if ( $data !== $this->value ) {
				$this->field['current'] = $data;
				$this->warning          = $this->field;
			}

			$this->value = $data;
		}

		/**
		 * Sanitize a CSS value, recursing into arrays so nested field
		 * values (e.g. box_shadow's inset-shadow and drop-shadow fields) are sanitized as well.
		 *
		 * @param mixed $value Value to sanitize.
		 *
		 * @return mixed
		 */
		private function sanitize_css_value( $value ) {
			if ( is_array( $value ) ) {
				return array_map( array( $this, 'sanitize_css_value' ), $value );
			}

			if ( ! is_string( $value ) ) {
				return $value;
			}

			$value = wp_kses( $value, 'strip' );
			$value = str_replace( '&gt;', '>', $value );

			return $value;
		}
	}
}
