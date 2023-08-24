<?php
/**
 * Redux User Meta Extension Helpers
 *
 * @package Redux
 */

defined( 'ABSPATH' ) || exit;

// Helper function to bypass WordPress hook priorities.  ;).
if ( ! function_exists( 'create_term_redux_users' ) ) {

	/**
	 * Create_term_redux_users.
	 *
	 * @param string $profile_id Profile ID.
	 */
	function create_term_redux_users( string $profile_id ) {
		$instances = Redux::all_instances();

		foreach ( $_POST as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification
			if ( is_array( $value ) && isset( $instances[ $key ] ) ) {
				$instances[ $key ]->extensions['users']->user_meta_save( $profile_id );
			}
		}
	}
}

add_action( 'create_term', 'create_term_redux_users', 4 );
