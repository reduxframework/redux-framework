<?php
/**
 * Redux Customizer Section Dev Class
 *
 * @class Redux_Customizer_Section_Dev
 * @version 4.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Customizer_Section_Dev', false ) ) {

	/**
	 * Customizer section representing widget area (sidebar).
	 *
	 * @package    WordPress
	 * @subpackage Customize
	 * @since      4.1.0
	 * @see        WP_Customize_Section
	 */
	class Redux_Customizer_Section_Dev extends WP_Customize_Section {

		/**
		 * Field render.
		 */
		protected function render() {}

	}
}
