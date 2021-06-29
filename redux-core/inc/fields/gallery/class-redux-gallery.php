<?php
/**
 * Gallery Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Gallery', false ) ) {

	/**
	 * Main Redux_gallery class
	 *
	 * @since       3.0.0
	 */
	class Redux_Gallery extends Redux_Field {

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function render() {
			echo '<div class="screenshot">';

			if ( ! empty( $this->value ) ) {
				$ids = explode( ',', $this->value );

				foreach ( $ids as $attachment_id ) {
					$img = wp_get_attachment_image_src( $attachment_id );
					$alt = wp_prepare_attachment_for_js( $attachment_id );
					$alt = $alt['alt'] ?? '';

					echo '<a class="of-uploaded-image" href="' . esc_url( $img[0] ) . '">';
					echo '<img class="redux-option-image" id="image_' . esc_attr( $this->field['id'] ) . '_' . esc_attr( $attachment_id ) . '" src="' . esc_url( $img[0] ) . '" alt="' . esc_attr( $alt ) . '" target="_blank" rel="external" />';
					echo '</a>';
				}
			}

			echo '</div>';
			echo '<a href="#" onclick="return false;" id="edit-gallery" class="gallery-attachments button button-primary">' . esc_html__( 'Add/Edit Gallery', 'redux-framework' ) . '</a> ';
			echo '<a href="#" onclick="return false;" id="clear-gallery" class="gallery-attachments button">' . esc_html__( 'Clear Gallery', 'redux-framework' ) . '</a>';
			echo '<input type="hidden" class="gallery_values ' . esc_attr( $this->field['class'] ) . '" value="' . esc_attr( $this->value ) . '" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '" />';
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

			if ( function_exists( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			} else {
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_style( 'thickbox' );
			}

			wp_enqueue_script(
				'redux-field-gallery-js',
				Redux_Core::$url . 'inc/fields/gallery/redux-gallery' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'redux-js' ),
				$this->timestamp,
				true
			);
		}
	}
}

class_alias( 'Redux_Gallery', 'ReduxFramework_Gallery' );
