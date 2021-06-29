<?php
/**
 * Customizer section representing widget area (sidebar).
 *
 * @package    WordPress
 * @subpackage Customize
 * @since      4.0.0
 * @see        WP_Customize_Section
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Redux_Customizer_Section
 */
class Redux_Customizer_Section extends WP_Customize_Section {

	/**
	 * Type of this section.
	 *
	 * @since  4.1.0
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
	 * Constructor.
	 * Any supplied $args override class property defaults.
	 *
	 * @since 3.4.0
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      An specific ID of the section.
	 * @param array                $args    Section arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		// Redux addition.
		if ( isset( $args['section'] ) ) {
			$this->section     = $args['section'];
			$this->description = isset( $this->section['desc'] ) ? $this->section['desc'] : '';
			$this->opt_name    = isset( $args['opt_name'] ) ? $args['opt_name'] : '';
		}
	}

	/**
	 * WP < 4.3 Render
	 */
	protected function render() {
		global $wp_version;
		$version = explode( '-', $wp_version );
		if ( version_compare( $version[0], '4.3', '<' ) ) {
			$this->render_fallback();
		}
	}

	/**
	 * Render the section, and the controls that have been added to it.
	 *
	 * @since 3.4.0
	 */
	protected function render_fallback() {
		$classes = 'accordion-section redux-section control-section control-section-' . $this->type;
		?>
		<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
			<h3 class="accordion-section-title" tabindex="0">
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
				<span class="screen-reader-text"><?php esc_attr_e( 'Press return or enter to expand', 'redux-framework' ); ?></span>
			</h3>
			<ul class="accordion-section-content redux-main">
				<?php
				if ( isset( $this->opt_name ) && isset( $this->section ) ) {
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action( "redux/page/{$this->opt_name}/section/before", $this->section );
				}
				?>
				<?php if ( ! empty( $this->description ) ) { ?>
					<li class="customize-section-description-container">
						<p class="description customize-section-description legacy"><?php echo wp_kses_post( $this->description ); ?></p>
					</li>
				<?php } ?>
			</ul>
		</li>
		<?php
	}


	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @since 4.1.0
	 *
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$array             = parent::json();
		$array['opt_name'] = $this->opt_name;
		return $array;
	}

	/**
	 * An Underscore (JS) template for rendering this section.
	 * Class variables for this section class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Section::json()}.
	 *
	 * @see   WP_Customize_Section::print_template()
	 * @since 4.3.0
	 */
	protected function render_template() {

		?>
		<li id="accordion-section-{{ data.id }}"
			class="redux-standalone-section redux-customizer-opt-name redux-section accordion-section control-section control-section-{{ data.type }}"
			data-opt-name="{{ data.opt_name }}">
			<h3 class="accordion-section-title" tabindex="0">
				{{ data.title }}
				<span class="screen-reader-text"><?php esc_html_e( 'Press return or enter to open', 'redux-framework' ); ?></span>
			</h3>
			<ul class="accordion-section-content redux-main">

				<li class="customize-section-description-container">
					<div class="customize-section-title">
						<button class="customize-section-back" tabindex="-1">
							<span class="screen-reader-text"><?php esc_html_e( 'Back', 'redux-framework' ); ?></span>
						</button>
						<h3>
							<span class="customize-action">
								{{{ data.customizeAction }}}
							</span> {{ data.title }}
						</h3>
					</div>
					<# if ( data.description ) { #>
					<p class="description customize-section-description">{{{ data.description }}}</p>
					<# } #>
					<?php
					if ( isset( $this->opt_name ) && isset( $this->section ) ) {
						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						do_action( "redux/page/{$this->opt_name}/section/before", $this->section );
					}
					?>
				</li>
			</ul>
		</li>
		<?php
	}




}
