<?php
/**
 * Redux Color Scheme Field Class
 *
 * @package Redux Extentions
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Color_Scheme
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Color_Scheme' ) ) {

	/**
	 * Main ReduxFramework_color_scheme class
	 *
	 * @since       1.0.0
	 */
	class Redux_Color_Scheme extends Redux_Field {

		/**
		 * Set Defaults.
		 */
		public function set_defaults() {
			// Validate.
			$this->field['options']['picker_font_size'] = $this->field['options']['picker_font_size'] ?? '11px';
			$this->field['options']['picker_gap']       = $this->field['options']['picker_gap'] ?? '60px';

			$this->field['options']['show_input']             = $this->field['options']['show_input'] ?? true;
			$this->field['options']['show_initial']           = $this->field['options']['show_initial'] ?? true;
			$this->field['options']['show_alpha']             = $this->field['options']['show_alpha'] ?? true;
			$this->field['options']['show_palette']           = $this->field['options']['show_palette'] ?? true;
			$this->field['options']['show_palette_only']      = $this->field['options']['show_palette_only'] ?? false;
			$this->field['options']['max_palette_size']       = $this->field['options']['max_palette_size'] ?? 10;
			$this->field['options']['show_selection_palette'] = $this->field['options']['show_selection_palette'] ?? true;
			$this->field['options']['allow_empty']            = $this->field['options']['allow_empty'] ?? true;
			$this->field['options']['clickout_fires_change']  = $this->field['options']['clickout_fires_change'] ?? false;
			$this->field['options']['choose_text']            = $this->field['options']['choose_text'] ?? 'Choose';
			$this->field['options']['cancel_text']            = $this->field['options']['cancel_text'] ?? 'Cancel';
			$this->field['options']['show_buttons']           = $this->field['options']['show_buttons'] ?? true;
			$this->field['options']['container_class']        = $this->field['options']['container_class'] ?? 'redux-colorpicker-container';
			$this->field['options']['replacer_class']         = $this->field['options']['replacer_class'] ?? 'redux-colorpicker-replacer';
			$this->field['options']['use_extended_classes']   = $this->field['options']['use_extended_classes'] ?? false;
			$this->field['options']['palette']                = $this->field['options']['palette'] ?? null;
			$this->field['simple']                            = $this->field['simple'] ?? false;

			// Convert an empty array to null, if there.
			$this->field['options']['palette'] = empty( $this->field['options']['palette'] ) ? null : $this->field['options']['palette'];

			$this->field['no_compiler_output'] = $this->field['no_compiler_output'] ?? false;

			$this->field['output_transparent'] = $this->field['output_transparent'] ?? false;
			$this->field['accordion']          = $this->field['accordion'] ?? true;

			// tooltips.
			$this->field['tooltip_toggle'] = $this->field['tooltip_toggle'] ?? true;

			$this->field['tooltips']['style']['color']   = $this->field['tooltips']['style']['color'] ?? 'light';
			$this->field['tooltips']['style']['shadow']  = $this->field['tooltips']['style']['shadow'] ?? true;
			$this->field['tooltips']['style']['rounded'] = $this->field['tooltips']['style']['rounded'] ?? false;
			$this->field['tooltips']['style']['style']   = $this->field['tooltips']['style']['style'] ?? '';

			$this->field['tooltips']['position']['my'] = $this->field['tooltips']['position']['my'] ?? 'top center';
			$this->field['tooltips']['position']['at'] = $this->field['tooltips']['position']['at'] ?? 'bottom center';

			$this->field['tooltips']['effect']['show_effect']   = $this->field['tooltips']['effect']['show_effect'] ?? 'slide';
			$this->field['tooltips']['effect']['show_duration'] = $this->field['tooltips']['effect']['show_duration'] ?? 500;
			$this->field['tooltips']['effect']['show_event']    = $this->field['tooltips']['effect']['show_event'] ?? 'mouseover';
			$this->field['tooltips']['effect']['hide_effect']   = $this->field['tooltips']['effect']['hide_effect'] ?? 'slide';
			$this->field['tooltips']['effect']['hide_duration'] = $this->field['tooltips']['effect']['hide_duration'] ?? 500;
			$this->field['tooltips']['effect']['hide_effect']   = $this->field['tooltips']['effect']['hide_effect'] ?? 'mouseleave';
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
			$field_id = esc_attr( $this->field['id'] );

			// Set field ID, just in case.
			Redux_Color_Scheme_Functions::$field_id = $field_id;
			Redux_Color_Scheme_Functions::$field    = $this->field;
			Redux_Color_Scheme_Functions::$parent   = $this->parent;

			if ( isset( $this->field['select'] ) ) {
				if ( is_array( $this->field['select'] ) && ! empty( $this->field['select'] ) ) {
					Redux_Color_Scheme_Functions::$select = $this->field['select'];
				}
			}

			// Nonce.
			$nonce = wp_create_nonce( "redux_{$this->parent->args['opt_name']}_color_schemes" );

			// Modal message.
			echo '<div id="redux-' . esc_attr( $field_id ) . '-scheme-message-notice" style="display:none; cursor: default">';
			echo '    <h2>message</h2>';
			echo '    <input type="button" id="redux-' . esc_attr( $field_id ) . '-scheme-ok" value="OK" />';
			echo '</div>';

			// Waiting message.
			echo '<div id="redux-' . esc_attr( $field_id ) . '-scheme-wait-message" style="display:none;">';
			echo '   <h1><img alt="Please wait..." src="' . esc_url( $this->url ) . 'img/busy.gif" /> Please wait...</h1>';
			echo '</div>';

			// Delete dialog.
			echo '<div id="redux-' . esc_attr( $field_id ) . '-delete-scheme-question" style="display:none; cursor: default">';
			echo '    <h2>Are you sure you want to delete this scheme?</h2>';
			echo '    <input type="button" id="redux-' . esc_attr( $field_id ) . '-delete-scheme-yes" value="Yes" />';
			echo '    <input type="button" id="redux-' . esc_attr( $field_id ) . '-delete-scheme-no" value="No" />';
			echo '</div>';

			// Select2 params.
			if ( isset( $this->field['select2'] ) ) { // if there are any, let's pass them to js.
				$select2_params = wp_json_encode( $this->field['select2'] );
				$select2_params = htmlspecialchars( $select2_params, ENT_QUOTES );

				echo '<input type="hidden" class="select2_params" value="' . $select2_params . '">'; // phpcs:ignore WordPress.Security.EscapeOutput
			}

			$tt_in_use = Redux_Color_Scheme_Functions::tooltips_in_use( $this->field );

			$tooltips = '';
			if ( $tt_in_use ) {
				$tooltips = rawurlencode( wp_json_encode( $this->field['tooltips'] ) );
			}

			$tt_toggle_state = Redux_Color_Scheme_Functions::get_tooltip_toggle_state();

			// Color picker container.
			// phpcs:disable WordPress.Security.EscapeOutput
			echo '<div
                      class="redux-color-scheme-container ' . esc_attr( $this->field['class'] ) . '"
                      data-id="' . esc_attr( $field_id ) . '"
                      data-opt-name="' . esc_attr( $this->parent->args['opt_name'] ) . '"
                      data-nonce="' . esc_attr( $nonce ) . '"
                      data-picker-gap="' . esc_attr( $this->field['options']['picker_gap'] ) . '"
                      data-picker-font-size="' . esc_attr( $this->field['options']['picker_font_size'] ) . '"
                      data-accordion="' . esc_attr( $this->field['accordion'] ) . '"
                      data-tooltips="' . $tooltips . '"
                      data-show-tooltips="' . esc_attr( $tt_toggle_state ) . '"
                      data-show-input="' . esc_attr( $this->field['options']['show_input'] ) . '"
                      data-show-initial="' . esc_attr( $this->field['options']['show_initial'] ) . '"
                      data-show-alpha="' . esc_attr( $this->field['options']['show_alpha'] ) . '"
                      data-show-palette="' . esc_attr( $this->field['options']['show_palette'] ) . '"
                      data-show-palette-only="' . esc_attr( $this->field['options']['show_palette_only'] ) . '"
                      data-show-selection-palette="' . esc_attr( $this->field['options']['show_selection_palette'] ) . '"
                      data-max-palette-size="' . esc_attr( $this->field['options']['max_palette_size'] ) . '"
                      data-allow-empty="' . esc_attr( $this->field['options']['allow_empty'] ) . '"
                      data-clickout-fires-change="' . esc_attr( $this->field['options']['clickout_fires_change'] ) . '"
                      data-choose-text="' . esc_attr( $this->field['options']['choose_text'] ) . '"
                      data-cancel-text="' . esc_attr( $this->field['options']['cancel_text'] ) . '"
                      data-palette="' . rawurlencode( wp_json_encode( $this->field['options']['palette'] ) ) . '"
                  >';
			// phpcs:enable WordPress.Security.EscapeOutput

			// Hide scheme save stuff on simple mode.
			if ( false === $this->field['simple'] ) {
				echo '<div>';
				// Select container.
				echo '<div class="redux-scheme-select-container input_wrapper">';
				echo '    <span class="redux-label redux-select-scheme-label">Scheme:</span>';

				// Output scheme selector.
				echo Redux_Color_Scheme_Functions::get_scheme_select_html( '' ); // phpcs:ignore WordPress.Security.EscapeOutput

				echo '</div>';

				// Text input.
				echo '<div class="redux-scheme-name input_wrapper">';
				echo '  <span class="redux-label redux-text-scheme-label">Name:</span>';
				echo '      <input
                                type="text"
                                class="noUpdate redux-scheme-input-' . esc_attr( $field_id ) . '"
                                id="redux-scheme-input"
                            />';
				echo '</div>';

				// Action buttons/links.
				echo '  <div class="redux-action-links">';
				echo '      <span class="redux-label redux-action-scheme-label">Actions:</span>';

				// Save button.
				echo '          <a
                                    href="javascript:void(0);"
                                    id="redux-' . esc_attr( $field_id ) . '-save-scheme-button"
                                    class="redux-save-scheme-button button-secondary">' . esc_html__( 'Add', 'redux-framework' ) . '
                                </a>';

				// Delete button.
				echo '          <a
                                    href="javascript:void(0);"
                                    id="redux-' . esc_attr( $field_id ) . '-delete-scheme-button"
                                    class="redux-delete-scheme-button button-secondary">' . esc_html__( 'Delete', 'redux-framework' ) . '
                                </a>';

				$link = admin_url( 'admin-ajax.php?action=redux_color_schemes&type=export&nonce=' . esc_attr( $nonce ) ) . '&opt_name=' . esc_attr( $this->parent->args['opt_name'] );

				// Export button.
				echo '          <a
                                    href="' . esc_url( $link ) . '"
                                    id="redux-' . esc_attr( $field_id ) . '-export-scheme-button"
                                    data-opt-name="' . esc_attr( $this->parent->args['opt_name'] ) . '"
                                    data-submit="' . esc_url( $this->url ) . '"
                                    class="redux-export-scheme-button button-primary">' . esc_html__( 'Export', 'redux-framework' ) . '
                                </a>';

				// Import button.
				echo '          <a
                                    href="javascript:void(0);"
                                    id="redux-' . esc_attr( $field_id ) . '-import-scheme-button"
                                    data-submit="' . esc_url( $this->url ) . '"
                                    data-nonce="' . esc_attr( md5( 'color_scheme_import' ) ) . '"
                                    class="noUpdate redux-import-scheme-button button-secondary">' . esc_html__( 'Import', 'redux-framework' ) . '
                                </a>';

				if ( $this->field['tooltip_toggle'] && $tt_in_use ) {
					$checked = '';
					if ( $tt_toggle_state ) {
						$checked = 'checked';
					}

					echo '<div class="redux-color-scheme-tooltip-checkbox">';
					echo '<input class="" name="' . esc_attr( $this->parent->args['opt_name'] ) . '[redux-color-scheme-tooltip-toggle]" id="redux-' . esc_attr( $field_id ) . '-tooltip-checkbox" type="checkbox" value="' . esc_attr( $tt_toggle_state ) . '" ' . esc_html( $checked ) . '>Show Tooltips';
					echo '</div>';
				}

				echo '  </div>';
				echo '</div>';
				echo '<div>';
				echo '<hr/>';
			}

			// Set field class.  Gotta do it this way so custom class makes
			// it through AJAX.
			Redux_Color_Scheme_Functions::$field_class = 'redux-color-scheme ';

			// Color picker layout.
			echo Redux_Color_Scheme_Functions::get_current_color_scheme_html(); // phpcs:ignore WordPress.Security.EscapeOutput

			echo '</div>';
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
			$min = Redux_Functions::is_min();

			// One-Click Upload.
			wp_enqueue_script(
				'redux-ocupload',
				$this->url . 'vendor/jquery.ocupload' . $min . '.js',
				array( 'jquery' ),
				'1.1.2',
				true
			);

			// Field dependent JS.
			wp_enqueue_script(
				'redux-field-color-scheme',
				$this->url . 'redux-color-scheme' . $min . '.js',
				array( 'jquery', 'redux-spectrum-js', 'select2-js', 'redux-block-ui' ),
				Redux_Extension_Color_Scheme::$version,
				true
			);

			// Field CSS.
			if ( true === $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-color-scheme',
					$this->url . 'redux-color-scheme.css',
					array( 'redux-spectrum-css', 'select2-css' ),
					Redux_Extension_Color_Scheme::$version
				);
			}

			// AJAX.
			wp_localize_script(
				'redux-field-color-scheme',
				'redux_ajax_script',
				array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
			);
		}

		/**
		 * Get default data.
		 *
		 * @param string $id Field ID.
		 *
		 * @return array
		 */
		private function data_from_default( string $id ): array {
			$x = $this->field;

			$data = array();

			foreach ( $x['default'] as $arr ) {
				if ( $arr['id'] === $id ) {
					$data['selector']  = $arr['selector'] ?? '';
					$data['mode']      = $arr['mode'] ?? '';
					$data['important'] = $arr['important'] ?? '';

					break;
				}
			}

			return $data;
		}

		/**
		 * If this field requires any scripts or css, define this function and register/enqueue the scripts/css
		 *
		 * @since       1.0.0
		 * @access      private
		 * @return      string
		 */
		private function get_css(): string {

			// No notices.
			$css = '';

			// Must be an array.
			if ( is_array( $this->value ) ) {

				// Enum array to parse values.
				foreach ( $this->value as $id => $val ) {

					// Default selector data, so we always have current info.
					$def_data = $this->data_from_default( $id );

					// Sanitize alpha.
					$alpha = $val['alpha'] ?? 1;

					// Sanitize color.
					$color = $val['color'] ?? '';

					// Only build rgba output if alpha ia less than 1.
					if ( $alpha < 1 && '' !== $alpha ) {
						$color = Redux_Helpers::hex2rgba( $color, $alpha );
					}

					$important = $def_data['important'] ?? false;
					if ( true === $important ) {
						$important = ' !important';
					} else {
						$important = '';
					}

					// Sanitize selector.
					$selector = $def_data['selector'] ?? '';

					if ( is_array( $selector ) ) {
						foreach ( $selector as $mode => $element ) {
							if ( '' !== $element && '' !== $color ) {
								$css .= $element . '{' . $mode . ': ' . $color . $important . ';}';
							}
						}
					} else {
						// Sanitize mode, default to 'color'.
						$mode = $def_data['mode'] ?? 'color';

						// Only build value if selector is indicated.
						if ( '' !== $selector && '' !== $color ) {
							$css .= $selector . '{' . $mode . ': ' . $color . $important . ';} ';
						}
					}
				}
			}

			return $css;
		}

		/**
		 * Generate CSS style.
		 *
		 * @param string $data Field data.
		 *
		 * @return string
		 */
		public function css_style( $data ): string {
			return '';
		}

		/**
		 * Output Function.
		 * Used to enqueue to the front-end
		 *
		 * @param       string|null|array $style Style.
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function output( $style = '' ) {
			if ( ! empty( $this->value ) ) {
				if ( ! empty( $this->field['output'] ) && ( true === $this->field['output'] ) ) {
					$css                      = $this->get_css();
					$this->parent->outputCSS .= $css;
				}

				if ( ! $this->field['no_compiler_output'] ) {
					if ( ! empty( $this->field['compiler'] ) && ( true === $this->field['compiler'] ) ) {
						$css                        = $this->get_css();
						$this->parent->compilerCSS .= $css;
					}
				}
			}
		}
	}
}
