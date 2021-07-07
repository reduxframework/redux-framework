<?php
/**
 * Border Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Border', false ) ) {

	/**
	 * Class Redux_Border
	 */
	class Redux_Border extends Redux_Field {

		/**
		 * Set field and value defaults.
		 */
		public function set_defaults() {
			// No errors please.
			$defaults = array(
				'top'    => true,
				'bottom' => true,
				'all'    => true,
				'style'  => true,
				'color'  => true,
				'left'   => true,
				'right'  => true,
			);

			$this->field = wp_parse_args( $this->field, $defaults );

			$defaults = array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
				'color'  => '',
				'style'  => '',
			);

			$this->value = wp_parse_args( $this->value, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function render() {
			$value = array(
				'top'    => isset( $this->value['border-top'] ) ? filter_var( $this->value['border-top'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['top'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
				'right'  => isset( $this->value['border-right'] ) ? filter_var( $this->value['border-right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['right'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
				'bottom' => isset( $this->value['border-bottom'] ) ? filter_var( $this->value['border-bottom'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['bottom'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
				'left'   => isset( $this->value['border-left'] ) ? filter_var( $this->value['border-left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['left'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
				'color'  => isset( $this->value['border-color'] ) ? $this->value['border-color'] : $this->value['color'],
				'style'  => isset( $this->value['border-style'] ) ? $this->value['border-style'] : $this->value['style'],
			);

			if ( ( isset( $this->value['width'] ) || isset( $this->value['border-width'] ) ) ) {
				if ( isset( $this->value['border-width'] ) && ! empty( $this->value['border-width'] ) ) {
					$this->value['width'] = $this->value['border-width'];
				}

				$this->value['width'] = $this->strip_alphas( $this->value['width'] );

				$value['top']    = $this->value['width'];
				$value['right']  = $this->value['width'];
				$value['bottom'] = $this->value['width'];
				$value['left']   = $this->value['width'];
			}

			$this->value = $value;

			$defaults = array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			);

			$this->check_for_all();

			$this->value = wp_parse_args( $this->value, $defaults );

			$this->select2_config['allowClear'] = false;

			if ( isset( $this->field['select2'] ) ) {
				$this->field['select2'] = wp_parse_args( $this->field['select2'], $this->select2_config );
			} else {
				$this->field['select2'] = $this->select2_config;
			}

			$this->field['select2'] = Redux_Functions::sanitize_camel_case_array_keys( $this->field['select2'] );

			$select2_data = Redux_Functions::create_data_string( $this->field['select2'] );

			echo '<input type="hidden" class="field-units" value="px">';

			if ( isset( $this->field['all'] ) && true === $this->field['all'] ) {
				echo '<div class="field-border-input input-prepend"><span class="add-on"><i class="el el-fullscreen icon-large"></i></span><input type="text" class="redux-border-all redux-border-input mini ' . esc_attr( $this->field['class'] ) . '" placeholder="' . esc_html__( 'All', 'redux-framework' ) . '" rel="' . esc_attr( $this->field['id'] ) . '-all" value="' . esc_attr( $this->value['top'] ) . '"></div>';
			}

			echo '<input type="hidden" class="redux-border-value" id="' . esc_attr( $this->field['id'] ) . '-top" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[border-top]" value="' . ( isset( $this->value['top'] ) && '' !== $this->value['top'] ? esc_attr( $this->value['top'] ) . 'px' : '' ) . '">';
			echo '<input type="hidden" class="redux-border-value" id="' . esc_attr( $this->field['id'] ) . '-right" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[border-right]" value="' . ( isset( $this->value['right'] ) && '' !== $this->value['right'] ? esc_attr( $this->value['right'] ) . 'px' : '' ) . '">';
			echo '<input type="hidden" class="redux-border-value" id="' . esc_attr( $this->field['id'] ) . '-bottom" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[border-bottom]" value="' . ( isset( $this->value['bottom'] ) && '' !== $this->value['bottom'] ? esc_attr( $this->value['bottom'] ) . 'px' : '' ) . '">';
			echo '<input type="hidden" class="redux-border-value" id="' . esc_attr( $this->field['id'] ) . '-left" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[border-left]" value="' . ( isset( $this->value['left'] ) && '' !== $this->value['left'] ? esc_attr( $this->value['left'] ) . 'px' : '' ) . '">';

			if ( ! isset( $this->field['all'] ) || true !== $this->field['all'] ) {
				/**
				 * Top
				 * */
				if ( true === $this->field['top'] ) {
					echo '<div class="field-border-input input-prepend">
                            <span class="add-on">
                                <i class="el el-arrow-up icon-large"></i>
                            </span>
                            <input type="text" class="redux-border-top redux-border-input mini ' . esc_attr( $this->field['class'] ) . '" placeholder="' . esc_html__( 'Top', 'redux-framework' ) . '" rel="' . esc_attr( $this->field['id'] ) . '-top" value="' . esc_attr( $this->value['top'] ) . '">
                         </div>';
				}

				/**
				 * Right
				 * */
				if ( true === $this->field['right'] ) {
					echo '<div class="field-border-input input-prepend">
                            <span class="add-on">
                                <i class="el el-arrow-right icon-large"></i>
                            </span>
                            <input type="text" class="redux-border-right redux-border-input mini ' . esc_attr( $this->field['class'] ) . '" placeholder="' . esc_html__( 'Right', 'redux-framework' ) . '" rel="' . esc_attr( $this->field['id'] ) . '-right" value="' . esc_attr( $this->value['right'] ) . '">
                        </div>';
				}

				/**
				 * Bottom
				 * */
				if ( true === $this->field['bottom'] ) {
					echo '<div class="field-border-input input-prepend">
                            <span class="add-on">
                                <i class="el el-arrow-down icon-large"></i>
                            </span>
                            <input type="text" class="redux-border-bottom redux-border-input mini ' . esc_attr( $this->field['class'] ) . '" placeholder="' . esc_html__( 'Bottom', 'redux-framework' ) . '" rel="' . esc_attr( $this->field['id'] ) . '-bottom" value="' . esc_attr( $this->value['bottom'] ) . '">
                        </div>';
				}

				/**
				 * Left
				 * */
				if ( true === $this->field['left'] ) {
					echo '<div class="field-border-input input-prepend">
                            <span class="add-on">
                                <i class="el el-arrow-left icon-large"></i>
                            </span>
                            <input type="text" class="redux-border-left redux-border-input mini ' . esc_attr( $this->field['class'] ) . '" placeholder="' . esc_html__( 'Left', 'redux-framework' ) . '" rel="' . esc_attr( $this->field['id'] ) . '-left" value="' . esc_attr( $this->value['left'] ) . '">
                        </div>';
				}
			}

			/**
			 * Border-style
			 * */
			if ( false !== $this->field['style'] ) {
				$options = array(
					'solid'  => esc_html__( 'Solid', 'redux-framework' ),
					'dashed' => esc_html__( 'Dashed', 'redux-framework' ),
					'dotted' => esc_html__( 'Dotted', 'redux-framework' ),
					'double' => esc_html__( 'Double', 'redux-framework' ),
					'none'   => esc_html__( 'None', 'redux-framework' ),
				);

				echo '<select data-placeholder="' . esc_html__( 'Border style', 'redux-framework' ) . '" id="' . esc_attr( $this->field['id'] ) . '[border-style]" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[border-style]" class="tips redux-border-style ' . esc_attr( $this->field['class'] ) . '" rows="6" data-id="' . esc_attr( $this->field['id'] ) . '"' . esc_attr( $select2_data ) . '>';

				foreach ( $options as $k => $v ) {
					echo '<option value="' . esc_attr( $k ) . '" ' . selected( $value['style'], $k, false ) . '>' . esc_html( $v ) . '</option>';
				}

				echo '</select>';
			} else {
				echo '<input type="hidden" id="' . esc_attr( $this->field['id'] ) . '[border-style]" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[border-style]" value="' . esc_attr( $this->value['style'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '">';
			}

			/**
			 * Color
			 * */
			if ( false !== $this->field['color'] ) {
				$default = isset( $this->field['default']['border-color'] ) ? $this->field['default']['border-color'] : '';

				if ( empty( $default ) ) {
					$default = ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '#ffffff';
				}

				echo '<input ';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[border-color]"';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-border"';
				echo 'class="color-picker redux-border-color redux-color redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'value="' . esc_attr( $this->value['color'] ) . '"';
				echo 'data-default-color="' . esc_attr( $default ) . '"';
				echo 'data-id="' . esc_attr( $this->field['id'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => '',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data);

				echo '>';
			} else {
				echo '<input type="hidden" id="' . esc_attr( $this->field['id'] ) . '[border-color]" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[border-color]" value="' . esc_attr( $this->value['color'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '">';
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function enqueue() {
			$min = Redux_Functions::is_min();

			if ( ! wp_style_is( 'select2-css' ) ) {
				wp_enqueue_style( 'select2-css' );
			}

			if ( ! wp_style_is( 'wp-color-picker' ) ) {
				wp_enqueue_style( 'wp-color-picker' );
			}

			$dep_array = array( 'jquery', 'select2-js', 'wp-color-picker', 'redux-js' );

			wp_enqueue_script(
				'redux-field-border-js',
				Redux_Core::$url . 'inc/fields/border/redux-border' . $min . '.js',
				$dep_array,
				$this->timestamp,
				true
			);

			if ( isset( $this->field['color_alpha'] ) && $this->field['color_alpha'] ) {
				if ( ! wp_script_is( 'redux-wp-color-picker-alpha-js' ) ) {
					wp_enqueue_script( 'redux-wp-color-picker-alpha-js' );
				}
			}

			if ( $this->parent->args['dev_mode'] ) {
				if ( ! wp_style_is( 'redux-color-picker-css' ) ) {
					wp_enqueue_style( 'redux-color-picker-css' );
				}

				wp_enqueue_style(
					'redux-field-border-css',
					Redux_Core::$url . 'inc/fields/border/redux-border.css',
					array(),
					$this->timestamp
				);
			}
		}

		/**
		 * Check to make sure all is properly set.
		 *
		 * @return void
		 */
		private function check_for_all() {
			if ( true === $this->field['all'] ) {
				if ( 1 !== $this->field['top'] || 1 !== $this->field['bottom'] || 1 !== $this->field['left'] || 1 !== $this->field['right'] ) {
					$this->field['all'] = false;
				}
			}
		}

		/**
		 * Output CSS styling.
		 *
		 * @param string $data Value array.
		 *
		 * @return string|void
		 */
		public function css_style( $data ) {
			$style = '';

			$this->check_for_all();

			if ( isset( $this->field['all'] ) && true === $this->field['all'] ) {
				$border_width = isset( $data['border-width'] ) ? $data['border-width'] : '0px';
				$val          = isset( $data['border-top'] ) ? $data['border-top'] : $border_width;

				$data['border-top']    = $val;
				$data['border-bottom'] = $val;
				$data['border-left']   = $val;
				$data['border-right']  = $val;
			}

			$clean_value = array(
				'color' => ! empty( $data['border-color'] ) ? $data['border-color'] : '',
				'style' => ! empty( $data['border-style'] ) ? $data['border-style'] : '',
			);

			$border_width = '';
			if ( isset( $data['border-width'] ) ) {
				$border_width = $data['border-width'];
			}

			$this->field['top']    = isset( $this->field['top'] ) ? $this->field['top'] : true;
			$this->field['bottom'] = isset( $this->field['bottom'] ) ? $this->field['bottom'] : true;
			$this->field['left']   = isset( $this->field['left'] ) ? $this->field['left'] : true;
			$this->field['right']  = isset( $this->field['right'] ) ? $this->field['right'] : true;

			if ( true === $this->field['top'] ) {
				$clean_value['top'] = ! empty( $data['border-top'] ) ? $data['border-top'] : $border_width;
			}

			if ( true === $this->field['bottom'] ) {
				$clean_value['bottom'] = ! empty( $data['border-bottom'] ) ? $data['border-bottom'] : $border_width;
			}

			if ( true === $this->field['left'] ) {
				$clean_value['left'] = ! empty( $data['border-left'] ) ? $data['border-left'] : $border_width;
			}

			if ( true === $this->field['right'] ) {
				$clean_value['right'] = ! empty( $data['border-right'] ) ? $data['border-right'] : $border_width;
			}

			// absolute, padding, margin.
			if ( ! isset( $this->field['all'] ) || true !== $this->field['all'] ) {
				foreach ( $clean_value as $key => $value ) {
					if ( 'color' === $key || 'style' === $key ) {
						continue;
					}
					if ( ! empty( $value ) ) {
						$style .= 'border-' . $key . ':' . $value . ' ' . $clean_value['style'] . ' ' . $clean_value['color'] . ';';
					}
				}
			} else {
				if ( ! empty( $clean_value['top'] ) ) {
					$style .= 'border:' . $clean_value['top'] . ' ' . $clean_value['style'] . ' ' . $clean_value['color'] . ';';
				}
			}

			return $style;
		}

		/**
		 * Strip alpha chars.
		 *
		 * @param string $s Criteria.
		 *
		 * @return null|string|string[]
		 */
		private function strip_alphas( $s ) {
			// Regex is our friend.  THERE ARE FOUR LIGHTS!!
			return preg_replace( '/[^\d.-]/', '', $s );
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

class_alias( 'Redux_Border', 'ReduxFramework_Border' );
