/* global reduxWidgetAreasLocalize, ajaxurl, jQuery */

( function( $ ) {
	'use strict';

	var reduxWidgetAreas = function() {
		this.widgetWrap     = $( '.sidebars-column-1' );
		this.widgetArea     = $( '#widgets-right' );
		this.parentArea     = $( '.widget-liquid-right' );
		this.widgetTemplate = $( '#redux-add-widget-template' );

		this.addFormHtml();
		this.addDelButton();
		this.bindEvents();
	};

	reduxWidgetAreas.prototype = {
		addFormHtml: function() {
			this.widgetWrap.append( this.widgetTemplate.html() );

			this.widgetName = this.widgetWrap.find( 'input[name="redux-add-widget-input"]' );
			this.nonce      = this.widgetWrap.find( 'input[name="redux-nonce"]' ).val();
		},

		addDelButton: function() {
			var i = 0;
			this.widgetArea.find( '.sidebar-redux-custom .widgets-sortables' ).each(
				function() {
					if ( i >= reduxWidgetAreasLocalize.count ) {
						$( this ).append(
							'<div class="redux-widget-area-edit"><a href="#" class="redux-widget-area-delete button-primary">' + reduxWidgetAreasLocalize.delete +
							'</a><a href="#" style="display:none" class="redux-widget-area-delete-cancel button-secondary">' + reduxWidgetAreasLocalize.cancel +
							'</a><a href="#" style="display:none" class="redux-widget-area-delete-confirm button-primary">' + reduxWidgetAreasLocalize.confirm + '</a></div>'
						);
					}

					i++;
				}
			);
		},

		bindEvents: function() {
			this.parentArea.on(
				'click',
				'a.redux-widget-area-delete',
				function( event ) {
					event.preventDefault();
					$( this ).hide();
					$( this ).next( 'a.redux-widget-area-delete-cancel' ).show().next( 'a.redux-widget-area-delete-confirm' ).show();
				}
			);

			this.parentArea.on(
				'click',
				'a.redux-widget-area-delete-cancel',
				function( event ) {
					event.preventDefault();
					$( this ).hide();
					$( this ).prev( 'a.redux-widget-area-delete' ).show();
					$( this ).next( 'a.redux-widget-area-delete-confirm' ).hide();
				}
			);

			this.parentArea.on(
				'click',
				'a.redux-widget-area-delete-confirm',
				$.proxy( this.deleteWidgetArea, this )
			);

			$( '#addWidgetAreaForm' ).on(
				'submit',
				function() {
					var spinner = $( '#redux-add-widget' ).find( '.spinner' );

					spinner.css( 'display', 'inline-block' );
					spinner.css( 'visibility', 'visible' );

					$.proxy( this.addWidgetArea, this );
				}
			);
		},

		addWidgetArea: function( e ) {
			e.preventDefault();

			return false;
		},

		// Delete the widgetArea area with all widgets within, then re calculate the other widgetArea ids and save the order.
		deleteWidgetArea: function( e ) {
			var widget     = $( e.currentTarget ).parents( '.widgets-holder-wrap:eq(0)' );
			var title      = widget.find( '.sidebar-name h2' );
			var spinner    = title.find( '.spinner' );
			var widgetName = title.text().trim();
			var _this      = this;

			widget.addClass( 'closed' );

			spinner.css( 'display', 'inline-block' );
			spinner.css( 'visibility', 'visible' );

			$.ajax(
				{
					type: 'POST',
					url: ajaxurl,
					data: {
						action: 'redux_delete_widget_area',
						name: widgetName,
						_wpnonce: _this.nonce
					},

					success: function( response ) {
						if ( 'widget_area-deleted' === response.trim() ) {
							widget.slideUp(
								200,
								function() {
									this.remove();
								}
							);
						}
					}
				}
			);
		}
	};

	$(
		function() {
			new reduxWidgetAreas();
		}
	);
})( jQuery );
