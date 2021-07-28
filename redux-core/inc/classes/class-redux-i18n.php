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
		 * @param object $parent ReduxFramework pointer.
		 * @param string $file Translation file.
		 */
		public function __construct( $parent, string $file ) {
			parent::__construct( $parent );

			$this->load( $file );
		}

		/**
		 * Load translations.
		 *
		 * @param string $file Path to translation files.
		 */
		private function load( string $file ) {
			$domain = 'redux-framework';

			$core = $this->core();

			/**
			 * Locale for text domain
			 * filter 'redux/textdomain/basepath/{opt_name}'
			 *
			 * @param string     The locale of the blog or from the 'locale' hook
			 * @param string     'redux-framework'  text domain
			 */
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$locale = apply_filters( 'redux/locale', get_locale(), 'redux-framework' );
			$mofile = $domain . '-' . $locale . '.mo';

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$basepath = apply_filters( "redux/textdomain/basepath/{$core->args['opt_name']}", Redux_Core::$dir );

			$loaded = load_textdomain( $domain, Redux_Core::$dir . 'languages/' . $mofile );

			if ( ! $loaded ) {
				$mofile = WP_LANG_DIR . '/plugins/' . $mofile;

				$loaded = load_textdomain( $domain, $mofile );
			}
		}
	}
}
