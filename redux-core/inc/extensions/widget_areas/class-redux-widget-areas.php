<?php
/**
 * Redux Widget Areas Class
 *
 * @package Redux Pro
 * @author  Dovy Paukstys (dovy)
 * @class   Redux_Widget_Areas
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Widget_Areas' ) ) {

	/**
	 * Main ReduxFramework customizer extension class
	 *
	 * @since       1.0.0
	 */
	class Redux_Widget_Areas {

		/**
		 * Extension URI.
		 *
		 * @var string
		 */
		private $extension_url;

		/** Extension directory.
		 *
		 * @var string
		 */
		private $extension_dir;

		/**
		 * Array of enabled widget_areas
		 *
		 * @since    1.0.0
		 * @var      array
		 */
		protected $widget_areas = array();

		/**
		 * Widget array.
		 *
		 * @var array
		 */
		protected $orig = array();

		/**
		 * @var object
		 */
		private $parent;

		/**
		 * Redux_Widget_Areas constructor.
		 *
		 * @param object $parent ReduxFramework pointer.
		 */
		public function __construct( $parent ) {
			global $pagenow;

			$this->parent = $parent;

			if ( empty( $this->extension_dir ) ) {
				$this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
				$this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
			}

			add_action( 'init', array( &$this, 'register_custom_widget_areas' ), 1000 );

			if ( 'widgets.php' === $pagenow ) {
				add_action( 'admin_print_scripts', array( $this, 'add_new_widget_area_box' ) );
				add_action( 'load-widgets.php', array( $this, 'add_widget_area_area' ), 100 );
				add_action( 'load-widgets.php', array( $this, 'enqueue' ), 100 );
			}
		}

		/**
		 * Function to create the HTML used to create widget_areas.
		 *
		 * @since     1.0.0
		 */
		public function add_new_widget_area_box() {
			$nonce     = wp_create_nonce( 'add-redux-widget_area-nonce' );
			$del_nonce = wp_create_nonce( 'delete-redux-widget_area-nonce' );

			?>
			<script type="text/html" id="redux-add-widget-template">
				<div id="redux-add-widget" class="widgets-holder-wrap">
					<div class="">
						<input type="hidden" name="redux-nonce" value="<?php echo esc_attr( $del_nonce ); ?>"/>
						<div class="sidebar-name">
							<h3><?php echo esc_html__( 'Create Widget Area', 'redux-framework' ); ?> <span class="spinner"></span></h3>
						</div>
						<div class="sidebar-description">
							<form id="addWidgetAreaForm" action="" method="post">
								<input type="hidden" name="redux-add-widget-nonce" value="<?php echo esc_attr( $nonce ); ?>"/>
								<div class="widget-content">
									<input
											id="redux-add-widget-input" name="redux-add-widget-input" type="text"
											class="regular-text"
											title="<?php echo esc_attr__( 'Name', 'redux-framework' ); ?>"
											placeholder="<?php echo esc_attr__( 'Name', 'redux-framework' ); ?>"/>
								</div>
								<div class="widget-control-actions">
									<div class="aligncenter">
										<input
												class="addWidgetArea-button button-primary"
												type="submit"
												value="<?php echo esc_attr__( 'Create Widget Area', 'redux-framework' ); ?>"/>
									</div>
									<br class="clear">
								</div>
							</form>
						</div>
					</div>
				</div>
			</script>
			<?php
		}

		/**
		 * Add widget area.
		 */
		public function add_widget_area_area() {
			if ( isset( $_POST ) && isset( $_POST['redux-add-widget-nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['redux-add-widget-nonce'] ) ), 'add-redux-widget_area-nonce' ) ) {
				if ( isset( $_POST['redux-add-widget-input'] ) && ! empty( $_POST['redux-add-widget-input'] ) ) {
					$this->widget_areas = $this->get_widget_areas();

					$this->widget_areas[] = $this->check_widget_area_name( sanitize_text_field( wp_unslash( $_POST['redux-add-widget-input'] ) ) );

					$this->save_widget_areas();

					wp_safe_redirect( admin_url( 'widgets.php' ) );

					die();
				}
			}
		}

		/**
		 * Before we create a new widget_area, verify it doesn't already exist. If it does, append a number to the name.
		 *
		 * @param    string $name Name of the widget_area to be created.
		 *
		 * @return    string|void     $name      Name of the new widget_area just created.
		 * @since     1.0.0
		 */
		private function check_widget_area_name( string $name ) {
			if ( empty( $GLOBALS['wp_registered_widget_areas'] ) ) {
				return $name;
			}

			$taken = array();

			foreach ( $GLOBALS['wp_registered_widget_areas'] as $widget_area ) {
				$taken[] = $widget_area['name'];
			}

			$taken = array_merge( $taken, $this->widget_areas );

			if ( in_array( $name, $taken, true ) ) {
				$counter = substr( $name, - 1 );

				if ( ! is_numeric( $counter ) ) {
					$new_name = $name . ' 1';
				} else {
					$new_name = substr( $name, 0, - 1 ) . ( (int) $counter + 1 );
				}

				$name = $this->check_widget_area_name( $new_name );
			}

			echo $name; // phpcs:disable WordPress.Security.EscapeOutput

			exit();
		}

		/**
		 * Save Widget Areas.
		 */
		private function save_widget_areas() {
			set_theme_mod( 'redux-widget-areas', array_unique( $this->widget_areas ) );
		}

		/**
		 * Register and display the custom widget_area areas we have set.
		 *
		 * @since     1.0.0
		 */
		public function register_custom_widget_areas() {

			// If the single instance hasn't been set, set it now.
			if ( empty( $this->widget_areas ) ) {
				$this->widget_areas = $this->get_widget_areas();
			}

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$this->orig = array_unique( apply_filters( 'redux/' . $this->parent->args['opt_name'] . '/widget_areas', array() ) );

			if ( ! empty( $this->orig ) && $this->orig !== $this->widget_areas ) {
				$this->widget_areas = array_unique( array_merge( $this->widget_areas, $this->orig ) );
				$this->save_widget_areas();
			}

			$options = array(
				'before_title'  => '<h3 class="widgettitle">',
				'after_title'   => '</h3>',
				'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
				'after_widget'  => '</div>',
			);

			$options = apply_filters( 'redux_custom_widget_args', $options );

			if ( is_array( $this->widget_areas ) ) {
				foreach ( array_unique( $this->widget_areas ) as $widget_area ) {
					$options['class'] = 'redux-custom';
					$options['name']  = $widget_area;
					$options['id']    = sanitize_key( $widget_area );
					register_sidebar( $options );
				}
			}
		}


		/**
		 * Return the widget_areas array.
		 *
		 * @since     1.0.0
		 * @return    array    If not empty, active redux widget_areas are returned.
		 */
		public function get_widget_areas(): array {

			// If the single instance hasn't been set, set it now.
			if ( ! empty( $this->widget_areas ) ) {
				return $this->widget_areas;
			}

			$db = get_theme_mod( 'redux-widget-areas' );

			if ( ! empty( $db ) ) {
				$this->widget_areas = array_unique( array_merge( $this->widget_areas, $db ) );
			}

			return $this->widget_areas;
		}

		/**
		 * Delete widget area.
		 */
		public function redux_delete_widget_area_area() {
			if ( isset( $_POST ) && isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ), 'delete-redux-widget_area-nonce' ) ) {
				if ( isset( $_POST['name'] ) && ! empty( sanitize_text_field( wp_unslash( $_POST['name'] ) ) ) ) {
					$name               = sanitize_text_field( wp_unslash( $_POST['name'] ) );
					$this->widget_areas = $this->get_widget_areas();
					$key                = array_search( $name, $this->widget_areas, true );

					if ( $key >= 0 ) {
						unset( $this->widget_areas[ $key ] );
						$this->save_widget_areas();
					}

					echo 'widget_area-deleted';
				}
			}

			die();
		}

		/**
		 * Enqueue support files.
		 */
		public function enqueue() {
			$min = Redux_Functions::is_min();

			wp_enqueue_style( 'dashicons' );

			wp_enqueue_script(
				'redux-widget-areas-js',
				$this->extension_url . 'redux-extension-widget-areas' . $min . '.js',
				array( 'jquery' ),
				Redux_Extension_Widget_Areas::$version,
				true
			);

			wp_enqueue_style(
				'redux-widget-areas-css',
				$this->extension_url . 'redux-extension-widget-areas.css',
				array(),
				time()
			);

			// Localize script.
			wp_localize_script(
				'redux-widget-areas-js',
				'reduxWidgetAreasLocalize',
				array(
					'count'   => count( $this->orig ),
					'delete'  => esc_html__( 'Delete', 'redux-framework' ),
					'confirm' => esc_html__( 'Confirm', 'redux-framework' ),
					'cancel'  => esc_html__( 'Cancel', 'redux-framework' ),
				)
			);
		}
	}
}
