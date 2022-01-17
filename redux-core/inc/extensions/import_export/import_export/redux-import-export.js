/* global jQuery, document, redux, ajaxurl, ImportExport */

(function( $ ) {
	'use strict';

	redux.field_objects               = redux.field_objects || {};
	redux.field_objects.import_export = redux.field_objects.import_export || {};

	redux.field_objects.import_export.copy_text = function( $text ) {
		var copyFrom = document.createElement( 'textarea' );
		document.body.appendChild( copyFrom );
		copyFrom.textContent = $text;
		copyFrom.select();
		document.execCommand( 'copy' );
		copyFrom.remove();
	};

	redux.field_objects.import_export.get_options = function( $secret ) {
		var $el = $( '#redux-export-code-copy' );
		var url = ajaxurl + '?download=0&action=redux_download_options-' + redux.optName.args.opt_name + '&secret=' + $secret;

		$el.addClass( 'disabled' ).attr( 'disabled', 'disabled' );
		$el.text( $el.data( 'copy' ) );

		$.get(
			url,
			function( data ) {
				redux.field_objects.import_export.copy_text( data );
				$el.removeClass( 'disabled' );
				$el.text( $el.data( 'copied' ) );
				setTimeout(
					function() {
						$el.text( $el.data( 'copy' ) ).removeClass( 'disabled' ).removeAttr( 'disabled' );
					},
					2000
				);
			}
		);
	};

	redux.field_objects.import_export.init = function( selector ) {
		selector = $.redux.getSelector( selector, 'import_export' );

		$( selector ).each(
			function() {
				var textBox1;
				var textBox2;

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

				el.each(
					function() {
						$( '#redux-import' ).on(
							'click',
							function( e ) {
								if ( '' === $( '#import-code-value' ).val() && '' === $( '#import-link-value' ).val() ) {
									e.preventDefault();
									return false;
								}
							}
						);

						$( this ).find( '#redux-import-code-button' ).on(
							'click',
							function() {
								var $el = $( '#redux-import-code-wrapper' );
								if ( $el.is( ':visible' ) ) {
									$( '#import-link-value' ).val( '' );
									$( '#redux-import-link-wrapper' ).fadeOut(
										'fast',
										function() {
											$el.fadeIn(
												'fast',
												function() {
													$( '#import-code-value' ).focus();
												}
											);
										}
									);
								} else {
									if ( $el.is( ':visible' ) ) {
										$el.fadeOut();
									} else {
										$el.fadeIn(
											'medium',
											function() {
												$( '#import-code-value' ).focus();
											}
										);
									}
								}
							}
						);

						$( this ).find( '#redux-export-code-dl' ).on(
							'click',
							function( e ) {
								e.preventDefault();

								if ( !! window.onbeforeunload ) {
									if ( confirm( ImportExport.unchanged_values ) ) {
										$( '#redux_top_save' ).click();
										setTimeout(
											function() {
												window.open( $( this ).attr( 'href' ) );
											},
											2000
										);
									}
								} else {
									window.open( $( this ).attr( 'href' ) );
								}
							}
						);

						$( this ).find( '#redux-import-upload' ).on(
							'click',
							function() {
								$( '#redux-import-upload-file' ).click();
							}
						);

						document.getElementById( 'redux-import-upload-file' ).addEventListener(
							'change',
							function() {
								var file_to_read = document.getElementById( 'redux-import-upload-file' ).files[0];
								var fileread     = new FileReader();

								$( '#redux-import-upload span' ).text( ': ' + file_to_read.name );

								fileread.onload = function() {
									var content = fileread.result;
									$( '#import-code-value' ).val( content );
								};

								fileread.readAsText( file_to_read );
							}
						);

						$( this ).find( '#redux-export-code-copy' ).on(
							'click',
							function( e ) {
								var $el     = $( '#redux-export-code' );
								var $secret = $( this ).data( 'secret' );

								e.preventDefault();
								if ( !! window.onbeforeunload ) {
									if ( confirm( ImportExport.unchanged_values ) ) {
										$( '#redux_top_save' ).click();
										setTimeout(
											function() {
												redux.field_objects.import_export.get_options( $secret, $el );
											},
											2000
										);
									}
								} else {
									redux.field_objects.import_export.get_options( $secret, $el );
								}
							}
						);

						$( this ).find( 'textarea' ).on(
							'focusout',
							function() {
								var $id        = $( this ).attr( 'id' );
								var $el        = $( this );
								var $container = $el;

								if ( 'import-link-value' === $id || 'import-code-value' === $id ) {
									$container = $( this ).parent();
								}

								$container.fadeOut(
									'medium',
									function() {
										if ( 'redux-export-link-value' !== $id ) {
											$el.text( '' );
										}
									}
								);
							}
						);

						textBox1 = document.getElementById( 'redux-export-code' );

						textBox1.onfocus = function() {
							textBox1.select();

							// Work around Chrome's little problem.
							textBox1.onmouseup = function() {

								// Prevent further mouseup intervention.
								textBox1.onmouseup = null;
								return false;
							};
						};

						textBox2 = document.getElementById( 'import-code-value' );

						textBox2.onfocus = function() {
							textBox2.select();

							// Work around Chrome's little problem.
							textBox2.onmouseup = function() {

								// Prevent further mouseup intervention.
								textBox2.onmouseup = null;
								return false;
							};
						};
					}
				);
			}
		);
	};
})( jQuery );
