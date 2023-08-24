<?php
/**
 * Social Profiles Widget.
 *
 * @package     Redux
 * @subpackage  Extensions
 * @author      Kevin Provance (kprovance)
 */

// phpcs:ignoreFile

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Social_Profiles_Widget' ) ) {

	/**
	 * Class Redux_Social_Profiles_Widget
	 */
	class Redux_Social_Profiles_Widget {

		/**
		 * Field ID.
		 *
		 * @var string
		 */
		public $field_id = '';

		/**
		 * ReduxFramework object pointer.
		 *
		 * @var null
		 */
		public $parent = null;

		/**
		 * Redux_Social_Profiles_Widget constructor.
		 *
		 * @param object $redux   ReduxFramework object.
		 * @param string $field_id Field ID.
		 */
		public function __construct( $redux, string $field_id ) {
			return;

			$this->parent   = $redux;
			$this->field_id = $field_id;


			$this->params = array(
				'parent'   => $this->parent,
				'field_id' => $this->field_id,
			);

			add_action( 'widgets_init', array( $this, 'load_widget' ), 0 );
		}

		/**
		 * Load widget
		 */
		public function load_widget() {
			$x = new Extend_WP_Widget_Factory();
			$x->register( 'Redux_Social_Widget_Display', $this->params );
		}
	}

	/**
	 * Class Extend_WP_Widget_Factory
	 */
	// phpcs:ignore Generic.Files.OneClassPerFile
	class Extend_WP_Widget_Factory extends WP_Widget_Factory {
		/**
		 * Register widget.
		 *
		 * @param string|WP_Widget $widget Widget class.
		 * @param null             $param  Who knows.
		 */
		public function register( $widget, $param = null ) {
			$this->widgets[ $widget ] = new $widget( $param );
		}
	}

	/**
	 * Class Redux_Social_Widget_Display
	 */
	class Redux_Social_Widget_Display extends WP_Widget {

		public $show_instance_in_rest = true;

		/**
		 * Redux_Social_Widget_Display constructor.
		 *
		 * @param array $params Params.
		 */
		public function __construct( $params ) {

			extract( $params ); // phpcs:ignore WordPress.PHP.DontExtract

			$this->parent   = $parent;
			$this->field_id = $field_id;

			$widget_ops = array(
				'classname'   => 'redux-social-icons-display',
				'description' => __( 'Display social media links', 'redux-framework' ),
				'show_instance_in_rest' => true
			);

			$control_ops = array(
				'width'                 => 250,
				'height'                => 200,
				'id_base'               => 'redux-social-icons-display',
				'show_instance_in_rest' => true,
			);

			parent::__construct( 'redux-social-icons-display', 'Redux Social Widget', $widget_ops, $control_ops );
		}

		/**
		 * Widget render.
		 *
		 * @param array $args Args.
		 * @param array $instance Instance.
		 */
		public function widget( $args, $instance ) {
			include_once 'class-redux-social-profiles-functions.php';

			extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract

			$title         = $instance['title'];
			$redux_options = get_option( $this->parent->args['opt_name'] );

			$social_items = $redux_options[ $this->field_id ];

			echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput

			if ( $title ) {
				echo $before_title . esc_html( $title ) . $after_title; // phpcs:ignore WordPress.Security.EscapeOutput
			}

			?>
			<ul class="redux-social-media-list clearfix">
				<?php
				if ( is_array( $social_items ) ) {
					foreach ( $social_items as $social_item ) {
						if ( $social_item['enabled'] ) {
							$icon       = $social_item['icon'];
							$color      = $social_item['color'];
							$background = $social_item['background'];
							$base_url   = $social_item['url'];
							$id         = $social_item['id'];

							// phpcs:ignore WordPress.NamingConventions.ValidHookName
							$url = apply_filters( 'redux/extensions/social_profiles/' . $this->parent->args['opt_name'] . '/icon_url', $id, $base_url );

							echo '<li>';
							echo '<a href="' . esc_url( $url ) . '">';
							Redux_Social_Profiles_Functions::render_icon( $icon, $color, $background, '' );
							echo '</a>';
							echo '</li>';
						}
					}
				}
				?>
			</ul>
			<?php

			echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput
		}

		/**
		 * Update Widget.
		 *
		 * @param array $new_instance New instance.
		 * @param array $old_instance Old instance.
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ): array {
			$instance          = $old_instance;
			$instance['title'] = wp_strip_all_tags( $new_instance['title'] ?? '' );

			return $instance;
		}

		/**
		 * Render widget form.
		 *
		 * @param array $instance Instance.
		 *
		 * @return void
		 */
		public function form( $instance ) {
			$defaults = array(
				'title' => esc_html__( 'Social', 'redux-framework' ),
			);

			$instance = wp_parse_args( (array) $instance, $defaults );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
					<?php esc_html_e( 'Title', 'redux-framework' ); ?>
					:
					<input
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
						value="<?php echo esc_attr( $instance['title'] ); ?>"/>
				</label>
				<label for="redux-social-icons-info">
					<?php
					$tab  = Redux_Helpers::tab_from_field( $this->parent, 'social_profiles' );
					$slug = $this->parent->args['page_slug'];

					// translators: %1$s: Settings page URL.  %2$s: Closing a tag.
					printf( esc_html__( 'Control which icons are displayed and their urls on the %1$sssettings page%2$s', 'redux-framework' ), '<a href="' . esc_url( admin_url( 'admin.php?page=' . esc_attr( $slug ) . '&tab=' . esc_attr( $tab ) ) ) . '">', '</a>' );
					?>
				</label>
			</p>
			<?php
		}
	}
}
