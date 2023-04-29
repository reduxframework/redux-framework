<?php
/**
 * Redux JS Button Field Class
 *
 * @package Redux Extentions
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Js_Button
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Js_Button' ) ) {

	/**
	 * Main ReduxFramework_Js_Button class
	 *
	 * @since       1.0.0
	 */
	class Redux_Js_Button extends Redux_Field {

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

			// primary container.
			echo '<div
	                class="redux-js-button-container ' . esc_attr( $this->field['class'] ) . '"
	                id="' . esc_attr( $field_id ) . '_container"
	                data-id="' . esc_attr( $field_id ) . '"
	                style="width: 0px;"
	            >';

			// Button render.
			if ( isset( $this->field['buttons'] ) && is_array( $this->field['buttons'] ) ) {
				echo '<div
	                    class="redux-js-button-button-container"
	                    id="redux-js-button-button-container"
	                    style="display: inline-flex;"
	                >';

				foreach ( $this->field['buttons'] as $idx => $arr ) {
					$button_text  = $arr['text'];
					$button_class = $arr['class'];
					$button_func  = $arr['function'];

					echo '<input
	                        id="' . esc_attr( $field_id ) . '_input-' . intval( $idx ) . '"
	                        class="hide-if-no-js button ' . esc_attr( $button_class ) . '"
	                        type="button"
	                        data-function="' . esc_attr( $button_func ) . '"
	                        value="' . esc_attr( $button_text ) . '"
	                    />&nbsp;&nbsp;';
				}

				echo '</div>';
			}

			// Close container.
			echo '</div>';
		}


		/**
		 * Do enqueue for every field instance.
		 *
		 * @return void
		 */
		public function always_enqueue() {
			// Make sure script data exists first.
			if ( isset( $this->field['script'] ) && ! empty( $this->field['script'] ) ) {

				// URI location of script to enqueue.
				$script_url = $this->field['script']['url'] ?? '';

				// Get deps, if any.
				$script_dep = $this->field['script']['dep'] ?? array();

				// Get ver, if any.
				$script_ver = $this->field['script']['ver'] ?? time();

				// Script location in HTML.
				$script_footer = $this->field['script']['in_footer'] ?? true;

				// If a script exists, enqueue it.
				if ( '' !== $script_url ) {
					wp_enqueue_script(
						'redux-js-button-' . $this->field['id'],
						$script_url,
						$script_dep,
						$script_ver,
						$script_footer
					);
				}

				if ( isset( $this->field['enqueue_ajax'] ) && $this->field['enqueue_ajax'] ) {
					wp_localize_script(
						'redux-js-button-' . $this->field['id'],
						'redux_ajax_script',
						array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
					);
				}
			}
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
				'redux-field-js-button',
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				apply_filters( "redux/js_button/{$this->parent->args['opt_name']}/enqueue/redux-field-js-button-js", $this->url . 'redux-js-button' . $min . '.js' ),
				array( 'jquery' ),
				Redux_Extension_Js_Button::$version,
				true
			);
		}
	}
}
