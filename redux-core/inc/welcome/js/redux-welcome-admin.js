/* global window */

( function( $ ) {
$.redux_welcome = $.redux_welcome || {};

	$( document ).ready(
		function() {
			$.redux_welcome.initQtip();

			if ( $( document.getElementById( 'support_div' ) ).is( ':visible' ) ) {
				$.redux_welcome.initSupportPage();
			}

			$.redux_welcome.supportHash();
		}
	);

	$.redux_welcome.supportHash = function() {
		$( '#support_hash' ).on(
			'focus',
			function() {
				var $this = $( this );
				$this.trigger( 'select' );

				// Work around Chrome's little problem.
				$this.on(
					'mouseup',
					function() {

						// Prevent further mouseup intervention.
						$this.off( 'mouseup' );
						return false;
					}
				);
			}
		);

		$( '.redux_support_hash' ).on(
			'click',
			function( e ) {
				var $nonce;

				var $button = $( this );

				if ( $button.hasClass( 'disabled' ) ) {
					return;
				}

				$nonce = $( '#redux_support_nonce' ).val();

				$button.addClass( 'disabled' );
				$button.parent().append( '<span class="spinner" style="display:block;float: none;margin: 10px auto;"></span>' );

				$button.closest( '.spinner' ).fadeIn();

				if ( ! window.console ) {
					window.console = {};
				}

				$.ajax(
					{
						type: 'post',
						dataType: 'json',
						url: window.ajaxurl,
						data: {
							action: 'redux_support_hash',
							nonce: $nonce
						},
						error: function( response ) {
							console.log( response );
							$button.removeClass( 'disabled' );
							$button.parent().find( '.spinner' ).remove();
							alert( 'There was an error. Please try again later.' );
						},
						success: function( response ) {
							if ( 'success' === response.status ) {
								$( '#support_hash' ).val( 'https://support.redux.io/?id=' + response.identifier );
								$button.parents( 'fieldset:first' ).find( '.next' ).prop( 'disabled', false ).trigger( 'click' );
							} else {
								window.console.log( response );
								alert( 'There was an error. Please try again later.' );
							}
						}
					}
				);

				e.preventDefault();
			}
		);
	};

	$.redux_welcome.initSupportPage = function() {
		var current_fs, next_fs, previous_fs;   // Fieldsets.
		var left, opacity, scale;               // Fieldset properties which we will animate.
		var animating;                          // Flag to prevent quick multi-click glitches.

		$.fn.actualHeight = function() {

			// Find the closest visible parent and get it's hidden children.
			var visibleParent = this.closest( ':visible' ).children(), thisHeight;

			// Set a temporary class on the hidden parent of the element.
			visibleParent.addClass( 'temp-show' );

			// Get the height.
			thisHeight = this.height();

			// Remove the temporary class.
			visibleParent.removeClass( 'temp-show' );

			return thisHeight;
		};

		function setHeight() {
			var $height = 0;

			$( document ).find( '#support_div fieldset' ).each(
				function() {
					var $actual = $( this ).actualHeight();
					if ( $height < $actual ) {
						$height = $actual;
					}
				}
			);

			$( '#support_div' ).height( $height + 20 );
		}

		setHeight();

		$( window ).on(
			'resize',
			function() {
				setHeight();
			}
		);

		$( '#is_user' ).on(
			'click',
			function() {
				$( '#final_support .is_user' ).show();
				$( '#final_support .is_developer' ).hide();
				$( this ).parents( 'fieldset:first' ).find( '.next' ).trigger( 'click' );
			}
		);

		$( '#is_developer' ).on(
			'click',
			function() {
				$( '#final_support .is_user' ).hide();
				$( '#final_support .is_developer' ).show();
				$( this ).parents( 'fieldset:first' ).find( '.next' ).trigger( 'click' );
			}
		);

		$( '#support_div .next' ).on(
			'click',
			function() {
				if ( animating ) {
					return false;
				}

				animating = true;

				current_fs = $( this ).parent();
				next_fs    = $( this ).parent().next();

				// Activate next step on progressbar using the index of next_fs.
				$( '#progressbar li' ).eq( $( 'fieldset' ).index( next_fs ) ).addClass( 'active' );

				// Show the next fieldset.
				next_fs.show();

				// Hide the current fieldset with style.
				current_fs.animate(
					{ opacity: 0 },
					{
						step: function( now ) {

							// As the opacity of current_fs reduces to 0 - stored in 'now'.
							// 1. scale current_fs down to 80%.
							scale = 1 - ( 1 - now ) * 0.2;

							// 2. bring next_fs from the right(50%)
							left = ( now * 50 ) + '%';

							// 3. increase opacity of next_fs to 1 as it moves in
							opacity = 1 - now;

							current_fs.css( { 'transform': 'scale(' + scale + ')' } );
							next_fs.css( { 'left': left, 'opacity': opacity } );
						},
						duration: 800, complete: function() {
							current_fs.hide();
							animating = false;
						},
						easing: 'easeInOutBack'
					}
				);
			}
		);

		$( '#support_div .previous' ).on(
			'click',
			function() {
				if ( animating ) {
					return false;
				}

				animating = true;

				current_fs  = $( this ).parent();
				previous_fs = $( this ).parent().prev();

				// De-activate current step on progressbar.
				$( '#progressbar li' ).eq( $( 'fieldset' ).index( current_fs ) ).removeClass( 'active' );

				// Show the previous fieldset.
				previous_fs.show();

				// Hide the current fieldset with style.
				current_fs.animate(
					{ opacity: 0 },
					{
						step: function( now ) {

							// As the opacity of current_fs reduces to 0 - stored in 'now'.
							// 1. scale previous_fs from 80% to 100%.
							scale = 0.8 + ( 1 - now ) * 0.2;

							// 2. take current_fs to the right(50%) - from 0%.
							left = ( ( 1 - now ) * 50 ) + '%';

							// 3. increase opacity of previous_fs to 1 as it moves in.
							opacity = 1 - now;

							current_fs.css( { 'left': left } );
							previous_fs.css( { 'transform': 'scale(' + scale + ')', 'opacity': opacity } );
						},
						duration: 800, complete: function() {
							current_fs.hide();
							animating = false;
						}, // This comes from the custom easing plugin
						easing: 'easeInOutBack'
					}
				);
			}
		);
	};

	$.redux_welcome.initQtip = function() {
		var shadow  = 'qtip-shadow';
		var color   = 'qtip-dark';
		var rounded = '';
		var style   = ''; // Qtip-bootstrap'.
		var classes = shadow + ',' + color + ',' + rounded + ',' + style;

		// Get position data.
		var myPos = 'top center';
		var atPos = 'bottom center';

		// Tooltip trigger action.
		var showEvent = 'mouseenter';
		var hideEvent = 'click mouseleave';

		// Tip show effect.
		var tipShowEffect   = 'slide';
		var tipShowDuration = '300';

		// Tip hide effect.
		var tipHideEffect   = 'slide';
		var tipHideDuration = '300';

		if ( $().qtip ) {
			classes = classes.replace( /,/g, ' ' );

			$( '.redux-hint-qtip' ).each(
				function() {
					$( this ).qtip(
						{
							content: {
								text: $( this ).attr( 'qtip-content' ), title: $( this ).attr( 'qtip-title' )
							},
							show: {
								effect: function() {
									switch ( tipShowEffect ) {
										case 'slide':
											$( this ).slideDown( tipShowDuration );
											break;
										case 'fade':
											$( this ).fadeIn( tipShowDuration );
											break;
										default:
											$( this ).show();
											break;
									}
								},
								event: showEvent
							}, hide: {
								effect: function() {
									switch ( tipHideEffect ) {
										case 'slide':
											$( this ).slideUp( tipHideDuration );
											break;
										case 'fade':
											$( this ).fadeOut( tipHideDuration );
											break;
										default:
											$( this ).show( tipHideDuration );
											break;
									}
								}, event: hideEvent
							}, style: {
								classes: classes
							}, position: {
								my: myPos,
								at: atPos
							}
						}
					);
				}
			);
		}
	};
})( jQuery );
