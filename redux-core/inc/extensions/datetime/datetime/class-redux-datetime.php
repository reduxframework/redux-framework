<?php
/**
 * Redux Date/Time Field Class
 *
 * @package Redux Extentions
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Datetime
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Datetime', false ) ) {

	/**
	 * Class Redux_Datetime
	 */
	class Redux_Datetime extends Redux_Field {

		/**
		 * Set field defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'date-format'   => 'mm-dd-yy',
				'time-format'   => 'hh:mm TT z',
				'split'         => false,
				'separator'     => ' ',
				'date-picker'   => true,
				'time-picker'   => true,
				'control-type'  => 'slider',
				'num-of-months' => 1,

				// DO NOT CHANGE THESE!!!!
				// It will make this file's javascript sister
				// cry like a deflowered virgin on prom night.
				'timezone-list' => null,
				'timezone'      => '0',
				'hour-min'      => 0,
				'hour-max'      => 23,
				'minute-min'    => 0,
				'minute-max'    => 59,
				'date-min'      => - 1,
				'date-max'      => - 1,
			);

			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since         1.0.0
		 * @access        public
		 * @return        void
		 */
		public function render() {
			$num_of_months = $this->field['num-of-months'];
			if ( 0 === $num_of_months ) {
				$num_of_months = 1;
			}

			// Validate min/max values.
			$hour_min = $this->field['hour-min'];
			$hour_max = $this->field['hour-max'];
			$min_min  = $this->field['minute-min'];
			$min_max  = $this->field['minute-max'];

			if ( $hour_min < 0 || $hour_min > 23 ) {
				$hour_min = 0;
			}

			if ( $hour_max < 0 || $hour_max > 23 ) {
				$hour_max = 23;
			}

			if ( $min_min < 0 || $min_min > 59 ) {
				$min_min = 0;
			}

			if ( $min_max < 0 || $min_max > 59 ) {
				$min_max = 59;
			}

			// Validate min date month.
			if ( is_array( $this->field['date-min'] ) ) {
				if ( isset( $this->field['date-min']['month'] ) ) {
					if ( $this->field['date-min']['month'] < 1 || $this->field['date-min']['month'] > 12 ) {
						$this->field['date-min']['month'] = 1;
					}
				}

				if ( isset( $this->field['date-min']['day'] ) ) {
					if ( $this->field['date-min']['day'] < 1 || $this->field['date-min']['day'] > 31 ) {
						$this->field['date-min']['day'] = 1;
					}
				}
			}

			// Validate max date month.
			if ( is_array( $this->field['date-max'] ) ) {
				if ( isset( $this->field['date-max']['month'] ) ) {
					if ( $this->field['date-max']['month'] < 1 || $this->field['date-max']['month'] > 12 ) {
						$this->field['date-max']['month'] = 1;
					}
				}

				// Validate max date day (imperfect, so we'll just use 31).
				if ( isset( $this->field['date-max']['day'] ) ) {
					if ( $this->field['date-max']['day'] < 1 || $this->field['date-max']['day'] > 31 ) {
						$this->field['date-max']['day'] = 1;
					}
				}
			}

			// Assignment, make it easier to read.
			$field_id     = $this->field['id'];
			$field_name   = $this->field['name'];
			$split        = $this->field['split'];
			$control_type = $this->field['control-type'];

			// Sanitize width
			// Sanitize default value.
			if ( true === $split ) {
				if ( ! is_array( $this->value ) ) {
					$this->value         = array();
					$this->value['time'] = '';
					$this->value['date'] = '';
				}
			} elseif ( is_array( $this->value ) ) {
					$this->value = '';
			}

			// Dummy check, in case something other than select or slider
			// is entered.
			switch ( $control_type ) {
				case 'select':
				case 'slider':
					break;
				default:
					$control_type = 'slider';
			}

			// Set placeholder based on mode.
			if ( true === $split ) {
				$date_placeholder = $this->field['placeholder']['date'] ?? __( 'Date', 'your-domain-here' );
				$time_placeholder = $this->field['placeholder']['time'] ?? __( 'Time', 'your-domain-here' );
			} else {
				$date_placeholder = $this->field['placeholder'] ?? __( 'Date / Time', 'your-domain-here' );
			}

			// Output defaults to div, so JS can read it.
			// Broken up for readability; coz I'm the one who has to debug it!
			echo '<div id="' . esc_attr( $field_id ) . '" class="redux-datetime-container"
                       data-dev-mode="' . esc_attr( $this->parent->args['dev_mode'] ) . '"
                       data-version="' . esc_attr( Redux_Extension_Datetime::$version ) . '"
                       data-id="' . esc_attr( $field_id ) . '"
                       data-mode="' . esc_attr( $split ) . '"
                       data-separator="' . esc_attr( $this->field['separator'] ) . '"
                       data-control-type="' . esc_attr( $control_type ) . '"
                       data-rtl="' . esc_attr( is_rtl() ) . '"
                       data-num-of-months="' . esc_attr( $num_of_months ) . '"
                       data-hour-min="' . esc_attr( $hour_min ) . '"
                       data-hour-max="' . esc_attr( $hour_max ) . '"
                       data-minute-min="' . esc_attr( $min_min ) . '"
                       data-minute-max="' . esc_attr( $min_max ) . '"
                       data-date-min="' . rawurlencode( wp_json_encode( $this->field['date-min'] ) ) . '"
                       data-date-max="' . rawurlencode( wp_json_encode( $this->field['date-max'] ) ) . '"
                       data-timezone="' . esc_attr( $this->field['timezone'] ) . '"
                       data-timezone-list="' . rawurlencode( wp_json_encode( $this->field['timezone-list'] ) ) . '"
                       data-date-picker="' . esc_attr( $this->field['date-picker'] ) . '"
                       data-time-picker="' . esc_attr( $this->field['time-picker'] ) . '"
                       data-time-format="' . esc_attr( $this->field['time-format'] ) . '"
                       data-date-format="' . esc_attr( $this->field['date-format'] ) . '">';

			// If split mode is on, output two text boxes.
			if ( true === $split ) {
				echo '<div class="redux-date-input input_wrapper">';
				echo '<label for="' . esc_attr( $field_id ) . '-date" class="redux-date-input-label">' . esc_html( $date_placeholder ) . '</label>';
				echo ' <input
							data-id="' . esc_attr( $field_id ) . '"
							type="text"
							id="' . esc_attr( $field_id ) . '-date"
							name="' . esc_attr( $field_name ) . '[date]"
							placeholder="' . esc_attr( $date_placeholder ) . '"
							value="' . esc_attr( $this->value['date'] ) . '"
							class="redux-date-picker ' . esc_attr( $this->field['class'] ) . '" />&nbsp;&nbsp;';

				echo '</div>';

				echo '<div class="redux-time-input input_wrapper">';
				echo '<label for="' . esc_attr( $field_id ) . '-time" class="redux-time-input-label">' . esc_html( $time_placeholder ) . '</label>';
				echo ' <input
							data-id="' . esc_attr( $field_id ) . '"
							type="text"
							id="' . esc_attr( $field_id ) . '-time"
							name="' . esc_attr( $field_name ) . '[time]"
							placeholder="' . esc_attr( $time_placeholder ) . '"
							value="' . esc_attr( $this->value['time'] ) . '"
							class="redux-time-picker ' . esc_attr( $this->field['class'] ) . '" />';

				// Otherwise, just one.
			} else {
				echo '<div class="redux-datetime-input single_wrapper">';
				echo '<label for="' . esc_attr( $field_id ) . '-date" class="redux-datetime-input-label">' . esc_attr( $date_placeholder ) . '</label>';
				echo ' <input
							data-id="' . esc_attr( $field_id ) . '"
							type="text"
							id="' . esc_attr( $field_id ) . '-date"
							name="' . esc_attr( $field_name ) . '"
							placeholder="' . esc_attr( $date_placeholder ) . '"
							value="' . esc_attr( $this->value ) . '"
							class="redux-date-picker ' . esc_attr( $this->field['class'] ) . '" />';

			}

			echo '</div>';

			// Close da div, main!
			echo '</div>';
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since         1.0.0
		 * @access        public
		 * @return        void
		 */
		public function enqueue() {
			$min = Redux_Functions::is_min();

			wp_enqueue_script(
				'redux-datetime-slider',
				$this->url . 'vendor/jquery-ui-sliderAccess' . $min . '.js',
				array( 'jquery' ),
				'0.3',
				true
			);

			wp_enqueue_script(
				'redux-datetime',
				$this->url . 'vendor/jquery-ui-timepicker-addon' . $min . '.js',
				array(
					'jquery',
					'jquery-ui-datepicker',
					'jquery-ui-widget',
					'jquery-ui-slider',
					'redux-datetime-slider',
				),
				'1.6.3',
				true
			);

			wp_enqueue_script(
				'redux-field-datetime',
				$this->url . 'redux-datetime' . $min . '.js',
				array( 'jquery', 'redux-datetime', 'redux-js' ),
				Redux_Extension_Datetime::$version,
				true
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-datetime',
					$this->url . 'redux-datetime.css',
					array(),
					Redux_Extension_Datetime::$version,
				);
			}
		}
	}
}
