<?php // phpcs:ignore WordPress.Files.FileName

/**
 * Redux Rate Limiter Class
 *
 * @class Secure Token
 * @version 4.5.10
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Rate_Limiter', false ) ) {

	/**
	 * Redux_Rate_Limiter.
	 *
	 * @since 4.5.10
	 */
	class Redux_Rate_Limiter {

		/**
		 * Limits for functions that require them.
		 *
		 * @var array[]
		 */
		private static array $limits = array(
			'download_options' => array(
				'max'    => 5,
				'window' => 300,
			),  // 5 per 5 minutes.
			'color_schemes'    => array(
				'max'    => 5,
				'window' => 300,
			), // 5 per 5 minutes.
		);

		/**
		 * Check for the rate limit.
		 *
		 * @param string $action Function to check.
		 *
		 * @return bool
		 */
		public static function check( string $action ): bool {
			if ( ! isset( self::$limits[ $action ] ) ) {
				return true;
			}

			$limit = self::$limits[ $action ];

			$ip  = self::get_client_ip();
			$key = 'redux_rate_' . md5( $action . $ip );

			$current = get_transient( $key );

			if ( false === $current ) {
				set_transient( $key, 1, $limit['window'] );
				return true;
			}

			if ( $current >= $limit['max'] ) {
				return false; // Rate limited.
			}

			set_transient( $key, $current + 1, $limit['window'] );
			return true;
		}

		/**
		 * Get the client's IP address.
		 *
		 * @return mixed|string
		 */
		private static function get_client_ip() {
			$ip = Redux_Core::$server['REMOTE_ADDR'];

			if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				$ips = explode( ',', Redux_Core::$server['HTTP_X_FORWARDED_FOR'] );
				$ip  = trim( $ips[0] );
			}

			return filter_var( $ip, FILTER_VALIDATE_IP ) ?? '0.0.0.0';
		}
	}
}
