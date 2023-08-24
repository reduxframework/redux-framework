<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Templates overrides for pages.
 *
 * @since 4.0.0
 * @package Redux Framework
 */

namespace ReduxTemplates;

defined( 'ABSPATH' ) || exit;

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

		if ( 'widgets.php' === $pagenow ) {
			return;
		}

		if ( ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) && ( class_exists( 'Classic_Editor' ) || defined( 'DISABLE_GUTENBERG_FILE' ) || class_exists( 'CodePopular_disable_gutenberg' ) ) ) {

			// We don't want to add templates unless it's a gutenberg page.
			return;
		}

		// Include ReduxTemplates default template without wrapper.
		add_filter( 'template_include', array( $this, 'template_include' ) );

		// Override the default content-width when using Redux templates so the template doesn't look like shit.
		add_action( 'wp', array( $this, 'modify_template_content_width' ) );

		// Add ReduxTemplates supported Post types in page template.
		$post_types = get_post_types( array(), 'object' );

		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				if ( isset( $post_type->name ) && isset( $post_type->show_in_rest ) && true === $post_type->show_in_rest ) {
					add_filter( "theme_{$post_type->name}_templates", array( $this, 'add_templates' ) );
				}
			}
		}

		add_filter( 'admin_body_class', array( $this, 'add_body_class' ), 999 );
	}

	/**
	 * Add the redux-template class to the admin body if a redux-templates page type is selected.
	 *
	 * @param string|null $classes Classes string for the admin panel.
	 *
	 * @return string|null
	 * @since 4.1.19
	 */
	public function add_body_class( ?string $classes ): ?string {
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
	 * Override the $content_width variable for themes, so our templates work properly and don't look squished.
	 *
	 * @param array $to_find Template keys to check against.
	 *
	 * @return bool
	 * @since 4.0.0
	 */
	public function check_template( array $to_find = array() ): bool {
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
	 * Override the $content_width variable for themes, so our templates work properly and don't look squished.
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
	 * Include the template
	 *
	 * @param string|null $template Template type.
	 *
	 * @return string|null
	 * @since 4.0.0
	 */
	public function template_include( ?string $template ): ?string {
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
	 * @param array $post_templates Default post template array.
	 *
	 * @return array
	 * @since 4.0.0
	 */
	public function add_templates( array $post_templates ): array {
		$post_templates['redux-templates_contained']  = __( 'Redux Contained', 'redux-framework' );
		$post_templates['redux-templates_full_width'] = __( 'Redux Full Width', 'redux-framework' );
		$post_templates['redux-templates_canvas']     = __( 'Redux Canvas', 'redux-framework' );

		return $post_templates;
	}
}
