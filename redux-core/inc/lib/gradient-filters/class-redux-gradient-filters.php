<?php
/**
 * Redux Gradient Filters Class
 *
 * @class Redux_Gradient_Filters
 * @version 4.1.30
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Gradient_Filters' ) ) {

	/**
	 * Class Redux_Gradient_Filters
	 */
	class Redux_Gradient_Filters {

		/**
		 * Render select boxes.
		 *
		 * @param array $data Data.
		 *
		 * @return string
		 */
		public static function render_select( array $data ): string {
			extract( $data ); // phpcs:ignore WordPress.PHP.DontExtract

			$output = '';

			if ( $field['gradient-type'] ) {
				$select2_default = array(
					'width'                   => 'resolve',
					'allowClear'              => false,
					'theme'                   => 'default',
					'minimumResultsForSearch' => 3,
				);

				$select2_default = Redux_Functions::sanitize_camel_case_array_keys( $select2_default );

				$select2_data = Redux_Functions::create_data_string( $select2_default );

				$output .= '<div class="redux-gradient-type">';
				$output .= '<strong>' . esc_html__( 'Gradient Type ', 'redux-framework' ) . '</strong>&nbsp;';
				$output .= '<select ';
				$output .= 'class="redux-gradient-select select2-container"';
				$output .= 'data-placeholder="' . esc_attr__( 'Type', 'redux-framework' ) . '" ' . $select2_data . ' ';
				$output .= 'name="' . esc_attr( $field['name'] . $field['name_suffix'] ) . '[gradient-type]"';
				$output .= 'data-value="' . esc_attr( $value['gradient-type'] ) . '"';
				$output .= 'data-id="' . esc_attr( $field['id'] ) . '">';

				$arr = array(
					esc_html__( 'linear', 'redux-framework' ),
					esc_html__( 'radial', 'redux-framework' ),
				);

				foreach ( $arr as $v ) {
					$output .= '<option value="' . esc_attr( $v ) . '" ' . selected( $value['gradient-type'], $v, false ) . '>' . esc_html( ucfirst( $v ) ) . '</option>';
				}

				$output .= '</select>';
				$output .= '</div>';
			}

			return $output;
		}

		/**
		 * Render sliders.
		 *
		 * @param array $data Data.
		 *
		 * @return string
		 */
		public static function render_sliders( array $data ): string {
			extract( $data ); // phpcs:ignore WordPress.PHP.DontExtract

			$output = '';

			if ( $field['gradient-reach'] ) {
				$output .= '<div class="slider-from-reach">';
				$output .= '<div class="label">' . esc_html__( 'From Reach ', 'redux-framework' ) . ':  <strong>' . esc_html( $value['gradient-reach']['from'] ) . '%</strong></div>';
				$output .= '<div ';
				$output .= 'class="redux-gradient-slider redux-color-gradient redux-gradient-from-reach color-gradient-input ' . esc_attr( $field['class'] ) . '"';
				$output .= 'id="' . esc_attr( $field['id'] ) . '"';
				$output .= 'data-id="' . esc_attr( $field['id'] ) . '-from"';
				$output .= 'data-min="0"';
				$output .= 'data-max="100"';
				$output .= 'data-step="1"';
				$output .= 'data-rtl="' . esc_attr( is_rtl() ) . '"';
				$output .= 'data-label="' . esc_attr__( 'From Reach', 'redux-framework' ) . '"';
				$output .= 'data-default = "' . esc_attr( $value['gradient-reach']['from'] ) . '">';
				$output .= '</div>';
				$output .= '<input ';
				$output .= 'type="hidden"';
				$output .= 'id="redux-slider-value-' . esc_attr( $field['id'] ) . '-from"';
				$output .= 'class="color-gradient-reach-from"';
				$output .= 'name="' . esc_attr( $field['name'] . $field['name_suffix'] ) . '[gradient-reach][from]"';
				$output .= 'value="' . esc_attr( $value['gradient-reach']['from'] ) . '"';
				$output .= 'data-id="' . esc_attr( $field['id'] ) . '"';
				$output .= '/>';
				$output .= '</div>';

				$output .= '<div class="slider-to-reach">';
				$output .= '<div class="label">' . esc_html__( 'To Reach', 'redux-framework' ) . ':  <strong>' . esc_html( $value['gradient-reach']['to'] ) . '%</strong></div>';
				$output .= '<div ';
				$output .= 'class="redux-gradient-slider redux-color-gradient redux-gradient-reach-to color-gradient-input ' . esc_attr( $field['class'] ) . '"';
				$output .= 'id="' . esc_attr( $field['id'] ) . '"';
				$output .= 'data-id="' . esc_attr( $field['id'] ) . '-to"';
				$output .= 'data-min="0"';
				$output .= 'data-max="100"';
				$output .= 'data-step="1"';
				$output .= 'data-rtl="' . esc_attr( is_rtl() ) . '"';
				$output .= 'data-label="' . esc_attr__( 'To Reach', 'redux-framework' ) . '"';
				$output .= 'data-default = "' . esc_attr( $value['gradient-reach']['to'] ) . '">';
				$output .= '</div>';
				$output .= '<input ';
				$output .= 'type="hidden"';
				$output .= 'id="redux-slider-value-' . esc_attr( $field['id'] ) . '-to"';
				$output .= 'class="color-gradient-reach-to"';
				$output .= 'name="' . esc_attr( $field['name'] . $field['name_suffix'] ) . '[gradient-reach][to]"';
				$output .= 'value="' . esc_attr( $value['gradient-reach']['to'] ) . '"';
				$output .= 'data-id="' . esc_attr( $field['id'] ) . '"';
				$output .= '/>';
				$output .= '</div>';
			}

			if ( $field['gradient-angle'] ) {
				$style = '';
				if ( 'radial' === $value['gradient-type'] ) {
					$style = 'style="display:none;"';
				}

				$output .= '<div class="slider-gradient-angle" ' . $style . '>';
				$output .= '<div class="label">' . esc_html__( 'Gradient Angle', 'redux-framework' ) . ':  <strong>' . $value['gradient-angle'] . '&deg;</strong></div>';
				$output .= '<div ';
				$output .= 'class="redux-gradient-slider redux-color-gradient redux-gradient-angle color-gradient-input ' . esc_attr( $field['class'] ) . '"';
				$output .= 'id="' . esc_attr( $field['id'] ) . '"';
				$output .= 'data-id="' . esc_attr( $field['id'] ) . '-angle"';
				$output .= 'data-min="0"';
				$output .= 'data-max="360"';
				$output .= 'data-step="1"';
				$output .= 'data-rtl="' . esc_attr( is_rtl() ) . '"';
				$output .= 'data-label="' . esc_attr__( 'Gradient Angle', 'redux-framework' ) . '"';
				$output .= 'data-default = "' . esc_attr( $value['gradient-angle'] ) . '">';
				$output .= '</div>';
				$output .= '<input ';
				$output .= 'type="hidden"';
				$output .= 'id="redux-slider-value-' . esc_attr( $field['id'] ) . '-angle"';
				$output .= 'class="color-gradient-angle"';
				$output .= 'name="' . esc_attr( $field['name'] . $field['name_suffix'] ) . '[gradient-angle]"';
				$output .= 'value="' . esc_attr( $value['gradient-angle'] ) . '"';
				$output .= 'data-id="' . esc_attr( $field['id'] ) . '"';
				$output .= '/>';
				$output .= '</div>';
			}

			return $output;
		}

		/**
		 * Render preview.
		 *
		 * @param array $data Data.
		 *
		 * @return string
		 */
		public static function render_preview( array $data ): string {
			extract( $data ); // phpcs:ignore WordPress.PHP.DontExtract

			$output = '';

			$css = '';
			if ( false === $field['preview'] ) {
				$css .= 'display:none;';
			}

			$css .= self::get_output( $value );

			$css .= 'height: ' . $field['preview_height'] . ';';

			$output .= '<div class="redux-gradient-preview" style="' . esc_attr( $css ) . '"></div>';

			return $output;
		}

		/**
		 * Get CSS output.
		 *
		 * @param mixed $data Data.
		 *
		 * @return string
		 */
		public static function get_output( $data ): string {
			if ( ! is_array( $data ) ) {
				return $data;
			}

			$angle = $data['gradient-angle'];

			$w3c_angle = abs( $angle - 450 ) % 360;

			$colors = $data['from'] . ' ' . $data['gradient-reach']['from'] . '%, ' . $data['to'] . ' ' . $data['gradient-reach']['to'] . '%)';

			if ( 'linear' === $data['gradient-type'] ) {
				$result_w3c = 'linear-gradient(' . $w3c_angle . 'deg,' . $colors;
				$result     = 'linear-gradient(' . $angle . 'deg,' . $colors;
			} else {
				$result_w3c = 'radial-gradient(center, ellipse cover, ' . $colors;
				$result     = 'radial-gradient(center, ellipse cover,' . $colors;
			}

			return 'background:' . $result_w3c . ';background:-moz-' . $result . ';background:-webkit-' . $result . ';background:-o-' . $result . ';background:-ms-' . $result;
		}

		/**
		 * Enqueue support files.
		 *
		 * @param array $field           Field array.
		 * @param bool  $filters_enabled Enable filter bit.
		 */
		public static function enqueue( array $field, bool $filters_enabled ) {
			$min = Redux_Functions::is_min();

			if ( $filters_enabled ) {
				if ( $field['gradient-type'] ) {
					if ( ! wp_style_is( 'select2-css' ) ) {
						wp_enqueue_style( 'select2-css' );
					}

					if ( ! wp_script_is( 'select2-js' ) ) {
						wp_enqueue_script( 'select2-js' );
					}
				}

				if ( ! wp_style_is( 'redux-nouislider' ) ) {
					wp_enqueue_style(
						'redux-nouislider',
						Redux_Core::$url . 'assets/css/vendor/nouislider' . $min . '.css',
						array(),
						'5.0.0'
					);

					wp_enqueue_script(
						'redux-nouislider',
						Redux_Core::$url . 'assets/js/vendor/nouislider/redux.jquery.nouislider' . $min . '.js',
						array( 'jquery' ),
						'5.0.0',
						true
					);
				}

				wp_enqueue_script(
					'redux-gradient-filters',
					Redux_Core::$url . 'inc/lib/gradient-filters/gradient-filters' . Redux_Functions::isMin() . '.js',
					array( 'jquery' ),
					Redux_Core::$version,
					true
				);

				wp_enqueue_style(
					'redux-gradient-filters',
					Redux_Core::$url . 'inc/lib/gradient-filters/gradient-filters.css',
					array(),
					Redux_Core::$version
				);
			}
		}
	}
}
