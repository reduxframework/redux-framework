<?php
/**
 * Redux Social Profiles Field Class
 *
 * @package Redux
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Social_Profiles
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Social_Profiles' ) ) {

	/**
	 * Main Redux_Social_Profiles class
	 *
	 * @since       1.0.0
	 */
	class Redux_Social_Profiles extends Redux_Field {

		/**
		 * Field ID.
		 *
		 * @var mixed|string
		 */
		public $field_id = '';

		/**
		 * Panel opt_name.
		 *
		 * @var string
		 */
		public $opt_name = '';

		/**
		 * Defaults array.
		 *
		 * @var array
		 */

		private $defaults = array();

		/**
		 * Set defaults.
		 */
		public function set_defaults() {
			$this->opt_name = $this->parent->args['opt_name'];
			$this->field_id = $this->field['id'];

			$this->defaults = Redux_Social_Profiles_Functions::get_default_data();
		}

		/**
		 * Rebuild Settings.
		 *
		 * @param array $settings Settings array.
		 *
		 * @return array
		 */
		private function rebuild_setttings( array $settings ): array {
			$fixed_arr = array();
			$stock     = '';

			foreach ( $this->defaults as $key => $arr ) {
				$search_default = true;

				$default_id = $arr['id'];

				foreach ( $settings as $a ) {
					if ( $default_id === $a['id'] ) {
						$search_default    = false;
						$fixed_arr[ $key ] = $a;
						break;
					}
				}

				if ( $search_default ) {
					if ( '' === $stock ) {
						$stock = Redux_Social_Profiles_Defaults::get_social_media_defaults();
						$stock = Redux_Social_Profiles_Functions::add_extra_icons( $stock );
					}

					foreach ( $stock as $def_arr ) {
						if ( $default_id === $def_arr['id'] ) {
							$fixed_arr[ $key ] = $def_arr;
							break;
						}
					}
				}
			}

			return $fixed_arr;
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
			if ( empty( $this->field ) ) {
				return;
			}

			global $pagenow, $post;

			$redux_settings = get_option( $this->opt_name );
			$settings       = $redux_settings[ $this->field_id ] ?? array();

			if ( is_admin() && ( 'post-new.php' === $pagenow || 'post.php' === $pagenow ) ) {
				$post_settings = get_post_meta( $post->ID, $this->field_id, true );

				if ( ! empty( $post_settings ) ) {
					$settings = $post_settings;
				}
			}

			$color_pickers = $this->field['color_pickers'] ?? true;

			// Icon container.
			echo '<div
                    class="redux-social-profiles-container ' . esc_attr( $this->field['class'] ) . '"
                    data-opt-name="' . esc_attr( $this->opt_name ) . '"
                    data-id="' . esc_attr( $this->field_id ) . '"
                  >';

			$show_msg = $this->field['hide_widget_msg'] ?? true;

			// translators: %1$s: Widget page HTML/URL. %s: widget admin URL.
			$def_msg = sprintf( esc_html__( 'Go to the %1$s page to add the Redux Social Widget to any active widget area.', 'redux-framework' ), sprintf( '<a href="%s">' . esc_html__( 'Widgets', 'redux-framework' ) . '</a> ', admin_url( 'widgets.php' ) ) );

			$msg = $this->field['widget_msg'] ?? $def_msg;

			if ( ! $show_msg ) {
				echo '<div class="redux-social-profiles-header">';
				echo $msg; // phpcs:ignore WordPress.Security.EscapeOutput
				echo '</div>';
			}

			echo '<div class="redux-social-profiles-selector-container">';
			echo '<ul id="redux-social-profiles-selector-list">';

			$settings = $this->rebuild_setttings( $settings );

			foreach ( $this->defaults as $key => $social_provider_default ) {
				$social_provider_option = ( $settings && is_array( $settings ) && array_key_exists( $key, $settings ) ) ? $settings[ $key ] : null;

				$icon    = ( $social_provider_option && array_key_exists( 'icon', $social_provider_option ) && $social_provider_option['icon'] ) ? $social_provider_option['icon'] : $social_provider_default['icon'];
				$name    = ( $social_provider_option && array_key_exists( 'name', $social_provider_option ) && $social_provider_option['name'] ) ? $social_provider_option['name'] : $social_provider_default['name'];
				$order   = ( $social_provider_option && array_key_exists( 'order', $social_provider_option ) ) ? $social_provider_option['order'] : $key;
				$order   = intval( $order );
				$enabled = ( $social_provider_option && array_key_exists( 'enabled', $social_provider_option ) && $social_provider_option['enabled'] ) ? $social_provider_option['enabled'] : $social_provider_default['enabled'];
				$display = ( $enabled ) ? 'enabled' : '';

				echo '<li class="redux-social-profiles-item-enable ' . esc_attr( $display ) . '" id="redux-social-profiles-item-enable-' . esc_attr( $key ) . '" data-key="' . esc_attr( $key ) . '" data-order="' . esc_attr( $order ) . '">';
				Redux_Social_Profiles_Functions::render_icon( $icon, '', '', $name );
				echo '</li>';
			}

			echo '</ul>';
			echo '</div>';

			echo '<ul id="redux-social-profiles-list">';

			foreach ( $this->defaults as $key => $social_provider_default ) {
				echo '<li id="redux-social-item-' . esc_attr( $key ) . '" data-key="' . esc_attr( $key ) . '" style="display: none;">';
				echo '<div class="redux-social-item-container">';

				$social_provider_option = ( $settings && is_array( $settings ) && array_key_exists( $key, $settings ) ) ? $settings[ $key ] : null;
				$icon                   = ( $social_provider_option && array_key_exists( 'icon', $social_provider_option ) && $social_provider_option['icon'] ) ? $social_provider_option['icon'] : $social_provider_default['icon'];
				$id                     = ( $social_provider_option && array_key_exists( 'id', $social_provider_option ) && $social_provider_option['id'] ) ? $social_provider_option['id'] : $social_provider_default['id'];
				$enabled                = ( $social_provider_option && array_key_exists( 'enabled', $social_provider_option ) && $social_provider_option['enabled'] ) ? $social_provider_option['enabled'] : $social_provider_default['enabled'];
				$name                   = ( $social_provider_option && array_key_exists( 'name', $social_provider_option ) && $social_provider_option['name'] ) ? $social_provider_option['name'] : $social_provider_default['name'];

				$label = ( $social_provider_option && array_key_exists( 'label', $social_provider_option ) && $social_provider_option['label'] ) ? $social_provider_option['label'] : __( 'Link URL', 'redux-framework' );

				$color = ( $social_provider_option && array_key_exists( 'color', $social_provider_option ) ) ? $social_provider_option['color'] : $social_provider_default['color'];
				$color = esc_attr( $color );

				$background = ( $social_provider_option && array_key_exists( 'background', $social_provider_option ) ) ? $social_provider_option['background'] : $social_provider_default['background'];
				$background = esc_attr( $background );

				$order = ( $social_provider_option && array_key_exists( 'order', $social_provider_option ) ) ? $social_provider_option['order'] : $key;
				$order = intval( $order );

				$url = ( $social_provider_option && array_key_exists( 'url', $social_provider_option ) ) ? $social_provider_option['url'] : $social_provider_default['url'];
				$url = esc_attr( $url );

				$profile_data = array(
					'id'         => $id,
					'icon'       => $icon,
					'enabled'    => $enabled,
					'url'        => $url,
					'color'      => $color,
					'background' => $background,
					'order'      => $order,
					'name'       => $name,
					'label'      => $label,
				);

				$profile_data = rawurlencode( wp_json_encode( $profile_data ) );

				echo '<input
	                    type="hidden"
	                    class="redux-social-profiles-hidden-data-' . esc_attr( $key ) . '"
	                    id="' . esc_attr( $this->field_id ) . '-' . esc_attr( $id ) . '-data"
	                    name="' . esc_attr( $this->field['name'] ) . esc_attr( $this->field['name_suffix'] ) . '[' . esc_attr( $key ) . '][data]"
	                    value="' . $profile_data . '" />'; // phpcs:ignore WordPress.Security.EscapeOutput

				echo '<div class="redux-icon-preview">';
				Redux_Social_Profiles_Functions::render_icon( $icon, $color, $background, $name );
				echo '&nbsp;</div>';

				echo '<div class="redux-social-profiles-item-name">';
				echo esc_html( $name );
				echo '</div>';

				echo '<div class="redux-social-profiles-item-enabled">';
				$checked = ( $enabled ) ? 'checked' : '';
				echo '<input type="checkbox" class="checkbox-' . esc_attr( $key ) . '" data-key="' . esc_attr( $key ) . '" value="1" ' . esc_attr( $checked ) . '/>';
				esc_html_e( 'Enabled', 'redux-framework' );
				echo '</div>';

				$color_class = $color_pickers ? '' : ' no-color-pickers';

				echo '<div class="redux-social-profiles-link-url input_wrapper' . esc_attr( $color_class ) . '">';
				echo '<label for="redux-social-profiles-url-' . esc_attr( $key ) . '-text" class="redux-text-url-label">' . esc_html( $label ) . '</label>';
				echo '<input id="redux-social-profiles-url-' . esc_attr( $key ) . '-text" class="redux-social-profiles-url-text" data-key="' . esc_attr( $key ) . '" type="text" value="' . esc_url( $url ) . '" />';
				echo '</div>';

				$reset_text = __( 'Reset', 'redux-framework' );
				echo '<div class="redux-social-profiles-item-reset">';
				echo '<a class="button" data-value="' . esc_attr( $key ) . '" value="' . esc_attr( $reset_text ) . '" />' . esc_html( $reset_text ) . '</a>';
				echo '</div>';

				if ( $color_pickers ) {
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$label = apply_filters( 'redux/extensions/social_profiles/' . $this->opt_name . '/color_picker/text', esc_html__( 'Text', 'redux-framework' ) );

					echo '<div class="redux-social-profiles-text-color picker_wrapper" >';
					echo '<label for="redux-social-profiles-color-picker-' . esc_attr( $key ) . ' text" class="redux-text-color-label">' . esc_html( $label ) . '</label>';
					echo '<input
                            class="redux-social-profiles-color-picker-' . esc_attr( $key ) . ' text"
                            id="redux-social-profiles-color-picker-' . esc_attr( $key ) . ' text"
                            type="text"
                            value="' . esc_attr( $color ) . '"
                            data-key="' . esc_attr( $key ) . '"
                        />';
					echo '</div>';

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$label = apply_filters( 'redux/extensions/social_profiles/' . $this->opt_name . '/color_picker/background', esc_html__( 'Background', 'redux-framework' ) );

					echo '<div class="redux-social-profiles-background-color picker_wrapper">';
					echo '<label for="redux-social-profiles-color-picker-' . esc_attr( $key ) . ' background" class="redux-background-color-label" for="redux-social-profiles-color-picker-' . esc_attr( $key ) . ' background">' . esc_html( $label ) . '</label>';
					echo '<input
                            class="redux-social-profiles-color-picker-' . esc_attr( $key ) . ' background"
                            id="redux-social-profiles-color-picker-' . esc_attr( $key ) . ' background"
                            type="text"
                            value="' . esc_attr( $background ) . '"
                            data-key="' . esc_attr( $key ) . '"
                        />';
					echo '</div>';
				}

				echo '<div class="redux-social-profiles-item-order">';
				echo '<input
                        type="hidden"
                        value="' . esc_attr( $order ) . '"
                    />';
				echo '</div>';

				echo '</div>';
				echo '</li>';
			}

			echo '</ul>';
			echo '</div>';
		}

		/**
		 * This function is unused, but necessary to trigger output.
		 *
		 * @param mixed $data CSS data.
		 *
		 * @return mixed|string|void
		 */
		public function css_style( $data ) {
			return $data;
		}

		/**
		 * Used to enqueue to the front-end
		 *
		 * @param string|null|array $style Style.
		 */
		public function output( $style = '' ) {
			if ( ! empty( $this->value ) ) {
				foreach ( $this->value as $arr ) {
					if ( $arr['enabled'] ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement
						Redux_Functions_Ex::enqueue_font_awesome();
					}
				}
			}
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
			if ( empty( $this->field ) ) {
				return;
			}

			$min = Redux_Functions::isMin();

			Redux_Functions_Ex::enqueue_font_awesome();

			// Field dependent JS.
			wp_enqueue_script(
				'redux-field-social-profiles',
				$this->url . 'redux-social-profiles' . $min . '.js',
				array( 'jquery', 'jquery-ui-sortable', 'redux-spectrum-js', 'redux-js' ),
				Redux_Extension_Social_Profiles::$version,
				true
			);

			wp_localize_script(
				'redux-field-social-profiles',
				'reduxSocialDefaults',
				$this->defaults
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-social-profiles',
					$this->url . 'redux-social-profiles.css',
					array( 'redux-spectrum-css' ),
					Redux_Extension_Social_Profiles::$version
				);
			}
		}
	}
}
