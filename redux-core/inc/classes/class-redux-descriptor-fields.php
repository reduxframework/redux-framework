<?php
/**
 * Redux Descriptor Fields Class
 *
 * @class Redux_Descriptor_Fields
 * @version 4.0.0
 * @package Redux Framework
 * @author Tofandel
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Redux_Descriptor
 */
class Redux_Descriptor_Fields implements ArrayAccess {

	/**
	 * Options had for this field.
	 *
	 * @var array $options Options.
	 */
	protected $options;

	/**
	 * Order number for this field.
	 *
	 * @var int $order Order number.
	 */
	public static $order = 0;

	/**
	 * Redux_Descriptor_Fields constructor.
	 *
	 * @param string $name Name of field.
	 * @param string $title Field title.
	 * @param string $type Field Type.
	 * @param string $description Field Description.
	 * @param mixed  $default Default vlaue.
	 *
	 * @throws Exception Throwable.
	 */
	public function __construct( $name, $title, $type, $description = '', $default = null ) {
		if ( ! Redux_Descriptor_Types::is_valid_type( $type ) ) {
			throw new Exception( 'Unknown type ' . $type . ' for option ' . $name );
		}
		if ( ! is_string( $title ) ) {
			$title = ucfirst( $name );
		}
		$this->options = array(
			'name'        => $name,
			'title'       => $title,
			'type'        => $type,
			'description' => $description,
			'default'     => $default,
			'order'       => static::$order ++,
			'required'    => (bool) $this->required,
		);
	}

	/**
	 * Varible to set required for this field descriptor.
	 *
	 * @var bool $required Required.
	 */

	protected $required = false;

	/**
	 * Set required.
	 *
	 * @param bool $required Set required for this field.
	 *
	 * @return Redux_Descriptor_Fields
	 */
	public function set_required( $required = true ) {
		$this->required = $required;

		return $this;
	}

	/**
	 * Set order.
	 *
	 * @param int $order Descriptor order for this field.
	 *
	 * @return $this
	 */
	public function set_order( $order ) {
		static::$order          = $order;
		$this->options['order'] = (float) $order;

		return $this;
	}

	/**
	 * Set group.
	 *
	 * @param string $group Set the group.
	 *
	 * @return $this
	 */
	public function set_group( $group ) {
		$this->options['group'] = $group;

		return $this;
	}

	/**
	 * Set an option.
	 *
	 * @param string $option_key Option key.
	 * @param mixed  $option_value Value to set.
	 *
	 * @return $this
	 */
	public function set_option( $option_key, $option_value ) {
		$this->options[ $option_key ] = $option_value;

		return $this;
	}

	/**
	 * Get an option.
	 *
	 * @param string $option_key Named key of the option.
	 *
	 * @return mixed
	 */
	public function get_option( $option_key ) {
		return $this->options[ $option_key ];
	}

	/**
	 * Remove an option.
	 *
	 * @param string $option_key Named key of the option.
	 */
	public function remove_option( $option_key ) {
		unset( $this->options[ $option_key ] );
	}

	/**
	 * To documentation.
	 *
	 * @return string
	 */
	public function to_doc() {
		$doc = $this['name'] . '(' . $this['type'] . ')\n' . $this['description'] . "\n";

		return $doc;
	}

	/**
	 * To array.
	 *
	 * @return array
	 */
	public function to_array() {
		return $this->options;
	}

	/**
	 * Whether a offset exists
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset An offset to check for.
	 * @return boolean true on success or false on failure.
	 *
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	public function offsetExists( $offset ) {
		return array_key_exists( $offset, $this->options );
	}

	/**
	 * Offset to retrieve
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset The offset to retrieve.
	 * @return mixed Can return all value types.
	 * @since 5.0.0
	 */
	public function offsetGet( $offset ) {
		return $this->options[ $offset ];
	}

	/**
	 * Offset to set
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value  The value to set.
	 *
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetSet( $offset, $value ) {
		$this->options[ $offset ] = $value;
	}

	/**
	 * Offset to unset
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset The offset to unset.
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetUnset( $offset ) {
		unset( $this->options[ $offset ] );
	}
}
