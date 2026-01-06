<?php // phpcs:ignore WordPress.Files.FileName

/**
 * Redux Secure Token Class
 *
 * @class Redux_Secure_Token
 * @version 4.5.10
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Secure_Token', false ) ) {

	/**
	 * Redux_Secure_Token.
	 *
	 * @since 4.5.10
	 */
	class Redux_Secure_Token {
		/**
		 * Generate a secure, single-use token.
		 *
		 * @param string          $action  Nonce action.
		 * @param int|string|null $user_id User ID.
		 *
		 * @return false|mixed|null
		 */
		public static function generate( string $action, $user_id = null ) {
			$user_id = $user_id ?? get_current_user_id();

			if ( ! $user_id ) {
				return false; // No tokens for unauthenticated users.
			}

			$token = wp_generate_password( 32, false );
			$hash  = wp_hash( $token . $action . $user_id );

			// Store with expiration (1 hour).
			set_transient(
				'redux_token_' . $hash,
				array(
					'user_id' => $user_id,
					'action'  => $action,
					'created' => time(),
				),
				HOUR_IN_SECONDS
			);

			return $token;
		}

		/**
		 * Verify and consume a token.
		 *
		 * @param string $token  Security token.
		 * @param string $action Nonce action.
		 *
		 * @return bool
		 */
		public static function verify( string $token, string $action ): bool {
			$user_id = get_current_user_id();

			if ( ! $user_id ) {
				return false;
			}

			$hash = wp_hash( $token . $action . $user_id );
			$data = get_transient( 'redux_token_' . $hash );

			if ( ! $data || $data['user_id'] !== $user_id || $data['action'] !== $action ) {
				return false;
			}

			// Delete token after use (single-use).
			delete_transient( 'redux_token_' . $hash );

			return true;
		}
	}
}
