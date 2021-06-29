<?php
/**
 * Slider Field
 *
 * @package     Redux Framework/Fields
 * @subpackage  Field_Slider
 * @since       3.3
 * @author      Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Slider', false ) ) {

	/**
	 * Class Redux_Slider
	 */
	class Redux_Slider extends Redux_Field {

		/**
		 * No value readout.
		 *
		 * @var int
		 */
		private $display_none = 0;

		/**
		 * Label value readout.
		 *
		 * @var int
		 */
		private $display_label = 1;

		/**
		 * Text value readout.
		 *
		 * @var int
		 */
		private $display_text = 2;

		/**
		 * Select box value readout.
		 *
		 * @var int
		 */
		private $display_select = 3;

		/**
		 * Set field and value defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'handles'       => 1,
				'resolution'    => 1,
				'display_value' => 'text',
				'float_mark'    => '.',
				'forced'        => true,
				'min'           => 0,
				'max'           => 1,
				'step'          => 1,
			);

			$this->field = wp_parse_args( $this->field, $defaults );

			// Sanitize float mark.
			if ( ',' !== $this->field['float_mark'] && '.' !== $this->field['float_mark'] ) {
				$this->field['float_mark'] = '.';
			}

			// Sanitize resolution value.
			$this->field['resolution'] = $this->clean_val( $this->field['resolution'] );

			// Sanitize handle value.
			if ( 0 === $this->field['handles'] || 1 === $this->field['handles'] ) {
				$this->field['handles'] = 1;
			} else {
				$this->field['handles'] = 2;
			}

			// Sanitize display value.
			if ( 'label' === $this->field['display_value'] ) {
				$this->field['display_value'] = $this->display_label;
			} elseif ( 'select' === $this->field['display_value'] ) {
				$this->field['display_value'] = $this->display_select;
			} elseif ( 'none' === $this->field['display_value'] ) {
				$this->field['display_value'] = $this->display_none;
			} else {
				$this->field['display_value'] = $this->display_text;
			}
		}

		/**
		 * Sanitize value.
		 *
		 * @param mixed $var Value to sanitize.
		 *
		 * @return float|int
		 */
		private function clean_val( $var ) {
			if ( is_float( $var ) ) {
				$clear_var = floatval( $var );
			} else {
				$clear_var = intval( $var );
			}

			return $clear_var;
		}

		/**
		 * Clean default values.
		 *
		 * @param mixed $val Default values.
		 *
		 * @return float|int
		 */
		private function clean_default( $val ) {
			if ( empty( $val ) && ! empty( $this->field['default'] ) && $this->clean_val( $this->field['min'] ) >= 1 ) {
				$val = $this->clean_val( $this->field['default'] );
			}

			if ( empty( $val ) && $this->clean_val( $this->field['min'] ) >= 1 ) {
				$val = $this->clean_val( $this->field['min'] );
			}

			if ( empty( $val ) ) {
				$val = 0;
			}

			// Extra Validation.
			if ( $val < $this->field['min'] ) {
				$val = $this->clean_val( $this->field['min'] );
			} elseif ( $val > $this->field['max'] ) {
				$val = $this->clean_val( $this->field['max'] );
			}

			return $val;
		}

		/**
		 * Sanitize default array.
		 *
		 * @param array $val Defaults.
		 *
		 * @return array
		 */
		private function clean_default_array( array $val ): array {
			$one = $this->value[1];
			$two = $this->value[2];

			if ( empty( $one ) && ! empty( $this->field['default'][1] ) && $this->clean_val( $this->field['min'] ) >= 1 ) {
				$one = $this->clean_val( $this->field['default'][1] );
			}

			if ( empty( $one ) && $this->clean_val( $this->field['min'] ) >= 1 ) {
				$one = $this->clean_val( $this->field['min'] );
			}

			if ( empty( $one ) ) {
				$one = 0;
			}

			if ( empty( $two ) && ! empty( $this->field['default'][2] ) && $this->clean_val( $this->field['min'] ) >= 1 ) {
				$two = $this->clean_val( $this->field['default'][1] + 1 );
			}

			if ( empty( $two ) && $this->clean_val( $this->field['min'] ) >= 1 ) {
				$two = $this->clean_val( $this->field['default'][1] + 1 );
			}

			if ( empty( $two ) ) {
				$two = $this->field['default'][1] + 1;
			}

			$val[0] = $one;
			$val[1] = $two;

			return $val;
		}

		/**
		 * Clean the field data to the fields defaults given the parameters.
		 *
		 * @since Redux_Framework 3.1.8
		 */
		private function clean() {

			// Set min to 0 if no value is set.
			$this->field['min'] = empty( $this->field['min'] ) ? 0 : $this->clean_val( $this->field['min'] );

			// Set max to min + 1 if empty.
			$this->field['max'] = empty( $this->field['max'] ) ? $this->field['min'] + 1 : $this->clean_val( $this->field['max'] );

			// Set step to 1 if step is empty ot step > max.
			$this->field['step'] = empty( $this->field['step'] ) || $this->field['step'] > $this->field['max'] ? 1 : $this->clean_val( $this->field['step'] );

			if ( 2 === $this->field['handles'] ) {
				if ( ! is_array( $this->value ) ) {
					$this->value[1] = 0;
					$this->value[2] = 1;
				}
				$this->value = $this->clean_default_array( $this->value );
			} else {
				if ( is_array( $this->value ) ) {
					$this->value = 0;
				}
				$this->value = $this->clean_default( $this->value );
			}

			// More dummy checks.
			if ( ! is_array( $this->value ) && 2 === $this->field['handles'] ) {
				$this->value[0] = $this->field['min'];
				$this->value[1] = $this->field['min'] + 1;
			}

			if ( is_array( $this->value ) && 1 === $this->field['handles'] ) {
				$this->value = $this->field['min'];
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since ReduxFramework 3.1.8
		 */
		public function enqueue() {
			$min = Redux_Functions::is_min();

			wp_enqueue_style( 'select2-css' );

			wp_enqueue_style(
				'redux-nouislider-css',
				Redux_Core::$url . "assets/css/vendor/nouislider$min.css",
				array(),
				'5.0.0'
			);

			wp_register_script(
				'redux-nouislider-js',
				Redux_Core::$url . 'assets/js/vendor/nouislider/redux.jquery.nouislider' . $min . '.js',
				array( 'jquery' ),
				'5.0.0',
				true
			);

			wp_enqueue_script(
				'redux-field-slider-js',
				Redux_Core::$url . 'inc/fields/slider/redux-slider' . $min . '.js',
				array( 'jquery', 'redux-nouislider-js', 'redux-js', 'select2-js' ),
				$this->timestamp,
				true
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-slider-css',
					Redux_Core::$url . 'inc/fields/slider/redux-slider.css',
					array(),
					$this->timestamp
				);
			}
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 0.0.4
		 */
		public function render() {
			$this->clean();

			$field_id   = $this->field['id'];
			$field_name = $this->field['name'] . $this->field['name_suffix'];

			// Set handle number variable.
			$two_handles = false;
			if ( 2 === $this->field['handles'] ) {
				$two_handles = true;
			}

			// Set default values(s).
			if ( true === $two_handles ) {
				$val_one = $this->value[0];
				$val_two = $this->value[1];

				$html  = 'data-default-one=' . $val_one . ' ';
				$html .= 'data-default-two=' . $val_two . ' ';

				$name_one = $field_name . '[1]';
				$name_two = $field_name . '[2]';

				$id_one = $field_id . '[1]';
				$id_two = $field_id . '[2]';
			} else {
				$val_one = $this->value;
				$val_two = '';

				$html = 'data-default-one=' . $val_one;

				$name_one = $field_name;
				$name_two = '';

				$id_one = $field_id;
				$id_two = '';
			}

			$show_input  = false;
			$show_label  = false;
			$show_select = false;

			// TEXT output.
			if ( $this->display_text === $this->field['display_value'] ) {
				$show_input = true;
				echo '<input
						type="text"
                        name="' . esc_attr( $name_one ) . '"
                        id="' . esc_attr( $id_one ) . '"
                        value="' . esc_attr( $val_one ) . '"
                        class="redux-slider-input redux-slider-input-one-' . esc_attr( $field_id ) . ' ' . esc_attr( $this->field['class'] ) . '"/>';

				// LABEL output.
			} elseif ( $this->display_label === $this->field['display_value'] ) {
				$show_label = true;

				$label_num = $two_handles ? '-one' : '';

				echo '<div class="redux-slider-label' . esc_attr( $label_num ) . '"
                       id="redux-slider-label-one-' . esc_attr( $field_id ) . '"
                       name="' . esc_attr( $name_one ) . '">
                  </div>';

				// SELECT output.
			} elseif ( $this->display_select === $this->field['display_value'] ) {
				$show_select = true;

				if ( isset( $this->field['select2'] ) ) {
					$this->field['select2'] = wp_parse_args( $this->field['select2'], $this->select2_config );
				} else {
					$this->field['select2'] = $this->select2_config;
				}

				$this->field['select2'] = Redux_Functions::sanitize_camel_case_array_keys( $this->field['select2'] );

				$select2_data = Redux_Functions::create_data_string( $this->field['select2'] );

				echo '<select
						class="redux-slider-select-one redux-slider-select-one-' . esc_attr( $field_id ) . ' ' . esc_attr( $this->field['class'] ) . '"
                        name="' . esc_attr( $name_one ) . '"
                        id="' . esc_attr( $id_one ) . '" ' . esc_attr( $select2_data ) . '></select>';
			}

			// DIV output.
			echo '<div
	                class="redux-slider-container ' . esc_attr( $this->field['class'] ) . '"
	                id="' . esc_attr( $field_id ) . '"
	                data-id="' . esc_attr( $field_id ) . '"
	                data-min="' . esc_attr( $this->field['min'] ) . '"
	                data-max="' . esc_attr( $this->field['max'] ) . '"
	                data-step="' . esc_attr( $this->field['step'] ) . '"
	                data-handles="' . esc_attr( $this->field['handles'] ) . '"
	                data-display="' . esc_attr( $this->field['display_value'] ) . '"
	                data-rtl="' . esc_attr( is_rtl() ) . '"
	                data-forced="' . esc_attr( $this->field['forced'] ) . '"
	                data-float-mark="' . esc_attr( $this->field['float_mark'] ) . '"
	                data-resolution="' . esc_attr( $this->field['resolution'] ) . '" ' . esc_html( $html ) . '></div>';

			// Double slider output.
			if ( true === $two_handles ) {

				// TEXT.
				if ( true === $show_input ) {
					echo '<input
							type="text"
                            name="' . esc_attr( $name_two ) . '"
                            id="' . esc_attr( $id_two ) . '"
                            value="' . esc_attr( $val_two ) . '"
                            class="redux-slider-input redux-slider-input-two-' . esc_attr( $field_id ) . ' ' . esc_attr( $this->field['class'] ) . '"/>';
				}

				// LABEL.
				if ( true === $show_label ) {
					echo '<div
							class="redux-slider-label-two"
                            id="redux-slider-label-two-' . esc_attr( $field_id ) . '"
                            name="' . esc_attr( $name_two ) . '"></div>';
				}

				// SELECT.
				if ( true === $show_select ) {
					echo '<select
								class="redux-slider-select-two redux-slider-select-two-' . esc_attr( $field_id ) . ' ' . esc_attr( $this->field['class'] ) . '"
                                name="' . esc_attr( $name_two ) . '"
                                id="' . esc_attr( $id_two ) . '" ' . esc_attr( $select2_data ) . '></select>';
				}
			}

			// NO output (input hidden).
			if ( $this->display_none === $this->field['display_value'] || $this->display_label === $this->field['display_value'] ) {
				echo '<input
							type="hidden"
	                        class="redux-slider-value-one-' . esc_attr( $field_id ) . ' ' . esc_attr( $this->field['class'] ) . '"
	                        name="' . esc_attr( $name_one ) . '"
	                        id="' . esc_attr( $id_one ) . '"
	                        value="' . esc_attr( $val_one ) . '"/>';

				// double slider hidden output.
				if ( true === $two_handles ) {
					echo '<input
								type="hidden"
	                            class="redux-slider-value-two-' . esc_attr( $field_id ) . ' ' . esc_attr( $this->field['class'] ) . '"
	                            name="' . esc_attr( $name_two ) . '"
	                            id="' . esc_attr( $id_two ) . '"
	                            value="' . esc_attr( $val_two ) . '"/>';
				}
			}
		}

		/**
		 * Enable output_variables to be generated.
		 *
		 * @since       4.0.3
		 * @return void
		 */
		public function output_variables() {
			// No code needed, just defining the method is enough.
		}
	}
}

class_alias( 'Redux_Slider', 'ReduxFramework_Slider' );
