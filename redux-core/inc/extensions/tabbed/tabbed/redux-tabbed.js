/*global redux*/

/**
 * Tabbed
 * Dependencies     : jquery
 * Feature added by : Kevin Provance (kprovance)
 * Date             : 09.18.2023
 */

(function ( $ ) {
	'use strict';

	var reduxObject;

	redux.field_objects        = redux.field_objects || {};
	redux.field_objects.tabbed = redux.field_objects.tabbed || {};

	redux.field_objects.tabbed.getOptName = function ( el ) {
		var optName;

		optName = el.parents().find( '.redux-ajax-security' ).data( 'opt-name' );

		if ( undefined === optName ) {
			optName = el.parents( '.redux-container' ).data( 'opt-name' );
		}

		if ( undefined === optName ) {
			return redux;
		} else {
			return redux.optName;
		}
	};

	redux.field_objects.tabbed.init = function ( selector ) {
		selector = $.redux.getSelector( selector, 'tabbed' );

		$( selector ).each(
			function () {
				var el     = $( this );
				var parent = el;

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

				reduxObject = redux.field_objects.tabbed.getOptName( el );

				el.find( '.redux-tabbed' ).each(
					function () {
						var $this    = el;
						var links    = $this.find( '.redux-tabbed-nav a' );
						var contents = $this.find( '.redux-tabbed-content' );

						$.redux.initFields();

						links.on(
							'click',
							function ( e ) {
								e.preventDefault();

								var link    = $( this );
								var	index   = link.index();
								var content = contents.eq( index );

								link.addClass( 'redux-tabbed-active' ).siblings().removeClass( 'redux-tabbed-active' );
								content.removeClass( 'hidden' ).siblings().addClass( 'hidden' );

								$.redux.initFields();
							}
						);
					}
				);
			}
		);
	};

	redux.field_objects.tabbed.check_parents_dependencies = function ( id ) {
		var show = '';

		if ( reduxObject.required_child.hasOwnProperty( id ) ) {
			$.each(
				reduxObject.required_child[id],
				function ( i, parentData ) {
					var parentValue;
					var value;
					var idx;
					var x;

					i   = null;
					idx = $( '#' + reduxObject.args.opt_name + '-' + parentData.parent );

					if ( idx.hasClass( 'hide' ) ) {
						show = false;
						return false;
					} else {
						if ( false !== show ) {
							value = idx.serializeForm();

							if ( null !== value && 'object' === typeof value && value.hasOwnProperty( reduxObject.args.opt_name ) ) {
								if ( undefined === value[reduxObject.args.opt_name][parentData.parent] ) {
									x = Object.values( value[reduxObject.args.opt_name] )[0][parentData.parent];
								} else {
									x = value[reduxObject.args.opt_name][parentData.parent];
								}

								value = x;
							}

							if ( $( '#' + reduxObject.args.opt_name + '-' + id ).hasClass( 'redux-container-media' ) ) {
								value = value.url;
							}

							parentValue = value;

							show = $.redux.check_dependencies_visibility( parentValue, parentData );

							return false;
						}
					}
				}
			);
		} else {
			show = true;
		}

		return show;
	};

	/* jshint -W117, -W098 */
	/* jscs:disable disallowUnusedParams */
	/* redux_hook(
		$.redux,
		'required',
		function ( returnValue, originalFunction ) {
			var reduxObj;

			reduxObj = redux.field_objects.tabbed.getOptName( $( '.redux-container-tabbed' ) );

			$.each(
				reduxObj.folds,
				function ( i, v ) {
					var fieldset;
					var div;
					var rawTable;

					fieldset = $( '[id^=' + reduxObj.args.opt_name + '-' + i + ']' );

					if ( fieldset.find( '*' ).hasClass( 'in-tabbed' ) ) {
						fieldset.addClass( 'fold' );
						fieldset.parents( '.redux-tab-field' ).addClass( 'fold' );

						if ( 'hide' === v ) {
							fieldset.addClass( 'hide' );
							fieldset.parents( '.redux-tab-field' ).addClass( 'hide' );

							if ( fieldset.hasClass( 'redux-container-section' ) ) {
								div = $( '#section-' + i );

								if ( div.hasClass( 'redux-section-indent-start' ) ) {
									$( '#section-table-' + i ).hide().addClass( 'hide' );
									div.hide().addClass( 'hide' );
								}
							}

							if ( fieldset.hasClass( 'redux-container-info' ) ) {
								$( '#info-' + i ).hide().addClass( 'hide' );
							}

							if ( fieldset.hasClass( 'redux-container-divide' ) ) {
								$( '#divide-' + i ).hide().addClass( 'hide' );
							}

							if ( fieldset.hasClass( 'redux-container-raw' ) ) {
								rawTable = fieldset.parents().find( 'table#' + redux.args.opt_name + '-' + i );
								rawTable.hide().addClass( 'hide' );
							}
						}
					}
				}
			);
		}
	); */

	/* redux_hook(
		$.redux,
		'required_recursive_hide',
		function ( returnValue, originalFunction, id ) {
			var div;
			var rawTable;
			var toFade;
			var theId;
			var reduxObj;

			reduxObj = redux.field_objects.tabbed.getOptName( $( '.redux-container-tabbed' ) );

			id    = id[0];
			theId = $( '#' + reduxObj.args.opt_name + '-' + id );

			if ( theId.find( '*' ).hasClass( 'in-tabbed' ) ) {
				toFade = theId.parents( '.redux-tab-field:first' );

				if ( 0 === toFade ) {
					toFade = theId.parents( 'li:first' );
				}

				toFade.fadeOut(
					50,
					function () {
						$( this ).addClass( 'hide' );
						//$( this ).prevUntil( '.redux-tab-field' ).addClass( 'hide' );

						if ( theId.hasClass( 'redux-container-section' ) ) {
							div = $( '#section-' + id );

							if ( div.hasClass( 'redux-section-indent-start' ) ) {
								$( '#section-table-' + id ).fadeOut( 50 ).addClass( 'hide' );
								div.fadeOut( 50 ).addClass( 'hide' );
							}
						}

						if ( theId.hasClass( 'redux-container-info' ) ) {
							$( '#info-' + id ).fadeOut( 50 ).addClass( 'hide' );
						}

						if ( theId.hasClass( 'redux-container-divide' ) ) {
							$( '#divide-' + id ).fadeOut( 50 ).addClass( 'hide' );
						}

						if ( theId.hasClass( 'redux-container-raw' ) ) {
							rawTable = $( '#' + reduxObj.args.opt_name + '-' + id ).parents().find( 'table#' + reduxObj.args.opt_name + '-' + id );
							rawTable.fadeOut( 50 ).addClass( 'hide' );
						}

						if ( reduxObject.required.hasOwnProperty( id ) ) {
							$.each(
								reduxObj.required[id],
								function ( child ) {
									$.redux.required_recursive_hide( child );
								}
							);
						}
					}
				);
			}
		}
	); */

	/* redux_hook(
		$.redux,
		'check_dependencies',
		function ( returnValue, originalFunction, variable ) {
			var current;
			var id;
			var container;
			var is_hidden;

			current   = $( variable[0] );

			if ( $( variable ).hasClass( 'in-tabbed' ) ) {
				current   = $( variable[0] );
				id        = current.parents( '.redux-field:first' ).data( 'id' );
				container = current.parents( '.redux-field-container:first' );
				is_hidden = container.hasClass( 'hide' );

				$.each(
					reduxObject.required[id],
					function ( child, dependents ) {
						var current;
						var show;
						var childFieldset;

						current       = $( this );
						show          = false;
						childFieldset = $( '#' + reduxObject.args.opt_name + '-' + child );

						if ( ! is_hidden ) {
							show = redux.field_objects.tabbed.check_parents_dependencies( child );
						}

						if ( true === show ) {
							childFieldset.fadeIn(
								300,
								function () {
									$( this ).removeClass( 'hide' );
									//$( this ).prevUntil( 'fieldset' ).removeClass( 'hide' );
									$( this ).parents( '.redux-tab-field' ).removeClass( 'hide' );
									//console.log($( '#' + reduxObject.args.opt_name + '-' + child ));
//console.log($( '#' + reduxObject.args.opt_name + '-' + child ).children().first());
									if ( reduxObject.required.hasOwnProperty( child ) ) {
										$.redux.check_dependencies( $( '#' + reduxObject.args.opt_name + '-' + child ).children().first() );
									}

									$.redux.initFields();
								}
							);
						} else {
							childFieldset.fadeOut(
								100,
								function () {
									$( this ).addClass( 'hide' );
									//$( this ).prevUntil( 'fieldset' ).addClass( 'hide' );
									$( this ).parents( '.redux-tab-field' ).addClass( 'hide' );

									if ( reduxObject.required.hasOwnProperty( child ) ) {
										$.redux.required_recursive_hide( child );
									}
								}
							);
						}

						current.find( 'select, radio, input[type=checkbox]' ).trigger( 'change' );

						//console.log(variable)
						console.log(current.length);
						console.log(Array.isArray(current));
						if( current.length > 1 ){
							$.each(
								current,
								function(i,v){
									console.log(v)
									//console.log(v.find( 'select, radio, input[type=checkbox]' ));
								}
							);
						} else {
							console.log(current.find( 'select, radio, input[type=checkbox]' ));
							current.find( 'select, radio, input[type=checkbox]' ).trigger( 'change' );
						}
					}
				);
			}
		}
	); */
})( jQuery );
