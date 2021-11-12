<?php
/**
 * Load CSS logic.
 *
 * @package gutenberg-css
 */

namespace ReduxTemplates;

if ( ! class_exists( '\ReduxTemplates\Gutenberg_Custom_CSS' ) ) {

	/**
	 * Class GutenbergCSS.
	 */
	class Gutenberg_Custom_CSS {

		/**
		 * The main instance var.
		 *
		 * @var Gutenberg_Custom_CSS
		 */
		public static $instance = null;

		/**
		 * Initialize the class
		 */
		public function init() {
			add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
			add_action( 'wp_head', array( $this, 'render_server_side_css' ) );
			add_action( 'wp_loaded', array( $this, 'add_attributes_to_blocks' ) );
			add_action( 'plugins_loaded', array( $this, 'remove_themeisle_css' ), 99 );
		}

		/**
		 * Remove ThemeIsle GutenbergCSS.
		 *
		 * @since   4.0.0
		 * @access  public
		 */
		public function remove_themeisle_css() {
			if ( class_exists( '\ThemeIsle\GutenbergCSS' ) ) {
				$instance = \ThemeIsle\GutenbergCSS::instance();
				remove_action(
					'enqueue_block_editor_assets',
					array(
						$instance,
						'enqueue_editor_assets',
					)
				);
				remove_action( 'wp_head', array( $instance, 'render_server_side_css' ) );
				remove_action( 'wp_loaded', array( $instance, 'add_attributes_to_blocks' ) );
			}
		}

		/**
		 * Load Gutenberg assets.
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		public function enqueue_editor_assets() {

			wp_enqueue_code_editor( array( 'type' => 'text/css' ) );

			wp_add_inline_script(
				'wp-codemirror',
				'window.CodeMirror = wp.CodeMirror;'
			);

			wp_set_script_translations( 'redux-gutenberg-css', 'redux-framework' );

		}

		/**
		 * Parse Blocks for Gutenberg and WordPress 5.0
		 *
		 * @param string $content Content to parse.
		 *
		 * @since   1.0.0
		 * @access  public
		 * @return string
		 */
		public function parse_blocks( $content ) {
			if ( ! function_exists( 'parse_blocks' ) ) {
				return gutenberg_parse_blocks( $content );
			} else {
				return parse_blocks( $content );
			}
		}

		/**
		 * Render server-side CSS
		 *
		 * @since   1.0.0
		 * @access  public
		 */
		public function render_server_side_css() {
			if ( function_exists( 'has_blocks' ) && has_blocks( get_the_ID() ) ) {
				global $post;

				if ( ! is_object( $post ) ) {
					return;
				}

				$blocks = $this->parse_blocks( $post->post_content );

				if ( ! is_array( $blocks ) || empty( $blocks ) ) {
					return;
				}

				$style  = "\n" . '<style type="text/css" media="all">' . "\n";
				$style .= $this->cycle_through_blocks( $blocks );
				$style .= "\n" . '</style>' . "\n";

				echo $style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Cycle thorugh Blocks
		 *
		 * @param array $inner_blocks Array of blocks.
		 * @since   1.0.0
		 * @access  public
		 * @return string
		 */
		public function cycle_through_blocks( $inner_blocks ) {
			$style = '';
			foreach ( $inner_blocks as $block ) {
				if ( isset( $block['attrs'] ) ) {
					if ( isset( $block['attrs']['hasCustomCSS'] ) && isset( $block['attrs']['customCSS'] ) ) {
						$style .= $block['attrs']['customCSS'];
					}
				}

				if ( 'core/block' === $block['blockName'] && ! empty( $block['attrs']['ref'] ) ) {
					$reusable_block = get_post( $block['attrs']['ref'] );

					if ( ! $reusable_block || 'wp_block' !== $reusable_block->post_type ) {
						return;
					}

					if ( 'publish' !== $reusable_block->post_status || ! empty( $reusable_block->post_password ) ) {
						return;
					}

					$blocks = $this->parse_blocks( $reusable_block->post_content );

					$style .= $this->cycle_through_blocks( $blocks );
				}

				if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
					$style .= $this->cycle_through_blocks( $block['innerBlocks'] );
				}
			}
			return $style;
		}

		/**
		 * Adds the `hasCustomCSS` and `customCSS` attributes to all blocks, to avoid `Invalid parameter(s): attributes`
		 * error in Gutenberg.
		 *
		 * @since   1.0.3
		 * @access  public
		 */
		public function add_attributes_to_blocks() {
			$registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();

			foreach ( $registered_blocks as $name => $block ) {
				$block->attributes['hasCustomCSS'] = array(
					'type'    => 'boolean',
					'default' => false,
				);

				$block->attributes['customCSS'] = array(
					'type'    => 'string',
					'default' => '',
				);
			}
		}

		/**
		 * Method to return path to child class in a Reflective Way.
		 *
		 * @since   1.0.0
		 * @access  protected
		 * @return  string
		 */
		protected function get_dir() {
			return dirname( __FILE__ );
		}

		/**
		 * The instance method for the static class.
		 * Defines and returns the instance of the static class.
		 *
		 * @static
		 * @since 1.0.0
		 * @access public
		 * @return GutenbergCSS
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
				self::$instance->init();
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'redux-framework' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'redux-framework' ), '1.0.0' );
		}
	}
}
