<?php
/**
 * ACE Editor Field.
 *
 * @package     Redux Framework/Fields
 * @subpackage  ACE_Editor
 * @version     3.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Ace_Editor', false ) ) {

	/**
	 * Class Redux_Ace_Editor
	 */
	class Redux_Ace_Editor extends Redux_Field {

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function render() {
			if ( is_array( $this->value ) ) {
				$this->value = '';
			} else {
				$this->value = trim( $this->value );
			}

			if ( ! empty( $this->field['options'] ) ) {
				$this->field['args'] = $this->field['options'];
				unset( $this->field['options'] );
			}

			if ( ! isset( $this->field['mode'] ) ) {
				$this->field['mode'] = 'javascript';
			}
			if ( ! isset( $this->field['theme'] ) ) {
				$this->field['theme'] = 'monokai';
			}

			$params = array(
				'minLines' => 10,
				'maxLines' => 30,
			);

			if ( isset( $this->field['args'] ) && ! empty( $this->field['args'] ) && is_array( $this->field['args'] ) ) {
				$params = wp_parse_args( $this->field['args'], $params );
			}
			?>
			<div class="ace-wrapper">
				<input
					type="hidden"
					class="localize_data"
					value="<?php echo esc_html( wp_json_encode( $params ) ); ?>"/>
				<label for="<?php echo esc_attr( $this->field['id'] ); ?>-textarea"></label>
				<textarea
					name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>"
					id="<?php echo esc_attr( $this->field['id'] ); ?>-textarea"
					class="ace-editor hide <?php echo esc_attr( $this->field['class'] ); ?>"
					data-editor="<?php echo esc_attr( $this->field['id'] ); ?>-editor"
					data-mode="<?php echo esc_attr( $this->field['mode'] ); ?>"
					data-theme="<?php echo esc_attr( $this->field['theme'] ); ?>"><?php echo esc_textarea( $this->value ); ?></textarea>
				<pre
					id="<?php echo esc_attr( $this->field['id'] ); ?>-editor"
					class="ace-editor-area"><?php echo esc_html( $this->value ); ?>
				</pre>
			</div>
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
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-ace-editor-css',
					Redux_Core::$url . 'inc/fields/ace_editor/redux-ace-editor.css',
					array(),
					$this->timestamp
				);
			}

			if ( ! wp_script_is( 'ace-editor-js' ) ) {
				Redux_CDN::enqueue_script(
					'ace-editor-js',
					// phpcs:ignore Generic.Strings.UnnecessaryStringConcat
					'//' . 'cdnjs' . '.cloudflare' . '.com/ajax/libs/ace/1.4.13/ace.js',
					array( 'jquery' ),
					'1.4.12',
					true
				);
			}

			wp_enqueue_script(
				'redux-field-ace-editor-js',
				Redux_Core::$url . 'inc/fields/ace_editor/redux-ace-editor' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'ace-editor-js', 'redux-js' ),
				$this->timestamp,
				true
			);
		}
	}
}

class_alias( 'Redux_Ace_Editor', 'ReduxFramework_Ace_Editor' );
