/* global jQuery, document, redux, redux_change:true, wp, ajaxurl */

(function( $ ) {
	'use strict';

	redux.customizer = redux.customizer || {};

	$( document ).ready(
		function() {
			redux.customizer.init();
		}
	);

	redux.customizer.init = function() {
		var reduxChange;
		var redux_initFields;

		$( 'body' ).addClass( redux.customizer.body_class );

		$( '.accordion-section.redux-section, .accordion-section.redux-panel, .accordion-section-title' ).on(
			'click',
			function() {
				$.redux.initFields();
			}
		);

		$( '.accordion-section.redux-section h3, .accordion-section.redux-panel h3' ).on(
			'click',
			function() {
				redux.customizer.resize( $( this ).parent() );
			}
		);

		if ( undefined === redux.optName ) {
			console.log( 'Redux customizer extension failure' );
			return;
		}

		$( '.control-panel-back, .customize-panel-back' ).on(
			'click',
			function() {
				$( document ).find( 'form#customize-controls' ).removeAttr( 'style' );
				$( document ).find( '.wp-full-overlay' ).removeAttr( 'style' );
				redux.customizer.width = 0;
			}
		);

		$( '.control-section-back, .customize-section-back' ).on(
			'click',
			function() {
				redux.customizer.resize( $( this ).parent().parent().parent() );
			}
		);

		if ( redux.customizer ) {

			// Customizer save hook.
			$( '#customize-save-button-wrapper #save' ).on(
				'click',
				function() {
					setTimeout(
						function() {
							var $parent = $( document.getElementById( 'customize-controls' ) );
							var $data   = $parent.serialize();
							var nonce   = $( '.redux-customizer-nonce' ).data( 'nonce' );

							$.ajax(
								{
									type: 'post',
									dataType: 'json',
									url: ajaxurl,
									data: {
										action: redux.optName.args.opt_name + '_customizer_save',
										nonce: nonce,
										opt_name: redux.optName.args.opt_name,
										data: $data
									},
									error: function( response ) {
										if ( true === redux.optName.args.dev_mode ) {
											console.log( response.responseText );
										}
									},
									success: function( response ) {
										if ( 'success' === response.status ) {
											console.log( response );
											$( '.redux-action_bar .spinner' ).removeClass( 'is-active' );
											redux.optName.options  = response.options;
											redux.optName.errors   = response.errors;
											redux.optName.warnings = response.warnings;
											redux.optName.sanitize = response.sanitize;

											if ( null !== response.errors || null !== response.warnings ) {
												$.redux.notices();
											}

											if ( null !== response.sanitize ) {
												$.redux.sanitize();
											}
										} else {
											console.log( response.responseText );
										}
									}
								}
							);

						},
						1000
					);
				}
			);
		}

		redux.optName.args.disable_save_warn = true;

		reduxChange  = redux_change;
		redux_change = function( variable ) {
			variable = $( variable );
			reduxChange.apply( this, arguments );
			redux.customizer.save( variable );
		};

		redux_initFields = $.redux.initFields;

		$.redux.initFiles = function() {
			redux_initFields();
		};
	};

	redux.customizer.resize = function( el ) {
		var width;
		var test;
		var id;

		if ( el.attr( 'data-width' ) ) {
			redux.customizer.width = el.attr( 'data-width' );

			width = redux.customizer.width;

		} else {
			width = redux.customizer.width;
		}

		if ( $( 'body' ).width() < 640 ) {
			width = '';
		}
		if ( '' !== width ) {
			test = $( '#' + el.attr( 'aria-owns' ) );

			if ( test.length > 0 ) {
				el = test;
			}
		}

		if ( el.hasClass( 'open' ) || el.hasClass( 'current-panel' ) || el.hasClass( 'current-section' ) ) {
			if ( '' !== width ) {
				$( document ).find( 'form#customize-controls' ).attr(
					'style',
					'width:' + width + ';'
				);
				$( document ).find( '.wp-full-overlay' ).attr(
					'style',
					'margin-left:' + width + ';'
				);
			}
		} else {
			id = el.attr( 'id' );
			id = $( '*[aria-owns="' + id + '"]' ).parents( '.redux-panel:first' ).attr( 'id' );

			width = $( '*[aria-owns="' + id + '"]' ).attr( 'data-width' );

			if ( ! width ) {
				$( document ).find( 'form#customize-controls' ).removeAttr( 'style' );
				$( document ).find( '.wp-full-overlay' ).removeAttr( 'style' );
			} else {
				$( document ).find( 'form#customize-controls' ).attr(
					'style',
					'width:' + width + ';'
				);
				$( document ).find( '.wp-full-overlay' ).attr(
					'style',
					'margin-left:' + width + ';'
				);
			}
		}
	};

	redux.customizer.save = function( $obj ) {
		var $parent = $obj.hasClass( 'redux-field' ) ? $obj : $obj.parents( '.redux-field-container:first' );
		redux.customizer.inputSave( $parent );
	};

	redux.customizer.inputSave = function( $parent ) {
		var $id;
		var $nData;
		var $key;
		var $control;

		if ( ! $parent.hasClass( 'redux-field-container' ) ) {
			$parent = $parent.parents( '[class^="redux-field-container"]' );
		}

		$id = $parent.parent().find( '.redux-customizer-input' ).data( 'id' );

		if ( ! $id ) {
			$parent = $parent.parents( '.redux-container-repeater:first' );
			$id     = $parent.parent().find( '.redux-customizer-input' ).data( 'id' );
		}

		$nData = $parent.find( ':input' ).serializeJSON();

		$.each(
			$nData,
			function( $k, $v ) {
				$k     = null;
				$nData = $v;
			}
		);

		$key = $parent.parent().find( '.redux-customizer-input' ).data( 'key' );
		if ( $nData[$key] ) {
			$nData = $nData[$key];
		}

		$control = wp.customize.control( $id );

		// Customizer hack since they didn't code it to save order...
		if ( JSON.stringify( $control.setting._value ) !== JSON.stringify( $nData ) ) {
			$control.setting._value = null;
		}

		$control.setting.set( $nData );
	};
})( jQuery );
