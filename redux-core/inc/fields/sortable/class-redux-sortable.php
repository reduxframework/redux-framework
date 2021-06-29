<?php
/**
 * Sortable Field
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Sortable', false ) ) {

	/**
	 * Class Redux_Sortable
	 */
	class Redux_Sortable extends Redux_Field {

		/**
		 * Set field defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'options' => array(),
				'label'   => false,
				'mode'    => 'text',
			);

			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since Redux_Options 2.0.1
		 */
		public function render() {
			if ( empty( $this->field['mode'] ) ) {
				$this->field['mode'] = 'text';
			}

			if ( 'checkbox' !== $this->field['mode'] && 'text' !== $this->field['mode'] && 'toggle' !== $this->field['mode'] ) {
				$this->field['mode'] = 'text';
			}

			if ( 'toggle' === $this->field['mode'] ) {
				$this->field['mode'] = 'checkbox';
			}

			$class   = ( isset( $this->field['class'] ) ) ? $this->field['class'] : '';
			$options = $this->field['options'];

			// This is to weed out missing options that might be in the default
			// Why?  Who knows.  Call it a dummy check.
			if ( ! empty( $this->value ) ) {
				foreach ( $this->value as $k => $v ) {
					if ( ! isset( $options[ $k ] ) ) {
						unset( $this->value[ $k ] );
					}
				}
			}

			$no_sort = false;
			if ( empty( $this->value ) && ! is_array( $this->value ) ) {
				if ( ! empty( $this->field['options'] ) ) {
					$this->value = $this->field['options'];
				} else {
					$this->value = array();
				}
			}
			foreach ( $options as $k => $v ) {
				if ( ! isset( $this->value[ $k ] ) ) {

					// A save has previously been done.
					if ( is_array( $this->value ) && array_key_exists( $k, $this->value ) ) {
						$this->value[ $k ] = $v;

						// Missing database entry, meaning no save has yet been done.
					} else {
						$no_sort           = true;
						$this->value[ $k ] = '';
					}
				}
			}

			// If missing database entries are found, it means no save has been done
			// and therefore no sort should be done.  Set the default array in the same
			// order as the options array.  Why?  The sort order is based on the
			// saved default array.  If entries are missing, the sort is messed up.
			// - kp.
			if ( true === $no_sort ) {
				$dummy_arr = array();

				foreach ( $options as $k => $v ) {
					$dummy_arr[ $k ] = $this->value[ $k ];
				}
				unset( $this->value );
				$this->value = $dummy_arr;
				unset( $dummy_arr );
			}

			$use_labels  = false;
			$label_class = ' checkbox';
			if ( 'checkbox' !== $this->field['mode'] ) {
				if ( ( isset( $this->field['label'] ) && true === $this->field['label'] ) ) {
					$use_labels  = true;
					$label_class = ' labeled';
				}
			}

			echo '<ul id="' . esc_attr( $this->field['id'] ) . '-list" class="redux-sortable ' . esc_attr( $class ) . ' ' . esc_attr( $label_class ) . '">';

			foreach ( $this->value as $k => $nicename ) {
				$invisible = '';

				if ( 'checkbox' === $this->field['mode'] ) {
					if ( empty( $this->value[ $k ] ) ) {
						$invisible = ' invisible';
					}
				}

				echo '<li class="' . esc_attr( $invisible ) . '">';

				$checked = '';
				$name    = 'name="' . $this->field['name'] . $this->field['name_suffix'] . '[' . esc_attr( $k ) . ']" ';

				if ( 'checkbox' === $this->field['mode'] ) {
					$value_display = $this->value[ $k ];

					if ( ! empty( $this->value[ $k ] ) ) {
						$checked = 'checked="checked" ';
					}

					$class .= ' checkbox_sortable';
					$name   = '';
					echo '<input
							type="hidden"
							name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[' . esc_attr( $k ) . ']"
							id="' . esc_attr( $this->field['id'] . '-' . $k ) . '-hidden"
							value="' . esc_attr( $value_display ) . '" />';

					echo '<div class="checkbox-container">';
				} else {
					$value_display = $this->value[ $k ] ?? '';
					$nicename      = $this->field['options'][ $k ];
				}

				if ( 'checkbox' !== $this->field['mode'] ) {
					if ( $use_labels ) {
						echo '<label class="bugger" for="' . esc_attr( $this->field['id'] ) . '[' . esc_attr( $k ) . ']"><strong>' . esc_html( $k ) . '</strong></label>';
						echo '<br />';
					}

					echo '<input
						rel="' . esc_attr( $this->field['id'] . '-' . $k ) . '-hidden"
						class="' . esc_attr( $class ) . '" ' . esc_html( $checked ) . '
						type="' . esc_attr( $this->field['mode'] ) . '"
						' . $name . // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'id="' . esc_attr( $this->field['id'] . '[' . $k ) . ']"
						value="' . esc_attr( $value_display ) . '"
						placeholder="' . esc_attr( $nicename ) . '" />';
				}

				echo '<span class="compact drag">';
				echo '<i class="dashicons dashicons-menu icon-large"></i>';
				echo '</span>';

				if ( 'checkbox' === $this->field['mode'] ) {
					echo '<i class="dashicons dashicons-visibility visibility"></i>';

					if ( 'checkbox' === $this->field['mode'] ) {
						echo '<label for="' . esc_attr( $this->field['id'] . '[' . $k ) . ']"><strong>' . esc_html( $options[ $k ] ) . '</strong></label>';
					}
				}

				if ( 'checkbox' === $this->field['mode'] ) {
					echo '</div>';
				}

				echo '</li>';
			}

			echo '</ul>';
		}

		/**
		 * Enqueue scripts and styles.
		 */
		public function enqueue() {
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-sortable-css',
					Redux_Core::$url . 'inc/fields/sortable/redux-sortable.css',
					array(),
					$this->timestamp
				);
			}

			wp_enqueue_script(
				'redux-field-sortable-js',
				Redux_Core::$url . 'inc/fields/sortable/redux-sortable' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'redux-js', 'jquery-ui-sortable' ),
				$this->timestamp,
				true
			);
		}
	}
}

class_alias( 'Redux_Sortable', 'ReduxFramework_Sortable' );
