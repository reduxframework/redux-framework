/* global reduxMetaboxes */

jQuery(
	function( $ ) {
		'use strict';

		var isGutenberg = false;

		$.reduxMetaBoxes = $.reduxMetaBoxes || {};

		$( document ).ready(
			function() {
				$.reduxMetaBoxes.init();

				if ( $( 'body' ).hasClass( 'block-editor-page' ) ) {
					isGutenberg = true;
				}
			}
		);

		setTimeout(
			function() {
				if ( true === isGutenberg ) {
					$( '.postbox .toggle-indicator' ).removeClass( 'toggle-indicator' ).addClass( 'el' );
				}

				$( '#publishing-action .button, #save-action .button, .editor-post-publish-button' ).on(
					'click',
					function() {
						$( '.redux-save-warn' ).slideUp();

						window.onbeforeunload = null;
					}
				);
			},
			1000
		);

		$.reduxMetaBoxes.init = function() {
			$.reduxMetaBoxes.notLoaded = true;

			$.redux.initFields();

			if ( $( 'body' ).hasClass( 'block-editor-page' ) ) {
				isGutenberg = true;
			}

			if ( isGutenberg ) {
				setTimeout(
					function() {
						$.reduxMetaBoxes.checkBoxVisibility();

						$( '.editor-post-format__content select, .editor-post-format select' ).on(
							'change',
							function() {
								$.reduxMetaBoxes.checkBoxVisibility( 'post_format' );
							}
						);

						$( '.edit-post-post-template__toggle' ).on(
							'click',
							function() {
								setTimeout(
									function() {
										$( '.components-popover .components-select-control__input' ).on(
											'change',
											function() {
												console.log( 'change' );
												$.reduxMetaBoxes.checkBoxVisibility( 'page_template' );
											}
										);
									},
									1000
								);
							}
						);
					},
					1000
				);
			} else {
				$.reduxMetaBoxes.checkBoxVisibility();

				$( '#page_template' ).on(
					'change',
					function() {
						$.reduxMetaBoxes.checkBoxVisibility( 'page_template' );
					}
				);

				$( 'input[name="post_format"]:radio' ).on(
					'change',
					function() {
						$.reduxMetaBoxes.checkBoxVisibility( 'post_format' );
					}
				);
			}
		};

		$.reduxMetaBoxes.checkBoxVisibility = function( fieldID ) {
			if ( 0 !== reduxMetaboxes.length ) {
				$.each(
					reduxMetaboxes,
					function( box, values ) {
						$.each(
							values,
							function( field, v ) {
								var visible = false;
								var testValue;

								if ( field === fieldID || ! fieldID ) {
									if ( 'post_format' === field ) {
										if ( isGutenberg ) {
											testValue = $( '.editor-post-format__content select option:selected, .editor-post-format select option:Selected' ).val();
										} else {
											testValue = $( 'input:radio[name="post_format"]:checked' ).val();
										}
									} else {
										testValue = $( '#' + field ).val();
									}

									if ( testValue ) {
										$.each(
											v,
											function( key, val ) {
												if ( val === testValue ) {
													visible = true;
												}
											}
										);

										if ( ! visible && ! $.reduxMetaBoxes.notLoaded ) {
											$( '#' + box ).hide();
										} else if ( ! visible ) {
											$( '#' + box ).hide();
										} else {
											$( '#' + box ).fadeIn( '300' );
											$.redux.initFields();
										}
									}
								}
							}
						);
					}
				);
			}
		};
	}
);
