<?php
/**
 * Color Palette
 *
 * @package     Redux Framework
 * @subpackage  Redux_Color_Palette
 * @author      Kevin Provance (kprovance)
 * @version     4.1.30
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Color_Palette' ) ) {

	/**
	 * Main Redux_Color_Palette class
	 *
	 * @since       4.0.0
	 */
	class Redux_Color_Palette extends Redux_Field {

		/**
		 * Set Defaults.
		 */
		public function set_defaults() {

			$defaults = array(
				'options' => array(
					'colors'     => array(),
					'size'       => 20,
					'style'      => 'square',
					'box-shadow' => false,
					'margin'     => false,
				),
			);

			$this->field = Redux_Functions::parse_args( $this->field, $defaults );
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
			$box_shadow = '';
			$margin     = '';

			if ( $this->field['options']['box-shadow'] ) {
				$box_shadow = ' box-shadow';
			}

			if ( $this->field['options']['margin'] ) {
				$margin = ' with-margin';
			}

			echo '<div id="input_' . esc_attr( $this->field['id'] ) . '" class="colors-wrapper ' . esc_attr( $this->field['options']['style'] ) . esc_attr( $box_shadow ) . esc_attr( $margin ) . '">';

			foreach ( $this->field['options']['colors'] as $idx => $key ) {
				$checked = '';

				if ( $this->value === $key ) {
					$checked = 'checked';
				}

				echo '<input
                            type="radio"
                            value="' . esc_attr( $key ) . '"
                            name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '"
                            id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $idx ) . '"' . esc_attr( $checked ) . '>';
				echo '<label for="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $idx ) . '" style="width:' . esc_attr( $this->field['options']['size'] ) . 'px;height:' . esc_attr( $this->field['options']['size'] ) . 'px;">';
				echo '<span class="color-palette-color" style="background:' . esc_attr( $key ) . ';">' . esc_attr( $key ) . '</span>';
				echo '</label>';
				echo '</input>';
			}

			echo '</div>';
		}

		/**
		 * CSS output.
		 *
		 * @param string|null|array $style CSS data.
		 */
		public function output( $style = '' ) {
			if ( ! empty( $this->value ) ) {
				$mode = ( isset( $this->field['mode'] ) && ! empty( $this->field['mode'] ) ? $this->field['mode'] : 'color' );

				$style .= $mode . ':' . $this->value . ';';

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
		 * Enqueue Function.
		 * If this field requires any scripts, or CSS define this function and register/enqueue the scripts/css
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function enqueue() {
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-color-palette-css',
					Redux_Core::$url . 'inc/fields/color_palette/redux-color-palette.css',
					array(),
					time()
				);
			}
		}
	}
}
