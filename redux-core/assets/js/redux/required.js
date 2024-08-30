/* global redux */
// noinspection JSUnresolvedReference

( function ( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.makeBoolStr = function ( val ) {
		if ( 'false' === val || false === val || '0' === val || 0 === val || null === val || '' === val ) {
			return 'false';
		} else if ( 'true' === val || true === val || '1' === val || 1 === val ) {
			return 'true';
		} else {
			return val;
		}
	};

	$.redux.checkRequired = function ( el ) {
		let body;

		$.redux.required();

		body = $( 'body' );

		body.on(
			'change',
			'.redux-main select, .redux-main radio, .redux-main input[type=checkbox], .redux-main input[type=hidden]',
			function () {
				$.redux.check_dependencies( this );
			}
		);

		body.on(
			'check_dependencies',
			function ( e, variable ) {
				e = null;
				$.redux.check_dependencies( variable );
			}
		);

		if ( redux.customizer ) {
			el.find( '.customize-control.redux-field.hide' ).hide();
		}

		el.find( '.redux-container td > fieldset:empty,td > div:empty' ).parent().parent().hide();
	};

	$.redux.required = function () {

		// Hide the fold elements on load.
		// It's better to do this by PHP but there is no filter in tr tag , so is not possible
		// we're going to move each attributes we may need for folding to tr tag.
		$.each(
			redux.opt_names,
			function ( x ) {
				$.each(
					window['redux_' + redux.opt_names[x].replace( /\-/g, '_' )].folds,
					function ( i, v ) {
						let div;
						let rawTable;
						let inTabbed   = false;
						const fieldset = $( '#' + redux.opt_names[x] + '-' + i );

						if ( fieldset.find( '*' ).hasClass( 'in-tabbed' ) ) {
							inTabbed = true;
						}

						if ( true === inTabbed ) {
							fieldset.addClass( 'fold' );
							fieldset.parents( '.redux-tab-field' ).addClass( 'fold' );
						} else {
							fieldset.parents( 'tr:first, li:first' ).addClass( 'fold' );
						}

						if ( 'hide' === v ) {
							if ( true === inTabbed ) {
								fieldset.addClass( 'hide' );
								fieldset.parents( '.redux-tab-field' ).addClass( 'hide' );
							} else {
								fieldset.parents( 'tr:first, li:first' ).addClass( 'hide' );
							}

							if ( fieldset.hasClass( 'redux-container-section' ) ) {
								div = $( '#section-' + i );

								if ( div.hasClass( 'redux-section-indent-start' ) ) {
									$( '#section-table-' + i ).hide().addClass( 'hide' );
									div.hide().addClass( 'hide' );
								}
							}

							if ( fieldset.hasClass( 'redux-container-content' ) ) {
								$( '#heading-' + i ).hide().addClass( 'hide' );
								$( '#subheading-' + i ).hide().addClass( 'hide' );
								$( '#content-' + i ).hide().addClass( 'hide' );
								$( '#submessage-' + i ).hide().addClass( 'hide' );
							}

							if ( fieldset.hasClass( 'redux-container-info' ) ) {
								$( '#info-' + i ).hide().addClass( 'hide' );
							}

							if ( fieldset.hasClass( 'redux-container-divide' ) ) {
								$( '#divide-' + i ).hide().addClass( 'hide' );
							}

							if ( fieldset.hasClass( 'redux-container-raw' ) ) {
								rawTable = fieldset.parents().find( 'table#' + redux.opt_names[x] + '-' + i );
								rawTable.hide().addClass( 'hide' );
							}
						}
					}
				);
			}
		);
	};

	$.redux.getContainerValue = function ( id ) {
		let theId;
		let value;

		theId = $( '#' + redux.optName.args.opt_name + '-' + id );
		value = theId.serializeForm();

		if ( null !== value && 'object' === typeof value && value.hasOwnProperty( redux.optName.args.opt_name ) ) {
			value = value[redux.optName.args.opt_name][id];
		}

		if ( theId.hasClass( 'redux-container-media' ) ) {
			value = value.url;
		}

		return value;
	};

	$.redux.check_dependencies = function ( variable ) {
		let current;
		let id;
		let container;
		let isHidden;
		let inTabbed = false;

		if ( null === redux.optName.required ) {
			return;
		}

		if ( $( variable ).hasClass( 'in-tabbed' ) ) {
			inTabbed = true;
		}

		current = $( variable );
		id      = current.parents( '.redux-field:first' ).data( 'id' );

		if ( ! redux.optName.required.hasOwnProperty( id ) ) {
			return;
		}

		container = current.parents( '.redux-field-container:first' );

		if ( true === inTabbed ) {
			isHidden = container.hasClass( 'hide' );
		} else {
			isHidden = container.parents( 'tr:first' ).hasClass( 'hide' );

			if ( ! container.parents( 'tr:first' ).length ) {
				isHidden = container.parents( '.customize-control:first' ).hasClass( 'hide' );
			}
		}

		$.each(
			redux.optName.required[id],
			function ( child ) {
				let div;
				let rawTable;
				let tr;
				let tabbed = false;

				const current       = $( this );
				let show            = false;
				const childFieldset = $( '#' + redux.optName.args.opt_name + '-' + child );

				if ( childFieldset.find( '*' ).hasClass( 'in-tabbed' ) ) {
					tabbed = true;
				}

				if ( true === tabbed ) {
					tr = childFieldset;
				} else {
					tr = childFieldset.parents( 'tr:first' );

					if ( 0 === tr.length ) {
						tr = childFieldset.parents( 'li:first' );
					}
				}

				if ( ! isHidden ) {
					show = $.redux.check_parents_dependencies( child );
				}

				if ( true === show ) {

					// Shim for sections.
					if ( childFieldset.hasClass( 'redux-container-section' ) ) {
						div = $( '#section-' + child );

						if ( div.hasClass( 'redux-section-indent-start' ) && div.hasClass( 'hide' ) ) {
							$( '#section-table-' + child ).fadeIn( 300 ).removeClass( 'hide' );
							div.fadeIn( 300 ).removeClass( 'hide' );
						}
					}

					if ( childFieldset.hasClass( 'redux-container-content' ) ) {
						$( '#heading-' + child ).hide().removeClass( 'hide' ).css( 'display', '' );
						$( '#subheading-' + child ).hide().removeClass( 'hide' ).css( 'display', '' );
						$( '#content-' + child ).hide().removeClass( 'hide' ).css( 'display', '' );
						$( '#submessage-' + child ).hide().removeClass( 'hide' ).css( 'display', '' );
					}

					if ( childFieldset.hasClass( 'redux-container-info' ) ) {
						$( '#info-' + child ).fadeIn( 300 ).removeClass( 'hide' );
					}

					if ( childFieldset.hasClass( 'redux-container-divide' ) ) {
						$( '#divide-' + child ).fadeIn( 300 ).removeClass( 'hide' );
					}

					if ( childFieldset.hasClass( 'redux-container-raw' ) ) {
						rawTable = childFieldset.parents().find( 'table#' + redux.optName.args.opt_name + '-' + child );
						rawTable.fadeIn( 300 ).removeClass( 'hide' );
					}

					tr.fadeIn(
						300,
						function () {
							$( this ).removeClass( 'hide' );

							if ( true === tabbed ) {
								$( this ).parents( '.redux-tab-field' ).removeClass( 'hide' ).css( { display:'' } );
							}

							if ( redux.optName.required.hasOwnProperty( child ) ) {
								$.redux.check_dependencies( $( '#' + redux.optName.args.opt_name + '-' + child ).children().first() );
							}

							$.redux.initFields();
						}
					);

					if ( childFieldset.hasClass( 'redux-container-section' ) || childFieldset.hasClass( 'redux-container-info' ) || childFieldset.hasClass( 'redux-container-content' ) ) {
						tr.css( { display: 'none' } );
					}
				} else if ( false === show ) {
					tr.fadeOut(
						100,
						function () {
							$( this ).addClass( 'hide' );

							if ( true === tabbed ) {
								$( this ).parents( '.redux-tab-field' ).addClass( 'hide' );
							}

							if ( redux.optName.required.hasOwnProperty( child ) ) {
								$.redux.required_recursive_hide( child );
							}
						}
					);
				}

				current.find( 'select, radio, input[type=checkbox]' ).trigger( 'change' );
			}
		);
	};

	$.redux.required_recursive_hide = function ( id ) {
		let div;
		let rawTable;
		let toFade;
		let theId;
		let inTabbed = false;

		theId = $( '#' + redux.optName.args.opt_name + '-' + id );

		if ( theId.find( '*' ).hasClass( 'in-tabbed' ) ) {
			inTabbed = true;
		}

		if ( true === inTabbed ) {
			toFade = theId.parents( '.redux-tab-field:first' );
		} else {
			toFade = theId.parents( 'tr:first' );
			if ( 0 === toFade ) {
				toFade = theId.parents( 'li:first' );
			}
		}

		toFade.fadeOut(
			50,
			function () {
				$( this ).addClass( 'hide' );

				if ( theId.hasClass( 'redux-container-section' ) ) {
					div = $( '#section-' + id );

					if ( div.hasClass( 'redux-section-indent-start' ) ) {
						$( '#section-table-' + id ).fadeOut( 50 ).addClass( 'hide' );
						div.fadeOut( 50 ).addClass( 'hide' );
					}
				}

				if ( theId.hasClass( 'redux-container-content' ) ) {
					$( '#heading-' + id ).hide().addClass( 'hide' );
					$( '#subheading-' + id ).hide().addClass( 'hide' );
					$( '#content-' + id ).hide().addClass( 'hide' );
					$( '#submessage-' + id ).hide().addClass( 'hide' );
				}

				if ( theId.hasClass( 'redux-container-info' ) ) {
					$( '#info-' + id ).fadeOut( 50 ).addClass( 'hide' );
				}

				if ( theId.hasClass( 'redux-container-divide' ) ) {
					$( '#divide-' + id ).fadeOut( 50 ).addClass( 'hide' );
				}

				if ( theId.hasClass( 'redux-container-raw' ) ) {
					rawTable = $( '#' + redux.optName.args.opt_name + '-' + id ).parents().find( 'table#' + redux.optName.args.opt_name + '-' + id );
					rawTable.fadeOut( 50 ).addClass( 'hide' );
				}

				if ( redux.optName.required.hasOwnProperty( id ) ) {
					$.each(
						redux.optName.required[id],
						function ( child ) {
							$.redux.required_recursive_hide( child );
						}
					);
				}
			}
		);
	};

	$.redux.check_parents_dependencies = function ( id ) {
		let show = '';

		if ( redux.optName.required_child.hasOwnProperty( id ) ) {
			$.each(
				redux.optName.required_child[id],
				function ( i, parentData ) {
					let parentValue;
					let parent;

					parent = $( '#' + redux.optName.args.opt_name + '-' + parentData.parent );

					i = null;

					if ( parent.parents( 'tr:first' ).hasClass( 'hide' ) ) {
						show = false;
					} else if ( parent.parents( 'li:first' ).hasClass( 'hide' ) ) {
						show = false;
					} else {
						if ( false !== show ) {
							parentValue = $.redux.getContainerValue( parentData.parent );

							show = $.redux.check_dependencies_visibility( parentValue, parentData );
						}
					}
				}
			);
		} else {
			show = true;
		}

		return show;
	};

	$.redux.check_dependencies_visibility = function ( parentValue, data ) {
		let show        = false;
		let checkValue  = data.checkValue;
		const operation = data.operation;
		let arr;

		if ( $.isPlainObject( parentValue ) ) {
			parentValue = Object.keys( parentValue ).map(
				function ( key ) {
					return [key, parentValue[key]];
				}
			);
		}

		switch ( operation ) {
			case '=':
			case 'equals':
				if ( Array.isArray( parentValue ) ) {
					$( parentValue[0] ).each(
						function ( idx, val ) {
							idx = null;

							if ( Array.isArray( checkValue ) ) {
								$( checkValue ).each(
									function ( i, v ) {
										i = null;
										if ( $.redux.makeBoolStr( val ) === $.redux.makeBoolStr( v ) ) {
											show = true;

											return true;
										}
									}
								);
							} else {
								if ( $.redux.makeBoolStr( val ) === $.redux.makeBoolStr( checkValue ) ) {
									show = true;

									return true;
								}
							}
						}
					);
				} else {
					if ( Array.isArray( checkValue ) ) {
						$( checkValue ).each(
							function ( i, v ) {
								i = null;

								if ( $.redux.makeBoolStr( parentValue ) === $.redux.makeBoolStr( v ) ) {
									show = true;
								}
							}
						);
					} else {
						if ( $.redux.makeBoolStr( parentValue ) === $.redux.makeBoolStr( checkValue ) ) {
							show = true;
						}
					}
				}
				break;

			case '!=':
			case 'not':
				if ( Array.isArray( parentValue ) ) {
					$( parentValue[0] ).each(
						function ( idx, val ) {
							idx = null;

							if ( Array.isArray( checkValue ) ) {
								$( checkValue ).each(
									function ( i, v ) {
										i = null;

										if ( $.redux.makeBoolStr( val ) !== $.redux.makeBoolStr( v ) ) {
											show = true;

											return true;
										}
									}
								);
							} else {
								if ( $.redux.makeBoolStr( val ) !== $.redux.makeBoolStr( checkValue ) ) {
									show = true;

									return true;
								}
							}
						}
					);
				} else {
					if ( Array.isArray( checkValue ) ) {
						$( checkValue ).each(
							function ( i, v ) {
								i = null;

								if ( $.redux.makeBoolStr( parentValue ) !== $.redux.makeBoolStr( v ) ) {
									show = true;
								}
							}
						);
					} else {
						if ( $.redux.makeBoolStr( parentValue ) !== $.redux.makeBoolStr( checkValue ) ) {
							show = true;
						}
					}
				}
				break;

			case '>':
			case 'greater':
			case 'is_larger':
				if ( parseFloat( parentValue ) > parseFloat( checkValue ) ) {
					show = true;
				}
				break;

			case '>=':
			case 'greater_equal':
			case 'is_larger_equal':
				if ( parseFloat( parentValue ) >= parseFloat( checkValue ) ) {
					show = true;
				}
				break;

			case '<':
			case 'less':
			case 'is_smaller':
				if ( parseFloat( parentValue ) < parseFloat( checkValue ) ) {
					show = true;
				}
				break;

			case '<=':
			case 'less_equal':
			case 'is_smaller_equal':
				if ( parseFloat( parentValue ) <= parseFloat( checkValue ) ) {
					show = true;
				}
				break;

			case 'contains':
				if ( $.isPlainObject( parentValue ) ) {
					parentValue = Object.keys( parentValue ).map(
						function ( key ) {
							return [key, parentValue[key]];
						}
					);
				}

				if ( $.isPlainObject( checkValue ) ) {
					checkValue = Object.keys( checkValue ).map(
						function ( key ) {
							return [key, checkValue[key]];
						}
					);
				}

				if ( Array.isArray( checkValue ) ) {
					$( checkValue ).each(
						function ( idx, val ) {
							let breakMe   = false;
							const toFind  = val[0];
							const findVal = val[1];

							idx = null;

							$( parentValue ).each(
								function ( i, v ) {
									const toMatch  = v[0];
									const matchVal = v[1];

									i = null;

									if ( toFind === toMatch ) {
										if ( findVal === matchVal ) {
											show    = true;
											breakMe = true;

											return false;
										}
									}
								}
							);

							if ( true === breakMe ) {
								return false;
							}
						}
					);
				} else {
					if ( parentValue.toString().indexOf( checkValue ) !== - 1 ) {
						show = true;
					}
				}
				break;

			case 'doesnt_contain':
			case 'not_contain':
				if ( $.isPlainObject( parentValue ) ) {
					arr = Object.keys( parentValue ).map(
						function ( key ) {
							return parentValue[key];
						}
					);

					parentValue = arr;
				}

				if ( $.isPlainObject( checkValue ) ) {
					arr = Object.keys( checkValue ).map(
						function ( key ) {
							return checkValue[key];
						}
					);

					checkValue = arr;
				}

				if ( Array.isArray( checkValue ) ) {
					$( checkValue ).each(
						function ( idx, val ) {
							idx = null;

							if ( parentValue.toString().indexOf( val ) === - 1 ) {
								show = true;
							}
						}
					);
				} else {
					if ( parentValue.toString().indexOf( checkValue ) === - 1 ) {
						show = true;
					}
				}
				break;

			case 'is_empty_or':
				if ( '' === parentValue || checkValue === parentValue ) {
					show = true;
				}
				break;

			case 'not_empty_and':
				if ( '' !== parentValue && checkValue !== parentValue ) {
					show = true;
				}
				break;

			case 'is_empty':
			case 'empty':
			case '!isset':
				if ( ! parentValue || '' === parentValue || null === parentValue ) {
					show = true;
				}
				break;

			case 'not_empty':
			case '!empty':
			case 'isset':
				if ( parentValue && '' !== parentValue && null !== parentValue ) {
					show = true;
				}
				break;
		}

		return show;
	};
})( jQuery );
