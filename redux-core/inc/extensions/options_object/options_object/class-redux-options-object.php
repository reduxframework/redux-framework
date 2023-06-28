<?php
/**
 * Debug Options Object
 *
 * @package     ReduxFramework
 * @author      Kevin Provance (kprovance)
 * @version     3.5.4
 *
 * @noinspection ALL
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Options_Object', false ) ) {

	/**
	 * Main Redux_options_object class
	 *
	 * @since       1.0.0
	 */
	class Redux_Options_Object extends Redux_Field {

		public $is_field;

		/**
		 * Redux_Options_Object constructor.
		 *
		 * @param array  $field Field array.
		 * @param mixed  $value Value array.
		 * @param object $parent ReduxFramework object.
		 *
		 * @throws ReflectionException .
		 */
		public function __construct( array $field, $value, $parent ) {
			parent::__construct( $field, $value, $parent );

			$this->is_field = $this->parent->extensions['options_object']->is_field;
		}

		/**
		 * Set field defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'options'          => array(),
				'stylesheet'       => '',
				'output'           => true,
				'enqueue'          => true,
				'enqueue_frontend' => true,
			);

			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function render() {
			if ( version_compare( phpversion(), '5.3.0', '>=' ) ) {
				$json = wp_json_encode( $this->parent->options, true );
			} else {
				$json = wp_json_encode( $this->parent->options );
			}

			$defaults = array(
				'full_width' => true,
				'overflow'   => 'inherit',
			);

			$this->field = wp_parse_args( $this->field, $defaults );

			if ( $this->is_field ) {
				$full_width = $this->field['full_width'];
			}

			$do_close = false;

			$id = $this->parent->args['opt_name'] . '-' . $this->field['id'];

			if ( ! $this->is_field || ( $this->is_field && false === $full_width ) ) {
				?>
				<style>#<?php echo esc_html( $id ); ?>{padding: 0;}</style>
				<?php // phpcs:ignore WordPress.CodeAnalysis.EmptyStatement ?>
				<?php echo '</td></tr></table>'; ?>
				<table id="<?php echo esc_attr( $id ); ?>" class="form-table no-border redux-group-table redux-raw-table" style=" overflow: <?php esc_attr( $this->field['overflow'] ); ?>;">
				<tbody><tr><td>
				<?php
				$do_close = true;
			}
			?>
			<fieldset
					id="<?php echo esc_attr( $id ); ?>-fieldset"
					class="redux-field redux-container-<?php echo esc_attr( $this->field['type'] ) . ' ' . esc_attr( $this->field['class'] ); ?>"
					data-id="<?php echo esc_attr( $this->field['id'] ); ?>">

				<h3><?php esc_html_e( 'Options Object', 'redux-framework' ); ?></h3>
				<div id="redux-object-browser"></div>
				<div id="redux-object-json" class="hide"><?php echo( $json ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				<a
					href="#" id="consolePrintObject"
					class="button">
					<?php esc_html_e( 'Show Object in Javascript Console Object', 'redux-framework' ); ?></a>

			</fieldset>
			<?php if ( true === $do_close ) { ?>
				</td></tr></table>
				<table class="form-table no-border" style="margin-top: 0;">
					<tbody>
					<tr style="border-bottom: 0;">
						<th></th>
						<td>
			<?php } ?>
			<?php
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function enqueue() {
			wp_enqueue_script(
				'redux-extension-options-object',
				$this->url . 'redux-options-object' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'redux-js' ),
				Redux_Extension_Options_Object::$version,
				true
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-options-object',
					$this->url . 'redux-options-object.css',
					array(),
					Redux_Extension_Options_Object::$version,
					'all'
				);
			}
		}
	}
}
