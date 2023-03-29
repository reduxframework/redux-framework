<?php
/**
 * Redux Google Maps Field Class
 *
 * @package Redux Pro
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Google_Maps
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Google_Maps' ) ) {

	/**
	 * Main ReduxFramework_google_maps class
	 *
	 * @since       1.0.0
	 */
	class Redux_Google_Maps extends Redux_Field {

		private $api_key = '';

		/**
		 * Get field defaults.
		 */
		public function set_defaults() {
			$field = array(
				'api_key'     => '',
				'map_version' => 'weekly',
			);

			$this->field = wp_parse_args( $this->field, $field );

			$this->api_key = null;

			$this->field['api_key'] = $this->field['api_key'] ?? '';

			if ( empty( $this->field['api_key'] ) ) {
				$redux         = get_option( $this->parent->args['opt_name'] );
				$this->api_key = $redux['google_map_api_key'] ?? '';
			} else {
				$this->api_key = $this->field['api_key'];
			}

			// Necessary, in case the user doesn't fill out a default array.
			$def_street_number = $this->field['default']['street_number'] ?? '';
			$def_route         = $this->field['default']['route'] ?? '';
			$def_locality      = $this->field['default']['locality'] ?? '';
			$def_state         = $this->field['default']['administrative_area_level_1'] ?? '';
			$def_postal        = $this->field['default']['postal_code'] ?? '';
			$def_country       = $this->field['default']['country'] ?? '';
			$def_lat           = $this->field['default']['latitude'] ?? '';
			$def_long          = $this->field['default']['longitude'] ?? '';
			$def_marker_info   = $this->field['default']['marker_info'] ?? '';
			$def_zoom          = $this->field['default']['zoom'] ?? '';

			$defaults = array(
				'latitude'                    => $def_lat,
				'longitude'                   => $def_long,
				'street_number'               => $def_street_number,
				'route'                       => $def_route,
				'locality'                    => $def_locality,
				'administrative_area_level_1' => $def_state,    // <-dickheads at google. lol, srsly...wtf?
				// level_1 huh? maybe It's for multiple planets one day.
				'postal_code'                 => $def_postal,
				'country'                     => $def_country,
				'marker_info'                 => $def_marker_info,
				'zoom'                        => $def_zoom,
			);

			$this->value = wp_parse_args( $this->value, $defaults );
		}


		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function render() {

			// Set default or saved zoom
			// USA: 39.11676722061108,-100.47761000000003
			// Zoom far: 3
			// zoom close 17.
			if ( empty( $this->value['zoom'] ) ) {
				if ( $this->value['street_number'] ) {
					$the_zoom = 17; // make it close if the street is included.
				} else {
					$the_zoom = 3; // make it far if it's not.
				}
			} else {
				$the_zoom = $this->value['zoom'];
			}

			// Make full address.
			$locality = ! empty( $this->value['locality'] ) ? $this->value['locality'] . ', ' : '';
			$route    = ! empty( $this->value['route'] ) ? $this->value['route'] . ', ' : '';
			$country  = ! empty( $this->value['country'] ) ? ', ' . $this->value['country'] : '';

			$full      = $this->value['street_number'] . ' ' . $route . ' ' . $locality . $this->value['administrative_area_level_1'] . ' ' . $this->value['postal_code'] . $country;
			$data_full = rawurlencode( $full );

			// Hide/show various input fields.
			$show_address     = $this->field['show_address'] ?? true;
			$show_city        = $this->field['show_city'] ?? true;
			$show_state       = $this->field['show_state'] ?? true;
			$show_postal      = $this->field['show_postal'] ?? true;
			$show_country     = $this->field['show_country'] ?? true;
			$show_lat         = $this->field['show_latitude'] ?? true;
			$show_long        = $this->field['show_longitude'] ?? true;
			$show_marker_info = $this->field['show_marker_info'] ?? true;
			$show_controls    = $this->field['show_controls'] ?? true;

			$this->field['placeholder']         = $this->field['placeholder'] ?? esc_html__( 'Enter your address', 'redux-framework' );
			$this->field['marker_tooltip']      = $this->field['marker_tooltip'] ?? esc_html__( 'Left mouse down on top of me to move me!', 'redux-framework' );
			$this->field['no_geometry_alert']   = $this->field['no_geometry_alert'] ?? esc_html__( 'The returned place contains no geometric data.', 'redux-framework' );
			$this->field['delay_render']        = $this->field['delay_render'] ?? false;
			$this->field['class']               = $this->field['class'] ?? '';
			$this->field['show_api_key']        = $this->field['show_api_key'] ?? true;
			$this->field['street_view_control'] = $this->field['street_view_control'] ?? true;
			$this->field['map_type_control']    = $this->field['map_type_control'] ?? true;
			$this->field['scroll_wheel']        = $this->field['scroll_wheel'] ?? false;
			$this->field['map_height']          = $this->field['map_height'] ?? '';

			$map_height = '';

			if ( ! empty( $this->field['map_height'] ) ) {
				$map_height = 'style="height:' . esc_attr( $this->field['map_height'] ) . ';"';
			}

			$geo_alert = rawurlencode( $this->field['no_geometry_alert'] );

			// admin defined.
			$the_lat        = $this->value['latitude'];
			$the_long       = $this->value['longitude'];
			$marker_tooltip = rawurlencode( $this->field['marker_tooltip'] );

			if ( ! empty( $the_lat ) && ! empty( $the_long ) ) {
				$full = '';
			}

			$hidden_style = ' style="display: none!important;" ';
			?>
			<div
				class="redux_framework_google_maps <?php echo esc_attr( $this->field['class'] ); ?>"
				id="<?php echo esc_attr( $this->field['id'] ); ?>"
				data-idx=""
				data-delay-render="<?php echo esc_attr( $this->field['delay_render'] ); ?>"
				data-scroll-wheel="<?php echo esc_attr( $this->field['scroll_wheel'] ); ?>"
				data-street-view="<?php echo esc_attr( $this->field['street_view_control'] ); ?>"
				data-map-type="<?php echo esc_attr( $this->field['map_type_control'] ); ?>"
				data-marker-tooltip="<?php echo esc_attr( $marker_tooltip ); ?>"
				data-geo-alert="<?php echo $geo_alert; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
				data-address="<?php echo $data_full; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">

				<input
					type="hidden"
					class="google_m_zoom_input"
					name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>[zoom]"
					value="<?php echo esc_attr( $the_zoom ); ?>"
				/>

				<?php $is_hidden = $show_controls ? '' : 'hidden'; ?>
				<label for="<?php echo esc_attr( $this->field['id'] ); ?>_autocomplete"></label>
				<input
					id="<?php echo esc_attr( $this->field['id'] ); ?>_autocomplete"
					class="google_m_controls google_m_autocomplete <?php echo esc_attr( $is_hidden ); ?>" type="text"
					value="<?php echo esc_attr( trim( $full ) ); ?>"
					placeholder="<?php echo esc_attr( $this->field['placeholder'] ); ?>"/>

				<div
					id="<?php echo esc_attr( $this->field['id'] ); ?>_type_selector"
					class="google_m_controls <?php echo esc_attr( $is_hidden ); ?>">

					<input class="noUpdate" type="radio" name="type" id="changetype-all-<?php echo esc_attr( $this->field['id'] ); ?>" checked="checked"/>
					<label for="changetype-all-<?php echo esc_attr( $this->field['id'] ); ?>"><?php esc_html_e( 'All', 'redux-framework' ); ?></label>

					<input class="noUpdate" type="radio" name="type" id="changetype-establishment-<?php echo esc_attr( $this->field['id'] ); ?>"/>
					<label for="changetype-establishment-<?php echo esc_attr( $this->field['id'] ); ?>"><?php esc_html_e( 'Place', 'redux-framework' ); ?></label>

					<input class="noUpdate" type="radio" name="type" id="changetype-address-<?php echo esc_attr( $this->field['id'] ); ?>"/>
					<label for="changetype-address-<?php echo esc_attr( $this->field['id'] ); ?>"><?php esc_html_e( 'Address', 'redux-framework' ); ?></label>

					<input class="noUpdate" type="radio" name="type" id="changetype-geocode-<?php echo esc_attr( $this->field['id'] ); ?>"/>
					<label for="changetype-geocode-<?php echo esc_attr( $this->field['id'] ); ?>"><?php esc_html_e( 'Geo', 'redux-framework' ); ?></label>
				</div>

				<div
					id="<?php echo esc_attr( $this->field['id'] ); ?>_map_canvas"
					class="google_m_canvas"
					data-default-long="<?php echo esc_attr( $the_long ); ?>"
					data-default-lat="<?php echo esc_attr( $the_lat ); ?>"
					data-default-zoom="<?php echo esc_attr( $the_zoom ); ?>"
					<?php echo $map_height; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				</div>

				<div class="google_maps_address_results">
					<?php $is_hidden = $show_address ? '' : $hidden_style; ?>
					<div class="input_wrapper street-address" <?php echo $is_hidden; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<label for="<?php echo esc_attr( $this->field['id'] ); ?>_street_number"><?php esc_html_e( 'Address', 'redux-framework' ); ?></label>
						<input
								data-id="<?php echo esc_attr( $this->field['id'] ); ?>"
								id="<?php echo esc_attr( $this->field['id'] ); ?>_street_number"
								name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>[street_number]"
								value="<?php echo esc_attr( $this->value['street_number'] ); ?>"
								class="slimField field"
								data-default-value="<?php echo esc_attr( $this->value['street_number'] ); ?>"
								type="text"
						/>
					</div>
					<div class="input_wrapper route" <?php echo $is_hidden; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<label for="<?php echo esc_attr( $this->field['id'] ); ?>_route"><?php esc_html_e( 'Street', 'redux-framework' ); ?></label>
						<input
								data-id="<?php echo esc_attr( $this->field['id'] ); ?>"
								id="<?php echo esc_attr( $this->field['id'] ); ?>_route"
								name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>[route]"
								value="<?php echo esc_attr( $this->value['route'] ); ?>"
								class="wideField field"
								data-default-value="<?php echo esc_attr( $this->value['route'] ); ?>"
								type="text"
						/>
					</div>

					<?php $is_hidden = $show_city ? '' : $hidden_style; ?>
					<div class="input_wrapper city" <?php echo( $is_hidden ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<label for="<?php echo esc_attr( $this->field['id'] ); ?>_locality"><?php esc_html_e( 'City', 'redux-framework' ); ?></label>
						<input
								data-id="<?php echo esc_attr( $this->field['id'] ); ?>"
								id="<?php echo esc_attr( $this->field['id'] ); ?>_locality"
								name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>[locality]"
								value="<?php echo esc_attr( $this->value['locality'] ); ?>"
								class="wideField field"
								data-default-value="<?php echo esc_attr( $this->value['locality'] ); ?>"
								type="text"
						/>
					</div>

					<?php $is_hidden = $show_state ? '' : $hidden_style; ?>
					<div class="input_wrapper state" <?php echo( $is_hidden ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<label for="<?php echo esc_attr( $this->field['id'] ); ?>_administrative_area_level_1"><?php esc_html_e( 'State', 'redux-framework' ); ?></label>
						<input
								data-id="<?php echo esc_attr( $this->field['id'] ); ?>"
								id="<?php echo esc_attr( $this->field['id'] ); ?>_administrative_area_level_1"
								name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>[administrative_area_level_1]"
								value="<?php echo esc_attr( $this->value['administrative_area_level_1'] ); ?>"
								class="slimField field"
								data-default-value="<?php echo esc_attr( $this->value['administrative_area_level_1'] ); ?>"
								type="text"
						/>
					</div>

					<?php $is_hidden = $show_postal ? '' : $hidden_style; ?>
					<div class="input_wrapper zip-code" <?php echo( $is_hidden ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<label for="<?php echo esc_attr( $this->field['id'] ); ?>_postal_code"><?php esc_html_e( 'ZIP Code', 'redux-framework' ); ?></label>
						<input
								data-id="<?php echo esc_attr( $this->field['id'] ); ?>"
								id="<?php echo esc_attr( $this->field['id'] ); ?>_postal_code"
								name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>[postal_code]"
								value="<?php echo esc_attr( $this->value['postal_code'] ); ?>"
								class="slimField field"
								data-default-value="<?php echo esc_attr( $this->value['postal_code'] ); ?>"
								type="text"
						/>
					</div>

					<?php $is_hidden = $show_country ? '' : $hidden_style; ?>
					<div class="input_wrapper country" <?php echo( $is_hidden ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<label for="<?php echo esc_attr( $this->field['id'] ); ?>_country"><?php esc_html_e( 'Country', 'redux-framework' ); ?></label>
						<input
								data-id="<?php echo esc_attr( $this->field['id'] ); ?>"
								id="<?php echo esc_attr( $this->field['id'] ); ?>_country"
								name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>[country]"
								value="<?php echo esc_attr( $this->value['country'] ); ?>"
								class="wideField field"
								data-default-value="<?php echo esc_attr( $this->value['country'] ); ?>"
								type="text"
						/>
					</div>

					<?php $is_hidden = $show_lat ? '' : $hidden_style; ?>
					<div class="input_wrapper latitude" <?php echo( $is_hidden ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<label for="<?php echo esc_attr( $this->field['id'] ); ?>_latitude"><?php esc_html_e( 'Latitude', 'redux-framework' ); ?></label>
						<input
								data-id="<?php echo esc_attr( $this->field['id'] ); ?>"
								id="<?php echo esc_attr( $this->field['id'] ); ?>_latitude"
								name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>[latitude]"
								value="<?php echo esc_attr( $this->value['latitude'] ); ?>"
								class="wideField field"
								data-default-value="<?php echo esc_attr( $this->value['latitude'] ); ?>"
								type="text"
						/>
					</div>

					<?php $is_hidden = $show_long ? '' : $hidden_style; ?>
					<div class="input_wrapper longitude" <?php echo( $is_hidden ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<label for="<?php echo esc_attr( $this->field['id'] ); ?>_longitude"><?php esc_html_e( 'Longitude', 'redux-framework' ); ?></label>
						<input
								data-id="<?php echo esc_attr( $this->field['id'] ); ?>"
								id="<?php echo esc_attr( $this->field['id'] ); ?>_longitude"
								name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>[longitude]"
								value="<?php echo esc_attr( $this->value['longitude'] ); ?>"
								class="wideField field"
								data-default-value="<?php echo esc_attr( $this->value['longitude'] ); ?>"
								type="text"
						/>
					</div>

					<?php $is_hidden = $show_marker_info ? '' : $hidden_style; ?>
					<div class="input_wrapper marker-info" <?php echo( $is_hidden ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<label for="<?php echo esc_attr( $this->field['id'] ); ?>_marker_info"><?php esc_html_e( 'Marker Info', 'redux-framework' ); ?></label>
						<textarea
								data-id="<?php echo esc_attr( $this->field['id'] ); ?>"
								id="<?php echo esc_attr( $this->field['id'] ); ?>_marker_info"
								name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>[marker_info]"
								class="field"
								data-default-value="<?php echo esc_attr( $this->value['marker_info'] ); ?>"
								rows="3"
								type="text"><?php echo esc_textarea( $this->value['marker_info'] ); ?></textarea>
					</div>
				</div>
				<?php if ( $this->field['show_api_key'] ) { ?>
					<div class="input google_m_api_key">
						<a href="javascript:void(0);" class="button button-secondary google_m_api_key_button"><?php esc_html_e( 'Google Map API Key', 'redux-framework' ); ?></a>
						<div class="google_m_api_key_wrapper">
							<p class="description" id="google_m_api_key_description">
								<?php
								$api_key_site     = ' ' . sprintf( '<a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank">%s</a>', esc_html__( 'Get an API Key', 'redux-framework' ) ) . ' ';
								$usage_limit_site = ' ' . sprintf( '<a href="https://developers.google.com/maps/documentation/javascript/usage" target="_blank">%s</a>', esc_html__( 'Google Map Usage Limits', 'redux-framework' ) ) . ' ';

								// translators: %1$s: Google Maps API Key url. %2$s: Google Maps Usage URL.
								echo sprintf( esc_html__( 'Google Maps supports 25,000 free map loads per 24 hours for 90 consecutive days.  In the events you run a high volume site, you may need to obtain an API Key to continue using Google Map output beyond the free quota.  To sign up for an API Key, please visit the %1$s site.  For more information about Google Map usage limits, please visit the %2$s guide.', 'redux-framework' ), $api_key_site, $usage_limit_site ) . '<br><br>' . esc_html__( 'Once you have obtained an API Key, please enter it in the text box below and save the options panel.', 'redux-framework' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</p>
							<label for="google_m_api_key_input"><?php esc_html_e( 'API Key', 'redux-framework' ); ?></label>
							<input
								type="text"
								value="<?php echo esc_attr( $this->api_key ); ?>"
								name="<?php echo esc_attr( $this->parent->args['opt_name'] ); ?>[google_map_api_key]"
								id="google_m_api_key_input" class="large-text"/>
						</div>
					</div>
				<?php } ?>
			</div>

			<?php
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function enqueue() {
			$min = Redux_Functions::isMin();

			$api_key = '';
			if ( ! empty( $this->api_key ) ) {
				//$api_key = '&key=' . $this->api_key;
				$api_key = $this->api_key;
			}

			//wp_enqueue_script(
			//	'redux-field-google_maps-api',
			//	'//maps.googleapis.com/maps/api/js?v=' . $this->field['map_version'] . $api_key . '&libraries=places&callback=initMap',
			//	array( 'jquery' ),
			//	$this->field['map_version'],
			//	true
			//);

			wp_register_script(
				'redux-field-google_maps-js',
				$this->url . 'redux-google-maps' . $min . '.js',
				array( 'jquery', 'redux-js' ),
				Redux_Extension_Google_Maps::$version,
				true
			);

			if ( ! wp_script_is('redux-field-google_maps-js','enqueued') ) {
				$script = '(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
					key:"' . $api_key . '",
					v:"' . $this->field['map_version'] . '",
					libraries:"places",
					callback:"initMap"
				});';

				wp_add_inline_script( 'redux-field-google_maps-js', $script );
			}

			wp_enqueue_script( 'redux-field-google_maps-js' );

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-google_maps',
					$this->url . 'redux-google-maps.css',
					array(),
					time()
				);
			}
		}
	}
}
