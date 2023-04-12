/**
 * Redux Users
 * Dependencies      : jquery
 * Created by        : Dovy Paukstys
 * Date              : 19 Feb. 2016
 */

/* global redux */

jQuery(
	function( $ ) {
		'use strict';

		$.reduxUsers = $.reduxUsers || {};

		$( document ).ready(
			function() {
				$.reduxUsers.init();
			}
		);

		$.reduxUsers.init = function() {
			var reduxObject;
			var optName = $( '.redux-ajax-security' ).data( 'opt-name' );

			if ( undefined === optName ) {
				reduxObject = redux.optName;
			} else {
				reduxObject = redux;
			}

			$.reduxUsers.notLoaded = true;
			$.redux.initFields();

			reduxObject.args.ajax_save         = 0;
			reduxObject.args.disable_save_warn = true;
		};

		// Check for successful element added since WP ajax doesn't have a callback.
		$.reduxUsers.editCount = $( '#the-list tr' );

		$.reduxUsers.editCheck = function() {
			var len;

			if ( $( '#ajax-response .error' ).length ) {
				return false;
			}

			len = $( '#the-list tr' ).length;

			if ( len > $.reduxUsers.editCount ) {
				window.location.reload();
				return false;
			}

			setTimeout( $.reduxUsers.editCheck, 100 );

			$.reduxUsers.editCount = len;
		};

		$( '#submit' ).on(
			'click',
			function() {
				window.onbeforeunload = null;

				$.reduxUsers.editCount = $( '#the-list tr' ).length;

				$( document ).ajaxSuccess(
					function() {
						$.reduxUsers.editCheck();
					}
				);
			}
		);
	}
);
