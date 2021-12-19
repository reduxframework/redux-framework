<?php
/**
 * Dimension Field
 *
 * @package     ReduxFramework
 * @subpackage  Field_Dimensions
 * @author      Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Dimensions', false ) ) {

	/**
	 * Class Redux_Dimensions
	 */
	class Redux_Dimensions extends Redux_Field {

		/**
		 * Set field and value defaults.
		 */
		public function set_defaults() {
			// No errors please.
			$defaults = array(
				'width'          => true,
				'height'         => true,
				'units_extended' => false,
				'units'          => 'px',
				'mode'           => array(
					'width'  => false,
					'height' => false,
				),
			);

			$this->field = wp_parse_args( $this->field, $defaults );

			$defaults = array(
				'width'  => '',
				'height' => '',
				'units'  => 'px',
			);

			$this->value = wp_parse_args( $this->value, $defaults );

			if ( isset( $this->value['unit'] ) ) {
				$this->value['units'] = $this->value['unit'];
			}
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function render() {
			/*
			 * Acceptable values checks.  If the passed variable doesn't pass muster, we unset them
			 * and reset them with default values to avoid errors.
			 */

			$arr_units = Redux_Helpers::$array_units;

			$unit_check   = $arr_units;
			$unit_check[] = false;

			// If units field has a value but is not an acceptable value, unset the variable.
			if ( isset( $this->field['units'] ) && ! Redux_Helpers::array_in_array( $this->field['units'], $unit_check ) ) {
				unset( $this->field['units'] );
			}

			// If there is a default unit value  but is not an accepted value, unset the variable.
			if ( isset( $this->value['units'] ) && ! Redux_Helpers::array_in_array( $this->value['units'], $unit_check ) ) {
				unset( $this->value['units'] );
			}

			/*
			 * Since units field could be an array, string value or bool (to hide the unit field)
			 * we need to separate our functions to avoid those nasty PHP index notices!
			 */

			// if field units has a value and IS an array, then evaluate as needed.
			if ( isset( $this->field['units'] ) && ! is_array( $this->field['units'] ) ) {

				// If units fields has a value but units value does not then make units value the field value.
				if ( isset( $this->field['units'] ) && ! isset( $this->value['units'] ) || false === $this->field['units'] ) {
					$this->value['units'] = $this->field['units'];

					// If units field does NOT have a value and units value does NOT have a value, set both to blank (default?).
				} elseif ( ! isset( $this->field['units'] ) && ! isset( $this->value['units'] ) ) {
					$this->field['units'] = 'px';
					$this->value['units'] = 'px';

					// If units field has NO value but units value does, then set unit field to value field.
				} elseif ( ! isset( $this->field['units'] ) && isset( $this->value['units'] ) ) {
					$this->field['units'] = $this->value['units'];

					// if unit value is set and unit value doesn't equal unit field (coz who knows why)
					// then set unit value to unit field.
				} elseif ( isset( $this->value['units'] ) && $this->value['units'] !== $this->field['units'] ) {
					$this->value['units'] = $this->field['units'];
				}

				// do stuff based on unit field NOT set as an array.
				// phpcs:ignore Generic.CodeAnalysis.EmptyStatement
			} elseif ( isset( $this->field['units'] ) && is_array( $this->field['units'] ) ) {
				// nothing to do here, but I'm leaving the construct just in case I have to debug this again.
			}

			echo '<fieldset id="' . esc_attr( $this->field['id'] ) . '-fieldset" class="redux-dimensions-container" data-id="' . esc_attr( $this->field['id'] ) . '">';

			$this->select2_config['allowClear'] = false;

			if ( isset( $this->field['select2'] ) ) {
				$this->field['select2'] = wp_parse_args( $this->field['select2'], $this->select2_config );
			} else {
				$this->field['select2'] = $this->select2_config;
			}

			$this->field['select2'] = Redux_Functions::sanitize_camel_case_array_keys( $this->field['select2'] );

			$select2_data = Redux_Functions::create_data_string( $this->field['select2'] );

			// This used to be unit field, but was giving the PHP index error when it was an array,
			// so I changed it.
			echo '<input type="hidden" class="field-units" value="' . esc_attr( $this->value['units'] ) . '">';

			/**
			 * Width
			 * */
			if ( true === $this->field['width'] ) {
				if ( ! empty( $this->value['width'] ) && false !== $this->value['units'] && strpos( $this->value['width'], strval( $this->value['units'] ) ) === false ) {
					$this->value['width'] = filter_var( $this->value['width'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
					if ( false !== $this->field['units'] ) {
						$this->value['width'] .= $this->value['units'];
					}
				}
				echo '<div class="field-dimensions-input input-prepend">';
				echo '<span class="add-on"><i class="el el-resize-horizontal icon-large"></i></span>';
				echo '<input
						type="text"
						class="redux-dimensions-input redux-dimensions-width mini ' . esc_attr( $this->field['class'] ) . '"
						placeholder="' . esc_html__( 'Width', 'redux-framework' ) . '"
						rel="' . esc_attr( $this->field['id'] ) . '-width"
						value="' . esc_attr( filter_var( $this->value['width'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) ) . '">';

				echo '<input
						data-id="' . esc_attr( $this->field['id'] ) . '"
						type="hidden"
						id="' . esc_attr( $this->field['id'] ) . '-width"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[width]"
						value="' . esc_attr( $this->value['width'] ) . '">';

				echo '</div>';
			}

			/**
			 * Height
			 * */
			if ( true === $this->field['height'] ) {
				if ( ! empty( $this->value['height'] ) && false !== $this->value['units'] && strpos( $this->value['height'], strval( $this->value['units'] ) ) === false ) {
					$this->value['height'] = filter_var( $this->value['height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
					if ( false !== $this->field['units'] ) {
						$this->value['height'] .= $this->value['units'];
					}
				}
				echo '<div class="field-dimensions-input input-prepend">';
				echo '<span class="add-on"><i class="el el-resize-vertical icon-large"></i></span>';
				echo '<input
						type="text"
						class="redux-dimensions-input redux-dimensions-height mini ' . esc_attr( $this->field['class'] ) . '"
						placeholder="' . esc_html__( 'Height', 'redux-framework' ) . '"
						rel="' . esc_attr( $this->field['id'] ) . '-height"
						value="' . esc_attr( filter_var( $this->value['height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) ) . '">';

				echo '<input
						data-id="' . esc_attr( $this->field['id'] ) . '"
						type="hidden"
						id="' . esc_attr( $this->field['id'] ) . '-height"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[height]"
						value="' . esc_attr( $this->value['height'] ) . '">';
						echo '</div>';
			}

			/**
			 * Units
			 * */
			// If units field is set and units field NOT false then fill out the options object and show it, otherwise it's hidden
			// and the default units value will apply.
			if ( isset( $this->field['units'] ) && false !== $this->field['units'] ) {
				echo '<div
						class="select_wrapper dimensions-units"
						original-title="' . esc_html__( 'Units', 'redux-framework' ) . '">';

				echo '<select
						data-id="' . esc_attr( $this->field['id'] ) . '"
						data-placeholder="' . esc_html__( 'Units', 'redux-framework' ) . '"
						class="redux-dimensions redux-dimensions-units select ' . esc_attr( $this->field['class'] ) . '"
						original-title="' . esc_html__( 'Units', 'redux-framework' ) . '"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[units]"' . esc_attr( $select2_data ) . '>';

				// Extended units, show 'em all.
				if ( $this->field['units_extended'] ) {
					$test_units = $arr_units;
				} else {
					$test_units = array( 'px', 'em', 'rem', '%' );
				}

				if ( '' !== $this->field['units'] && is_array( $this->field['units'] ) ) {
					$test_units = $this->field['units'];
				}

				if ( in_array( $this->field['units'], $test_units, true ) ) {
					echo '<option value="' . esc_attr( $this->field['units'] ) . '" selected="selected">' . esc_attr( $this->field['units'] ) . '</option>';
				} else {
					foreach ( $test_units as $a_unit ) {
						echo '<option value="' . esc_attr( $a_unit ) . '" ' . selected( $this->value['units'], $a_unit, false ) . '>' . esc_attr( $a_unit ) . '</option>';
					}
				}
				echo '</select></div>';
			}

			echo '</fieldset>';
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function enqueue() {
			wp_enqueue_style( 'select2-css' );

			wp_enqueue_script(
				'redux-field-dimensions-js',
				Redux_Core::$url . 'inc/fields/dimensions/redux-dimensions' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'select2-js', 'redux-js' ),
				$this->timestamp,
				true
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-dimensions-css',
					Redux_Core::$url . 'inc/fields/dimensions/redux-dimensions.css',
					array(),
					$this->timestamp
				);
			}
		}

		/**
		 * Compile CSS styles for output.
		 *
		 * @param string $data CSS data.
		 *
		 * @return string
		 */
		public function css_style( $data ): string {
			$style = '';

			// If field units has a value and IS an array, then evaluate as needed.
			if ( isset( $this->field['units'] ) && ! is_array( $this->field['units'] ) ) {

				// If units fields has a value but units value does not then make units value the field value.
				if ( isset( $this->field['units'] ) && ! isset( $this->value['units'] ) || false === $this->field['units'] ) {
					$this->value['units'] = $this->field['units'];

					// If units field does NOT have a value and units value does NOT have a value, set both to blank (default?).
				} elseif ( ! isset( $this->field['units'] ) && ! isset( $this->value['units'] ) ) {
					$this->field['units'] = 'px';
					$this->value['units'] = 'px';

					// If units field has NO value but units value does, then set unit field to value field.
				} elseif ( ! isset( $this->field['units'] ) && isset( $this->value['units'] ) ) {
					$this->field['units'] = $this->value['units'];

					// If unit value is set and unit value doesn't equal unit field (coz who knows why)
					// then set unit value to unit field.
				} elseif ( isset( $this->value['units'] ) && $this->field['units'] !== $this->value['units'] ) {
					$this->value['units'] = $this->field['units'];
				}

				// Do stuff based on unit field NOT set as an array.
				// phpcs:ignore Generic.CodeAnalysis.EmptyStatement
			} elseif ( isset( $this->field['units'] ) && is_array( $this->field['units'] ) ) {
				// nothing to do here, but I'm leaving the construct just in case I have to debug this again.
			}

			$units = $this->value['units'] ?? '';

			if ( ! is_array( $this->field['mode'] ) ) {
				$height = isset( $this->field['mode'] ) && ! empty( $this->field['mode'] ) ? $this->field['mode'] : 'height';
				$width  = isset( $this->field['mode'] ) && ! empty( $this->field['mode'] ) ? $this->field['mode'] : 'width';
			} else {
				$height = false !== $this->field['mode']['height'] ? $this->field['mode']['height'] : 'height';
				$width  = false !== $this->field['mode']['width'] ? $this->field['mode']['width'] : 'width';
			}

			$clean_value = array(
				$height => isset( $this->value['height'] ) ? filter_var( $this->value['height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : '',
				$width  => isset( $this->value['width'] ) ? filter_var( $this->value['width'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : '',
			);

			foreach ( $clean_value as $key => $value ) {
				// Output if it's a numeric entry.
				if ( isset( $value ) && is_numeric( $value ) ) {
					$style .= $key . ':' . $value . $units . ';';
				}
			}

			return $style;
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

class_alias( 'Redux_Dimensions', 'ReduxFramework_Dimensions' );
