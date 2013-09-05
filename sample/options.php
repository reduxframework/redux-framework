<?php

/*
 *
 * Require the framework class before doing anything else, so we can use the defined URLs and directories.
 * If you are running on Windows you may have URL problems which can be fixed by defining the framework url first.
 *
 */
//define('REDUX_URL', site_url('path the options folder'));
if(!class_exists('Redux_Framework')){
    require_once(dirname(__FILE__) . '/../framework.php');
}

/*
 *
 * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for URLs, and directories will NOT be available at this point in a child theme,
 * so you must use get_template_directory_uri() if you want to use any of the built in icons
 *
 */
function add_another_section($sections){
    //$sections = array();
    $sections[] = array(
        'title' => __('A Section added by hook', 'redux-framework'),
        'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework'),
		'icon' => 'paper-clip',
		'icon_class' => 'icon-large',
        // Leave this as a blank section, no options just some intro text set above.
        'fields' => array()
    );

    return $sections;
}
//add_filter('redux-opts-sections-twenty_eleven', 'add_another_section');


/*
 * 
 * Custom function for filtering the args array given by a theme, good for child themes to override or add to the args array.
 *
 */
function change_framework_args($args){
    //$args['dev_mode'] = false;
    
    return $args;
}
//add_filter('redux-opts-args-twenty_eleven', 'change_framework_args');


/*
 *
 * Most of your editing will be done in this section.
 *
 * Here you can override default values, uncomment args and change their values.
 * No $args are required, but they can be over ridden if needed.
 *
 */
function setup_framework_options(){
    $args = array();


    // For use with a tab below
		$tabs = array();
    if (function_exists('wp_get_theme')){
        $theme_data = wp_get_theme();
        $item_name = $theme_data->get('Name'); 
        $item_uri = $theme_data->get('ThemeURI');
        $description = $theme_data->get('Description');
        $author = $theme_data->get('Author');
        $author_uri = $theme_data->get('AuthorURI');
        $version = $theme_data->get('Version');
        $tags = $theme_data->get('Tags');
    }else{
        $theme_data = get_theme_data(trailingslashit(get_stylesheet_directory()) . 'style.css');
        $item_name = $theme_data['Name']; 
        $item_uri = $theme_data['URI'];
        $description = $theme_data['Description'];
        $author = $theme_data['Author'];
        $author_uri = $theme_data['AuthorURI'];
        $version = $theme_data['Version'];
        $tags = $theme_data['Tags'];
     }
    
    $item_info = '<div class="redux-opts-section-desc">';
    $item_info .= '<p class="redux-opts-item-data description item-uri">' . __('<strong>Theme URL:</strong> ', 'redux-framework') . '<a href="' . $item_uri . '" target="_blank">' . $item_uri . '</a></p>';
    $item_info .= '<p class="redux-opts-item-data description item-author">' . __('<strong>Author:</strong> ', 'redux-framework') . ($author_uri ? '<a href="' . $author_uri . '" target="_blank">' . $author . '</a>' : $author) . '</p>';
    $item_info .= '<p class="redux-opts-item-data description item-version">' . __('<strong>Version:</strong> ', 'redux-framework') . $version . '</p>';
    $item_info .= '<p class="redux-opts-item-data description item-description">' . $description . '</p>';
    $item_info .= '<p class="redux-opts-item-data description item-tags">' . __('<strong>Tags:</strong> ', 'redux-framework') . implode(', ', $tags) . '</p>';
    $item_info .= '</div>';    

    // Setting dev mode to true allows you to view the class settings/info in the panel.
    // Default: true
    //$args['dev_mode'] = true;

	// Set the icon for the dev mode tab.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	// If $args['icon_type'] = 'iconfont', this should be the icon name.
	// Default: info-sign
	//$args['dev_mode_icon'] = 'info-sign';

	// Set the class for the dev mode tab icon.
	// This is ignored unless $args['icon_type'] = 'iconfont'
	// Default: null
	$args['dev_mode_icon_class'] = 'icon-large';

	$theme = wp_get_theme();

	$args['display_name'] = $item_name;
	$args['display_version'] = $version;

    // If you want to use Google Webfonts, you MUST define the api key.
    $args['google_api_key'] = 'AIzaSyAX_2L_UzCDPEnAHTG7zhESRVpMPS4ssII';

    // Define the starting tab for the option panel.
    // Default: '0';
    //$args['last_tab'] = '0';

    // Define the option panel stylesheet. Options are 'standard', 'custom', and 'none'
    // If only minor tweaks are needed, set to 'custom' and override the necessary styles through the included custom.css stylesheet.
    // If replacing the stylesheet, set to 'none' and don't forget to enqueue another stylesheet!
    // Default: 'standard'
    //$args['admin_stylesheet'] = 'standard';

    // Add HTML before the form.
    $args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework');

    // Add content after the form.
    $args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework');

    // Set footer/credit line.
    //$args['footer_credit'] = __('<p>This text is displayed in the options panel footer across from the WordPress version (where it normally says \'Thank you for creating with WordPress\'). This field accepts all HTML.</p>', 'redux-framework');

    // Setup custom links in the footer for share icons
    $args['share_icons']['twitter'] = array(
        'link' => 'http://twitter.com/ghost1227',
        'title' => 'Follow me on Twitter', 
        'img' => REDUX_URL . 'assets/img/social/Twitter.png'
    );
    $args['share_icons']['linked_in'] = array(
        'link' => 'http://www.linkedin.com/profile/view?id=52559281',
        'title' => 'Find me on LinkedIn', 
        'img' => REDUX_URL . 'assets/img/social/LinkedIn.png'
    );

    // Enable the import/export feature.
    // Default: true
    //$args['show_import_export'] = false;

	// Set the icon for the import/export tab.
	// If $args['icon_type'] = 'image', this should be the path to the icon.
	// If $args['icon_type'] = 'iconfont', this should be the icon name.
	// Default: refresh
	//$args['import_icon'] = 'refresh';

	// Set the class for the import/export tab icon.
	// This is ignored unless $args['icon_type'] = 'iconfont'
	// Default: null
	$args['import_icon_class'] = 'icon-large';

    // Set a custom option name. Don't forget to replace spaces with underscores!
    $args['opt_name'] = 'twenty_eleven2';

    // Set a custom menu icon.
    //$args['menu_icon'] = '';

    // Set a custom title for the options page.
    // Default: Options
    $args['menu_title'] = __('Options', 'redux-framework');

    // Set a custom page title for the options page.
    // Default: Options
    $args['page_title'] = __('Options', 'redux-framework');

    // Set a custom page slug for options page (wp-admin/themes.php?page=***).
    // Default: redux_options
    $args['page_slug'] = 'redux_options';

    // Set a custom page capability.
    // Default: manage_options
    //$args['page_cap'] = 'manage_options';

    // Set the menu type. Set to "menu" for a top level menu, or "submenu" to add below an existing item.
    // Default: menu
    //$args['page_type'] = 'submenu';

    // Set the parent menu.
    // Default: themes.php
    // A list of available parent menus is available at http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    //$args['page_parent'] = 'options_general.php';

    // Set a custom page location. This allows you to place your menu where you want in the menu order.
    // Must be unique or it will override other items!
    // Default: null
    //$args['page_position'] = null;

    // Set a custom page icon class (used to override the page icon next to heading)
    //$args['page_icon'] = 'icon-themes';

	// Set the icon type. Set to "iconfont" for Font Awesome, or "image" for traditional.
	// Redux no longer ships with standard icons!
	// Default: iconfont
	//$args['icon_type'] = 'image';

    // Disable the panel sections showing as submenu items.
    // Default: true
    //$args['allow_sub_menu'] = false;
        
    // Set ANY custom page help tabs, displayed using the new help tab API. Tabs are shown in order of definition.
    $args['help_tabs'][] = array(
        'id' => 'redux-opts-1',
        'title' => __('Theme Information 1', 'redux-framework'),
        'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework')
    );
    $args['help_tabs'][] = array(
        'id' => 'redux-opts-2',
        'title' => __('Theme Information 2', 'redux-framework'),
        'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework')
    );

    // Set the help sidebar for the options page.                                        
    $args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework');

    $sections = array();

    $sections[] = array(
		// Redux uses the Font Awesome iconfont to supply its default icons.
		// If $args['icon_type'] = 'iconfont', this should be the icon name minus 'icon-'.
		// If $args['icon_type'] = 'image', this should be the path to the icon.
		'icon' => 'paper-clip',
		// Set the class for this icon.
		// This field is ignored unless $args['icon_type'] = 'iconfont'
		'icon_class' => 'icon-large',
        'title' => __('Getting Started', 'redux-framework'),
		'desc' => __('<p class="description">This is the description field for this section. HTML is allowed</p>', 'redux-framework'),
        // Lets leave this as a blank section, no options just some intro text set above.
        //'fields' => array()
    );

    $sections[] = array(
		'icon' => 'edit',
		'icon_class' => 'icon-large',
        'title' => __('Text Fields', 'redux-framework'),
        'desc' => __('<p class="description">This is the description field for this section. Again HTML is allowed2</p>', 'redux-framework'),
        'fields' => array(
            array(
                'id' => '1', // The item ID must be unique
                'type' => 'text', // Built-in field types include:
                // text, textarea, editor, checkbox, multi_checkbox, radio, images, button_set,
                // select, multi_select, color, date, divide, info, upload
                'title' => __('Text Option', 'redux-framework'),
                'subtitle' => __('This is a little space under the field title which can be used for additonal info.', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                //'validate' => '', // Built-in validation includes: 
                //  email, html, html_custom, no_html, js, numeric, comma_numeric, url, str_replace, preg_replace
                //'msg' => 'custom error message', // Override the default validation error message for specific fields
                //'std' => '', // This is the default value and is used to set an option on theme activation.
                //'class' => '' // Set custom classes for elements if you want to do something a little different
                //'rows' => '6' // Set the number of rows shown for the textarea. Default: 6
			),
            array(
                'id' => '2',
                'type' => 'text',
                'title' => __('Text Option - Email Validated', 'redux-framework'),
                'subtitle' => __('This is a little space under the field title which can be used for additonal info.', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate' => 'email',
                'msg' => 'custom error message',
                'std' => 'test@test.com'
            ),
            array(
                'id' => 'password',
                'type' => 'password',
                'title' => __('Password Option', 'redux-framework'),
                'subtitle' => __('This is a little space under the field title which can be used for additonal info.', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework')
            ),
            array(
                'id' => 'multi_text',
                'type' => 'multi_text',
                'title' => __('Multi Text Option', 'redux-framework'),
                'subtitle' => __('This is a little space under the field title which can be used for additonal info.', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
            ),
            array(
                'id' => '3',
                'type' => 'text',
                'title' => __('Text Option - URL Validated', 'redux-framework'),
                'subtitle' => __('This must be a URL.', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate' => 'url',
                'std' => 'http://reduxframework.com'
            ),
            array(
                'id' => '4',
                'type' => 'text',
                'title' => __('Text Option - Numeric Validated', 'redux-framework'),
                'subtitle' => __('This must be numeric.', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate' => 'numeric',
                'std' => '0',
                'class' => 'small-text'
            ),
            array(
                'id' => 'comma_numeric',
                'type' => 'text',
                'title' => __('Text Option - Comma Numeric Validated', 'redux-framework'),
                'subtitle' => __('This must be a comma seperated string of numerical values.', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate' => 'comma_numeric',
                'std' => '0',
                'class' => 'small-text'
            ),
            array(
                'id' => 'no_special_chars',
                'type' => 'text',
                'title' => __('Text Option - No Special Chars Validated', 'redux-framework'),
                'subtitle' => __('This must be a alpha numeric only.', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate' => 'no_special_chars',
                'std' => '0'
            ),
            array(
                'id' => 'str_replace',
                'type' => 'text',
                'title' => __('Text Option - Str Replace Validated', 'redux-framework'),
                'subtitle' => __('You decide.', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate' => 'str_replace',
                'str' => array('search' => ' ', 'replacement' => 'thisisaspace'),
                'std' => '0'
            ),
            array(
                'id' => 'preg_replace',
                'type' => 'text',
                'title' => __('Text Option - Preg Replace Validated', 'redux-framework'),
                'subtitle' => __('You decide.', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate' => 'preg_replace',
                'preg' => array('pattern' => '/[^a-zA-Z_ -]/s', 'replacement' => 'no numbers'),
                'std' => '0'
            ),
            array(
                'id' => 'custom_validate',
                'type' => 'text',
                'title' => __('Text Option - Custom Callback Validated', 'redux-framework'),
                'subtitle' => __('You decide.', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate_callback' => 'validate_callback_function',
                'std' => '0'
            ),
            array(
                'id' => '5',
                'type' => 'textarea',
                'title' => __('Textarea Option - No HTML Validated', 'redux-framework'), 
                'subtitle' => __('All HTML will be stripped', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate' => 'no_html',
                'std' => 'No HTML is allowed in here.'
            ),
            array(
                'id' => '6',
                'type' => 'textarea',
                'title' => __('Textarea Option - HTML Validated', 'redux-framework'), 
                'subtitle' => __('HTML Allowed', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate' => 'html', // See http://codex.wordpress.org/Function_Reference/wp_kses_post
                'std' => 'HTML is allowed in here.'
            ),
            array(
                'id' => '7',
                'type' => 'textarea',
                'title' => __('Textarea Option - HTML Validated Custom', 'redux-framework'), 
                'subtitle' => __('Custom HTML Allowed', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate' => 'html_custom',
                'std' => 'Some HTML is allowed in here.',
                'allowed_html' => array('') // See http://codex.wordpress.org/Function_Reference/wp_kses
            ),
            array(
                'id' => '8',
                'type' => 'textarea',
                'title' => __('Textarea Option - JS Validated', 'redux-framework'), 
                'subtitle' => __('JS will be escaped', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'validate' => 'js'
            ),
            array(
                'id' => '9',
                'type' => 'editor',
                'title' => __('Editor Option', 'redux-framework'),
                'subtitle' => __('Can also use the validation methods if required', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'std' => 'OOOOOOhhhh, rich editing.',
            ),
            array(
                'id' => 'editor2',
                'type' => 'editor',
                'title' => __('Editor Option 2', 'redux-framework'), 
                'subtitle' => __('Can also use the validation methods if required', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'std' => 'OOOOOOhhhh, rich editing with auto paragraphs disabled.',
                'autop' => false
            )
        )
    );
    
    $sections[] = array(
		'icon' => 'check',
		'icon_class' => 'icon-large',
        'title' => __('Radio/Checkbox Fields', 'redux-framework'),
        'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework'),
        'fields' => array(
            array(
                'id' => 'switch',
                'type' => 'switch',
                'title' => __('Switch Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'std' => '1' // 1 = checked | 0 = unchecked
            ),
            array(
                'id' => '10',
                'type' => 'checkbox',
                'title' => __('Checkbox Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'switch' => false,
                'std' => '1' // 1 = checked | 0 = unchecked
            ),
            array(
                'id' => '11',
                'type' => 'multi_checkbox',
                'title' => __('Multi Checkbox Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'options' => array('1' => 'Opt 1', '2' => 'Opt 2', '3' => 'Opt 3'), // Must provide key => value pairs for multi checkbox options
                'std' => array('1' => '1', '2' => '0', '3' => '0') // See how std has changed? You also dont need to specify opts that are 0.
            ),
            array(
                'id' => '12',
                'type' => 'radio',
                'title' => __('Radio Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'options' => array('1' => 'Opt 1', '2' => 'Opt 2', '3' => 'Opt 3'), // Must provide key => value pairs for radio options
                'std' => '2'
            ),
            array(
                'id' => '13',
                'type' => 'images',
                'title' => __('Radio Image Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'options' => array(
                    '1' => array('title' => 'Opt 1', 'img' => 'images/align-none.png'),
                    '2' => array('title' => 'Opt 2', 'img' => 'images/align-left.png'),
                    '3' => array('title' => 'Opt 3', 'img' => 'images/align-center.png'),
                    '4' => array('title' => 'Opt 4', 'img' => 'images/align-right.png')
                ), // Must provide key => value(array:title|img) pairs for radio options
                'std' => '2'
            ),
            array(
                'id' => 'images',
                'type' => 'images',
                'title' => __('Radio Image Option For Layout', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This uses some of the built in images, you can use them for layout options.', 'redux-framework'),
                'options' => array(
                    '1' => array('title' => '1 Column', 'img' => REDUX_URL . 'assets/img/1col.png'),
                    '2' => array('title' => '2 Column Left', 'img' => REDUX_URL . 'assets/img/2cl.png'),
                    '3' => array('title' => '2 Column Right', 'img' => REDUX_URL . 'assets/img/2cr.png')
                ), // Must provide key => value(array:title|img) pairs for radio options
                'std' => '2'
            )                                                                        
        )
    );
    
    $sections[] = array(
		'icon' => 'list-alt',
		'icon_class' => 'icon-large',
        'title' => __('Select Fields', 'redux-framework'),
        'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework'),
        'fields' => array(
            array(
                'id' => '14',
                'type' => 'select',
                'title' => __('Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'options' => array('1' => 'Opt 1', '2' => 'Opt 2', '3' => 'Opt 3'), // Must provide key => value pairs for select options
                'std' => '2'
            ),
            array(
                'id' => '15',
                'type' => 'select',
                'title' => __('Multi Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'options' => array('1' => 'Opt 1', '2' => 'Opt 2', '3' => 'Opt 3'), // Must provide key => value pairs for radio options
                'std' => array('2', '3'),
                'multi' => true
            )                                    
        )
    );
    
    $sections[] = array(
		'icon' => 'cogs',
		'icon_class' => 'icon-large',
        'title' => __('Custom Fields', 'redux-framework'),
        'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework'),
        'fields' => array(
            array(
                'id' => '16',
                'type' => 'color',
                'title' => __('Color Option', 'redux-framework'), 
                'subtitle' => __('Only color validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'std' => '#FFFFFF'
            ),
            array(
                'id' => 'color_gradient',
                'type' => 'color_gradient',
                'title' => __('Color Gradient Option', 'redux-framework'),
                'subtitle' => __('Only color validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'std' => array('from' => '#000000', 'to' => '#FFFFFF')
            ),
            array(
                'id' => '17',
                'type' => 'date',
                'title' => __('Date Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework')
            ),
            array(
                'id' => '18',
                'type' => 'button_set',
                'title' => __('Button Set Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework'),
                'options' => array('1' => 'Opt 1', '2' => 'Opt 2', '3' => 'Opt 3'), // Must provide key => value pairs for radio options
                'std' => '2'
			),
            array(
                'id' => '19',
                'type' => 'media',
                'title' => __('Upload Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework')
            ),
            array(
                'id' => 'pages_select',
                'type' => 'select',
                'title' => __('Pages Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a drop down menu of all the sites pages.', 'redux-framework'),
                'data' => 'pages',
                'args' => array() // Uses get_pages()
            ),
            array(
                'id' => 'select',
                'type' => 'select',
                'title' => __('Pages Multiple Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a multi select menu of all the sites pages.', 'redux-framework'),
                'args' => array('number' => '5'), // Uses get_pages()
                'multi' => true,
                'data' => 'pages',
            ),
            array(
                'id' => 'posts_select',
                'type' => 'select',
                'title' => __('Posts Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a drop down menu of all the sites posts.', 'redux-framework'),
                'args' => array('numberposts' => '10'), // Uses get_posts()
                'data' => 'posts',
            ),
            array(
                'id' => 'posts_multi_select',
                'type' => 'select',
                'multi' => true,
                'data' => 'posts',
                'title' => __('Posts Multiple Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a multi select menu of all the sites posts.', 'redux-framework'),
                'args' => array('numberposts' => '10') // Uses get_posts()
            ),
            array(
                'id' => 'tags_select',
                'type' => 'select',
                'data' => 'tags',
                'title' => __('Tags Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a drop down menu of all the sites tags.', 'redux-framework'),
                'args' => array('number' => '10') // Uses get_tags()
            ),
            array(
                'id' => 'tags_multi_select',
                'type' => 'select',
                'multi' => true,
                'data' => 'tags',                
                'title' => __('Tags Multiple Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a multi select menu of all the sites tags.', 'redux-framework'),
                'args' => array('number' => '10') // Uses get_tags()
            ),
            array(
                'id' => 'cats_select',
                'type' => 'select',
                'data' => 'categories',
                'title' => __('Cats Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a drop down menu of all the sites cats.', 'redux-framework'),
                'args' => array('number' => '10') // Uses get_categories()
            ),
            array(
                'id' => 'cats_multi_select',
                'type' => 'select',
                'multi' => true,
                'data' => 'categories',
                'title' => __('Cats Multiple Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a multi select menu of all the sites cats.', 'redux-framework'),
                'args' => array('number' => '10') // Uses get_categories()
            ),
            array(
                'id' => 'menu_select',
                'type' => 'select',
                'data' => 'menu',
                'title' => __('Menu Select Option', 'redux-framework'),
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a drop down menu of all the sites menus.', 'redux-framework'),
                //'args' => array() // Uses wp_get_nav_menus()
            ),
            array(
                'id' => 'select_hide_below',
                'type' => 'select',
                'title' => __('Select Hide Below Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field requires certain options to be checked before the below field will be shown.', 'redux-framework'),
                'options' => array(
                    '1' => 'Opt 1 field below allowed',
                    '2' => 'Opt 2 field below hidden',
                    '3' => 'Opt 3 field below allowed',
                ), // Must provide key => value(array) pairs for select options
                'std' => '2'
            ),
            array(
                'id' => 'menu_location_select',
                'type' => 'select',
                'data' => 'menu_location',
                'title' => __('Menu Location Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a drop down menu of all the themes menu locations.', 'redux-framework'),
                'fold' => array('select_hide_below'=>"2")
            ),
            array(
                'id' => 'checkbox_hide_below',
                'type' => 'checkbox',
                'title' => __('Checkbox to hide below', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a checkbox which will allow the user to use the next setting.', 'redux-framework'),
            ),
            array(
                'id' => 'post_type_select',
                'type' => 'select',
                'data' => 'post_type',
                'title' => __('Post Type Select Option', 'redux-framework'), 
                'subtitle' => __('No validation can be done on this field type', 'redux-framework'),
                'desc' => __('This field creates a drop down menu of all registered post types.', 'redux-framework'),
                //'args' => array() // Uses get_post_types()
            ),
            array(
                'id' => 'custom_callback',
                //'type' => 'nothing', // Doesn't need to be called for callback fields
                'title' => __('Custom Field Callback', 'redux-framework'), 
                'subtitle' => __('This is a completely unique field type', 'redux-framework'),
                'desc' => __('This is created with a callback function, so anything goes in this field. Make sure to define the function though.', 'redux-framework'),
                'callback' => 'my_custom_field'
            ),
            array(
                'id' => 'typography',
                'type' => 'typography',
                'title' => __('Google Webfonts', 'redux-framework'), 
                'subtitle' => __('This is a completely unique field type', 'redux-framework'),
                'desc' => __('This is a simple implementation of the developer API for Google Webfonts. Don\'t forget to set your API key!', 'redux-framework')
            ),
            array(
                'id' => 'slider',
                'type' => 'slider',
                'title' => __('Slider', 'redux-framework'), 
                'min' => 25,
                'max' => 100,
                'subtitle' => __('Min: 25  Max 100', 'redux-framework'),
                'desc' => __('This is a simple implementation of the developer API for Google Webfonts. Don\'t forget to set your API key!', 'redux-framework')
            )  

        )
    );

    $sections[] = array(
		'icon' => 'eye-open',
		'icon_class' => 'icon-large',
        'title' => __('Non Value Fields', 'redux-framework'),
        'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework'),
        'fields' => array(
            array(
                'id' => '20',
                'type' => 'text',
                'title' => __('Text Field', 'redux-framework'), 
                'subtitle' => __('Additional Info', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework')
            ),
            array(
                'id' => '21',
                'type' => 'divide'
            ),
            array(
                'id' => '22',
                'type' => 'text',
                'title' => __('Text Field', 'redux-framework'), 
                'subtitle' => __('Additional Info', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework')
            ),
            array(
                'id' => '23',
                'type' => 'info',
                'desc' => __('<p class="description">This is the info field, if you want to break sections up.</p>', 'redux-framework')
            ),
            array(
                'id' => '24',
                'type' => 'text',
                'title' => __('Text Field', 'redux-framework'), 
                'subtitle' => __('Additional Info', 'redux-framework'),
                'desc' => __('This is the description field, again good for additional info.', 'redux-framework')
            )                
        )
    );
                
    

    $tabs['item_info'] = array(
		'icon' => 'info-sign',
		'icon_class' => 'icon-large',
        'title' => __('Theme Information', 'redux-framework'),
        'content' => $item_info
    );
    
    if(file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
        $tabs['docs'] = array(
			'icon' => 'book',
			'icon_class' => 'icon-large',
            'title' => __('Documentation', 'redux-framework'),
            'content' => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
        );
    }

    global $Redux_Framework;
    $Redux_Framework = new Redux_Framework($sections, $args, $tabs);

}
add_action('init', 'setup_framework_options', 0);

/*
 * 
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value) {
    print_r($field);
    print_r($value);
}

/*
 * 
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value) {
    $error = false;
    $value =  'just testing';
    /*
    do your validation
    
    if(something) {
        $value = $value;
    } elseif(somthing else) {
        $error = true;
        $value = $existing_value;
        $field['msg'] = 'your custom error message';
    }
    */
    
    $return['value'] = $value;
    if($error == true) {
        $return['error'] = $field;
    }
    return $return;
}
