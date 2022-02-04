<?php // phpcs:ignore WordPress.Files.FileName

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Installer_Muter', false ) ) {

	/**
	 * Redux_Installer_Muter.
	 *
	 * @since 4.0.0
	 */
	class Redux_Installer_Muter extends WP_Upgrader_Skin {

		/**
		 * Suppress feedback.
		 *
		 * @param string|null $string  A string.
		 * @param array|null  ...$args Passed args.
		 *
		 * @return void
		 * @since 4.0.0
		 */
		public function feedback( $string, ...$args ) {
			/* no output */
		}
	}
}
