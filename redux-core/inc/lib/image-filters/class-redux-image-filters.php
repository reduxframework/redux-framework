<?php
/**
 * Redux Image Filters Class
 *
 * @class Redux_Includes
 * @version 4.1.30
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Image_Filters' ) ) {

	/**
	 * Class Redux_Image_Filters
	 */
	class Redux_Image_Filters {

		/**
		 * Render preview.
		 *
		 * @param array $data Data.
		 *
		 * @return string
		 */
		public static function render( array $data ): string {
			extract( $data ); // phpcs:ignore WordPress.PHP.DontExtract

			$output = '';

			$filter_arr = array(
				'grayscale',
				'blur',
				'sepia',
				'saturate',
				'opacity',
				'brightness',
				'contrast',
				'hue-rotate',
				'invert',
			);

			// Make an array of in use filters.
			$in_use_filters = array();

			foreach ( $filter_arr as $filter ) {
				if ( $field['filter'][ $filter ] ) {
					$in_use_filters[] = $filter;
				}
			}

			$filters = rawurlencode( wp_json_encode( $in_use_filters ) );

			$output .= '<div class="redux-' . $mode . '-filter-container" data-filters="' . $filters . '">';
			$output .= '<div class="container-label">' . esc_html__( 'Filters', 'redux-framework' ) . '</div>';

			foreach ( $in_use_filters as $filter ) {
				$step = 1;
				$unit = self::get_filter_unit( $filter );

				if ( 'grayscale' === $filter || 'invert' === $filter ) {
					$min = 0;
					$max = 100;
				} elseif ( 'blur' === $filter ) {
					$min = 0;
					$max = 30;
				} elseif ( 'sepia' === $filter || 'saturate' === $filter || 'opacity' === $filter ) {
					$min  = 0;
					$max  = 1;
					$step = .01;
				} elseif ( 'brightness' === $filter || 'contrast' === $filter ) {
					$min = 0;
					$max = 200;
				} elseif ( 'hue-rotate' === $filter ) {
					$min = 0;
					$max = 360;
				}

				$disabled = 'pro-disabled';
				if ( $value['filter'][ $filter ]['checked'] ) {
					$disabled = '';
				}

				$output .= '<div class="filter filter-' . $filter . '">';
				$output .= '<label for="' . esc_attr( $field['id'] ) . '-' . $filter . '" class="' . $disabled . '">';
				$output .= '<input type="checkbox" id="' . esc_attr( $field['id'] ) . '-' . $filter . '" class="checkbox" value="1"' . checked( $value['filter'][ $filter ]['checked'], '1', false ) . '/>';
				$output .= '<input type="hidden" data-val="1" value="' . esc_attr( $value['filter'][ $filter ]['checked'] ) . '" class="checkbox-check" name="' . esc_attr( $field['name'] . $field['name_suffix'] ) . '[filter][' . $filter . '][checked]"/>';
				$output .= ucfirst( $filter ) . ': ';
				$output .= '<span class="filter-value"><strong>' . $value['filter'][ $filter ]['value'] . $unit . '</strong></span>';
				$output .= '</label>';

				$output .= '<div ';
				$output .= 'class="redux-' . $mode . '-slider redux-' . $mode . '-filter redux-filter redux-filter-' . $filter . esc_attr( $field['class'] ) . '"';
				$output .= 'id="' . esc_attr( $field['id'] ) . '"';
				$output .= 'data-id="' . esc_attr( $field['id'] . '-' . $filter ) . '"';
				$output .= 'data-min="' . $min . '"';
				$output .= 'data-max="' . $max . '"';
				$output .= 'data-step="' . $step . '"';
				$output .= 'data-rtl="' . esc_attr( is_rtl() ) . '"';
				$output .= 'data-unit="' . $unit . '"';
				$output .= 'data-default = "' . esc_attr( $value['filter'][ $filter ]['value'] ) . '" ';
				$output .= disabled( filter_var( $value['filter'][ $filter ]['checked'], FILTER_VALIDATE_BOOLEAN ), false, false );
				$output .= '>';
				$output .= '</div>';

				if ( '&deg;' === $unit ) {
					$unit = 'deg';
				}

				$output .= '<input ';
				$output .= 'type="hidden"';
				$output .= 'id="redux-slider-value-' . esc_attr( $field['id'] ) . '-' . $filter . '"';
				$output .= 'class="' . $mode . '-filter-' . $filter . '"';
				$output .= 'name="' . esc_attr( $field['name'] . $field['name_suffix'] ) . '[filter][' . $filter . '][value]"';
				$output .= 'value="' . esc_attr( $value['filter'][ $filter ]['value'] ) . '"';
				$output .= 'data-id="' . esc_attr( $field['id'] ) . '"';
				$output .= 'data-unit="' . $unit . '"';
				$output .= '/>';
				$output .= '</div>';
			}

			$output .= '</div>';

			return $output;
		}

		/**
		 * Get filter unit.
		 *
		 * @param string $filter Filter type.
		 *
		 * @return string
		 */
		public static function get_filter_unit( string $filter ): string {
			if ( 'grayscale' === $filter || 'invert' === $filter || 'brightness' === $filter || 'contrast' === $filter ) {
				return '%';
			} elseif ( 'blur' === $filter ) {
				return 'px';
			} elseif ( 'hue-rotate' === $filter ) {
				return '&deg;';
			} else {
				return '';
			}
		}

		/**
		 * Enqueue support files.
		 *
		 * @param bool $filters_enabled Filtered enabled bit.
		 */
		public static function enqueue( bool $filters_enabled ) {
			$min = Redux_Functions::is_min();

			if ( $filters_enabled ) {
				if ( ! wp_style_is( 'redux-nouislider' ) ) {
					wp_enqueue_style(
						'redux-nouislider',
						Redux_Core::$url . 'assets/css/vendor/nouislider/redux.jquery.nouislider.css',
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
					'redux-image-filters',
					Redux_Core::$url . 'inc/lib/image-filters/image-filters' . $min . '.js',
					array( 'jquery' ),
					Redux_Core::$version,
					true
				);

				wp_enqueue_style(
					'redux-image-filters',
					Redux_Core::$url . 'inc/lib/image-filters/image-filters.css',
					array(),
					Redux_Core::$version
				);
			}
		}
	}
}
