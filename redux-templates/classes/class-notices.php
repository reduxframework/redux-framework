<?php // phpcs:ignore WordPress.Files.FileName

/**
 * Notices class to ensure WP is the proper version (block editor exists).
 *
 * @since 4.0.0
 * @package Redux Framework
 */


namespace ReduxTemplates;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * ReduxTemplates Notices.
 *
 * @since 4.0.0
 */
class Notices {

	/**
	 * PHP Error Notice.
	 *
	 * @return void
	 * @since 4.0.0
	 */
	public static function php_error_notice() {
		$message      = sprintf( /* translators: %s: php version number */
			esc_html__( 'ReduxTemplates requires PHP version %s or more.', 'redux-framework' ),
			'5.4'
		);
		$html_message = sprintf( '<div class="notice notice-error is-dismissible">%s</div>', wpautop( $message ) );
		echo wp_kses_post( $html_message );
	}

	/**
	 * WordPress version error notice.
	 *
	 * @return void
	 * @since 4.0.0
	 */
	public static function wordpress_error_notice() {
		$message      = sprintf( /* translators: %s: WordPress version number */
			esc_html__( 'ReduxTemplates requires WordPress version %s or more.', 'redux-framework' ),
			'4.7'
		);
		$html_message = sprintf( '<div class="notice notice-error is-dismissible">%s</div>', wpautop( $message ) );
		echo wp_kses_post( $html_message );
	}
}

