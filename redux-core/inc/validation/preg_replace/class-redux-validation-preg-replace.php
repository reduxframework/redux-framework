<?php
/**
 * Preg Replace validation
 *
 * @package     Redux Framework
 * @subpackage  Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_Preg_Replace', false ) ) {

	/**
	 * Class Redux_Validation_Preg_Replace
	 */
	class Redux_Validation_Preg_Replace extends Redux_Validate {

		/**
		 * Field Validate Function.
		 * Takes the vars and validates them
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function validate() {
			$that                   = $this;
			$this->value            = preg_replace( $this->field['preg']['pattern'], $that->field['preg']['replacement'], $this->value );
			$this->field['current'] = $this->value;

			$this->sanitize = $this->field;
		}
	}
}
