<?php // phpcs:ignore WordPress.Files.FileName

/**
 * Notice overrides for Redux Pro block plugins.
 *
 * @since   4.0.0
 * @package Redux Framework
 */

namespace ReduxTemplates;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Redux Templates Notice Overrides Class
 *
 * @since 4.1.19
 */
class Notice_Overrides {

	/**
	 * ReduxTemplates Notice_Overrides.
	 *
	 * @since 4.1.19
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'filter_notices' ), 0 );
	}

	/**
	 * Filter out any notices before they're displayed.
	 *
	 * @since 4.0.0
	 */
	public function filter_notices() {
		if ( \Redux_Helpers::mokama() ) {
			$this->remove_filters_for_anonymous_class( 'admin_notices', 'QUBELY_PRO\Updater', 'show_invalid_license_notice' );
		}
	}

	/**
	 * Allow to remove method for an hook when, it's a class method used and class don't have variable, but you know the class name.
	 *
	 * @since 1.6.0
	 *
	 * @param string $hook_name Hook/action name to remove.
	 * @param string $class_name Class name. `Colors::class` for example.
	 * @param string $method_name Class method name.
	 * @param int    $priority Action priority.
	 *
	 * @return bool
	 */
	public static function remove_filters_for_anonymous_class( $hook_name = '', $class_name = '', $method_name = '', $priority = 10 ) {
		global $wp_filter;

		// Take only filters on right hook name and priority.
		if ( ! isset( $wp_filter[ $hook_name ][ $priority ] ) || ! is_array( $wp_filter[ $hook_name ][ $priority ] ) ) {
			return false;
		}

		// Loop on filters registered.
		foreach ( $wp_filter[ $hook_name ][ $priority ] as $unique_id => $filter_array ) {
			// Test if filter is an array ! (always for class/method).
			// Test if object is a class, class and method is equal to param !
			if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) && is_object( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) === $class_name && $filter_array['function'][1] === $method_name ) {
				// Test for WordPress >= 4.7 WP_Hook class (https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/).
				if ( $wp_filter[ $hook_name ] instanceof \WP_Hook ) {
					unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $unique_id ] );
				} else {
					unset( $wp_filter[ $hook_name ][ $priority ][ $unique_id ] );
				}
			}
		}

		return false;
	}
}
