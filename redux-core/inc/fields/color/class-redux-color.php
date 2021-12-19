<?php
/**
 * Color Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Color', false ) ) {

	/**
	 * Main Redux_color class
	 *
	 * @since       1.0.0
	 */
	class Redux_Color extends Redux_Field {

		/**
		 * Set field defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'transparent' => true,
				'color_alpha' => false,
			);

			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since         1.0.0
		 * @access        public
		 * @return        void
		 */
		public function render() {
			if ( isset( $this->field['color_alpha'] ) && $this->field['color_alpha'] ) {
				$this->field['class'] = 'alpha-enabled';
			}

			echo '<input ';
			echo 'data-id="' . esc_attr( $this->field['id'] ) . '"';
			echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '"';
			echo 'id="' . esc_attr( $this->field['id'] ) . '-color"';
			echo 'class="color-picker redux-color redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
			echo 'type="text" value="' . esc_attr( $this->value ) . '"';
			echo 'data-oldcolor=""';
			echo 'data-default-color="' . ( isset( $this->field['default'] ) ? esc_attr( $this->field['default'] ) : '' ) . '"';

			$data = array(
				'field' => $this->field,
				'index' => '',
			);

			echo Redux_Functions_Ex::output_alpha_data( $data );  // phpcs:ignore WordPress.Security.EscapeOutput

			echo '>';

			echo '<input type="hidden" class="redux-saved-color" id="' . esc_attr( $this->field['id'] ) . '-saved-color" value="">';

			if ( ! isset( $this->field['transparent'] ) || false !== $this->field['transparent'] ) {
				$trans_checked = '';

				if ( 'transparent' === $this->value ) {
					$trans_checked = ' checked="checked"';
				}

				echo '<label for="' . esc_attr( $this->field['id'] ) . '-transparency" class="color-transparency-check">';
				echo '<input type="checkbox" class="checkbox color-transparency ' . esc_attr( $this->field['class'] ) . '" id="' . esc_attr( $this->field['id'] ) . '-transparency" data-id="' . esc_attr( $this->field['id'] ) . '-color" value="1"' . esc_html( $trans_checked ) . '>';
				echo esc_html__( 'Transparent', 'redux-framework' );
				echo '</label>';
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or CSS define this function and register/enqueue the scripts/css
		 *
		 * @since         1.0.0
		 * @access        public
		 * @return        void
		 */
		public function enqueue() {
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style( 'redux-color-picker-css' );
			}

			if ( ! wp_style_is( 'wp-color-picker' ) ) {
				wp_enqueue_style( 'wp-color-picker' );
			}

			$dep_array = array( 'jquery', 'wp-color-picker', 'redux-js' );

			wp_enqueue_script(
				'redux-field-color-js',
				Redux_Core::$url . 'inc/fields/color/redux-color' . Redux_Functions::is_min() . '.js',
				$dep_array,
				$this->timestamp,
				true
			);

			if ( isset( $this->field['color_alpha'] ) && $this->field['color_alpha'] ) {
				if ( ! wp_script_is( 'redux-wp-color-picker-alpha-js' ) ) {
					wp_enqueue_script( 'redux-wp-color-picker-alpha-js' );
				}
			}
		}

		/**
		 * Generate CSS style (unused, but needed).
		 *
		 * @param string $data Field data.
		 *
		 * @return string
		 */
		public function css_style( $data ): string {
			return '';
		}

		/**
		 * Output CSS styling.
		 *
		 * @param string|null|array $style CSS style.
		 */
		public function output( $style = '' ) {
			if ( ! empty( $this->value ) ) {
				$mode = ( isset( $this->field['mode'] ) && ! empty( $this->field['mode'] ) ? $this->field['mode'] : 'color' );

				$style = $mode . ':' . $this->value . ';';

				if ( ! empty( $this->field['output'] ) && is_array( $this->field['output'] ) ) {
					$css                      = Redux_Functions::parse_css( $this->field['output'], $style, $this->value );
					$this->parent->outputCSS .= $css;
				}

				if ( ! empty( $this->field['compiler'] ) && is_array( $this->field['compiler'] ) ) {
					$css                        = Redux_Functions::parse_css( $this->field['compiler'], $style, $this->value );
					$this->parent->compilerCSS .= $css;
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

class_alias( 'Redux_Color', 'ReduxFramework_Color' );
