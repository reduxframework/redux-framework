<?php
/**
 * This file is part of the array_column library
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey (http://benramsey.com)
 * @license   http://opensource.org/licenses/MIT MIT
 * @package Redux Framework
 */

if ( ! function_exists( 'array_column' ) ) {

	/**
	 * Returns the values from a single column of the input array, identified by
	 * the $column_key.
	 * Optionally, you may provide an $index_key to index the values in the returned
	 * array by the values from the $index_key column in the input array.
	 *
	 * @param array $input      A multi-dimensional array (record set) from which to pull
	 *                          a column of values.
	 * @param mixed $column_key The column of values to return. This value may be the
	 *                          integer key of the column you wish to retrieve, or it
	 *                          may be the string key name for an associative array.
	 * @param mixed $index_key  (Optional.) The column to use as the index/keys for
	 *                          the returned array. This value may be the integer key
	 *                          of the column, or it may be the string key name.
	 *
	 * @return array
	 */
	function array_column( $input = null, $column_key = null, $index_key = null ) {
		// Using func_get_args() in order to check for proper number of
		// parameters and trigger errors exactly as the built-in array_column()
		// does in PHP 5.5.
		$argc   = func_num_args();
		$params = func_get_args();

		$params_input      = $params[0];
		$params_column_key = ( null !== $params[1] ) ? (string) $params[1] : null;

		$params_index_key = null;
		if ( isset( $params[2] ) ) {
			if ( is_float( $params[2] ) || is_int( $params[2] ) ) {
				$params_index_key = (int) $params[2];
			} else {
				$params_index_key = (string) $params[2];
			}
		}

		$result_array = array();

		foreach ( $params_input as $row ) {
			$key   = null;
			$value = null;

			$key_set   = false;
			$value_set = false;

			if ( null !== $params_index_key && array_key_exists( $params_index_key, $row ) ) {
				$key_set = true;
				$key     = (string) $row[ $params_index_key ];
			}

			if ( null === $params_column_key ) {
				$value_set = true;
				$value     = $row;
			} elseif ( is_array( $row ) && array_key_exists( $params_column_key, $row ) ) {
				$value_set = true;
				$value     = $row[ $params_column_key ];
			}

			if ( $value_set ) {
				if ( $key_set ) {
					$result_array[ $key ] = $value;
				} else {
					$result_array[] = $value;
				}
			}
		}

		return $result_array;
	}
}
