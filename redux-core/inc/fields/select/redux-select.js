/*global redux_change, redux, ajaxurl*/

(function( $ ) {
	'use strict';

	redux.field_objects        = redux.field_objects || {};
	redux.field_objects.select = redux.field_objects.select || {};

	redux.field_objects.select.init = function( selector ) {
		selector = $.redux.getSelector( selector, 'select' );

		$( selector ).each(
			function() {
				var default_params = {};
				var el             = $( this );
				var parent         = el;

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

				el.find( 'select.redux-select-item' ).each(
					function() {
						var action;
						var nonce;
						var wpdata;
						var min;
						var data_args;

						if ( $( this ).hasClass( 'font-icons' ) ) {
							default_params = $.extend(
								{},
								{
									templateResult: redux.field_objects.select.addIcon,
									templateSelection: redux.field_objects.select.addIcon,
									escapeMarkup: function( m ) {
										return m;
									}
								},
								default_params
							);
						}
						if ( $( this ).data( 'ajax' ) ) {
							action = $( this ).data( 'action' );
							nonce = $( this ).data( 'nonce' );
							wpdata = $( this ).data( 'wp-data' );
							min = $( this ).data( 'min-input-length' );
							data_args = {};
							if ( $( this ).data( 'args' ) ) {
								data_args = JSON.stringify( $( this ).data( 'args' ) );
							}

							if ( 'true' === min ) {
								min = 1;
							}

							default_params = {
								minimumInputLength: min,
								ajax: {
									url: ajaxurl,
									dataType: 'json',
									delay: 250,
									data: function( params ) {
										return {
											nonce: nonce,
											data: wpdata,
											q: params.term,
											page: params.page || 1,
											action: action,
											data_args: data_args
										};
									},
									processResults: function( data, params ) {
										params.page = params.page || 1;

										if ( true === data.success ) {
											return {
												results: data.data // ,
												// We'll revisit this later.
												// pagination: {
												// more: ( params.page * 20 ) < data.data.length
												// }.
											};
										} else if ( false === data.success ) {
											alert( data.data );

											return {
												results: data.data
											};
										}
									},
									cache: true
								}
							};
						}

						$( this ).select2( default_params );

						el.find( '.select2-search__field' ).width( 'auto' );

						if ( $( this ).hasClass( 'select2-sortable' ) ) {
							default_params                 = {};
							default_params.bindOrder       = 'sortableStop';
							default_params.sortableOptions = { placeholder: 'ui-state-highlight' };

							$( this ).select2Sortable( default_params );
						}

						$( this ).on(
							'change',
							function() {
								redux_change( $( $( this ) ) );
								$( this ).select2SortableOrder();
							}
						);
					}
				);
			}
		);
	};

	redux.field_objects.select.addIcon = function( icon ) {
		if ( icon.hasOwnProperty( 'id' ) ) {
			return '<span class="elusive"><i class="' + icon.id + '"></i>&nbsp;&nbsp;' + icon.text + '</span>';
		}
	};
})( jQuery );
