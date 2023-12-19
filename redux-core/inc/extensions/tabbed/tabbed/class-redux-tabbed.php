<?php
/**
 * Tabbed Field
 *
 * @package     Redux Framework\Tabbed
 * @author      Kevin Provance (kprovance)
 * @version     4.4.8
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Tabbed', false ) ) {

	/**
	 * Class Redux_Tabbed
	 */
	class Redux_Tabbed extends Redux_Field {

		/**
		 * Set field defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'icon' => '',
			);

			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 0.0.4
		 */
		public function render() {
			$unallowed = array( 'tabbed', 'social_profiles', 'color_schemes', 'repeater' );

			echo '<div id="' . esc_attr( $this->field['id'] ) . '-tabbed" class="redux-tabbed" rel="' . esc_attr( $this->field['id'] ) . '">';
			echo '<div class="redux-tabbed-nav" data-id="' . esc_attr( $this->field['id'] ) . '">';
			foreach ( $this->field['tabs'] as $key => $tab ) {

				$tabbed_icon   = ( ! empty( $tab['icon'] ) ) ? '<i class="redux-tab-icon ' . esc_attr( $tab['icon'] ) . '"></i>' : '';
				$tabbed_active = ( empty( $key ) ) ? 'redux-tabbed-active' : '';

				// Output HTML escaped above.
				// phpcs:ignore WordPress.Security.EscapeOutput
				echo '<a href="#" class="' . esc_attr( $tabbed_active ) . '"">' . $tabbed_icon . esc_attr( $tab['title'] ) . '</a>';

			}
			echo '</div>';

			echo '<div class="redux-tabbed-contents">';
			foreach ( $this->field['tabs'] as $key => $tab ) {

				$tabbed_hidden = ( ! empty( $key ) ) ? ' hidden' : '';

				echo '<div class="redux-tabbed-content' . esc_attr( $tabbed_hidden ) . '">';

				foreach ( $tab['fields'] as $field ) {

					if ( in_array( $field['type'], $unallowed, true ) ) {
						echo esc_html__( 'The', 'redux-framework' ) . ' <code>' . esc_html( $field['type'] ) . '</code> ' . esc_html__( 'field is not supported within the Tabbed field.', 'redux-framework' );
					} else {
						$this->output_field( $field );
					}
				}

				echo '</div>';
			}

			echo '</div>';
			echo '</div>';
		}

		/**
		 * Output field.
		 *
		 * @param array $field Field array.
		 */
		public function output_field( array $field ) {
			$this->enqueue_dependencies( $field );

			if ( ! isset( $field['class'] ) ) {
				$field['class'] = '';
			}

			$field['class'] .= ' tabbed';

			echo '<div class="redux-tab-field">';

			if ( ! empty( $field['title'] ) ) {
				echo '<div class="redux-field-title">';
				echo '<h4>' . wp_kses_post( $field['title'] ) . '</h4>';

				if ( ! empty( $field['subtitle'] ) ) {
					echo '<div class="redux-field-subtitle">' . wp_kses_post( $field['subtitle'] ) . '</div>';
				}

				echo '</div>';
			}

			$orig_field_id = $field['id'];

			$field['name']   = $this->parent->args['opt_name'] . '[' . $orig_field_id . ']';
			$field['class'] .= ' in-tabbed';

			if ( isset( $field['options'] ) ) {

				// Sorter data filter.
				if ( 'sorter' === $field['type'] && ! empty( $field['data'] ) && is_array( $field['data'] ) ) {
					if ( ! isset( $field['args'] ) ) {
						$field['args'] = array();
					}

					foreach ( $field['data'] as $key => $data ) {
						if ( ! isset( $field['args'][ $key ] ) ) {
							$field['args'][ $key ] = array();
						}
						$field['options'][ $key ] = $this->parent->get_wordpress_data( $data, $field['args'][ $key ] );
					}
				}
			}

			$default = $field['default'] ?? '';

			$value = empty( $this->parent->options[ $orig_field_id ] ) ? $default : $this->parent->options[ $orig_field_id ];

			$this->parent->render_class->field_input( $field, $value );

			echo '<div class="clear"></div>';
			echo '</div>';
		}

		/**
		 * Localize.
		 *
		 * @param array  $field Field.
		 * @param string $value Value.
		 *
		 * @return void
		 */
		public function localize( array $field, string $value = '' ) {
			ob_start();

			foreach ( $field['tabs'] as $f ) {
				foreach ( $f['fields'] as $field ) {
					$this->output_field( $field );
				}
			}

			ob_end_clean();
		}

		/**
		 * Enqueue Deps.
		 *
		 * @param array $field Field.
		 */
		private function enqueue_dependencies( array $field ) {
			$field_type = $field['type'];

			$field_class = 'Redux_' . $field_type;

			if ( ! class_exists( $field_class ) ) {
				$field_type = str_replace( '_', '-', $field_type );

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$class_file = apply_filters( 'redux-typeclass-load', ReduxFramework::$_dir . 'inc/fields/' . $field_type . '/class-redux-' . $field_type . '.php', $field_class );

				if ( file_exists( $class_file ) ) {
					require_once $class_file;
				}
			}

			if ( class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) {
				$enqueue = new $field_class( '', '', $this->parent );
				$enqueue->enqueue();
			}

			if ( class_exists( $field_class ) && method_exists( $field_class, 'localize' ) ) {

				$enqueue = new $field_class( '', '', $this->parent );

				$data = $enqueue->localize( $field );

				$this->parent->enqueue_class->localize_data[ $field['type'] ][ $field['id'] ] = $data;
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since ReduxFramework 0.0.4
		 */
		public function enqueue() {
			wp_enqueue_script(
				'redux-field-tabbed',
				Redux_Core::$url . 'inc/extensions/tabbed/tabbed/redux-tabbed' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'redux-js' ),
				Redux_Extension_Tabbed::$version,
				true
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-tabbed',
					Redux_Core::$url . 'inc/extensions/tabbed/tabbed/redux-tabbed.css',
					array(),
					Redux_Extension_Tabbed::$version
				);
			}
		}
	}
}
