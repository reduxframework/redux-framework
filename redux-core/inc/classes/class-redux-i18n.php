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

			add_action( 'init', array( $this, 'load' ) );
			//$this->load( $file );
		}

		/**
		 * Load translations.
		 *
		 * @param string $file Path to translation files.
		 */
		public function load( string $file ) {
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

				$loaded = load_textdomain( $domain, $mofile );
			}
		}
	}
}
