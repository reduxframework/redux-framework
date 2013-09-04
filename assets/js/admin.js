/*global $, jQuery, document, tabid:true, redux_opts, confirm, relid:true*/
/*
jQuery(document).ready(function () {

    if (jQuery('#last_tab').val() === '') {
        jQuery('.redux-opts-group-tab:first').slideDown('fast');
        jQuery('#redux-opts-group-menu li:first').addClass('active');
    } else {
        tabid = jQuery('#last_tab').val();
        jQuery('#' + tabid + '_section_group').slideDown('fast');
        jQuery('#' + tabid + '_section_group_li').addClass('active');
    }

    jQuery('input[name="' + redux_opts.opt_name + '[defaults]"]').click(function () {
        if (!confirm(redux_opts.reset_confirm)) {
            return false;
        }
    });

    jQuery('.redux-opts-group-tab-link-a').click(function () {
        relid = jQuery(this).attr('data-rel');

        jQuery('#last_tab').val(relid);

        jQuery('.redux-opts-group-tab').each(function () {
            if (jQuery(this).attr('id') === relid + '_section_group') {
                jQuery(this).delay(400).fadeIn(1200);
            } else {
                jQuery(this).fadeOut('fast');
            }
        });

        jQuery('.redux-opts-group-tab-link-li').each(function () {
            if (jQuery(this).attr('id') !== relid + '_section_group_li' && jQuery(this).hasClass('active')) {
                jQuery(this).removeClass('active');
            }
            if (jQuery(this).attr('id') === relid + '_section_group_li') {
                jQuery(this).addClass('active');
            }
        });
    });

    if (jQuery('#redux-opts-save').is(':visible')) {
        jQuery('#redux-opts-save').delay(4000).slideUp('slow');
    }

    if (jQuery('#redux-opts-imported').is(':visible')) {
        jQuery('#redux-opts-imported').delay(4000).slideUp('slow');
    }

    jQuery('#redux-opts-form-wrapper').on('change', 'input, textarea, select', function () {
        if(this.id === 'google_webfonts' && this.value === '') return;
        jQuery('#redux-opts-save-warn').slideDown('slow');
    });

    jQuery('#redux-opts-import-code-button').click(function () {
        if (jQuery('#redux-opts-import-link-wrapper').is(':visible')) {
            jQuery('#redux-opts-import-link-wrapper').fadeOut('fast');
            jQuery('#import-link-value').val('');
        }
        jQuery('#redux-opts-import-code-wrapper').fadeIn('slow');
    });

    jQuery('#redux-opts-import-link-button').click(function () {
        if (jQuery('#redux-opts-import-code-wrapper').is(':visible')) {
            jQuery('#redux-opts-import-code-wrapper').fadeOut('fast');
            jQuery('#import-code-value').val('');
        }
        jQuery('#redux-opts-import-link-wrapper').fadeIn('slow');
    });

    jQuery('#redux-opts-export-code-copy').click(function () {
        if (jQuery('#redux-opts-export-link-value').is(':visible')) {jQuery('#redux-opts-export-link-value').fadeOut('slow'); }
        jQuery('#redux-opts-export-code').toggle('fade');
    });

    jQuery('#redux-opts-export-link').click(function () {
        if (jQuery('#redux-opts-export-code').is(':visible')) {jQuery('#redux-opts-export-code').fadeOut('slow'); }
        jQuery('#redux-opts-export-link-value').toggle('fade');
    });
});


*/

// DOVY!

jQuery.noConflict();
/*global $, jQuery, document, tabid:true, redux_opts, confirm, relid:true*/

jQuery('.redux-action_bar, .redux-presets-bar').click(function() {
	window.onbeforeunload = null;
});

function verify_fold(variable) {
	jQuery(document).ready(function($){		
		// Hide errors if the user changed the field
		
		if (variable.hasClass('fold')) {
			var varVisible = jQuery('#'+variable.attr('id')).closest('td').is(":visible");
			var data = variable.data();
			var fold = variable.attr('data-fold').split(',');
			var value = variable.val();
			jQuery.each(fold,function(n){
				var theData = variable.data(fold[n]);
				var hide = false;
				if( theData == value ) {
    			hide = true;
				}
				if (theData instanceof String) {
					if (theData.indexOf(",") != -1) {
						theData = theData.split(",");
					} else {
						theData = theData.split();
					}
				}
				if (!hide && jQuery.inArray(value, theData) != -1) {
					hide = true;
				} 
				var foldChild = jQuery('#'+fold[n]);

				if ( !hide && varVisible ) {
					jQuery('#foldChild-'+fold[n]).parent().parent().fadeIn('medium', function() {
						if (foldChild.hasClass('fold')) {
							verify_fold(foldChild);
						}
					});					
				} else {
					jQuery('#foldChild-'+fold[n]).parent().parent().fadeOut('medium', function() {						
						if (foldChild.hasClass('fold')) {
							verify_fold(foldChild);
						}
					});					
				}
			});
		}
	});
}

function redux_change(variable) {
//console.log('value changed!');
	if (variable.hasClass('compiler')) {
		jQuery('#redux-compiler-hook').val(1);
		//console.log('Compiler init');
	}
	
	window.onbeforeunload = confirmOnPageExit;
	jQuery(document).ready(function($){		
		verify_fold(variable); // Verify if the variable is visible
		if (jQuery(this).hasClass('redux-field-error')) {
			jQuery(this).removeClass('redux-field-error');
			jQuery(this).parent().find('.redux-th-error').slideUp();
			var parentID = jQuery(this).closest('.redux-group-tab').attr('id');
			var hideError = true;
			jQuery('#'+parentID+' .redux-field-error').each(function() {
				hideError = false;
			});
			if (hideError) {
				jQuery('#'+parentID+'_li .redux-menu-error').hide();
			}			
		}
		jQuery('#redux-save-warn').slideDown();	
	});	
}


var confirmOnPageExit = function (e) {
    // If we haven't been passed the event get the window.event
    e = e || window.event;

    var message = redux_opts.save_pending;

    // For IE6-8 and Firefox prior to version 4
    if (e) 
    {
        e.returnValue = message;
    }

    // For Chrome, Safari, IE8+ and Opera 12+
    return message;
};


jQuery(document).ready(function($){

/**	Tipsy @since v1.3 */
if (jQuery().tipsy) {
	$('.tips').tipsy({
		fade: true,
		gravity: 's',
		opacity: 0.7,
	});
}	

var confirmOnPageExit = function (e) {
    // If we haven't been passed the event get the window.event
    e = e || window.event;

    var message = redux_opts.save_pending;

    // For IE6-8 and Firefox prior to version 4
    if (e) 
    {
        e.returnValue = message;
    }

    // For Chrome, Safari, IE8+ and Opera 12+
    return message;
};
	
	/**
		Unfolding elements. Used by switch, checkbox, select
	**/
	//(un)fold options in a checkbox-group
	jQuery('.fld').click(function() {
  	var $fold='.f_'+this.id;
  	$($fold).slideToggle('normal', "swing");
	});
	// (un)fold options where the id equals the value
	jQuery('.fld-parent').change(function() {
  	var $fold='.f_'+this.id+"-"+this.val();
  	$($fold).slideToggle('normal', "swing");
	});

	/**
		Current tab checks, based on cookies
	**/
	jQuery('.redux-group-tab-link-a').click(function(){
		relid = jQuery(this).data('rel'); // The group ID of interest

		$('#'+relid).children('.fold').each(function() {
			verify_fold(jQuery(this));
		});


		// Set the proper page cookie
		$.cookie('redux_current_tab', relid, { expires: 7, path: '/' });	
		// Remove the old active tab
		oldid = jQuery('.redux-group-tab-link-li.active .redux-group-tab-link-a').data('rel');

		jQuery('#'+oldid+'_section_group_li').removeClass('active');

		// Show the group
		jQuery('#'+oldid+'_section_group').hide();
		jQuery('#'+relid+'_section_group').fadeIn(300, function() {
			stickyInfo();// race condition fix
		});

		jQuery('#'+relid+'_section_group_li').addClass('active');
	});

	// Get the URL parameter for tab
	function getURLParameter(name) {
	    return decodeURI(
	        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,''])[1]
	    );
	}
	
	// If the $_GET param of tab is set, use that for the tab that should be open
	var tab = getURLParameter('tab');
	if (tab != "") {
		if ($.cookie("redux_current_tab_get") != tab) {
			$.cookie('redux_current_tab', tab, { expires: 7, path: '/' });	
			$.cookie('redux_current_tab_get', tab, { expires: 7, path: '/' });
			jQuery('#'+tab+'_section_group_li').click();
		}
	} else if ($.cookie('redux_current_tab_get') != "") {
		$.removeCookie('redux_current_tab_get');
	}

	var sTab = jQuery('#'+$.cookie("redux_current_tab")+'_section_group_li_a');
	// Tab the first item or the saved one
	if($.cookie("redux_current_tab") === null || typeof($.cookie("redux_current_tab")) == "undefined" || sTab.length == 0){
		jQuery('.redux-group-tab-link-a:first').click();
	}else{
		sTab.click();
	}


	
	// Default button clicked
	jQuery('input[name="'+redux_opts.opt_name+'[defaults]"]').click(function(){
		if(!confirm(redux_opts.reset_confirm)){
			return false;
		}
		window.onbeforeunload = null;

	});
	

	
	

	jQuery('#expand_options').click(function(e) {
		e.preventDefault();
		
		var trigger = jQuery('#expand_options');
		var width = jQuery('#redux-sidebar').width();
		var id = jQuery('#redux-group-menu .active a').data('rel')+'_section_group';
		
		if (trigger.hasClass('expanded')) {
			trigger.removeClass('expanded');
			jQuery('#redux-main').removeClass('expand');
			jQuery('#redux-sidebar').stop().animate({'margin-left':'0px'},500);
			jQuery('#redux-main').stop().animate({'margin-left':width},500);

			

			jQuery('.redux-group-tab').each(function(){
					if(jQuery(this).attr('id') != id){
						jQuery(this).fadeOut('fast');
					}
			});
			// Show the only active one

		} else {
			trigger.addClass('expanded');
			jQuery('#redux-main').addClass('expand');
			jQuery('#redux-sidebar').stop().animate({'margin-left':-width-2},500);
			jQuery('#redux-main').stop().animate({'margin-left':'0px'},500);	
			jQuery('.redux-group-tab').fadeIn();

		}
		return false;
	});	
	
	jQuery('#redux-import').click(function(e) {
		if (jQuery('#import-code-value').val() == "" && jQuery('#import-link-value').val() == "" ) {
			e.preventDefault();
			return false;
		}
	});

	
	if(jQuery('#redux-save').is(':visible')){
		jQuery('#redux-save').slideDown();
	}
	
	if(jQuery('#redux-imported').is(':visible')){
		jQuery('#redux-imported').slideDown();
	}	
	
	jQuery('input, textarea, select').on('change',function() {
		if (!jQuery(this).hasClass('noUpdate')) {
			redux_change(jQuery(this));	
		}
	});
	

	
	jQuery('#redux-import-code-button').click(function(){
		if(jQuery('#redux-import-link-wrapper').is(':visible')){
			jQuery('#redux-import-link-wrapper').fadeOut('fast');
			jQuery('#import-link-value').val('');
		}
		jQuery('#redux-import-code-wrapper').fadeIn('slow');
	});
	
	jQuery('#redux-import-link-button').click(function(){
		if(jQuery('#redux-import-code-wrapper').is(':visible')){
			jQuery('#redux-import-code-wrapper').fadeOut('fast');
			jQuery('#import-code-value').val('');
		}
		jQuery('#redux-import-link-wrapper').fadeIn('slow');
	});
	
	jQuery('#redux-export-code-copy').click(function(){
		if(jQuery('#redux-export-link-value').is(':visible')){jQuery('#redux-export-link-value').fadeOut('slow');}
		jQuery('#redux-export-code').toggle('fade');
	});
	
	jQuery('#redux-export-link').click(function(){
		if(jQuery('#redux-export-code').is(':visible')){jQuery('#redux-export-code').fadeOut('slow');}
		jQuery('#redux-export-link-value').toggle('fade');
	});
	
	
jQuery.fn.isOnScreen = function(){
    
    var win = jQuery(window);
    
    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();
    
    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();
    
    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
    
};


/**
	Show the sticky header bar and notes!
**/
  var stickyHeight = jQuery('#redux-footer').height();
  jQuery('#redux-sticky-padder').css({height: stickyHeight});

  function stickyInfo() {
  	var stickyWidth = jQuery('#info_bar').width()-2;
    if( !jQuery('#info_bar').isOnScreen() && !jQuery('#redux-footer-sticky').isOnScreen()) {
        jQuery('#redux-footer').css({position: 'fixed', bottom: '0', width: stickyWidth});
        jQuery('#redux-footer').addClass('sticky-footer-fixed');
        jQuery('#redux-sticky-padder').show();
    } else {
    		jQuery('#redux-footer').css({background: '#eee',position: 'inherit', bottom: 'inherit', width: 'inherit' });
    		jQuery('#redux-sticky-padder').hide();
    		jQuery('#redux-footer').removeClass('sticky-footer-fixed');
    }  	
  }  
  jQuery(window).scroll(function(){
		stickyInfo();
  });
  jQuery(window).resize(function(){
		stickyInfo();
  });

	
  jQuery('#redux-save, #redux-imported').delay(4000).slideUp();
  jQuery('#redux-field-errors').delay(5000).slideUp();


  jQuery('.redux-save').click(function() {
  	window.onbeforeunload = null;
  });

	jQuery('.fold-data').each(function() {
		var id = jQuery(this).attr('id').replace("foldChild-","");
		var foldata = jQuery(this).attr('id');
		var data = jQuery(this).val(); // Items that make this element fold
		var split = "";

		if (data.indexOf(",") != -1) {
			split = data.split(',');
		} else {
			split = data.split();
		}		

		jQuery.each(split,function(n){
			var fid = jQuery('#'+split[n]); // ID of the unit that causes a fold
			fid.addClass('fold'); // Add the fold class
			var ndata = jQuery('#'+foldata).attr('data-'+split[n]); // The values of fid that cause the fold
			if (fid.attr('data-'+id)) { // If this fold object already has values that cause a fold
				ndata = fid.attr('data-'+id)+","+ndata;
			}		
			fid.attr('data-'+id, ndata);

			// This is where we say, these are the elements that cause you to hide!
			var fold = "";
			var fdata = jQuery('#'+split[n]);

			var currentData = jQuery('#'+split[n]).attr('data-fold');
			if (typeof(fdata) !== 'undefined' && typeof(currentData) !== 'undefined') {
				fold += jQuery('#'+split[n]).attr('data-fold'); // All what's already there	
			}
			if (fold != "") {
				fold += ",";
			}
			fold += id;

			jQuery('#'+split[n]).attr('data-fold', fold);		
			verify_fold(jQuery('#'+split[n]));
			
    });
	});  
	


	// Markdown Viewer for Theme Documentation
	if ($('#theme_docs_section_group').length != 0) {
		var converter = new Showdown.converter();
		var text = jQuery('#theme_docs_section_group').html();
		text = converter.makeHtml(text);
		jQuery('#theme_docs_section_group').html(text);
	}

});



