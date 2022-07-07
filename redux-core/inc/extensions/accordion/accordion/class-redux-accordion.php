<?php
/**
 * Redux Accordion Field Class
 *
 * @package Redux Extentions
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Accordion
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Accordion' ) ) {

	/**
	 * Main ReduxFramework_Accordion class
	 *
	 * @since       1.0.0
	 */
	class Redux_Accordion extends Redux_Field {

		/**
		 * Set field defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'position'   => 'end',
				'style'      => '',
				'class'      => '',
				'title'      => '',
				'subtitle'   => '',
				'open'       => '',
				'open-icon'  => 'el-plus',
				'close-icon' => 'el-minus',
			);

			$this->field = wp_parse_args( $this->field, $defaults );
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
			$guid      = uniqid();
			$field_pos = '';
			$add_class = '';

			// primary container.
			if ( 'start' === $this->field['position'] ) {
				$add_class = ' form-table-accordion';
				$field_pos = 'start';
			} elseif ( 'end' === $this->field['position'] ) {
				$add_class = ' hide';
				$field_pos = 'end';
			}

			echo '<input type="hidden" id="accordion-' . esc_attr( $this->field['id'] ) . '-marker" data-open-icon="' . esc_attr( $this->field['open-icon'] ) . '" data-close-icon="' . esc_attr( $this->field['close-icon'] ) . '"></td></tr></table>';

			$is_open = false;
			if ( isset( $this->field['open'] ) && true === $this->field['open'] ) {
				$is_open = true;
			}

			echo '<div data-state="' . esc_attr( $is_open ) . '" data-position="' . esc_attr( $field_pos ) . '" id="' . esc_attr( $this->field['id'] ) . '" class="redux-accordion-field redux-field ' . esc_attr( $this->field['style'] ) . esc_attr( $this->field['class'] ) . '">';
			echo '<div class="control">';
			echo '<div class="redux-accordion-info' . esc_attr( $add_class ) . '">';

			if ( ! empty( $this->field['title'] ) ) {
				echo '<h3>' . esc_html( $this->field['title'] ) . '</h3>';
			}

			$icon_class = '';
			if ( ! empty( $this->field['subtitle'] ) ) {
				echo '<div class="redux-accordion-desc">' . esc_html( $this->field['subtitle'] ) . '</div>';
				$icon_class = ' subtitled';
			}

			echo '<span class="el el-plus' . esc_attr( $icon_class ) . '"></span>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '<table id="accordion-table-' . esc_attr( $this->field['id'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '" class="form-table form-table-accordion no-border' . esc_attr( $add_class ) . '"><tbody><tr class="hide"><th></th><td id="' . esc_attr( $guid ) . '">';

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
			$min = Redux_Functions::isMin();

			// Field dependent JS.
			wp_enqueue_script(
				'redux-field-accordion-js',
				$this->url . 'redux-accordion' . $min . '.js',
				array( 'jquery', 'redux-js' ),
				Redux_Extension_Accordion::$version,
				true
			);

			// Field CSS.
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-accordion',
					$this->url . 'redux-accordion.css',
					array(),
					Redux_Extension_Accordion::$version
				);
			}
		}
	}
}
