<?php
/**
 * Media filter validation
 *
 * @package     Redux Framework
 * @subpackage  Validation
 * @version     4.5.19
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_Media_Filter', false ) ) {

	/**
	 * Class Redux_Validation_Media_Filter
	 */
	class Redux_Validation_Media_Filter extends Redux_Validate {

		/**
		 * Coerces non-numeric filter values back to the previously stored numeric value,
		 * or to 0 when no numeric value was stored.
		 *
		 * @since ReduxFramework 4.5.19
		 */
		public function validate() {
			if ( ! is_array( $this->value ) ) {
				// Scalar values (url, id, width, height, thumbnail) pass through untouched.
				return;
			}

			foreach ( $this->value as $filter => $data ) {
				if ( ! is_array( $data ) || ! array_key_exists( 'value', $data ) || is_numeric( $data['value'] ) ) {
					continue;
				}

				if ( is_array( $this->current ) && isset( $this->current[ $filter ]['value'] ) && is_numeric( $this->current[ $filter ]['value'] ) ) {
					$this->value[ $filter ]['value'] = $this->current[ $filter ]['value'];
				} else {
					$this->value[ $filter ]['value'] = 0;
				}

				$this->field['msg'] = $this->field['msg'] ?? esc_html__( 'Non-numeric filter values were reset on save.', 'redux-framework' );
				$this->warning      = $this->field;
			}
		}
	}
}
