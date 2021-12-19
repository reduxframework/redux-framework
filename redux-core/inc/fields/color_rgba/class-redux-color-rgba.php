<?php
/**
 * Color RGBA Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Color_Rgba', false ) ) {

	/**
	 * Main Redux_color_rgba class
	 *
	 * @since       1.0.0
	 */
	class Redux_Color_Rgba extends Redux_Field {

		/**
		 * Set field and value defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'color' => '',
				'alpha' => 1,
				'rgba'  => '',
			);

			$option_defaults = array(
				'show_input'             => true,
				'show_initial'           => false,
				'show_alpha'             => true,
				'show_palette'           => false,
				'show_palette_only'      => false,
				'max_palette_size'       => 10,
				'show_selection_palette' => false,
				'allow_empty'            => true,
				'clickout_fires_change'  => false,
				'choose_text'            => esc_html__( 'Choose', 'redux-framework' ),
				'cancel_text'            => esc_html__( 'Cancel', 'redux-framework' ),
				'show_buttons'           => true,
				'input_text'             => esc_html__( 'Select Color', 'redux-framework' ),
				'palette'                => null,
			);

			$this->value = wp_parse_args( $this->value, $defaults );

			if ( isset( $this->field ) && ! is_array( $this->field ) ) {
				return;
			}

			$this->field['options'] = isset( $this->field['options'] ) ? wp_parse_args( $this->field['options'], $option_defaults ) : $option_defaults;

			// Convert empty array to null, if there.
			$this->field['options']['palette'] = empty( $this->field['options']['palette'] ) ? null : $this->field['options']['palette'];

			$this->field['output_transparent'] = $this->field['output_transparent'] ?? false;
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function render() {
			$field_id = $this->field['id'];

			// Color picker container.
			echo '<div
                  class="redux-color-rgba-container ' . esc_attr( $this->field['class'] ) . '"
                  data-id="' . esc_attr( $field_id ) . '"
                  data-show-input="' . esc_attr( $this->field['options']['show_input'] ) . '"
                  data-show-initial="' . esc_attr( $this->field['options']['show_initial'] ) . '"
                  data-show-alpha="' . esc_attr( $this->field['options']['show_alpha'] ) . '"
                  data-show-palette="' . esc_attr( $this->field['options']['show_palette'] ) . '"
                  data-show-palette-only="' . esc_attr( $this->field['options']['show_palette_only'] ) . '"
                  data-show-selection-palette="' . esc_attr( $this->field['options']['show_selection_palette'] ) . '"
                  data-max-palette-size="' . esc_attr( $this->field['options']['max_palette_size'] ) . '"
                  data-allow-empty="' . esc_attr( $this->field['options']['allow_empty'] ) . '"
                  data-clickout-fires-change="' . esc_attr( $this->field['options']['clickout_fires_change'] ) . '"
                  data-choose-text="' . esc_attr( $this->field['options']['choose_text'] ) . '"
                  data-cancel-text="' . esc_attr( $this->field['options']['cancel_text'] ) . '"
                  data-input-text="' . esc_attr( $this->field['options']['input_text'] ) . '"
                  data-show-buttons="' . esc_attr( $this->field['options']['show_buttons'] ) . '"
                  data-palette="' . rawurlencode( wp_json_encode( $this->field['options']['palette'] ) ) . '"
              >';

			// Colour picker layout.
			if ( '' === $this->value['color'] || 'transparent' === $this->value['color'] ) {
				$color = '';
			} else {
				$color = Redux_Helpers::hex2rgba( $this->value['color'], $this->value['alpha'] );
			}

			if ( '' === $this->value['rgba'] && '' !== $this->value['color'] ) {
				$this->value['rgba'] = Redux_Helpers::hex2rgba( $this->value['color'], $this->value['alpha'] );
			}

			echo '<input
                    name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[color]"
                    id="' . esc_attr( $field_id ) . '-color-display"
                    class="redux-color-rgba"
                    type="text"
                    value="' . esc_attr( $this->value['color'] ) . '"
                    data-color="' . esc_attr( $color ) . '"
                    data-id="' . esc_attr( $field_id ) . '"
                    data-current-color="' . esc_attr( $this->value['color'] ) . '"
                    data-block-id="' . esc_attr( $field_id ) . '"
                    data-output-transparent="' . esc_attr( $this->field['output_transparent'] ) . '"
                  />';

			echo '<input
                    type="hidden"
                    class="redux-hidden-color"
                    data-id="' . esc_attr( $field_id ) . '-color"
                    id="' . esc_attr( $field_id ) . '-color"
                    value="' . esc_attr( $this->value['color'] ) . '"
                  />';

			// Hidden input for alpha channel.
			echo '<input
                    type="hidden"
                    class="redux-hidden-alpha"
                    data-id="' . esc_attr( $field_id ) . '-alpha"
                    name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[alpha]"
                    id="' . esc_attr( $field_id ) . '-alpha"
                    value="' . esc_attr( $this->value['alpha'] ) . '"
                  />';

			// Hidden input for rgba.
			echo '<input
                    type="hidden"
                    class="redux-hidden-rgba"
                    data-id="' . esc_attr( $field_id ) . '-rgba"
                    name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[rgba]"
                    id="' . esc_attr( $field_id ) . '-rgba"
                    value="' . esc_attr( $this->value['rgba'] ) . '"
                  />';

			echo '</div>';
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function enqueue() {

			// Set up min files for dev_mode = false.
			$min = Redux_Functions::is_min();

			// Field dependent JS.
			wp_enqueue_script(
				'redux-field-color-rgba-js',
				Redux_Core::$url . 'inc/fields/color_rgba/redux-color-rgba' . $min . '.js',
				array( 'jquery', 'redux-spectrum-js', 'redux-js' ),
				$this->timestamp,
				true
			);

			// Spectrum CSS.
			if ( ! wp_style_is( 'redux-spectrum-css' ) ) {
				wp_enqueue_style( 'redux-spectrum-css' );
			}

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-color-rgba-css',
					Redux_Core::$url . 'inc/fields/color_rgba/redux-color-rgba.css',
					array(),
					$this->timestamp
				);
			}
		}

		/**
		 * -> getColorVal.  Returns formatted color val in hex or rgba.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since       1.0.0
		 * @access      private
		 * @return      string
		 */
		private function get_color_val(): string {

			// No notices.
			$color = '';
			$alpha = 1;
			$rgba  = '';

			// Must be an array.
			if ( is_array( $this->value ) ) {

				// Enum array to parse values.
				foreach ( $this->value as $id => $val ) {

					// Sanitize alpha.
					if ( 'alpha' === $id ) {
						$alpha = is_numeric( $val ) ? $val : 1;
					} elseif ( 'color' === $id ) {
						$color = ! empty( $val ) ? $val : '';
					} elseif ( 'rgba' === $id ) {
						$rgba = Redux_Helpers::hex2rgba( $color, $alpha );
					}
				}

				// Only build rgba output if alpha ia less than 1.
				if ( $alpha < 1 && '' !== $alpha ) {
					$color = $rgba;
				}
			}

			return $color;
		}

		/**
		 * Generate CSS style.
		 *
		 * @param string $data Field data.
		 *
		 * @return string
		 */
		public function css_style( $data ): string {
			return '';
		}

		/**
		 * Output Function.
		 * Used to enqueue to the front-end
		 *
		 * @since   1.0.0
		 * @access  public
		 *
		 * @param   string|null|array $style css data.
		 *
		 * @return  void
		 */
		public function output( $style = '' ) {
			if ( ! empty( $this->value ) ) {
				$mode = ( isset( $this->field['mode'] ) && ! empty( $this->field['mode'] ) ? $this->field['mode'] : 'color' );

				$color_val = $this->get_color_val();

				$style = $mode . ':' . $color_val . ';';

				if ( ! empty( $this->field['output'] ) && is_array( $this->field['output'] ) ) {
					if ( ! empty( $color_val ) ) {
						$css                      = Redux_Functions::parse_css( $this->field['output'], $style, $color_val );
						$this->parent->outputCSS .= esc_attr( $css );
					}
				}

				if ( ! empty( $this->field['compiler'] ) && is_array( $this->field['compiler'] ) ) {
					if ( ! empty( $color_val ) ) {
						$css                        = Redux_Functions::parse_css( $this->field['compiler'], $style, $color_val );
						$this->parent->compilerCSS .= esc_attr( $css );
					}
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

class_alias( 'Redux_Color_Rgba', 'ReduxFramework_Color_Rgba' );
