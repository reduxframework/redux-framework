<?php
/**
 * Redux Taxonomy Meta Helpers
 *
 * @package Redux
 */

defined( 'ABSPATH' ) || exit;

// Helper function to bypass WordPress hook priorities.
if ( ! function_exists( 'create_term_redux_taxonomy' ) ) {

	/**
	 * Create.
	 *
	 * @param int $term_id  Term ID.
	 */
	function create_term_redux_taxonomy( int $term_id ) {
		$instances = Redux::all_instances();

		foreach ( $_POST as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification
			if ( is_array( $value ) && isset( $instances[ $key ] ) ) {
				$instances[ $key ]->extensions['taxonomy']->meta_terms_save( $term_id );
			}
		}
	}
}

add_action( 'create_term', 'create_term_redux_taxonomy', 4 );
