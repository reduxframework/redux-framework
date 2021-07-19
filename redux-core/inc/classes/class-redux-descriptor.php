<?php
/**
 * Redux Descriptor Class
 *
 * @class Redux_Descriptor
 * @version 4.0.0
 * @package Redux Framework
 * @author Tofandel
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Redux_Descriptor
 */
class Redux_Descriptor {
	/**
	 * Stores the Reflection class object.
	 *
	 * @var object ReflectionClass object.
	 */
	protected $reflection_class;
	/**
	 * Field type.
	 *
	 * @var string $field_type Type of field.
	 */
	protected $field_type;
	/**
	 * Name of the field.
	 *
	 * @var string $name Name of field.
	 */
	protected $name;
	/**
	 * Description of the field.
	 *
	 * @var string $description Description of field.
	 */
	protected $description;
	/**
	 * Icon of the field.
	 *
	 * @var string $icon Icon of field.
	 */
	protected $icon;
	/**
	 * Icon of the field.
	 *
	 * @var bool $required Is field required?
	 */
	protected $required;

	/**
	 * Array of Redux_Descriptor_Fields.
	 *
	 * @var array Redux_Descriptor_Fields[] Array of descriptor_fields.
	 */
	protected $fields = array();

	/**
	 * Current field
	 *
	 * @var array Redux_Descriptor_Fields[] Array of descriptor_fields.
	 */

	/**
	 * Redux_Descriptor constructor.
	 *
	 * @param string $field Field name.
	 */
	public function __construct( string $field ) {
		Redux_Descriptor_Fields::$order = 0;
		try {
			$this->reflection_class = new ReflectionClass( $field );
		} catch ( ReflectionException $e ) {
			die ( $e->getMessage() ); // phpcs:ignore
		}
		$this->field_type = Redux_Core::strtolower( Redux_Helpers::remove_prefix( $this->reflection_class->getShortName(), 'Redux_' ) );
		$this->name       = ucfirst( $this->field_type );
	}

	/**
	 * Get field type.
	 *
	 * @return string
	 */
	public function get_field_type(): string {
		return $this->field_type;
	}

	/**
	 * Set the basic required information.
	 *
	 * @param string $name        Set name for the descriptor.
	 * @param string $description Set description for the descriptor.
	 * @param string $icon        Set icon for the descriptor.
	 */
	public function set_info( string $name, string $description = '', string $icon = '' ) {
		$this->name        = $name;
		$this->description = $description;
		$this->icon        = $icon;
	}

	/**
	 * Get name.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Add field to the descriptor.
	 *
	 * @param string $name        Name of field.
	 * @param string $title       Title of field.
	 * @param string $type        Type of field.
	 * @param string $description Field Description.
	 * @param mixed  $default     Field default value.
	 *
	 * @return Redux_Descriptor_Fields
	 */
	public function add_field( string $name, string $title, string $type, string $description = '', $default = null ): ?Redux_Descriptor_Fields {
		try {
			$this->fields[ $name ] = new Redux_Descriptor_Fields( $name, $title, $type, $description, $default );
		} catch ( Exception $e ) {
			return null;
		}
		$this->current_field = $name;

		return $this->fields[ $name ];
	}

	/**
	 * Parse the given request.
	 *
	 * @param array $req Request.
	 *
	 * @return array
	 */
	public function parse_request( array $req ): array {
		$parsed_req = array();
		foreach ( $req as $k => $v ) {
			if ( isset( $this->fields[ $k ] ) ) {
				$parsed_req[ $k ] = $v;
			}
		}

		return $parsed_req;
	}

	/**
	 * Selects and returns a field or the current field
	 *
	 * @param string $field_name Field name.
	 *
	 * @return mixed|null
	 */
	public function field( string $field_name = '' ) {
		if ( ! empty( $field_name ) ) {
			$this->current_field = $field_name;
		}

		return $this->fields[ $this->current_field ] ?? null;
	}

	/**
	 * Remove a field.
	 *
	 * @param string $name Remove a field from the keys.
	 */
	public function remove_field( string $name ) {
		unset( $this->fields[ $name ] );
	}

	/**
	 * To documentation.
	 *
	 * @return string
	 */
	public function to_doc(): string {
		$doc  = $this->name . "\n" . $this->description . "\n";
		$doc .= 'Fields:';
		$this->sort_fields();
		foreach ( $this->fields as $option ) {
			$doc .= $option->to_doc();
		}

		return $doc;
	}

	/**
	 * Sorts the fields by their order field.
	 */
	protected function sort_fields() {
		uksort(
			$this->fields,
			function ( $item1, $item2 ) {
				if ( isset( $item1['order'] ) && $item1['order'] === $item2['order'] ) {
					return 0;
				}
				return isset( $item1['order'] ) && $item1['order'] < $item2['order'] ? - 1 : 1;
			}
		);
	}

	/**
	 * To array.
	 *
	 * @return array
	 */
	public function to_array(): array {
		$fields = array();

		$this->sort_fields();
		foreach ( $this->fields as $option ) {
			$fields[ $option['name'] ] = $option->to_array();
		}

		return array(
			'type'        => $this->field_type,
			'name'        => $this->name,
			'description' => $this->description,
			'icon'        => $this->icon,
			'fields'      => $fields,
		);
	}
}
