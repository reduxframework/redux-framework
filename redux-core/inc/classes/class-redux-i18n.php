<?php
/**
 * Load the plugin text domain for translation.
 *
 * @package  Redux Framework/Classes
 * @since    3.0.5
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_I18n', false ) ) {

	/**
	 * Class Redux_I18n
	 */
	class Redux_I18n extends Redux_Class {

		/**
		 * Redux_I18n constructor.
		 *
		 * @param object $redux ReduxFramework pointer.
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux );

			add_action( 'init', array( $this, 'load' ) );
		}

		/**
		 * Load translations.
		 */
		public function load() {
			$domain = 'redux-framework';

			unload_textdomain( $domain );

			$core = $this->core();

			/**
			 * Locale for text domain
			 * filter 'redux/textdomain/basepath/{opt_name}'
			 */
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$locale = apply_filters( 'redux/locale', get_locale(), 'redux-framework' );
			$mofile = $domain . '-' . $locale . '.mo';

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$basepath = apply_filters( "redux/textdomain/basepath/{$core->args['opt_name']}", Redux_Core::$dir );

			$loaded = load_textdomain( $domain, $basepath . 'languages/' . $mofile );

			if ( ! $loaded ) {
				$mofile = WP_LANG_DIR . '/plugins/' . $mofile;

				load_textdomain( $domain, $mofile );
			}
		}
	}
}
