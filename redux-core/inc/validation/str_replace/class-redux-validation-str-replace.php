<?php
/**
 * Str Replace validation
 *
 * @package     Redux Framework
 * @subpackage  Validation
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation_Str_Replace', false ) ) {

	/**
	 * Class Redux_Validation_Str_Replace
	 */
	class Redux_Validation_Str_Replace extends Redux_Validate {

		/**
		 * Field Validate Function.
		 * Takes the vars and validates them
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function validate() {
			$this->value = str_replace( $this->field['str']['search'], $this->field['str']['replacement'], $this->value );

			$this->field['current'] = $this->value;
			$this->sanitize         = $this->field;
		}
	}
}
