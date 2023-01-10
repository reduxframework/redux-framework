<?php
/**
 * Typography Field
 *
 * @package     ReduxFramework/Fields
 * @author      Kevin Provance (kprovance) & Dovy Paukstys
 * @version     4.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Typography', false ) ) {

	/**
	 * Class Redux_Typography
	 */
	class Redux_Typography extends Redux_Field {

		/**
		 * Array of data for typography preview.
		 *
		 * @var array
		 */
		private $typography_preview = array();

		/**
		 *  Standard font array.
		 *
		 * @var array $std_fonts
		 */
		private $std_fonts = array(
			'Arial, Helvetica, sans-serif'            => 'Arial, Helvetica, sans-serif',
			'\'Arial Black\', Gadget, sans-serif'     => '\'Arial Black\', Gadget, sans-serif',
			'\'Bookman Old Style\', serif'            => '\'Bookman Old Style\', serif',
			'\'Comic Sans MS\', cursive'              => '\'Comic Sans MS\', cursive',
			'Courier, monospace'                      => 'Courier, monospace',
			'Garamond, serif'                         => 'Garamond, serif',
			'Georgia, serif'                          => 'Georgia, serif',
			'Impact, Charcoal, sans-serif'            => 'Impact, Charcoal, sans-serif',
			'\'Lucida Console\', Monaco, monospace'   => '\'Lucida Console\', Monaco, monospace',
			'\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif' => '\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif',
			'\'MS Sans Serif\', Geneva, sans-serif'   => '\'MS Sans Serif\', Geneva, sans-serif',
			'\'MS Serif\', \'New York\', sans-serif'  => '\'MS Serif\', \'New York\', sans-serif',
			'\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif' => '\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif',
			'Tahoma,Geneva, sans-serif'               => 'Tahoma, Geneva, sans-serif',
			'\'Times New Roman\', Times,serif'        => '\'Times New Roman\', Times, serif',
			'\'Trebuchet MS\', Helvetica, sans-serif' => '\'Trebuchet MS\', Helvetica, sans-serif',
			'Verdana, Geneva, sans-serif'             => 'Verdana, Geneva, sans-serif',
		);

		/**
		 * Default font weights.
		 *
		 * @var string[]
		 */
		private $default_font_weights = array(
			'400'       => 'Normal 400',
			'700'       => 'Bold 700',
			'400italic' => 'Normal 400 Italic',
			'700italic' => 'Bold 700 Italic',
		);

		/**
		 * User font array.
		 *
		 * @var bool $user_fonts
		 */
		private $user_fonts = true;

		/**
		 * Redux_Field constructor.
		 *
		 * @param array  $field  Field array.
		 * @param string $value  Field values.
		 * @param null   $parent ReduxFramework object pointer.
		 *
		 * @throws ReflectionException Exception.
		 */
		public function __construct( $field = array(), $value = null, $parent = null ) { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod
			parent::__construct( $field, $value, $parent );

			$this->parent = $parent;
			$this->field  = $field;
			$this->value  = $value;

			$this->set_defaults();

			$path_info = Redux_Helpers::path_info( __file__ );
			$this->dir = trailingslashit( dirname( $path_info['real_path'] ) );
			$this->url = trailingslashit( dirname( $path_info['url'] ) );

			$this->timestamp = Redux_Core::$version;
			if ( $parent->args['dev_mode'] ) {
				$this->timestamp .= '.' . time();
			}
		}

		/**
		 * Sets default values for field.
		 */
		public function set_defaults() {
			// Shim out old arg to new.
			if ( isset( $this->field['all_styles'] ) && ! empty( $this->field['all_styles'] ) ) {
				$this->field['all-styles'] = $this->field['all_styles'];
				unset( $this->field['all_styles'] );
			}

			$defaults = array(
				'font-family'             => true,
				'font-size'               => true,
				'font-weight'             => true,
				'font-style'              => true,
				'font-backup'             => false,
				'subsets'                 => true,
				'custom_fonts'            => true,
				'text-align'              => true,
				'text-transform'          => false,
				'font-variant'            => false,
				'text-decoration'         => false,
				'color'                   => true,
				'preview'                 => true,
				'line-height'             => true,
				'multi'                   => array(
					'subsets' => false,
					'weight'  => false,
				),
				'word-spacing'            => false,
				'letter-spacing'          => false,
				'google'                  => true,
				'font_family_clear'       => true,
				'allow_empty_line_height' => false,
				'margin-top'              => false,
				'margin-bottom'           => false,
				'text-shadow'             => false,
				'word-spacing-unit'       => '',
				'letter-spacing-unit'     => '',
				'font-size-unit'          => '',
				'margin-top-unit'         => '',
				'margin-bottom-unit'      => '',
			);

			$this->field = wp_parse_args( $this->field, $defaults );

			if ( isset( $this->field['color_alpha'] ) ) {
				if ( is_array( $this->field['color_alpha'] ) ) {
					$this->field['color_alpha']['color']        = $this->field['color_alpha']['color'] ?? false;
					$this->field['color_alpha']['shadow-color'] = $this->field['color_alpha']['shadow-color'] ?? false;
				} else {
					$mode                                       = $this->field['color_alpha'];
					$this->field['color_alpha']                 = array();
					$this->field['color_alpha']['color']        = $mode;
					$this->field['color_alpha']['shadow-color'] = $mode;
				}
			} else {
				$this->field['color_alpha']['color']        = false;
				$this->field['color_alpha']['shadow-color'] = false;
			}

			// Set value defaults.
			$defaults = array(
				'font-family'       => '',
				'font-options'      => '',
				'font-backup'       => '',
				'text-align'        => '',
				'text-transform'    => '',
				'font-variant'      => '',
				'text-decoration'   => '',
				'line-height'       => '',
				'word-spacing'      => '',
				'letter-spacing'    => '',
				'subsets'           => '',
				'google'            => false,
				'font-script'       => '',
				'font-weight'       => '',
				'font-style'        => '',
				'color'             => '',
				'font-size'         => '',
				'margin-top'        => '',
				'margin-bottom'     => '',
				'shadow-color'      => '#000000',
				'shadow-horizontal' => '1',
				'shadow-vertical'   => '1',
				'shadow-blur'       => '4',
			);

			$this->value = wp_parse_args( $this->value, $defaults );

			if ( empty( $this->field['units'] ) || ! in_array( $this->field['units'], Redux_Helpers::$array_units, true ) ) {
				$this->field['units'] = 'px';
			}

			// Get the Google array.
			$this->get_google_array();

			if ( empty( $this->field['fonts'] ) ) {
				$this->user_fonts     = false;
				$this->field['fonts'] = $this->std_fonts;
			}

			$this->field['weights'] = $this->field['weights'] ?? $this->default_font_weights;

			// Localize std fonts.
			$this->localize_std_fonts();
		}

		/**
		 * Localize font array
		 *
		 * @param array  $field Field array.
		 * @param string $value Value.
		 *
		 * @return array
		 */
		public function localize( array $field, string $value = '' ): array {
			$params = array();

			if ( true === $this->user_fonts && ! empty( $this->field['fonts'] ) ) {
				$params['std_font'] = $this->field['fonts'];
			}

			return $params;
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function render() {
			// Since fonts declared is CSS (@font-face) are not rendered in the preview,
			// they can be declared in a CSS file and passed here, so they DO display in
			// font preview.  Do NOT pass style.css in your theme, as that will mess up
			// admin page styling.  It's recommended to pass a CSS file with ONLY font
			// declarations.
			// If field is set and not blank, then enqueue field.
			if ( isset( $this->field['ext-font-css'] ) && '' !== $this->field['ext-font-css'] ) {
				wp_enqueue_style( 'redux-external-fonts', $this->field['ext-font-css'], array(), $this->timestamp );
			}

			if ( empty( $this->field['units'] ) && ! empty( $this->field['default']['units'] ) ) {
				$this->field['units'] = $this->field['default']['units'];
			}

			$unit = $this->field['units'];

			echo '<div id="' . esc_attr( $this->field['id'] ) . '" class="redux-typography-container" data-id="' . esc_attr( $this->field['id'] ) . '" data-units="' . esc_attr( $unit ) . '">';

			$this->select2_config['allowClear'] = true;

			if ( isset( $this->field['select2'] ) ) {
				$this->field['select2'] = wp_parse_args( $this->field['select2'], $this->select2_config );
			} else {
				$this->field['select2'] = $this->select2_config;
			}

			$this->field['select2'] = Redux_Functions::sanitize_camel_case_array_keys( $this->field['select2'] );

			$select2_data = Redux_Functions::create_data_string( $this->field['select2'] );

			$google_set     = false;
			$is_google_font = '0';

			// If no fontFamily array exists, create one and set array 0
			// with font value.
			if ( ! isset( $font_family ) ) {
				$font_family    = array();
				$font_family[0] = $this->value['font-family'];
				$font_family[1] = '';
			}

			/* Font Family */
			if ( true === $this->field['font-family'] ) {
				if ( filter_var( $this->value['google'], FILTER_VALIDATE_BOOLEAN ) ) {

					// Divide and conquer.
					$font_family = explode( ', ', $this->value['font-family'], 2 );

					// If array 0 is empty and array 1 is not.
					if ( empty( $font_family[0] ) && ! empty( $font_family[1] ) ) {

						// Make array 0 = array 1.
						$font_family[0] = $font_family[1];
					}
				}

				// Is selected font a Google font.
				if ( isset( $this->parent->fonts['google'][ $font_family[0] ] ) ) {
					$is_google_font = '1';
				}

				// If not a Google font, show all font families.
				if ( '1' !== $is_google_font ) {
					$font_family[0] = $this->value['font-family'];
				}

				$user_fonts = '0';
				if ( true === $this->user_fonts ) {
					$user_fonts = '1';
				}

				echo '<input
						type="hidden"
						class="redux-typography-font-family ' . esc_attr( $this->field['class'] ) . '"
						data-user-fonts="' . esc_attr( $user_fonts ) . '" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[font-family]"
						value="' . esc_attr( $this->value['font-family'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"  />';

				echo '<input
						type="hidden"
						class="redux-typography-font-options ' . esc_attr( $this->field['class'] ) . '"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[font-options]"
						value="' . esc_attr( $this->value['font-options'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"  />';

				echo '<input
						type="hidden"
						class="redux-typography-google-font" value="' . esc_attr( $is_google_font ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-google-font">';

				echo '<div class="select_wrapper typography-family" style="width: 220px; margin-right: 5px;">';
				echo '<label>' . esc_html__( 'Font Family', 'redux-framework' ) . '</label>';

				$placeholder = esc_html__( 'Font family', 'redux-framework' );

				$new_arr                = $this->field['select2'];
				$new_arr['allow-clear'] = $this->field['font_family_clear'];
				$new_data               = Redux_Functions::create_data_string( $new_arr );

				echo '<select class=" redux-typography redux-typography-family select2-container ' . esc_attr( $this->field['class'] ) . '" id="' . esc_attr( $this->field['id'] ) . '-family" data-placeholder="' . esc_attr( $placeholder ) . '" data-id="' . esc_attr( $this->field['id'] ) . '" data-value="' . esc_attr( $font_family[0] ) . '"' . esc_html( $new_data ) . '>';

				echo '</select>';
				echo '</div>';

				if ( true === $this->field['google'] ) {

					// Set a flag, so we know to set a header style or not.
					echo '<input
							type="hidden"
							class="redux-typography-google ' . esc_attr( $this->field['class'] ) . '"
							id="' . esc_attr( $this->field['id'] ) . '-google" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[google]"
							type="text" value="' . esc_attr( $this->field['google'] ) . '"
							data-id="' . esc_attr( $this->field['id'] ) . '" />';

					$google_set = true;
				}
			}

			/* Backup Font */
			if ( true === $this->field['font-family'] && true === $this->field['google'] ) {
				if ( false === $google_set ) {
					// Set a flag, so we know to set a header style or not.
					echo '<input
							type="hidden"
							class="redux-typography-google ' . esc_attr( $this->field['class'] ) . '"
							id="' . esc_attr( $this->field['id'] ) . '-google" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[google]"
							type="text" value="' . esc_attr( $this->field['google'] ) . '"
							data-id="' . esc_attr( $this->field['id'] ) . '"  />';
				}

				if ( true === $this->field['font-backup'] ) {
					echo '<div class="select_wrapper typography-family-backup" style="width: 220px; margin-right: 5px;">';
					echo '<label>' . esc_html__( 'Backup Font Family', 'redux-framework' ) . '</label>';
					echo '<select
							data-placeholder="' . esc_html__( 'Backup Font Family', 'redux-framework' ) . '"
							name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[font-backup]"
							class="redux-typography redux-typography-family-backup ' . esc_attr( $this->field['class'] ) . '"
							id="' . esc_attr( $this->field['id'] ) . '-family-backup"
							data-id="' . esc_attr( $this->field['id'] ) . '"
							data-value="' . esc_attr( $this->value['font-backup'] ) . '"' . esc_attr( $select2_data ) . '>';

					echo '<option data-google="false" data-details="" value=""></option>';

					foreach ( $this->field['fonts'] as $i => $family ) {
						echo '<option data-google="true" value="' . esc_attr( $i ) . '" ' . selected( $this->value['font-backup'], $i, false ) . '>' . esc_html( $family ) . '</option>';
					}

					echo '</select></div>';
				}
			}

			/* Font Style/Weight */
			if ( true === $this->field['font-style'] || true === $this->field['font-weight'] ) {
				echo '<div data-weights="' . rawurlencode( wp_json_encode( $this->field['weights'] ) ) . '" class="select_wrapper typography-style" original-title="' . esc_html__( 'Font style', 'redux-framework' ) . '">';
				echo '<label>' . esc_html__( 'Font Weight &amp; Style', 'redux-framework' ) . '</label>';

				$style = $this->value['font-weight'] . $this->value['font-style'];

				echo '<input
						type="hidden"
						class="typography-font-weight" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[font-weight]"
						value="' . esc_attr( $this->value['font-weight'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"  /> ';

				echo '<input
						type="hidden"
						class="typography-font-style" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[font-style]"
						value="' . esc_attr( $this->value['font-style'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"  /> ';
				$multi = ( isset( $this->field['multi']['weight'] ) && $this->field['multi']['weight'] ) ? ' multiple="multiple"' : '';
				echo '<select' . esc_html( $multi ) . '
				        data-placeholder="' . esc_html__( 'Style', 'redux-framework' ) . '"
				        class="redux-typography redux-typography-style select ' . esc_attr( $this->field['class'] ) . '"
				        original-title="' . esc_html__( 'Font style', 'redux-framework' ) . '"
				        id="' . esc_attr( $this->field['id'] ) . '_style" data-id="' . esc_attr( $this->field['id'] ) . '"
				        data-value="' . esc_attr( $style ) . '"' . esc_attr( $select2_data ) . '>';

				if ( empty( $this->value['subsets'] ) || empty( $this->value['font-weight'] ) ) {
					echo '<option value=""></option>';
				}

				echo '</select></div>';
			}

			/* Font Script */
			if ( true === $this->field['font-family'] && true === $this->field['subsets'] && true === $this->field['google'] ) {
				echo '<div class="select_wrapper typography-script tooltip" original-title="' . esc_html__( 'Font subsets', 'redux-framework' ) . '">';
				echo '<input
						type="hidden"
						class="typography-subsets"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[subsets]"
						value="' . esc_attr( $this->value['subsets'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"  /> ';

				echo '<label>' . esc_html__( 'Font Subsets', 'redux-framework' ) . '</label>';
				$multi = ( isset( $this->field['multi']['subsets'] ) && $this->field['multi']['subsets'] ) ? ' multiple="multiple"' : '';
				echo '<select' . esc_html( $multi ) . '
						data-placeholder="' . esc_html__( 'Subsets', 'redux-framework' ) . '"
						class="redux-typography redux-typography-subsets ' . esc_attr( $this->field['class'] ) . '"
						original-title="' . esc_html__( 'Font script', 'redux-framework' ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-subsets"
						data-value="' . esc_attr( $this->value['subsets'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"' . esc_attr( $select2_data ) . '>';

				if ( empty( $this->value['subsets'] ) ) {
					echo '<option value=""></option>';
				}

				echo '</select></div>';
			}

			/* Font Align */
			if ( true === $this->field['text-align'] ) {
				echo '<div class="select_wrapper typography-align tooltip" original-title="' . esc_html__( 'Text Align', 'redux-framework' ) . '">';
				echo '<label>' . esc_html__( 'Text Align', 'redux-framework' ) . '</label>';
				echo '<select
						data-placeholder="' . esc_html__( 'Text Align', 'redux-framework' ) . '"
						class="redux-typography redux-typography-align ' . esc_attr( $this->field['class'] ) . '"
						original-title="' . esc_html__( 'Text Align', 'redux-framework' ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-align"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[text-align]"
						data-value="' . esc_attr( $this->value['text-align'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"' . esc_attr( $select2_data ) . '>';

				echo '<option value=""></option>';

				$align = array(
					esc_html__( 'inherit', 'redux-framework' ),
					esc_html__( 'left', 'redux-framework' ),
					esc_html__( 'right', 'redux-framework' ),
					esc_html__( 'center', 'redux-framework' ),
					esc_html__( 'justify', 'redux-framework' ),
					esc_html__( 'initial', 'redux-framework' ),
				);

				foreach ( $align as $v ) {
					echo '<option value="' . esc_attr( $v ) . '" ' . selected( $this->value['text-align'], $v, false ) . '>' . esc_html( ucfirst( $v ) ) . '</option>';
				}

				echo '</select></div>';
			}

			/* Text Transform */
			if ( true === $this->field['text-transform'] ) {
				echo '<div class="select_wrapper typography-transform tooltip" original-title="' . esc_html__( 'Text Transform', 'redux-framework' ) . '">';
				echo '<label>' . esc_html__( 'Text Transform', 'redux-framework' ) . '</label>';
				echo '<select
						data-placeholder="' . esc_html__( 'Text Transform', 'redux-framework' ) . '"
						class="redux-typography redux-typography-transform ' . esc_attr( $this->field['class'] ) . '"
						original-title="' . esc_html__( 'Text Transform', 'redux-framework' ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-transform"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[text-transform]"
						data-value="' . esc_attr( $this->value['text-transform'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"' . esc_attr( $select2_data ) . '>';

				echo '<option value=""></option>';

				$values = array(
					esc_html__( 'none', 'redux-framework' ),
					esc_html__( 'capitalize', 'redux-framework' ),
					esc_html__( 'uppercase', 'redux-framework' ),
					esc_html__( 'lowercase', 'redux-framework' ),
					esc_html__( 'initial', 'redux-framework' ),
					esc_html__( 'inherit', 'redux-framework' ),
				);

				foreach ( $values as $v ) {
					echo '<option value="' . esc_attr( $v ) . '" ' . selected( $this->value['text-transform'], $v, false ) . '>' . esc_html( ucfirst( $v ) ) . '</option>';
				}

				echo '</select></div>';
			}

			/* Font Variant */
			if ( true === $this->field['font-variant'] ) {
				echo '<div class="select_wrapper typography-font-variant tooltip" original-title="' . esc_html__( 'Font Variant', 'redux-framework' ) . '">';
				echo '<label>' . esc_html__( 'Font Variant', 'redux-framework' ) . '</label>';
				echo '<select
						data-placeholder="' . esc_html__( 'Font Variant', 'redux-framework' ) . '"
						class="redux-typography redux-typography-font-variant ' . esc_attr( $this->field['class'] ) . '"
						original-title="' . esc_html__( 'Font Variant', 'redux-framework' ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-font-variant"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[font-variant]"
						data-value="' . esc_attr( $this->value['font-variant'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"' . esc_attr( $select2_data ) . '>';

				echo '<option value=""></option>';

				$values = array(
					esc_html__( 'inherit', 'redux-framework' ),
					esc_html__( 'normal', 'redux-framework' ),
					esc_html__( 'small-caps', 'redux-framework' ),
				);

				foreach ( $values as $v ) {
					echo '<option value="' . esc_attr( $v ) . '" ' . selected( $this->value['font-variant'], $v, false ) . '>' . esc_attr( ucfirst( $v ) ) . '</option>';
				}

				echo '</select></div>';
			}

			/* Text Decoration */
			if ( true === $this->field['text-decoration'] ) {
				echo '<div class="select_wrapper typography-decoration tooltip" original-title="' . esc_html__( 'Text Decoration', 'redux-framework' ) . '">';
				echo '<label>' . esc_html__( 'Text Decoration', 'redux-framework' ) . '</label>';
				echo '<select
						data-placeholder="' . esc_html__( 'Text Decoration', 'redux-framework' ) . '"
						class="redux-typography redux-typography-decoration ' . esc_attr( $this->field['class'] ) . '"
						original-title="' . esc_html__( 'Text Decoration', 'redux-framework' ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-decoration"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[text-decoration]"
						data-value="' . esc_attr( $this->value['text-decoration'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"' . esc_attr( $select2_data ) . '>';

				echo '<option value=""></option>';

				$values = array(
					esc_html__( 'none', 'redux-framework' ),
					esc_html__( 'inherit', 'redux-framework' ),
					esc_html__( 'underline', 'redux-framework' ),
					esc_html__( 'overline', 'redux-framework' ),
					esc_html__( 'line-through', 'redux-framework' ),
					esc_html__( 'blink', 'redux-framework' ),
				);

				foreach ( $values as $v ) {
					echo '<option value="' . esc_attr( $v ) . '" ' . selected( $this->value['text-decoration'], $v, false ) . '>' . esc_html( ucfirst( $v ) ) . '</option>';
				}

				echo '</select></div>';
			}

			/* Font Size */
			if ( true === $this->field['font-size'] ) {
				$the_unit = '' !== $this->field['font-size-unit'] ? $this->field['font-size-unit'] : $unit;

				echo '<div class="input_wrapper font-size redux-container-typography">';
				echo '<label>' . esc_html__( 'Font Size', 'redux-framework' ) . '</label>';
				echo '<div class="input-append">';
				echo '<input
						type="text"
						class="span2 redux-typography redux-typography-size mini typography-input ' . esc_attr( $this->field['class'] ) . '"
						title="' . esc_html__( 'Font Size', 'redux-framework' ) . '"
						placeholder="' . esc_html__( 'Size', 'redux-framework' ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-size"
						value="' . esc_attr( str_replace( Redux_Helpers::$array_units, '', $this->value['font-size'] ) ) . '"
						data-unit="' . esc_attr( $the_unit ) . '"
						data-value="' . esc_attr( str_replace( Redux_Helpers::$array_units, '', $this->value['font-size'] ) ) . '">';
				echo '<span class="add-on">' . esc_html( $the_unit ) . '</span>';
				echo '</div>';
				echo '<input type="hidden" class="typography-font-size" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[font-size]" value="' . esc_attr( $this->value['font-size'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '"/>';
				echo '</div>';
			}

			/* Line Height */
			if ( true === $this->field['line-height'] ) {
				$the_unit = $this->field['line-height-unit'] ?? $unit;

				echo '<div class="input_wrapper line-height redux-container-typography">';
				echo '<label>' . esc_html__( 'Line Height', 'redux-framework' ) . '</label>';
				echo '<div class="input-append">';
				echo '<input
						type="text"
						class="span2 redux-typography redux-typography-height mini typography-input ' . esc_attr( $this->field['class'] ) . '"
						title="' . esc_html__( 'Line Height', 'redux-framework' ) . '"
						placeholder="' . esc_html__( 'Height', 'redux-framework' ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-height"
						value="' . esc_attr( str_replace( Redux_Helpers::$array_units, '', $this->value['line-height'] ) ) . '"
						data-allow-empty="' . esc_attr( $this->field['allow_empty_line_height'] ) . '"
						data-unit="' . esc_attr( $the_unit ) . '"
						data-value="' . esc_attr( str_replace( Redux_Helpers::$array_units, '', $this->value['line-height'] ) ) . '">';
				echo '<span class="add-on">' . esc_html( '' === $the_unit ? '&nbsp;' : $the_unit ) . '</span>';
				echo '</div>';
				echo '<input
						type="hidden"
						class="typography-line-height"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[line-height]"
						value="' . esc_attr( $this->value['line-height'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"/>';
				echo '</div>';
			}

			/* Word Spacing */
			if ( true === $this->field['word-spacing'] ) {
				$the_unit = '' !== $this->field['word-spacing-unit'] ? $this->field['word-spacing-unit'] : $unit;

				echo '<div class="input_wrapper word-spacing redux-container-typography">';
				echo '<label>' . esc_html__( 'Word Spacing', 'redux-framework' ) . '</label>';
				echo '<div class="input-append">';
				echo '<input
						type="text"
						class="span2 redux-typography redux-typography-word mini typography-input ' . esc_attr( $this->field['class'] ) . '"
						title="' . esc_html__( 'Word Spacing', 'redux-framework' ) . '"
						placeholder="' . esc_html__( 'Word Spacing', 'redux-framework' ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-word"
						data-unit="' . esc_attr( $the_unit ) . '"
						value="' . esc_attr( str_replace( $the_unit, '', $this->value['word-spacing'] ) ) . '"
						data-value="' . esc_attr( str_replace( $the_unit, '', $this->value['word-spacing'] ) ) . '">';

				echo '<span class="add-on">' . esc_html( $the_unit ) . '</span>';
				echo '</div>';
				echo '<input type="hidden" class="typography-word-spacing" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[word-spacing] " value="' . esc_attr( $this->value['word-spacing'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '"/>';
				echo '</div>';
			}

			/* Letter Spacing */
			if ( true === $this->field['letter-spacing'] ) {
				$the_unit = '' !== $this->field['letter-spacing-unit'] ? $this->field['letter-spacing-unit'] : $unit;

				echo '<div class="input_wrapper letter-spacing redux-container-typography">';
				echo '<label>' . esc_html__( 'Letter Spacing', 'redux-framework' ) . '</label>';
				echo '<div class="input-append">';
				echo '<input
						type="text"
						class="span2 redux-typography redux-typography-letter mini typography-input ' . esc_attr( $this->field['class'] ) . '"
						title="' . esc_html__( 'Letter Spacing', 'redux-framework' ) . '"
						placeholder="' . esc_html__( 'Letter Spacing', 'redux-framework' ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-letter"
						data-unit="' . esc_attr( $the_unit ) . '"
						value="' . esc_attr( str_replace( $the_unit, '', $this->value['letter-spacing'] ) ) . '"
						data-value="' . esc_attr( str_replace( $the_unit, '', $this->value['letter-spacing'] ) ) . '">';

				echo '<span class="add-on">' . esc_html( $the_unit ) . '</span>';
				echo '</div>';
				echo '<input
						type="hidden"
						class="typography-letter-spacing"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[letter-spacing]"
						value="' . esc_attr( $this->value['letter-spacing'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"  />';

				echo '</div>';
			}

			echo '<div class="clearfix"></div>';

			// Margins.
			if ( $this->field['margin-top'] ) {
				$the_unit = '' !== $this->field['margin-top-unit'] ? $this->field['margin-top-unit'] : $unit;

				echo '<div class="input_wrapper margin-top redux-container-typography">';
				echo '<label>' . esc_html__( 'Margin Top', 'redux-framework' ) . '</label>';
				echo '<div class="input-append">';
				echo '<input
						type="text"
						class="span2 redux-typography redux-typography-margin-top mini typography-input ' . esc_attr( $this->field['class'] ) . '"
						title="' . esc_html__( 'Margin Top', 'redux-framework' ) . '"
						placeholder="' . esc_html__( 'Top', 'redux-framework' ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-margin-top"
						data-unit="' . esc_attr( $the_unit ) . '"
						value="' . esc_attr( str_replace( $the_unit, '', $this->value['margin-top'] ) ) . '"
						data-value="' . esc_attr( str_replace( $the_unit, '', $this->value['margin-top'] ) ) . '">';
				echo '<span class="add-on">' . esc_html( $the_unit ) . '</span>';
				echo '</div>';
				echo '<input
						type="hidden"
						class="typography-margin-top"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[margin-top]"
						value="' . esc_attr( $this->value['margin-top'] ) . '"
						data-id="' . esc_attr( $this->field['id'] ) . '"  />';
				echo '</div>';
			}

			/* Bottom Margin */
			if ( $this->field['margin-bottom'] ) {
				$the_unit = '' !== $this->field['margin-bottom-unit'] ? $this->field['margin-bottom-unit'] : $unit;

				echo '<div class="input_wrapper margin-bottom redux-container-typography">';
				echo '<label>' . esc_html__( 'Margin Bottom', 'redux-framework' ) . '</label>';
				echo '<div class="input-append">';
				echo '<input
						type="text"
						class="span2 redux-typography redux-typography-margin-bottom mini typography-input ' . esc_attr( $this->field['class'] ) . '"
						title="' . esc_html__( 'Margin Bottom', 'redux-framework' ) . '"
						placeholder="' . esc_html__( 'Bottom', 'redux-framework' ) . '"
						id="' . esc_attr( $this->field['id'] ) . '-margin-bottom"
						data-unit="' . esc_attr( $the_unit ) . '"
						value="' . esc_attr( str_replace( $the_unit, '', $this->value['margin-bottom'] ) ) . '"
						data-value="' . esc_attr( str_replace( $the_unit, '', $this->value['margin-bottom'] ) ) . '">';
				echo '<span class="add-on">' . esc_html( $the_unit ) . '</span>';
				echo '</div>';
				echo '<input type="hidden" class="typography-margin-bottom" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[margin-bottom]" value="' . esc_attr( $this->value['margin-bottom'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '"  />';
				echo '</div>';
			}

			if ( $this->field['margin-top'] || $this->field['margin-bottom'] ) {
				echo '<div class="clearfix"></div>';
			}

			/* Font Color */
			if ( true === $this->field['color'] ) {
				$default = '';

				if ( empty( $this->field['default']['color'] ) && ! empty( $this->field['color'] ) ) {
					$default = $this->value['color'];
				} elseif ( ! empty( $this->field['default']['color'] ) ) {
					$default = $this->field['default']['color'];
				}

				echo '<div class="picker-wrapper">';
				echo '<label>' . esc_html__( 'Font Color', 'redux-framework' ) . '</label>';
				echo '<div id="' . esc_attr( $this->field['id'] ) . '_color_picker" class="colorSelector typography-color">';
				echo '<div style="background-color: ' . esc_attr( $this->value['color'] ) . '"></div>';
				echo '</div>';
				echo '<input ';
				echo 'data-default-color="' . esc_attr( $default ) . '"';
				echo 'class="color-picker redux-color redux-typography-color ' . esc_attr( $this->field['class'] ) . '"';
				echo 'original-title="' . esc_html__( 'Font color', 'redux-framework' ) . '"';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-color"';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[color]"';
				echo 'type="text"';
				echo 'value="' . esc_attr( $this->value['color'] ) . '"';
				echo 'data-id="' . esc_attr( $this->field['id'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => 'color',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				echo '>';
				echo '</div>';
			}

			echo '<div class="clearfix"></div>';

			/* Font Preview */
			if ( ! isset( $this->field['preview'] ) || false !== $this->field['preview'] ) {
				$g_text = $this->field['preview']['text'] ?? '1 2 3 4 5 6 7 8 9 0 A B C D E F G H I J K L M N O P Q R S T U V W X Y Z a b c d e f g h i j k l m n o p q r s t u v w x y z';

				$style = '';
				if ( isset( $this->field['preview']['always_display'] ) ) {
					if ( true === filter_var( $this->field['preview']['always_display'], FILTER_VALIDATE_BOOLEAN ) ) {
						if ( true === (bool) $is_google_font ) {
							$this->typography_preview[ $font_family[0] ] = array(
								'font-style' => array( $this->value['font-weight'] . $this->value['font-style'] ),
								'subset'     => array( $this->value['subsets'] ),
							);

							wp_deregister_style( 'redux-typography-preview' );
							wp_dequeue_style( 'redux-typography-preview' );

							wp_enqueue_style( 'redux-typography-preview', $this->make_google_web_font_link( $this->typography_preview ), array(), $this->timestamp );
						}

						$style = 'display: block; font-family: ' . esc_attr( $this->value['font-family'] ) . '; font-weight: ' . esc_attr( $this->value['font-weight'] ) . ';';
					}
				}

				if ( isset( $this->field['preview']['font-size'] ) ) {
					$style .= 'font-size: ' . $this->field['preview']['font-size'] . ';';
					$in_use = '1';
				} else {
					$in_use = '0';
				}

				if ( Redux_Helpers::google_fonts_update_needed() && ! get_option( 'auto_update_redux_google_fonts', false ) && $this->field['font-family'] && $this->field['google'] ) {
					$nonce = wp_create_nonce( 'redux_update_google_fonts' );

					echo '<div data-nonce="' . esc_attr( $nonce ) . '" class="redux-update-google-fonts update-message notice inline notice-warning notice-alt">';
					echo '<p>' . esc_html__( 'Your Google Fonts are out of date. To update them, please click one of the following:', 'redux-framework' );
					echo '&nbsp;<a href="#" class="update-google-fonts" data-action="automatic" aria-label="' . esc_attr__( 'Automated updates', 'redux-framework' ) . '">' . esc_html__( 'Automated updates', 'redux-framework' ) . '</a> or <a href="#" class="update-google-fonts" data-action="manual" aria-label="' . esc_attr__( 'one-time update', 'redux-framework' ) . '">' . esc_html__( 'one-time update', 'redux-framework' ) . '</a>.';
					echo '</p>';
					echo '</div>';
				}

				echo '<p data-preview-size="' . esc_attr( $in_use ) . '" class="clear ' . esc_attr( $this->field['id'] ) . '_previewer typography-preview" style="' . esc_attr( $style ) . '">' . esc_html( $g_text ) . '</p>';

				if ( $this->field['text-shadow'] ) {

					/* Shadow Colour */
					echo '<div class="picker-wrapper">';
					echo '<label>' . esc_html__( 'Shadow Color', 'redux-framework' ) . '</label>';
					echo '<div id="' . esc_attr( $this->field['id'] ) . '_color_picker" class="colorSelector typography-shadow-color"><div style="background-color: ' . esc_attr( $this->value['color'] ) . '"></div></div>';
					echo '<input
		                    data-default-color="' . esc_attr( $this->value['shadow-color'] ) . '"
		                    class="color-picker redux-color redux-typography-shadow-color ' . esc_attr( $this->field['class'] ) . '"
		                    original-title="' . esc_html__( 'Shadow color', 'redux-framework' ) . '"
		                    id="' . esc_attr( $this->field['id'] ) . '-shadow-color"
		                    name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[shadow-color]"
		                    type="text"
		                    value="' . esc_attr( $this->value['shadow-color'] ) . '"
		                    data-alpha="' . esc_attr( $this->field['color_alpha']['shadow-color'] ) . '"
		                    data-id="' . esc_attr( $this->field['id'] ) . '"
		                  />';
					echo '</div>';

					/* Shadow Horizontal Length */
					echo '<div class="input_wrapper shadow-horizontal redux-container-typography" style="top:-60px;margin-left:20px;width:20%">';
					echo '<label>' . esc_html__( 'Horizontal', 'redux-framework' ) . ': <strong>' . esc_attr( $this->value['shadow-horizontal'] ) . 'px</strong></label>';
					echo '<div
                            class="redux-typography-slider span2 redux-typography redux-typography-shadow-horizontal mini typography-input ' . esc_attr( $this->field['class'] ) . '"
                            id="' . esc_attr( $this->field['id'] ) . '"
                            data-id="' . esc_attr( $this->field['id'] ) . '-h"
                            data-min="-20"
                            data-max="20"
                            data-step="1"
                            data-rtl="' . esc_attr( is_rtl() ) . '"
                            data-label="' . esc_attr__( 'Horizontal', 'redux-framework' ) . '"
                            data-default = "' . esc_attr( $this->value['shadow-horizontal'] ) . '">
                        </div>';
					echo '<input type="hidden" id="redux-slider-value-' . esc_attr( $this->field['id'] ) . '-h" class="typography-shadow-horizontal" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[shadow-horizontal]" value="' . esc_attr( $this->value['shadow-horizontal'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '"  />';
					echo '</div>';

					/* Shadow Vertical Length */
					echo '<div class="input_wrapper shadow-vertical redux-container-typography" style="top:-60px;margin-left:20px;width:20%">';
					echo '<label>' . esc_html__( 'Vertical', 'redux-framework' ) . ': <strong>' . esc_attr( $this->value['shadow-vertical'] ) . 'px</strong></label>';
					echo '<div
                            class="redux-typography-slider span2 redux-typography redux-typography-shadow-vertical mini typography-input ' . esc_attr( $this->field['class'] ) . '"
                            id="' . esc_attr( $this->field['id'] ) . '"
                            data-id="' . esc_attr( $this->field['id'] ) . '-v"
                            data-min="-20"
                            data-max="20"
                            data-step="1"
                            data-rtl="' . esc_attr( is_rtl() ) . '"
                            data-label="' . esc_attr__( 'Vertical', 'redux-framework' ) . '"
                            data-default = "' . esc_attr( $this->value['shadow-vertical'] ) . '">
                        </div>';
					echo '<input type="hidden" id="redux-slider-value-' . esc_attr( $this->field['id'] ) . '-v" class="typography-shadow-vertical" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[shadow-vertical]" value="' . esc_attr( $this->value['shadow-vertical'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '"  />';
					echo '</div>';

					/* Shadow Blur */
					echo '<div class="input_wrapper shadow-blur redux-container-typography" style="top:-60px;margin-left:20px;width:20%">';
					echo '<label>' . esc_html__( 'Blur', 'redux-framework' ) . ': <strong>' . esc_attr( $this->value['shadow-blur'] ) . 'px</strong></label>';
					echo '<div
                            class="redux-typography-slider span2 redux-typography redux-typography-shadow-blur mini typography-input ' . esc_attr( $this->field['class'] ) . '"
                            id="' . esc_attr( $this->field['id'] ) . '"
                            data-id="' . esc_attr( $this->field['id'] ) . '-b"
                            data-min="0"
                            data-max="25"
                            data-step="1"
                            data-rtl="' . esc_attr( is_rtl() ) . '"
                            data-label="' . esc_attr__( 'Blur', 'redux-framework' ) . '"
                            data-default = "' . esc_attr( $this->value['shadow-blur'] ) . '">
                        </div>';
					echo '<input type="hidden" id="redux-slider-value-' . esc_attr( $this->field['id'] ) . '-b" class="typography-shadow-blur" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[shadow-blur]" value="' . esc_attr( $this->value['shadow-blur'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '"  />';
					echo '</div>';
				}

				echo '</div>'; // end typography container.
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or CSS define this function and register/enqueue the scripts/css
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function enqueue() {
			$min = Redux_Functions::is_min();

			if ( ! wp_style_is( 'select2-css' ) ) {
				wp_enqueue_style( 'select2-css' );
			}

			if ( ! wp_style_is( 'wp-color-picker' ) ) {
				wp_enqueue_style( 'wp-color-picker' );
			}

			wp_enqueue_script( 'redux-webfont-js', '//' . 'ajax' . '.googleapis' . '.com/ajax/libs/webfont/1.6.26/webfont.js', array(), '1.6.26', true ); // phpcs:ignore Generic.Strings.UnnecessaryStringConcat

			$dep_array = array( 'jquery', 'wp-color-picker', 'select2-js', 'redux-js', 'redux-webfont-js' );

			wp_enqueue_script( 'redux-field-typography-js', Redux_Core::$url . "inc/fields/typography/redux-typography$min.js", $dep_array, $this->timestamp, true );

			wp_localize_script(
				'redux-field-typography-js',
				'redux_typography_ajax',
				array(
					'ajaxurl'             => esc_url( admin_url( 'admin-ajax.php' ) ),
					'update_google_fonts' => array(
						'updating' => esc_html__( 'Downloading Google Fonts...', 'redux-framework' ),
						// translators: Aria title, link title.
						'error'    => sprintf( esc_html__( 'Update Failed|msg. %1$s', 'redux-framework' ), sprintf( '<a href="#" class="update-google-fonts" data-action="manual" aria-label="%s">%s</a>', esc_html__( 'Retry?', 'redux-framework' ), esc_html__( 'Retry?', 'redux-framework' ) ) ),
						// translators: Javascript reload command, link title.
						'success'  => sprintf( esc_html__( 'Updated! %1$s to start using your updated fonts.', 'redux-framework' ), sprintf( '<a href="%1$s">%2$s</a>', 'javascript:location.reload();', esc_html__( 'Reload the page', 'redux-framework' ) ) ),
					),
				)
			);

			if ( isset( $this->field['color_alpha'] ) && is_array( $this->field['color_alpha'] ) ) {
				if ( $this->field['color_alpha']['color'] || $this->field['color_alpha']['shadow-color'] ) {
					wp_enqueue_script( 'redux-wp-color-picker-alpha-js' );
				}
			}

			if ( ! wp_style_is( 'redux-nouislider-css' ) && isset( $this->field['text-shadow'] ) && $this->field['text-shadow'] ) {
				wp_enqueue_style(
					'redux-nouislider-css',
					Redux_Core::$url . "assets/css/vendor/nouislider$min.css",
					array(),
					'5.0.0'
				);

				wp_enqueue_script(
					'redux-nouislider-js',
					Redux_Core::$url . "assets/js/vendor/nouislider/redux.jquery.nouislider$min.js",
					array( 'jquery' ),
					'5.0.0',
					true
				);
			}

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style( 'redux-color-picker-css' );

				wp_enqueue_style(
					'redux-field-typography-css',
					Redux_Core::$url . 'inc/fields/typography/redux-typography.css',
					array(),
					$this->timestamp
				);
			}
		}

		/**
		 * Make_google_web_font_link Function.
		 * Creates the Google fonts link.
		 *
		 * @param array $fonts Array of google fonts.
		 *
		 * @return string
		 *
		 * @since ReduxFramework 3.0.0
		 */
		public function make_google_web_font_link( array $fonts ): string {
			$link    = '';
			$subsets = array();

			foreach ( $fonts as $family => $font ) {
				if ( ! empty( $link ) ) {
					$link .= '|'; // Append a new font to the string.
				}
				$link .= $family;

				if ( ! empty( $font['font-style'] ) || ! empty( $font['all-styles'] ) ) {
					$link .= ':';
					if ( ! empty( $font['all-styles'] ) ) {
						$link .= implode( ',', $font['all-styles'] );
					} elseif ( ! empty( $font['font-style'] ) ) {
						$link .= implode( ',', $font['font-style'] );
					}
				}

				if ( ! empty( $font['subset'] ) || ! empty( $font['all-subsets'] ) ) {
					if ( ! empty( $font['all-subsets'] ) ) {
						foreach ( $font['all-subsets'] as $subset ) {
							if ( ! in_array( $subset, $subsets, true ) ) {
								$subsets[] = $subset;
							}
						}
					} elseif ( ! empty( $font['subset'] ) ) {
						foreach ( $font['subset'] as $subset ) {
							if ( ! in_array( $subset, $subsets, true ) ) {
								$subsets[] = $subset;
							}
						}
					}
				}
			}

			if ( ! empty( $subsets ) ) {
				$link .= '&subset=' . implode( ',', $subsets );
			}
			$link .= '&display=' . $this->parent->args['font_display'];

			//return 'https://fonts.bunny.net/css?family=' . $link;
			return 'https://fonts.googleapis.com/css?family=' . $link;
		}

		/**
		 * Make_google_web_font_string Function.
		 * Creates the Google fonts link.
		 *
		 * @param array $fonts Array of Google fonts.
		 *
		 * @return string
		 *
		 * @since ReduxFramework 3.1.8
		 */
		public function make_google_web_font_string( array $fonts ): string {
			$link    = '';
			$subsets = array();

			foreach ( $fonts as $family => $font ) {
				if ( ! empty( $link ) ) {
					$link .= "', '"; // Append a new font to the string.
				}
				$link .= $family;

				if ( ! empty( $font['font-style'] ) || ! empty( $font['all-styles'] ) ) {
					$link .= ':';
					if ( ! empty( $font['all-styles'] ) ) {
						$link .= implode( ',', $font['all-styles'] );
					} elseif ( ! empty( $font['font-style'] ) ) {
						$link .= implode( ',', $font['font-style'] );
					}
				}

				if ( ! empty( $font['subset'] ) || ! empty( $font['all-subsets'] ) ) {
					if ( ! empty( $font['all-subsets'] ) ) {
						foreach ( $font['all-subsets'] as $subset ) {
							if ( ! in_array( $subset, $subsets, true ) && ! is_numeric( $subset ) ) {
								$subsets[] = $subset;
							}
						}
					} elseif ( ! empty( $font['subset'] ) ) {
						foreach ( $font['subset'] as $subset ) {
							if ( ! in_array( $subset, $subsets, true ) && ! is_numeric( $subset ) ) {
								$subsets[] = $subset;
							}
						}
					}
				}
			}

			if ( ! empty( $subsets ) ) {
				$link .= '&subset=' . implode( ',', $subsets );
			}

			return "'" . $link . "'";
		}

		/**
		 * Compiles field CSS for output.
		 *
		 * @param array $data Array of data to process.
		 *
		 * @return string
		 */
		public function css_style( $data ): string {
			$style = '';

			$font = $data;

			// Shim out old arg to new.
			if ( isset( $this->field['all_styles'] ) && ! empty( $this->field['all_styles'] ) ) {
				$this->field['all-styles'] = $this->field['all_styles'];
				unset( $this->field['all_styles'] );
			}

			// Check for font-backup.  If it's set, stick it on a variable for
			// later use.
			if ( ! empty( $font['font-family'] ) && ! empty( $font['font-backup'] ) ) {
				$font['font-family'] = str_replace( ', ' . $font['font-backup'], '', $font['font-family'] );
				$font_backup         = ',' . $font['font-backup'];
			}

			$font_value_set = false;

			if ( ! empty( $font ) ) {
				foreach ( $font as $key => $value ) {
					if ( ! empty( $value ) && in_array( $key, array( 'font-family', 'font-weight' ), true ) ) {
						$font_value_set = true;
					}
				}
			}

			if ( ! empty( $font ) ) {
				foreach ( $font as $key => $value ) {
					if ( 'font-options' === $key ) {
						continue;
					}

					// Check for font-family key.
					if ( 'font-family' === $key ) {

						// Enclose font family in quotes if spaces are in the
						// name.  This is necessary because if there are numerics
						// in the font name, they will not render properly.
						// Google should know better.
						if ( strpos( $value, ' ' ) && ! strpos( $value, ',' ) ) {
							$value = '"' . $value . '"';
						}

						// Ensure fontBackup isn't empty. We already option
						// checked this earlier.  No need to do it again.
						if ( ! empty( $font_backup ) ) {

							// Apply the backup font to the font-family element
							// via the saved variable.  We do this here, so it
							// doesn't get appended to the Google stuff below.
							$value .= $font_backup;
						}
					}

					if ( empty( $value ) && in_array(
						$key,
						array(
							'font-weight',
							'font-style',
						),
						true
					) && true === $font_value_set ) {
						$value = 'normal';
					}

					if ( 'font-weight' === $key && false === $this->field['font-weight'] ) {
						continue;
					}

					if ( 'font-style' === $key && false === $this->field['font-style'] ) {
						continue;
					}

					if ( 'google' === $key || 'subsets' === $key || 'font-backup' === $key || empty( $value ) ) {
						continue;
					}

					if ( isset( $data['key'] ) ) {
						return $data;
					}

					$continue = false;

					if ( 'shadow-horizontal' === $key || 'shadow-vertical' === $key || 'shadow-blur' === $key ) {
						$continue = true;
					}

					if ( 'shadow-color' === $key ) {
						if ( $this->field['text-shadow'] ) {
							$key   = 'text-shadow';
							$value = $data['shadow-horizontal'] . 'px ' . $data['shadow-vertical'] . 'px ' . $data['shadow-blur'] . 'px ' . $data['shadow-color'];
						} else {
							$continue = true;
						}
					}

					if ( $continue ) {
						continue;
					}

					$style .= $key . ':' . $value . ';';
				}

				$style .= 'font-display:' . $this->parent->args['font_display'] . ';';
			}

			return $style;
		}

		/**
		 * CSS Output to send to the page.
		 *
		 * @param string|null|array $style CSS styles.
		 */
		public function output( $style = '' ) {
			$font = $this->value;

			if ( '' !== $style ) {
				if ( ! empty( $this->field['output'] ) && ! is_array( $this->field['output'] ) ) {
					$this->field['output'] = array( $this->field['output'] );
				}

				if ( ! empty( $this->field['output'] ) && is_array( $this->field['output'] ) ) {
					$keys                     = implode( ',', $this->field['output'] );
					$this->parent->outputCSS .= $keys . '{' . $style . '}';
				}

				if ( ! empty( $this->field['compiler'] ) && ! is_array( $this->field['compiler'] ) ) {
					$this->field['compiler'] = array( $this->field['compiler'] );
				}

				if ( ! empty( $this->field['compiler'] ) && is_array( $this->field['compiler'] ) ) {
					$keys                       = implode( ',', $this->field['compiler'] );
					$this->parent->compilerCSS .= $keys . '{' . $style . '}';
				}
			}

			$this->set_google_fonts( (array) $font );
		}

		/**
		 * Set global Google font data for global pointer.
		 *
		 * @param array $font Array of font data.
		 */
		private function set_google_fonts( array $font ) {
			// Google only stuff!
			if ( ! empty( $font['font-family'] ) && ! empty( $this->field['google'] ) && filter_var( $this->field['google'], FILTER_VALIDATE_BOOLEAN ) ) {

				// Added standard font matching check to avoid output to Google fonts call - kp
				// If no custom font array was supplied, then load it with default
				// standard fonts.
				if ( empty( $this->field['fonts'] ) ) {
					$this->field['fonts'] = $this->std_fonts;
				}

				// Ensure the fonts array is NOT empty.
				if ( ! empty( $this->field['fonts'] ) ) {

					// Make the font keys in the array lowercase, for case-insensitive matching.
					$lc_fonts = array_change_key_case( $this->field['fonts'] );

					// Rebuild font array with all keys stripped of spaces.
					$arr = array();
					foreach ( $lc_fonts as $key => $value ) {
						$key         = str_replace( ', ', ',', $key );
						$arr[ $key ] = $value;
					}

					if ( is_array( $this->field['custom_fonts'] ) ) {
						$lc_fonts = array_change_key_case( $this->field['custom_fonts'] );

						foreach ( $lc_fonts as $font_arr ) {
							foreach ( $font_arr as $key => $value ) {
								$arr[ Redux_Core::strtolower( $key ) ] = $key;
							}
						}
					}

					$lc_fonts = $arr;

					unset( $arr );

					// lowercase chosen font for matching purposes.
					$lc_font = Redux_Core::strtolower( $font['font-family'] );

					// Remove spaces after commas in chosen font for matching purposes.
					$lc_font = str_replace( ', ', ',', $lc_font );

					// If the lower cased passed font-family is NOT found in the standard font array
					// Then it's a Google font, so process it for output.
					if ( ! array_key_exists( $lc_font, $lc_fonts ) ) {
						$family = $font['font-family'];

						// TODO: This method doesn't respect spaces after commas, hence the reason
						// Strip out spaces in font names and replace with with plus signs
						// for the std_font array keys having no spaces after commas.  This could be
						// fixed with RegEx in the future.
						$font['font-family'] = str_replace( ' ', '+', $font['font-family'] );

						// Push data to parent typography variable.
						if ( empty( $this->parent->typography[ $font['font-family'] ] ) ) {
							$this->parent->typography[ $font['font-family'] ] = array();
						}

						if ( isset( $this->field['all-styles'] ) || isset( $this->field['all-subsets'] ) ) {
							if ( empty( $font['font-options'] ) ) {
								$this->get_google_array();

								if ( isset( $this->parent->google_array ) && ! empty( $this->parent->google_array ) && isset( $this->parent->google_array[ $family ] ) ) {
									$font['font-options'] = $this->parent->google_array[ $family ];
								}
							} else {
								$font['font-options'] = json_decode( $font['font-options'], true );
							}
						}

						if ( isset( $font['font-options'] ) && ! empty( $font['font-options'] ) && isset( $this->field['all-styles'] ) && filter_var( $this->field['all-styles'], FILTER_VALIDATE_BOOLEAN ) ) {
							if ( ! empty( $font['font-options']['variants'] ) ) {
								if ( ! isset( $this->parent->typography[ $font['font-family'] ]['all-styles'] ) || empty( $this->parent->typography[ $font['font-family'] ]['all-styles'] ) ) {
									$this->parent->typography[ $font['font-family'] ]['all-styles'] = array();
									foreach ( $font['font-options']['variants'] as $variant ) {
										$this->parent->typography[ $font['font-family'] ]['all-styles'][] = $variant['id'];
									}
								}
							}
						}

						if ( isset( $font['font-options'] ) && ! empty( $font['font-options'] ) && isset( $this->field['all-subsets'] ) && $this->field['all-styles'] ) {
							if ( ! empty( $font['font-options']['subsets'] ) ) {
								if ( ! isset( $this->parent->typography[ $font['font-family'] ]['all-subsets'] ) || empty( $this->parent->typography[ $font['font-family'] ]['all-subsets'] ) ) {
									$this->parent->typography[ $font['font-family'] ]['all-subsets'] = array();
									foreach ( $font['font-options']['subsets'] as $variant ) {
										$this->parent->typography[ $font['font-family'] ]['all-subsets'][] = $variant['id'];
									}
								}
							}
						}

						$style = '';

						if ( ! empty( $font['font-weight'] ) ) {
							if ( empty( $this->parent->typography[ $font['font-family'] ]['font-weight'] ) || ! in_array( $font['font-weight'], $this->parent->typography[ $font['font-family'] ]['font-weight'], true ) ) {
								$style = $font['font-weight'];
							}

							if ( ! empty( $font['font-style'] ) ) {
								$style .= $font['font-style'];
							}

							if ( empty( $this->parent->typography[ $font['font-family'] ]['font-style'] ) || ! in_array( $style, $this->parent->typography[ $font['font-family'] ]['font-style'], true ) ) {
								$this->parent->typography[ $font['font-family'] ]['font-style'][] = $style;
							}
						}

						if ( ! empty( $font['subsets'] ) ) {
							if ( empty( $this->parent->typography[ $font['font-family'] ]['subset'] ) || ! in_array( $font['subsets'], $this->parent->typography[ $font['font-family'] ]['subset'], true ) ) {
								$this->parent->typography[ $font['font-family'] ]['subset'][] = $font['subsets'];
							}
						}
					}
				}
			}
		}

		/**
		 * Localize standard, custom and typekit fonts.
		 */
		private function localize_std_fonts() {
			if ( false === $this->user_fonts ) {
				if ( isset( $this->parent->fonts['std'] ) && ! empty( $this->parent->fonts['std'] ) ) {
					return;
				}

				$this->parent->font_groups['std'] = array(
					'text'     => esc_html__( 'Standard Fonts', 'redux-framework' ),
					'children' => array(),
				);

				foreach ( $this->field['fonts'] as $font => $extra ) {
					$this->parent->font_groups['std']['children'][] = array(
						'id'          => $font,
						'text'        => $font,
						'data-google' => 'false',
					);
				}
			}

			if ( false !== $this->field['custom_fonts'] ) {
				// phpcs:ignored WordPress.NamingConventions.ValidHookName
				$this->field['custom_fonts'] = apply_filters( "redux/{$this->parent->args['opt_name']}/field/typography/custom_fonts", array() );

				if ( ! empty( $this->field['custom_fonts'] ) ) {
					foreach ( $this->field['custom_fonts'] as $group => $fonts ) {
						$this->parent->font_groups['customfonts'] = array(
							'text'     => $group,
							'children' => array(),
						);

						foreach ( $fonts as $family => $v ) {
							$this->parent->font_groups['customfonts']['children'][] = array(
								'id'          => $family,
								'text'        => $family,
								'data-google' => 'false',
							);
						}
					}
				}
			}

			// Typekit.
			// phpcs:ignored WordPress.NamingConventions.ValidHookName
			$typekit_fonts = apply_filters( "redux/{$this->parent->args['opt_name']}/field/typography/typekit_fonts", array() );

			if ( ! empty( $typekit_fonts ) ) {
				foreach ( $typekit_fonts as $group => $fonts ) {
					$this->parent->font_groups['typekitfonts'] = array(
						'text'     => $group,
						'children' => array(),
					);

					foreach ( $fonts as $family => $v ) {
						$this->parent->font_groups['typekitfonts']['children'][] = array(
							'text'        => $family,
							'id'          => $family,
							'data-google' => 'false',
						);
					}
				}
			}
		}

		/**
		 *   Construct the Google array from the stored JSON/HTML
		 */
		private function get_google_array() {
			if ( ( isset( $this->parent->fonts['google'] ) && ! empty( $this->parent->fonts['google'] ) ) || isset( $this->parent->fonts['google'] ) && false === $this->parent->fonts['google'] ) {
				return;
			}

			$fonts = Redux_Helpers::google_fonts_array( get_option( 'auto_update_redux_google_fonts', false ) );
			if ( empty( $fonts ) ) {
				$google_font = dirname( __FILE__ ) . '/googlefonts.php';
				$fonts       = include $google_font;
			}

			if ( true === $fonts ) {
				$this->parent->fonts['google'] = false;

				return;
			}

			if ( isset( $fonts ) && ! empty( $fonts ) && is_array( $fonts ) ) {
				$this->parent->fonts['google'] = $fonts;
				$this->parent->google_array    = $fonts;

				// optgroup.
				$this->parent->font_groups['google'] = array(
					'text'     => esc_html__( 'Google Webfonts', 'redux-framework' ),
					'children' => array(),
				);

				// options.
				foreach ( $this->parent->fonts['google'] as $font => $extra ) {
					$this->parent->font_groups['google']['children'][] = array(
						'id'          => $font,
						'text'        => $font,
						'data-google' => 'true',
					);
				}
			}
		}

		/**
		 * Clean up the Google Webfonts subsets to be human-readable
		 *
		 * @param array $var Font subset array.
		 *
		 * @return array
		 *
		 * @since ReduxFramework 0.2.0
		 */
		private function get_subsets( array $var ): array {
			$result = array();

			foreach ( $var as $v ) {
				if ( strpos( $v, '-ext' ) ) {
					$name = ucfirst( str_replace( '-ext', ' Extended', $v ) );
				} else {
					$name = ucfirst( $v );
				}

				$result[] = array(
					'id'   => $v,
					'name' => $name,
				);
			}

			return array_filter( $result );
		}

		/**
		 * Clean up the Google Webfonts variants to be human-readable
		 *
		 * @param array $var Font variant array.
		 *
		 * @return array
		 *
		 * @since ReduxFramework 0.2.0
		 */
		private function get_variants( array $var ): array {
			$result = array();
			$italic = array();

			foreach ( $var as $v ) {
				$name = '';
				if ( 1 === $v[0] ) {
					$name = 'Ultra-Light 100';
				} elseif ( 2 === $v[0] ) {
					$name = 'Light 200';
				} elseif ( 3 === $v[0] ) {
					$name = 'Book 300';
				} elseif ( 4 === $v[0] || 'r' === $v[0] || 'i' === $v[0] ) {
					$name = 'Normal 400';
				} elseif ( 5 === $v[0] ) {
					$name = 'Medium 500';
				} elseif ( 6 === $v[0] ) {
					$name = 'Semi-Bold 600';
				} elseif ( 7 === $v[0] ) {
					$name = 'Bold 700';
				} elseif ( 8 === $v[0] ) {
					$name = 'Extra-Bold 800';
				} elseif ( 9 === $v[0] ) {
					$name = 'Ultra-Bold 900';
				}

				if ( 'regular' === $v ) {
					$v = '400';
				}

				if ( strpos( $v, 'italic' ) || 'italic' === $v ) {
					$name .= ' Italic';
					$name  = trim( $name );
					if ( 'italic' === $v ) {
						$v = '400italic';
					}
					$italic[] = array(
						'id'   => $v,
						'name' => $name,
					);
				} else {
					$result[] = array(
						'id'   => $v,
						'name' => $name,
					);
				}
			}

			foreach ( $italic as $item ) {
				$result[] = $item;
			}

			return array_filter( $result );
		}

		/**
		 * Update google font array via AJAX call.
		 */
		public function google_fonts_update_ajax() {
			if ( ! isset( $_POST['nonce'] ) || ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'redux_update_google_fonts' ) ) ) {
				die( 'Security check' );
			}

			if ( isset( $_POST['data'] ) && 'automatic' === $_POST['data'] ) {
				update_option( 'auto_update_redux_google_fonts', true );
			}

			$fonts = Redux_Helpers::google_fonts_array( true );

			if ( ! empty( $fonts ) && ! is_wp_error( $fonts ) ) {
				echo wp_json_encode(
					array(
						'status' => 'success',
						'fonts'  => $fonts,
					)
				);
			} else {
				$err_msg = '';

				if ( is_wp_error( $fonts ) ) {
					$err_msg = $fonts->get_error_code();
				}

				echo wp_json_encode(
					array(
						'status' => 'error',
						'error'  => $err_msg,
					)
				);
			}

			die();
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
if ( ! class_exists( 'ReduxFramework_Typography' ) ) {
	class_alias( 'Redux_Typography', 'ReduxFramework_Typography' );
}

