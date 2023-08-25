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
		 * ReduxFramework object.
		 *
		 * @var object
		 */
		public $parent;

		/**
		 * Redux fields array.
		 *
		 * @var array
		 */
		public $field = array();

		/**
		 * Redux values array|string.
		 *
		 * @var array|string
		 */
		public $value;

		/**
		 * Validation current value.
		 *
		 * @var mixed
		 */
		public $current;

		/**
		 * Warning array.
		 *
		 * @var array
		 */
		public $warning = array();

		/**
		 * Error array.
		 *
		 * @var array
		 */
		public $error = array();

		/**
		 * Sanitize array.
		 *
		 * @var array
		 */
		public $sanitize = array();

		/**
		 * Redux_Validate constructor.
		 *
		 * @param object       $redux ReduxFramework pointer.
		 * @param array        $field Fields array.
		 * @param array|string $value Values array.
		 * @param mixed        $current Current.
		 */
		public function __construct( $redux, array $field, $value, $current ) {
			$this->parent  = $redux;
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
