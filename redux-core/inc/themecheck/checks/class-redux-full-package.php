<?php
/**
 * Redux Full_Package Class
 *
 * @class Redux_Full_Package
 * @version 3.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Redux_Full_Package
 */
class Redux_Full_Package implements themecheck {

	/**
	 * Themecheck error array.
	 *
	 * @var array $error Error storage.
	 */
	protected $error = array();

	/**
	 * Check files.
	 *
	 * @param array $php_files File to check.
	 * @param array $css_files Files to check.
	 * @param array $other_files Files to check.
	 *
	 * @return bool
	 */
	public function check( $php_files, $css_files, $other_files ): bool {

		$ret = true;

		$check = Redux_ThemeCheck::get_instance();
		$redux = $check::get_redux_details( $php_files );

		if ( $redux ) {

			$blacklist = array(
				'codestyles'                       => esc_html__( 'Redux Code Styles', 'redux-framework' ),
				'class-redux-framework-plugin.php' => esc_html__( 'Redux Plugin File', 'redux-framework' ),
				'.travis.yml'                      => esc_html__( 'CI Testing File', 'redux-framework' ),
			);

			$errors = array();

			foreach ( $blacklist as $file => $reason ) {
				checkcount();
				if ( file_exists( $redux['parent_dir'] . $file ) ) {
					$errors[ $redux['parent_dir'] . $file ] = $reason;
				}
			}

			if ( ! empty( $errors ) ) {
				$error  = '<span class="tc-lead tc-required">REQUIRED</span> ' . esc_html__( 'It appears that you have embedded the full Redux package inside your theme. You need only embed the', 'redux-framework' ) . ' <strong>redux-core</strong> ' . esc_html__( 'folder. Embedding anything else will get your rejected from theme submission. Suspected Redux package file(s):', 'redux-framework' );
				$error .= '<ol>';

				foreach ( $errors as $key => $e ) {
					$error .= '<li><strong>' . $e . '</strong>: ' . $key . '</li>';
				}

				$error        .= '</ol>';
				$this->error[] = '<div class="redux-error">' . $error . '</div>';
				$ret           = false;
			}
		}

		return $ret;
	}

	/**
	 * Retrieve errors.
	 *
	 * @return array
	 */
	public function getError(): array {
		return $this->error;
	}
}

$themechecks[] = new Redux_Full_Package();
