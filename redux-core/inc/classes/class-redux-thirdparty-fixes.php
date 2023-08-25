<?php
/**
 * Redux ThirdParty Fixes Class
 *
 * @class Redux_ThirdParty_Fixes
 * @version 3.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_ThirdParty_Fixes', false ) ) {

	/**
	 * Class Redux_ThirdParty_Fixes
	 */
	class Redux_ThirdParty_Fixes extends Redux_Class {

		/**
		 * Redux_ThirdParty_Fixes constructor.
		 *
		 * @param object $redux ReduxFramework pointer.
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux );

			$this->gt3_page_builder();

			// These are necessary to override an outdated extension embedded in themes
			// that are loaded via the antiquated 'loader.php' method.
			add_filter( 'redux/extension/' . $this->parent->args['opt_name'] . '/repeater', array( $this, 'repeater_extension_override' ) );
			add_filter( 'redux/extension/' . $this->parent->args['opt_name'] . '/metaboxes', array( $this, 'metaboxes_extension_override' ) );
			add_filter( 'redux/extension/' . $this->parent->args['opt_name'] . '/social_profiles', array( $this, 'social_profiles_extension_override' ) );
			add_filter( 'redux/extension/' . $this->parent->args['opt_name'] . '/widget_areas', array( $this, 'widget_areas_extension_override' ) );
			add_filter( 'redux/extension/' . $this->parent->args['opt_name'] . '/custom_fonts', array( $this, 'custom_fonts_extension_override' ) );
			add_filter( 'redux/extension/' . $this->parent->args['opt_name'] . '/icon_select', array( $this, 'icon_select_extension_override' ) );
		}

		/**
		 * Icon Select extension override.
		 *
		 * @return string
		 */
		public function icon_select_extension_override(): string {
			return Redux_core::$dir . 'inc/extensions/icon_select/class-redux-extension-icon-select.php';
		}

		/**
		 * Widget Area extension override.
		 *
		 * @return string
		 */
		public function custom_fonts_extension_override(): string {
			return Redux_core::$dir . 'inc/extensions/custom_fonts/class-redux-extension-custom-fonts.php';
		}

		/**
		 * Widget Area extension override.
		 *
		 * @return string
		 */
		public function widget_areas_extension_override(): string {
			return Redux_core::$dir . 'inc/extensions/widget_areas/class-redux-extension-widget-areas.php';
		}

		/**
		 * Repeater extension override.
		 *
		 * @return string
		 */
		public function repeater_extension_override(): string {
			return Redux_core::$dir . 'inc/extensions/repeater/class-redux-extension-repeater.php';
		}

		/**
		 * Metaboxes extension override.
		 *
		 * @return string
		 */
		public function metaboxes_extension_override(): string {
			return Redux_core::$dir . 'inc/extensions/metaboxes/class-redux-extension-metaboxes.php';
		}

		/**
		 * Social Profiles extension override.
		 *
		 * @return string
		 */
		public function social_profiles_extension_override(): string {
			return Redux_core::$dir . 'inc/extensions/social_profiles/class-redux-extension-social-profiles.php';
		}

		/**
		 * GT3 Page Builder fix.
		 */
		private function gt3_page_builder() {
			// Fix for the GT3 page builder: http://www.gt3themes.com/wordpress-gt3-page-builder-plugin/.
			if ( has_action( 'ecpt_field_options_' ) ) {
				global $pagenow;

				if ( 'admin.php' === $pagenow ) {
					remove_action( 'admin_init', 'pb_admin_init' );
				}
			}
		}
	}
}
