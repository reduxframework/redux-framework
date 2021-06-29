<?php
/**
 * Redux_Instances Functions
 *
 * @package     Redux_Framework
 * @subpackage  Core
 * @deprecated Maintained for backward compatibility with v3.
 */

/**
 * Retreive an instance of ReduxFramework
 *
 * @depreciated
 *
 * @param  string $opt_name the defined opt_name as passed in $args.
 *
 * @return object                ReduxFramework
 */
function get_redux_instance( $opt_name ) {
	_deprecated_function( __FUNCTION__, '4.0', 'Redux::instance($opt_name)' );

	return Redux::instance( $opt_name );
}

/**
 * Retreive all instances of ReduxFramework
 * as an associative array.
 *
 * @depreciated
 * @return array        format ['opt_name' => $ReduxFramework]
 */
function get_all_redux_instances() {
	_deprecated_function( __FUNCTION__, '4.0', 'Redux::all_instances()' );

	return Redux::all_instances();
}
