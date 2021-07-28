<?php
/**
 * Redux Required Class
 *
 * @class Redux_Required
 * @version 4.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Required', false ) ) {

	/**
	 * Class Redux_Required
	 */
	class Redux_Required extends Redux_Class {

		/**
		 * Array of fields to reload.
		 *
		 * @var array
		 */
		public $reload_fields = array();

		/**
		 * Checks dependencies between objects based on the $field['required'] array
		 * If the array is set it needs to have exactly 3 entries.
		 * The first entry describes which field should be monitored by the current field. eg: "content"
		 * The second entry describes the comparison parameter. eg: "equals, not, is_larger, is_smaller ,contains"
		 * The third entry describes the value that we are comparing against.
		 * Example: if the required array is set to array('content','equals','Hello World'); then the current
		 * field will only be displayed if the field with id "content" has exactly the value "Hello World"
		 *
		 * @param array $field Field array.
		 */
		public function check_dependencies( array $field ) {
			$core = $this->core();

			if ( isset( $field['ajax_save'] ) && false === $field['ajax_save'] ) {
				$core->required_class->reload_fields[] = $field['id'];
			}

			if ( ! empty( $field['required'] ) ) {
				if ( ! isset( $core->required_child[ $field['id'] ] ) ) {
					$core->required_child[ $field['id'] ] = array();
				}

				if ( ! isset( $core->required[ $field['id'] ] ) ) {
					$core->required[ $field['id'] ] = array();
				}

				if ( is_array( $field['required'][0] ) ) {

					foreach ( $field['required'] as $value ) {
						if ( is_array( $value ) && 3 === count( $value ) ) {
							$data               = array();
							$data['parent']     = $value[0];
							$data['operation']  = $value[1];
							$data['checkValue'] = $value[2];

							$core->required[ $data['parent'] ][ $field['id'] ][] = $data;

							if ( ! in_array( $data['parent'], $core->required_child[ $field['id'] ], true ) ) {
								$core->required_child[ $field['id'] ][] = $data;
							}

							$this->check_required_dependencies( $core, $field, $data );
						}
					}
				} else {
					$data               = array();
					$data['parent']     = $field['required'][0];
					$data['operation']  = $field['required'][1];
					$data['checkValue'] = $field['required'][2];

					$core->required[ $data['parent'] ][ $field['id'] ][] = $data;

					if ( ! in_array( $data['parent'], $core->required_child[ $field['id'] ], true ) ) {
						$core->required_child[ $field['id'] ][] = $data;
					}

					$this->check_required_dependencies( $core, $field, $data );
				}
			}
		}

		/**
		 * Check field for require deps.
		 *
		 * @param object $core  ReduxFramework core pointer.
		 * @param array  $field Field array.
		 * @param array  $data  Required data.
		 */
		private function check_required_dependencies( $core, array $field, array $data ) {
			// required field must not be hidden. Otherwise, hide this one by default.
			if ( ! in_array( $data['parent'], $core->fields_hidden, true ) && ( ! isset( $core->folds[ $field['id'] ] ) || 'hide' !== $core->folds[ $field['id'] ] ) ) {
				if ( isset( $core->options[ $data['parent'] ] ) ) {
					$return = $this->compare_value_dependencies( $core->options[ $data['parent'] ], $data['checkValue'], $data['operation'] );
				}
			}

			if ( ( isset( $return ) && $return ) && ( ! isset( $core->folds[ $field['id'] ] ) || 'hide' !== $core->folds[ $field['id'] ] ) ) {
				$core->folds[ $field['id'] ] = 'show';
			} else {
				$core->folds[ $field['id'] ] = 'hide';

				if ( ! in_array( $field['id'], $core->fields_hidden, true ) ) {
					$core->fields_hidden[] = $field['id'];
				}
			}
		}

		/**
		 * Compare data for required field.
		 *
		 * @param mixed  $parent_value Parent value.
		 * @param mixed  $check_value  Check value.
		 * @param string $operation    Operation.
		 *
		 * @return bool
		 */
		public function compare_value_dependencies( $parent_value, $check_value, string $operation ): bool {
			$return = false;

			switch ( $operation ) {
				case '=':
				case 'equals':
					$data['operation'] = '=';

					if ( is_array( $parent_value ) ) {
						foreach ( $parent_value as $idx => $val ) {
							if ( is_array( $check_value ) ) {
								foreach ( $check_value as $i => $v ) {
									if ( Redux_Helpers::make_bool_str( $val ) === Redux_Helpers::make_bool_str( $v ) ) {
										$return = true;
									}
								}
							} else {
								if ( Redux_Helpers::make_bool_str( $val ) === Redux_Helpers::make_bool_str( $check_value ) ) {
									$return = true;
								}
							}
						}
					} else {
						if ( is_array( $check_value ) ) {
							foreach ( $check_value as $i => $v ) {
								if ( Redux_Helpers::make_bool_str( $parent_value ) === Redux_Helpers::make_bool_str( $v ) ) {
									$return = true;
								}
							}
						} else {
							if ( Redux_Helpers::make_bool_str( $parent_value ) === Redux_Helpers::make_bool_str( $check_value ) ) {
								$return = true;
							}
						}
					}
					break;

				case '!=':
				case 'not':
					$data['operation'] = '!==';
					if ( is_array( $parent_value ) ) {
						foreach ( $parent_value as $idx => $val ) {
							if ( is_array( $check_value ) ) {
								foreach ( $check_value as $i => $v ) {
									if ( Redux_Helpers::make_bool_str( $val ) !== Redux_Helpers::make_bool_str( $v ) ) {
										$return = true;
									}
								}
							} else {
								if ( Redux_Helpers::make_bool_str( $val ) !== Redux_Helpers::make_bool_str( $check_value ) ) {
									$return = true;
								}
							}
						}
					} else {
						if ( is_array( $check_value ) ) {
							foreach ( $check_value as $i => $v ) {
								if ( Redux_Helpers::make_bool_str( $parent_value ) !== Redux_Helpers::make_bool_str( $v ) ) {
									$return = true;
								}
							}
						} else {
							if ( Redux_Helpers::make_bool_str( $parent_value ) !== Redux_Helpers::make_bool_str( $check_value ) ) {
								$return = true;
							}
						}
					}

					break;
				case '>':
				case 'greater':
				case 'is_larger':
					$data['operation'] = '>';
					if ( $parent_value > $check_value ) {
						$return = true;
					}
					break;
				case '>=':
				case 'greater_equal':
				case 'is_larger_equal':
					$data['operation'] = '>=';
					if ( $parent_value >= $check_value ) {
						$return = true;
					}
					break;
				case '<':
				case 'less':
				case 'is_smaller':
					$data['operation'] = '<';
					if ( $parent_value < $check_value ) {
						$return = true;
					}
					break;
				case '<=':
				case 'less_equal':
				case 'is_smaller_equal':
					$data['operation'] = '<=';
					if ( $parent_value <= $check_value ) {
						$return = true;
					}
					break;
				case 'contains':
					if ( is_array( $parent_value ) ) {
						$parent_value = implode( ',', $parent_value );
					}

					if ( is_array( $check_value ) ) {
						foreach ( $check_value as $idx => $opt ) {
							if ( strpos( $parent_value, (string) $opt ) !== false ) {
								$return = true;
							}
						}
					} else {
						if ( strpos( $parent_value, (string) $check_value ) !== false ) {
							$return = true;
						}
					}

					break;
				case 'doesnt_contain':
				case 'not_contain':
					if ( is_array( $parent_value ) ) {
						$parent_value = implode( ',', $parent_value );
					}

					if ( is_array( $check_value ) ) {
						foreach ( $check_value as $idx => $opt ) {
							if ( strpos( $parent_value, (string) $opt ) === false ) {
								$return = true;
							}
						}
					} else {
						if ( strpos( $parent_value, (string) $check_value ) === false ) {
							$return = true;
						}
					}

					break;
				case 'is_empty_or':
					if ( empty( $parent_value ) || $check_value === $parent_value ) {
						$return = true;
					}
					break;
				case 'not_empty_and':
					if ( ! empty( $parent_value ) && $check_value !== $parent_value ) {
						$return = true;
					}
					break;
				case 'is_empty':
				case 'empty':
				case '!isset':
					if ( empty( $parent_value ) || '' === $parent_value ) {
						$return = true;
					}
					break;
				case 'not_empty':
				case '!empty':
				case 'isset':
					if ( ! empty( $parent_value ) && '' !== $parent_value ) {
						$return = true;
					}
					break;
			}

			return $return;
		}
	}
}
