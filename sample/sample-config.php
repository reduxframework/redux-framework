<?php

  /**
   ReduxFramework Sample Config File
   For full documentation, please visit http://reduxframework.com/docs/
   **/


  /**

   Most of your editing will be done in this section.

   Here you can override default values, uncomment args and change their values.
   No $args are required, but they can be overridden if needed.

   **/

  require_once(get_template_directory(). '/ReduxFramework/ReduxCore/framework.php');
  $args = array();


  // For use with a tab example below
  $tabs = array();

  ob_start();

  $ct = wp_get_theme();
  $theme_data = $ct;
  $item_name = $theme_data->get('Name'); 
  $tags = $ct->Tags;
  $screenshot = $ct->get_screenshot();
  $class = $screenshot ? 'has-screenshot' : '';

  $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;','redux-framework-demo' ), $ct->display('Name') );

?>
<div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
<?php if ( $screenshot ) : ?>
  <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
    <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr( $customize_title ); ?>">
    <img src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Current theme preview' ); ?>" />
      </a>
  <?php endif; ?>
  <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Current theme preview' ); ?>" />
<?php endif; ?>

  <h4>
    <?php echo $ct->display('Name'); ?>
  </h4>

  <div>
  <ul class="theme-info">
    <li><?php printf( __('By %s','redux-framework-demo'), $ct->display('Author') ); ?></li>
    <li><?php printf( __('Version %s','redux-framework-demo'), $ct->display('Version') ); ?></li>
    <li><?php echo '<strong>'.__('Tags', 'redux-framework-demo').':</strong> '; ?><?php printf( $ct->display('Tags') ); ?></li>
  </ul>
<p class="theme-description"><?php echo $ct->display('Description'); ?></p>
  <?php if ( $ct->parent() ) {
      printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.' ) . '</p>',
      __( 'http://codex.wordpress.org/Child_Themes','redux-framework-demo' ),
      $ct->parent()->display( 'Name' ) );
  } ?>

  </div>

  </div><!-- div#current-thene

  <?php
    $item_info = ob_get_contents();

    ob_end_clean();

    $sampleHTML = '';
    if( file_exists( dirname(__FILE__).'/info-html.html' )) {
      /** @global WP_Filesystem_Direct $wp_filesystem  */
      global $wp_filesystem;
      if (empty($wp_filesystem)) {
        require_once(ABSPATH .'/wp-admin/includes/file.php');
        WP_Filesystem();
      }  		
      $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__).'/info-html.html');
    }

    // BEGIN Sample Config

    // Setting dev mode to true allows you to view the class settings/info in the panel.
    // Default: true
  $args['dev_mode'] = true;

  // Set the icon for the dev mode tab.
  // If $args['icon_type'] = 'image', this should be the path to the icon.
  // If $args['icon_type'] = 'iconfont', this should be the icon name.
  // Default: info-sign
  //$args['dev_mode_icon'] = 'info-sign';

  // Set the class for the dev mode tab icon.
  // This is ignored unless $args['icon_type'] = 'iconfont'
  // Default: null
  $args['dev_mode_icon_class'] = 'icon-large';

  // Set a custom option name. Don't forget to replace spaces with underscores!
  $args['opt_name'] = 'redux_demo';

  // Setting system info to true allows you to view info useful for debugging.
  // Default: false
  $args['system_info'] = true;


  // Set the icon for the system info tab.
  // If $args['icon_type'] = 'image', this should be the path to the icon.
  // If $args['icon_type'] = 'iconfont', this should be the icon name.
  // Default: info-sign
  //$args['system_info_icon'] = 'info-sign';

  // Set the class for the system info tab icon.
  // This is ignored unless $args['icon_type'] = 'iconfont'
  // Default: null
  $args['system_info_icon_class'] = 'icon-large';

  $theme = wp_get_theme();

  $args['display_name'] = $theme->get('Name');
  //$args['database'] = "theme_mods_expanded";
  $args['display_version'] = $theme->get('Version');

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

  // Setup custom links in the footer for share icons
  $args['share_icons']['twitter'] = array(
  'link' => 'http://twitter.com/ghost1227',
    'title' => 'Follow me on Twitter', 
    'img' => ReduxFramework::$_url . 'assets/img/social/Twitter.png'
  );
  $args['share_icons']['linked_in'] = array(
    'link' => 'http://www.linkedin.com/profile/view?id=52559281',
    'title' => 'Find me on LinkedIn', 
    'img' => ReduxFramework::$_url . 'assets/img/social/LinkedIn.png'
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

  // Set a custom menu icon.
  //$args['menu_icon'] = '';

  // Set a custom title for the options page.
  // Default: Options
  $args['menu_title'] = __('Options', 'redux-framework-demo');

  // Set a custom page title for the options page.
  // Default: Options
  $args['page_title'] = __('Options', 'redux-framework-demo');

  // Set a custom page slug for options page (wp-admin/themes.php?page=***).
  // Default: redux_options
  $args['page_slug'] = 'redux_options';

  $args['default_show'] = true;
  $args['default_mark'] = '*';

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
  // $args['page_icon'] = 'icon-themes';

  // Set the icon type. Set to "iconfont" for Elusive Icon, or "image" for traditional.
  // Redux no longer ships with standard icons!
  // Default: iconfont
  //$args['icon_type'] = 'image';

  // Disable the panel sections showing as submenu items.
  // Default: true
  //$args['allow_sub_menu'] = false;

  // Set ANY custom page help tabs, displayed using the new help tab API. Tabs are shown in order of definition.
  $args['help_tabs'][] = array(
    'id' => 'redux-opts-1',
    'title' => __('Theme Information 1', 'redux-framework-demo'),
    'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
  );
  $args['help_tabs'][] = array(
    'id' => 'redux-opts-2',
    'title' => __('Theme Information 2', 'redux-framework-demo'),
    'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
  );

  // Set the help sidebar for the options page.                                        
  $args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');


  // Add HTML before the form.
  if (!isset($args['global_variable']) || $args['global_variable'] !== false ) {
    if (!empty($args['global_variable'])) {
      $v = $args['global_variable'];
    } else {
      $v = str_replace("-", "_", $args['opt_name']);
    }
    $args['intro_text'] = __('<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$'.$v.'</strong></p>', 'redux-framework-demo');
  } else {
    $args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo');
  }

  // Add content after the form.
  //$args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework-demo');

  // Set footer/credit line.
  //$args['footer_credit'] = __('<p>This text is displayed in the options panel footer across from the WordPress version (where it normally says \'Thank you for creating with WordPress\'). This field accepts all HTML.</p>', 'redux-framework-demo');


  $sections = array();              

  //Background Patterns Reader
  $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
  $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
  $sample_patterns      = array();

  if ( is_dir( $sample_patterns_path ) ) :

    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
    $sample_patterns = array();

    while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

      if( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
        $name = explode(".", $sample_patterns_file);
        $name = str_replace('.'.end($name), '', $sample_patterns_file);
        $sample_patterns[] = array( 'alt'=>$name,'img' => $sample_patterns_url . $sample_patterns_file );
      }
    }
    endif;
  endif;


  $sections[] = array(
    'icon' => 'cogs',
    'icon_class' => 'icon-large',
    'title' => __('General Setting', 'redux-framework-demo'),
    'fields' => array(
      array(
        'id'=>'header-border',
        'type' => 'border',
        'title' => __('Header Border Option', 'redux-framework-demo'),
        'subtitle' => __('Only color validation can be done on this field type', 'redux-framework-demo'),
        'output' => array('.site-header'), // An array of CSS selectors to apply this font style to
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'default' => array('border-color' => '#1e73be', 'border-style' => 'solid', 'border-top'=>'3px', 'border-right'=>'3px', 'border-bottom'=>'3px', 'border-left'=>'3px')
      ),

      array(
        'id'=>'link-color',
        'type' => 'link_color',
        'title' => __('Links Color Option', 'redux-framework-demo'),
        'subtitle' => __('Only color validation can be done on this field type', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'default' => array(
          'show_regular' => true,
          'show_hover' => true,
          'show_active' => true
        )
      )
    )
  );


  $sections[] = array(
    'type' => 'divide',
  );


  $sections[] = array(
    'title' => __('Home Page', 'redux-framework-demo'),
    'header' => __('Welcome to the Simple Options', 'redux-framework-demo'),
    'desc' => __('Wellcome in your Option Theme', 'redux-framework-demo'),
    'icon_class' => 'icon-large',
    'icon' => 'home',
    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
    'fields' => array(

      array(
        'id'=>'slides',
        'type' => 'slides',
        'title' => __('Carroussel Options', 'redux-framework-demo'),
        'subtitle'=> __('Unlimited slides with drag and drop sortings.', 'redux-framework-demo'),
        'desc' => __('This field will store all slides values into a multidimensional.', 'redux-framework-demo')
      ),
      array(
        'id'=>'dimensions',
        'type' => 'dimensions',
        'units' => '%', // You can specify a unit value. Possible: px, em, %
        'units_extended' => 'true', // Allow users to select any type of unit
        'title' => __('Dimensions (Width/Height) Option', 'redux-framework-demo'),
        'subtitle' => __('Choose width %, height px.', 'redux-framework-demo'),
        'desc' => __('the width it % and the height in pxthe width it % and the height in px.', 'redux-framework-demo'),
        'default' => array('width' => 100, 'height'=>300)
      ),
      array(
        'id'=>'slider-caroussel',
        'type' => 'slider', 
        'title' => __('Carroussel / Sliders', 'redux-framework-demo'),
        'desc'=> __('Interval time in milliseconds (2000 for two seconds)', 'redux-framework-demo'),
        "default" 		=> "2000",
        "min" 		=> "100",
        "step"		=> "3",
        "max" 		=> "10000",
      ),
      array(
        'id'=>'switch-custom',
        'type' => 'switch', 
        'title' => __('Direction of caroussel', 'redux-framework-demo'),
        'subtitle'=> __('Turns to the right by default', 'redux-framework-demo'),
        //"default" 		=> 1,
        'on' => 'Left',
        'off' => 'Right',
      ), 
      array( 
        'title'     => __( 'Type of animation', 'redux-framework-demo' ),
        'desc'      => __( 'Nivo Slider uses amazing transition effects ranging from slicing and sliding to fading and folding. *', 'redux-framework-demo' ),
        'id'        => 'layout_primary_width',
        'type'      => 'button_set',
        'options'   => array(
          'fadeAnim' => 'Fade',
          'slideAnim' => 'Slide',
          'nivoSliderAnim' => '* Nivo Slider'
        ),
        'default' => 'fadeAnim'
      ),
      array( 
        'title'       => __( 'Whats Hot Recipes', 'redux-framework-demo' ),
        'desc'        => __( 'You can use an alternative menu style for your NavBars.', 'redux-framework-demo' ),
        'id'          => 'navbar_style',
        'default'     => 'default',
        'type'        => 'select',
        'customizer'  => array(),
        'options'     => array( 
          'default'   => __( 'Default', 'redux-framework-demo' ),
          'Post id 1'    => __( 'postId_1', 'redux-framework-demo' ) . ' 1',
          'Post id 2'    => __( 'postId_2', 'redux-framework-demo' ) . ' 2',
          'Post id 3'    => __( 'postId_3', 'redux-framework-demo' ) . ' 3',
          'Post id 4'    => __( 'postId_4', 'redux-framework-demo' ) . ' 4',
          'Post id 5'    => __( 'postId_5', 'redux-framework-demo' ) . ' 5',
          'Post id 6'    => __( 'postId_6', 'redux-framework-demo' ) . ' 6'
        )
      ),
      array(
        "id" => "homepage_blocks",
        "type" => "sorter",
        "title" => "Homepage Layout Manager",
        "desc" => "Organize how you want the layout to appear on the homepage",
        "compiler"=>'true',   //trier des champs avec Drag and Drop	
        //'required' => array('switch-fold','equals','0'), 
        'options' => array(
          "enabled" => array(
            "placebo" => "placebo", //REQUIRED!
            "sliderHome" => "Slider",
            "hotRecipes" => "Hot Recipes", 
            "contentHome" => "Content",
            "footerHome" => "Footer"
          ),
          "disabled" => array(
            "placebo" => "placebo", //REQUIRED!
          )
        ),
      ),
    ),
  );


  $sections[] = array(
    'icon' => 'website',
    'icon_class' => 'icon-large',
    'title' => __('Layout', 'redux-framework-demo'),
    'fields' => array(
      array( 
        'title'     => __( 'Layout', 'redux-framework-demo' ),
        'desc'      => __( 'Select main content and sidebar arrangement. Choose between 1, 2 or 3 column layout.', 'redux-framework-demo' ),
        'id'        => 'layout',
        'default'   => 1,
        'type'      => 'image_select',
        'customizer'=> array(),
        'options'   => array( 
          0         => 'REDUX_URL' . 'assets/img/1c.png',
          1         => 'REDUX_URL' . 'assets/img/2cr.png',
          2         => 'REDUX_URL' . 'assets/img/2cl.png',
          3         => 'REDUX_URL' . 'assets/img/3cl.png',
          4         => 'REDUX_URL' . 'assets/img/3cr.png',
          5         => 'REDUX_URL' . 'assets/img/3cm.png',
        )
      ),

      array(
        'title'     => __( 'Custom Layouts per Post Type', 'redux-framework-demo' ),
        'desc'      => __( 'Set a default layout for each post type on your site.', 'redux-framework-demo' ),
        'id'        => 'cpt_layout_toggle',
        'default'   => 0,
        'type'      => 'switch',
        'customizer'=> array(),
      ),
      /*
       if ( post_type_exists( 'recipes' ) ) {
         $post_types = get_post_types( array( 'public' => true ), 'names' );
         foreach ( $post_types as $post_type ) :
         array(
           'title'     => __( $post_type . ' Layout', 'redux-framework-demo' ),
           'desc'      => __( 'Override your default stylings. Choose between 1, 2 or 3 column layout.', 'redux-framework-demo' ),
           'id'        => $post_type . '_layout',
           'default'   => 'layout',
           'type'      => 'image_select',
           'required'  => array( 'cpt_layout_toggle','='array( '1' ) ),
           'options'   => array(
             0         => 'REDUX_URL' . 'assets/img/1c.png',
             1         => 'REDUX_URL' . 'assets/img/2cr.png',
             2         => 'REDUX_URL' . 'assets/img/2cl.png',
             3         => 'REDUX_URL' . 'assets/img/3cl.png',
             4         => 'REDUX_URL' . 'assets/img/3cr.png',
             5         => 'REDUX_URL' . 'assets/img/3cm.png',
           )
         )
         endforeach; 
       }
       */            

      array( 
        'title'     => __( 'Show sidebars on the frontpage', 'redux-framework-demo' ),
        'desc'      => __( 'OFF by default. If you want to display the sidebars in your frontpage, turn this ON.', 'redux-framework-demo' ),
        'id'        => 'layout_sidebar_on_front',
        'customizer'=> array(),
        'default'   => 0,
        'type'      => 'switch'
      ),

      array( 
        'title'     => __( 'Margin from top ( Works only in \'Boxed\' mode )', 'redux-framework-demo' ),
        'desc'      => __( 'This will add a margin above the navbar. Useful if you\'ve enabled the \'Boxed\' mode above. Default: 0px', 'redux-framework-demo' ),
          'id'        => 'navbar_margin_top',
          'required'  => array('navbar_boxed','=',array('1')),
          'default'   => 0,
          'min'       => 0,
          'step'      => 1,
          'max'       => 120,
          'compiler'  => true,
          'type'      => 'slider'
       ),

       array( 
         'title'     => __( 'Widgets mode', 'redux-framework-demo' ),
         'desc'      => __( 'How do you want your widgets to be displayed?', 'redux-framework-demo' ),
         'id'        => 'widgets_mode',
         'default'   => 1,
         // 'required'  => array('advanced_toggle','=',array('1')),
         'off'       => __( 'Panel', 'redux-framework-demo' ),
         'on'        => __( 'Well', 'redux-framework-demo' ),
         'type'      => 'switch',
         'customizer'=> array(),
       ),

       array( 
         'title'     => __( 'Show Breadcrumbs', 'redux-framework-demo' ),
         'desc'      => __( 'Display Breadcrumbs. Default: OFF.', 'redux-framework-demo' ),
           'id'        => 'breadcrumbs',
           'default'   => 0,
           'type'      => 'switch',
           'customizer'=> array(),
         ),

         array( 
           'title'     => __( 'Body Top Margin', 'redux-framework-demo' ),
           'desc'      => __( 'Select the top margin of body element in pixels. Default: 0px.', 'redux-framework-demo' ),
             'id'        => 'body_margin_top',
             'default'   => 0,
             'min'       => 0,
             'max'       => 200,
             'type'      => 'slider',
             // 'required'  => array('advanced_toggle','=',array('1'))
           ),

           array( 
             'title'     => __( 'Body Bottom Margin', 'redux-framework-demo' ),
             'desc'      => __( 'Select the bottom margin of body element in pixels. Default: 0px.', 'redux-framework-demo' ),
               'id'        => 'body_margin_bottom',
               'default'   => 0,
               'min'       => 0,
               'max'       => 200,
               'type'      => 'slider',
               // 'required'  => array('advanced_toggle','=',array('1'))
       )
     )
  );

  $sections[] = array(
    'icon' => 'list-alt',
    'icon_class' => 'icon-large',
    'title' => __('Recipes', 'redux-framework-demo'),
    'desc' => __('<p class="description">This is the Description. Again HTML is allowed2</p>', 'redux-framework-demo'),
    'fields' => array(
      array(
        'id'=>'2',
        'type' => 'text',
        'title' => __('Text Option - Email Validated', 'redux-framework-demo'),
        'subtitle' => __('This is a little space under the Field Title in the Options table, additional info is good in here.', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'email',
        'msg' => 'custom error message',
        'default' => 'test@test.com'
      ),				
      array(
        'id'=>'multi_text',
        'type' => 'multi_text',
        'title' => __('Multi Text Option', 'redux-framework-demo'),
        'subtitle' => __('This is a little space under the Field Title in the Options table, additional info is good in here.', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo')
      ),
      array(
        'id'=>'3',
        'type' => 'text',
        'title' => __('Text Option - URL Validated', 'redux-framework-demo'),
        'subtitle' => __('This must be a URL.', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'url',
        'default' => 'http://reduxframework.com'
      ),
      array(
        'id'=>'4',
        'type' => 'text',
        'title' => __('Text Option - Numeric Validated', 'redux-framework-demo'),
        'subtitle' => __('This must be numeric.', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'numeric',
        'default' => '0',
        'class' => 'small-text'
      ),
      array(
        'id'=>'comma_numeric',
        'type' => 'text',
        'title' => __('Text Option - Comma Numeric Validated', 'redux-framework-demo'),
        'subtitle' => __('This must be a comma separated string of numerical values.', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'comma_numeric',
        'default' => '0',
        'class' => 'small-text'
      ),
      array(
        'id'=>'no_special_chars',
        'type' => 'text',
        'title' => __('Text Option - No Special Chars Validated', 'redux-framework-demo'),
        'subtitle' => __('This must be a alpha numeric only.', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'no_special_chars',
        'default' => '0'
      ),
      array(
        'id'=>'str_replace',
        'type' => 'text',
        'title' => __('Text Option - Str Replace Validated', 'redux-framework-demo'),
        'subtitle' => __('You decide.', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'str_replace',
        'str' => array('search' => ' ', 'replacement' => 'thisisaspace'),
        'default' => '0'
      ),
      array(
        'id'=>'preg_replace',
        'type' => 'text',
        'title' => __('Text Option - Preg Replace Validated', 'redux-framework-demo'),
        'subtitle' => __('You decide.', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'preg_replace',
        'preg' => array('pattern' => '/[^a-zA-Z_ -]/s', 'replacement' => 'no numbers'),
        'default' => '0'
      ),
      array(
        'id'=>'custom_validate',
        'type' => 'text',
        'title' => __('Text Option - Custom Callback Validated', 'redux-framework-demo'),
        'subtitle' => __('You decide.', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate_callback' => 'validate_callback_function',
        'default' => '0'
      ),
      array(
        'id'=>'5',
        'type' => 'textarea',
        'title' => __('Textarea Option - No HTML Validated', 'redux-framework-demo'), 
        'subtitle' => __('All HTML will be stripped', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'no_html',
        'default' => 'No HTML is allowed in here.'
      ),
      array(
        'id'=>'6',
        'type' => 'textarea',
        'title' => __('Textarea Option - HTML Validated', 'redux-framework-demo'), 
        'subtitle' => __('HTML Allowed (wp_kses)', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
        'default' => 'HTML is allowed in here.'
      ),
      array(
        'id'=>'7',
        'type' => 'textarea',
        'title' => __('Textarea Option - HTML Validated Custom', 'redux-framework-demo'), 
        'subtitle' => __('Custom HTML Allowed (wp_kses)', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'html_custom',
        'default' => '<p>Some HTML is allowed in here.</p>',
        'allowed_html' => array('') //see http://codex.wordpress.org/Function_Reference/wp_kses
      ),
      array(
        'id'=>'8',
        'type' => 'textarea',
        'title' => __('Textarea Option - JS Validated', 'redux-framework-demo'), 
        'subtitle' => __('JS will be escaped', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'validate' => 'js'
      ),

    )
  );
  $sections[] = array(
    'icon' => 'check',
    'icon_class' => 'icon-large',
    'title' => __('Blog', 'redux-framework-demo'),
    'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
    'fields' => array(
      array(
        'id'=>'10',
        'type' => 'checkbox',
        'title' => __('Checkbox Option', 'redux-framework-demo'), 
        'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'default' => '1'// 1 = on | 0 = off
      ),
      array(
        'id'=>'11',
        'type' => 'checkbox',
        'title' => __('Multi Checkbox Option', 'redux-framework-demo'), 
        'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for multi checkbox options
        'default' => array('1' => '1', '2' => '0', '3' => '0')//See how std has changed? you also don't need to specify opts that are 0.
      ),
      array(
        'id'=>'checkbox-data',
        'type' => 'checkbox',
        'title' => __('Multi Checkbox Option (with menu data)', 'redux-framework-demo'), 
        'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'data' => "menu"
      ),					
      array(
        'id'=>'12',
        'type' => 'radio',
        'title' => __('Radio Option', 'redux-framework-demo'), 
        'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'options' => array('1' => 'Opt 1', '2' => 'Opt 2', '3' => 'Opt 3'),//Must provide key => value pairs for radio options
        'default' => '2'
      ),
      array(
        'id'=>'radio-data',
        'type' => 'radio',
        'title' => __('Multi Checkbox Option (with menu data)', 'redux-framework-demo'), 
        'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'data' => "menu"
      ),					
      array(
        'id'=>'13',
        'type' => 'image_select',
        'title' => __('Images Option', 'redux-framework-demo'), 
        'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'options' => array(
          '1' => array('title' => 'Opt 1', 'img' => 'images/align-none.png'),
          '2' => array('title' => 'Opt 2', 'img' => 'images/align-left.png'),
          '3' => array('title' => 'Opt 3', 'img' => 'images/align-center.png'),
          '4' => array('title' => 'Opt 4', 'img' => 'images/align-right.png')
        ),//Must provide key => value(array:title|img) pairs for radio options
        'default' => '2'
      ),
      array(
        'id'=>'image_select',
        'type' => 'image_select',
        'title' => __('Images Option for Layout', 'redux-framework-demo'), 
        'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
        'desc' => __('This uses some of the built in images, you can use them for layout options.', 'redux-framework-demo'),
        'options' => array(
          '1' => array('alt' => '1 Column', 'img' => ReduxFramework::$_url.'assets/img/1col.png'),
          '2' => array('alt' => '2 Column Left', 'img' => ReduxFramework::$_url.'assets/img/2cl.png'),
          '3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url.'assets/img/2cr.png'),
          '4' => array('alt' => '3 Column Middle', 'img' => ReduxFramework::$_url.'assets/img/3cm.png'),
          '5' => array('alt' => '3 Column Left', 'img' => ReduxFramework::$_url.'assets/img/3cl.png'),
          '6' => array('alt' => '3 Column Right', 'img' => ReduxFramework::$_url.'assets/img/3cr.png')
        ),//Must provide key => value(array:title|img) pairs for radio options
        'default' => '2'
      ),
      array(
        'id' => 'text_sortable',
        'type' => 'sortable',
        'title' => __('Sortable Text Option', 'redux-framework-demo'),
        'sub_desc' => __('Define and reorder these however you want.', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'options' => array(
          'si1' => 'Item 1',
          'si2' => 'Item 2',
          'si3' => 'Item 3',
        )
      ),	
      array(
        'id' => 'check_sortable',
        'type' => 'sortable',
        'mode' => 'checkbox', // checkbox or text
        'title' => __('Sortable Text Option', 'redux-framework-demo'),
        'sub_desc' => __('Define and reorder these however you want.', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'options' => array(
          'si1' => 'Item 1',
          'si2' => 'Item 2',
          'si3' => 'Item 3',
        )
      ),	        																						
    )
  );
  $sections[] = array(
    'icon' => 'list-alt',
    'icon_class' => 'icon-large',
    'title' => __('Background', 'redux-framework-demo'),
    'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
    'fields' => array(
      array(
        'title'     => __( 'General Background Color', 'redux-framework-demo' ),
        'desc'      => __( 'Select a background color for your site. Default: #ffffff.', 'redux-framework-demo' ),
          'id'        => 'html_color_bg',
          'default'   => '#ffffff',
          'customizer'=> array(),
          'transparent'=> false,
          'type'      => 'color',
        ),
        array(
          'id'=>'color-header',
          'type' => 'color_gradient',
          'title' => __('Header Gradient Color Option', 'redux-framework-demo'),
          'subtitle' => __('Only color validation can be done on this field type', 'redux-framework-demo'),
          'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
          'default' => array('from' => '#1e73be', 'to' => '#00897e')
        ),
        array(
          'title'     => __( 'Content Background Color', 'redux-framework-demo' ),
          'desc'      => __( 'Select a background color for your site\'s content area. Default: #ffffff.', 'redux-framework-demo' ),
            'id'        => 'color_body_bg',
            'default'   => '#ffffff',
            'compiler'  => true,
            'customizer'=> array(),
            'transparent'=> false,
            'type'      => 'color',
        ),

        array(
          'title'     => __( 'Content Background Color Opacity', 'redux-framework-demo' ),
          'desc'      => __( 'Select the opacity of your background color for the main content area so that background images and patterns will show through. Default: 100 (fully opaque)', 'redux-framework-demo' ),
            'id'        => 'color_body_bg_opacity',
            'default'   => 100,
            'min'       => 0,
            'step'      => 1,
            'max'       => 100,
            'advanced'  => true,
            'type'      => 'slider',
        ),

        array(
          'title'     => 'Background Images',
          'id'        => 'help4',
          'desc'      => __( 'If you want a background image, you can select one here.
          You can either upload a custom image, or use one of our pre-defined image patterns.
          If you both upload a custom image and select a pattern, your custom image will override the selected pattern.
          Please note that the image only applies to the area on the right and left of the main content area,
          to ensure better content readability. You can also set the background position to be fixed or scroll!', 'redux-framework-demo' ),
          'type'      => 'info'
        ),

        array(
          'title'     => __( 'Use a Background Image', 'redux-framework-demo' ),
          'desc'      => __( 'Enable this option to upload a custom background image for your site. This will override any patterns you may have selected. Default: OFF.', 'redux-framework-demo' ),
            'id'        => 'background_image_toggle',
            'default'   => 0,
            'type'      => 'switch'
        ),
        array(
          'title'     => __( 'Upload a Custom Background Image', 'redux-framework-demo' ),
          'desc'      => __( 'Upload a Custom Background image using the media uploader, or define the URL directly.', 'redux-framework-demo' ),
          'id'        => 'background_image',
          'required'  => array('background_image_toggle','=',array('1')),
          'default'   => '',
          'type'      => 'media',
          'customizer'=> array(),
        ),

        array(
          'title'     => __( 'Background position', 'redux-framework-demo' ),
          'desc'      => __( 'Changes how the background image or pattern is displayed from scroll to fixed position. Default: Fixed.', 'redux-framework-demo' ),
            'id'        => 'background_fixed_toggle',
            'default'   => 1,
            'on'        => __( 'Fixed', 'redux-framework-demo' ),
            'off'       => __( 'Scroll', 'redux-framework-demo' ),
            'type'      => 'switch',
            'required'  => array('background_image_toggle','=',array('1')),
        ),

        array(
          'title'     => __( 'Background Image Positioning', 'redux-framework-demo' ),
          'desc'      => __( 'Allows the user to modify how the background displays. By default it is full width and stretched to fill the page. Default: Full Width.', 'redux-framework-demo' ),
            'id'        => 'background_image_position_toggle',
            'default'   => 0,
            'required'  => array('background_image_toggle','=',array('1')),
            'on'        => __( 'Custom', 'redux-framework-demo' ),
            'off'       => __( 'Full Width', 'redux-framework-demo' ),
            'type'      => 'switch'
        ),
        array(
          'title'     => __( 'Background Repeat', 'redux-framework-demo' ),
          'desc'      => __( 'Select how (or if) the selected background should be tiled. Default: Tile', 'redux-framework-demo' ),
            'id'        => 'background_repeat',
            'required'  => array('background_image_position_toggle','=',array('1')),
            'default'   => 'repeat',
            'type'      => 'select',
            'options'   => array(
              'no-repeat'  => __( 'No Repeat', 'redux-framework-demo' ),
              'repeat'     => __( 'Tile', 'redux-framework-demo' ),
              'repeat-x'   => __( 'Tile Horizontally', 'redux-framework-demo' ),
              'repeat-y'   => __( 'Tile Vertically', 'redux-framework-demo' ),
            ),
        ),

        array(
          'title'     => __( 'Background Alignment', 'redux-framework-demo' ),
          'desc'      => __( 'Select how the selected background should be horizontally aligned. Default: Left', 'redux-framework-demo' ),
            'id'        => 'background_position_x',
            'required'  => array('background_image_position_toggle','=',array('1')),
            'default'   => 'repeat',
            'type'      => 'select',
            'options'   => array(
              'left'    => __( 'Left', 'redux-framework-demo' ),
              'right'   => __( 'Right', 'redux-framework-demo' ),
              'center'  => __( 'Center', 'redux-framework-demo' ),
            ),
        ),

        array(
          'title'     => __( 'Use a Background Pattern', 'redux-framework-demo' ),
          'desc'      => __( 'Select one of the already existing Background Patterns. Default: OFF.', 'redux-framework-demo' ),
            'id'        => 'background_pattern_toggle',
            'default'   => 0,
            'type'      => 'switch'
        ),

        array(
          'title'     => __( 'Choose a Background Pattern', 'redux-framework-demo' ),
          'desc'      => __( 'Select a background pattern.', 'redux-framework-demo' ),
          'id'        => 'background_pattern',
          'required'  => array('background_pattern_toggle','=',array('1')),
          'default'   => '',
          'tiles'     => true,
          'type'      => 'image_select',
          'options'   => $sample_patterns,
        )
      )
    );


  if (function_exists('wp_get_theme')){
    $theme_data = wp_get_theme();
    $theme_uri = $theme_data->get('ThemeURI');
    $description = $theme_data->get('Description');
    $author = $theme_data->get('Author');
    $version = $theme_data->get('Version');
    $tags = $theme_data->get('Tags');
  }else{
    $theme_data = wp_get_theme(trailingslashit(get_stylesheet_directory()).'style.css');
    $theme_uri = $theme_data['URI'];
    $description = $theme_data['Description'];
    $author = $theme_data['Author'];
    $version = $theme_data['Version'];
    $tags = $theme_data['Tags'];
  }	

  $theme_info = '<div class="redux-framework-section-desc">';
  $theme_info .= '<p class="redux-framework-theme-data description theme-uri">'.__('<strong>Theme URL:</strong> ', 'redux-framework-demo').'<a href="'.$theme_uri.'" target="_blank">'.$theme_uri.'</a></p>';
  $theme_info .= '<p class="redux-framework-theme-data description theme-author">'.__('<strong>Author:</strong> ', 'redux-framework-demo').$author.'</p>';
  $theme_info .= '<p class="redux-framework-theme-data description theme-version">'.__('<strong>Version:</strong> ', 'redux-framework-demo').$version.'</p>';
  $theme_info .= '<p class="redux-framework-theme-data description theme-description">'.$description.'</p>';
  if ( !empty( $tags ) ) {
    $theme_info .= '<p class="redux-framework-theme-data description theme-tags">'.__('<strong>Tags:</strong> ', 'redux-framework-demo').implode(', ', $tags).'</p>';	
  }
  $theme_info .= '</div>';

  if(file_exists(dirname(__FILE__).'/README.md')){
    $tabs['theme_docs'] = array(
      'icon' => ReduxFramework::$_url.'assets/img/glyphicons/glyphicons_071_book.png',
      'title' => __('Documentation', 'redux-framework-demo'),
      'content' => file_get_contents(dirname(__FILE__).'/README.md')
    );
  }//if



  $url = 'nav-menus.php?action=locations';
  $sections[] = array(
    'icon' => 'tag',
    'icon_class' => 'icon-large',
    'title' => __('Menus', 'redux-framework-demo'),
    'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
    'fields' => array(
      array( 
        'id'          => 'help7',
        'title'       => __( 'Advanced NavBar Options', 'redux-framework-demo' ),
        'desc'        => __( "You can activate or deactivate your Primary NavBar here, and define its properties.
        Please note that you might have to manually create a menu if it doesn't already exist
        and add items to it from <a href='$url'>this page</a>.", 'redux-framework-demo' ),
        'type'        => 'info'
      ),

      array(
        'id'=>'link-color',
        'type' => 'link_color',
        'title' => __('Links Color Option', 'redux-framework-demo'),
        'subtitle' => __('Only color validation can be done on this field type', 'redux-framework-demo'),
        'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
        'default' => array(
          'show_regular' => true,
          'show_hover' => true,
          'show_active' => true
        )
      ),
      array( 
        'title'       => __( 'Type of NavBar', 'redux-framework-demo' ),
        'desc'        => __( 'Normal mode or Pills?' ),
        'id'          => 'navbar_toggle',
        'default'     => 1,
        'on'          => __( 'Normal', 'redux-framework-demo' ),
        'off'         => __( 'Pills', 'redux-framework-demo' ),
        'customizer'  => array(),
        'type'        => 'switch'
      ),

      array( 
        'id'          => 'helpnavbarbg',
        'title'       => __( 'NavBar Styling Options', 'redux-framework-demo' ),
        'desc'   	    => __( 'Customize the look and feel of your navbar below.', 'redux-framework-demo' ),
        'type'        => 'info'
      ),    

      array( 
        'title'       => __( 'NavBar Background Color', 'redux-framework-demo' ),
        'desc'        => __( 'Pick a background color for the NavBar. Default: #eeeeee.', 'redux-framework-demo' ),
          'id'          => 'navbar_bg',
          'default'     => '#f8f8f8',
          'compiler'    => true,
          'customizer'  => array(),
          'transparent' => false,    
          'type'        => 'color'
      ),

      array( 
        'title'       => __( 'NavBar Background Opacity', 'redux-framework-demo' ),
        'desc'        => __( 'Pick a background opacity for the NavBar. Default: 100%.', 'redux-framework-demo' ),
          'id'          => 'navbar_bg_opacity',
          'default'     => 100,
          'min'         => 0,
          'step'        => 1,
          'max'         => 100,
          'type'        => 'slider'
      ),
      array( 
        'title'       => __( 'NavBar Menu Style', 'redux-framework-demo' ),
        'desc'        => __( 'You can use an alternative menu style for your NavBars.', 'redux-framework-demo' ),
        'id'          => 'navbar_style',
        'default'     => 'default',
        'type'        => 'select',
        'customizer'  => array(),
        'options'     => array( 
          'default'   => __( 'Default', 'redux-framework-demo' ),
          'style1'    => __( 'Style', 'redux-framework-demo' ) . ' 1',
          'style2'    => __( 'Style', 'redux-framework-demo' ) . ' 2',
          'style3'    => __( 'Style', 'redux-framework-demo' ) . ' 3',
          'style4'    => __( 'Style', 'redux-framework-demo' ) . ' 4',
          'style5'    => __( 'Style', 'redux-framework-demo' ) . ' 5',
          'style6'    => __( 'Style', 'redux-framework-demo' ) . ' 6',
          'metro'     => __( 'Metro', 'redux-framework-demo' ),
        )
      ),

      array( 
        'title'       => __( 'Display Branding ( Sitename or Logo ) on the NavBar', 'redux-framework-demo' ),
        'desc'        => __( 'Default: ON', 'redux-framework-demo' ),
          'id'          => 'navbar_brand',
          'default'     => 1,
          'customizer'  => array(),
          'type'        => 'switch'
      ),

      array( 
        'title'       => __( 'Use Logo ( if available ) for branding on the NavBar', 'redux-framework-demo' ),
        'desc'        => __( 'If this option is OFF, or there is no logo available, then the sitename will be displayed instead. Default: ON', 'redux-framework-demo' ),
          'id'          => 'navbar_logo',
          'default'     => 1,
          'customizer'  => array(),
          'type'        => 'switch'
        ),

      array( 
        'title'       => __( 'NavBar Positioning', 'redux-framework-demo' ),
        'desc'        => __( 'Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you\'re using one of the \'fixed\' options, the navbar will stay fixed on the top or bottom of the page. Default: Normal', 'redux-framework-demo' ),
          'id'          => 'navbar_fixed',
          'default'     => 0,
          'on'          => __( 'Fixed', 'redux-framework-demo' ),
          'off'         => __( 'Scroll', 'redux-framework-demo' ),
          'type'        => 'switch'
      ),
      array( 
        'title'       => __( 'Fixed NavBar Position', 'redux-framework-demo' ),
        'desc'        => __( 'Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you\'re using one of the \'fixed\' options, the navbar will stay fixed on the top or bottom of the page. Default: Normal', 'redux-framework-demo' ),
          'id'          => 'navbar_fixed_position',
          'required'    => array('navbar_fixed','=',array('1')),
          'default'     => 0,
          'on'          => __( 'Bottom', 'redux-framework-demo' ),
          'off'         => __( 'Top', 'redux-framework-demo' ),
          'type'        => 'switch'
      ),

      array( 
        'title'       => __( 'NavBar Height', 'redux-framework-demo' ),
        'desc'        => __( 'Select the height of the NavBar in pixels. Should be equal or greater than the height of your logo if you\'ve added one.', 'redux-framework-demo' ),
        'id'          => 'navbar_height',
        'default'     => 50,
        'min'         => 38,
        'step'        => 1,
        'max'         => 200,
        'compiler'    => true,
        'type'        => 'slider'
      ),

      array( 
        'title'       => __( 'Navbar Font', 'redux-framework-demo' ),
        'desc'        => __( 'The font used in navbars.', 'redux-framework-demo' ),
        'id'          => 'font_navbar',
        'compiler'    => true,
        'default'     => array( 
          'font-family' => 'Arial, Helvetica, sans-serif',
          'font-size'   => 14,
          'color'     => '#333333',
          'google'    => 'false',
        ),
        'preview'     => array( 
          'text'      => __( 'This is my preview text!', 'redux-framework-demo' ), //this is the text from preview box
          'size'      => 30 //this is the text size from preview box
        ),
        'type'        => 'typography',
        // 'required'    => array('advanced_toggle','=',array('1'))
      ),

      array( 
        'title'       => __( 'Branding Font', 'redux-framework-demo' ),
        'desc'        => __( 'The branding font for your site.', 'redux-framework-demo' ),
        'id'          => 'font_brand',
        'compiler'    => true,
        'default'     => array( 
          'font-family'  => 'Arial, Helvetica, sans-serif',
          'font-size'    => 18,
          'google'    => 'false',
          'color'     => '#333333',
        ),
        'preview'     => array( 
          'text'      => __( 'This is my preview text!', 'redux-framework-demo' ), //this is the text from preview box
          'size'      => 30 //this is the text size from preview box
        ),
        'type'        => 'typography',
        // 'required'    => array('advanced_toggle','=',array('1'))
      ),

      array( 
        'title'       => __( 'NavBar Margin', 'redux-framework-demo' ),
        'desc'        => __( 'Select the top and bottom margin of the NavBar in pixels. Applies only in static top navbar ( scroll condition ). Default: 0px.', 'redux-framework-demo' ),
          'id'          => 'navbar_margin',
          'default'     => 0,
          'min'         => 0,
          'step'        => 1,
          'max'         => 200,
          'type'        => 'slider'
      ),
      array( 
        'title'       => __( 'Display social links in the NavBar.', 'redux-framework-demo' ),
        'desc'        => __( 'Display social links in the NavBar. These can be setup in the \'Social\' section on the left. Default: OFF', 'redux-framework-demo' ),
          'id'          => 'navbar_social',
          'customizer'  => array(),
          'default'     => 0,
          'type'        => 'switch'
      ),

      array( 
        'title'       => __( 'Display social links as a Dropdown list or an Inline list.', 'redux-framework-demo' ),
        'desc'        => __( 'How to display social links. Default: Dropdown list', 'redux-framework-demo' ),
          'id'          => 'navbar_social_style',
          'customizer'  => array(),
          'default'     => 0,
          'on'          => __( 'Inline', 'redux-framework-demo' ),
          'off'         => __( 'Dropdown', 'redux-framework-demo' ),
          'type'        => 'switch',
          'required'    => array('navbar_social','=',array('1')),
      ),

      array( 
        'title'       => __( 'Search form on the NavBar', 'redux-framework-demo' ),
        'desc'        => __( 'Display a search form in the NavBar. Default: On', 'redux-framework-demo' ),
          'id'          => 'navbar_search',
          'customizer'  => array(),
          'default'     => 1,
          'type'        => 'switch'
      ),

      array( 
        'title'       => __( 'Float NavBar menu to the right', 'redux-framework-demo' ),
        'desc'        => __( 'Floats the primary navigation to the right. Default: On', 'redux-framework-demo' ),
          'id'          => 'navbar_nav_right',
          'default'     => 1,
          'customizer'  => array(),
          'type'        => 'switch'
      ),

      array( 
        'id'          => 'help9',
        'title'       => __( 'Secondary Navbar', 'redux-framework-demo' ),
        'desc'        => __( 'The secondary navbar is a 2nd navbar, located right above the main wrapper. You can show a menu there, by assigning it from Appearance -> Menus.', 'redux-framework-demo' ),
        'type'        => 'info',
        // 'required'    => array('advanced_toggle','=',array('1'))
      ),

      array( 
        'title'       => __( 'Enable the Secondary NavBar', 'redux-framework-demo' ),
        'desc'        => __( 'Display a Secondary NavBar on top of the Main NavBar. Default: ON', 'redux-framework-demo' ),
          'id'          => 'secondary_navbar_toggle',
          'customizer'  => array(),
          'default'     => 0,
          'type'        => 'switch',
          // 'required'    => array('advanced_toggle','=',array('1'))
      ),
      array( 
        'title'       => __( 'Display social networks in the navbar', 'redux-framework-demo' ),
        'desc'        => __( 'Enable this option to display your social networks as a dropdown menu on the seondary navbar.', 'redux-framework-demo' ),
        'id'          => 'navbar_secondary_social',
        'required'    => array('secondary_navbar_toggle','=',array('1')),
        'default'     => 0,
        'type'        => 'switch',
      ),

      array( 
        'title'       => __( 'Secondary NavBar Margin', 'redux-framework-demo' ),
        'desc'        => __( 'Select the top and bottom margin of header in pixels. Default: 0px.', 'redux-framework-demo' ),
          'id'          => 'secondary_navbar_margin',
          'default'     => 0,
          'min'         => 0,
          'max'         => 200,
          'type'        => 'slider',
          'required'    => array('secondary_navbar_toggle','=',array('1')),
      )
    )
  );


  // Branding
  $sections[] = array(
    'icon' => 'star',
    'icon_class' => 'icon-large',
    'title' => __('Branding', 'redux-framework-demo'),
    'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
    'fields' => array(
      array( 
        'title'       => __( 'Logo', 'redux-framework-demo' ),
        'desc'        => __( 'Upload a logo image using the media uploader, or define the URL directly.', 'redux-framework-demo' ),
        'id'          => 'logo',
        'default'     => '',
        'type'        => 'media',
        'customizer'  => array(),
      ),

      array( 
        'title'       => 'Retina Logo',
        'desc'        => __( 'Upload a logo that is exactly 2x the size you want to typically display. A version will then be generated for general site use. If you have previously uploaded a logo, you will need to re-upload it to generate the proper versions.', 'redux-framework-demo' ),
        'id'          => 'retina_help',
        'required'    => array('retina_logo_toggle','=',array('1')),
        'type'        => 'info'
      ),

      array( 
        'title'       => __( 'Custom Favicon', 'redux-framework-demo' ),
        'desc'        => __( 'Upload a favicon image using the media uploader, or define the URL directly.', 'redux-framework-demo' ),
        'id'          => 'favicon',
        'default'     => '',
        'type'        => 'media',
        // 'required'    => array('advanced_toggle','=',array('1'))
      ),

      array( 
        'title'       => __( 'Apple Icon', 'redux-framework-demo' ),
        'desc'        => __( 'This will create icons for Apple iPhone ( 57px x 57px ), Apple iPhone Retina Version ( 114px x 114px ), Apple iPad ( 72px x 72px ) and Apple iPad Retina ( 144px x 144px ). Please note that for better results the image you upload should be at least 144px x 144px.', 'redux-framework-demo' ),
        'id'          => 'apple_icon',
        'default'     => '',
        'type'        => 'media',
        // 'required'    => array('advanced_toggle','=',array('1'))
      ),


      array( 
        'title'       => 'Colors',
        'desc'        => '',
        'id'          => 'help6',
        'default'     => __( 'The primary color you select will also affect other elements on your site,
        such as table borders, widgets colors, input elements, dropdowns etc.
        The branding colors you select will be used throughout the site in various elements.
        One of the most important settings in your branding is your primary color,
        since this will be used more often.', 'redux-framework-demo' ),
        'type'        => 'info'
      ),

      array(
        'title'       => __( 'Enable Gradients', 'redux-framework-demo' ),
        'desc'        => __( 'Enable gradients for buttons and the navbar. Default: Off.', 'redux-framework-demo' ),
          'id'          => 'gradients_toggle',
          'default'     => 0,
          'customizer'  => array(),
          'compiler'    => true,
          'type'        => 'switch',
          // 'required'    => array('advanced_toggle','=',array('1')),
      ),
      array( 
        'title'       => __( 'Brand Colors: Primary', 'redux-framework-demo' ),
        'desc'        => __( 'Select your primary branding color. This will affect various areas of your site, including the color of your primary buttons, the background of some elements and many more.', 'redux-framework-demo' ),
        'id'          => 'color_brand_primary',
        'default'     => '#428bca',
        'compiler'    => true,
        'customizer'  => array(),
        'transparent' => false,    
        'type'        => 'color'
      ),

      array( 
        'title'       => __( 'Brand Colors: Secondary', 'redux-framework-demo' ),
        'desc'        => __( 'Select your secondary branding color. Also referred to as an accent color. This will affect various areas of your site, including the color of your primary buttons, link color, the background of some elements and many more.', 'redux-framework-demo' ),
        'id'          => 'color_brand_secondary',
        'default'     => '#428bca',
        'compiler'    => true,
        'customizer'  => array(),
        'transparent' => false,    
        'type'        => 'color'
      ),      

      array( 
        'title'       => __( 'Brand Colors: Success', 'redux-framework-demo' ),
        'desc'        => __( 'Select your branding color for success messages etc. Default: #5cb85c.', 'redux-framework-demo' ),
          'id'          => 'color_brand_success',
          'default'     => '#5cb85c',
          'compiler'    => true,
          'customizer'  => array(),
          'transparent' => false,    
          'type'        => 'color',
      ),

      array( 
        'title'       => __( 'Brand Colors: Warning', 'redux-framework-demo' ),
        'desc'        => __( 'Select your branding color for warning messages etc. Default: #f0ad4e.', 'redux-framework-demo' ),
          'id'          => 'color_brand_warning',
          'default'     => '#f0ad4e',
          'compiler'    => true,
          'customizer'  => array(),
          'type'        => 'color',
          'transparent' => false,    
      ),

      array( 
        'title'       => __( 'Brand Colors: Danger', 'redux-framework-demo' ),
        'desc'        => __( 'Select your branding color for success messages etc. Default: #d9534f.', 'redux-framework-demo' ),
          'id'          => 'color_brand_danger',
          'default'     => '#d9534f',
          'compiler'    => true,
          'customizer'  => array(),
          'type'        => 'color',
          'transparent' => false,    
      ),
      array( 
        'title'       => __( 'Brand Colors: Info', 'redux-framework-demo' ),
        'desc'        => __( 'Select your branding color for info messages etc. It will also be used for the Search button color as well as other areas where it semantically makes sense to use an \'info\' class. Default: #5bc0de.', 'redux-framework-demo' ),
          'id'          => 'color_brand_info',
          'default'     => '#5bc0de',
          'compiler'    => true,
          'customizer'  => array(),
          'type'        => 'color',
          'transparent' => false,    
      )		
    )
  );



  $sections[] = array(
    'icon' => 'repeat',
    'icon_class' => 'icon-large',
    'title' => __('Presets', 'redux-framework-demo'),
    'desc' => __('', 'redux-framework-demo'),
    'fields' => array(

      array(
        'id'           =>'presets',
        'type'         => 'image_select', 
        'presets'      => true,
        'title'        => __('Preset', 'redux-framework-demo'),
        'subtitle'     => __('This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework-demo'),
        'default' 	   => 0,
        'desc'=> __('This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework-demo'),
        'options'       => array(
          '1' => array('alt' => 'Preset 1', 'img' => ReduxFramework::$_url.'../sample/presets/preset1.png', 'presets'=>array('switch-on'=>1,'switch-off'=>1, 'switch-custom'=>1)),
          '2' => array('alt' => 'Preset 2', 'img' => ReduxFramework::$_url.'../sample/presets/preset2.png', 'presets'=>'{"slider1":"1", "slider2":"0", "switch-on":"0"}'),
        ),
      )
    )
  );


  $sections[] = array(
    'icon' => 'text-height',
    'icon_class' => 'icon-large',
    'title' => __('Typography', 'redux-framework-demo'),
    'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
    'fields' => array(

      array( 
        'title'     => __( 'Base Font', 'redux-framework-demo' ),
        'desc'      => __( 'The main font for your site.', 'redux-framework-demo' ),
        'id'        => 'font_base',
        'compiler'  => true,
        'units'     => 'px',
        'default'   => array( 
          'font-family' => 'Arial, Helvetica, sans-serif',
          'font-size'   => '14px',
          'google'      => 'false',
          'weight'      => 'inherit',
          'color'       => '#333333',
        ),
        'preview'   => array( 
          'text'        => __( 'This is my preview text!', 'redux-framework-demo' ), //this is the text from preview box
          'font-size'   => '30px' //this is the text size from preview box
        ),
        'type'      => 'typography',
      ),

      array( 
        'title'     => __( 'Header Overrides', 'redux-framework-demo' ),
        'desc'      => __( 'By enabling this you can specify custom values for each <h*> tag. Default: Off', 'redux-framework-demo' ),
          'id'        => 'font_heading_custom',
          'default'   => 0,
          'compiler'  => true,
          'type'      => 'switch',
          'customizer'=> array(),
          // 'required'  => array('advanced_toggle','=',array('1')),
      ),
      array( 
        'title'     => __( 'H1 Font', 'redux-framework-demo' ),
        'desc'      => __( 'The main font for your site.', 'redux-framework-demo' ),
        'id'        => 'font_h1',
        'compiler'  => true,
        'units'     => '%',
        'default'   => array( 
          'font-family' => 'Arial, Helvetica, sans-serif',
          'font-size'   => '260%',
          'color'       => '#333333',
          'google'      => 'false'
        ),
        'preview'   => array( 
          'text'        => __( 'This is my preview text!', 'redux-framework-demo' ), //this is the text from preview box
          'font-size'   => '30px' //this is the text size from preview box
        ),
        'type'      => 'typography',
        'required'  => array('font_heading_custom','=',array('1')),
      ),
      array( 
        'id'        => 'font_h2',
        'title'     => __( 'H2 Font', 'redux-framework-demo' ),
        'desc'      => __( 'The main font for your site.', 'redux-framework-demo' ),
        'compiler'  => true,
        'units'     => '%',
        'default'   => array( 
          'font-family' => 'Arial, Helvetica, sans-serif',
          'font-size'   => '215%',
          'color'       => '#333333',
          'google'      => 'false'
        ),
        'preview'   => array( 
          'text'        => __( 'This is my preview text!', 'redux-framework-demo' ), //this is the text from preview box
          'font-size'   => '30px' //this is the text size from preview box
        ),
        'type'      => 'typography',
        'required'  => array('font_heading_custom','=',array('1')),    
      ),

      array( 
        'id'        => 'font_h3',
        'title'     => __( 'H3 Font', 'redux-framework-demo' ),
        'desc'      => __( 'The main font for your site.', 'redux-framework-demo' ),
        'compiler'  => true,
        'units'     => '%',
        'default'   => array( 
          'font-family' => 'Arial, Helvetica, sans-serif',
          'font-size'   => '170%',
          'color'       => '#333333',
          'google'      => 'false'
        ),
        'preview'   => array( 
          'text'        => __( 'This is my preview text!', 'redux-framework-demo' ), //this is the text from preview box
          'font-size'   => '30px' //this is the text size from preview box
        ),
        'type'      => 'typography',
        'required'  => array('font_heading_custom','=',array('1')),
      ),

      array( 
        'title'     => __( 'H4 Font', 'redux-framework-demo' ),
        'desc'      => __( 'The main font for your site.', 'redux-framework-demo' ),
        'id'        => 'font_h4',
        'compiler'  => true,
        'units'     => '%',
        'default'   => array( 
          'font-family' => 'Arial, Helvetica, sans-serif',
          'font-size'   => '125%',
          'color'       => '#333333',
          'google'      => 'false'
        ),
        'preview'   => array( 
          'text'    => __( 'This is my preview text!', 'redux-framework-demo' ), //this is the text from preview box
          'font-size'   => '30px' //this is the text size from preview box
        ),
        'type'      => 'typography',
        'required'  => array('font_heading_custom','=',array('1')),
      ),

      array( 
        'title'     => __( 'H5 Font', 'redux-framework-demo' ),
        'desc'      => __( 'The main font for your site.', 'redux-framework-demo' ),
        'id'        => 'font_h5',
        'compiler'  => true,
        'units'     => '%',
        'default'   => array( 
          'font-family' => 'Arial, Helvetica, sans-serif',
          'font-size'   => '100%',
          'color'       => '#333333',
          'google'      => 'false'
        ),
        'preview'       => array( 
          'text'        => __( 'This is my preview text!', 'redux-framework-demo' ), //this is the text from preview box
          'font-size'   => '30px' //this is the text size from preview box
        ),
        'type'      => 'typography',
        'required'  => array('font_heading_custom','=',array('1')),
      ),

      array( 
        'title'     => __( 'H6 Font', 'redux-framework-demo' ),
        'desc'      => __( 'The main font for your site.', 'redux-framework-demo' ),
        'id'        => 'font_h6',
        'compiler'  => true,
        'units'     => '%',
        'default'   => array( 
          'font-family' => 'Arial, Helvetica, sans-serif',
          'font-size'   => '85%',
          'color'       => '#333333',
          'google'      => 'false'
        ),
        'preview'   => array( 
          'text'        => __( 'This is my preview text!', 'redux-framework-demo' ), //this is the text from preview box
          'font-size'   => '30px' //this is the text size from preview box
        ),
        'type'      => 'typography',
        'required'  => array('font_heading_custom','=',array('1')),
      )			
    )
  );


  $sections[] = array(
    'icon' => 'network',
    'icon_class' => 'icon-large',
    'title' => __('Social Sharing', 'redux-framework-demo'),
    'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
    'fields' => array(

      array( 
        'id'        => 'social_sharing_help_1',
        'title'     => __( 'General Options', 'redux-framework-demo' ),
        'type'      => 'info'
      ),

      array( 
        'title'     => __( 'Button Text', 'redux-framework-demo' ),
        'desc'      => __( 'Select the text for the social sharing button.', 'redux-framework-demo' ),
        'id'        => 'social_sharing_text',
        'default'   => 'Share',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Button Location', 'redux-framework-demo' ),
        'desc'      => __( 'Select between NONE, TOP, BOTTOM & BOTH. For archives, \'BOTH\' fallbacks in \'BOTTOM\' only.', 'redux-framework-demo' ),
        'id'        => 'social_sharing_location',
        'default'   => 'top',
        'type'      => 'select',
        'customizer'=> array(),
        'options'   => array( 
          'none'    =>'None',
          'top'     =>'Top',
          'bottom'  =>'Bottom',
          'both'    =>'Both',
        )
      ),

      array( 
        'title'     => __( 'Button Styling', 'redux-framework-demo' ),
        'desc'      => __( 'Select between standard Bootstrap\'s button classes', 'redux-framework-demo' ),
        'id'        => 'social_sharing_button_class',
        'default'   => 'btn-default',
        'type'      => 'select',
        'customizer'=> array(),
        'options'   => array( 
          'btn-default'    => 'Default',
          'btn-primary'    => 'Primary',
          'btn-success'    => 'Success',
          'btn-warning'    => 'Warning',
          'btn-danger'     => 'Danger',
        )
      ),

      array( 
        'title'     => __( 'Show in Posts Archives', 'redux-framework-demo' ),
        'desc'      => __( 'Show the sharing button in posts archives.', 'redux-framework-demo' ),
        'id'        => 'social_sharing_archives',
        'default'   => '',
        'type'      => 'switch'
      ),

      array( 
        'title'     => __( 'Show in Single Post', 'redux-framework-demo' ),
        'desc'      => __( 'Show the sharing button in single post.', 'redux-framework-demo' ),
        'id'        => 'social_sharing_single_post',
        'default'   => '1',
        'type'      => 'switch'
      ),

      array( 
        'title'     => __( 'Show in Single Page', 'redux-framework-demo' ),
        'desc'      => __( 'Show the sharing button in single page.', 'redux-framework-demo' ),
        'id'        => 'social_sharing_single_page',
        'default'   => '1',
        'type'      => 'switch'
      ),

      array( 
        'id'        => 'social_sharing_help_2',
        'title'     => __( 'Select Socials', 'redux-framework-demo' ),
        'type'      => 'info'
      ),

      array( 
        'title'     => __( 'Facebook', 'redux-framework-demo' ),
        'desc'      => __( 'Show the Facebook sharing icon in blog posts.', 'redux-framework-demo' ),
        'id'        => 'facebook_share',
        'default'   => '',
        'type'      => 'switch'
      ),

      array( 
        'title'     => __( 'Google+', 'redux-framework-demo' ),
        'desc'      => __( 'Show the Google+ sharing icon in blog posts.', 'redux-framework-demo' ),
        'id'        => 'google_plus_share',
        'default'   => '',
        'type'      => 'switch'
      ),

      array( 
        'title'     => __( 'LinkedIn', 'redux-framework-demo' ),
        'desc'      => __( 'Show the LinkedIn sharing icon in blog posts.', 'redux-framework-demo' ),
        'id'        => 'linkedin_share',
        'default'   => '',
        'type'      => 'switch'
      ),

      array( 
        'title'     => __( 'Pinterest', 'redux-framework-demo' ),
        'desc'      => __( 'Show the Pinterest sharing icon in blog posts.', 'redux-framework-demo' ),
        'id'        => 'pinterest_share',
        'default'   => '',
        'type'      => 'switch'
      ),

      array( 
        'title'     => __( 'Reddit', 'redux-framework-demo' ),
        'desc'      => __( 'Show the Reddit sharing icon in blog posts.', 'redux-framework-demo' ),
        'id'        => 'reddit_share',
        'default'   => '',
        'type'      => 'switch'
      ),

      array( 
        'title'     => __( 'Tumblr', 'redux-framework-demo' ),
        'desc'      => __( 'Show the Tumblr sharing icon in blog posts.', 'redux-framework-demo' ),
        'id'        => 'tumblr_share',
        'default'   => '',
        'type'      => 'switch'
      ),

      array( 
        'title'     => __( 'Twitter', 'redux-framework-demo' ),
        'desc'      => __( 'Show the Twitter sharing icon in blog posts.', 'redux-framework-demo' ),
        'id'        => 'twitter_share',
        'default'   => '',
        'type'      => 'switch'
      ),

      array( 
        'title'     => __( 'Email', 'redux-framework-demo' ),
        'desc'      => __( 'Show the Email sharing icon in blog posts.', 'redux-framework-demo' ),
        'id'        => 'email_share',
        'default'   => '',
        'type'      => 'switch'
      )

    )

  );



  $sections[] = array(
    'icon' => 'eye-open',
    'icon_class' => 'icon-large',
    'title' => __('Social Links', 'redux-framework-demo'),
    'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
    'fields' => array(

      array( 
        'title'     => __( 'Blogger', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Blogger icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'blogger_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'DeviantART', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the DeviantART icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'deviantart_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Digg', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Digg icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'digg_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Dribbble', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Dribbble icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'dribbble_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Facebook', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Facebook icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'facebook_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Flickr', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Flickr icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'flickr_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'GitHub', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the GitHub icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'github_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Google+', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Google+ icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'google_plus_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'LinkedIn', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the LinkedIn icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'linkedin_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'MySpace', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the MySpace icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'myspace_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Pinterest', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Pinterest icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'pinterest_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Reddit', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Reddit icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'reddit_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'RSS', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the RSS icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'rss_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Skype', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Skype icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'skype_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'SoundCloud', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the SoundCloud icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'soundcloud_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Tumblr', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Tumblr icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'tumblr_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Twitter', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Twitter icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'twitter_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => __( 'Vimeo', 'redux-framework-demo' ),
        'desc'      => __( 'Provide the link you desire and the Vimeo icon will appear. To remove it, just leave it blank.', 'redux-framework-demo' ),
        'id'        => 'vimeo_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),


      array( 
        'title'     => 'Vkontakte',
        'desc'      => 'Provide the link you desire and the Vkontakte icon will appear. To remove it, just leave it blank.',
        'id'        => 'vkontakte_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      ),

      array( 
        'title'     => 'YouTube Link',
        'desc'      => 'Provide the link you desire and the YouTube icon will appear. To remove it, just leave it blank.',
        'id'        => 'youtube_link',
        'validate'  => 'url',
        'default'   => '',
        'type'      => 'text'
      )
    )
  );



  $sections[] = array(
    'icon' => 'photo',
    'icon_class' => 'icon-large',
    'title' => __('Featured Images', 'redux-framework-demo'),
    'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
    'fields' => array(

      array( 
        'id'        => 'help3',
        'title'     => __( 'Featured Images', 'redux-framework-demo' ),
        'desc'      => __( 'Here you can select if you want to display the featured images in post archives and individual posts.
        Please note that these apply to posts, pages, as well as custom post types.
        You can select image sizes independently for archives and individual posts view.', 'redux-framework-demo' ),
        'type'      => 'info',
      ),

      array( 
        'title'     => __( 'Featured Images on Archives', 'redux-framework-demo' ),
        'desc'      => __( 'Display featured Images on post archives ( such as categories, tags, month view etc ). Default: OFF.', 'redux-framework-demo' ),
          'id'        => 'feat_img_archive',
          'default'   => 0,
          'type'      => 'switch',
          'customizer'=> true,
      ),

      array( 
        'title'     => __( 'Featured Images on Archives Full Width', 'redux-framework-demo' ),
        'desc'      => __( 'Display featured Images on posts. Default: OFF.', 'redux-framework-demo' ),
          'id'        => 'feat_img_archive_custom_toggle',
          'default'   => 0,
          'required'  => array('feat_img_archive','=',array('1')),
          'off'       => __( 'Full Width', 'redux-framework-demo' ),
          'on'        => __( 'Custom Dimensions', 'redux-framework-demo' ),
          'type'      => 'switch',
          'customizer'=> true,
      ),

      array( 
        'title'     => __( 'Archives Featured Image Width', 'redux-framework-demo' ),
        'desc'      => __( 'Select the width of your featured images on single posts. Default: 550px', 'redux-framework-demo' ),
          'id'        => 'feat_img_archive_width',
          'default'   => 550,
          'min'       => 100,
          'step'      => 1,
          'max'       => 'screen_large_desktop', //redux-framework-demo_getVariable( 'screen_large_desktop' ),
          'required'  => array('feat_img_archive_custom_toggle','=',array('1')),
          'edit'      => 1,
          'type'      => 'slider'
      ),

      array( 
        'title'     => __( 'Archives Featured Image Height', 'redux-framework-demo' ),
        'desc'      => __( 'Select the height of your featured images on post archives. Default: 300px', 'redux-framework-demo' ),
          'id'        => 'feat_img_archive_height',
          'default'   => 300,
          'min'       => 50,
          'step'      => 1,
          'edit'      => 1,
          'max'       => 'screen_large_desktop', //redux-framework-demo_getVariable( 'screen_large_desktop' ),
          'required'  => array('feat_img_archive_custom_toggle','=',array('1')),
          'type'      => 'slider'
      ),

      array( 
        'title'     => __( 'Featured Images on Posts', 'redux-framework-demo' ),
        'desc'      => __( 'Display featured Images on posts. Default: OFF.', 'redux-framework-demo' ),
          'id'        => 'feat_img_post',
          'default'   => 0,
          'type'      => 'switch',
          'customizer'=> true,
      ),

      array( 
        'title'     => __( 'Featured Images on Posts Full Width', 'redux-framework-demo' ),
        'desc'      => __( 'Display featured Images on posts. Default: OFF.', 'redux-framework-demo' ),
          'id'        => 'feat_img_post_custom_toggle',
          'default'   => 0,
          'off'       => __( 'Full Width', 'redux-framework-demo' ),
          'on'        => __( 'Custom Dimensions', 'redux-framework-demo' ),
          'type'      => 'switch',
          'required'  => array('feat_img_post','=',array('1')),
          'customizer'=> true,
      ),

      array( 
        'title'     => __( 'Posts Featured Image Width', 'redux-framework-demo' ),
        'desc'      => __( 'Select the width of your featured images on single posts. Default: 550px', 'redux-framework-demo' ),
          'id'        => 'feat_img_post_width',
          'default'   => 550,
          'min'       => 100,
          'step'      => 1,
          'max'       => 'screen_large_desktop', //redux-framework-demo_getVariable('screen_large_desktop'),
          'edit'      => 1,
          'required'  => array('feat_img_post_custom_toggle','=',array('1')),
          'type'      => 'slider'
      ),

      array( 
        'title'     => __( 'Posts Featured Image Height', 'redux-framework-demo' ),
        'desc'      => __( 'Select the height of your featured images on single posts. Default: 330px', 'redux-framework-demo' ),
          'id'        => 'feat_img_post_height',
          'default'   => 330,
          'min'       => 50,
          'step'      => 1,
          'max'       => 'screen_large_desktop', //redux-framework-demo_getVariable( 'screen_large_desktop' ),
          'edit'      => 1,
          'required'  => array('feat_img_post_custom_toggle','=',array('1')),
          'type'      => 'slider'
      )
    )
  );



  $sections[] = array(
    'icon' => 'list-alt',
    'icon_class' => 'icon-large',
    'title' => __('Footer', 'redux-framework-demo'),
    'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
    'fields' => array(

      array( 
        'title'       => __( 'Footer Background Color', 'redux-framework-demo' ),
        'desc'        => __( 'Select the background color for your footer. Default: #282a2b.', 'redux-framework-demo' ),
          'id'          => 'footer_background',
          'default'     => '#282a2b',
          'customizer'  => array(),
          'transparent' => false,    
          'type'        => 'color'
      ),

      array( 
        'title'       => __( 'Footer Background Opacity', 'redux-framework-demo' ),
        'desc'        => __( 'Select the opacity level for the footer bar. Default: 100%.', 'redux-framework-demo' ),
          'id'          => 'footer_opacity',
          'default'     => 100,
          'min'         => 0,
          'max'         => 100,
          'type'        => 'slider',
          'required'    => array('retina_toggle','=',array('1')),
      ),

      array( 
        'title'       => __( 'Footer Text Color', 'redux-framework-demo' ),
        'desc'        => __( 'Select the text color for your footer. Default: #8C8989.', 'redux-framework-demo' ),
          'id'          => 'footer_color',
          'default'     => '#8C8989',
          'customizer'  => array(),
          'transparent' => false,    
          'type'        => 'color'
      ),

      array( 
        'title'       => __( 'Footer Text', 'redux-framework-demo' ),
        'desc'        => __( 'The text that will be displayed in your footer. You can use [year] and [sitename] and they will be replaced appropriately. Default: &copy; [year] [sitename]', 'redux-framework-demo' ),
          'id'          => 'footer_text',
          'default'     => '&copy; [year] [sitename]',
          'customizer'  => array(),
          'type'        => 'textarea'
      ),

      array( 
        'title'       => 'Footer Top Border',
        'desc'        => 'Select the border options for your Footer',
        'id'          => 'footer_border_top',
        'type'        => 'border',
        'default'     => array( 
          'border-width'     => '2',
          'border-style'     => 'solid',
          'border-color'     => '#4B4C4D',
        ),
        // 'required'    => array('advanced_toggle','=',array('1'))
      ),

      array( 
        'title'       => __( 'Footer Top Margin', 'redux-framework-demo' ),
        'desc'        => __( 'Select the top margin of footer in pixels. Default: 0px.', 'redux-framework-demo' ),
          'id'          => 'footer_top_margin',
          'default'     => 0,
          'min'         => 0,
          'max'         => 200,
          'type'        => 'slider',
          // 'required'    => array('advanced_toggle','=',array('1'))
      ),

      array( 
        'title'       => __( 'Show social icons in footer', 'redux-framework-demo' ),
        'desc'        => __( 'Show social icons in the footer. Default: On.', 'redux-framework-demo' ),
          'id'          => 'footer_social_toggle',
          'default'     => 0,
          'customizer'  => array(),
          'type'        => 'switch',
          // 'required'    => array('advanced_toggle','=',array('1'))
      ),

      array( 
        'title'       => __( 'Footer social links column width', 'redux-framework-demo' ),
        'desc'        => __( 'You can customize the width of the footer social links area. The footer text width will be adjusted accordingly. Default: 5.', 'redux-framework-demo' ),
          'id'          => 'footer_social_width',
          'required'    => array('footer_social_toggle','=',array('1')),
          'default'     => 6,
          'min'         => 3,
          'step'        => 1,
          'max'         => 10,
          'customizer'  => array(),
          'type'        => 'slider',
      ),    

      array( 
        'title'       => __( 'Footer social icons open new window', 'redux-framework-demo' ),
        'desc'        => __( 'Social icons in footer will open a new window. Default: On.', 'redux-framework-demo' ),
          'id'          => 'footer_social_new_window_toggle',
          'required'    => array('footer_social_toggle','=',array('1')),
          'default'     => 1,
          'customizer'  => array(),
          'type'        => 'switch',
      )
    )
  );

  $sections[] = array(
    'icon' => 'list-alt',
    'icon_class' => 'icon-large',
    'title' => __('Advanced', 'redux-framework-demo'),
    'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
    'fields' => array(

      array( 
        'title'     => __( 'Disable Comments on Blog', 'redux-framework-demo' ),
        'desc'      => __( 'Do not allow site visitors to write comments on blog posts. Default: Off.', 'redux-framework-demo' ),
          'id'        => 'blog_comments_toggle',
          'default'   => 0,
          'type'      => 'switch',
          'customizer'=> array(),
      ),

      array( 
        'title'     => __( 'Post excerpt length', 'redux-framework-demo' ),
        'desc'      => __( 'Select the height of your featured images on post archives. Default: 40px', 'redux-framework-demo' ),
          'id'        => 'post_excerpt_length',
          'default'   => 40,
          'min'       => 10,
          'step'      => 1,
          'max'       => 1000,
          'edit'      => 1,
          'type'      => 'slider'
      ),

      array( 
        'title'     => __( 'Enable Retina mode', 'redux-framework-demo' ),
        'desc'      => __( 'By enabling your site will be retina ready. Requires a all images to be uploaded at 2x the typical size desired, including logos. Default: On', 'redux-framework-demo' ),
          'id'        => 'retina_toggle',
          'default'   => 1,
          'type'      => 'switch',
          'customizer'=> array(),
          // 'required' => array('advanced_toggle','=',array('1')),  
      ),

      array( 
        'title'     => __( 'Dev mode', 'redux-framework-demo' ),
        'desc'      => __( 'By enabling your admin panel will have a Dev Mode Info with an output of the options object for addition debugging. Default: Off', 'redux-framework-demo' ),
          'id'        => 'dev_mode',
          'default'   => 0,
          'type'      => 'switch',
          'customizer'=> array(),
          // 'required'  => array('advanced_toggle','=',array('1')),
      ),    

      array( 
        'title'     => __( 'Allow shortcodes in widgets', 'redux-framework-demo' ),
        'desc'      => __( 'This option allows shortcodes within widgets. Default: On.', 'redux-framework-demo' ),
          'id'        => 'enable_widget_shortcodes',
          'compiler'      => true,
          'default'   => 1,
          'type'      => 'switch',
      ),

      array( 
        'title'     => __( 'Google Analytics ID', 'redux-framework-demo' ),
        'desc'      => __( 'Paste your Google Analytics ID here to enable analytics tracking. Your user ID should be in the form of UA-XXXXX-Y.', 'redux-framework-demo' ),
        'id'        => 'analytics_id',
        'default'   => '',
        'type'      => 'text',
      ),

      array( 
        'title'     => 'Border-Radius and Padding Base',
        'id'        => 'help2',
        'desc'      => __( 'The following settings affect various areas of your site, most notably buttons.', 'redux-framework-demo' ),
        'type'      => 'info',
        // 'required'  => array('advanced_toggle','=',array('1')),
      ),

      array( 
        'title'     => __( 'Border-Radius', 'redux-framework-demo' ),
        'desc'      => __( 'You can adjust the corner-radius of all elements in your site here. This will affect buttons, navbars, widgets and many more. Default: 4', 'redux-framework-demo' ),
          'id'        => 'general_border_radius',
          'default'   => 4,
          'min'       => 0,
          'step'      => 1,
          'max'       => 50,
          'advanced'  => true,
          'compiler'  => true,
          'type'      => 'slider',
          // 'required'  => array('advanced_toggle','=',array('1')),
      ),

      array( 
        'title'     => __( 'Padding Base', 'redux-framework-demo' ),
        'desc'      => __( 'You can adjust the padding base. This affects buttons size and lots of other cool stuff too! Default: 8', 'redux-framework-demo' ),
          'id'        => 'padding_base',
          'default'   => 8,
          'min'       => 0,
          'step'      => 1,
          'max'       => 20,
          'advanced'  => true,
          'compiler'  => true,
          'type'      => 'slider',
          // 'required'  => array('advanced_toggle','=',array('1')),
      ),
    )
  );


  global $ReduxFramework;
  $ReduxFramework = new ReduxFramework($sections, $args, $tabs);

  // END Sample Config


  /**

   Custom function for filtering the sections array. Good for child themes to override or add to the sections.
   Simply include this function in the child themes functions.php file.

   NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
   so you must use get_template_directory_uri() if you want to use any of the built in icons

   **/
  // function add_another_section($sections){
  //     //$sections = array();
  //     $sections[] = array(
  //         'title' => __('A Section added by hook', 'redux-framework-demo'),
  //         'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
  // 		'icon' => 'paper-clip',
  // 		'icon_class' => 'icon-large',
  //         // Leave this as a blank section, no options just some intro text set above.
  // 		'fields' => array(
  // 
  // 			array(
  // 				'id'=>'17',
  // 				'type' => 'date',
  // 				'title' => __('Date Option', 'redux-framework-demo'), 
  // 				'subtitle' => __('No validation can be done on this field type', 'redux-framework-demo'),
  // 				'desc' => __('This is the description field, again good for additional info.', 'redux-framework-demo')
  // 				)
  // 			)
  //         //'fields' => array()
  //     );
  // 
  //     return $sections;
  // }
  // add_filter('redux-opts-sections-redux-sample', 'add_another_section');


  /**

   Custom function for filtering the args array given by a theme, good for child themes to override or add to the args array.

   **/
  function change_framework_args($args){
    //$args['dev_mode'] = false;

    return $args;
  }
  //add_filter('redux-opts-args-redux-sample-file', 'change_framework_args');

  /** 

   Custom function for the callback referenced above

   */
  function my_custom_field($field, $value) {
    print_r($field);
    print_r($value);
  }

  /**

   Custom function for the callback validation referenced above

   **/
  function validate_callback_function($field, $value, $existing_value) {
    $error = false;
    $value =  'just testing';
    /*
     do your validation

     if(something) {
       $value = $value;
     } elseif(something else) {
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

  /**

   This is a test function that will let you see when the compiler hook occurs. 
   It only runs if a field	set with compiler=>true is changed.

   **/
  function testCompiler() {
    echo "Compiler hook!";
  }
  add_action('redux-compiler-redux-sample-file', 'testCompiler');



  //require(get_template_directory() . '/assets/less/redux.php');