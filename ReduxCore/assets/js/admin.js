/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @author      ReduxFramework Team
 * @version     3.0.9
 */

/**
 * global jQuery, document, redux_opts, confirm, relid:true, console, jsonView
 */
(function ($) {
	'use strict';
	$.redux = $.redux || {};

	var the_body = $("body");

	$(document).ready(function () {

		jQuery.fn.isOnScreen = function () {
			if (!window) {
				return;
			}
			var win = jQuery(window);
			var viewport = {
				top: win.scrollTop(),
				left: win.scrollLeft()
			};
			viewport.right = viewport.left + win.width();
			viewport.bottom = viewport.top + win.height();
			var bounds = this.offset();
			bounds.right = bounds.left + this.outerWidth();
			bounds.bottom = bounds.top + this.outerHeight();
			return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
		};

		$.redux.required();

		the_body.on('check_dependencies', function (event, variable) {
			$.redux.check_dependencies(event, variable);
		});
	});

	$.redux.required = function () {

		// Hide the fold elements on load ,
		// It's better to do this by PHP but there is no filter in tr tag , so is not possible
		// we going to move each attributes we may need for folding to tr tag
		$('.hiddenFold , .showFold').each(function () {
			var current = $(this),
				scope = current.parents('tr:eq(0)'),
				check_data = current.data();

			if (current.hasClass('hiddenFold')) {
				scope.addClass('hiddenFold').attr('data-check-field', check_data.checkField)
					.attr('data-check-comparison', check_data.checkComparison)
					.attr('data-check-value', check_data.checkValue)
					.attr('data-check-id', check_data.id).hide();
				//we clean here, so we won't get confuse 	
				current.removeClass('hiddenFold').removeAttr('data-check-field')
					.removeAttr('data-check-comparison')
					.removeAttr('data-check-value');
			} else {
				scope.attr('data-check-field', check_data.checkField)
					.attr('data-check-comparison', check_data.checkComparison)
					.attr('data-check-value', check_data.checkValue)
					.attr('data-check-id', check_data.id);
				//we clean here, so we won't get confuse 	
				current.removeClass('showFold').removeAttr('data-check-field')
					.removeAttr('data-check-comparison')
					.removeAttr('data-check-value');
			}
		});

		$(".fold").promise().done(function () {
			// Hide the fold elements on load
			$('.foldParent').each(function () {
				// in case of a radio input, take in consideration only the checked value
				if ($(this).attr('type') == 'radio' && $(this).attr('checked') != 'checked') {
					return;
				}
				var id = $(this).parents('.redux-field:first').data('id');
				if (redux_opts.folds[ id ]) {
					if (!redux_opts.folds[ id ].parent) {
						$.redux.verify_fold($(this));
					}
				}
			});
		});

		the_body.on('change', '#redux-main select, #redux-main radio, #redux-main input[type=checkbox], #redux-main input[type=hidden]', function (e) {
			$.redux.check_dependencies(e, this);
		});
	};

	$.redux.check_dependencies = function (e, variable) {

		var current = $(variable),
			scope = current.parents('.redux-group-tab:eq(0)');

		if (!scope.length) scope = the_body;

		// Fix for Checkbox + Required issue
		if ($(variable).prop('type') == "checkbox")
			$(variable).is(":checked") ? $(variable).val('1') : $(variable).val('0');

		var id = current.parents('.redux-field:first').data('id'),
			dependent = scope.find('tr[data-check-field="' + id + '"]'),
			value1 = variable.value,
			is_hidden = current.parents('tr:eq(0)').is('.hiddenFold');

		if (!dependent.length) return;

		dependent.each(function () {
			var current = $(this),
				check_data = current.data(),
				value2 = check_data.checkValue,
				show = false;

			if (!is_hidden) {
				switch (check_data.checkComparison) {
					case '=':
					case 'equals':
						//if value was array
						if (value2.toString().indexOf('|') !== -1) {
							var value2_array = value2.split('|');
							if ($.inArray(value1, value2_array) != -1) {
								show = true;
							}
						} else {
							if (value1 == value2)
								show = true;
						}
						break;
					case '!=':
					case 'not':
						//if value was array
						if (value2.indexOf('|') !== -1) {
							var value2_array = value2.split('|');
							if ($.inArray(value1, value2_array) == -1) {
								show = true;
							}
						} else {
							if (value1 != value2)
								show = true;
						}
						break;
					case '>':
					case 'greater':
					case 'is_larger':
						if (parseFloat(value1) > parseFloat(value2))
							show = true;
						break;
					case '<':
					case 'less':
					case 'is_smaller':
						if (parseFloat(value1) < parseFloat(value2))
							show = true;
						break;
					case 'contains':
						if (value1.indexOf(value2) != -1)
							show = true;
						break;
					case 'doesnt_contain':
						if (value1.indexOf(value2) == -1)
							show = true;
						break;
					case 'is_empty_or':
						if (value1 == "" || value1 == value2)
							show = true;
						break;
					case 'not_empty_and':
						if (value1 != "" && value1 != value2)
							show = true;
						break;
				}
			}

			/*if(show == true && current.is('.hiddenFold')){
			 current.css({
			 display:'none'
			 }).removeClass('hiddenFold').find('select, radio, input[type=checkbox]').trigger('change');
			 current.slideDown(300);
			 }else if(show == false  && !current.is('.hiddenFold')){
			 current.css({
			 display:''
			 }).addClass('hiddenFold').find('select, radio, input[type=checkbox]').trigger('change');
			 current.slideUp(300);
			 }*/
			$.redux.verify_fold($(variable));
		});
	};

	$.redux.verify_fold = function (item) {
		var id = item.parents('.redux-field:first').data('id');
		var itemVal = item.val();
		var scope = (item.parents('.redux-groups-accordion-group:first').length > 0) ? item.parents('.redux-groups-accordion-group:first') : item.parents('.redux-group-tab:eq(0)');

		if (redux_opts.folds[ id ]) {

			if (redux_opts.folds[ id ].children) {

				var theChildren = {};
				$.each(redux_opts.folds[ id ].children, function (index, value) {
					$.each(value, function (index2, value2) { // Each of the children for this value
						if (!theChildren[value2]) { // Create an object if it's not there
							theChildren[value2] = { show: false, hidden: false };
						}

						if (index == itemVal || theChildren[value2] === true) { // Check to see if it's in the criteria
							theChildren[value2].show = true;
						}

						if (theChildren[value2].show === true && scope.find('tr[data-check-id="' + id + '"]').hasClass("hiddenFold")) {
							theChildren[value2].show = false; // If this item is hidden, hide this child
						}

						if (theChildren[value2].show === true && scope.find('tr[data-check-id="' + redux_opts.folds[ id ].parent + '"]').hasClass('hiddenFold')) {
							theChildren[value2].show = false; // If the parent of the item is hidden, hide this child
						}
						// Current visibility of this child node
						theChildren[value2].hidden = scope.find('tr[data-check-id="' + value2 + '"]').hasClass("hiddenFold");
					});
				});

				$.each(theChildren, function (index) {

					var parent = scope.find('tr[data-check-id="' + index + '"]');

					if (theChildren[index].show === true) {

						parent.fadeIn('medium', function () {
							parent.removeClass('hiddenFold');
							if (redux_opts.folds[ index ] && redux_opts.folds[ index ].children) {
								// Now iterate the children
								$.redux.verify_fold(parent.find('select, radio, input[type=checkbox], input[type=hidden]'));
							}
						});

					} else if (theChildren[index].hidden === false) {

						parent.fadeOut('medium', function () {
							parent.addClass('hiddenFold');
							if (redux_opts.folds[ index ].children) {
								// Now iterate the children
								$.redux.verify_fold(parent.find('select, radio, input[type=checkbox], input[type=hidden]'));
							}
						});
					}
				});
			}
		}
	}

})(jQuery);

jQuery.noConflict();
var confirmOnPageExit = function (e) {
	//return; // ONLY FOR DEBUGGING
	// If we haven't been passed the event get the window.event
	e = e || window.event;
	var message = redux_opts.save_pending;
	// For IE6-8 and Firefox prior to version 4
	if (e) {
		e.returnValue = message;
	}
	window.onbeforeunload = null;
	// For Chrome, Safari, IE8+ and Opera 12+
	return message;
};

function verify_fold(item) {

	jQuery(document).ready(function ($) {


		if (item.hasClass('redux-info') || item.hasClass('redux-typography')) {
			return;
		}

		var id = item.parents('.redux-field:first').data('id');
		//console.log(id);
		var itemVal = item.val();

		if (redux_opts.folds[ id ]) {

			/*
			 if ( redux_opts.folds[ id ].parent && jQuery( '#' + redux_opts.folds[ id ].parent ).is('hidden') ) {
			 console.log('Going to parent: '+redux_opts.folds[ id ].parent+' for field: '+id);
			 //verify_fold( jQuery( '#' + redux_opts.folds[ id ].parent ) );
			 }
			 */
			if (redux_opts.folds[ id ].children) {
				//console.log('Children for: '+id);

				var theChildren = {};
				$.each(redux_opts.folds[ id ].children, function (index, value) {
					$.each(value, function (index2, value2) { // Each of the children for this value
						if (!theChildren[value2]) { // Create an object if it's not there
							theChildren[value2] = { show: false, hidden: false };
						}
						//console.log('id: '+id+' childID: '+value2+' parent value: '+index+' itemVal: '+itemVal);
						if (index == itemVal || theChildren[value2] === true) { // Check to see if it's in the criteria
							theChildren[value2].show = true;
							//console.log('theChildren['+value2+'].show = true');
						}

						if (theChildren[value2].show === true && jQuery('#' + id).parents("tr:first").hasClass("hiddenFold")) {
							theChildren[value2].show = false; // If this item is hidden, hide this child
							//console.log('set '+value2+' false');
						}

						if (theChildren[value2].show === true && jQuery('#' + redux_opts.folds[ id ].parent).hasClass('hiddenFold')) {
							theChildren[value2].show = false; // If the parent of the item is hidden, hide this child
							//console.log('set '+value2+' false2');
						}
						// Current visibility of this child node
						theChildren[value2].hidden = jQuery('#' + value2).parents("tr:first").hasClass("hiddenFold");
					});
				});

				//console.log(theChildren);

				$.each(theChildren, function (index) {

					var parent = jQuery('#' + index).parents("tr:first");

					if (theChildren[index].show === true) {
						//console.log('FadeIn '+index);

						parent.fadeIn('medium', function () {
							parent.removeClass('hiddenFold');
							if (redux_opts.folds[ index ] && redux_opts.folds[ index ].children) {
								//verify_fold(jQuery('#'+index)); // Now iterate the children
							}
						});

					} else if (theChildren[index].hidden === false) {
						//console.log('FadeOut '+index);

						parent.fadeOut('medium', function () {
							parent.addClass('hiddenFold');
							if (redux_opts.folds[ index ].children) {
								//verify_fold(jQuery('#'+index)); // Now iterate the children
							}
						});
					}
				});
			}
		}

	});
}

function redux_change(variable) {
	//We need this for switch and image select fields , jquery dosn't catch it on fly
	//if(variable.is('input[type=hidden]') || variable.hasClass('spinner-input') || variable.hasClass('slider-input') || variable.hasClass('upload') || jQuery(variable).parents('fieldset:eq(0)').is('.redux-container-image_select') ) {

	jQuery('body').trigger('check_dependencies', variable);
	//}

	if (variable.hasClass('compiler')) {
		jQuery('#redux-compiler-hook').val(1);
		//console.log('Compiler init');
	}


	if (variable.hasClass('foldParent')) {
		//verify_fold(variable);
	}
	window.onbeforeunload = confirmOnPageExit;
	if (jQuery(variable).parents('fieldset.redux-field:first').hasClass('redux-field-error')) {
		jQuery(variable).parents('fieldset.redux-field:first').removeClass('redux-field-error');
		jQuery(variable).parent().find('.redux-th-error').slideUp();
		var parentID = jQuery(variable).closest('.redux-group-tab').attr('id');
		var hideError = true;
		jQuery('#' + parentID + ' .redux-field-error').each(function () {
			hideError = false;
		});
		if (hideError) {
			jQuery('#' + parentID + '_li .redux-menu-error').hide();
			jQuery('#' + parentID + '_li .redux-group-tab-link-a').removeClass('hasError');
		}
	}
	jQuery('#redux-save-warn').slideDown();
}
jQuery(document).ready(function ($) {
	$('.redux-action_bar, .redux-presets-bar').on('click', function () {
		window.onbeforeunload = null;
	});
	/**    Tipsy @since v1.3 DEPRICATE? */
	if (jQuery().tipsy) {
		$('.tips').tipsy({
			fade: true,
			gravity: 's',
			opacity: 0.7
		});
	}

	$('#toplevel_page_' + redux_opts.slug + ' .wp-submenu a').click(function (e) {
		//if ( $(this).hasClass('wp-menu-open') ) {
		e.preventDefault();
		var url = $(this).attr('href').split('&tab=');
		$('#' + url[1] + '_section_group_li_a').click();
		console.log(url[1]);
		return false;
		//}
	});

	/**
	 Current tab checks, based on cookies
	 **/
	$('.redux-group-tab-link-a').click(function () {
		relid = $(this).data('rel'); // The group ID of interest
		// Set the proper page cookie
		$.cookie('redux_current_tab', relid, {
			expires: 7,
			path: '/'
		});

		$('#toplevel_page_' + redux_opts.slug + ' .wp-submenu a.current').removeClass('current');
		$('#toplevel_page_' + redux_opts.slug + ' .wp-submenu li.current').removeClass('current');

		$('#toplevel_page_' + redux_opts.slug + ' .wp-submenu a').each(function () {
			var url = $(this).attr('href').split('&tab=');
			if (url[1] == relid) {
				$(this).addClass('current');
				$(this).parent().addClass('current');
			}
		});

		// Remove the old active tab
		var oldid = $('.redux-group-tab-link-li.active .redux-group-tab-link-a').data('rel');
		$('#' + oldid + '_section_group_li').removeClass('active');
		// Show the group
		$('#' + oldid + '_section_group').hide();
		$('#' + relid + '_section_group').fadeIn(300, function () {
			stickyInfo(); // race condition fix
		});
		$('#' + relid + '_section_group_li').addClass('active');
	});
	// Get the URL parameter for tab

	function getURLParameter(name) {
		return decodeURI((new RegExp(name + '=' + '(.+?)(&|$)').exec(location.search) || [, ''])[1]);
	}

	// If the $_GET param of tab is set, use that for the tab that should be open
	var tab = getURLParameter('tab');
	if (tab !== "") {
		if ($.cookie("redux_current_tab_get") !== tab) {
			$.cookie('redux_current_tab', tab, {
				expires: 7,
				path: '/'
			});
			$.cookie('redux_current_tab_get', tab, {
				expires: 7,
				path: '/'
			});
			$('#' + tab + '_section_group_li').click();
		}
	} else if ($.cookie('redux_current_tab_get') !== "") {
		$.removeCookie('redux_current_tab_get');
	}
	var sTab = $('#' + $.cookie("redux_current_tab") + '_section_group_li_a');
	// Tab the first item or the saved one
	if ($.cookie("redux_current_tab") === null || typeof($.cookie("redux_current_tab")) === "undefined" || sTab.length === 0) {
		$('.redux-group-tab-link-a:first').click();
	} else {
		sTab.click();
	}
	// Default button clicked
	$('input[name="' + redux_opts.opt_name + '[defaults]"]').click(function () {
		if (!confirm(redux_opts.reset_confirm)) {
			return false;
		}
		window.onbeforeunload = null;
	});

	/**
	 * Open and close the left side menu panel
	 */
	$('#expand_options').click(function (e) {
		e.preventDefault();
		var trigger = $('#expand_options');
		var redux_main = $('#redux-main');
		var redux_sidebar = $('#redux-sidebar');

		var width = redux_sidebar.width();
		var id = $('#redux-group-menu').find('.active a').data('rel') + '_section_group';

		if (trigger.hasClass('expanded')) {
			trigger.removeClass('expanded');
			redux_main.removeClass('expand');

			//slide out and remove style
			redux_sidebar.stop().animate({
				'margin-left': '0px'
			}, 200,function () {
				$(this).removeAttr('style');
			}).toggle();

			//animate width and then remove style
			redux_main.stop().animate({
				'margin-left': width
			}, 200, function () {
				$(this).removeAttr('style');
			});

			$('.redux-group-tab').each(function () {
				if ($(this).attr('id') !== id) {
					$(this).fadeOut('fast');
				}
			});
			// Show the only active one
		} else {
			trigger.addClass('expanded');
			redux_main.addClass('expand');

			redux_sidebar.stop().animate({
				'margin-left': -width - 2
			}, 200).toggle();

			redux_main.stop().animate({
				'margin-left': '0px'
			}, 200);

			$('.redux-group-tab').fadeIn();
		}

		return false;
	});
	$('#redux-import').click(function (e) {
		if ($('#import-code-value').val() === "" && $('#import-link-value').val() === "") {
			e.preventDefault();
			return false;
		}
		return true;
	});
	if ($('#redux-save').is(':visible')) {
		$('#redux-save').slideDown();
	}
	if ($('#redux-imported').is(':visible')) {
		$('#redux-imported').slideDown();
	}
	$(document.body).on('change', 'input, textarea, select', function () {
		if (!$(this).hasClass('noUpdate')) {
			redux_change($(this));
		}
	});
	$('#redux-import-code-button').click(function () {
		if ($('#redux-import-link-wrapper').is(':visible')) {
			$('#redux-import-link-wrapper').fadeOut('fast');
			$('#import-link-value').val('');
		}
		$('#redux-import-code-wrapper').fadeIn('slow');
	});
	$('#redux-import-link-button').click(function () {
		if ($('#redux-import-code-wrapper').is(':visible')) {
			$('#redux-import-code-wrapper').fadeOut('fast');
			$('#import-code-value').val('');
		}
		$('#redux-import-link-wrapper').fadeIn('slow');
	});
	$('#redux-export-code-copy').click(function () {
		if ($('#redux-export-link-value').is(':visible')) {
			$('#redux-export-link-value').fadeOut('slow');
		}
		$('#redux-export-code').toggle('fade');
	});
	$('#redux-export-link').click(function () {
		if ($('#redux-export-code').is(':visible')) {
			$('#redux-export-code').fadeOut('slow');
		}
		$('#redux-export-link-value').toggle('fade');
	});

	/**
	 BEGIN Sticky footer bar
	 **/
	var stickyHeight = $('#redux-footer').height();
	$('#redux-sticky-padder').css({
		height: stickyHeight
	});

	function stickyInfo() {
		var stickyWidth = $('#info_bar').width() - 2;
		var redux_footer = $('#redux-footer');
		if (!$('#info_bar').isOnScreen() && !jQuery('#redux-footer-sticky').isOnScreen()) {
			redux_footer.css({
				position: 'fixed',
				bottom: '0',
				width: stickyWidth
			});
			redux_footer.addClass('sticky-footer-fixed');
			$('#redux-sticky-padder').show();
		} else {
			redux_footer.css({
				background: '#eee',
				position: 'inherit',
				bottom: 'inherit',
				width: 'inherit'
			});
			$('#redux-sticky-padder').hide();
			redux_footer.removeClass('sticky-footer-fixed');
		}
	}

	$(window).scroll(function () {
		stickyInfo();
	});
	$(window).resize(function () {
		stickyInfo();
	});
	$('#redux-save, #redux-imported').delay(4000).slideUp();
	$('#redux-field-errors').delay(8000).slideUp();
	$('.redux-save').click(function () {
		window.onbeforeunload = null;
	});
	/**
	 END Sticky footer bar
	 **/

	/**
	 BEGIN dev_mode commands
	 **/
	$('#consolePrintObject').on('click', function () {
		console.log($.parseJSON($("#redux-object-json").html()));
	});

	if (typeof jsonView === 'function') {
		jsonView('#redux-object-json', '#redux-object-browser');
	}
	/**
	 END dev_mode commands
	 **/

	/**
	 BEGIN error and warning notices
	 **/
	// Display errors on page load
	if (redux_opts.errors !== undefined) {
		var rfe = $("#redux-field-errors");
		rfe.find("span").html(redux_opts.errors.total);
		rfe.show();
		$.each(redux_opts.errors.errors, function (sectionID, sectionArray) {
			$("#" + sectionID + "_section_group_li_a")
				.prepend('<span class="redux-menu-error">' + sectionArray.total + '</span>')
				.addClass("hasError");
			$.each(sectionArray.errors, function (key, value) {
				console.log(value);
				$("#" + redux_opts.opt_name + '-' + value.id)
					.addClass("redux-field-error")
					.append('<div class="redux-th-error">' + value.msg + '</div>');
			});
		});
	}
	// Display warnings on page load
	if (redux_opts.warnings !== undefined) {
		var rfw = $("#redux-field-warnings");
		rfw.find("span").html(redux_opts.warnings.total);
		rfw.show();
		$.each(redux_opts.warnings.warnings, function (sectionID, sectionArray) {
			$("#" + sectionID + "_section_group_li_a")
				.prepend('<span class="redux-menu-warning">' + sectionArray.total + '</span>')
				.addClass("hasWarning");
			$.each(sectionArray.warnings, function (key, value) {
				$("#" + redux_opts.opt_name + '-' + value.id)
					.addClass("redux-field-warning")
					.append('<div class="redux-th-warning">' + value.msg + '</div>');
			});
		});
	}
	/**
	 END error and warning notices
	 **/


	/**
	 BEGIN Control the tabs of the site to the left. Eventually (perhaps) across the top too.
	 **/
	 //jQuery( ".redux-section-tabs" ).tabs();
	$('.redux-section-tabs div').hide();
	$('.redux-section-tabs div:first').show();
	$('.redux-section-tabs ul li:first').addClass('active');

	$('.redux-section-tabs ul li a').click(function () {
		$('.redux-section-tabs ul li').removeClass('active');
		$(this).parent().addClass('active');
		var currentTab = $(this).attr('href');
		$('.redux-section-tabs div').hide();
		$(currentTab).fadeIn();
		return false;
	});
	/**
	 END Control the tabs of the site to the left. Eventually (perhaps) across the top too.
	 **/


});
