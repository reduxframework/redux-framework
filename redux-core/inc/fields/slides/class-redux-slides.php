<?php
/**
 * Slides Field
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Slides', false ) ) {

	/**
	 * Main Redux_slides class
	 *
	 * @since       1.0.0
	 */
	class Redux_Slides extends Redux_Field {

		/**
		 * Set field and value defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'show'          => array(
					'title'       => true,
					'description' => true,
					'url'         => true,
				),
				'content_title' => esc_html__( 'Slide', 'redux-framework' ),
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
			// translators: New accordion title.
			echo '<div class="redux-slides-accordion" data-new-content-title="' . esc_attr( sprintf( __( 'New %s', 'redux-framework' ), $this->field['content_title'] ) ) . '">';

			$x = 0;

			if ( isset( $this->value ) && is_array( $this->value ) && ! empty( $this->value ) ) {
				$slides = $this->value;

				foreach ( $slides as $slide ) {
					if ( empty( $slide ) ) {
						continue;
					}

					$defaults = array(
						'title'         => '',
						'description'   => '',
						'sort'          => '',
						'url'           => '',
						'image'         => '',
						'thumb'         => '',
						'attachment_id' => '',
						'height'        => '',
						'width'         => '',
						'select'        => array(),
					);

					$slide = wp_parse_args( $slide, $defaults );

					if ( empty( $slide['thumb'] ) && ! empty( $slide['attachment_id'] ) ) {
						$img             = wp_get_attachment_image_src( $slide['attachment_id'], 'full' );
						$slide['image']  = $img[0];
						$slide['width']  = $img[1];
						$slide['height'] = $img[2];
					}

					echo '<div class="redux-slides-accordion-group"><fieldset class="redux-field" data-id="' . esc_attr( $this->field['id'] ) . '"><h3><span class="redux-slides-header">' . esc_html( $slide['title'] ) . '</span></h3><div>';

					$hide = '';
					if ( empty( $slide['image'] ) ) {
						$hide = ' hide';
					}

					$alt = wp_prepare_attachment_for_js( $slide['attachment_id'] );
					$alt = $alt['alt'] ?? '';

					echo '<div class="screenshot' . esc_attr( $hide ) . '">';
					echo '<a class="of-uploaded-image" href="' . esc_url( $slide['image'] ) . '">';
					echo '<img
							class="redux-slides-image"
							id="image_image_id_' . esc_attr( $x ) . '" src="' . esc_url( $slide['thumb'] ) . '"
							alt="' . esc_attr( $alt ) . '"
							target="_blank" rel="external" />';

					echo '</a>';
					echo '</div>';

					echo '<div class="redux_slides_add_remove">';

					echo '<span class="button media_upload_button" id="add_' . esc_attr( $x ) . '">' . esc_html__( 'Upload', 'redux-framework' ) . '</span>';

					$hide = '';
					if ( empty( $slide['image'] ) ) {
						$hide = ' hide';
					}

					echo '<span
							class="button remove-image' . esc_attr( $hide ) . '"
							id="reset_' . esc_attr( $x ) . '"
							rel="' . esc_attr( $slide['attachment_id'] ) . '">' .
							esc_html__( 'Remove', 'redux-framework' ) . '</span>';

					echo '</div>' . "\n";

					echo '<ul id="' . esc_attr( $this->field['id'] ) . '-ul" class="redux-slides-list">';

					if ( $this->field['show']['title'] ) {
						$title_type = 'text';
					} else {
						$title_type = 'hidden';
					}

					$placeholder = ( isset( $this->field['placeholder']['title'] ) ) ? esc_attr( $this->field['placeholder']['title'] ) : __( 'Title', 'redux-framework' );
					echo '<li>';
					echo '<input
							type="' . esc_attr( $title_type ) . '"
							id="' . esc_attr( $this->field['id'] ) . '-title_' . esc_attr( $x ) . '"
							name="' . esc_attr( $this->field['name'] . '[' . $x . '][title]' . $this->field['name_suffix'] ) . '"
							value="' . esc_attr( $slide['title'] ) . '"
							placeholder="' . esc_attr( $placeholder ) . '" class="full-text slide-title" />';

					echo '</li>';

					if ( $this->field['show']['description'] ) {
						$placeholder = ( isset( $this->field['placeholder']['description'] ) ) ? esc_attr( $this->field['placeholder']['description'] ) : __( 'Description', 'redux-framework' );
						echo '<li>';
						echo '<textarea
								name="' . esc_attr( $this->field['name'] . '[' . $x . '][description]' . $this->field['name_suffix'] ) . '"
								id="' . esc_attr( $this->field['id'] ) . '-description_' . esc_attr( $x ) . '"
								placeholder="' . esc_attr( $placeholder ) . '"
								class="large-text"
								rows="6">' . esc_textarea( $slide['description'] ) . '</textarea>';

						echo '</li>';
					}

					$placeholder = ( isset( $this->field['placeholder']['url'] ) ) ? esc_attr( $this->field['placeholder']['url'] ) : __( 'URL', 'redux-framework' );
					if ( $this->field['show']['url'] ) {
						$url_type = 'text';
					} else {
						$url_type = 'hidden';
					}

					echo '<li>';
					echo '<input
							type="' . esc_attr( $url_type ) . '"
							id="' . esc_attr( $this->field['id'] . '-url_' ) . esc_attr( $x ) . '"
							name="' . esc_attr( $this->field['name'] . '[' . esc_attr( $x ) . '][url]' . $this->field['name_suffix'] ) . '"
							value="' . esc_attr( $slide['url'] ) . '"
							class="full-text" placeholder="' . esc_attr( $placeholder ) . '" />';
					echo '</li>';

					echo '<li>';
					echo '<input
							type="hidden"
							class="slide-sort"
							name="' . esc_attr( $this->field['name'] . '[' . $x . '][sort]' . $this->field['name_suffix'] ) . '"
							id="' . esc_attr( $this->field['id'] ) . '-sort_' . esc_attr( $x ) . '"
							value="' . esc_attr( $slide['sort'] ) . '" />';

					echo '<li>';
					echo '<input
							type="hidden"
							class="upload-id"
							name="' . esc_attr( $this->field['name'] . '[' . $x . '][attachment_id]' . $this->field['name_suffix'] ) . '"
							id="' . esc_attr( $this->field['id'] ) . '-image_id_' . esc_attr( $x ) . '"
							value="' . esc_attr( $slide['attachment_id'] ) . '" />';

					echo '<input
							type="hidden"
							class="upload" name="' . esc_attr( $this->field['name'] . '[' . $x . '][image]' . $this->field['name_suffix'] ) . '"
							id="' . esc_attr( $this->field['id'] ) . '-image_url_' . esc_attr( $x ) . '"
							value="' . esc_attr( $slide['image'] ) . '" readonly="readonly" />';

					echo '<input
							type="hidden"
							class="upload-height"
							name="' . esc_attr( $this->field['name'] . '[' . $x . '][height]' . $this->field['name_suffix'] ) . '"
							id="' . esc_attr( $this->field['id'] ) . '-image_height_' . esc_attr( $x ) . '"
							value="' . esc_attr( $slide['height'] ) . '" />';

					echo '<input
							type="hidden"
							class="upload-width"
							name="' . esc_attr( $this->field['name'] . '[' . $x . '][width]' . $this->field['name_suffix'] ) . '"
							id="' . esc_attr( $this->field['id'] ) . '-image_width_' . esc_attr( $x ) . '"
							value="' . esc_attr( $slide['width'] ) . '" />';

					echo '</li>';

					echo '<input
							type="hidden"
							class="upload-thumbnail"
							name="' . esc_attr( $this->field['name'] . '[' . $x . '][thumb]' . $this->field['name_suffix'] ) . '"
							id="' . esc_attr( $this->field['id'] ) . '-thumb_url_' . esc_attr( $x ) . '"
							value="' . esc_attr( $slide['thumb'] ) . '" readonly="readonly" />';
					echo '</li>';

					echo '<li>';
					echo '<a href="javascript:void(0);" class="button deletion redux-slides-remove">' . esc_html__( 'Delete', 'redux-framework' ) . '</a>';
					echo '</li>';

					echo '</ul>';
					echo '</div>';
					echo '</fieldset>';
					echo '</div>';

					$x ++;
				}
			}

			if ( 0 === $x ) {
				echo '<div class="redux-slides-accordion-group">';
				echo '<fieldset class="redux-field" data-id="' . esc_attr( $this->field['id'] ) . '">';
				echo '<h3>';

				// translators:  Content title for new accordion.
				echo '<span class="redux-slides-header">' . esc_html( sprintf( __( 'New %s', 'redux-framework' ), esc_attr( $this->field['content_title'] ) ) ) . '</span>';
				echo '</h3>';
				echo '<div>';

				$hide = ' hide';

				echo '<div class="screenshot' . esc_attr( $hide ) . '">';
				echo '<a class="of-uploaded-image" href="">';
				echo '<img class="redux-slides-image" id="image_image_id_' . esc_attr( $x ) . '" src="" alt="placeholder" target="_blank" rel="external" />';
				echo '</a>';
				echo '</div>';

				// Upload controls DIV.
				echo '<div class="upload_button_div">';

				// If the user has WP3.5+ show upload/remove button.
				echo '<span class="button media_upload_button" id="add_' . esc_attr( $x ) . '">' . esc_html__( 'Upload', 'redux-framework' ) . '</span>';

				echo '<span class="button remove-image' . esc_attr( $hide ) . '" id="reset_' . esc_attr( $x ) . '" rel="' . esc_attr( $this->parent->args['opt_name'] . '[' . $this->field['id'] ) . '][attachment_id]">' . esc_html__( 'Remove', 'redux-framework' ) . '</span>';

				echo '</div>' . "\n";

				echo '<ul id="' . esc_attr( $this->field['id'] ) . '-ul" class="redux-slides-list">';

				if ( $this->field['show']['title'] ) {
					$title_type = 'text';
				} else {
					$title_type = 'hidden';
				}

				$placeholder = ( isset( $this->field['placeholder']['title'] ) ) ? esc_attr( $this->field['placeholder']['title'] ) : __( 'Title', 'redux-framework' );

				echo '<li>';
				echo '<input
						type="' . esc_attr( $title_type ) . '"
						id="' . esc_attr( $this->field['id'] . '-title_' . $x ) . '"
						name="' . esc_attr( $this->field['name'] . '[' . $x . '][title]' . $this->field['name_suffix'] ) . '"
						value=""
						placeholder="' . esc_attr( $placeholder ) . '"
						class="full-text slide-title" />';
				echo '</li>';

				if ( $this->field['show']['description'] ) {
					$placeholder = ( isset( $this->field['placeholder']['description'] ) ) ? esc_attr( $this->field['placeholder']['description'] ) : __( 'Description', 'redux-framework' );

					echo '<li>';
					echo '<textarea
							name="' . esc_attr( $this->field['name'] . '[' . $x . '][description]' . $this->field['name_suffix'] ) . '"
							id="' . esc_attr( $this->field['id'] . '-description_' . $x ) . '"
							placeholder="' . esc_attr( $placeholder ) . '"
							class="large-text"
							rows="6"></textarea>';
					echo '</li>';
				}

				$placeholder = ( isset( $this->field['placeholder']['url'] ) ) ? esc_attr( $this->field['placeholder']['url'] ) : __( 'URL', 'redux-framework' );

				if ( $this->field['show']['url'] ) {
					$url_type = 'text';
				} else {
					$url_type = 'hidden';
				}

				echo '<li>';
				echo '<input
						type="' . esc_attr( $url_type ) . '"
						id="' . esc_attr( $this->field['id'] . '-url_' . $x ) . '"
						name="' . esc_attr( $this->field['name'] . '[' . $x . '][url]' . $this->field['name_suffix'] ) . '"
						value="" class="full-text" placeholder="' . esc_attr( $placeholder ) . '" />';
				echo '</li>';

				echo '<li>';
				echo '<input
						type="hidden"
						class="slide-sort"
						name="' . esc_attr( $this->field['name'] . '[' . $x . '][sort]' . $this->field['name_suffix'] ) . '"
						id="' . esc_attr( $this->field['id'] . '-sort_' . $x ) . '"
						value="' . esc_attr( $x ) . '" />';

				echo '<li>';
				echo '<input
						type="hidden"
						class="upload-id"
						name="' . esc_attr( $this->field['name'] . '[' . $x . '][attachment_id]' . $this->field['name_suffix'] ) . '"
						id="' . esc_attr( $this->field['id'] . '-image_id_' . $x ) . '"
						value="" />';

				echo '<input
						type="hidden"
						class="upload"
						name="' . esc_attr( $this->field['name'] . '[' . $x . '][image]' . $this->field['name_suffix'] ) . '"
						id="' . esc_attr( $this->field['id'] . '-image_url_' . $x ) . '"
						value="" readonly="readonly" />';

				echo '<input
						type="hidden"
						class="upload-height"
						name="' . esc_attr( $this->field['name'] . '[' . $x . '][height]' . $this->field['name_suffix'] ) . '"
						id="' . esc_attr( $this->field['id'] . '-image_height_' . $x ) . '"
						value="" />';

				echo '<input
						type="hidden"
						class="upload-width"
						name="' . esc_attr( $this->field['name'] . '[' . $x . '][width]' . $this->field['name_suffix'] ) . '"
						id="' . esc_attr( $this->field['id'] . '-image_width_' . $x ) . '"
						value="" />';
				echo '</li>';

				echo '<input
						type="hidden"
						class="upload-thumbnail"
						name="' . esc_attr( $this->field['name'] . '[' . $x . '][thumb]' . $this->field['name_suffix'] ) . '"
						id="' . esc_attr( $this->field['id'] . '-thumb_url_' . $x ) . '"
						value="" />';
				echo '</li>';

				echo '<li>';
				echo '<a href="javascript:void(0);" class="button deletion redux-slides-remove">' . esc_html__( 'Delete', 'redux-framework' ) . '</a>';
				echo '</li>';

				echo '</ul>';
				echo '</div>';
				echo '</fieldset>';
				echo '</div>';
			}

			echo '</div>';

			// translators:  Content title for accordion.
			echo '<a href="javascript:void(0);" class="button redux-slides-add button-primary" rel-id="' . esc_attr( $this->field['id'] ) . '-ul" rel-name="' . esc_attr( $this->field['name'] . '[title][]' . $this->field['name_suffix'] ) . '">' . esc_html( sprintf( __( 'Add %s', 'redux-framework' ), esc_html( $this->field['content_title'] ) ) ) . '</a>';
			echo '<br/>';
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
			}

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style( 'redux-field-media-css' );

				wp_enqueue_style(
					'redux-field-slides-css',
					Redux_Core::$url . 'inc/fields/slides/redux-slides.css',
					array(),
					$this->timestamp
				);
			}

			wp_enqueue_script(
				'redux-field-media-js',
				Redux_Core::$url . 'assets/js/media/media' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'redux-js' ),
				$this->timestamp,
				true
			);

			wp_enqueue_script(
				'redux-field-slides-js',
				Redux_Core::$url . 'inc/fields/slides/redux-slides' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-sortable', 'redux-field-media-js' ),
				$this->timestamp,
				true
			);
		}
	}
}

class_alias( 'Redux_Slides', 'ReduxFramework_Slides' );
