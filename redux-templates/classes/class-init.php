<?php // phpcs:ignore WordPress.Files.FileName

/**
 * Initialize the Redux Template Library.
 *
 * @since 4.0.0
 * @package Redux Framework
 */

namespace ReduxTemplates;

use ReduxTemplates;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Redux Templates Init Class
 *
 * @since 4.0.0
 */
class Init {

	/**
	 * Default left value
	 *
	 * @var int
	 */
	public static $default_left = 5;

	/**
	 * Init constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		global $pagenow;

		if ( 'widgets.php' === $pagenow ) {
			return;
		}

		add_action( 'init', array( $this, 'load' ) );

		if ( did_action( 'init' ) ) { // In case the devs load it at the wrong place.
			$this->load();
		}

		if ( false === \Redux_Core::$redux_templates_enabled ) {
			return;
		}

		// Editor Load.
		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ), 1 );
		// Admin Load.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
		// Initiate the custom css fields.
		Gutenberg_Custom_CSS::instance();

	}

	/**
	 * Load everything up after init.
	 *
	 * @access public
	 * @since 4.0.0
	 */
	public static function load() {
		new ReduxTemplates\API();
		new ReduxTemplates\Templates();
		new ReduxTemplates\Notice_Overrides();
	}

	/**
	 * Get local contents of a file.
	 *
	 * @param string $file_path File path.
	 * @access public
	 * @since 4.0.0
	 * @return string
	 */
	public static function get_local_file_contents( $file_path ) {
		$fs = \Redux_Filesystem::get_instance();
		return $fs->get_contents( $file_path );
	}

	/**
	 * Load Editor Styles and Scripts.
	 *
	 * @access public
	 * @since 4.0.0
	 */
	public function editor_assets() {
		$fs  = \Redux_Filesystem::get_instance();
		$min = \Redux_Functions::is_min();

		// Little safety here for developers.
		if ( ! $fs->file_exists( REDUXTEMPLATES_DIR_PATH . "assets/js/redux-templates{$min}.js" ) ) {
			if ( '.min' === $min ) {
				$min = '';
			} else {
				$min = '.min';
			}
		}
		$version = REDUXTEMPLATES_VERSION;
		// When doing local dev work. Otherwise follow the check for dev_mode or not.
		if ( defined( 'REDUX_PLUGIN_FILE' ) ) {
			if ( $fs->file_exists( trailingslashit( dirname( REDUX_PLUGIN_FILE ) ) . 'local_developer.txt' ) ) {
				$min = '';
			}
			$version = time();
		}
		$min = ''; // Fix since our min'd file isn't working.

		wp_enqueue_script(
			'redux-templates-js',
			plugins_url( "assets/js/redux-templates{$min}.js", REDUXTEMPLATES_FILE ),
			array( 'code-editor', 'csslint', 'wp-i18n', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-data', 'wp-editor', 'wp-element', 'wp-hooks' ),
			$version,
			true
		);

		wp_set_script_translations( 'redux-templates-js', 'redux-templates' );

		// Backend editor scripts: common vendor files.
		wp_enqueue_script(
			'redux-templates-js-vendor',
			plugins_url( "assets/js/vendor{$min}.js", REDUXTEMPLATES_FILE ),
			array(),
			$version,
			true
		);

		// We started using the CSS variables. This gives us the function before it's put in core.
		if ( version_compare( get_bloginfo( 'version' ), '5.5', '<' ) ) {
			if ( ! defined( 'GUTENBERG_VERSION' ) || ( defined( 'GUTENBERG_VERSION' ) && version_compare( GUTENBERG_VERSION, '8.5.1', '<' ) ) ) {
				wp_register_style( 'redux-templates-gutenberg-compatibility', false, array(), $version );
				wp_enqueue_style( 'redux-templates-gutenberg-compatibility' );
				wp_add_inline_style( 'redux-templates-gutenberg-compatibility', ':root {--wp-admin-theme-color: #007cba;}' );
			}
		}

		$global_vars = array(
			'i18n'              => 'redux-framework',
			'plugin'            => REDUXTEMPLATES_DIR_URL,
			'mokama'            => \Redux_Helpers::mokama(),
			'key'               => \base64_encode( \Redux_Functions::gs() ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			'version'           => \Redux_Core::$version,
			'supported_plugins' => array(), // Load the supported plugins.
			'tos'               => \Redux_Connection_Banner::tos_blurb( 'import_wizard' ),
		);
		if ( ! $global_vars['mokama'] ) {
			// phpcs:disable Squiz.PHP.CommentedOutCode
			// delete_user_meta( get_current_user_id(), '_redux_templates_counts'); // To test left.
			$global_vars['left'] = ReduxTemplates\Init::left( get_current_user_id() );

			// phpcs:ignore
			// delete_user_meta( get_current_user_id(), '_redux_welcome_guide' ); // For testing.
			if ( \Redux_Helpers::is_gutenberg_page() && $global_vars['left'] === self::$default_left ) {
				// We don't want to show unless Gutenberg is running, and they haven't tried the library yet.
				$launched = get_user_meta( get_current_user_id(), '_redux_welcome_guide', true );
				if ( '1' !== $launched ) {
					$global_vars['welcome'] = 1;
				}
			}
		}

		if ( ! $global_vars['mokama'] ) {
			$global_vars['u'] = rtrim( \Redux_Functions_Ex::get_site_utm_url( '', 'library', true ), '1' );
		}

		// TODO - Only have this show up After 2 imports and Redux installed for a week. If they dismissed, then show up again in 30 days one last time.
		// phpcs:ignore Squiz.Commenting.InlineComment.InvalidEndChar
		// $global_vars['nps'] = __( 'Hey there. You\'ve been using Redux for a bit now, would you mind letting us know how likely you are to recommend Redux to a friend or colleague?', 'redux-framework' );

		wp_localize_script(
			'redux-templates-js',
			'redux_templates',
			$global_vars
		);

		wp_enqueue_style(
			'redux-fontawesome',
			REDUXTEMPLATES_DIR_URL . 'assets/css/font-awesome.min.css',
			false,
			$version
		);
		$extra_css = ReduxTemplates\Templates::inline_editor_css();
		if ( ! empty( $extra_css ) ) {
			wp_add_inline_style( 'redux-fontawesome', $extra_css );
		}
	}

	/**
	 * Admin Style & Script.
	 *
	 * @access public
	 * @since 4.0.0
	 */
	public function admin_assets() {
		wp_enqueue_style(
			'redux-templates-bundle',
			REDUXTEMPLATES_DIR_URL . 'assets/css/admin.min.css',
			false,
			REDUXTEMPLATES_VERSION
		);
	}

	/**
	 * Get the items left.
	 *
	 * @param int $uid User ID number.
	 * @access public
	 * @since 4.1.18
	 * @return int
	 */
	public static function left( $uid ) {
		$count = get_user_meta( $uid, '_redux_templates_counts', true );
		if ( empty( $count ) ) {
			$count = self::$default_left;
		}
		if ( $count <= 0 ) {
			$count = 0;
		}

		return $count;
	}

}

new Init();
