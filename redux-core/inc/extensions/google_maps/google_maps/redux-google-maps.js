// noinspection JSUnresolvedReference

/**
 * Field Google Map
 */

/* global jQuery, document, redux_change, redux, google */

(function ( $ ) {
	'use strict';

	let g_map;
	let g_marker;
	let g_autoComplete;
	let g_advancedMarker;
	let g_LatLng;

	redux.field_objects             = redux.field_objects || {};
	redux.field_objects.google_maps = redux.field_objects.google_maps || {};

	/* LIBRARY INIT */
	redux.field_objects.google_maps.init = function ( selector ) {
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-google_maps:visible' );
		}

		$( selector ).each(
			function ( i ) {
				let containerID;
				let delayRender;

				const el   = $( this );
				let parent = el;

				if ( ! el.hasClass( 'redux-field-container' ) ) {
					parent = el.parents( '.redux-field-container:first' );
				}

				if ( parent.is( ':hidden' ) ) {
					return;
				}

				if ( parent.hasClass( 'redux-field-init' ) ) {
					parent.removeClass( 'redux-field-init' );
				} else {
					return;
				}

				// Get container ID.
				containerID = el.find( '.redux_framework_google_maps' ).attr( 'id' );

				// Check for delay render, which is useful for calling a map
				// render after JavaScript load.
				delayRender = Boolean( el.find( '.redux_framework_google_maps' ).data( 'delay-render' ) );

				// API Key button.
				redux.field_objects.google_maps.clickHandler( el );

				// Init our maps.
				redux.field_objects.google_maps.initMap( el, i, containerID, delayRender );

				// Fucking radio button won't check on its own, for some reason.
				setTimeout(
					function () {
						$( '#changetype-all' ).prop( 'checked', true );
					},
					1
				);
			}
		);
	};

	/* API BUTTON CLICK HANDLER */
	redux.field_objects.google_maps.clickHandler = function ( el ) {

		// Find the API Key button and react on click.
		el.find( '.google_m_api_key_button' ).on(
			'click',
			function () {

				// Find message wrapper.
				const wrapper = el.find( '.google_m_api_key_wrapper' );

				if ( wrapper.is( ':visible' ) ) {

					// If the wrapper is visible, close it.
					wrapper.slideUp(
						'fast',
						function () {
							el.find( '#google_m_api_key_input' ).trigger( 'focus' );
						}
					);
				} else {

					// If the wrapper is visible, open it.
					wrapper.slideDown(
						'medium',
						function () {
							el.find( '#google_m_api_key_input' ).trigger( 'focus' );
						}
					);
				}
			}
		);

		// Auto select autocomplete contents,
		// since Google doesn't do this inherently.
		el.find( '.google_m_autocomplete' ).on(
			'click',
			function ( e ) {
				this.trigger( 'focus' );
				this.trigger( 'select' );
				e.preventDefault();
			}
		);
	};

	/* MAP RENDER FUNCTION */
	redux.field_objects.google_maps.renderMap = async function ( el, mapClass ) {
		let scrollWheel;
		let streetView;
		let mapType;
		let address;
		let defLat;
		let defLong;
		let defaultZoom;
		let mapOptions;
		let geocoder;

		let noLatLng      = false;
		const containerID = el.find( '.redux_framework_google_maps' ).attr( 'id' );

		// Set IDs to variables.
		const autocomplete = containerID + '_autocomplete';
		const canvas       = containerID + '_map_canvas';
		const canvasId     = $( '#' + canvas );

		// Create the autocomplete object, restricting the search
		// to geographical location types.
		g_autoComplete   = await google.maps.importLibrary( 'places' );
		g_advancedMarker = await google.maps.importLibrary( 'marker' );

		g_autoComplete = new google.maps.places.Autocomplete( document.getElementById( autocomplete ), {types: ['geocode']} );

		// Data bindings.
		scrollWheel = Boolean( mapClass.data( 'scroll-wheel' ) );
		streetView  = Boolean( mapClass.data( 'street-view' ) );
		mapType     = Boolean( mapClass.data( 'map-type' ) );

		address = mapClass.data( 'address' );
		address = decodeURIComponent( address );
		address = address.trim();

		// Set default Lat/lng.
		defLat      = canvasId.data( 'default-lat' );
		defLong     = canvasId.data( 'default-long' );
		defaultZoom = canvasId.data( 'default-zoom' );

		// Eval whether to set maps based on lat/lng or address.
		if ( '' !== address ) {
			if ( '' === defLat || '' === defLong ) {
				noLatLng = true;
			}
		} else {
			noLatLng = false;
		}

		// Can't have empty values, or the map API will complain.
		// Set default for the middle of the United States.
		defLat  = defLat ? defLat : 39.11676722061108;
		defLong = defLong ? defLong : - 100.47761000000003;

		if ( noLatLng ) {

			// If displaying a map based on an address.
			geocoder = new google.maps.Geocoder();

			// Set up Geocode and pass address.
			geocoder.geocode(
				{ 'address': address },
				function ( results, status ) {
					let latitude;
					let longitude;

					// Function results.
					if ( status === google.maps.GeocoderStatus.OK ) {

						// A good address was passed.
						g_LatLng = results[0].geometry.location;

						// Set map options.
						mapOptions = {
							center: g_LatLng,
							zoom: defaultZoom,
							streetViewControl: streetView,
							mapTypeControl: mapType,
							scrollwheel: scrollWheel,
							mapTypeControlOptions: {
								style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
								position: google.maps.ControlPosition.LEFT_BOTTOM
							},
							mapId:'REDUX_GOOGLE_MAPS',
						};

						// Create map.
						g_map = new google.maps.Map( document.getElementById( canvas ), mapOptions );

						// Render map controls.
						redux.field_objects.google_maps.renderControls( el, autocomplete, mapClass );

						// Get and set lat/long data.
						latitude = el.find( '#' + containerID + '_latitude' );
						latitude.val( results[0].geometry.location.lat() );

						longitude = el.find( '#' + containerID + '_longitude' );
						longitude.val( results[0].geometry.location.lng() );
					} else {

						// No data found, alert the user.
						alert( 'Geocode was not successful for the following reason: ' + status );
					}
				}
			);
		} else {

			// If displaying map based on an lat/lng.
			g_LatLng = new google.maps.LatLng( defLat, defLong );

			// Set map options.
			mapOptions = {
				center: g_LatLng,
				zoom: defaultZoom, // Start off far unless an item is selected, set by php.
				streetViewControl: streetView,
				mapTypeControl: mapType,
				scrollwheel: scrollWheel,
				mapTypeControlOptions: {
					style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
					position: google.maps.ControlPosition.LEFT_BOTTOM
				},
				mapId:'REDUX_GOOGLE_MAPS',
			};

			// Create the map.
			g_map = new google.maps.Map( document.getElementById( canvas ), mapOptions );

			// Render map controls.
			redux.field_objects.google_maps.renderControls( el, autocomplete, mapClass );
		}
	};

	/* INIT MAP FUNCTION */
	redux.field_objects.google_maps.initMap = function ( el, idx, containerID, delayRender ) {
		let delayed;

		// Pull the map class.
		const mapClass = el.find( '.redux_framework_google_maps' );

		// Add map index to data attr.  Why, say we want to use delay_render,
		// and want to init the map later on.  You'd need the index number in the
		// event of multiple map instances.  This allows one to retrieve it
		// later.
		$( mapClass ).attr( 'data-idx', idx );
		if ( true === delayRender ) {
			return;
		}

		// Map has been rendered, no need to process again.
		if ( $( '#' + containerID ).hasClass( 'rendered' ) ) {
			return;
		}

		// If a map is set to delay render and has been initiated
		// from another scrip, add the 'render' class so rendering
		// does not occur.
		// It messes things up.
		delayed = Boolean( mapClass.data( 'delay-render' ) );
		if ( true === delayed ) {
			mapClass.addClass( 'rendered' );
		}

		// Render the map.
		redux.field_objects.google_maps.renderMap( el, mapClass );
	};

	/* RENDER CONTROLS FUNCTION */
	redux.field_objects.google_maps.renderControls = function ( el, autoComplete, mapClass ) {
		let markerTooltip;
		let infoWindow;

		// Set variables.
		const containerID = el.find( '.redux_framework_google_maps' ).attr( 'id' );
		const controls    = containerID + '_type_selector';

		// Get HTML.
		const input = document.getElementById( autoComplete );
		const types = document.getElementById( controls );

		// Set objects into the map.
		g_map.controls[google.maps.ControlPosition.TOP_LEFT].push( input );
		g_map.controls[google.maps.ControlPosition.TOP_LEFT].push( types );

		// Bind objects to the map.
		g_autoComplete = new google.maps.places.Autocomplete( input );
		g_autoComplete.bindTo( 'bounds', g_map );

		// Get the marker tooltip data.
		markerTooltip = mapClass.data( 'marker-tooltip' );
		markerTooltip = decodeURIComponent( markerTooltip );

		// Create infoWindow.
		infoWindow = new google.maps.InfoWindow();

		// Create marker.
		g_marker = new google.maps.marker.AdvancedMarkerElement(
			{
				position: g_LatLng,
				map: g_map,
				//anchorPoint: new google.maps.Point( 0, - 29 ),
				//draggable: true,
				title: markerTooltip,
				gmpClickable: true,
				gmpDraggable: true,
				//animation: google.maps.Animation.DROP

			}
		);

		// Add Event Listeners.
		redux.field_objects.google_maps.addListeners( el, mapClass, g_marker, infoWindow );
	};

	/* ADD LISTENERS FUNCTION */
	redux.field_objects.google_maps.addListeners = function ( el, mapClass, marker ) {
		let infoWindow;

		// Set variables.
		const containerID = el.find( '.redux_framework_google_maps' ).attr( 'id' );
		const latitude    = containerID + '_latitude';
		const longitude   = containerID + '_longitude';
		const marker_info = containerID + '_marker_info';
		let geoAlert      = mapClass.data( 'geo-alert' );

		geoAlert = decodeURIComponent( geoAlert );

		// Place change.
		google.maps.event.addListener(
			g_autoComplete,
			'place_changed',
			function () {
				let place;
				let address;

				infoWindow.close();
				marker.setVisible( false );

				// Get place data.
				place = g_autoComplete.getPlace();

				// Display alert if something went wrong.
				if ( ! place.geometry ) {
					window.alert( geoAlert );
					return;
				}

				// If the place has a geometry, then present it on a map.
				if ( place.geometry.viewport ) {
					g_map.fitBounds( place.geometry.viewport );
				} else {
					g_map.setCenter( place.geometry.location );
					g_map.setZoom( 17 ); // Why 17? Because it looks good.
				}

				// Set the marker icon.
				marker.setIcon(
					({
						url: place.icon,
						size: new google.maps.Size( 71, 71 ),
						origin: new google.maps.Point( 0, 0 ),
						anchor: new google.maps.Point( 17, 34 ),
						scaledSize: new google.maps.Size( 35, 35 )
					})
				);

				// Set marker position and display.
				marker.setPosition( place.geometry.location );
				marker.setVisible( true );

				// Form array of address components.
				address = '';
				if ( place.address_components ) {
					address = [( place.address_components[0] && place.address_components[0].short_name || '' ),
						( place.address_components[1] && place.address_components[1].short_name || '' ),
						( place.address_components[2] && place.address_components[2].short_name || '' )].join( ' ' );
				}

				// Set the default marker info window with address data.
				infoWindow.setContent( '<div><strong>' + place.name + '</strong><br>' + address );
				infoWindow.open( g_map, marker );

				// Run Geolocation.
				redux.field_objects.google_maps.geoLocate();

				// Fill in address inputs.
				redux.field_objects.google_maps.fillInAddress( el, latitude, longitude );
			}
		);

		// Search radio buttons.
		redux.field_objects.google_maps.setupClickListener( 'changetype-all-' + containerID, [] );
		redux.field_objects.google_maps.setupClickListener( 'changetype-address-' + containerID, ['address'] );
		redux.field_objects.google_maps.setupClickListener( 'changetype-establishment-' + containerID, ['establishment'] );
		redux.field_objects.google_maps.setupClickListener( 'changetype-geocode-' + containerID, ['geocode'] );

		// Marker drag.
		google.maps.event.addListener(
			marker,
			'drag',
			function ( event ) {
				document.getElementById( latitude ).value  = event.latLng.lat();
				document.getElementById( longitude ).value = event.latLng.lng();
			}
		);

		// End marker drag.
		google.maps.event.addListener(
			marker,
			'dragend',
			function () {
				redux_change( el.find( '.redux_framework_google_maps' ) );
			}
		);

		// Zoom Changed.
		g_map.addListener(
			'zoom_changed',
			function () {
				el.find( '.google_m_zoom_input' ).val( g_map.getZoom() );
			}
		);

		// Marker Info Window.
		infoWindow = new google.maps.InfoWindow();

		google.maps.event.addListener(
			marker,
			'click',
			function () {
				const infoValue = document.getElementById( marker_info ).value;

				if ( '' !== infoValue ) {
					infoWindow.setContent( infoValue );
					infoWindow.open( g_map, marker );
				}
			}
		);
	};

	/* FILL IN ADDRESS FUNCTION */
	redux.field_objects.google_maps.fillInAddress = function ( el, latitude, longitude ) {

		// Set variables.
		const containerID = el.find( '.redux_framework_google_maps' ).attr( 'id' );

		// What if someone only wants city, or state, ect...
		// gotta do it this way to check for the address!
		// Need to check each of the returned components to see what is returned.
		const componentForm = {
			street_number: 'short_name',
			route: 'long_name',
			locality: 'long_name',
			administrative_area_level_1: 'short_name',
			country: 'long_name',
			postal_code: 'short_name'
		};

		// Get the place details from the autocomplete object.
		const place = g_autoComplete.getPlace();

		let component;
		let i;
		let addressType;
		let _d_addressType;
		let val;
		let len;

		document.getElementById( latitude ).value  = place.geometry.location.lat();
		document.getElementById( longitude ).value = place.geometry.location.lng();

		for ( component in componentForm ) {
			if ( componentForm.hasOwnProperty( component ) ) {

				// Push in the dynamic form element ID again.
				component = containerID + '_' + component;

				// Assign to proper place.
				document.getElementById( component ).value    = '';
				document.getElementById( component ).disabled = false;
			}
		}

		// Get each component of the address from the place details
		// and fill the corresponding field on the form.
		len = place.address_components.length;

		for ( i = 0; i < len; i += 1 ) {
			addressType = place.address_components[i].types[0];

			if ( componentForm[addressType] ) {

				// Push in the dynamic form element ID again.
				_d_addressType = containerID + '_' + addressType;

				// Get the original.
				val = place.address_components[i][componentForm[addressType]];

				// Assign to proper place.
				document.getElementById( _d_addressType ).value = val;
			}
		}
	};

	redux.field_objects.google_maps.setupClickListener = function ( id, types ) {
		const radioButton = document.getElementById( id );

		google.maps.event.addListener(
			radioButton,
			'click',
			function () {
				g_autoComplete.setTypes( types );
			}
		);
	};

	redux.field_objects.google_maps.geoLocate = function () {
		if ( navigator.geolocation ) {
			navigator.geolocation.getCurrentPosition(
				function ( position ) {
					const geolocation = new google.maps.LatLng( position.coords.latitude, position.coords.longitude );

					const circle = new google.maps.Circle(
						{
							center: geolocation,
							radius: position.coords.accuracy
						}
					);

					g_autoComplete.setBounds( circle.getBounds() );
				}
			);
		}
	};
} )( jQuery );
