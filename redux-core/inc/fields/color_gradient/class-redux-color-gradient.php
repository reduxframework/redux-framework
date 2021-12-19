<?php
/**
 * Color Gradient Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Color_Gradient', false ) ) {

	/**
	 * Main Redux_color_gradient class
	 *
	 * @since       1.0.0
	 */
	class Redux_Color_Gradient extends Redux_Field {

		/**
		 * Filters enabled flag.
		 *
		 * @var bool
		 */
		private $filters_enabled = false;

		/**
		 * Redux_Field constructor.
		 *
		 * @param array  $field  Field array.
		 * @param string $value  Field values.
		 * @param null   $parent ReduxFramework object pointer.
		 *
		 * @throws ReflectionException Reflection.
		 */
		public function __construct( $field = array(), $value = null, $parent = null ) { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod
			parent::__construct( $field, $value, $parent );
		}

		/**
		 * Set field and value defaults.
		 */
		public function set_defaults() {
			// No errors please.
			$defaults = array(
				'from'           => '',
				'to'             => '',
				'gradient-type'  => 'linear',
				'gradient-angle' => 0,
				'gradient-reach' => array(
					'from' => 0,
					'to'   => 100,
				),
			);

			$this->value = Redux_Functions::parse_args( $this->value, $defaults );

			$defaults = array(
				'preview'        => false,
				'preview_height' => '150px',
				'transparent'    => true,
				'gradient-type'  => false,
				'gradient-reach' => false,
				'gradient-angle' => false,
			);

			$this->field = wp_parse_args( $this->field, $defaults );

			include_once Redux_Core::$dir . 'inc/lib/gradient-filters/class-redux-gradient-filters.php';

			if ( $this->field['gradient-reach'] || $this->field['gradient-angle'] || $this->field['gradient-type'] ) {
				include_once Redux_Core::$dir . 'inc/lib/gradient-filters/class-redux-gradient-filters.php';
				$this->filters_enabled = true;
			}
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
			$data = array(
				'field' => $this->field,
				'value' => $this->value,
			);

			// Escaping done in function.
			// phpcs:ignore WordPress.Security.EscapeOutput
			echo Redux_Gradient_Filters::render_select( $data );

			$mode_arr = array(
				'from',
				'to',
			);

			foreach ( $mode_arr as $mode ) {
				$uc_mode = ucfirst( $mode );

				echo '<div class="colorGradient ' . esc_html( $mode ) . 'Label">';
				echo '<strong>' . esc_html( $uc_mode . ' ' ) . '</strong>&nbsp;';
				echo '<input ';
				echo 'data-id="' . esc_attr( $this->field['id'] ) . '"';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $mode ) . '"';
				echo 'name="' . esc_attr( $this->field['name'] ) . esc_attr( $this->field['name_suffix'] ) . '[' . esc_attr( $mode ) . ']"';
				echo 'value="' . esc_attr( $this->value[ $mode ] ) . '"';
				echo 'class="color-picker redux-color redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'data-default-color="' . esc_attr( $this->field['default'][ $mode ] ) . '"';

				// Escaping done in function.
				// phpcs:ignore WordPress.Security.EscapeOutput
				echo Redux_Functions_Ex::output_alpha_data( $data );

				echo '>';

				echo '<input type="hidden" class="redux-saved-color" id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $mode ) . '-saved-color" value="">';

				if ( ! isset( $this->field['transparent'] ) || false !== $this->field['transparent'] ) {
					$trans_checked = '';

					if ( 'transparent' === $this->value[ $mode ] ) {
						$trans_checked = ' checked="checked"';
					}

					echo '<label for="' . esc_attr( $this->field['id'] ) . '-' . esc_html( $mode ) . '-transparency" class="color-transparency-check">';
					echo '<input type="checkbox" class="checkbox color-transparency ' . esc_attr( $this->field['class'] ) . '" id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $mode ) . '-transparency" data-id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $mode ) . '" value="1"' . esc_html( $trans_checked ) . '> ' . esc_html__( 'Transparent', 'redux-framework' );
					echo '</label>';
				}

				echo '</div>';
			}

			// Escaping done in function.
			// phpcs:ignore WordPress.Security.EscapeOutput
			echo Redux_Gradient_Filters::render_preview( $data );

			// Escaping done in function.
			// phpcs:ignore WordPress.Security.EscapeOutput
			echo Redux_Gradient_Filters::render_sliders( $data );
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
			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script(
				'redux-field-color-gradient-js',
				Redux_Core::$url . 'inc/fields/color_gradient/redux-color-gradient' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'wp-color-picker', 'redux-js' ),
				$this->timestamp,
				true
			);

			$min = Redux_Functions::isMin();

			Redux_Gradient_Filters::enqueue( $this->field, $this->filters_enabled );

			if ( isset( $this->field['color_alpha'] ) && ( $this->field['color_alpha'] || ( $this->field['color_alpha']['from'] || $this->field['color_alpha']['to'] ) ) ) {
				if ( ! wp_script_is( 'redux-wp-color-picker-alpha-js' ) ) {
					wp_enqueue_script( 'redux-wp-color-picker-alpha-js' );
				}
			}

			if ( $this->filters_enabled ) {
				wp_enqueue_script(
					'redux-field-color-gradient-js',
					Redux_Core::$url . 'inc/fields/color_gradient/redux-color-gradient' . $min . '.js',
					array( 'jquery', 'wp-color-picker' ),
					Redux_Core::$version,
					true
				);
			}

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style( 'redux-color-picker-css' );

				wp_enqueue_style(
					'redux-field-color_gradient-css',
					Redux_Core::$url . 'inc/fields/color_gradient/redux-color-gradient.css',
					array(),
					$this->timestamp
				);
			}
		}

		/**
		 * Compile CSS styling for output.
		 *
		 * @param string $data CSS data.
		 *
		 * @return string
		 */
		public function css_style( $data ): string {
			return Redux_Gradient_Filters::get_output( $data );
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

class_alias( 'Redux_Color_Gradient', 'ReduxFramework_Color_Gradient' );
