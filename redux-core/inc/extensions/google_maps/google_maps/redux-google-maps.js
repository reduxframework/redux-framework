// noinspection JSUnresolvedReference

/**
 * Field Google Map
 */

/* global jQuery, document, redux_change, redux, google */

(function ( $ ) {
	'use strict';

	redux.field_objects             = redux.field_objects || {};
	redux.field_objects.google_maps = redux.field_objects.google_maps || {};

	/* LIBRARY INIT */
	redux.field_objects.google_maps.init = function ( selector ) {
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-google_maps:visible' );
		}

		$( selector ).each(
			function ( i ) {
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

				// Check for delay render, which is useful for calling a map
				// render after JavaScript load.
				delayRender = Boolean( el.find( '.redux_framework_google_maps' ).data( 'delay-render' ) );

				// API Key button.
				redux.field_objects.google_maps.clickHandler( el );

				// Init our maps.
				redux.field_objects.google_maps.initMap( el, i, delayRender );
			}
		);
	};

	/* INIT MAP FUNCTION */
	redux.field_objects.google_maps.initMap = async function ( el, idx, delayRender ) {
		let delayed;
		let scrollWheel;
		let streetView;
		let mapType;
		let address;
		let defLat;
		let defLong;
		let defaultZoom;
		let mapOptions;
		let geocoder;
		let g_autoComplete;
		let g_LatLng;
		let g_map;

		let noLatLng = false;

		// Pull the map class.
		const mapClass     = el.find( '.redux_framework_google_maps' );
		const containerID  = mapClass.attr( 'id' );
		const autocomplete = containerID + '_autocomplete';
		const canvas       = containerID + '_map_canvas';
		const canvasId     = $( '#' + canvas );

		const latitude    = containerID + '_latitude';
		const longitude   = containerID + '_longitude';

		// Add map index to data attr.
		// Why, say we want to use delay_render,
		// and want to init the map later on.
		// You'd need the index number in the
		// event of multiple map instances.
		// This allows one to retrieve it
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

		// Create the autocomplete object, restricting the search
		// to geographical location types.
		g_autoComplete = await google.maps.importLibrary( 'places' );

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
		defLong = defLong ? defLong : -100.47761000000003;

		if ( noLatLng ) {

			// If displaying a map based on an address.
			geocoder = new google.maps.Geocoder();

			// Set up Geocode and pass address.
			geocoder.geocode(
				{'address': address},
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
							mapId: 'REDUX_GOOGLE_MAPS',
						};

						// Create map.
						g_map = new google.maps.Map( document.getElementById( canvas ), mapOptions );

						// Get and set lat/long data.
						latitude = el.find( '#' + containerID + '_latitude' );
						latitude.val( results[0].geometry.location.lat() );

						longitude = el.find( '#' + containerID + '_longitude' );
						longitude.val( results[0].geometry.location.lng() );

						redux.field_objects.google_maps.renderControls( el, latitude, longitude, g_autoComplete, g_map, autocomplete, mapClass, g_LatLng, containerID );
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
				mapId: 'REDUX_GOOGLE_MAPS',
			};

			// Create the map.
			g_map = new google.maps.Map( document.getElementById( canvas ), mapOptions );

			redux.field_objects.google_maps.renderControls( el, latitude, longitude, g_autoComplete, g_map, autocomplete, mapClass, g_LatLng, containerID );
		}
	};

	redux.field_objects.google_maps.renderControls = function ( el, latitude, longitude, g_autoComplete, g_map, autocomplete, mapClass, g_LatLng, containerID ) {
		let markerTooltip;
		let infoWindow;
		let g_marker;

		let geoAlert = mapClass.data( 'geo-alert' );

		// Get HTML.
		const input = document.getElementById( autocomplete );

		// Set objects into the map.
		g_map.controls[google.maps.ControlPosition.TOP_LEFT].push( input );

		// Bind objects to the map.
		g_autoComplete = new google.maps.places.Autocomplete( input );
		g_autoComplete.bindTo( 'bounds', g_map );

		// Get the marker tooltip data.
		markerTooltip = mapClass.data( 'marker-tooltip' );
		markerTooltip = decodeURIComponent( markerTooltip );

		// Create infoWindow.
		infoWindow = new google.maps.InfoWindow();

		// Create marker.
		g_marker = new google.maps.Marker(
			{
				position: g_LatLng,
				map: g_map,
				anchorPoint: new google.maps.Point( 0, - 29 ),
				draggable: true,
				title: markerTooltip,
				animation: google.maps.Animation.DROP
			}
		);

		geoAlert = decodeURIComponent( geoAlert );

		// Place change.
		google.maps.event.addListener(
			g_autoComplete,
			'place_changed',
			function () {
				let place;
				let address;
				let markerTooltip;

				infoWindow.close();

				// Get place data.
				place = g_autoComplete.getPlace();

				// Display alert if something went wrong.
				if ( ! place.geometry ) {
					window.alert( geoAlert );
					return;
				}

				console.log( place.geometry.viewport );
				// If the place has a geometry, then present it on a map.
				if ( place.geometry.viewport ) {
					g_map.fitBounds( place.geometry.viewport );
				} else {
					g_map.setCenter( place.geometry.location );
					g_map.setZoom( 17 ); // Why 17? Because it looks good.
				}

				markerTooltip = mapClass.data( 'marker-tooltip' );
				markerTooltip = decodeURIComponent( markerTooltip );

				// Set the marker icon.
				g_marker = new google.maps.Marker(
					{
						position: g_LatLng,
						map: g_map,
						anchorPoint: new google.maps.Point( 0, - 29 ),
						title: markerTooltip,
						clickable: true,
						draggable: true,
						animation: google.maps.Animation.DROP
					}
				);

				// Set marker position and display.
				g_marker.setPosition( place.geometry.location );
				g_marker.setVisible( true );

				// Form array of address components.
				address = '';
				if ( place.address_components ) {
					address = [( place.address_components[0] && place.address_components[0].short_name || '' ),
						( place.address_components[1] && place.address_components[1].short_name || '' ),
						( place.address_components[2] && place.address_components[2].short_name || '' )].join( ' ' );
				}

				// Set the default marker info window with address data.
				infoWindow.setContent( '<div><strong>' + place.name + '</strong><br>' + address );
				infoWindow.open( g_map, g_marker );

				// Run Geolocation.
				redux.field_objects.google_maps.geoLocate( g_autoComplete );

				// Fill in address inputs.
				redux.field_objects.google_maps.fillInAddress( el, latitude, longitude, g_autoComplete );
			}
		);

		// Marker drag.
		google.maps.event.addListener(
			g_marker,
			'drag',
			function ( event ) {
				document.getElementById( latitude ).value  = event.latLng.lat();
				document.getElementById( longitude ).value = event.latLng.lng();
			}
		);

		// End marker drag.
		google.maps.event.addListener(
			g_marker,
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
			g_marker,
			'click',
			function () {
				const marker_info = containerID + '_marker_info';
				const infoValue = document.getElementById( marker_info ).value;

				if ( '' !== infoValue ) {
					infoWindow.setContent( infoValue );
					infoWindow.open( g_map, g_marker );
				}
			}
		);
	};

	/* FILL IN ADDRESS FUNCTION */
	redux.field_objects.google_maps.fillInAddress = function ( el, latitude, longitude, g_autoComplete ) {

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

	redux.field_objects.google_maps.geoLocate = function ( g_autoComplete ) {
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

		el.find( '.google_m_autocomplete' ).on(
			'keypress',
			function ( e ) {
				if ( 13 === e.keyCode ) {
					e.preventDefault();
				}
			}
		);

		// Auto select autocomplete contents,
		// since Google doesn't do this inherently.
		el.find( '.google_m_autocomplete' ).on(
			'click',
			function ( e ) {
				$( this ).trigger( 'focus' );
				$( this ).trigger( 'select' );
				e.preventDefault();
			}
		);
	};

} )( jQuery );
