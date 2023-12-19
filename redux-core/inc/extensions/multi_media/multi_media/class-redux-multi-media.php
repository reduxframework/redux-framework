<?php
/**
 * Redux Multi Media Field Class
 *
 * @package Redux
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Multi_Media
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Multi_Media' ) ) {

	/**
	 * Main ReduxFramework_multi_media class
	 *
	 * @since       1.0.0
	 */
	class Redux_Multi_Media extends Redux_Field {

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function render() {
			$field_id = $this->field['id'];

			$button_text    = $this->field['labels']['button'] ?? esc_html__( 'Add or Upload File(s)', 'redux-framework' );
			$max_file_count = $this->field['max_file_upload'] ?? 0;

			// Set library filter data, if it's set.
			if ( ! isset( $this->field['library_filter'] ) ) {
				$lib_filter = '';
			} else {
				if ( ! is_array( $this->field['library_filter'] ) ) {
					$this->field['library_filter'] = array( $this->field['library_filter'] );
				}

				$mime_types = get_allowed_mime_types();

				$lib_array = $this->field['library_filter'];

				$json_arr = array();

				// Enum mime types.
				foreach ( $mime_types as $ext => $type ) {
					if ( strpos( $ext, '|' ) ) {
						$exp_arr = explode( '|', $ext );

						foreach ( $exp_arr as $ext ) {
							if ( in_array( $ext, $lib_array, true ) ) {
								$json_arr[ $ext ] = $type;
							}
						}
					} elseif ( in_array( $ext, $lib_array, true ) ) {
						$json_arr[ $ext ] = $type;
					}
				}

				// Encode for transit to JS.
				$lib_filter = rawurlencode( wp_json_encode( $json_arr ) );
			}

			// primary container.
			echo '<div
					class="redux-multi-media-container' . esc_attr( $this->field['class'] ) . '"
					id="' . esc_attr( $field_id ) . '"
					data-max-file-upload="' . intval( $max_file_count ) . '"
					data-id="' . esc_attr( $field_id ) . '">';

			// Library filter.
			echo '<input type="hidden" class="library-filter" data-lib-filter="' . $lib_filter . '" />'; // phpcs:ignore WordPress.Security.EscapeOutput

			// Hidden inout for file(s).
			echo '<input
					name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '"
					id="' . esc_attr( $field_id ) . '-multi-media"
					class="redux_upload_file redux_upload_list"
					type="hidden"
					value=""
					size="45" />';

			// Upload button.
			echo '<input
					type="button"
					class="redux_upload_button button redux_upload_list"
					name=""
					id="' . esc_attr( $field_id ) . '-multi-media-upload"
					value="' . esc_attr( $button_text ) . '" />';

			// list container.
			echo '<ul id="' . esc_attr( $this->parent->args['opt_name'] ) . '_' . esc_attr( $field_id ) . '_status" class="redux_media_status attach_list">';

			$file_arr = array();
			$img_arr  = array();
			$all_arr  = array();

			// Check for file entries in array format.
			if ( $this->value && is_array( $this->value ) ) {

				// Enum existing file entries.
				foreach ( $this->value as $id => $url ) {

					// hidden ID input.
					$id_input = '<input
                                    type="hidden"
                                    value="' . $url . '"
                                    name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[' . intval( $id ) . ']"
                                    id="filelist-' . $id . '"
                                    class="" />';

					// Check for a valid image extension.
					if ( $this->is_valid_img_ext( $url ) ) {

						// Add image to array.
						$html  = '<li class="img_status">';
						$html .= wp_get_attachment_image( $id, array( 50, 50 ) );
						$html .= '<p class="redux_remove_wrapper">';
						$html .= '<a href="#" class="redux_remove_file_button">' . esc_html__( 'Remove Image', 'redux-framework' ) . '</a>';
						$html .= '</p>';
						$html .= $id_input;
						$html .= '</li>';

						$img_arr[] = $html;

						// No image?  Output standard file info.
					} else {

						// Get parts of URL.
						$parts = explode( '/', $url );

						// Get the filename.
						$title      = '';
						$part_count = count( $parts );

						for ( $i = 0; $i < $part_count; ++$i ) {
							$title = $parts[ $i ];
						}

						// Add file to array.
						$html  = '<li>';
						$html .= esc_html__( 'File: ', 'redux-framework' );
						$html .= '<strong>' . $title . '</strong>&nbsp;&nbsp;&nbsp;';
						$html .= '(<a href="' . $url . '" target="_blank" rel="external">' . esc_html__( 'Download', 'redux-framework' ) . '</a> / <a href="#" class="redux_remove_file_button">' . __( 'Remove', 'redux-framework' ) . '</a>)';
						$html .= $id_input;
						$html .= '</li>';

						$file_arr[] = $html;
					}
				}
			}

			// Push images onto array stack.
			if ( ! empty( $img_arr ) ) {
				foreach ( $img_arr as $html ) {
					$all_arr[] = $html;
				}
			}

			// Push files onto array stack.
			if ( ! empty( $file_arr ) ) {
				foreach ( $file_arr as $html ) {
					$all_arr[] = $html;
				}
			}

			// Output array to page.
			if ( ! empty( $all_arr ) ) {
				foreach ( $all_arr as $html ) {
					echo $html; // phpcs:ignore WordPress.Security.EscapeOutput
				}
			}

			// Close the list.
			echo '</ul>';

			// Close container.
			echo '</div>';
		}

		/**
		 * Determine a file's extension
		 *
		 * @param  string $file File url.
		 *
		 * @return string|false     File extension or false
		 * @since  1.0.0
		 */
		private function get_file_ext( string $file ) {
			$parsed = wp_parse_url( $file, PHP_URL_PATH );

			return $parsed ? strtolower( pathinfo( $parsed, PATHINFO_EXTENSION ) ) : false;
		}

		/**
		 * Determines if a file has a valid image extension
		 *
		 * @param  string $file File url.
		 *
		 * @return bool         Whether the file has a valid image extension
		 * @since  1.0.0
		 */
		private function is_valid_img_ext( string $file ): bool {
			$file_ext = $this->get_file_ext( $file );

			$ext_arr = array( 'jpg', 'jpeg', 'png', 'gif', 'ico', 'icon' );

			$valid = empty( $valid ) ? (array) apply_filters( 'redux_valid_img_types', $ext_arr ) : $valid;

			return ( $file_ext && in_array( $file_ext, $valid, true ) );
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

			// Get labels for localization.
			$upload_file    = $this->field['labels']['upload_file'] ?? esc_html__( 'Select File(s)', 'redux-framework' );
			$remove_image   = $this->field['labels']['remove_image'] ?? esc_html__( 'Remove Image', 'redux-framework' );
			$remove_file    = $this->field['labels']['remove_file'] ?? esc_html__( 'Remove', 'redux-framework' );
			$file_label     = $this->field['labels']['file'] ?? esc_html__( 'File: ', 'redux-framework' );
			$download_label = $this->field['labels']['download'] ?? esc_html__( 'Download', 'redux-framework' );
			$media_title    = $this->field['labels']['title'] ?? 'Title';

			// translators: %s: Filename.
			$dup_warn = $this->field['labels']['duplicate'] ?? esc_html__( '%s already exists in your file queue.', 'redux-framework' );

			// translators: %s: Upload limit.
			$max_warn = $this->field['labels']['max_limit'] ?? esc_html__( 'Maximum upload limit of %s reached/exceeded.', 'redux-framework' );

			// Set up min files for dev_mode = false.
			$min = Redux_Functions::isMin();

			if ( function_exists( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			} else {
				wp_enqueue_script( 'media-upload' );
			}

			// Field dependent JS.
			wp_enqueue_script(
				'redux-field-multi-media',
				$this->url . 'redux-multi-media' . $min . '.js',
				array( 'jquery', 'redux-js' ),
				Redux_Extension_Multi_Media::$version,
				true
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-multi-media',
					$this->url . 'redux-multi-media.css',
					array(),
					Redux_Extension_Multi_Media::$version
				);
			}

			// Localization.
			$data_arr = array(
				'upload_file'  => $upload_file,
				'remove_image' => $remove_image,
				'remove_file'  => $remove_file,
				'file'         => $file_label,
				'download'     => $download_label,
				'title'        => $media_title,
				'dup_warn'     => $dup_warn,
				'max_warn'     => $max_warn,
			);

			wp_localize_script(
				'redux-field-multi-media',
				'redux_multi_media_l10',
				apply_filters( 'redux_multi_media_localized_data', $data_arr )
			);
		}
	}
}
