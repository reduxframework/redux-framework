<?php
/**
 * Redux Customizer Panel Class
 *
 * @class Redux_Customizer_Panel
 * @version 4.0.0
 * @package Redux Framework/Extentions
 */

defined( 'ABSPATH' ) || exit;

/**
 * Customizer section representing widget area (sidebar).
 *
 * @package    WordPress
 * @subpackage Customize
 * @since      4.1.0
 * @see        WP_Customize_Section
 */
class Redux_Customizer_Panel extends WP_Customize_Panel {

	/**
	 * Type of this panel.
	 *
	 * @since  4.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'redux';

	/**
	 * Panel opt_name.
	 *
	 * @since  4.0.0
	 * @access public
	 * @var string
	 */
	public $opt_name = '';

	/**
	 * Section array.
	 *
	 * @var array|mixed
	 */
	public $section = array();

	/**
	 * Constructor.
	 * Any supplied $args override class property defaults.
	 *
	 * @since 4.0.0
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      A specific ID for the panel.
	 * @param array                $args    Panel arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		// Redux addition.
		if ( isset( $args['section'] ) ) {
			$this->section     = $args['section'];
			$this->description = $this->section['desc'] ?? '';
			$this->opt_name    = $args['opt_name'] ?? '';
		}
	}

	/**
	 * WP < 4.3 Render
	 *
	 * @since
	 * @access protected
	 */
	protected function render() {
		global $wp_version;
		$version = explode( '-', $wp_version );
		if ( version_compare( $version[0], '4.3', '<' ) ) {
			$this->render_fallback();
		}
	}

	/**
	 * Render.
	 */
	protected function render_fallback() {
		$classes = 'accordion-section redux-main redux-panel control-section control-panel control-panel-' . esc_attr( $this->type );

		?>
		<li id="accordion-panel-<?php echo esc_attr( $this->id ); ?>" class="<?php echo esc_attr( $classes ); ?>"
			data-width="<?php echo isset( $this->section['customizer_width'] ) ? esc_attr( $this->section['customizer_width'] ) : ''; ?>">
			<h3 class="accordion-section-title" tabindex="0">
				<?php if ( isset( $this->section['icon'] ) && ! empty( $this->section['icon'] ) ) : ?>
					<i class="<?php echo esc_attr( $this->section['icon'] ); ?>"></i>
				<?php endif; ?>
				<?php
				echo wp_kses(
					$this->title,
					array(
						'em'     => array(),
						'i'      => array(),
						'strong' => array(),
						'span'   => array(
							'class' => array(),
							'style' => array(),
						),
					)
				);
				?>
				<span class="screen-reader-text"><?php esc_html_e( 'Press return or enter to open this panel', 'redux-framework' ); ?></span>
			</h3>
			<ul class="accordion-sub-container control-panel-content">
				<table class="form-table">
					<tbody><?php $this->render_content(); ?></tbody>
				</table>
			</ul>
		</li>
		<?php
	}

	/**
	 * Render the sections that have been added to the panel.
	 *
	 * @since  4.1.0
	 * @access protected
	 */
	protected function render_content() {
		?>
		<li class="panel-meta accordion-section redux-panel redux-panel-meta control-section
		<?php
		if ( empty( $this->description ) ) {
			echo ' cannot-expand';
		}
		?>
		">
			<div class="accordion-section-title" tabindex="0">
				<span class="preview-notice">
					<?php /* translators: %s is the site/panel title in the Customizer */ ?>
					<?php printf( esc_html__( 'You are customizing', 'redux-framework' ) . ' %s', '<strong class="panel-title">' . esc_html( $this->title ) . '</strong>' ); ?>
				</span>
			</div>
			<?php if ( ! empty( $this->description ) ) { ?>
				<div class="accordion-section-content description legacy">
					<?php echo wp_kses_post( $this->description ); ?>
				</div>
			<?php } ?>
		</li>
		<?php
	}

	/**
	 * JSON.
	 *
	 * @return array
	 */
	public function json(): array {
		$array = wp_array_slice_assoc(
			parent::json(),
			array(
				'id',
				'title',
				'description',
				'priority',
				'type',
			)
		);

		$array['content']        = $this->get_content();
		$array['active']         = $this->active();
		$array['instanceNumber'] = $this->instance_number;

		// BEGIN Redux Additions.
		$array['width'] = $this->section['customizer_width'] ?? '';
		$array['icon']  = ( isset( $this->section['icon'] ) && ! empty( $this->section['icon'] ) ) ? $this->section['icon'] : 'hide';

		$array['opt_name'] = $this->opt_name;
		return $array;
	}

	/**
	 * An Underscore (JS) template for this panel's content (but not its container).
	 * Class variables for this panel class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Panel::json()}.
	 *
	 * @see   WP_Customize_Panel::print_template()
	 * @since 4.3.0
	 */
	protected function content_template() {
		?>
		<li
			class="panel-meta customize-info redux-customizer-opt-name redux-panel accordion-section <# if ( ! data.description ) { #> cannot-expand<# } #>"
			data-opt-name="{{{ data.opt_name }}}">
			<button class="customize-panel-back" tabindex="-1">
				<span class="screen-reader-text"><?php esc_attr_e( 'Back', 'redux-framework' ); ?></span></button>
			<div class="accordion-section-title">
				<span class="preview-notice">
					<?php /* translators: %s is the site/panel title in the Customizer */ ?>
					<?php printf( esc_html__( 'You are customizing', 'redux-framework' ) . ' %s', '<strong class="panel-title">{{ data.title }}</strong>' ); ?>
				</span>
				<# if ( data.description ) { #>
				<button
					class="customize-help-toggle dashicons dashicons-editor-help"
					tabindex="0"
					aria-expanded="false">
					<span class="screen-reader-text"><?php esc_attr_e( 'Help', 'redux-framework' ); ?></span></button>
				<# } #>
			</div>
			<# if ( data.description ) { #>
			<div class="description customize-panel-description">
				{{{ data.description }}}
			</div>
			<# } #>
		</li>
		<?php
	}

	/**
	 * An Underscore (JS) template for rendering this panel's container.
	 * Class variables for this panel class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Panel::json()}.
	 *
	 * @see   WP_Customize_Panel::print_template()
	 * @since 4.3.0
	 */
	protected function render_template() {
		?>
		<li id="accordion-panel-{{ data.id }}"
			class="accordion-section redux-panel control-section control-panel control-panel-{{ data.type }}"
			data-width="{{ data.width }}">
			<h3 class="accordion-section-title" tabindex="0">
				<# if ( data.icon ) { #><i class="{{ data.icon }}"></i> <# } #>{{ data.title }}
				<span class="screen-reader-text"><?php echo esc_html__( 'Press return or enter to open this panel', 'redux-framework' ); ?></span>
			</h3>
			<ul class="accordion-sub-container control-panel-content"></ul>
		</li>
		<?php
	}
}
