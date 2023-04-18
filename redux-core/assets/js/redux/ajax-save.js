/* global redux, tinyMCE, ajaxurl */

(function( $ ) {
	'use strict';

	$.redux = $.redux || {};

	$.redux.ajax_save = function( button ) {
		var $data;
		var $nonce;

		var overlay           = $( document.getElementById( 'redux_ajax_overlay' ) );
		var $notification_bar = $( document.getElementById( 'redux_notification_bar' ) );
		var $parent           = $( button ).parents( '.redux-wrap-div' ).find( 'form' ).first();

		overlay.fadeIn();

		// Add the loading mechanism.
		$( '.redux-action_bar .spinner' ).addClass( 'is-active' );
		$( '.redux-action_bar input' ).prop( 'disabled', true );

		$notification_bar.slideUp();

		$( '.redux-save-warn' ).slideUp();
		$( '.redux_ajax_save_error' ).slideUp(
			'medium',
			function() {
				$( this ).remove();
			}
		);

		// Editor field doesn't auto save. Have to call it. Boo.
		if ( redux.optName.hasOwnProperty( 'editor' ) ) {
			$.each(
				redux.optName.editor,
				function( $key ) {
					var editor;

					if ( 'undefined' !== typeof ( tinyMCE ) ) {
						editor = tinyMCE.get( $key );

						if ( editor ) {
							editor.save();
						}
					}
				}
			);
		}

		$data = $parent.serialize();

		// Add values for checked and unchecked checkboxes fields.
		$parent.find( 'input[type=checkbox]' ).each(
			function() {
				var chkVal;

				if ( 'undefined' !== typeof $( this ).attr( 'name' ) ) {
					chkVal = $( this ).is( ':checked' ) ? $( this ).val() : '0';

					$data += '&' + $( this ).attr( 'name' ) + '=' + chkVal;
				}
			}
		);

		if ( 'redux_save' !== button.attr( 'name' ) ) {
			$data += '&' + button.attr( 'name' ) + '=' + button.val();
		}

		$nonce = $parent.attr( 'data-nonce' );

		$.ajax(
			{ type: 'post',
				dataType: 'json',
				url: ajaxurl,
				data: {
					action:     redux.optName.args.opt_name + '_ajax_save',
					nonce:      $nonce,
					'opt_name': redux.optName.args.opt_name,
					data:       $data
				},
				error: function( response ) {
					var input = $( '.redux-action_bar input' );

					input.prop( 'disabled', false );

					if ( true === redux.optName.args.dev_mode ) {
						console.log( response.responseText );

						overlay.fadeOut( 'fast' );
						$( '.redux-action_bar .spinner' ).removeClass( 'is-active' );
						alert( redux.optName.ajax.alert );
					} else {
						redux.optName.args.ajax_save = false;

						$( button ).trigger( 'click' );
						input.prop( 'disabled', true );
					}
				},
				success: function( response ) {
					var $save_notice;

					if ( response.action && 'reload' === response.action ) {
						location.reload( true );
					} else if ( 'success' === response.status ) {
						$( '.redux-action_bar input' ).prop( 'disabled', false );
						overlay.fadeOut( 'fast' );
						$( '.redux-action_bar .spinner' ).removeClass( 'is-active' );
						redux.optName.options  = response.options;
						redux.optName.errors   = response.errors;
						redux.optName.warnings = response.warnings;
						redux.optName.sanitize = response.sanitize;

						$notification_bar.html( response.notification_bar ).slideDown( 'fast' );
						if ( null !== response.errors || null !== response.warnings ) {
							$.redux.notices();
						}

						if ( null !== response.sanitize ) {
							$.redux.sanitize();
						}

						$save_notice = $( document.getElementById( 'redux_notification_bar' ) ).find( '.saved_notice' );

						$save_notice.slideDown();
						$save_notice.delay( 4000 ).slideUp();
					} else {
						$( '.redux-action_bar input' ).prop( 'disabled', false );
						$( '.redux-action_bar .spinner' ).removeClass( 'is-active' );
						overlay.fadeOut( 'fast' );
						$( '.wrap h2:first' ).parent().append( '<div class="error redux_ajax_save_error" style="display:none;"><p>' + response.status + '</p></div>' );
						$( '.redux_ajax_save_error' ).slideDown();
						$( 'html, body' ).animate(
							{ scrollTop: 0 },
							'slow'
						);
					}
				}
			}
		);

		return false;
	};
})( jQuery );
