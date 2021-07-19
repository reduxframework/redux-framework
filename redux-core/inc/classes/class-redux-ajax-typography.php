<?php
/**
 * Redux Typography AJAX Class
 *
 * @class Redux_Core
 * @version 4.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_AJAX_Typography', false ) ) {

	/**
	 * Class Redux_AJAX_Typography
	 */
	class Redux_AJAX_Typography extends Redux_Class {

		/**
		 * Redux_AJAX_Typography constructor.
		 *
		 * @param object $parent RedusFramework object.
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent );
			add_action( 'wp_ajax_redux_update_google_fonts', array( $this, 'google_fonts_update' ) );
		}

		/**
		 * Google font AJAX callback
		 *
		 * @return void
		 */
		public function google_fonts_update() {
			$field_class = 'Redux_typography';

			if ( ! class_exists( $field_class ) ) {
				$dir = str_replace( '/classes', '', Redux_Functions_Ex::wp_normalize_path( dirname( __FILE__ ) ) );

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$class_file = apply_filters( 'redux-typeclass-load', $dir . '/fields/typography/class-redux-typography.php', $field_class );
				if ( $class_file ) {
					require_once $class_file;
				}
			}

			if ( class_exists( $field_class ) && method_exists( $field_class, 'google_fonts_update_ajax' ) ) {
				$f = new $field_class( array(), '', $this->parent );

				return $f->google_fonts_update_ajax();
			}

			die();
		}
	}
}
