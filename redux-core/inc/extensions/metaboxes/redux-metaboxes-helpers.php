<?php
/**
 * Redux Metabox Extension Helpers
 *
 * @package Redux
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'redux_metaboxes_loop_start' ) ) {
	/**
	 * Start loop.
	 *
	 * @param string $opt_name Panel opt_name.
	 * @param array  $the_post Post object.
	 */
	function redux_metaboxes_loop_start( string $opt_name, array $the_post = array() ) {
		$redux     = ReduxFrameworkInstances::get_instance( $opt_name );
		$metaboxes = $redux->extensions['metaboxes'];

		$metaboxes->loop_start( $the_post );
	}
}

if ( ! function_exists( 'redux_metaboxes_loop_end' ) ) {
	/**
	 * End loop.
	 *
	 * @param string $opt_name Panel opt_name.
	 * @param array  $the_post Post object.
	 */
	function redux_metaboxes_loop_end( string $opt_name, array $the_post = array() ) {
		$redux     = ReduxFrameworkInstances::get_instance( $opt_name );
		$metaboxes = $redux->extensions['metaboxes'];

		$metaboxes->loop_end();
	}
}

if ( ! function_exists( 'redux_post_meta' ) ) {
	/**
	 * Retrieve post meta values/settings.
	 *
	 * @param string $opt_name Panel opt_name.
	 * @param mixed  $the_post Post ID.
	 * @param string $meta_key Meta key.
	 * @param mixed  $def_val  Default value.
	 *
	 * @return string|void
	 */
	function redux_post_meta( string $opt_name = '', $the_post = array(), string $meta_key = '', $def_val = '' ) {
		return Redux::get_post_meta( $opt_name, $the_post, $meta_key, $def_val );
	}
}
