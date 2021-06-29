<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Templates overrides for pages.
 *
 * @since 4.0.0
 * @package Redux Framework
 */

namespace ReduxTemplates;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Redux Templates Templates Class
 *
 * @since 4.0.0
 */
class Templates {

	/**
	 * Default container width.
	 *
	 * @var int
	 */
	public static $content_width = 1200;

	/**
	 * ReduxTemplates Template.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		global $pagenow;

		if ( ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) && \Redux_Enable_Gutenberg::$is_disabled ) {

				// We don't want to add templates unless it's a gutenberg page.
				return;
		}

		if ( ! $this->is_gutenberg_page() ) {
			return;
		}

		// Include ReduxTemplates default template without wrapper.
		add_filter( 'template_include', array( $this, 'template_include' ) );
		// Override the default content-width when using Redux templates so the template doesn't look like crao.
		add_action( 'wp', array( $this, 'modify_template_content_width' ) );

		// Add ReduxTemplates supported Post type in page template.
		$post_types = get_post_types();

		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				add_filter( "theme_{$post_type}_templates", array( $this, 'add_templates' ) );
			}
		}

		add_filter( 'admin_body_class', array( $this, 'add_body_class' ), 999 );

	}

	/**
	 * Is Gutenburg loaded via WordPress core.
	 *
	 * @return bool
	 */
	public function is_gutenberg_page(): bool {
		if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
			// The Gutenberg plugin is on.
			return true;
		}

		if ( ! function_exists( 'get_current_screen' ) ) {
			require_once ABSPATH . '/wp-admin/includes/screen.php';
		}

		$current_screen = get_current_screen();

		if ( isset( $current_screen ) && method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
			// Gutenberg page on 5+.
			return true;
		}

		return false;
	}

	/**
	 * Add the redux-template class to the admin body if a redux-templates page type is selected.
	 *
	 * @param string $classes Classes string for admin panel.
	 *
	 * @since 4.1.19
	 * @return string
	 */
	public function add_body_class( $classes ): string {
		global $post;

		$screen = get_current_screen();

		if ( 'post' === $screen->base && get_current_screen()->is_block_editor() ) {
			$check = get_post_meta( $post->ID, '_wp_page_template', true );
			if ( strpos( $check, 'redux-templates_' ) !== false ) {
				$classes .= ' redux-template';
			}
		}

		return $classes;
	}

	/**
	 * Override the $content_width variable for themes so our templates work properly and don't look squished.
	 *
	 * @param array $to_find Template keys to check against.
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	public function check_template( $to_find = array() ) {
		global $post;
		if ( ! empty( $post ) ) {
			$template = get_page_template_slug( $post->ID );
			if ( false !== strpos( $template, 'redux' ) ) {
				$test = mb_strtolower( preg_replace( '/[^A-Za-z0-9 ]/', '', $template ) );
				foreach ( $to_find as $key ) {
					if ( false !== strpos( $test, $key ) ) {
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * Override the $content_width variable for themes so our templates work properly and don't look squished.
	 *
	 * @since 4.0.0
	 */
	public function modify_template_content_width() {
		$to_find = array( 'cover', 'canvas', 'fullwidth' );
		if ( $this->check_template( $to_find ) ) {
			global $content_width;
			if ( $content_width < 1000 ) {
				$content_width = get_option( '_redux_content_width', self::$content_width );
			}
		}
	}

	/**
	 * Override the $content_width variable for themes so our templates work properly and don't look squished.
	 *
	 * @since 4.0.0
	 */
	public static function inline_editor_css() {
		global $content_width;
		if ( $content_width < 1000 ) {
			$content_width = get_option( '_redux_content_width', self::$content_width );
			return ".redux-template .wp-block {max-width: {$content_width}px;}";
		}
	}

	/**
	 * Include the template
	 *
	 * @param string $template Template type.
	 *
	 * @return string
	 * @since 4.0.0
	 */
	public function template_include( $template ) {
		if ( is_singular() ) {
			$page_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
			if ( 'redux-templates_full_width' === $page_template ) {
				$template = REDUXTEMPLATES_DIR_PATH . 'classes/templates/template-full-width.php';
			} elseif ( 'redux-templates_contained' === $page_template ) {
				$template = REDUXTEMPLATES_DIR_PATH . 'classes/templates/template-contained.php';
			} elseif ( 'redux-templates_canvas' === $page_template ) {
				$template = REDUXTEMPLATES_DIR_PATH . 'classes/templates/template-canvas.php';
			}
		}

		return $template;
	}

	/**
	 * Hook to add the templates to the dropdown
	 *
	 * @param array $post_templates Default post templates array.
	 *
	 * @return array
	 * @since 4.0.0
	 */
	public function add_templates( $post_templates ) {
		$post_templates['redux-templates_contained']  = __( 'Redux Contained', 'redux-framework' );
		$post_templates['redux-templates_full_width'] = __( 'Redux Full Width', 'redux-framework' );
		$post_templates['redux-templates_canvas']     = __( 'Redux Canvas', 'redux-framework' );

		return $post_templates;
	}
}
