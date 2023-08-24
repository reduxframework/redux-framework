/**
 * Redux Taxonomy
 * Dependencies: jquery
 * Created by: Dovy Paukstys
 * Date: 19 Feb. 2014
 */

/* global redux */

jQuery(
	function ( $ ) {
		'use strict';

		$.reduxTaxonomy = $.reduxTaxonomy || {};

		$( document ).ready(
			function () {
				$.reduxTaxonomy.init();
			}
		);

		$.reduxTaxonomy.init = function () {
			var reduxObject;
			var optName = $( '.redux-ajax-security' ).data( 'opt-name' );

			if ( undefined === optName ) {
				reduxObject = redux.optName;
			} else {
				reduxObject = redux;
			}

			$.reduxTaxonomy.notLoaded = true;
			$.redux.initFields();

			reduxObject.args.ajax_save         = 0;
			reduxObject.args.disable_save_warn = true;
		};

		// Check for a successful element added since WP ajax doesn't have a callback.
		$.reduxTaxonomy.editCount = $( '#the-list tr' );

		$.reduxTaxonomy.editCheck = function () {
			var tr;

			if ( $( '#ajax-response .error' ).length ) {
				return false;
			}

			tr = $( '#the-list tr' );

			if ( tr.length > $.reduxTaxonomy.editCount ) {
				window.location.reload();
				return false;
			}

			setTimeout( $.reduxTaxonomy.editCheck, 100 );

			$.reduxTaxonomy.editCount = tr.length;
		};

		$( '#submit' ).on(
			'click',
			function () {
				window.onbeforeunload = null;

				$.reduxTaxonomy.editCount = $( '#the-list tr' ).length;

				$( document ).ajaxSuccess(
					function () {
						$.reduxTaxonomy.editCheck();
					}
				);
			}
		);
	}
);
