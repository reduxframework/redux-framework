<?php
/**
 * Text Field
 *
 * @package     Redux Framework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

use Redux_Descriptor_Types as RDT;

if ( ! class_exists( 'Redux_Text', false ) ) {

	/**
	 * Class Redux_Text
	 */
	class Redux_Text extends Redux_Field {

		/**
		 * Set the field's custom descriptors.
		 */
		public static function make_descriptor() {
			$d = static::make_base_descriptor();

			$d->set_info( 'Text', __( 'The Text field accepts any form of text and optionally validates the text before saving the value.', 'redux-framework' ) );

			$d->add_field( 'text_hint', __( 'Display a qTip div when active on the text field.', 'redux-framework' ), RDT::TEXT )->set_order( 100 )->set_required();
			$d->add_field( 'placeholder', __( 'Placeholder text.', 'redux-framework' ), RDT::TEXT )->set_order( 100 )->set_required();
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function render() {

			$this->field['attributes']            = wp_parse_args(
				$this->field['attributes'] ?? array(),
				array(
					'qtip_title'   => '',
					'qtip_text'    => '',
					'class'        => isset( $this->field['class'] ) && ! empty( $this->field['class'] ) ? array( trim( $this->field['class'] ) ) : array(),
					'readonly'     => isset( $this->field['readonly'] ) && $this->field['readonly'] ? 'readonly' : '',
					'autocomplete' => isset( $this->field['autocomplete'] ) && false === $this->field['autocomplete'] ? 'off' : '',
					'type'         => ! isset( $this->field['type'] ) ? 'text' : $this->field['type'],
				)
			);
			$this->field['attributes']['class'][] = 'regular-text';

			// Deprecated from the docs. Left as not to break user's code!
			if ( isset( $this->field['text_hint'] ) && ! empty( $this->field['text_hint'] ) ) {
				if ( ! is_array( $this->field['text_hint'] ) && is_string( $this->field['text_hint'] ) ) {
					$this->field['text_hint'] = array(
						'content' => $this->field['text_hint'],
					);
				} else {
					if ( isset( $this->field['text_hint']['title'] ) && ! empty( $this->field['text_hint']['title'] ) ) {
						if ( ! isset( $this->field['text_hint']['content'] ) || ( isset( $this->field['text_hint']['content'] ) && empty( $this->field['text_hint']['content'] ) ) ) {
							$this->field['text_hint']['content'] = $this->field['text_hint']['title'];
							unset( $this->field['text_hint']['title'] );
						}
					}
				}
				$this->field['attributes']['qtip_title'] = isset( $this->field['text_hint']['title'] ) ? 'qtip-title="' . $this->field['text_hint']['title'] . '" ' : '';
				$this->field['attributes']['qtip_text']  = isset( $this->field['text_hint']['content'] ) ? 'qtip-content="' . $this->field['text_hint']['content'] . '" ' : '';
			}

			if ( ! empty( $this->field['data'] ) && is_array( $this->field['data'] ) ) {
				$this->field['options'] = $this->field['data'];
				unset( $this->field['data'] );
			}

			if ( ! empty( $this->field['data'] ) && empty( $this->field['options'] ) ) {
				if ( empty( $this->field['args'] ) ) {
					$this->field['args'] = array();
				}
				if ( isset( $this->field['options'] ) && is_array( $this->field['options'] ) && ! is_array( min( $this->field['options'] ) ) ) {
					$this->field['options']               = $this->parent->wordpress_data->get( $this->field['data'], $this->field['args'], $this->value );
					$this->field['attributes']['class'][] = 'hasOptions';
				}
			}

			if ( empty( $this->value ) && ! empty( $this->field['options'] ) ) {
				$this->value = $this->field['options'];
			}

			$this->field['attributes']['class'] = implode( ' ', $this->field['attributes']['class'] );

			if ( isset( $this->field['options'] ) && ! empty( $this->field['options'] ) ) {
				if ( ! isset( $this->value ) || ( isset( $this->value ) && ! is_array( $this->value ) ) ) {
					$this->value = array();
				}
				foreach ( $this->field['options'] as $k => $v ) {
					$attributes = $this->field['attributes'];
					if ( ! isset( $this->value[ $k ] ) ) {
						$this->value[ $k ] = $v;
					}
					$attributes['value'] = $this->value[ $k ];
					if ( ! empty( $placeholder ) ) {
						$attributes['placeholder'] = ( is_array( $this->field['placeholder'] ) && isset( $this->field['placeholder'][ $k ] ) ) ? esc_attr( $this->field['placeholder'][ $k ] ) : '';
					}
					$attributes['name'] = esc_attr( $this->field['name'] . $this->field['name_suffix'] . '[' . esc_attr( $k ) ) . ']';
					$attributes['id']   = esc_attr( $this->field['id'] . $k );

					$attributes_string = $this->render_attributes( $attributes );
					echo '<div class="input_wrapper"><label for="' . $attributes['name'] . '">' . $v . '</label><input placeholder="' . $v . '" ' . $attributes_string . '></div>'; // phpcs:ignore WordPress.Security.EscapeOutput

				}
			} else {
				$this->field['attributes']['id']          = $this->field['id'];
				$this->field['attributes']['name']        = esc_attr( $this->field['name'] . $this->field['name_suffix'] );
				$this->field['attributes']['value']       = $this->value;
				$this->field['attributes']['placeholder'] = ( isset( $this->field['placeholder'] ) && ! is_array( $this->field['placeholder'] ) ) ? esc_attr( $this->field['placeholder'] ) : '';
				$attributes_string                        = $this->render_attributes( $this->field['attributes'] );
				echo '<input ' . $attributes_string . '>'; // phpcs:ignore WordPress.Security.EscapeOutput
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since ReduxFramework 3.0.0
		 */
		public function enqueue() {
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-text-css',
					Redux_Core::$url . 'inc/fields/text/redux-text.css',
					array(),
					$this->timestamp
				);
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

class_alias( 'Redux_Text', 'ReduxFramework_Text' );
