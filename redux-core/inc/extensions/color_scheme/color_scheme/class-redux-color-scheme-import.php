<?php
/**
 * Redux Color Scheme Attach Class
 *
 * WordPress' functions are lot loaded in this file due to a post-callback in javascript.  We therefore must
 * compensate for WPCS by creating our own validate/sanitize/nonce functions, which are comments throughout.
 *
 * @package Redux Extentions
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Color_Scheme_Import
 */

if ( ! class_exists( 'Redux_Color_Scheme_Import' ) ) {
	/**
	 * Class Redux_Color_Scheme_Import
	 */
	class Redux_Color_Scheme_Import {

		/**
		 * Errors.
		 *
		 * @var string
		 */
		private $err = '';

		/**
		 * Upload directory.
		 *
		 * @var string
		 */
		private $upload_dir = '';

		/**
		 * Field ID.
		 *
		 * @var string
		 */
		private $field_id = '';

		/**
		 * Option panel opt_name.
		 *
		 * @var string
		 */
		private $opt_name = '';

		/**
		 * Text result.
		 *
		 * @var string
		 */
		private $result;

		/**
		 * Data.
		 *
		 * @var string
		 */
		private $data;

		/**
		 * Redux_Color_Scheme_Import constructor.
		 */
		public function __construct() {

			// Set result to false.
			$this->result = false;

			// String used more than once.
			$abort = '  Aborting import.';

			// This is our nonce check using MD5.
			// $this->sanitize_input replaces sanitize_title() and wp_unslash().
			// phpcs:disable WordPress.Security.ValidatedSanitizedInput, WordPress.Security.NonceVerification
			if ( isset( $_REQUEST['nonce'] ) && $this->sanitize_input( $_REQUEST['nonce'] ) === md5( 'color_scheme_import' ) ) { // WPCS: CSRF ok, sanitization ok.

				// Is request type set?
				if ( isset( $_REQUEST['type'] ) ) { // WPCS: CSRF ok.

					// Is request type import?
					if ( 'import' === $this->sanitize_input( $_REQUEST['type'] ) ) { // WPCS: CSRF ok, sanitization ok.

						// Get upload dir from cookie.
						if ( true === $this->get_upload_dir() ) {

							// Check for field id.
							if ( isset( $_REQUEST['field_id'] ) && isset( $_REQUEST['opt_name'] ) ) { // WPCS: CSRF ok.

								// Get field id.
								$this->field_id = $this->sanitize_input( $_REQUEST['field_id'] );
								$this->opt_name = $this->sanitize_input( $_REQUEST['opt_name'] );

								// Process import file.
								if ( true === $this->process_file() ) {
									$this->result = true;
									$this->data   = 'Import successful!  Click <strong>OK</strong> to refresh.';

									// process_file failed, return error message.
								} else {
									$this->data = $this->err . $abort;
								}
							} else {
								$this->data = 'Invalid field ID.' . $abort;
							}

							// Cookie read failed.
						} else {
							$this->data = $this->err . $abort;
						}

						// No request types.  Somebody tryin' to do something they shouldn't.
					} else {
						$this->data = 'Invalid request type.' . $abort;
					}

					// No request type?  Just in case.
				} else {
					$this->data = 'No request type specified.' . $abort;
				}
			} else {
				$this->data = 'Security check failed.' . $abort;
			}
			// phpcs:enable WordPress.Security.ValidatedSanitizedInput, WordPress.Security.NonceVerification

			// data array to return.
			$arr = array(
				'result' => $this->result,
				'data'   => $this->data,
			);

			// json encode.
			$arr = json_encode( $arr ); // phpcs:ignore WordPress.WP.AlternativeFunctions

			echo $arr; // phpcs:ignore WordPress.Security.EscapeOutput
		}

		/**
		 * Replaces proprietary WordPress functions sanitize_title() and wp_unslash().
		 *
		 * @param string|array $input Value to sanitize.
		 */
		private function sanitize_input( $input ): string {
			$input = is_array( $input ) ? array_map( 'stripslashes_deep', $input ) : stripslashes( $input );

			return htmlspecialchars( $input, ENT_QUOTES );
		}

		/**
		 * Get wp upload dir from cookie.
		 *
		 * @return bool
		 */
		private function get_upload_dir(): bool {

			// cookie name.
			$val = 'redux_color_scheme_upload_dir';

			// Is the cookie there?
			if ( isset( $_COOKIE[ $val ] ) ) {

				// Get value from cookie.
				$upload_dir = $this->sanitize_input( $_COOKIE[ $val ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

				// Is it blank?
				if ( '' === $upload_dir ) {
					$this->err = 'Required cookie is empty.';

					// Nope, grab the data.
				} else {
					$this->upload_dir = $upload_dir;

					return true;
				}

				// No cookie for you!
			} else {
				$this->err = 'Unable to read required cookie.';
			}

			return false;
		}

		/**
		 * Process import file
		 *
		 * @return bool
		 */
		private function process_file(): bool {

			// Undefined | Multiple Files | $_FILES Corruption Attacks
			// If this request falls under any of them, treat it invalid.
			if ( ! isset( $_FILES['file']['error'] ) || is_array( $_FILES['file']['error'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				$this->err = 'Invalid upload parameters.';

				return false;
			} else {

				switch ( $_FILES['file']['error'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing

					// All is good.
					case UPLOAD_ERR_OK:
						break;

					// Missing file.
					case UPLOAD_ERR_NO_FILE:
						$this->err = 'No file chosen.';

						return false;

					// File too big.
					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE:
						$this->err = 'Exceeds filesize limit.';

						return false;

					// Unknown err.
					default:
						$this->err = 'Unknown error.';

						return false;
				}

				// get file name.
				$filename = htmlspecialchars( trim( $_FILES['file']['name'] ), ENT_QUOTES ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput, WordPress.Security.NonceVerification.Missing

				// get a temp file path.
				$filepath = htmlspecialchars( trim( $_FILES['file']['tmp_name'] ), ENT_QUOTES ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput, WordPress.Security.NonceVerification.Missing

				// remove illegal chars.
				$filename = preg_replace( '#[^a-z (),-_]#i', '', $filename );

				// Is accepted type?
				if ( true === $this->is_proper_mime( $filepath ) ) {

					// Check for JSON extension.
					if ( $this->is_ext_json( $filename ) ) {

						// Is the actual scheme file?
						if ( $this->is_scheme_file( $filepath ) ) {

							// Try moving it from temp to wp upload.
							if ( ! move_uploaded_file( $filepath, $this->upload_dir . '/' . $this->opt_name . '_' . $this->field_id . '.json' ) ) {
								$this->err = 'Cannot move Redux color scheme file to the upload folder.';
							} else {
								return true;
							}
						}
					}
				}
			}

			return false;
		}

		/**
		 * Check for a proper MIME type.
		 *
		 * @param string $filepath File path.
		 *
		 * @return bool
		 */
		private function is_proper_mime( string $filepath ): bool {
			// Get MIME type.
			$finfo = new finfo( FILEINFO_MIME_TYPE );

			// Check a type against accepted list.
			$ext = in_array(
				$finfo->file( $filepath ),
				array(
					'json' => 'application/json',
					'tmp'  => 'application/json',
				),
				true
			);

			// Bad type.
			if ( false === $ext ) {
				$this->err = 'Invalid file format.';

				return false;
			}

			return true;
		}

		/**
		 * Is file a valid scheme file.
		 *
		 * @param string $filepath File path.
		 *
		 * @return bool
		 */
		private function is_scheme_file( string $filepath ): bool {

			// Check for valid color scheme backup tag.
			$content = $this->read_tmp( $filepath );

			// If empty, set the error.
			if ( '' === $content ) {
				$this->err = 'The selected file is empty.';

				// check for valid scheme entry.
			} else {

				// scheme tag.
				$tag = '"color_scheme_name"';

				// Locate its position in the string.
				$pos = strpos( $content, $tag );

				// Is There?  Return true.
				if ( $pos > 0 ) {
					return true;

					// Otherwise, set the error.
				} else {
					$this->err = 'The selected file is not a valid color scheme file.';
				}
			}

			return false;
		}

		/**
		 * Check for valid extension.
		 *
		 * @param string $filename File name.
		 *
		 * @return bool
		 */
		private function is_ext_json( string $filename ): bool {
			// Get last period position.
			$pos = strrpos( $filename, '.' );

			// One found.
			if ( $pos > 0 ) {

				// Extract file extension.
				$ext = substr( $filename, $pos + 1 );

				// Is JSON, then continue.
				if ( 'json' === $ext ) {
					return true;

					// If not, set err.
				} else {
					$this->err = 'The selected file is a ' . strtoupper( $ext ) . ' file, not a JSON file.';
				}

				// No file ext found, set error.
			} else {
				$this->err = 'The selected file has no JSON extension.';
			}

			return false;
		}

		/**
		 * Reads data from file.
		 *
		 * @param string $file File path.
		 *
		 * @return bool|string
		 */
		private function read_tmp( string $file ) {
			if ( file_exists( $file ) ) {
				$fp   = fopen( $file, 'r' );  // phpcs:ignore WordPress.WP.AlternativeFunctions
				$data = fread( $fp, filesize( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions

				fclose( $fp ); // phpcs:ignore WordPress.WP.AlternativeFunctions

				return $data;
			} else {
				return '';
			}
		}
	}

	new Redux_Color_Scheme_Import();
}
