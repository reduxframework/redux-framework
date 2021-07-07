<?php
/**
 * Bow Shadow.
 *
 * @package     ReduxPro
 * @subpackage  Redux_Box_Shadow
 * @author      Kevin Provance (kprovance)
 * @version     1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Box_Shadow', false ) ) {

	/**
	 * Main Redux_Box_Shadow class
	 *
	 * @since       4.0.0
	 */
	class Redux_Box_Shadow extends Redux_Field {

		/**
		 * Set defaults.
		 */
		public function set_defaults() {

			$defaults = array(
				'inset-shadow'  => true,
				'drop-shadow'   => true,
				'preview-color' => '#f1f1f1',
			);

			$this->field = Redux_Functions::parse_args( $this->field, $defaults );

			$defaults = array(
				'inset'        => true,
				'drop'         => true,
				'inset-shadow' => array(
					'checked'    => false,
					'color'      => '#ABABAB',
					'horizontal' => 0,
					'vertical'   => 0,
					'blur'       => 10,
					'spread'     => 0,
				),
				'drop-shadow'  => array(
					'checked'    => true,
					'color'      => '#dddddd',
					'horizontal' => 5,
					'vertical'   => 5,
					'blur'       => 5,
					'spread'     => 1,
				),
			);

			$this->value = Redux_Functions::parse_args( $this->value, $defaults );
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
			$shadow_arr = array(
				'inset',
				'drop',
			);

			echo '<div class="box-shadow-inset">';
			echo '<div class="box-shadow-controls row">';

			foreach ( $shadow_arr as $idx => $shadow_type ) {
				if ( $this->field[ $shadow_type . '-shadow' ] ) {
					$disabled = ' pro-disabled';

					if ( $this->value[ $shadow_type . '-shadow' ]['checked'] ) {
						$disabled = '';
					}

					$slider_disable = disabled( filter_var( $this->value[ $shadow_type . '-shadow' ]['checked'], FILTER_VALIDATE_BOOLEAN ), false, false );

					echo '<div class="col-2 shadow-' . esc_attr( $shadow_type ) . ' " data-shadow="' . esc_attr( $shadow_type ) . '">';
					echo '<ul>';
					echo '<li>';
					echo '<label for="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $shadow_type ) . '-shadow" class="' . esc_attr( $disabled ) . '">';
					echo '<input type="checkbox" id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $shadow_type ) . '-shadow" class="checkbox" value="1"' . checked( $this->value[ $shadow_type . '-shadow' ]['checked'], '1', false ) . '/>';
					echo '<input type="hidden" data-val="1" value="' . esc_attr( $this->value[ $shadow_type . '-shadow' ]['checked'] ) . '" class="checkbox-check" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[' . esc_attr( $shadow_type ) . '-shadow][checked]"/>';
					echo esc_html( ucfirst( $shadow_type ) ) . ' ' . esc_html__( 'Shadow', 'redux-framework' );
					echo '</label>';
					echo '</li>';
					echo '<li>';

					$def_color = $this->field['default'][ $shadow_type . '-shadow' ]['color'] ?? '';

					echo '<input ';
					echo 'data-id="' . esc_attr( $this->field['id'] ) . '"';
					echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[' . esc_attr( $shadow_type ) . '-shadow][color]"';
					echo 'id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $shadow_type ) . '-color"';
					echo 'class="color-picker redux-color redux-box-shadow-' . esc_attr( $shadow_type ) . '-input redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
					echo 'type="text" value="' . esc_attr( $this->value[ $shadow_type . '-shadow' ]['color'] ) . '"';
					echo 'data-default-color="' . esc_attr( $def_color ) . '"';

					$data = array(
						'field' => $this->field,
						'index' => $shadow_type . '-shadow',
					);

					echo '/>';
					echo '</li>';
					echo '<li>';
					echo '<div class="slider-' . esc_attr( $shadow_type ) . '-horizontal">';
					echo '<label>' . esc_html__( 'Horizontal Length ', 'redux-framework' ) . ':  <strong>' . esc_html( $this->value[ $shadow_type . '-shadow' ]['horizontal'] ) . 'px</strong></label>';
					echo '<div
                            class="redux-box-shadow-slider redux-box-shadow-' . esc_attr( $shadow_type ) . ' redux-' . esc_attr( $shadow_type ) . '-horizontal ' . esc_attr( $shadow_type ) . '-horizontal-input ' . esc_attr( $this->field['class'] ) . '"
                            id="' . esc_attr( $this->field['id'] ) . '"
                            data-id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $shadow_type ) . '-horizontal"
                            data-min="-50"
                            data-max="50"
                            data-step="1"
                            data-rtl="' . esc_attr( is_rtl() ) . '"
                            data-label="' . esc_attr__( 'Horizontal Length', 'redux-framework' ) . '"
                            data-default = "' . esc_attr( $this->value[ $shadow_type . '-shadow' ]['horizontal'] ) . '" ' . esc_html( $slider_disable ) . '>
                        </div>';
					echo '<input
                                type="hidden"
                                id="redux-slider-value-' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $shadow_type ) . '-horizontal"
                                class="' . esc_attr( $shadow_type ) . '-horizontal"
                                name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[' . esc_attr( $shadow_type ) . '-shadow][horizontal]"
                                value="' . esc_attr( $this->value[ $shadow_type . '-shadow' ]['horizontal'] ) . '"
                                data-id="' . esc_attr( $this->field['id'] ) . '"
                            />';
					echo '</div>';
					echo '</li>';
					echo '<li>';
					echo '<div class="slider-' . esc_attr( $shadow_type ) . '-vertical">';
					echo '<label>' . esc_html__( 'Vertical Length ', 'redux-framework' ) . ':  <strong>' . esc_html( $this->value[ $shadow_type . '-shadow' ]['vertical'] ) . 'px</strong></label>';
					echo '<div
                                class="redux-box-shadow-slider redux-box-shadow-' . esc_attr( $shadow_type ) . ' redux-' . esc_attr( $shadow_type ) . '-vertical ' . esc_attr( $shadow_type ) . '-vertical-input ' . esc_attr( $this->field['class'] ) . '"
                                id="' . esc_attr( $this->field['id'] ) . '"
                                data-id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $shadow_type ) . '-vertical"
                                data-min="-50"
                                data-max="50"
                                data-step="1"
                                data-rtl="' . esc_attr( is_rtl() ) . '"
                                data-label="' . esc_attr__( 'Vertical Length', 'redux-framework' ) . '"
                                data-default = "' . esc_attr( $this->value[ $shadow_type . '-shadow' ]['vertical'] ) . '" ' . esc_html( $slider_disable ) . '>
                            </div>';
					echo '<input
                                type="hidden"
                                id="redux-slider-value-' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $shadow_type ) . '-vertical"
                                class="' . esc_attr( $shadow_type ) . '-vertical"
                                name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[' . esc_attr( $shadow_type ) . '-shadow][vertical]"
                                value="' . esc_attr( $this->value[ $shadow_type . '-shadow' ]['vertical'] ) . '"
                                data-id="' . esc_attr( $this->field['id'] ) . '"
                            />';
					echo '</div>';
					echo '</li>';
					echo '<li>';
					echo '<div class="slider-' . esc_attr( $shadow_type ) . '-blur">';
					echo '<label>' . esc_html__( 'Blur Radius ', 'redux-framework' ) . ':  <strong>' . esc_html( $this->value[ $shadow_type . '-shadow' ]['blur'] ) . 'px</strong></label>';
					echo '<div
                                class="redux-box-shadow-slider redux-box-shadow-' . esc_attr( $shadow_type ) . ' redux-' . esc_attr( $shadow_type ) . '-blur ' . esc_attr( $shadow_type ) . '-blur-input ' . esc_attr( $this->field['class'] ) . '"
                                id="' . esc_attr( $this->field['id'] ) . '"
                                data-id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $shadow_type ) . '-blur"
                                data-min="0"
                                data-max="100"
                                data-step="1"
                                data-rtl="' . esc_attr( is_rtl() ) . '"
                                data-label="' . esc_attr__( 'Blur Radius', 'redux-framework' ) . '"
                                data-default = "' . esc_attr( $this->value[ $shadow_type . '-shadow' ]['blur'] ) . '" ' . esc_html( $slider_disable ) . '>
                            </div>';
					echo '<input
                                type="hidden"
                                id="redux-slider-value-' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $shadow_type ) . '-blur"
                                class="' . esc_attr( $shadow_type ) . '-blur"
                                name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[' . esc_attr( $shadow_type ) . '-shadow][blur]"
                                value="' . esc_attr( $this->value[ $shadow_type . '-shadow' ]['blur'] ) . '"
                                data-id="' . esc_attr( $this->field['id'] ) . '"
                            />';
					echo '</div>';
					echo '</li>';
					echo '<li>';
					echo '<div class="slider-' . esc_attr( $shadow_type ) . '-spread">';
					echo '<label>' . esc_html__( 'Spread ', 'redux-framework' ) . ':  <strong>' . esc_html( $this->value[ $shadow_type . '-shadow' ]['spread'] ) . 'px</strong></label>';
					echo '<div
                                class="redux-box-shadow-slider redux-box-shadow-' . esc_attr( $shadow_type ) . ' redux-' . esc_attr( $shadow_type ) . '-spread ' . esc_attr( $shadow_type ) . '-spread-input ' . esc_attr( $this->field['class'] ) . '"
                                id="' . esc_attr( $this->field['id'] ) . '"
                                data-id="' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $shadow_type ) . '-spread"
                                data-min="-100"
                                data-max="100"
                                data-step="1"
                                data-rtl="' . esc_attr( is_rtl() ) . '"
                                data-label="' . esc_attr__( 'Spread', 'redux-framework' ) . '"
                                data-default = "' . esc_attr( $this->value[ $shadow_type . '-shadow' ]['spread'] ) . '" ' . esc_html( $slider_disable ) . '>
                            </div>';
					echo '<input
                                type="hidden"
                                id="redux-slider-value-' . esc_attr( $this->field['id'] ) . '-' . esc_attr( $shadow_type ) . '-spread"
                                class="' . esc_attr( $shadow_type ) . '-spread"
                                name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[' . esc_attr( $shadow_type ) . '-shadow][spread]"
                                value="' . esc_attr( $this->value[ $shadow_type . '-shadow' ]['spread'] ) . '"
                                data-id="' . esc_attr( $this->field['id'] ) . '"
                            />';
					echo '</div>';
					echo '</li>';
					echo '</ul>';
					echo '</div>';
				}
			}

			$css  = $this->css_style( $this->value );
			$css .= 'background:' . esc_html( $this->field['preview-color'] );

			echo '</div>';
			echo '<div class="" id="shadow-result" style="' . $css . '"></div>'; // WPCS: XSS ok.
			echo '</div>';
		}

		/**
		 * Compile CSS output.
		 *
		 * @param string $data Data.
		 *
		 * @return string
		 */
		public function css_style( $data ): string {
			$css = '';

			if ( $this->field['inset-shadow'] ) {
				$inset = $data['inset-shadow'];

				if ( filter_var( $inset['checked'], FILTER_VALIDATE_BOOLEAN ) ) {
					$h     = $inset['horizontal'];
					$v     = $inset['vertical'];
					$b     = $inset['blur'];
					$s     = $inset['spread'];
					$color = $inset['color'];

					$css .= 'inset ' . $h . 'px ' . $v . 'px ' . $b . 'px ' . $s . 'px ' . $color;
				}
			}

			if ( $this->field['drop-shadow'] ) {
				$drop = $data['drop-shadow'];

				if ( filter_var( $drop['checked'], FILTER_VALIDATE_BOOLEAN ) ) {
					$h     = $drop['horizontal'];
					$v     = $drop['vertical'];
					$b     = $drop['blur'];
					$s     = $drop['spread'];
					$color = $drop['color'];

					if ( '' !== $css ) {
						$css .= ',';
					}

					$css .= $h . 'px ' . $v . 'px ' . $b . 'px ' . $s . 'px ' . $color;
				}
			}

			if ( '' !== $css ) {
				$css = 'box-shadow:' . $css . ';-webkit-box-shadow:' . $css . ';-moz-box-shadow:' . $css . ';-o-box-shadow:' . $css . ';';
			}

			return $css;
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
			$min = Redux_Functions::is_min();

			if ( ! wp_style_is( 'wp-color-picker' ) ) {
				wp_enqueue_style( 'wp-color-picker' );
			}

			$dep_array = array( 'jquery', 'wp-color-picker', 'redux-js' );

			if ( isset( $this->field['color_alpha'] ) && ( $this->field['color_alpha'] || ( $this->field['color_alpha']['inset-shadow'] || $this->field['color_alpha']['drop-shadow'] ) ) ) {
				wp_enqueue_script( 'redux-wp-color-picker-alpha-js' );
			}

			wp_enqueue_script(
				'redux-field-box-shadow-js',
				Redux_Core::$url . 'inc/fields/box_shadow/redux-box-shadow' . $min . '.js',
				$dep_array,
				$this->timestamp,
				true
			);

			wp_enqueue_style(
				'redux-nouislider-css',
				Redux_Core::$url . 'assets/css/vendor/nouislider' . $min . '.css',
				array(),
				'5.0.0'
			);

			wp_enqueue_script(
				'redux-nouislider-js',
				Redux_Core::$url . 'assets/js/vendor/nouislider/redux.jquery.nouislider' . $min . '.js',
				array( 'jquery' ),
				'5.0.0',
				true
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-box-shadow-css',
					Redux_Core::$url . 'inc/fields/box_shadow/redux-box-shadow.css',
					array(),
					time()
				);

				wp_enqueue_style( 'redux-color-picker-css' );
			}
		}
	}
}
