<?php
/**
 * Section Field
 *
 * @package     ReduxFramework/Fields
 * @author      Kevin Provance (kprovance) & Tobias Karnetze (athoss.de)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Section', false ) ) {

	/**
	 * Main Redux_heading class
	 *
	 * @since       1.0.0
	 */
	class Redux_Section extends Redux_Field {

		/**
		 * Set field and value defaults.
		 */
		public function set_defaults() {
			// No errors please.
			$defaults = array(
				'indent'   => true,
				'style'    => '',
				'class'    => '',
				'title'    => '',
				'subtitle' => '',
			);

			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since         1.0.0
		 * @access        public
		 * @return        void
		 */
		public function render() {
			$guid = uniqid();

			if ( true === $this->field['indent'] ) {
				$this->field['class'] .= ' redux-section-indent-start';
			}

			$add_class = '';
			if ( isset( $this->field['indent'] ) && true === $this->field['indent'] ) {
				$add_class = ' form-table-section-indented';
			} elseif ( ! isset( $this->field['indent'] ) || ( isset( $this->field['indent'] ) && false !== $this->field['indent'] ) ) {
				$add_class = ' hide';
			}

			echo '<input type="hidden" id="' . esc_attr( $this->field['id'] ) . '-marker"></td></tr></table>';

			if ( isset( $this->field['indent'] ) && true === $this->field['indent'] ) {
				echo '<div class="indent-section-container">';
			}

			echo '<div id="section-' . esc_attr( $this->field['id'] ) . '" class="redux-section-field redux-field ' . esc_attr( $this->field['style'] ) . ' ' . esc_attr( $this->field['class'] ) . ' ">';

			if ( ! empty( $this->field['title'] ) ) {
				echo '<h3>' . wp_kses_post( $this->field['title'] ) . '</h3>';
			}

			if ( ! empty( $this->field['subtitle'] ) ) {
				echo '<div class="redux-section-desc">' . wp_kses_post( $this->field['subtitle'] ) . '</div>';
			}

			echo '</div>';

			if ( isset( $this->field['indent'] ) && true === $this->field['indent'] ) {
				echo '</div>';
			}

			echo '<table id="section-table-' . esc_attr( $this->field['id'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '" class="form-table form-table-section no-border' . esc_attr( $add_class ) . '"><tbody><tr><th></th><td id="' . esc_attr( $guid ) . '">';

			?>
			<script type="text/javascript">
				jQuery( document ).ready(
					function() {
						jQuery( '#<?php echo esc_attr( $this->field['id'] ); ?>-marker' ).parents( 'tr:first' )
						.css( {display: 'none'} )
						.prev( 'tr' )
						.css( 'border-bottom', 'none' );

						var group = jQuery( '#<?php echo esc_attr( $this->field['id'] ); ?>-marker' ).parents( '.redux-group-tab:first' );
						if ( !group.hasClass( 'sectionsChecked' ) ) {
							group.addClass( 'sectionsChecked' );
							var test = group.find( '.redux-section-indent-start h3' );
							jQuery.each(
								test, function( key, value ) {
									jQuery( value ).css( 'margin-top', '20px' )
								}
							);
							if ( group.find( 'h3:first' ).css( 'margin-top' ) === "20px" ) {
								group.find( 'h3:first' ).css( 'margin-top', '0' );
							}
						}
					}
				);
			</script>
			<?php
		}

		/**
		 * Enqueue Script and styles.
		 */
		public function enqueue() {
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-section-css',
					Redux_Core::$url . 'inc/fields/section/redux-section.css',
					array(),
					$this->timestamp
				);
			}
		}
	}
}

class_alias( 'Redux_Section', 'ReduxFramework_Section' );
