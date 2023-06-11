/* global redux_change, redux, reduxRepeaterAccordionActivate, reduxRepeaterAccordionBeforeActivate */

( function( $ ) {
	'use strict';

	var reduxObject;
	var panelsClosed;

	redux.field_objects          = redux.field_objects || {};
	redux.field_objects.repeater = redux.field_objects.repeater || {};

	redux.field_objects.repeater.getOptName = function( el ) {
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

	redux.field_objects.repeater.init = function( selector ) {
		if ( ! selector ) {
			selector = $( document ).find( '.redux-group-tab:visible' ).find( '.redux-container-repeater:visible' );
		}

		$( selector ).each(
			function() {
				var gid;
				var blank;

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

				if ( ! el.hasClass( 'redux-field-container' ) ) {
					parent = el.parents( '.redux-field-container:first' );
				}

				reduxObject = redux.field_objects.repeater.getOptName( el );

				gid   = parent.attr( 'data-id' );
				blank = el.find( '.redux-repeater-accordion-repeater:last-child' );

				reduxObject.repeater[gid].blank = blank.clone().wrap( '<p>' ).parent().html();

				if ( parent.hasClass( 'redux-container-repeater' ) ) {
					parent.addClass( 'redux-field-init' );
				}

				if ( parent.hasClass( 'redux-field-init' ) ) {
					parent.removeClass( 'redux-field-init' );
				} else {
					return;
				}

				redux.field_objects.repeater.setAccordion( el, gid );
				redux.field_objects.repeater.bindTitle( el );
				redux.field_objects.repeater.remove( el, gid );
				redux.field_objects.repeater.add( el );
			}
		);
	};

	redux.field_objects.repeater.add = function( el ) {

		/* jshint -W121 */
		String.prototype.reduxReplaceAll = function( s1, s2 ) {
			return this.replace( new RegExp( s1.replace( /[.^$*+?()[{\|]/g, '\\$&' ), 'g' ), s2 );
		};

		el.find( '.redux-repeaters-add' ).on(
			'click',
			function() {
				var parent;
				var count;
				var gid;
				var id;
				var newSlide;
				var html;

				redux_change( $( this ) );

				if ( $( this ).hasClass( 'button-disabled' ) ) {
					return;
				}

				parent = $( this ).parent().find( '.redux-repeater-accordion:first' );
				count  = parent.find( '.redux-repeater-accordion-repeater' ).length;
				gid    = parent.attr( 'data-id' ); // Group id.

				if ( '' !== reduxObject.repeater[gid].limit ) {
					if ( count >= reduxObject.repeater[gid].limit ) {
						$( this ).addClass( 'button-disabled' );
						return;
					}
				}

				count += 1;

				id = parent.find( '.redux-repeater-accordion-repeater' ).length; // Index number.

				if ( parent.find( '.redux-repeater-accordion-repeater:last' ).find( '.ui-accordion-header' ).hasClass( 'ui-state-active' ) ) {
					parent.find( '.redux-repeater-accordion-repeater:last' ).find( '.ui-accordion-header' ).trigger( 'click' );
				}

				newSlide = parent.find( '.redux-repeater-accordion-repeater:last' ).clone( true, true );

				if ( 0 === newSlide.length ) {
					newSlide = reduxObject.repeater[gid].blank;
				}

				if ( reduxObject.repeater[gid] ) {
					reduxObject.repeater[gid].count = el.find( '.redux-repeater-header' ).length;
					html                            = reduxObject.repeater[gid].html.reduxReplaceAll( '99999', id );

					$( newSlide ).find( '.redux-repeater-header' ).text( '' );
				}

				newSlide.find( '.ui-accordion-content' ).html( html );

				// Append to the accordion.
				$( parent ).append( newSlide );

				// Reorder.
				redux.field_objects.repeater.sort_repeaters( newSlide );

				// Refresh the JS object.
				newSlide = $( this ).parent().find( '.redux-repeater-accordion:first' );

				newSlide.find( '.redux-repeater-accordion-repeater:last .ui-accordion-header' ).trigger( 'click' );
				newSlide.find( '.redux-repeater-accordion-repeater:last .bind_title' ).on(
					'change keyup',
					function( event ) {
						var value;

						if ( $( event.target ).find( ':selected' ).text().length > 0 ) {
							value = $( event.target ).find( ':selected' ).text();
						} else {
							value = $( event.target ).val();
						}

						$( this ).closest( '.redux-repeater-accordion-repeater' ).find( '.redux-repeater-header' ).text( value );
					}
				);

				$.redux.checkRequired( el );

				if ( reduxObject.repeater[gid].limit > 0 && count >= reduxObject.repeater[gid].limit ) {
					$( this ).addClass( 'button-disabled' );
				}

				if ( true === panelsClosed ) {
					if ( count >= 2 ) {
						el.find( '.redux-repeater-accordion' ).accordion( 'option', { active: false } );
					}
				}

				if ( count > 1 ) {
					redux.field_objects.repeater.remove( newSlide );
				}
			}
		);
	};

	redux.field_objects.repeater.remove = function( el ) {
		var x;

		// Handler to remove the given repeater.
		el.find( '.redux-repeaters-remove' ).on(
			'click',
			function() {
				var parent;
				var gid;
				var count;

				redux_change( $( this ) );

				parent = $( this ).parents( '.redux-container-repeater:first' );
				gid    = parent.attr( 'data-id' );

				reduxObject.repeater[gid].blank = $( this ).parents( '.redux-repeater-accordion-repeater:first' ).clone( true, true );

				$( this ).parents( '.redux-repeater-accordion-repeater:first' ).slideUp(
					'medium',
					function() {
						$( this ).remove();

						redux.field_objects.repeater.sort_repeaters( el );

						if ( '' !== reduxObject.repeater[gid].limit ) {
							count = parent.find( '.redux-repeater-accordion-repeater' ).length;

							if ( count < reduxObject.repeater[gid].limit ) {
								parent.find( '.redux-repeaters-add' ).removeClass( 'button-disabled' );
							}
						}

						parent.find( '.redux-repeater-accordion-repeater:last .ui-accordion-header' ).trigger( 'click' );
					}
				);
			}
		);

		x = el.find( '.redux-repeater-accordion-repeater' );

		if ( x.hasClass( 'close-me' ) ) {
			el.find( '.redux-repeaters-remove' ).trigger( 'click' );
		}
	};

	redux.field_objects.repeater.bindTitle = function( el ) {
		el.find( '.redux-repeater-accordion-repeater .bind_title' ).on(
			'change keyup',
			function( event ) {
				var value;

				if ( $( event.target ).find( ':selected' ).text().length > 0 ) {
					value = $( event.target ).find( ':selected' ).text();
				} else {
					value = $( event.target ).val();
				}

				$( this ).closest( '.redux-repeater-accordion-repeater' ).find( '.redux-repeater-header' ).text( value );
			}
		);
	};

	redux.field_objects.repeater.setAccordion = function( el, gid ) {
		var active;
		var accordion;

		var base = el.find( '.redux-repeater-accordion' );

		panelsClosed = Boolean( base.data( 'panels-closed' ) );

		if ( true === panelsClosed ) {
			active = Boolean( false );
		} else {
			active = 0;
		}

		accordion = el.find( '.redux-repeater-accordion' ).accordion(
			{
				header: '> div > fieldset > h3',
				collapsible: true,
				active: active,

				beforeActivate: function( event ) {
					var a;
					var relName;
					var optName;
					var bracket;

					a       = $( this ).next( '.redux-repeaters-add' );
					relName = a.attr( 'data-name' );

					bracket = relName.indexOf( '[' );

					optName = relName.substring( 0, bracket );

					if ( 'function' === typeof reduxRepeaterAccordionBeforeActivate ) {
						reduxRepeaterAccordionBeforeActivate( $( this ), el, event, optName );
					}
				},
				activate: function( event, ui ) {
					var a;
					var relName;
					var optName;
					var bracket;

					$.redux.initFields();

					if ( 'function' === typeof reduxRepeaterAccordionActivate ) {
						a       = $( this ).next( '.redux-repeaters-add' );
						relName = a.attr( 'data-name' );
						bracket = relName.indexOf( '[' );

						optName = relName.substring( 0, bracket );

						reduxRepeaterAccordionActivate( $( this ), el, event, ui, optName );
					}
				},
				heightStyle: 'content', icons: {
					'header': 'ui-icon-plus', 'activeHeader': 'ui-icon-minus'
				}
			}
		);

		if ( true === reduxObject.repeater[gid].sortable ) {
			accordion.sortable(
				{
					axis: 'y',
					handle: 'h3',
					placeholder: 'ui-state-highlight',
					start: function( e, ui ) {
						e = null;

						ui.placeholder.height( ui.item.height() );
						ui.placeholder.width( ui.item.width() );
					},
					stop: function( event, ui ) {
						event = null;

						// IE doesn't register the blur when sorting
						// so trigger focusout handlers to remove .ui-state-focus.
						ui.item.children( 'h3' ).triggerHandler( 'focusout' );

						redux.field_objects.repeater.sort_repeaters( $( this ) );

					}
				}
			);
		} else {
			accordion.find( 'h3.ui-accordion-header' ).css( 'cursor', 'pointer' );
		}
	};

	redux.field_objects.repeater.sort_repeaters = function( selector ) {
		if ( ! selector.hasClass( 'redux-container-repeater' ) ) {
			selector = selector.parents( '.redux-container-repeater:first' );
		}

		selector.find( '.redux-repeater-accordion-repeater' ).each(
			function( idx ) {
				var header;
				var split;
				var content;

				var id    = $( this ).attr( 'data-sortid' );
				var input = $( this ).find( '.redux-field .repeater[name*=\'[' + id + ']\']' );

				input.each(
					function() {
						$( this ).attr( 'name', $( this ).attr( 'name' ).replace( '[' + id + ']', '[' + idx + ']' ) );
					}
				);

				input = $( this ).find( '.slide-title' );

				input.attr( 'name', input.attr( 'name' ).replace( '[' + id + ']', '[' + idx + ']' ) );
				input.attr( 'data-key', idx );

				$( this ).attr( 'data-sortid', idx );

				// Fix the accordion header.
				header = $( this ).find( '.ui-accordion-header' );
				split  = header.attr( 'id' ).split( '-header-' );

				header.attr( 'id', split[0] + '-header-' + idx );
				split = header.attr( 'aria-controls' ).split( '-panel-' );

				header.attr( 'aria-controls', split[0] + '-panel-' + idx );

				// Fix the accordion content.
				content = $( this ).find( '.ui-accordion-content' );
				split   = content.attr( 'id' ).split( '-panel-' );

				content.attr( 'id', split[0] + '-panel-' + idx );
				split = content.attr( 'aria-labelledby' ).split( '-header-' );

				content.attr( 'aria-labelledby', split[0] + '-header-' + idx );

			}
		);
	};

	redux.field_objects.repeater.check_parents_dependencies = function( id ) {
		var show    = '';
		var current = id;
		var dash    = current.lastIndexOf( '-' );
		var index   = current.substring( dash + 1 );
		var fixedId = current.replace( index, '99999' );

		if ( reduxObject.required_child.hasOwnProperty( fixedId ) ) {
			$.each(
				reduxObject.required_child[fixedId],
				function( i, parentData ) {
					var parentValue;
					var value;
					var idx;
					var x;

					i   = null;
					idx = $( '#' + reduxObject.args.opt_name + '-' + parentData.parent + '-' + index );

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

								value = x[index];
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
	redux_hook(
		$.redux,
		'required',
		function( returnValue, originalFunction ) {
			var reduxObj;

			reduxObj = redux.field_objects.repeater.getOptName( $( '.redux-container-repeater' ) );

			$.each(
				reduxObj.folds,
				function( i, v ) {

					var fieldset;
					var div;
					var rawTable;

					if ( i.indexOf( '-99999' ) !== - 1 ) {
						i = i.replace( '-99999', '' );
					}

					fieldset = $( '[id^=' + reduxObj.args.opt_name + '-' + i + ']' );

					if ( ! fieldset.children().hasClass( 'in-repeater' ) ) {
						return;
					}

					fieldset.addClass( 'fold' );

					if ( 'hide' === v ) {
						fieldset.addClass( 'hide' );
						fieldset.prevUntil( 'fieldset' ).addClass( 'hide' );

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
			);
		}
	);

	redux_hook(
		$.redux,
		'check_dependencies',
		function( returnValue, originalFunction, variable ) {
			var current;
			var id;
			var container;
			var is_hidden;
			var dash;
			var idNoIndex;
			var index;

			if ( $( variable ).hasClass( 'in-repeater' ) ) {
				current   = $( variable );
				id        = current.parents( '.redux-field:first' ).data( 'id' );
				container = current.parents( '.redux-field-container:first' );
				is_hidden = container.hasClass( 'hide' );
				dash      = id.lastIndexOf( '-' );
				idNoIndex = id.substring( 0, dash );
				index     = id.substring( dash + 1 );

				$.each(
					reduxObject.required[idNoIndex],
					function( child, dependents ) {
						var current;
						var show;
						var childFieldset;

						if ( child.indexOf( '99999' ) !== - 1 ) {
							child = child.replace( '99999', index );
						}

						current       = $( this );
						show          = false;
						childFieldset = $( '#' + reduxObject.args.opt_name + '-' + child );

						if ( ! is_hidden ) {
							show = redux.field_objects.repeater.check_parents_dependencies( child );
						}

						if ( true === show ) {
							childFieldset.fadeIn(
								300,
								function() {
									$( this ).removeClass( 'hide' );
									$( this ).prevUntil( 'fieldset' ).removeClass( 'hide' );

									if ( reduxObject.required.hasOwnProperty( child ) ) {
										$.redux.check_dependencies( $( '#' + reduxObject.args.opt_name + '-' + child ).children().first() );
									}

									$.redux.initFields();
								}
							);
						} else {
							childFieldset.fadeOut(
								100,
								function() {
									$( this ).addClass( 'hide' );
									$( this ).prevUntil( 'fieldset' ).addClass( 'hide' );

									if ( reduxObject.required.hasOwnProperty( child ) ) {
										$.redux.required_recursive_hide( child );
									}
								}
							);
						}

						current.find( 'select, radio, input[type=checkbox]' ).trigger( 'change' );
					}
				);
			}
		}
	);
} )( jQuery );
