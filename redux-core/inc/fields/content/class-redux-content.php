<?php
/**
 * Content Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Kevin Provance (kprovance)
 * @version     4.4.14
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Content', false ) ) {

	/**
	 * Main Redux_Content class
	 *
	 * @since       1.0.0
	 */
	class Redux_Content extends Redux_Field {

		/**
		 * Set field and value defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'content' => '',
				'class'   => '',
				'mode'    => 'content',
				'icon'    => '',
				'style'   => 'normal',
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
			echo '</td></tr></table>';

			if ( 'content' === $this->field['mode'] ) {
				echo '<div
						id="content-' . esc_attr( $this->field['id'] ) . '"
						class="redux-field redux-field-borders ' . esc_attr( $this->field['class'] ) . ' redux-field-' . esc_attr( $this->field['mode'] ) . '"' .
					'>';

				echo wp_kses_post( $this->field['content'] );
			} elseif ( 'heading' === $this->field['mode'] ) {
				$has_icon = isset( $this->field['icon'] ) && ! empty( $this->field['icon'] ) && true !== $this->field['icon'] ? 'hasIcon ' : '';

				echo '<div
						id="heading-' . esc_attr( $this->field['id'] ) . '"
						class="redux-field redux-field-borders ' . esc_attr( $has_icon ) . esc_attr( $this->field['class'] ) . ' redux-field-' . esc_attr( $this->field['mode'] ) . '"' .
					'>';

				if ( isset( $this->field['icon'] ) && ! empty( $this->field['icon'] ) && true !== $this->field['icon'] ) {
					echo '<p class="redux-heading-icon"><i class="' . esc_attr( $this->field['icon'] ) . ' icon-large"></i></p>';
				}

				echo '<h2 class="redux-heading-text">' . wp_kses_post( $this->field['content'] ) . '</h2>';
			} elseif ( 'subheading' === $this->field['mode'] ) {
				echo '<div
						id="subheading-' . esc_attr( $this->field['id'] ) . '"
						class="redux-field redux-field-borders ' . esc_attr( $this->field['class'] ) . ' redux-field-' . esc_attr( $this->field['mode'] ) . '"' .
					'>';

				echo wp_kses_post( $this->field['content'] );
			} elseif ( 'submessage' === $this->field['mode'] ) {
				echo '<div
						id="submessage-' . esc_attr( $this->field['id'] ) . '"
						class="redux-field redux-field-no-borders redux-submessage-' . esc_attr( $this->field['style'] ) . ' ' . esc_attr( $this->field['class'] ) . ' redux-field-' . esc_attr( $this->field['mode'] ) . '"' .
					'>';

				echo wp_kses_post( $this->field['content'] );
			}

			echo '</div>';
			echo '<table class="form-table no-border" style="display:none;">';
			echo '<tbody>';
			echo '<tr style="border-bottom:0; display:none;">';
			echo '<th style="padding-top:0;"></th>';
			echo '<td style="padding-top:0;">';
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
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-content',
					Redux_Core::$url . 'inc/fields/content/redux-content.css',
					array(),
					$this->timestamp
				);
			}
		}
	}
}
