<?php
/**
 * PHP version compatibility functionality.
 *
 * @package redux-framework
 */

if ( ! class_exists( 'Redux_PHP' ) ) {

	/**
	 * Redux_PHP class.
	 */
	class Redux_PHP {

		/**
		 * Minimum PHP version.
		 *
		 * @var string
		 *
		 * @noinspection PhpMissingFieldTypeInspection
		 */
		public static $minimum_version = '7.4.0';

		/**
		 * Is PHP version met.
		 *
		 * @return bool
		 */
		public static function version_met(): bool {
			return version_compare( PHP_VERSION, self::$minimum_version, '>=' );
		}

		/**
		 * Display incompatibility message on admin screen.
		 *
		 * @return void
		 */
		public static function php_version_nope() {
			printf(
				'<div id="redux-php-nope" class="notice notice-error"><p>%s</p></div>',
				wp_kses(
					sprintf(
					/* translators: 1: Redux Framework, 2: Required PHP version number, 3: Current PHP version number, 4: URL of PHP update help page */
						__( 'The %1$s plugin requires PHP version %2$s or higher. This site is running PHP version %3$s. The theme/plugin that relies on Redux will not run properly without a PHP update. <a href="%4$s">Learn about updating PHP</a>.', 'redux-framework' ),
						'Redux Framework',
						'<strong>7.4.0</strong>',
						'<strong>' . PHP_VERSION . '</strong>',
						'https://wordpress.org/support/update-php/'
					),
					array(
						'a'      => array(
							'href' => array(),
						),
						'strong' => array(),
					)
				)
			);
		}
	}
}
