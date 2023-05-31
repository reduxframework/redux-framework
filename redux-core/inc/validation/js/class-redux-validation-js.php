<?php
/**
 * Javascript validation
 *
 * @package     Redux Framework
 * @subpackage  Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_Js', false ) ) {

	/**
	 * Class Redux_Validation_Js
	 */
	class Redux_Validation_Js extends Redux_Validate {

		/**
		 * Field Validation Function.
		 * Takes the vars and validates them
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function validate() {
			$this->field['msg'] = $this->field['msg'] ?? esc_html__( 'Javascript has been successfully escaped.', 'redux-framework' );

			$js = esc_js( $this->value );

			if ( $js !== $this->value ) {
				$this->field['current'] = $js;
				$this->warning          = $this->field;
			}

			$this->value = $js;
		}
	}
}
