<?php
/**
 * Redux Descriptor Types Class
 *
 * @class Redux_Descriptor_Types
 * @version 4.0.0
 * @package Redux Framework
 * @author Tofandel
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Redux_Descriptor_Types
 */
abstract class Redux_Descriptor_Types {
	const TEXT     = 'text';
	const TEXTAREA = 'textarea';
	const BOOL     = 'bool';
	const SLIDER   = 'slider';
	const NUMBER   = 'number';
	const RANGE    = 'range';
	const OPTIONS  = 'array';
	const RADIO    = 'radio';
	// Todo add more field types for the builder!

	/**
	 * Get the available types of field.
	 *
	 * @return array
	 */
	public static function get_types(): array {
		static $const_cache;

		if ( ! isset( $const_cache ) ) {
			$reflect     = new ReflectionClass( __CLASS__ );
			$const_cache = $reflect->getConstants();
		}

		return $const_cache;
	}


	/**
	 * Check if a type is in the list of available types.
	 *
	 * @param string $value Check if it's a valid type.
	 *
	 * @return bool
	 */
	public static function is_valid_type( string $value ): bool {
		return in_array( $value, self::get_types(), true );
	}

}
