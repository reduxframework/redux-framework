<?php
/**
 * Redux Validate Class
 *
 * @class Redux_Validate
 * @version 4.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validate', false ) ) {

	/**
	 * Class Redux_Validate
	 */
	abstract class Redux_Validate {

		/**
		 * Redux_Validate constructor.
		 *
		 * @param ReduxFramework $parent ReduxFramework pointer.
		 * @param array          $field Fields array.
		 * @param array          $value Values array.
		 * @param mixed          $current Current.
		 */
		public function __construct( ReduxFramework $parent, array $field, array $value, $current ) {
			$this->parent  = $parent;
			$this->field   = $field;
			$this->value   = $value;
			$this->current = $current;

			if ( isset( $this->field['validate_msg'] ) ) {
				$this->field['msg'] = $this->field['validate_msg'];

				unset( $this->field['validate_msg'] );
			}

			$this->validate();
		}

		/**
		 * Validate.
		 *
		 * @return mixed
		 */
		abstract public function validate();
	}
}
