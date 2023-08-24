<?php
/**
 * Redux Icon Select Field Class
 *
 * @package Redux Extentions
 * @author  Dovy Paukstys <dovy@reduxframework.com> & Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Icon_Select
 * @version 4.4.2
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Icon_Select' ) ) {

	/**
	 * Main ReduxFramework_icon_select class
	 *
	 * @since       1.0.0
	 */
	class Redux_Icon_Select extends Redux_Field {

		/**
		 * Stylesheet URL array, for enqueue.
		 *
		 * @var array
		 */
		private $stylesheet_url = array();

		/**
		 * Stylesheet data array.
		 *
		 * @var array
		 */
		private $stylesheet_data = array();

		/**
		 * Error flag to prevent render and enqueue.
		 *
		 * @var bool
		 */
		private $is_error = false;
		/**
		 * ReduxFramework_Icon_Select constructor.
		 *
		 * @param array          $field  Field array.
		 * @param string         $value  Value.
		 * @param ReduxFramework $redux ReduxFramework object.
		 *
		 * @throws ReflectionException Exception.
		 */
		public function __construct( $field = array(), $value = '', $redux = object ) {
			parent::__construct( $field, $value, $redux );

			if ( empty( $field ) ) {
				return;
			}

			if ( ! is_array( $this->field['stylesheet'] ) ) {
				if ( '' !== $this->field['stylesheet'] ) {
					$this->stylesheet_data[] = array(
						'url'     => $this->field['stylesheet'],
						'class'   => $this->field['prefix'],
						'title'   => basename( $this->field['stylesheet'] ),
						'icons'   => $this->field['options'],
						'exclude' => $this->field['exclude_icons'],
					);

					unset( $this->field['exclude_icons'] );
					unset( $this->field['options'] );
					unset( $this->field['stylesheet'] );
					unset( $this->field['prefix'] );
				}
			} else {
				$arr = $this->field['stylesheet'];

				if ( isset( $arr['url'] ) || isset( $arr['title'] ) ) {
					$arr = array( $arr );
				}

				foreach ( $arr as $idx => $val ) {
					$val['url']     = ! empty( $val['url'] ) ? $val['url'] : '';
					$val['title']   = ! empty( $val['title'] ) ? $val['title'] : basename( $val['url'] );
					$val['class']   = ! empty( $val['prefix'] ) ? $val['prefix'] : '';
					$val['icons']   = ! empty( $val['icons'] ) ? $val['icons'] : array();
					$val['exclude'] = ! empty( $val['exclude'] ) ? $val['exclude'] : '';
					$val['regex']   = ! empty( $val['regex'] ) ? $val['regex'] : '';

					$arr[ $idx ] = $val;
				}

				$this->stylesheet_data = $arr;
			}

			if ( ! empty( $this->stylesheet_data ) ) {
				foreach ( $this->stylesheet_data as $idx => $sub_arr ) {
					if ( false === stripos( $sub_arr['url'], '//' ) ) {
						$this->stylesheet_url[] = '';
					} else {
						$this->stylesheet_url[] = $sub_arr['url'];
					}

					if ( empty( $sub_arr['icons'] ) && ! empty( $sub_arr ) ) {
						if ( false === stripos( $sub_arr['url'], '//' ) ) {
							$to_parse = '';
						} else {
							$to_parse = wp_remote_get( $sub_arr['url'] );

							if ( is_wp_error( $to_parse ) ) {
								$data = array(
									'parent'  => $this->parent,
									'type'    => 'error',
									'msg'     => 'Icon Select: ' . esc_html__( 'Error retrieving stylesheet ', 'redux-framework' ) . ' "' . $sub_arr['url'] . '". (' . esc_html( $to_parse->get_error_code() ) . ' - ' . esc_html( $to_parse->get_error_message() ) . ')',
									'id'      => 'Icon_Select_notice_',
									'dismiss' => false,
								);

								Redux_Admin_Notices::set_notice( $data );

								$this->is_error = true;

								return;
							} elseif ( 200 !== wp_remote_retrieve_response_code( $to_parse ) ) {
								$data = array(
									'parent'  => $this->parent,
									'type'    => 'error',
									'msg'     => 'Icon Select: ' . esc_html__( 'Error retrieving stylesheet ', 'redux-framework' ) . ' "' . $sub_arr['url'] . '". (' . wp_remote_retrieve_response_code( $to_parse ) . ' - ' . esc_html( wp_remote_retrieve_response_message( $to_parse ) ) . ')',
									'id'      => 'Icon_Select_notice_',
									'dismiss' => false,
								);

								Redux_Admin_Notices::set_notice( $data );

								$this->is_error = true;

								return;
							}

							$to_parse = $to_parse['body'];
						}

						// Remove whitespace.
						$to_parse = preg_replace( '/\s+/', ' ', $to_parse );

						$regex_arr = array( '/.([\w-]+):{2}before{content/mi', '/.([\w-]+):{2}before { content/mi', '/.([\w-]+):{1}before{content:/mi', '/.([\w-]+):{1}before { content:/mi' );

						if ( ! is_array( $sub_arr['exclude'] ) ) {
							if ( empty( $sub_arr['exclude'] ) ) {
								$sub_arr['exclude'] = array();
							} else {
								$sub_arr['exclude'] = array( $sub_arr['exclude'] );
							}
						}

						$regex_arr = $this->array_merge( $sub_arr['exclude'], $regex_arr );

						$str = array();

						foreach ( $regex_arr as $regex ) {
							preg_match_all( $regex, $to_parse, $output_array );

							$str = $this->array_merge( $str, $output_array[1] );
						}

						if ( ! empty( $sub_arr['exclude'] ) && is_array( $sub_arr['exclude'] ) ) {
							$str = $this->array_delete( $str, $sub_arr['exclude'] );
						}

						if ( ! empty( $str ) ) {
							$sub_arr['icons'] = $str;

							$this->stylesheet_data[ $idx ]['icons'] = $str;
						}
					}

					if ( ! $this->is_multi_array( $sub_arr['icons'] ) ) {
						$new_array = array();

						if ( ! isset( $sub_arr['icons'][0] ) ) {
							foreach ( $sub_arr['icons'] as $class_name => $class ) {
								$new_array[] = $class . ' ' . $class_name;
							}

							$this->stylesheet_data[ $idx ]['icons'] = $new_array;
						} else {
							$this->stylesheet_data[ $idx ]['icons'] = $sub_arr['icons'];
						}
					}
				}
			}
		}

		/**
		 * Combine the array with null check.
		 *
		 * @param mixed $array1 First array.
		 * @param mixed $array2 Second array.
		 *
		 * @return array
		 */
		private function array_merge( $array1, $array2 ): array {
			if ( ! is_array( $array1 ) ) {
				$array1 = array();
			}

			if ( ! is_array( $array2 ) ) {
				$array2 = array();
			}

			return array_merge( $array1, $array2 );
		}

		/**
		 * Check if the array is multidimensional.
		 *
		 * @param array $my_array Array to evaluate.
		 *
		 * @return bool
		 */
		private function is_multi_array( array $my_array ): bool {
			if ( count( $my_array ) === count( $my_array, COUNT_RECURSIVE ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Set field defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'options'          => array(),
				'stylesheet'       => '',
				'output'           => true,
				'prefix'           => '',
				'selector'         => '',
				'height'           => '',
				'enqueue'          => true,
				'enqueue_frontend' => true,
				'class'            => '',
				'button_title'     => esc_html__( 'Add Icon', 'redux-framework' ),
				'remove_title'     => esc_html__( 'Remove Icon', 'redux-framework' ),
				'elusive'          => true,
				'fontawesome'      => true,
				'dashicons'        => true,
				'exclude_icons'    => '',
			);

			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @return      void
		 * @since       1.0.0
		 * @access      public
		 */
		public function render() {
			if ( $this->is_error ) {
				return;
			}

			$icon_sets = array();

			if ( true === $this->field['fontawesome'] ) {
				$icon_sets['font-awesome'] = 'Font Awesome';
			}

			if ( true === $this->field['dashicons'] ) {
				$icon_sets['dashicons'] = 'Dashicons';
			}

			if ( true === $this->field['elusive'] ) {
				$icon_sets['elusive'] = 'Elusive';
			}

			// Custom icon sets for <select>.
			foreach ( $this->stylesheet_data as $value ) {
				$icon_sets[ strtolower( str_replace( array( '.', ' ' ), '-', $value['title'] ) ) ] = $value['title'];
			}

			$icon_sets = rawurlencode( wp_json_encode( $icon_sets ) );

			// Data to send to AJAX.
			$ajax_data = array();
			$temp      = array();

			foreach ( $this->stylesheet_data as $value ) {
				$temp['title'] = $value['title'];
				$temp['class'] = $value['class'];
				$temp['icons'] = $value['icons'];

				$ajax_data[] = $temp;
			}

			$options_json = '';
			if ( ! empty( $ajax_data ) ) {
				$options_json = ' data-options=' . rawurlencode( wp_json_encode( $ajax_data ) );
			}

			$nonce  = wp_create_nonce( 'redux_icon_nonce' );
			$hidden = ( empty( $this->value ) ) ? ' hidden' : '';

			echo '<div class="redux-icon-select" data-icon-sets="' . esc_attr( $icon_sets ) . '"' . esc_html( $options_json ) . '>';
			echo '<span class="redux-icon-select-preview' . esc_attr( $hidden ) . '"><i class="' . esc_attr( $this->value ) . '"></i></span>';
			echo '<a href="#" class="button button-primary redux-icon-add" data-nonce="' . esc_attr( $nonce ) . '">' . esc_html( $this->field['button_title'] ) . '</a>';
			echo '<a href="#" class="button redux-warning-primary redux-icon-remove' . esc_attr( $hidden ) . '">' . esc_html( $this->field['remove_title'] ) . '</a>';
			echo '<input type="hidden" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '" value="' . esc_attr( $this->value ) . '" class="redux-icon-value" id="' . esc_attr( $this->field['id'] ) . '" />';
			echo '</div>';
		}

		/**
		 * Render modal icon popup in the footer.
		 *
		 * @return void
		 */
		public function add_footer_modal_icon() {
			?>
			<div id="redux-modal-icon" class="redux-modal redux-modal-icon hidden">
				<div class="redux-modal-table">
					<div class="redux-modal-table-cell">
						<div class="redux-modal-overlay"></div>
						<div class="redux-modal-inner">
							<div class="redux-modal-title">
								<?php esc_html_e( 'Add Icon', 'redux-framework' ); ?>
								<div class="redux-modal-close redux-icon-close"></div>
							</div>
							<div class="redux-modal-header">
								<label for="redux-icon-select-font">
									<select class="redux-icon-select-font" id="redux-icon-select-font"></select>
								</label>
								<label for="redux-icon-search">
									<input type="text" placeholder="<?php esc_html_e( 'Search...', 'redux-framework' ); ?>" class="redux-icon-search" id="redux-icon-search"/>
								</label>
								<div class="redux-modal-loading">
									<div class="redux-loading"></div>
								</div>
							</div>
							<div class="redux-modal-content">
								<div class="redux-modal-load"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Do enqueue for all instances of the field.
		 *
		 * @return void
		 */
		public function always_enqueue() {
			if ( isset( $this->stylesheet_url ) && is_array( $this->stylesheet_url ) && isset( $this->field['enqueue'] ) && $this->field['enqueue'] ) {
				foreach ( $this->stylesheet_url as $idx => $stylesheet_url ) {
					wp_enqueue_style(
						$this->field['id'] . '-webfont-' . $idx,
						$stylesheet_url,
						array(),
						Redux_Core::$version
					);
				}
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @return      void
		 * @since       1.0.0
		 * @access      public
		 */
		public function enqueue() {
			if ( $this->is_error || '' === $this->url ) {
				return;
			}

			add_action( 'admin_footer', array( $this, 'add_footer_modal_icon' ) );
			add_action( 'customize_controls_print_footer_scripts', array( $this, 'add_footer_modal_icon' ) );

			$min = Redux_Functions::isMin();

			wp_enqueue_script(
				'redux-field-icon-select',
				$this->url . 'redux-icon-select' . $min . '.js',
				array( 'jquery', 'redux-js', 'wp-util' ),
				Redux_Core::$version,
				true
			);

			wp_enqueue_style(
				'redux-field-icon-select',
				$this->url . 'redux-icon-select.css',
				array(),
				Redux_Core::$version
			);
		}

		/**
		 * This function is unused, but necessary to trigger output.
		 *
		 * @param mixed $data CSS data.
		 *
		 * @return mixed|string|void
		 */
		public function css_style( $data ) {
			return $data;
		}

		/**
		 * Used to enqueue to Webfont to the front-end
		 *
		 * @param string|null|array $style CSS style.
		 */
		public function output( $style = '' ) {
			if ( $this->is_error ) {
				return;
			}

			if ( true === $this->field['elusive'] ) {
				Redux_Functions_Ex::enqueue_elusive_font();
			}

			if ( true === $this->field['fontawesome'] ) {
				Redux_Functions_Ex::enqueue_font_awesome();
			}

			if ( isset( $this->stylesheet_url ) && is_array( $this->stylesheet_url ) && $this->field['enqueue_frontend'] ) {
				foreach ( $this->stylesheet_url as $idx => $stylesheet_url ) {
					wp_enqueue_style(
						$this->field['id'] . '-webfont-' . $idx,
						$stylesheet_url,
						array(),
						Redux_Core::$version
					);
				}
			}
		}

		/**
		 * Remove items from an array.
		 *
		 * @param array $my_array   The array to manage.
		 * @param mixed $element An array or a string of the item to remove.
		 *
		 * @return array The cleaned array with reset keys.
		 */
		private function array_delete( array $my_array, $element ): array {
			return ( is_array( $element ) ) ? array_values( array_diff( $my_array, $element ) ) : array_values( array_diff( $my_array, array( $element ) ) );
		}
	}
}
