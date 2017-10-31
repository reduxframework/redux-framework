<?php

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    Redux::setArgs("bucket_options", array(
        'admin_bar' => TRUE,
        'admin_bar_icon' => '',
        'admin_bar_priority' => 999,
        'admin_stylesheet' => 'custom',
        'ajax_save' => TRUE,
        'allow_sub_menu' => FALSE,
        'async_typography' => FALSE,
        'cdn_check_time' => 1440,
        'class' => '',
        'compiler' => TRUE,
        'customizer' => FALSE,
        'customizer_style' => FALSE,
        'database' => '',
        'default_mark' => '',
        'default_show' => FALSE,
        'dev_mode' => FALSE,
        'disable_google_fonts_link' => FALSE,
        'disable_save_warn' => FALSE,
        'footer_credit' => '<span id="footer-thankyou">Options panel created using <a href="http://www.reduxframework.com/" target="_blank">Redux Framework</a> v3.6.7.7</span>',
        'global_variable' => 'bucket_redux',
        'google_api_key' => 'AIzaSyB7Yj842mK5ogSiDa3eRrZUIPTzgiGopls',
        'google_update_weekly' => FALSE,
        'help_sidebar' => '',
        'help_tabs' => array(),
        'hide_expand' => FALSE,
        'hide_reset' => FALSE,
        'hide_save' => FALSE,
        'hints' => array(
            'icon' => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color' => 'lightgray',
            'icon_size' => 'normal',
            'tip_style' => array(
                'color' => 'light',
                'shadow' => TRUE,
                'rounded' => FALSE,
                'style' => '',
            ),
            'tip_position' => array(
                'my' => 'top_left',
                'at' => 'bottom_right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'effect' => 'slide',
                    'duration' => '500',
                    'event' => 'mouseover',
                ),
                'hide' => array(
                    'effect' => 'fade',
                    'duration' => '500',
                    'event' => 'click mouseleave',
                ),
            ),
        ),
        'import_icon_class' => '',
        'intro_text' => '<h4>Theme Options</h4><p>These allow you to adjust the overall settings for your website.</p>',
        'last_tab' => '',
        'menu_icon' => 'http://localhost/mastoremata/wp-content/themes/mastoremata/wpgrade-core/vendor/redux3/assets/img/theme_options.png',
        'menu_title' => 'Theme Options',
        'menu_type' => 'menu',
        'network_admin' => FALSE,
        'network_sites' => TRUE,
        'open_expanded' => FALSE,
        'options_api' => TRUE,
        'output' => TRUE,
        'output_location' => array(
            'frontend',
        ),
        'output_tag' => TRUE,
        'page_parent' => 'themes.php',
        'page_permissions' => 'manage_options',
        'page_priority' => '60.66',
        'page_slug' => 'bucket_options',
        'page_title' => 'Options',
        'save_defaults' => TRUE,
        'share_icons' => array(
            'twitter' => array(
                'link' => 'http://twitter.com/pixelgrade',
                'title' => 'Follow me on Twitter',
                'img' => 'http://localhost/mastoremata/wp-content/themes/mastoremata/wpgrade-core/vendor/redux3/assets/img/social/Twitter.png',
            ),
            'linked_in' => array(
                'link' => 'http://www.linkedin.com/company/pixelgrade-media',
                'title' => 'Find me on LinkedIn',
                'img' => 'http://localhost/mastoremata/wp-content/themes/mastoremata/wpgrade-core/vendor/redux3/assets/img/social/LinkedIn.png',
            ),
            'facebook' => array(
                'link' => 'http://www.facebook.com/PixelGradeMedia',
                'title' => 'Find me on LinkedIn',
                'img' => 'http://localhost/mastoremata/wp-content/themes/mastoremata/wpgrade-core/vendor/redux3/assets/img/social/Facebook.png',
            ),
        ),
        'show_import_export' => FALSE,
        'show_options_object' => TRUE,
        'system_info' => FALSE,
        'templates_path' => '',
        'transient_time' => 3600,
        'update_notice' => TRUE,
        'use_cdn' => TRUE,
    ));

    Redux::setSections("bucket_options", array(
        array(
            'icon' => 'database-1',
            'icon_class' => '',
            'title' => 'General',
            'desc' => '<p class="description">General settings contains options that have a site-wide effect like defining your site branding (including logo and other icons).</p>',
            'fields' => array(
                array(
                    'id' => 'main_logo',
                    'type' => 'media',
                    'title' => 'Main Logo',
                    'subtitle' => "If there is no image uploaded, plain text will be used instead (generated from the site's name).",
                    'class' => '',
                ),
                array(
                    'id' => 'use_retina_logo',
                    'type' => 'switch',
                    'title' => 'Retina 2x Logo',
                    'subtitle' => 'To be Retina-ready you need to add a 2x logo image (double the dimensions of the 1x logo above).',
                    'class' => '',
                ),
                array(
                    'id' => 'retina_main_logo',
                    'type' => 'media',
                    'title' => 'Retina 2x Logo Image',
                    'required' => array(
                        'use_retina_logo',
                        'equals',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'favicon',
                    'type' => 'media',
                    'title' => 'Favicon',
                    'subtitle' => 'Upload a 16px x 16px image that will be used as a favicon.',
                    'class' => '',
                ),
                array(
                    'id' => 'apple_touch_icon',
                    'type' => 'media',
                    'title' => 'Apple Touch Icon',
                    'subtitle' => 'You can customize the icon for the Apple touch shortcut to your website. The size of this icon must be 77x77px.',
                    'class' => '',
                ),
                array(
                    'id' => 'metro_icon',
                    'type' => 'media',
                    'title' => 'Metro Icon',
                    'subtitle' => 'The size of this icon must be 144x144px.',
                    'class' => '',
                ),
                array(
                    'id' => 'enable_lazy_loading_images',
                    'type' => 'switch',
                    'title' => 'Enable Images Lazy Loading?',
                    'subtitle' => 'Enable this to allow us to lazy load the images so you will increase your page loading speed.',
                    'default' => '1',
                    'class' => '',
                ),
            ),
            'id' => 'general',
        ),
        array(
            'icon' => 'params',
            'icon_class' => '',
            'class' => 'has-customizer customizer-only',
            'title' => 'Style',
            'desc' => '<p class="description">The style options control the general styling of the site, like accent color and Google Web Fonts. You can choose custom fonts for various typography elements with font weight, char set, size and/or height. You also have a live preview for them.</p>',
            'type' => 'customizer_section',
            'fields' => array(
                array(
                    'id' => 'main_color',
                    'type' => 'color',
                    'title' => 'Main Color',
                    'subtitle' => 'Use the color picker to change the main color of the site to match your brand color.',
                    'default' => '#fb4834',
                    'validate' => 'color',
                    'transparent' => FALSE,
                    'compiler' => TRUE,
                    'class' => ' compiler',
                ),
                array(
                    'id' => 'typography-info',
                    'desc' => '<h3>Typography</h3>',
                    'type' => 'info',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'use_google_fonts',
                    'type' => 'switch',
                    'title' => 'Do you need custom web fonts?',
                    'subtitle' => 'Tap into the massive <a href="http://www.google.com/fonts/">Google Fonts</a> collection (with Live preview).',
                    'default' => '0',
                    'compiler' => FALSE,
                    'class' => '',
                ),
                array(
                    'id' => 'google_titles_font',
                    'type' => 'customizer_typography',
                    'color' => FALSE,
                    'font-size' => FALSE,
                    'line-height' => FALSE,
                    'text-align' => FALSE,
                    'required' => array(
                        'use_google_fonts',
                        '=',
                        1,
                    ),
                    'title' => 'Headings Font',
                    'subtitle' => 'Font for titles and headings.',
                    'compiler' => FALSE,
                    'class' => '',
                ),
                array(
                    'id' => 'google_nav_font',
                    'type' => 'customizer_typography',
                    'color' => FALSE,
                    'font-size' => FALSE,
                    'line-height' => FALSE,
                    'text-align' => FALSE,
                    'required' => array(
                        'use_google_fonts',
                        '=',
                        1,
                    ),
                    'title' => 'Navigation Font',
                    'subtitle' => 'Font for navigation menu.',
                    'compiler' => FALSE,
                    'class' => '',
                ),
                array(
                    'id' => 'google_body_font',
                    'type' => 'customizer_typography',
                    'color' => FALSE,
                    'text-align' => FALSE,
                    'required' => array(
                        'use_google_fonts',
                        '=',
                        1,
                    ),
                    'title' => 'Body Font',
                    'subtitle' => 'Font for content text and widget text.',
                    'compiler' => FALSE,
                    'class' => '',
                ),
                array(
                    'id' => 'layout-info',
                    'desc' => '<h3>Layout</h3>',
                    'type' => 'info',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'layout_boxed',
                    'type' => 'switch',
                    'title' => 'Boxed Layout',
                    'subtitle' => 'With Boxed Layout enabled you can use an image as background (go to Appearance - Background).',
                    'default' => '0',
                    'class' => '',
                ),
            ),
            'id' => 'style',
        ),
        array(
            'icon' => 'pencil-1',
            'title' => 'Articles',
            'desc' => '<p class="description">Article options control the various aspects related to displaying posts both in archives and single articles. You can control things like excerpt length and social sharing.</p>',
            'fields' => array(
                array(
                    'id' => 'title_position',
                    'type' => 'select',
                    'title' => 'Single Post Title Position',
                    'subtitle' => 'Choose where to display the article title and meta tags.',
                    'options' => array(
                        'above' => 'Above the Featured Image',
                        'below' => 'Below the Featured Image',
                    ),
                    'default' => 'below',
                    'select2' => array(
                        'minimumResultsForSearch' => -1,
                        'allowClear' => FALSE,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'blog_single_show_title_meta_info',
                    'type' => 'switch',
                    'title' => 'Show Post Title Extra Info',
                    'subtitle' => 'Do you want to show the date and the author under the title?',
                    'default' => '1',
                    'class' => '',
                ),
                array(
                    'id' => 'blog_single_share_links_twitter',
                    'type' => 'checkbox',
                    'title' => 'Twitter Share Link',
                    'desc' => '',
                    'default' => '1',
                    'required' => array(
                        'blog_single_show_share_links',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'blog_single_share_links_facebook',
                    'type' => 'checkbox',
                    'title' => 'Facebook Share Link',
                    'desc' => '',
                    'default' => '1',
                    'required' => array(
                        'blog_single_show_share_links',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'blog_single_share_links_googleplus',
                    'type' => 'checkbox',
                    'title' => 'Google+ Share Link',
                    'desc' => '',
                    'default' => '1',
                    'required' => array(
                        'blog_single_show_share_links',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'blog_single_share_links_pinterest',
                    'type' => 'checkbox',
                    'title' => 'Pinterest Share Link',
                    'desc' => '',
                    'default' => '1',
                    'required' => array(
                        'blog_single_show_share_links',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'blog_single_share_links_position',
                    'type' => 'select',
                    'title' => 'Share Links Position',
                    'subtitle' => 'Choose where to display the share links.',
                    'options' => array(
                        'top' => 'Top',
                        'bottom' => 'Bottom',
                        'both' => 'Both Top & Bottom',
                    ),
                    'default' => 'bottom',
                    'select2' => array(
                        'minimumResultsForSearch' => -1,
                        'allowClear' => FALSE,
                    ),
                    'required' => array(
                        'blog_single_show_share_links',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'blog_single_show_author_box',
                    'type' => 'switch',
                    'title' => 'Show Author Info Box',
                    'subtitle' => 'Do you want to show author info box with avatar and description bellow the post?',
                    'default' => '1',
                    'class' => '',
                ),
                array(
                    'id' => 'blog-archive-info',
                    'desc' => '<h3>Blog Archive</h3>',
                    'type' => 'info',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'blog_layout',
                    'type' => 'image_select',
                    'title' => 'Blog Posts Layout',
                    'subtitle' => 'Choose the layout for blog areas (eg. blog archive page, categories, search results).',
                    'default' => 'masonry',
                    'options' => array(
                        'masonry' => array(
                            0 => 'Masonry',
                            'img' => 'http://localhost/mastoremata/wp-content/themes/mastoremata/theme-content/images/blog-masonry.png',
                        ),
                        'classic' => array(
                            0 => 'Classic',
                            'img' => 'http://localhost/mastoremata/wp-content/themes/mastoremata/theme-content/images/blog-classic.png',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'blog_excerpt_length',
                    'type' => 'text',
                    'title' => 'Excerpt Length',
                    'subtitle' => 'Set the number of words for posts excerpt.',
                    'default' => '20',
                    'class' => '',
                ),
                array(
                    'id' => 'blog_excerpt_more_text',
                    'type' => 'text',
                    'title' => 'Excerpt "More" Text',
                    'subtitle' => 'Change the default [...] with something else (leave empty if you want to remove it).',
                    'default' => '..',
                    'class' => '',
                ),
                array(
                    'id' => 'blog_archive_show_cat_billboard',
                    'type' => 'switch',
                    'title' => 'Show Slider On Category Pages?',
                    'subtitle' => 'Check this if you want to display at the top of your category archives a slider with the posts marked as making part of the category slider.',
                    'default' => '1',
                    'class' => '',
                ),
                array(
                    'id' => 'blog_cat_slider_transition',
                    'type' => 'select',
                    'title' => 'Slider transition',
                    'options' => array(
                        'move' => 'Slide/Move',
                        'fade' => 'Fade',
                    ),
                    'default' => 'move',
                    'select2' => array(
                        'minimumResultsForSearch' => -1,
                        'allowClear' => FALSE,
                    ),
                    'required' => array(
                        'blog_archive_show_cat_billboard',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'blog_cat_slider_autoplay',
                    'type' => 'switch',
                    'title' => 'Slider autoplay',
                    'default' => '0',
                    'required' => array(
                        'blog_archive_show_cat_billboard',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'blog_cat_slider_delay',
                    'type' => 'text',
                    'title' => 'Autoplay delay between slides (in milliseconds)',
                    'default' => '2000',
                    'required' => array(
                        'blog_archive_show_cat_billboard',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'articles',
        ),
        array(
            'icon' => 'note-1',
            'title' => 'Header',
            'desc' => '<p class="description">Header options allow you to control both the visual and functional aspect of the site header. You can choose various layouts, show or hide elements, and change the color scheme (light or dark).</p>',
            'fields' => array(
                array(
                    'id' => 'header_type',
                    'type' => 'image_select',
                    'title' => 'Header Layout Style',
                    'subtitle' => 'Choose the layout for the header area.',
                    'default' => 'type1',
                    'options' => array(
                        'type1' => array(
                            0 => 'Type 1',
                            'img' => 'http://localhost/mastoremata/wp-content/themes/mastoremata/theme-content/images/header-type1.png',
                        ),
                        'type2' => array(
                            0 => 'Type 2',
                            'img' => 'http://localhost/mastoremata/wp-content/themes/mastoremata/theme-content/images/header-type2.png',
                        ),
                        'type3' => array(
                            0 => 'Type 3',
                            'img' => 'http://localhost/mastoremata/wp-content/themes/mastoremata/theme-content/images/header-type3.png',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'header_728_90_ad',
                    'type' => 'ace_editor',
                    'title' => 'Header Ad Code',
                    'subtitle' => 'Paste here the code for the header ad (optimally 720x90px). We will also parse any shortcodes present.',
                    'required' => array(
                        'header_type',
                        'equals',
                        'type2',
                    ),
                    'default' => '<a class="header-ad-link" href="#"><img src="http://placehold.it/728x90" alt="#" /></a>',
                    'mode' => 'html',
                    'theme' => 'chrome',
                    'class' => '',
                ),
                array(
                    'id' => 'nav_inverse_top',
                    'type' => 'switch',
                    'title' => 'Header Top Nav Inverse',
                    'subtitle' => 'Inverse the contrast of the header top navigation bar (black text on white background).',
                    'default' => '0',
                    'class' => '',
                ),
                array(
                    'id' => 'nav_inverse_main',
                    'type' => 'switch',
                    'title' => 'Header Main Nav Inverse',
                    'subtitle' => 'Inverse the contrast of the main navigation bar including sub-menus and mega-menus (black text on white background).',
                    'default' => '0',
                    'class' => '',
                ),
                array(
                    'id' => 'nav_show_header_search',
                    'type' => 'switch',
                    'title' => 'Show Header Search Form',
                    'subtitle' => "Display the search form in the header (it's position may vary depending the Header Type).",
                    'default' => '1',
                    'class' => '',
                ),
                array(
                    'id' => 'nav_main_sticky',
                    'type' => 'switch',
                    'title' => 'Sticky Main Navigation',
                    'subtitle' => 'Pin the Main Navigation to the top of the screen when scrolling down.',
                    'default' => '0',
                    'class' => '',
                ),
            ),
            'id' => 'header',
        ),
        array(
            'icon' => 'tag-1',
            'title' => 'Footer',
            'desc' => '<p class="description">Footer related options including Copyright Text. Other footer elements including widgets and menus can be set from Appearance - Widgets/Menus admin page. </p>',
            'fields' => array(
                array(
                    'id' => 'posts_stats',
                    'type' => 'switch',
                    'title' => 'Posts Stats',
                    'subtitle' => 'Display a monthly based vertical bar graph for posts.',
                    'default' => '1',
                    'class' => '',
                ),
                array(
                    'id' => 'back_to_top',
                    'type' => 'switch',
                    'title' => 'Back to Top Link',
                    'subtitle' => 'Add a link that helps users jump to the top of the page (instead of pressing "Home" key).',
                    'default' => '1',
                    'class' => '',
                ),
                array(
                    'id' => 'copyright_text',
                    'type' => 'editor',
                    'title' => 'Copyright Text',
                    'subtitle' => 'Text that will appear in footer left area (eg. Copyright 2013 Bucket | All Rights Reserved).',
                    'default' => 'Copyright &copy; 2015 Bucket | All rights reserved.',
                    'rows' => 3,
                    'class' => '',
                ),
            ),
            'id' => 'footer',
        ),
        array(
            'type' => 'divide',
            'id' => 5,
        ),
        array(
            'icon' => 'thumbs-up-1',
            'icon_class' => '',
            'title' => 'Social and SEO',
            'desc' => '<p class="description">Social and SEO options allow you to input your social links and choose where to display them. Then you can set the social SEO related info added in the meta tags or used in various widgets.</p>',
            'fields' => array(
                array(
                    'id' => 'social_icons',
                    'type' => 'text_sortable',
                    'title' => 'Social Icons',
                    'subtitle' => 'Define and reorder your social links.<br /><b>Note:</b> These will be displayed in the "Bucket Social Links" widget so you can put them anywhere on your site. Only those filled will appear.<br /><br /><strong> You need to imput the entire URL (ie. http://twitter.com/username)</strong>',
                    'desc' => 'Icons provided by <strong>FontAwesome</strong> and <strong>Entypo</strong>.',
                    'checkboxes' => array(
                        'widget' => 'Widget',
                        'header' => 'Header',
                    ),
                    'options' => array(
                        'flickr' => 'Flickr',
                        'tumblr' => 'Tumblr',
                        'pinterest' => 'Pinterest',
                        'instagram' => 'Instagram',
                        'behance' => 'Behance',
                        'fivehundredpx' => '500px',
                        'deviantart' => 'DeviantART',
                        'dribbble' => 'Dribbble',
                        'twitter' => 'Twitter',
                        'facebook' => 'Facebook',
                        'gplus' => 'Google+',
                        'youtube' => 'Youtube',
                        'vimeo' => 'Vimeo',
                        'linkedin' => 'LinkedIn',
                        'skype' => 'Skype',
                        'soundcloud' => 'SoundCloud',
                        'digg' => 'Digg',
                        'lastfm' => 'Last.FM',
                        'appnet' => 'App.net',
                        'rss' => 'RSS Feed',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'social_icons_target_blank',
                    'type' => 'switch',
                    'title' => 'Open social icons links in new a window?',
                    'subtitle' => 'Do you want to open social links in a new window ?',
                    'default' => '1',
                    'class' => '',
                ),
                array(
                    'id' => 'prepare_for_social_share',
                    'type' => 'switch',
                    'title' => 'Add Social Meta Tags',
                    'subtitle' => 'Let us properly prepare your theme for the social sharing and discovery by adding the needed metatags in the <head> section.',
                    'default' => '1',
                    'class' => '',
                ),
                array(
                    'id' => 'facebook_id_app',
                    'type' => 'text',
                    'title' => 'Facebook Application ID',
                    'subtitle' => 'Enter the Facebook Application ID of the Fan Page which is associated with this website. You can create one <a href="https://developers.facebook.com/apps">here</a>.',
                    'required' => array(
                        'prepare_for_social_share',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'facebook_admin_id',
                    'type' => 'text',
                    'title' => 'Facebook Admin ID',
                    'subtitle' => 'The id of the user that has administrative privileges to your Facebook App so you can access the <a href="https://www.facebook.com/insights/">Facebook Insights</a>.',
                    'required' => array(
                        'prepare_for_social_share',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'google_page_url',
                    'type' => 'text',
                    'title' => 'Google+ Publisher',
                    'subtitle' => 'Enter your Google Plus page ID (example: https://plus.google.com/<b>105345678532237339285</b>) here if you have set up a "Google+ Page".',
                    'required' => array(
                        'prepare_for_social_share',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'twitter_card_site',
                    'type' => 'text',
                    'title' => 'Twitter Site Username',
                    'subtitle' => "The Twitter username of the entire site. The username for the author will be taken from the author's profile (skip the @)",
                    'required' => array(
                        'prepare_for_social_share',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'social_share_default_image',
                    'type' => 'media',
                    'title' => 'Default Social Share Image',
                    'desc' => "If an image is uploaded, this will be used for content sharing if you don't upload a custom image with your content (at least 200px wide recommended).",
                    'class' => '',
                ),
                array(
                    'id' => 'use_twitter_widget',
                    'type' => 'switch',
                    'title' => 'Use Twitter Widget',
                    'subtitle' => 'Just a widget to show your latest tweets (Twitter API v1.1 compatible). You can add it in your blog or footer sidebars.<div class="description">',
                    'default' => '1',
                    'class' => '',
                ),
                array(
                    'id' => 'info_about_twitter_app',
                    'type' => 'info',
                    'title' => 'Important Note : ',
                    'desc' => '<div>In order to use the Twitter widget you will need to create a Twitter application <a href="https://dev.twitter.com/apps/new" >here</a> and get your own key, secrets and access tokens. This is due to the changes that Twitter made to it\'s API (v1.1). Please note that these defaults are used on the theme demo site but they might be disabled at any time, so we <strong>strongly</strong> recommend you to input your own bellow.</div>',
                    'required' => array(
                        'use_twitter_widget',
                        '=',
                        1,
                    ),
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'twitter_consumer_key',
                    'type' => 'text',
                    'title' => 'Consumer Key',
                    'default' => 'UGciUkPwjDpCRyEqcGsbg',
                    'required' => array(
                        'use_twitter_widget',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'twitter_consumer_secret',
                    'type' => 'text',
                    'title' => 'Consumer Secret',
                    'default' => 'nuHkqRLxKTEIsTHuOjr1XX5YZYetER6HF7pKxkV11E',
                    'required' => array(
                        'use_twitter_widget',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'twitter_oauth_access_token',
                    'type' => 'text',
                    'title' => 'Oauth Access Token',
                    'default' => '205813011-oLyghRwqRNHbZShOimlGKfA6BI4hk3KRBWqlDYIX',
                    'required' => array(
                        'use_twitter_widget',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'twitter_oauth_access_token_secret',
                    'type' => 'text',
                    'title' => 'Oauth Access Token Secret',
                    'default' => '4LqlZjf7jDqmxqXQjc6MyIutHCXPStIa3TvEHX9NEYw',
                    'required' => array(
                        'use_twitter_widget',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'social-and-seo',
        ),
        array(
            'icon' => 'database-1',
            'icon_class' => '',
            'title' => 'Custom Code',
            'desc' => '<p class="description">You can change the site style and behaviour by adding custom scripts to all pages within your site using the custom code areas below.</p>',
            'fields' => array(
                array(
                    'id' => 'custom_css',
                    'type' => 'ace_editor',
                    'title' => 'Custom CSS',
                    'subtitle' => 'Enter your custom CSS code. It will be included in the head section of the page.',
                    'desc' => '',
                    'mode' => 'css',
                    'theme' => 'chrome',
                    'compiler' => TRUE,
                    'class' => ' compiler',
                ),
                array(
                    'id' => 'inject_custom_css',
                    'type' => 'select',
                    'title' => 'Apply Custom CSS',
                    'subtitle' => 'Select how to insert the custom CSS into your site.',
                    'default' => 'inline',
                    'compiler' => TRUE,
                    'options' => array(
                        'inline' => 'Inline <em>(recommended)</em>',
                        'file' => 'Write To File (might require file permissions)',
                    ),
                    'select2' => array(
                        'minimumResultsForSearch' => -1,
                        'allowClear' => FALSE,
                    ),
                    'class' => ' compiler',
                ),
                array(
                    'id' => 'custom_js',
                    'type' => 'ace_editor',
                    'title' => 'Custom JavaScript (header)',
                    'subtitle' => 'Enter your custom Javascript code. This code will be loaded in the head section',
                    'mode' => 'text',
                    'compiler' => TRUE,
                    'theme' => 'chrome',
                    'class' => ' compiler',
                ),
                array(
                    'id' => 'custom_js_footer',
                    'type' => 'ace_editor',
                    'title' => 'Custom JavaScript (footer)',
                    'subtitle' => 'This javascript code will be loaded in the footer. You can paste here your <strong>Google Analytics tracking code</strong> (or for what matters any tracking code) and we will put it on every page.',
                    'mode' => 'text',
                    'compiler' => TRUE,
                    'theme' => 'chrome',
                    'class' => ' compiler',
                ),
            ),
            'id' => 'custom-code',
        ),
        array(
            'icon' => 'truck',
            'icon_class' => '',
            'title' => 'Utilities',
            'desc' => '<p class="description">Utilities help you keep up-to-date with new versions of the theme. Also you can import the demo data from here.</p>',
            'fields' => array(
                array(
                    'id' => 'import-demo-data-info',
                    'desc' => '<h3>Import Demo Data</h3>
                    				<p class="description">Here you can import the demo data and get on your way of setting up the site like the theme demo.</p>',
                    'type' => 'info',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'wpGrade_import_demodata_button',
                    'type' => 'info',
                    'desc' => '
                    				<input type="hidden" name="wpGrade-nonce-import-posts-pages" value="6abf18e6c3" />
                    						<input type="hidden" name="wpGrade-nonce-import-theme-options" value="a281ff586f" />
                    						<input type="hidden" name="wpGrade-nonce-import-widgets" value="b1f84e4d33" />
                    						<input type="hidden" name="wpGrade_import_ajax_url" value="http://localhost/mastoremata/wp-admin/admin-ajax.php" />
                    
                    						<a href="#" class="button button-primary" id="wpGrade_import_demodata_button">
                    							Import demo data
                    						</a>
                    
                    						<div class="wpGrade-loading-wrap hidden">
                    							<span class="wpGrade-loading wpGrade-import-loading"></span>
                    							<div class="wpGrade-import-wait">
                    								Please wait a few minutes (between 1 and 3 minutes usually, but depending on your hosting it can take longer) and <strong>don\'t reload the page</strong>. You will be notified as soon as the import has finished!
                    							</div>
                    						</div>
                    
                    						<div class="wpGrade-import-results hidden"></div>
                    						<div class="hr"><div class="inner"><span>&nbsp;</span></div></div>
                    					',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'enable_acf_ui',
                    'type' => 'switch',
                    'title' => 'Enable Advanced Custom Fields Settings',
                    'subtitle' => ' Advanced Custom Fields plugin is already included in Bucket, instead of installing it again you can enable it from here.',
                    'default' => '0',
                    'class' => '',
                ),
                array(
                    'id' => 'admin_panel_options',
                    'type' => 'switch',
                    'title' => 'Admin Panel Options',
                    'subtitle' => 'Here you can copy/download your current admin option settings. Keep this safe as you can use it as a backup should anything go wrong, or you can use it to restore your settings on this site (or any other site).',
                    'class' => '',
                ),
                array(
                    'id' => 'theme_options_import',
                    'type' => 'import_export',
                    'required' => array(
                        'admin_panel_options',
                        '=',
                        1,
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'utilities',
        ),
        array(
            'icon' => 'cart',
            'icon_class' => '',
            'title' => 'WooCommerce',
            'desc' => '<p class="description">WooCommerce options!</p>',
            'fields' => array(
                array(
                    'id' => 'enable_woocommerce_support',
                    'type' => 'switch',
                    'title' => 'Enable WooCommerce Support',
                    'subtitle' => 'Turn this off to avoid loading the WooCommerce assets (CSS and JS).',
                    'default' => '1',
                    'class' => '',
                ),
            ),
            'id' => 'woocommerce',
        ),
        array(
            'icon' => 'cd-1',
            'icon_class' => '',
            'title' => 'Help and Support',
            'desc' => '<p class="description">If you had anything less than a great experience with this theme please contact us now. You can also find answers in our community site, or official articles and tutorials in our knowledge base.</p>
            		<ul class="help-and-support">
            			<li>
            				<a href="http://bit.ly/19G56H1">
            					<span class="community-img"></span>
            					<h4>Community Answers</h4>
            					<span class="description">Get Help from other people that are using this theme.</span>
            				</a>
            			</li>
            			<li>
            				<a href="http://bit.ly/19G5cyl">
            					<span class="knowledge-img"></span>
            					<h4>Knowledge Base</h4>
            					<span class="description">Getting started guides and useful articles to better help you with this theme.</span>
            				</a>
            			</li>
            			<li>
            				<a href="http://bit.ly/new-ticket">
            					<span class="community-img"></span>
            					<h4>Submit a Ticket</h4>
            					<span class="description">File a ticket for a personal response from our support team.</span>
            				</a>
            			</li>
            		</ul>
            	',
            'fields' => array(),
            'id' => 'help-and-support',
        ),
    ));


    Redux::setArgs("wcuc_options", array(
        'admin_bar' => TRUE,
        'admin_bar_icon' => '',
        'admin_bar_priority' => 999,
        'ajax_save' => TRUE,
        'allow_sub_menu' => TRUE,
        'async_typography' => FALSE,
        'cdn_check_time' => 1440,
        'class' => '',
        'compiler' => TRUE,
        'customizer' => TRUE,
        'database' => '',
        'default_mark' => '',
        'default_show' => FALSE,
        'dev_mode' => FALSE,
        'disable_google_fonts_link' => FALSE,
        'disable_save_warn' => FALSE,
        'display_name' => 'Woocommerce Ultimate Customizer',
        'display_version' => '1.0',
        'footer_credit' => '<span id="footer-thankyou">Πίνακα επιλογών που έχει δημιουργηθεί με τη χρήση <a href="http://www.reduxframework.com/" target="_blank">Redux Framework</a> v3.6.7.7</span>',
        'global_variable' => 'wcuc_options',
        'google_api_key' => '',
        'google_update_weekly' => FALSE,
        'help_sidebar' => '',
        'help_tabs' => array(),
        'hide_expand' => FALSE,
        'hide_reset' => FALSE,
        'hide_save' => FALSE,
        'hints' => array(
            'icon' => 'icon-question-sign',
            'icon_position' => 'right',
            'icon_color' => 'lightgray',
            'icon_size' => 'normal',
            'tip_style' => array(
                'color' => 'light',
                'shadow' => TRUE,
                'rounded' => FALSE,
                'style' => '',
            ),
            'tip_position' => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'effect' => 'slide',
                    'duration' => '500',
                    'event' => 'mouseover',
                ),
                'hide' => array(
                    'effect' => 'slide',
                    'duration' => '500',
                    'event' => 'click mouseleave',
                ),
            ),
        ),
        'last_tab' => '',
        'menu_icon' => '',
        'menu_title' => 'Woocommerce Ultimate Customizer',
        'menu_type' => 'menu',
        'network_admin' => FALSE,
        'network_sites' => TRUE,
        'open_expanded' => FALSE,
        'options_api' => TRUE,
        'output' => TRUE,
        'output_location' => array(
            'frontend',
        ),
        'output_tag' => TRUE,
        'page_icon' => 'icon-themes',
        'page_parent' => 'themes.php',
        'page_permissions' => 'manage_options',
        'page_priority' => NULL,
        'page_slug' => '_wcuc_options',
        'page_title' => 'Woocommerce Ultimate Customizer',
        'save_defaults' => TRUE,
        'show_import_export' => TRUE,
        'show_options_object' => TRUE,
        'system_info' => FALSE,
        'templates_path' => '',
        'transient_time' => 3600,
        'update_notice' => TRUE,
        'use_cdn' => TRUE,
    ));

    Redux::setSections("wcuc_options", array(
        array(
            'icon' => 'el-icon-shopping-cart',
            'title' => 'WooCommerce',
            'fields' => array(
                array(
                    'id' => 'ecommerce_functionality',
                    'type' => 'switch',
                    'title' => 'eCommerce functionality',
                    'desc' => 'Set it to "off" to take out the cart, checkout process and "buy now" buttons.',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_quantity_allow_only_single',
                    'type' => 'switch',
                    'title' => 'Sell each product individually',
                    'desc' => 'Enable this to only allow each product to be bought individually in a single order. Quantity buttons will not be visible.',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'ecommerce_add_image_to_product',
                    'type' => 'switch',
                    'title' => 'Autoset feature image',
                    'desc' => 'When an image is added in the library, set feature image to existing product if filename equals sku.',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'title' => 'Enable brands',
                    'id' => 'woocommerce_brands',
                    'default' => 0,
                    'type' => 'switch',
                    'class' => '',
                ),
                array(
                    'id' => 'minicart_info',
                    'type' => 'info',
                    'desc' => 'Minicart',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'minicart_enabled',
                    'type' => 'switch',
                    'title' => 'Minicart',
                    'default' => 0,
                    'compiler' => 'true',
                    'class' => ' compiler',
                ),
                array(
                    'id' => 'minicart_top',
                    'type' => 'slider',
                    'title' => 'Minicart top position',
                    'desc' => 'Default: 20px',
                    'default' => 20,
                    'min' => 1,
                    'step' => 1,
                    'max' => 200,
                    'advanced' => TRUE,
                    'required' => array(
                        'minicart_enabled',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'minicart_product_padding',
                    'type' => 'spacing',
                    'title' => 'Minicart product padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '10px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        '.cart-tab .cart_list.product_list_widget li',
                    ),
                    'required' => array(
                        'minicart_enabled',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'minicart_product_margin',
                    'type' => 'spacing',
                    'title' => 'Minicart product margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '10px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.cart-tab .cart_list.product_list_widget li',
                    ),
                    'required' => array(
                        'minicart_enabled',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'minicart_product_border',
                    'type' => 'border',
                    'title' => 'Minicart product border',
                    'output' => array(
                        '.cart-tab .cart_list.product_list_widget li',
                    ),
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#000000',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '1px',
                        'border-left' => '0px',
                    ),
                    'required' => array(
                        'minicart_enabled',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'woocommerce',
        ),
        array(
            'icon' => 'el-icon-font',
            'title' => 'Typography',
            'subsection' => TRUE,
            'fields' => array(
                array(
                    'id' => 'woocommerce_body_info',
                    'type' => 'info',
                    'desc' => 'WooCommerce body',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_body_font',
                    'type' => 'typography',
                    'title' => 'Body font',
                    'text-transform' => TRUE,
                    'output' => '.woocommerce ul.products li.product .price, .woocommerce ul.products li.product .woocommerce-Price-amount.amount,body.woocommerce.single-product .sku_title,body.woocommerce.single-product .cats_title,body.woocommerce.single-product .tags_title,.woocommerce table.shop_attributes tr td,body.woocommerce.single-product .summary.entry-summary .price, body.woocommerce.single-product .summary.entry-summary .price .amount,.woocommerce div.product p.stock,.woocommerce .sku_wrapper .sku,.woocommerce .woocommerce-error, .woocommerce .woocommerce-info, .woocommerce .woocommerce-message, .woocommerce .woocommerce-error p, .woocommerce .woocommerce-info p, .woocommerce .woocommerce-message p',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_paragraph_padding',
                    'type' => 'spacing',
                    'title' => 'Paragraph padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => array(
                        'body.woocommerce-account .woocommerce-MyAccount-content p',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_title_info',
                    'type' => 'info',
                    'desc' => 'WooCommerce titles (h1, h2 ...)',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_title_font',
                    'type' => 'typography',
                    'title' => 'Title font',
                    'text-transform' => TRUE,
                    'output' => '.woocommerce .cart_totals h2,.woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3,body.woocommerce.single-product h1.product_title ,body.woocommerce section.related.products > h2,.woocommerce #reviews #comments h2,.woocommerce article h1,.woocommerce article h2,.woocommerce article h3, .woocommerce .checkout h2, .woocommerce .checkout h3',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_title_margin',
                    'type' => 'spacing',
                    'title' => 'Title margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => '.woocommerce .cart_totals h2,.woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3,body.woocommerce.single-product h1.product_title,body.woocommerce section.related.products > h2,.woocommerce #reviews #comments h2,.woocommerce article h1,.woocommerce article h2,.woocommerce article h3, .woocommerce .checkout h2, .woocommerce .checkout h3',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_title_padding',
                    'type' => 'spacing',
                    'title' => 'Title padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'output' => '.woocommerce .cart_totals h2,.woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3,body.woocommerce.single-product h1.product_title,body.woocommerce section.related.products > h2,.woocommerce #reviews #comments h2,.woocommerce article h1,.woocommerce article h2,.woocommerce article h3, .woocommerce .checkout h2, .woocommerce .checkout h3',
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_links_info',
                    'type' => 'info',
                    'desc' => 'WooCommerce links (categories, tags)',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_link_font',
                    'type' => 'typography',
                    'title' => 'Link font',
                    'text-transform' => TRUE,
                    'output' => 'body.woocommerce.single-product .cats a,body.woocommerce.single-product .tags a',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_link_bg_color',
                    'type' => 'color',
                    'title' => 'Link Background Color',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => 'body.woocommerce.single-product .cats a,body.woocommerce.single-product .tags a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_link_hover_txt_color',
                    'type' => 'color',
                    'title' => 'Link Text Hover Color',
                    'output' => 'body.woocommerce.single-product .cats a:hover,body.woocommerce.single-product .tags a:hover',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_link_hover_bg_color',
                    'type' => 'color',
                    'title' => 'Link Background Hover Color',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => 'body.woocommerce.single-product .cats a:hover,body.woocommerce.single-product .tags a:hover',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_buttons_info',
                    'type' => 'info',
                    'desc' => 'WooCommerce buttons (add to cart, update cart etc.)',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_btn_font',
                    'type' => 'typography',
                    'title' => 'Button font',
                    'text-transform' => TRUE,
                    'output' => 'body.woocommerce.archive ul.products.default a.button.add_to_cart_button, body.woocommerce.single section.related.products ul.products.default a.button.add_to_cart_button, ul.brands.default .nof_products,.single-product button.button.single_add_to_cart_button,.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, body.woocommerce .woocommerce a.checkout-button, body.woocommerce a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce-page #respond .form-submit input#submit, body.woocommerce #review_form #commentform #submit, body.woocommerce .button.wc-forward,.woocommerce .woocommerce-error .button.wc-forward, .woocommerce .woocommerce-info .button.wc-forward, .woocommerce .woocommerce-message .button.wc-forward',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_btn_bg_color',
                    'type' => 'color',
                    'title' => 'Button Background Color',
                    'default' => '#000000',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce ul.products.default a.button.add_to_cart_button, ul.brands.default .nof_products,.single-product button.button.single_add_to_cart_button,.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, body.woocommerce .woocommerce a.checkout-button, body.woocommerce a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce-page #respond .form-submit input#submit, body.woocommerce #review_form #commentform #submit, body.woocommerce .button.wc-forward,.woocommerce .woocommerce-error .button.wc-forward, .woocommerce .woocommerce-info .button.wc-forward, .woocommerce .woocommerce-message .button.wc-forward',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_btn_active_txt_color',
                    'type' => 'color',
                    'title' => 'Active Button Text Color',
                    'default' => '#000000',
                    'output' => 'body.woocommerce.archive ul.products.default a.button.add_to_cart_button:hover, body.woocommerce.single section.related.products ul.products.default a.button.add_to_cart_button:hover,.single-product button.button.single_add_to_cart_button:hover,.woocommerce #respond input#submit:hover, .woocommerce input.button.alt:hover, .woocommerce a.button:hover, .woocommerce button.button, .woocommerce input.button:hover, body.woocommerce .woocommerce a.checkout-button:hover, body.woocommerce a.button:hover, .woocommerce .widget_price_filter .price_slider_amount .button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce-page #respond .form-submit input#submit:hover, body.woocommerce #review_form #commentform #submit:hover, body.woocommerce .button.wc-forward:hover,.woocommerce .woocommerce-error .button.wc-forward:hover, .woocommerce .woocommerce-info .button.wc-forward:hover, .woocommerce .woocommerce-message .button.wc-forward:hover',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_btn_active_bg_color',
                    'type' => 'color',
                    'title' => 'Active Button Background Color',
                    'default' => '#ffffff',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce ul.products.default a.button.add_to_cart_button:hover,.single-product button.button.single_add_to_cart_button:hover,.woocommerce #respond input#submit:hover, .woocommerce input.button.alt:hover, .woocommerce a.button:hover, .woocommerce button.button, .woocommerce input.button:hover, body.woocommerce .woocommerce a.checkout-button:hover, body.woocommerce a.button:hover, .woocommerce .widget_price_filter .price_slider_amount .button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce-page #respond .form-submit input#submit:hover, body.woocommerce #review_form #commentform #submit:hover, body.woocommerce .button.wc-forward:hover,.woocommerce .woocommerce-error .button.wc-forward:hover, .woocommerce .woocommerce-info .button.wc-forward:hover, .woocommerce .woocommerce-message .button.wc-forward:hover',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_btn_padding',
                    'type' => 'spacing',
                    'title' => 'Button padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '5px',
                        'padding-right' => '5px',
                        'padding-bottom' => '5px',
                        'padding-left' => '5px',
                    ),
                    'output' => '.woocommerce ul.products.default a.button.add_to_cart_button,.single-product button.button.single_add_to_cart_button,.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, body.woocommerce .woocommerce a.checkout-button, body.woocommerce a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce-page #respond .form-submit input#submit, body.woocommerce #review_form #commentform #submit, body.woocommerce .button.wc-forward,.woocommerce .woocommerce-error .button.wc-forward, .woocommerce .woocommerce-info .button.wc-forward, .woocommerce .woocommerce-message .button.wc-forward',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_btn_border',
                    'type' => 'border',
                    'all' => FALSE,
                    'title' => 'Button border',
                    'default' => array(
                        'border-color' => '#ffffff',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => '.woocommerce ul.products.default a.button.add_to_cart_button, ul.brands.default .nof_products,.single-product button.button.single_add_to_cart_button,.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, body.woocommerce .woocommerce a.checkout-button, body.woocommerce a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce-page #respond .form-submit input#submit, body.woocommerce #review_form #commentform #submit, body.woocommerce .button.wc-forward,.woocommerce .woocommerce-error .button.wc-forward, .woocommerce .woocommerce-info .button.wc-forward, .woocommerce .woocommerce-message .button.wc-forward',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_btn_border_radius',
                    'type' => 'slider',
                    'title' => 'Button border radius',
                    'default' => 0,
                    'min' => 0,
                    'step' => 1,
                    'max' => 5,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_inputs_info',
                    'type' => 'info',
                    'desc' => 'WooCommerce inputs (input texts, textareas etc.)',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_hidelabels',
                    'type' => 'switch',
                    'title' => 'Hide labels from billing and shipping input fields',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_label_font',
                    'type' => 'typography',
                    'title' => 'Input label font',
                    'output' => array(
                        '.woocommerce form label, .woocommerce form .form-row .required',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_label_required_asterisk_color',
                    'type' => 'color',
                    'title' => 'Input label required asterisk color',
                    'output' => array(
                        '.woocommerce form .form-row .required',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_placeholder_txt_color',
                    'type' => 'color',
                    'title' => 'Input placeholder text Color',
                    'default' => '#ffffff',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_txt_color',
                    'type' => 'color',
                    'title' => 'Input written text Color',
                    'default' => '#ffffff',
                    'output' => array(
                        '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce-page form .form-row input.input-text, .woocommerce-page form .form-row textarea, .chosen-container-single .chosen-single, body.woocommerce .select2-container a.select2-choice, .woocommerce form.checkout select ,.woocommerce form .select2-container--default .select2-selection--single .select2-selection__rendered',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_bg_color',
                    'type' => 'color',
                    'title' => 'Input Background Color',
                    'default' => '#000000',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce-page form .form-row input.input-text, .woocommerce-page form .form-row textarea, .chosen-container-single .chosen-single, body.woocommerce .select2-container a.select2-choice, .woocommerce form.checkout select ,.woocommerce form .select2-selection',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_border',
                    'type' => 'border',
                    'title' => 'Input border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#ffffff',
                        'border-style' => 'solid',
                        'border-top' => '1px',
                        'border-right' => '1px',
                        'border-bottom' => '1px',
                        'border-left' => '1px',
                    ),
                    'output' => array(
                        '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce-page form .form-row input.input-text, .woocommerce-page form .form-row textarea, .chosen-container-single .chosen-single, body.woocommerce .select2-container a.select2-choice, .woocommerce form.checkout select ,.woocommerce form .form-row .select2-container',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_border_radius',
                    'type' => 'slider',
                    'title' => 'Input border radius',
                    'default' => 0,
                    'min' => 0,
                    'step' => 1,
                    'max' => 5,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_margin',
                    'type' => 'spacing',
                    'title' => 'Input margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '10px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce-page form .form-row input.input-text, .woocommerce-page form .form-row textarea, .chosen-container-single .chosen-single, body.woocommerce .select2-container a.select2-choice, .woocommerce form.checkout select',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_padding',
                    'type' => 'spacing',
                    'title' => 'Input padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '5px',
                        'padding-right' => '5px',
                        'padding-bottom' => '5px',
                        'padding-left' => '5px',
                    ),
                    'output' => array(
                        '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce-page form .form-row input.input-text, .woocommerce-page form .form-row textarea, .chosen-container-single .chosen-single, body.woocommerce .select2-container a.select2-choice, .woocommerce form.checkout select',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_focus_border_color',
                    'type' => 'color',
                    'title' => 'Input focus border color',
                    'default' => '#000000',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_success_border_color',
                    'type' => 'color',
                    'title' => 'Input success border color',
                    'default' => '#69bf29',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_input_invalid_border_color',
                    'type' => 'color',
                    'title' => 'Input invalid border color',
                    'default' => '#a00',
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_quantity_info',
                    'type' => 'info',
                    'desc' => 'Quantity buttons',
                    'required' => array(
                        array(
                            'woocommerce_quantity_allow_only_single',
                            '=',
                            array(
                                '0',
                            ),
                        ),
                    ),
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_quantity_padding',
                    'type' => 'spacing',
                    'title' => 'Holder padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce div.product form.cart div.quantity',
                    ),
                    'required' => array(
                        array(
                            'woocommerce_quantity_allow_only_single',
                            '=',
                            array(
                                '0',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_quantity_color',
                    'type' => 'color',
                    'title' => 'Icon color',
                    'default' => '#ffffff',
                    'output' => array(
                        'color' => '.quantity .minus, .quantity .plus',
                    ),
                    'required' => array(
                        array(
                            'woocommerce_quantity_allow_only_single',
                            '=',
                            array(
                                '0',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_quantity_back_color',
                    'type' => 'color',
                    'title' => 'Icon background color',
                    'default' => '#000000',
                    'output' => array(
                        'background-color' => '.quantity .minus, .quantity .plus',
                    ),
                    'required' => array(
                        array(
                            'woocommerce_quantity_allow_only_single',
                            '=',
                            array(
                                '0',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_quantity_hover_color',
                    'type' => 'color',
                    'title' => 'Icon hover color',
                    'default' => '#000000',
                    'output' => array(
                        'color' => '.quantity .minus:hover, .quantity .plus:hover',
                    ),
                    'required' => array(
                        array(
                            'woocommerce_quantity_allow_only_single',
                            '=',
                            array(
                                '0',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_quantity_hover_back_color',
                    'type' => 'color',
                    'title' => 'Icon hover background color',
                    'default' => '#ffffff',
                    'output' => array(
                        'background-color' => '.quantity .minus:hover, .quantity .plus:hover',
                    ),
                    'required' => array(
                        array(
                            'woocommerce_quantity_allow_only_single',
                            '=',
                            array(
                                '0',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_pagination_info',
                    'type' => 'info',
                    'desc' => 'WooCommerce pagination',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_change_nofproducts',
                    'type' => 'switch',
                    'title' => 'Change number of products per page',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_pagination_nofproducts',
                    'type' => 'slider',
                    'title' => 'Number of products per page',
                    'default' => 10,
                    'min' => 1,
                    'step' => 1,
                    'max' => 50,
                    'required' => array(
                        'woo_change_nofproducts',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_pagination_back_color',
                    'type' => 'color',
                    'title' => 'WooCommerce pagination Background color',
                    'default' => '#ffffff',
                    'output' => array(
                        'background-color' => '.woocommerce #content nav.woocommerce-pagination, .woocommerce nav.woocommerce-pagination, .woocommerce-page #content nav.woocommerce-pagination, .woocommerce-page nav.woocommerce-pagination',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_pagination_border',
                    'type' => 'border',
                    'title' => 'WooCommerce pagination border',
                    'all' => TRUE,
                    'default' => array(
                        'border-color' => '#000000',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '1px',
                        'border-left' => '0px',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_tables_info',
                    'type' => 'info',
                    'desc' => 'WooCommerce tables',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_tables_border',
                    'type' => 'border',
                    'title' => 'Table border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#000000',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce table.shop_table, .woocommerce table.shop_table th, .woocommerce table.shop_table td',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_tables_tr_border',
                    'type' => 'border',
                    'title' => 'Table row bottom border',
                    'all' => FALSE,
                    'left' => FALSE,
                    'right' => FALSE,
                    'top' => FALSE,
                    'output' => array(
                        '.woocommerce table.shop_table tr, .woocommerce-page table.shop_table tr, .woocommerce table.shop_table.woocommerce-cart-form__contents tbody tr.cart_item',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_table_cell_font',
                    'type' => 'typography',
                    'title' => 'Table cell font',
                    'output' => array(
                        '.woocommerce table.shop_table td, .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table strong, .woocommerce table.shop_table tfoot td, .woocommerce table.shop_table tfoot th',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_table_cell_back_color',
                    'type' => 'color',
                    'title' => 'Table cell background color',
                    'output' => array(
                        'background-color' => '.woocommerce table.shop_table td, .woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table strong, .woocommerce table.shop_table tfoot td, .woocommerce table.shop_table tfoot th',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_table_header_font',
                    'type' => 'typography',
                    'title' => 'Table header font',
                    'output' => array(
                        '.woocommerce table.shop_table th',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_table_header_back_color',
                    'type' => 'color',
                    'title' => 'Table header background color',
                    'output' => array(
                        'background-color' => '.woocommerce table.shop_table th',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_table_cell_padding',
                    'type' => 'spacing',
                    'title' => 'Table cell padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => array(
                        'body.woocommerce table.shop_table th, body.woocommerce .woocommerce table.shop_table td',
                    ),
                    'default' => array(
                        'padding-top' => '10px',
                        'padding-right' => '10px',
                        'padding-bottom' => '10px',
                        'padding-left' => '10px',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_saleprices_info',
                    'type' => 'info',
                    'desc' => 'Sale prices',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_saleprices_icon_back_color',
                    'type' => 'color',
                    'title' => 'Icon background color',
                    'default' => '#000000',
                    'output' => array(
                        'background-color' => '.woocommerce span.onsale, .woocommerce .product .onsale',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_saleprices_icon_font',
                    'type' => 'typography',
                    'title' => 'Icon font',
                    'output' => array(
                        '.woocommerce .product .onsale, .woocommerce ul.products li.product .onsale',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_saleprices_percentage',
                    'type' => 'switch',
                    'title' => 'Show percentage in icon',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_saleprices_icon_padding',
                    'type' => 'spacing',
                    'title' => 'Icon padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '10px',
                        'padding-right' => '10px',
                        'padding-bottom' => '10px',
                        'padding-left' => '10px',
                    ),
                    'output' => array(
                        '.woocommerce span.onsale',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_info',
                    'type' => 'info',
                    'desc' => 'Menu icons (profile and cart icons)',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_fontset',
                    'type' => 'select',
                    'title' => 'Icon fontset',
                    'default' => 'font_awesome',
                    'options' => array(
                        'font_awesome' => 'Font awesome',
                        'linea' => 'Linea',
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_iconset_font',
                    'type' => 'typography',
                    'title' => 'Icon',
                    'output' => array(
                        '.woo_profile a i',
                    ),
                    'font-family' => FALSE,
                    'font-style' => FALSE,
                    'font-weight' => FALSE,
                    'text-align' => FALSE,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_hover_color',
                    'type' => 'color',
                    'title' => 'Icon hover color',
                    'default' => '#000000',
                    'output' => array(
                        'color' => '.woo_profile a:hover',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_btn_margin',
                    'type' => 'spacing',
                    'title' => 'Icon margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.woo_profile',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_loggedin_color',
                    'type' => 'color',
                    'title' => 'Loggein in profile icon color',
                    'default' => '#000000',
                    'output' => array(
                        'color' => '.woo_profile.logged_in a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_cartcounter_color',
                    'type' => 'color',
                    'title' => 'Cart counter color',
                    'default' => '#ffffff',
                    'output' => array(
                        'color' => '.woo_profile .cart_counter',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_cartcounter__back_color',
                    'type' => 'color',
                    'title' => 'Cart counter Background color',
                    'default' => '#000000',
                    'output' => array(
                        'background-color' => '.woo_profile .cart_counter',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_showtext',
                    'type' => 'switch',
                    'title' => 'Show text right next to the icon',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_profile_text',
                    'type' => 'text',
                    'title' => 'My account text',
                    'default' => 'My account',
                    'required' => array(
                        'woocommerce_menuicons_showtext',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_cart_text',
                    'type' => 'text',
                    'title' => 'My cart text',
                    'default' => 'My cart',
                    'required' => array(
                        'woocommerce_menuicons_showtext',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_font',
                    'type' => 'typography',
                    'title' => 'Icon text font',
                    'text-transform' => TRUE,
                    'output' => array(
                        '.woo_profile .woo_icon_text',
                    ),
                    'required' => array(
                        'woocommerce_menuicons_showtext',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_menuicons_hover_color',
                    'type' => 'color',
                    'title' => 'Icon text hover color',
                    'output' => array(
                        '.woo_profile:hover .woo_icon_text',
                    ),
                    'required' => array(
                        'woocommerce_menuicons_showtext',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'typography',
        ),
        array(
            'icon' => 'el-icon-comment',
            'title' => 'Notices',
            'desc' => 'WooCommerce notices (success, error and notice messages).',
            'subsection' => TRUE,
            'fields' => array(
                array(
                    'id' => 'woocommerce_notices_style_select',
                    'type' => 'select',
                    'title' => 'Notice box style',
                    'default' => 'default',
                    'customizer' => array(),
                    'options' => array(
                        'default' => 'Default',
                        'custom' => 'Custom',
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_back_color',
                    'type' => 'color',
                    'title' => 'Background color',
                    'default' => '#ffffff',
                    'output' => array(
                        'background-color' => '.woocommerce .woocommerce-error, .woocommerce .woocommerce-info, .woocommerce .woocommerce-message, .woocommerce-page .woocommerce-error, .woocommerce-page .woocommerce-info, .woocommerce-page .woocommerce-message',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_text_typography_custom',
                    'type' => 'switch',
                    'title' => 'Text Customize Typography',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_text_font',
                    'type' => 'typography',
                    'title' => 'Text font',
                    'text-transform' => TRUE,
                    'output' => '.woocommerce .woocommerce-error, .woocommerce .woocommerce-info, .woocommerce .woocommerce-message, .woocommerce .woocommerce-error p, .woocommerce .woocommerce-info p, .woocommerce .woocommerce-message p',
                    'required' => array(
                        array(
                            'woocommerce_notices_text_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_padding',
                    'type' => 'spacing',
                    'title' => 'Padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '10px',
                        'padding-right' => '10px',
                        'padding-bottom' => '10px',
                        'padding-left' => '10px',
                    ),
                    'required' => array(
                        'woocommerce_notices_style_select',
                        '=',
                        array(
                            'custom',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_border',
                    'type' => 'border',
                    'title' => 'Border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#000000',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce .woocommerce-error, .woocommerce .woocommerce-info, .woocommerce .woocommerce-message, .woocommerce-page .woocommerce-error, .woocommerce-page .woocommerce-info, .woocommerce-page .woocommerce-message',
                    ),
                    'required' => array(
                        'woocommerce_notices_style_select',
                        '=',
                        array(
                            'custom',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_border_radius',
                    'type' => 'slider',
                    'title' => 'Border radius',
                    'default' => 0,
                    'min' => 0,
                    'step' => 1,
                    'max' => 5,
                    'required' => array(
                        'woocommerce_notices_style_select',
                        '=',
                        array(
                            'custom',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_symbol_color_select',
                    'type' => 'select',
                    'title' => 'Symbol color',
                    'default' => 'default',
                    'customizer' => array(),
                    'options' => array(
                        'default' => 'Default colors',
                        'simple_custom_color' => 'Simple custom color',
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'required' => array(
                        'woocommerce_notices_style_select',
                        '=',
                        array(
                            'default',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_symbol_color',
                    'type' => 'color',
                    'title' => 'Symbol color',
                    'default' => '#000000',
                    'output' => array(
                        'color' => '.woocommerce .woocommerce-message::before, .woocommerce .woocommerce-info::before',
                    ),
                    'required' => array(
                        'woocommerce_notices_symbol_color_select',
                        '=',
                        array(
                            'simple_custom_color',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_line_color_select',
                    'type' => 'select',
                    'title' => 'Line color',
                    'default' => 'default',
                    'customizer' => array(),
                    'options' => array(
                        'default' => 'Default colors',
                        'simple_custom_color' => 'Simple custom color',
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'required' => array(
                        'woocommerce_notices_style_select',
                        '=',
                        array(
                            'default',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_line_color',
                    'type' => 'color',
                    'title' => 'Line color',
                    'default' => '#000000',
                    'required' => array(
                        'woocommerce_notices_line_color_select',
                        '=',
                        array(
                            'simple_custom_color',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_button_info',
                    'type' => 'info',
                    'desc' => 'Button inside notice',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_button_typography_custom',
                    'type' => 'switch',
                    'title' => 'Add To Cart Customize Typography',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_button_font',
                    'type' => 'typography',
                    'title' => 'Button font',
                    'text-transform' => TRUE,
                    'output' => '.woocommerce .woocommerce-error .button.wc-forward, .woocommerce .woocommerce-info .button.wc-forward, .woocommerce .woocommerce-message .button.wc-forward',
                    'required' => array(
                        array(
                            'woocommerce_notices_button_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_button_bg_color',
                    'type' => 'color',
                    'title' => 'Button Background Color',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce .woocommerce-error .button.wc-forward, .woocommerce .woocommerce-info .button.wc-forward, .woocommerce .woocommerce-message .button.wc-forward',
                    ),
                    'required' => array(
                        array(
                            'woocommerce_notices_button_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_button_active_txt_color',
                    'type' => 'color',
                    'title' => 'Active Button Text Color',
                    'output' => array(
                        '.woocommerce .woocommerce-error .button.wc-forward:hover, .woocommerce .woocommerce-info .button.wc-forward:hover, .woocommerce .woocommerce-message .button.wc-forward:hover',
                    ),
                    'required' => array(
                        array(
                            'woocommerce_notices_button_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_button_active_bg_color',
                    'type' => 'color',
                    'title' => 'Active Button Background Color',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce .woocommerce-error .button.wc-forward:hover, .woocommerce .woocommerce-info .button.wc-forward:hover, .woocommerce .woocommerce-message .button.wc-forward:hover',
                    ),
                    'required' => array(
                        array(
                            'woocommerce_notices_button_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_button_padding',
                    'type' => 'spacing',
                    'title' => 'Button padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => '.woocommerce .woocommerce-error .button.wc-forward, .woocommerce .woocommerce-info .button.wc-forward, .woocommerce .woocommerce-message .button.wc-forward',
                    'required' => array(
                        array(
                            'woocommerce_notices_button_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_notices_button_border',
                    'type' => 'border',
                    'title' => 'Button border',
                    'all' => FALSE,
                    'output' => '.woocommerce .woocommerce-error .button.wc-forward, .woocommerce .woocommerce-info .button.wc-forward, .woocommerce .woocommerce-message .button.wc-forward',
                    'required' => array(
                        array(
                            'woocommerce_notices_button_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'notices',
        ),
        array(
            'icon' => 'el-icon-th',
            'title' => 'Product archives',
            'subsection' => TRUE,
            'fields' => array(
                array(
                    'id' => 'woo_product_archives_style',
                    'type' => 'select',
                    'title' => 'Product archives Style',
                    'default' => 'boxed',
                    'options' => array(
                        'wide' => 'Wide',
                        'boxed' => 'Boxed',
                    ),
                    'required' => array(
                        'site_style',
                        '=',
                        array(
                            'wide',
                        ),
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_packery',
                    'type' => 'switch',
                    'title' => 'Product archives packery grid',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_gutter',
                    'type' => 'slider',
                    'title' => 'Grid gutter',
                    'default' => 0,
                    'min' => 0,
                    'step' => 1,
                    'max' => 25,
                    'required' => array(
                        'woo_product_archives_packery',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_columns',
                    'type' => 'slider',
                    'title' => 'Grid columns',
                    'default' => 4,
                    'min' => 2,
                    'step' => 1,
                    'max' => 6,
                    'required' => array(
                        'woo_product_archives_packery',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_smartphones_columns',
                    'type' => 'slider',
                    'title' => 'Grid columns in smartphones',
                    'default' => 1,
                    'min' => 1,
                    'step' => 1,
                    'max' => 2,
                    'required' => array(
                        'woo_product_archives_packery',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_type',
                    'type' => 'select',
                    'title' => 'Grid type',
                    'default' => 'default',
                    'options' => array(
                        'default' => 'Title below image. Price next to title.',
                        'text_below_cart_below' => 'Title below image. Price below title. Add to cart below price.',
                        'minimal' => 'Text over image.',
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_thumbnail_hover_info',
                    'type' => 'info',
                    'desc' => 'Thumbnail hover',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_thumbnail_hover_zoom',
                    'type' => 'switch',
                    'title' => 'Zoom thumbnail on hover',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_image_info',
                    'type' => 'info',
                    'desc' => 'Thumbnail image',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_image_border',
                    'type' => 'border',
                    'title' => 'Image border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#ffffff',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce ul.products li.product a img, .woocommerce-page ul.products li.product a img, .woocommerce ul.products li.product a:hover img, .woocommerce-page ul.products li.product a:hover img',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_overlay_box_color',
                    'type' => 'color',
                    'title' => 'Image overlay box color',
                    'validate' => 'color',
                    'output' => array(
                        'background-color' => 'ul.products.default .thumb_back_box, ul.brands.default .thumb_back_box',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_overlay_box_hover_opacity',
                    'type' => 'slider',
                    'title' => 'Image overlay box hover opacity',
                    'default' => 0.5,
                    'min' => 0,
                    'step' => 0.10000000000000001,
                    'max' => 1,
                    'resolution' => 0.10000000000000001,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_fade_in_images_flag',
                    'type' => 'switch',
                    'title' => 'Fade in product archive images',
                    'default' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_fade_in_images_transition_time',
                    'type' => 'slider',
                    'title' => 'Fade in image transition time (in seconds)',
                    'default' => 0.29999999999999999,
                    'min' => 0.29999999999999999,
                    'step' => 0.10000000000000001,
                    'max' => 5,
                    'resolution' => 0.10000000000000001,
                    'required' => array(
                        'woo_fade_in_images_flag',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Exclude images from fading',
                    'description' => 'Provide here the classes from the images that you want to exclude from fading (eg. .myclass1,.myclass2)',
                    'id' => 'woo_exclude_image_classes_fade',
                    'type' => 'text',
                    'required' => array(
                        'woo_fade_in_images_flag',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addtocart_info',
                    'type' => 'info',
                    'desc' => 'Thumbnail Add To Cart button',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addtocart_max_width',
                    'title' => 'Add to cart button max width',
                    'subtitle' => 'Set it to 0 to use 100% width.',
                    'default' => 0,
                    'min' => 0,
                    'step' => 10,
                    'max' => 1000,
                    'type' => 'slider',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addtocart_max_margin',
                    'type' => 'spacing',
                    'title' => 'Add to cart button margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce ul.products.default a.button.add_to_cart_button',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addtocart_typography_custom',
                    'type' => 'switch',
                    'title' => 'Add to cart button font Custom',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addtocart_font',
                    'type' => 'typography',
                    'title' => 'Add to cart button font',
                    'text-transform' => TRUE,
                    'output' => 'body.woocommerce.archive ul.products.default a.button.add_to_cart_button, body.woocommerce.single section.related.products ul.products.default a.button.add_to_cart_button, ul.brands.default .nof_products',
                    'required' => array(
                        array(
                            'woo_product_archives_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addtocart_bg_color',
                    'type' => 'color',
                    'title' => 'Add to cart button Background Color',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce ul.products.default a.button.add_to_cart_button, ul.brands.default .nof_products',
                    ),
                    'required' => array(
                        array(
                            'woo_product_archives_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addtocarthover_color',
                    'type' => 'color',
                    'title' => 'Add to cart button hover color',
                    'validate' => 'color',
                    'output' => array(
                        'body.woocommerce.archive ul.products.default a.button.add_to_cart_button:hover, body.woocommerce.single section.related.products ul.products.default a.button.add_to_cart_button:hover',
                    ),
                    'required' => array(
                        array(
                            'woo_product_archives_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addtocarthover_bg_color',
                    'type' => 'color',
                    'title' => 'Add to cart button hover background color',
                    'validate' => 'color',
                    'output' => array(
                        'background-color' => '.woocommerce ul.products.default a.button.add_to_cart_button:hover',
                    ),
                    'required' => array(
                        array(
                            'woo_product_archives_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addtocart_padding',
                    'type' => 'spacing',
                    'title' => 'Add To Cart padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => '.woocommerce ul.products.default a.button.add_to_cart_button',
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'required' => array(
                        array(
                            'woo_product_archives_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addtocart_border',
                    'type' => 'border',
                    'title' => 'Add to cart button border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#ffffff',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => '.woocommerce ul.products.default a.button.add_to_cart_button, ul.brands.default .nof_products',
                    'required' => array(
                        array(
                            'woo_product_archives_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addedtocart_font',
                    'type' => 'typography',
                    'title' => 'Added to cart button font',
                    'output' => array(
                        'body.woocommerce ul.products.default .added_to_cart',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_addedtocarthover_color',
                    'type' => 'color',
                    'title' => 'Added to cart button hover color',
                    'validate' => 'color',
                    'output' => array(
                        'body.woocommerce ul.products.default .added_to_cart:hover',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_title_info',
                    'type' => 'info',
                    'desc' => 'Thumbnail title',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_title_typography_custom',
                    'type' => 'switch',
                    'title' => 'Grid title Customize Typography',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_title_font',
                    'type' => 'typography',
                    'title' => 'Grid title font',
                    'text-transform' => TRUE,
                    'output' => '.woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3',
                    'required' => array(
                        array(
                            'woo_product_archives_title_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_title_padding',
                    'type' => 'spacing',
                    'title' => 'Grid title padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '10px',
                        'padding-right' => '0px',
                        'padding-bottom' => '10px',
                        'padding-left' => '0px',
                    ),
                    'output' => '.woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3',
                    'required' => array(
                        array(
                            'woo_product_archives_title_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_title_border',
                    'type' => 'border',
                    'title' => 'Grid title border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#ffffff',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_title_hover_color',
                    'type' => 'color',
                    'title' => 'Grid title hover color',
                    'validate' => 'color',
                    'output' => array(
                        '.woocommerce ul.products li.product:hover .woocommerce-loop-product__title, .woocommerce ul.products li.product:hover h3',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_title_price_border',
                    'type' => 'border',
                    'title' => 'Grid title-price holder border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#ffffff',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce ul.products li.product .text_price',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_title_price_back',
                    'type' => 'color',
                    'title' => 'Grid title-price holder background color',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce ul.products li.product .text_price',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_title_price_min_height',
                    'title' => 'Grid title-price holder min height',
                    'subtitle' => 'Set it to 0 to use auto. Use it if you want all your thumbnails to have the same height.',
                    'default' => 0,
                    'min' => 0,
                    'step' => 1,
                    'max' => 300,
                    'type' => 'slider',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_price_info',
                    'type' => 'info',
                    'desc' => 'Thumbnail price',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_price_font_custom',
                    'type' => 'switch',
                    'title' => 'Grid price font Custom',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_price_font',
                    'type' => 'typography',
                    'title' => 'Grid price font',
                    'text-transform' => TRUE,
                    'output' => '.woocommerce ul.products li.product .price, .woocommerce ul.products li.product .woocommerce-Price-amount.amount',
                    'required' => array(
                        array(
                            'woo_product_archives_price_font_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_price_margin',
                    'type' => 'spacing',
                    'title' => 'Grid price margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce ul.products li.product .price',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_price_padding',
                    'type' => 'spacing',
                    'title' => 'Grid price padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '10px',
                        'padding-right' => '0px',
                        'padding-bottom' => '10px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce ul.products li.product .price',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_archives_price_border',
                    'type' => 'border',
                    'title' => 'Grid price border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#ffffff',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce ul.products li.product .price',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_breadcrums',
                    'type' => 'switch',
                    'title' => 'WooCommerce breadcrums',
                    'default' => 0,
                    'required' => array(
                        'woo_product_archives_packery',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_result_count_text',
                    'type' => 'switch',
                    'title' => 'Output the result count text (Showing x - x of x results)',
                    'default' => 1,
                    'required' => array(
                        'woo_product_archives_packery',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting',
                    'type' => 'info',
                    'desc' => 'Product sorting',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_options',
                    'type' => 'switch',
                    'title' => 'WooCommerce product sorting options',
                    'default' => 1,
                    'required' => array(
                        'woo_product_archives_packery',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_min_width',
                    'title' => 'Sorting options min width',
                    'subtitle' => 'Set it to 0 to use auto.',
                    'default' => 0,
                    'min' => 0,
                    'step' => 10,
                    'max' => 400,
                    'type' => 'slider',
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_margin',
                    'type' => 'spacing',
                    'title' => 'Sorting options margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce .woocommerce-ordering',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_select_font',
                    'type' => 'typography',
                    'title' => 'Select font',
                    'text-transform' => TRUE,
                    'output' => array(
                        '.woocommerce-ordering button, .woocommerce-ordering button i',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_select_color_active',
                    'type' => 'color',
                    'title' => 'Select active color',
                    'output' => array(
                        '.woocommerce-ordering .dropdown.open button, .woocommerce-ordering .dropdown button:hover',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_select_border',
                    'type' => 'border',
                    'title' => 'Select border',
                    'default' => array(
                        'border-color' => '#ffffff',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-ordering button',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_select_back',
                    'type' => 'color',
                    'title' => 'Select background color',
                    'default' => '#000000',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce-ordering button',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_option_holder_back',
                    'type' => 'color',
                    'title' => 'Options wrapper background color',
                    'default' => '#000000',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce-ordering .dropdown_menu',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_option_holder_border',
                    'type' => 'border',
                    'title' => 'Options wrapper border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#ffffff',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-ordering .dropdown_menu',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_option_holder_padding',
                    'type' => 'spacing',
                    'title' => 'Options wrapper padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '10px',
                        'padding-right' => '10px',
                        'padding-bottom' => '10px',
                        'padding-left' => '10px',
                    ),
                    'output' => array(
                        '.woocommerce-ordering .dropdown_menu',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_option_font',
                    'type' => 'typography',
                    'title' => 'Options font',
                    'text-transform' => TRUE,
                    'output' => array(
                        'body.woocommerce .woocommerce-ordering .dropdown_menu li a',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_option_color_active',
                    'type' => 'color',
                    'title' => 'Option button active color',
                    'output' => array(
                        'body.woocommerce .woocommerce-ordering .dropdown_menu li:hover a',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_option_back',
                    'type' => 'color',
                    'title' => 'Options background color',
                    'default' => '#000000',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce-ordering .dropdown_menu li a',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_product_sorting_option_padding',
                    'type' => 'spacing',
                    'title' => 'Option button padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '5px',
                        'padding-right' => '0px',
                        'padding-bottom' => '5px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        'background-color' => '.woocommerce-ordering .dropdown_menu li a',
                    ),
                    'required' => array(
                        'woo_product_sorting_options',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_widget_padding',
                    'type' => 'spacing',
                    'title' => 'Widget padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '20px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        '.widget',
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'product-archives',
        ),
        array(
            'icon' => 'el-icon-screen',
            'title' => 'Single product',
            'subsection' => TRUE,
            'fields' => array(
                array(
                    'id' => 'woo_single_style',
                    'type' => 'select',
                    'title' => 'Page Style',
                    'default' => 'boxed',
                    'options' => array(
                        'wide' => 'Wide',
                        'boxed' => 'Boxed',
                    ),
                    'required' => array(
                        'site_style',
                        '=',
                        array(
                            'wide',
                        ),
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_image_info',
                    'type' => 'info',
                    'desc' => 'Image',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_image_position',
                    'type' => 'button_set',
                    'title' => 'Image position',
                    'options' => array(
                        1 => 'Left',
                        2 => 'Right',
                    ),
                    'default' => '1',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_image_border',
                    'type' => 'border',
                    'title' => 'Image border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#000000',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-product-gallery',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_image_zoom',
                    'type' => 'switch',
                    'title' => 'Zoom image on hover',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_summary_info',
                    'type' => 'info',
                    'desc' => 'Summary',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_summary_margin',
                    'type' => 'spacing',
                    'title' => 'Summary margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-page div.product div.summary ',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_summary_border',
                    'type' => 'border',
                    'title' => 'Summary border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#000000',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-page div.product div.summary',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_summary_padding',
                    'type' => 'spacing',
                    'title' => 'Summary padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-page div.product div.summary ',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_summary_backcolor',
                    'type' => 'color',
                    'title' => 'Summary background color',
                    'output' => array(
                        'background-color' => '.woocommerce-page div.product div.summary',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_summary_sameheight',
                    'type' => 'switch',
                    'title' => 'Make summary same height with image gallery',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_summary_enable_bottom_holder',
                    'type' => 'switch',
                    'title' => 'Enable bottom holder',
                    'default' => 0,
                    'required' => array(
                        'woo_single_summary_sameheight',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_title_info',
                    'type' => 'info',
                    'desc' => 'Product title',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_title_typography_custom',
                    'type' => 'switch',
                    'title' => 'Product title Customize Typography',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_title_font',
                    'type' => 'typography',
                    'title' => 'Product title font',
                    'text-transform' => TRUE,
                    'output' => 'body.woocommerce.single-product h1.product_title ',
                    'required' => array(
                        array(
                            'woo_single_product_title_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_title_padding',
                    'type' => 'spacing',
                    'title' => 'Product title padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => 'body.woocommerce.single-product h1.product_title',
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'required' => array(
                        array(
                            'woo_single_product_title_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_title_border',
                    'type' => 'border',
                    'title' => 'Product title border',
                    'all' => FALSE,
                    'output' => array(
                        'body.woocommerce.single-product h1',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_sku_info',
                    'type' => 'info',
                    'desc' => 'SKU',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_show_sku',
                    'type' => 'switch',
                    'title' => 'Show SKU',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_sku_inline',
                    'type' => 'switch',
                    'title' => 'SKU inline ',
                    'default' => 0,
                    'required' => array(
                        'woo_single_show_sku',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_sku_order',
                    'type' => 'slider',
                    'title' => 'SKU order',
                    'default' => 15,
                    'min' => 10,
                    'step' => 1,
                    'max' => 50,
                    'required' => array(
                        'woo_single_show_sku',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_sku_padding',
                    'type' => 'spacing',
                    'title' => 'SKU wrapper padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => array(
                        'body.woocommerce.single-product .sku_wrapper',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'required' => array(
                        'woo_single_show_sku',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_sku_title_font_custom',
                    'type' => 'switch',
                    'title' => 'SKU title font Custom',
                    'default' => 0,
                    'required' => array(
                        array(
                            'woo_single_show_sku',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_sku_title_font',
                    'type' => 'typography',
                    'title' => 'SKU title font',
                    'text-transform' => TRUE,
                    'output' => 'body.woocommerce.single-product .sku_title',
                    'required' => array(
                        array(
                            'woo_single_show_sku',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_sku_title_font_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_sku_font_custom',
                    'type' => 'switch',
                    'title' => 'SKU font Custom',
                    'default' => 0,
                    'required' => array(
                        array(
                            'woo_single_show_sku',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_sku_font',
                    'type' => 'typography',
                    'title' => 'SKU font',
                    'text-transform' => TRUE,
                    'output' => '.woocommerce .sku_wrapper .sku',
                    'required' => array(
                        array(
                            'woo_single_show_sku',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_sku_font_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_cats_info',
                    'type' => 'info',
                    'desc' => 'Categories',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_show_cats',
                    'type' => 'switch',
                    'title' => 'Show categories',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_cats_order',
                    'type' => 'slider',
                    'title' => 'Categories order',
                    'default' => 16,
                    'min' => 10,
                    'step' => 1,
                    'max' => 50,
                    'required' => array(
                        'woo_single_show_cats',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_cats_inline',
                    'type' => 'switch',
                    'title' => 'Categories inline ',
                    'default' => 0,
                    'required' => array(
                        'woo_single_show_cats',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_cats_padding',
                    'type' => 'spacing',
                    'title' => 'Categories wrapper padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => array(
                        'body.woocommerce.single-product .cats',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'required' => array(
                        'woo_single_show_cats',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_cats_title_font_custom',
                    'type' => 'switch',
                    'title' => 'Categories title font Custom',
                    'default' => 0,
                    'required' => array(
                        array(
                            'woo_single_show_cats',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_cats_title_font',
                    'type' => 'typography',
                    'title' => 'Categories title font',
                    'text-transform' => TRUE,
                    'output' => 'body.woocommerce.single-product .cats_title',
                    'required' => array(
                        array(
                            'woo_single_show_cats',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_cats_title_font_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_cats_link_typography_custom',
                    'type' => 'switch',
                    'title' => 'Categories Links Typography Custom',
                    'default' => 0,
                    'required' => array(
                        array(
                            'woo_single_show_cats',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_cats_font',
                    'type' => 'typography',
                    'title' => 'Categories font',
                    'text-transform' => TRUE,
                    'output' => array(
                        'body.woocommerce.single-product .cats a',
                    ),
                    'required' => array(
                        array(
                            'woo_single_show_cats',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_cats_link_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_cats_background_color',
                    'type' => 'color',
                    'title' => 'Categories background color',
                    'validate' => 'color',
                    'output' => array(
                        'background-color' => 'body.woocommerce.single-product .cats a',
                    ),
                    'required' => array(
                        array(
                            'woo_single_show_cats',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_cats_link_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_cats_hover_color',
                    'type' => 'color',
                    'title' => 'Categories hover color',
                    'validate' => 'color',
                    'output' => array(
                        'body.woocommerce.single-product .cats a:hover',
                    ),
                    'required' => array(
                        array(
                            'woo_single_show_cats',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_cats_link_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_cats_background_hover_color',
                    'type' => 'color',
                    'title' => 'Categories background hover color',
                    'validate' => 'color',
                    'output' => array(
                        'background-color' => 'body.woocommerce.single-product .cats a:hover',
                    ),
                    'required' => array(
                        array(
                            'woo_single_show_cats',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_cats_link_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_tags_info',
                    'type' => 'info',
                    'desc' => 'Tags',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_show_tags',
                    'type' => 'switch',
                    'title' => 'Show tags',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_tags_order',
                    'type' => 'slider',
                    'title' => 'Tags order',
                    'default' => 17,
                    'min' => 10,
                    'step' => 1,
                    'max' => 50,
                    'required' => array(
                        'woo_single_show_tags',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_tags_padding',
                    'type' => 'spacing',
                    'title' => 'Tags wrapper padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => array(
                        'body.woocommerce.single-product .tags',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'required' => array(
                        'woo_single_show_tags',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_tags_title_font_custom',
                    'type' => 'switch',
                    'title' => 'Tags title font Custom',
                    'default' => 0,
                    'required' => array(
                        array(
                            'woo_single_show_tags',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_tags_title_font',
                    'type' => 'typography',
                    'title' => 'Tags title font',
                    'text-transform' => TRUE,
                    'output' => 'body.woocommerce.single-product .tags_title',
                    'required' => array(
                        array(
                            'woo_single_show_tags',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_tags_title_font_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_tags_link_typography_custom',
                    'type' => 'switch',
                    'title' => 'Tags Links Typography Custom',
                    'default' => 0,
                    'required' => array(
                        array(
                            'woo_single_show_tags',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_tags_font',
                    'type' => 'typography',
                    'title' => 'Tags font',
                    'text-transform' => TRUE,
                    'output' => array(
                        'body.woocommerce.single-product .tags a',
                    ),
                    'required' => array(
                        array(
                            'woo_single_show_tags',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_tags_link_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_tags_background_color',
                    'type' => 'color',
                    'title' => 'Tags background color',
                    'validate' => 'color',
                    'output' => array(
                        'background-color' => 'body.woocommerce.single-product .tags a',
                    ),
                    'required' => array(
                        array(
                            'woo_single_show_tags',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_tags_link_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_tags_hover_color',
                    'type' => 'color',
                    'title' => 'Tags hover color',
                    'validate' => 'color',
                    'output' => array(
                        'body.woocommerce.single-product .tags a:hover',
                    ),
                    'required' => array(
                        array(
                            'woo_single_show_tags',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_tags_link_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_tags_background_hover_color',
                    'type' => 'color',
                    'title' => 'Tags background hover color',
                    'validate' => 'color',
                    'output' => array(
                        'background-color' => 'body.woocommerce.single-product .tags a:hover',
                    ),
                    'required' => array(
                        array(
                            'woo_single_show_tags',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_tags_link_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_brands_info',
                    'type' => 'info',
                    'desc' => 'Brands',
                    'required' => array(
                        'woocommerce_brands',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_brands_order',
                    'type' => 'slider',
                    'title' => 'Brands order',
                    'default' => 18,
                    'min' => 10,
                    'step' => 1,
                    'max' => 50,
                    'required' => array(
                        'woocommerce_brands',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_brands_padding',
                    'type' => 'spacing',
                    'title' => 'Brands wrapper padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => array(
                        'body.woocommerce.single-product .brands_wrapper',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'required' => array(
                        'woocommerce_brands',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_brands_title_font',
                    'type' => 'typography',
                    'title' => 'Brands title font',
                    'text-transform' => TRUE,
                    'output' => array(
                        'body.woocommerce.single-product .brands_wrapper .brand_title',
                    ),
                    'required' => array(
                        'woocommerce_brands',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_desc_info',
                    'type' => 'info',
                    'desc' => 'Description',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_description_in_summary',
                    'type' => 'switch',
                    'title' => 'Add product description in summary',
                    'description' => 'Remove from tab and add it right next to the image',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_description_add_to_bottom',
                    'type' => 'switch',
                    'title' => 'Add to bottom holder',
                    'default' => 0,
                    'required' => array(
                        array(
                            'woo_single_summary_enable_bottom_holder',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_product_description_in_summary',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_description_order',
                    'type' => 'slider',
                    'title' => 'Product description order',
                    'default' => 15,
                    'min' => 10,
                    'step' => 1,
                    'max' => 50,
                    'required' => array(
                        'woo_single_product_description_in_summary',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_description_title_show',
                    'type' => 'switch',
                    'title' => 'Show product description title',
                    'default' => 1,
                    'required' => array(
                        'woo_single_product_description_in_summary',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_description_title_font',
                    'type' => 'typography',
                    'title' => 'Product description title font',
                    'text-transform' => TRUE,
                    'output' => array(
                        '.single-product .desc_title',
                    ),
                    'required' => array(
                        'woo_single_product_description_title_show',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_description_padding',
                    'type' => 'spacing',
                    'title' => 'Product description padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => array(
                        '.single-product .desc_content p',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'required' => array(
                        'woo_single_product_description_in_summary',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_additional_info',
                    'type' => 'info',
                    'desc' => 'Additional info',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_additional_info_in_summary',
                    'type' => 'switch',
                    'title' => 'Add product additional information in summary',
                    'description' => 'Remove from tab and add it right next to the image',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_additional_info_add_to_bottom',
                    'type' => 'switch',
                    'title' => 'Add to bottom holder',
                    'default' => 0,
                    'required' => array(
                        array(
                            'woo_single_summary_enable_bottom_holder',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_single_product_additional_info_in_summary',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_additional_info_order',
                    'type' => 'slider',
                    'title' => 'Product additional information order',
                    'default' => 15,
                    'min' => 10,
                    'step' => 1,
                    'max' => 50,
                    'required' => array(
                        'woo_single_product_additional_info_in_summary',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_additional_info_title_show',
                    'type' => 'switch',
                    'title' => 'Show additional info title',
                    'default' => 1,
                    'required' => array(
                        'woo_single_product_additional_info_in_summary',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_additional_info_title_font',
                    'type' => 'typography',
                    'title' => 'Additional info title font',
                    'text-transform' => TRUE,
                    'output' => array(
                        '.single-product .add_info_title',
                    ),
                    'required' => array(
                        'woo_single_additional_info_title_show',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_additional_info_title_width',
                    'type' => 'slider',
                    'title' => 'Additional info title width',
                    'default' => 200,
                    'min' => 10,
                    'step' => 1,
                    'max' => 400,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_additional_info_table_cell_padding',
                    'type' => 'spacing',
                    'title' => 'Additional info table cell padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => array(
                        'body.woocommerce table.shop_attributes tr td, body.woocommerce table.shop_attributes tr th',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_additional_info_table_titles',
                    'type' => 'typography',
                    'title' => 'Additional info table header cell font',
                    'text-transform' => TRUE,
                    'output' => array(
                        '.woocommerce table.shop_attributes th',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_additional_info_table_th_border',
                    'type' => 'border',
                    'title' => 'Additional info table header cell border',
                    'all' => FALSE,
                    'output' => array(
                        '.woocommerce table.shop_attributes tr th',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_additional_info_table_td_font_custom',
                    'type' => 'switch',
                    'title' => 'Additional info table standard cell font Custom',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_additional_info_table_td_font',
                    'type' => 'typography',
                    'title' => 'Additional info table standard cell font',
                    'text-transform' => TRUE,
                    'output' => '.woocommerce table.shop_attributes tr td',
                    'required' => array(
                        array(
                            'woo_single_additional_info_table_td_font_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_additional_info_table_td_border',
                    'type' => 'border',
                    'title' => 'Additional info table standard cell border',
                    'all' => FALSE,
                    'output' => array(
                        '.woocommerce table.shop_attributes tr td',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_price_info',
                    'type' => 'info',
                    'desc' => 'Price',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_price_order',
                    'type' => 'slider',
                    'title' => 'Price order',
                    'default' => 20,
                    'min' => 10,
                    'step' => 1,
                    'max' => 50,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_price_font_custom',
                    'type' => 'switch',
                    'title' => 'Price font Custom',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_price_font',
                    'type' => 'typography',
                    'title' => 'Price font',
                    'text-transform' => TRUE,
                    'output' => 'body.woocommerce.single-product .summary.entry-summary .price, body.woocommerce.single-product .summary.entry-summary .price .amount',
                    'required' => array(
                        array(
                            'woo_single_price_font_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_price_padding',
                    'type' => 'spacing',
                    'title' => 'Price padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        'body.woocommerce.single-product .summary.entry-summary .price',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_addtocart_info',
                    'type' => 'info',
                    'desc' => 'Add To Cart button',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_addtocart_add_to_bottom',
                    'type' => 'switch',
                    'title' => 'Add to bottom holder',
                    'default' => 0,
                    'required' => array(
                        'woo_single_summary_enable_bottom_holder',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_addtocart_order',
                    'type' => 'slider',
                    'title' => 'Quantity & Add To Cart order',
                    'default' => 20,
                    'min' => 10,
                    'step' => 1,
                    'max' => 50,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_addtocart_holder_layout',
                    'type' => 'select',
                    'title' => 'Quantity & Add To Cart Layout',
                    'default' => 'horizontal',
                    'options' => array(
                        'horizontal' => 'Horizontal',
                        'vertical' => 'Vertical',
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_quantity_backcolor',
                    'type' => 'color',
                    'title' => 'Quantity background color',
                    'output' => array(
                        'background-color' => '.single-product div.product form.cart div.quantity',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_quantity_padding',
                    'type' => 'spacing',
                    'title' => 'Quantity padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => array(
                        '.single-product div.product form.cart div.quantity',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_addtocart_typography_custom',
                    'type' => 'switch',
                    'title' => 'Add To Cart Customize Typography',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_addtocart_font',
                    'type' => 'typography',
                    'title' => 'Add To Cart font',
                    'text-transform' => TRUE,
                    'output' => '.single-product button.button.single_add_to_cart_button',
                    'required' => array(
                        array(
                            'woo_single_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_addtocart_backcolor',
                    'type' => 'color',
                    'title' => 'Add To Cart background color',
                    'output' => array(
                        'background-color' => '.single-product button.button.single_add_to_cart_button',
                    ),
                    'required' => array(
                        array(
                            'woo_single_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_addtocart_hover_color',
                    'type' => 'color',
                    'title' => 'Add to cart button hover color',
                    'validate' => 'color',
                    'output' => array(
                        '.single-product button.button.single_add_to_cart_button:hover',
                    ),
                    'required' => array(
                        array(
                            'woo_single_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_addtocart_hover_backcolor',
                    'type' => 'color',
                    'title' => 'Add To Cart hover background color',
                    'output' => array(
                        'background-color' => '.single-product button.button.single_add_to_cart_button:hover',
                    ),
                    'required' => array(
                        array(
                            'woo_single_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_addtocart_padding',
                    'type' => 'spacing',
                    'title' => 'Add To Cart padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => '.single-product button.button.single_add_to_cart_button',
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'required' => array(
                        array(
                            'woo_single_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_addtocart_border',
                    'type' => 'border',
                    'title' => 'Add to cart border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#ffffff',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => '.single-product button.button.single_add_to_cart_button',
                    'required' => array(
                        array(
                            'woo_single_addtocart_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_stock_info',
                    'type' => 'info',
                    'desc' => 'Stock',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_stock_font_custom',
                    'type' => 'switch',
                    'title' => 'Price font Custom',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_stock_font',
                    'type' => 'typography',
                    'title' => 'Stock font',
                    'text-transform' => TRUE,
                    'output' => '.woocommerce div.product p.stock',
                    'required' => array(
                        array(
                            'woo_single_stock_font_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_stock_padding',
                    'type' => 'spacing',
                    'title' => 'Stock padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => array(
                        '.woocommerce div.product p.stock',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_socialshare_info',
                    'type' => 'info',
                    'desc' => 'Social share buttons',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_socialshare',
                    'type' => 'switch',
                    'title' => 'Show social share buttons',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_socialshare_order',
                    'type' => 'slider',
                    'title' => 'Social share buttons order',
                    'default' => 20,
                    'min' => 10,
                    'step' => 1,
                    'max' => 50,
                    'required' => array(
                        'woo_single_socialshare',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'social_share_btns',
                    'type' => 'select',
                    'multi' => TRUE,
                    'sortable' => TRUE,
                    'title' => 'Social share buttons activation and order',
                    'options' => array(
                        1 => 'Facebook',
                        2 => 'Twitter',
                        3 => 'Google+',
                        4 => 'Pinterest',
                    ),
                    'required' => array(
                        'woo_single_socialshare',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Share buttons size',
                    'id' => 'social_share_btn_size',
                    'default' => 20,
                    'min' => 10,
                    'step' => 1,
                    'max' => 50,
                    'advanced' => TRUE,
                    'type' => 'slider',
                    'required' => array(
                        'woo_single_socialshare',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'social_share_btn_margin',
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'title' => 'Share buttons margin',
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '5px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.social-share-btn .fa',
                    ),
                    'required' => array(
                        'woo_single_socialshare',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Share buttons color',
                    'id' => 'social_share_btn_color',
                    'default' => '#ffffff',
                    'transparent' => FALSE,
                    'type' => 'color',
                    'required' => array(
                        'woo_single_socialshare',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Share buttons background color',
                    'id' => 'social_share_btn_back_color',
                    'default' => '#000000',
                    'transparent' => TRUE,
                    'type' => 'color',
                    'required' => array(
                        'woo_single_socialshare',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Share buttons color on hover',
                    'id' => 'social_share_btn_back_color_hover',
                    'default' => '#ffffff',
                    'transparent' => FALSE,
                    'type' => 'color',
                    'output' => array(
                        '.social-share-btn:hover .fa',
                    ),
                    'required' => array(
                        'woo_single_socialshare',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Share buttons background color on hover',
                    'id' => 'social_share_btn_color_hover',
                    'default' => '#ffffff',
                    'transparent' => TRUE,
                    'type' => 'color',
                    'output' => array(
                        'background-color' => '.social-share-btn:hover .fa',
                    ),
                    'required' => array(
                        'woo_single_socialshare',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Share buttons shape',
                    'id' => 'social_share_btn_back_shape',
                    'default' => 'square',
                    'type' => 'select',
                    'options' => array(
                        'square' => 'Square',
                        'circle' => 'Circle',
                    ),
                    'required' => array(
                        'woo_single_socialshare',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Share buttons background size',
                    'id' => 'social_share_btn_back_size',
                    'default' => 20,
                    'min' => 15,
                    'step' => 1,
                    'max' => 50,
                    'advanced' => TRUE,
                    'type' => 'slider',
                    'required' => array(
                        'woo_single_socialshare',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Tooltips',
                    'id' => 'social_share_btn_tooltip',
                    'default' => 0,
                    'type' => 'switch',
                    'required' => array(
                        'woo_single_socialshare',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Tooltip placement',
                    'id' => 'social_share_btn_tooltip_placement',
                    'default' => 'top',
                    'type' => 'select',
                    'options' => array(
                        'top' => 'Top',
                        'right' => 'Right',
                        'bottom' => 'Bottom',
                        'left' => 'Left',
                    ),
                    'required' => array(
                        'social_share_btn_tooltip',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Tooltip text color',
                    'id' => 'social_share_btn_tooltip_color',
                    'default' => '#ffffff',
                    'transparent' => FALSE,
                    'type' => 'color',
                    'output' => array(
                        '.social_share_btn_tooltip_class',
                    ),
                    'required' => array(
                        'social_share_btn_tooltip',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Tooltip background color',
                    'id' => 'social_share_btn_tooltip_back_color',
                    'default' => '#000000',
                    'transparent' => TRUE,
                    'type' => 'color',
                    'output' => array(
                        'background-color' => '.social_share_btn_tooltip_class',
                    ),
                    'required' => array(
                        'social_share_btn_tooltip',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Tooltip arrow',
                    'id' => 'social_share_btn_tooltip_arrow',
                    'default' => 1,
                    'type' => 'switch',
                    'required' => array(
                        'social_share_btn_tooltip',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Facebook tooltip text',
                    'id' => 'facebook_share_tooltip_text',
                    'default' => 'Share on facebook',
                    'type' => 'text',
                    'required' => array(
                        array(
                            'social_share_btns',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'social_share_btn_tooltip',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Twitter tooltip text',
                    'id' => 'twitter_share_tooltip_text',
                    'default' => 'Share on twitter',
                    'type' => 'text',
                    'required' => array(
                        array(
                            'social_share_btns',
                            '=',
                            array(
                                '2',
                            ),
                        ),
                        array(
                            'social_share_btn_tooltip',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Google+ tooltip text',
                    'id' => 'googleplus_share_tooltip_text',
                    'default' => 'Share on google+',
                    'type' => 'text',
                    'required' => array(
                        array(
                            'social_share_btns',
                            '=',
                            array(
                                '3',
                            ),
                        ),
                        array(
                            'social_share_btn_tooltip',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Pinterest tooltip text',
                    'id' => 'pinterest_share_tooltip_text',
                    'default' => 'Share on pinterest',
                    'type' => 'text',
                    'required' => array(
                        array(
                            'social_share_btns',
                            '=',
                            array(
                                '4',
                            ),
                        ),
                        array(
                            'social_share_btn_tooltip',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_related_products_info',
                    'type' => 'info',
                    'desc' => 'Related products',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_related_products',
                    'type' => 'switch',
                    'title' => 'Related products',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_related_products_holder_padding',
                    'type' => 'spacing',
                    'title' => 'Related products holder padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        'body.woocommerce section.related.products',
                    ),
                    'required' => array(
                        'woo_related_products',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_related_products_number',
                    'type' => 'slider',
                    'title' => 'Number of related products',
                    'default' => 4,
                    'min' => 1,
                    'step' => 1,
                    'max' => 20,
                    'required' => array(
                        'woo_related_products',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_related_products_title_typography_custom',
                    'type' => 'switch',
                    'title' => 'Related products title Customize Typography',
                    'default' => 0,
                    'required' => array(
                        array(
                            'woo_related_products',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_related_products_title_font',
                    'type' => 'typography',
                    'title' => 'Related products title font',
                    'text-transform' => TRUE,
                    'output' => 'body.woocommerce section.related.products > h2',
                    'required' => array(
                        array(
                            'woo_related_products',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_related_products_title_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_related_products_title_padding',
                    'type' => 'spacing',
                    'title' => 'Related products title padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '10px',
                        'padding-right' => '0px',
                        'padding-bottom' => '10px',
                        'padding-left' => '0px',
                    ),
                    'output' => 'body.woocommerce section.related.products > h2',
                    'required' => array(
                        array(
                            'woo_related_products',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_related_products_title_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_related_products_title_margin',
                    'type' => 'spacing',
                    'title' => 'Related products title margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '#main section.related.products > h2',
                    ),
                    'required' => array(
                        'woo_related_products',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_related_products_title_border',
                    'type' => 'border',
                    'title' => 'Related products title border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#000000',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '#main section.related.products > h2',
                    ),
                    'required' => array(
                        'woo_related_products',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_tabs',
                    'type' => 'info',
                    'desc' => 'Product tabs',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_tabs_padding',
                    'type' => 'spacing',
                    'title' => 'Tab holder padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '20px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-tabs',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_tabst_backcolor',
                    'type' => 'color',
                    'title' => 'Tab holder background color',
                    'default' => '#E6E6E6',
                    'output' => array(
                        'background-color' => '.wc-tab, .woocommerce div.product .woocommerce-tabs ul.tabs li.active, .wc-tab, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_tab_title',
                    'type' => 'typography',
                    'title' => 'Tab title font',
                    'output' => array(
                        'body.woocommerce .woocommerce-tabs a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_tab_border_show',
                    'type' => 'switch',
                    'title' => 'Show border around tabs',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_tab_border',
                    'type' => 'border',
                    'title' => 'Tab border',
                    'all' => TRUE,
                    'required' => array(
                        'woo_single_product_tab_border_show',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_tab_title_padding',
                    'type' => 'spacing',
                    'title' => 'Tab title padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '20px',
                        'padding-right' => '20px',
                        'padding-bottom' => '20px',
                        'padding-left' => '20px',
                    ),
                    'output' => array(
                        '.woocommerce div.product .woocommerce-tabs ul.tabs li',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_tab_panel_padding',
                    'type' => 'spacing',
                    'title' => 'Tab panel content padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '20px',
                        'padding-right' => '20px',
                        'padding-bottom' => '20px',
                        'padding-left' => '20px',
                    ),
                    'output' => array(
                        '.woocommerce div.product .woocommerce-tabs .panel',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_extra_tabs',
                    'type' => 'switch',
                    'title' => 'Add extra tabs',
                    'default' => FALSE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_extra_tab1',
                    'type' => 'text',
                    'title' => 'Tab 1 title',
                    'required' => array(
                        'woo_single_product_extra_tabs',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_extra_tab2',
                    'type' => 'text',
                    'title' => 'Tab 2 title',
                    'required' => array(
                        'woo_single_product_extra_tabs',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_extra_tab3',
                    'type' => 'text',
                    'title' => 'Tab 3 title',
                    'required' => array(
                        'woo_single_product_extra_tabs',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_extra_tab4',
                    'type' => 'text',
                    'title' => 'Tab 4 title',
                    'required' => array(
                        'woo_single_product_extra_tabs',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_reviews_info',
                    'type' => 'info',
                    'desc' => 'Reviews',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_reviews_enabled',
                    'type' => 'switch',
                    'title' => 'Enable product reviews',
                    'default' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_review_icon_shape',
                    'type' => 'select',
                    'title' => 'Review icon shape',
                    'default' => 'circle',
                    'options' => array(
                        'circle' => 'Circle',
                        'star' => 'Star',
                        'heart' => 'Heart',
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'required' => array(
                        'woo_reviews_enabled',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_review_icon_color',
                    'type' => 'color',
                    'title' => 'Review icon color',
                    'default' => 'black',
                    'output' => array(
                        '.woocommerce .star-rating span::before, .woocommerce p.stars a::before, .woocommerce .star-rating span i',
                    ),
                    'required' => array(
                        'woo_reviews_enabled',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_review_title_typography_custom',
                    'type' => 'switch',
                    'title' => 'Review title Customize Typography',
                    'default' => 0,
                    'required' => array(
                        array(
                            'woo_reviews_enabled',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_review_title_font',
                    'type' => 'typography',
                    'title' => 'Review title font',
                    'text-transform' => TRUE,
                    'output' => '.woocommerce #reviews #comments h2',
                    'required' => array(
                        array(
                            'woo_reviews_enabled',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_review_title_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_review_title_padding',
                    'type' => 'spacing',
                    'title' => 'Review title padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'output' => '.woocommerce #reviews #comments h2',
                    'required' => array(
                        array(
                            'woo_reviews_enabled',
                            '=',
                            array(
                                '1',
                            ),
                        ),
                        array(
                            'woo_review_title_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_review_reply_title_font',
                    'type' => 'typography',
                    'title' => 'Review reply title font',
                    'text-transform' => TRUE,
                    'output' => array(
                        '.woocommerce #reviews h3',
                    ),
                    'required' => array(
                        'woo_reviews_enabled',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_review_reply_title_padding',
                    'type' => 'spacing',
                    'title' => 'Review reply title padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce #reviews h3',
                    ),
                    'required' => array(
                        'woo_reviews_enabled',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_navigation',
                    'type' => 'info',
                    'desc' => 'Product navigation',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_navigation_enable',
                    'type' => 'switch',
                    'title' => 'Add navigation arrows',
                    'description' => 'Add previous and next links to products',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_navigation_arrow_size',
                    'type' => 'typography',
                    'title' => 'Arrow size & color',
                    'font-family' => FALSE,
                    'font-weight' => FALSE,
                    'text-align' => FALSE,
                    'line-height' => FALSE,
                    'font-style' => FALSE,
                    'default' => array(
                        'font-size' => 14,
                        'color' => '#ffffff',
                    ),
                    'output' => array(
                        'body.woocommerce.single-product .next_product_btn, body.woocommerce.single-product .prev_product_btn',
                    ),
                    'required' => array(
                        'woo_single_product_navigation_enable',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_navigation_arrow_icon',
                    'type' => 'select',
                    'title' => 'Icon',
                    'default' => 'chevron',
                    'options' => array(
                        'chevron' => 'Chevron',
                        'arrow' => 'Arrow',
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'required' => array(
                        'woo_single_product_navigation_enable',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Arrow background color',
                    'id' => 'woo_single_product_navigation_arrow_back_color',
                    'default' => '#000000',
                    'transparent' => TRUE,
                    'type' => 'color',
                    'output' => array(
                        'background-color' => '.next_product_btn, .prev_product_btn',
                    ),
                    'required' => array(
                        'woo_single_product_navigation_enable',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Arrow hover color',
                    'id' => 'woo_single_product_navigation_arrow_hover_color',
                    'default' => '#ffffff',
                    'transparent' => TRUE,
                    'type' => 'color',
                    'output' => array(
                        'body.woocommerce.single-product .next_product_btn:hover, body.woocommerce.single-product .prev_product_btn:hover',
                    ),
                    'required' => array(
                        'woo_single_product_navigation_enable',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Arrow hover background color',
                    'id' => 'woo_single_product_navigation_arrow_hover_back_color',
                    'default' => '#000000',
                    'transparent' => TRUE,
                    'type' => 'color',
                    'output' => array(
                        'background-color' => '.next_product_btn:hover, .prev_product_btn:hover',
                    ),
                    'required' => array(
                        'woo_single_product_navigation_enable',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_navigation_back_shape',
                    'type' => 'select',
                    'title' => 'Background shape',
                    'default' => 'square',
                    'options' => array(
                        'square' => 'Square',
                        'circle' => 'Circle',
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'required' => array(
                        'woo_single_product_navigation_enable',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_navigation_border',
                    'type' => 'border',
                    'title' => 'Border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#000000',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.next_product_btn, .prev_product_btn',
                    ),
                    'required' => array(
                        'woo_single_product_navigation_enable',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_navigation_arrow_padding',
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'title' => 'Arrow padding',
                    'default' => array(
                        'padding-top' => '10px',
                        'padding-right' => '10px',
                        'padding-bottom' => '10px',
                        'padding-left' => '10px',
                    ),
                    'output' => array(
                        '.next_product_btn, .prev_product_btn',
                    ),
                    'required' => array(
                        'woo_single_product_navigation_enable',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_single_product_navigation_arrow_margin',
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'title' => 'Arrow margin',
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '10px',
                        'margin-bottom' => '0px',
                        'margin-left' => '10px',
                    ),
                    'output' => array(
                        '.next_product_btn, .prev_product_btn',
                    ),
                    'required' => array(
                        'woo_single_product_navigation_enable',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Tooltips',
                    'id' => 'woo_single_product_navigation_arrow_tooltip',
                    'default' => 1,
                    'type' => 'switch',
                    'required' => array(
                        'woo_single_product_navigation_enable',
                        '=',
                        array(
                            '1',
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Tooltip text color',
                    'id' => 'woo_single_product_navigation_arrow_tooltip_color',
                    'default' => '#ffffff',
                    'transparent' => FALSE,
                    'type' => 'color',
                    'output' => array(
                        '.product_navigation_tooltip ',
                    ),
                    'required' => array(
                        array(
                            'woo_single_product_navigation_arrow_tooltip',
                            '=',
                            array(
                                1,
                            ),
                        ),
                        array(
                            'woo_single_product_navigation_enable',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Tooltip background color',
                    'id' => 'woo_single_product_navigation_arrow_tooltip_back_color',
                    'default' => '#000000',
                    'transparent' => TRUE,
                    'type' => 'color',
                    'output' => array(
                        'background-color' => '.product_navigation_tooltip',
                    ),
                    'required' => array(
                        array(
                            'woo_single_product_navigation_arrow_tooltip',
                            '=',
                            array(
                                1,
                            ),
                        ),
                        array(
                            'woo_single_product_navigation_enable',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'title' => 'Tooltip arrow',
                    'id' => 'woo_single_product_navigation_arrow',
                    'default' => 1,
                    'type' => 'switch',
                    'required' => array(
                        array(
                            'woo_single_product_navigation_arrow_tooltip',
                            '=',
                            array(
                                1,
                            ),
                        ),
                        array(
                            'woo_single_product_navigation_enable',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'single-product',
        ),
        array(
            'icon' => 'el-icon-align-justify',
            'title' => 'Widgets',
            'subsection' => TRUE,
            'fields' => array(
                array(
                    'id' => 'info_woo_sidebar_productcats',
                    'type' => 'info',
                    'desc' => 'Product categories widget',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_productcats_level1font',
                    'type' => 'typography',
                    'title' => 'Level 1 text font',
                    'text-transform' => TRUE,
                    'output' => array(
                        'body.woocommerce ul.product-categories li > a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_productcats_level1activecolor',
                    'type' => 'color',
                    'title' => 'Level 1 Active Text Color',
                    'validate' => 'color',
                    'output' => array(
                        'body.woocommerce ul.product-categories li > a:hover, body.woocommerce ul.product-categories > li > ul.children > li > a:hover, body.woocommerce ul.product-categories > li > ul.children > li > ul.children > li > a:hover, body.woocommerce ul.product-categories li.current-cat > a, body.woocommerce ul.product-categories li.current-cat-parent > a, body.woocommerce ul.product-categories > li > ul.children > li.current-cat > a, body.woocommerce ul.product-categories > li > ul.children > li > ul.children > li.current-cat > a, body.woocommerce ul.product-categories > li > ul.children > li.current-cat-parent > a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_productcats_level2font',
                    'type' => 'typography',
                    'title' => 'Level 2 text font',
                    'text-transform' => TRUE,
                    'output' => array(
                        'body.woocommerce ul.product-categories > li > ul.children > li > a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_productcats_level2margin',
                    'type' => 'spacing',
                    'title' => 'Level 2 margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '10px',
                    ),
                    'output' => array(
                        'body.woocommerce ul.product-categories > li > ul.children > li > a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_productcats_level3color',
                    'type' => 'color',
                    'title' => 'Level 3 Text Color',
                    'validate' => 'color',
                    'output' => array(
                        'body.woocommerce ul.product-categories > li > ul.children > li > ul.children > li > a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_productcats_level3margin',
                    'type' => 'spacing',
                    'title' => 'Level 3 margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '20px',
                    ),
                    'output' => array(
                        'body.woocommerce ul.product-categories > li > ul.children > li > ul.children > li > a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'info_woo_sidebar_pricefilter',
                    'type' => 'info',
                    'desc' => 'Price filter',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_pricefilter_color',
                    'type' => 'color',
                    'title' => 'Slider color',
                    'default' => '#000000',
                    'transparent' => FALSE,
                    'output' => array(
                        'background-color' => '.woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_pricefilter_backcolor',
                    'type' => 'color',
                    'title' => 'Slider background color',
                    'default' => '#E6E6E6',
                    'transparent' => FALSE,
                    'output' => array(
                        'background-color' => '.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_pricefilter_margin',
                    'type' => 'spacing',
                    'title' => 'Slider margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '20px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_pricefilter_label_font',
                    'type' => 'typography',
                    'title' => 'Price label font',
                    'text-transform' => TRUE,
                    'output' => array(
                        '.woocommerce .widget_price_filter .price_slider_amount',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_pricefilter_button_padding',
                    'type' => 'spacing',
                    'title' => 'Filter button padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '5px',
                        'padding-right' => '5px',
                        'padding-bottom' => '5px',
                        'padding-left' => '5px',
                    ),
                    'output' => array(
                        '.woocommerce .widget_price_filter button.button',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'info_woo_sidebar_layerednav',
                    'type' => 'info',
                    'desc' => 'Layered nav filter',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_layerednav_margin',
                    'type' => 'spacing',
                    'title' => 'Widget margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce .widget_layered_nav',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_layerednav_title_font',
                    'type' => 'typography',
                    'title' => 'Titles font',
                    'output' => array(
                        '.woocommerce .widget_layered_nav h6',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_layerednav_link_font',
                    'type' => 'typography',
                    'title' => 'Links font',
                    'output' => array(
                        'body.woocommerce.archive .widget_layered_nav ul li a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_layerednav_removefilter_color',
                    'type' => 'color',
                    'title' => 'Remove filter icon color',
                    'validate' => 'color',
                    'output' => array(
                        '.woocommerce .widget_layered_nav ul li.chosen a::before, .woocommerce .widget_layered_nav_filters ul li a::before ',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_layerednav_removebrackets',
                    'type' => 'switch',
                    'title' => 'Remove brackets from counters',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_sidebar_layerednav_select_min_width',
                    'title' => 'Dropdown select min width',
                    'subtitle' => 'Set it to 0 to use auto.',
                    'default' => 0,
                    'min' => 0,
                    'step' => 10,
                    'max' => 400,
                    'type' => 'slider',
                    'class' => '',
                ),
            ),
            'id' => 'widgets',
        ),
        array(
            'icon' => 'el-icon-shopping-cart',
            'title' => 'Cart',
            'subsection' => TRUE,
            'fields' => array(
                array(
                    'id' => 'woo_cart_info',
                    'type' => 'info',
                    'desc' => 'Product table',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_cart_products_header_font',
                    'type' => 'typography',
                    'title' => 'Table header font',
                    'text-transform' => TRUE,
                    'output' => array(
                        '.woocommerce .shop_table.cart thead tr th',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_cart_remove_color',
                    'type' => 'color',
                    'title' => 'Remove button Color',
                    'default' => '#ffffff',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_cart_remove_bg_color',
                    'type' => 'color',
                    'title' => 'Remove button Background Color',
                    'default' => '#000000',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce #content table.cart a.remove, .woocommerce table.cart a.remove, .woocommerce-page #content table.cart a.remove, .woocommerce-page table.cart a.remove',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_cart_remove_bg_hover_color',
                    'type' => 'color',
                    'title' => 'Remove button hover Background Color',
                    'default' => '#333333',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce #content table.cart a.remove:hover, .woocommerce table.cart a.remove:hover, .woocommerce-page #content table.cart a.remove:hover, .woocommerce-page table.cart a.remove:hover',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_cart_removeimages',
                    'type' => 'switch',
                    'title' => 'Remove images',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_cart_remove_updatecart_ifdisabled',
                    'type' => 'switch',
                    'title' => 'Hide update cart button if disabled',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_cart_totals_info',
                    'type' => 'info',
                    'desc' => 'Cart totals table',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_cart_totals_title_typography_custom',
                    'type' => 'switch',
                    'title' => 'Title Customize Typography',
                    'default' => 0,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_cart_totals_title_font',
                    'type' => 'typography',
                    'title' => 'Title font',
                    'output' => '.woocommerce .cart_totals h2',
                    'required' => array(
                        array(
                            'woo_cart_totals_title_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_cart_totals_title_padding',
                    'type' => 'spacing',
                    'title' => 'Add To Cart padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'output' => '.woocommerce .cart_totals h2',
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'required' => array(
                        array(
                            'woo_cart_totals_title_typography_custom',
                            '=',
                            array(
                                1,
                            ),
                        ),
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'cart',
        ),
        array(
            'icon' => 'el-icon-credit-card',
            'title' => 'Checkout',
            'subsection' => TRUE,
            'fields' => array(
                array(
                    'id' => 'woo_checkout_layout',
                    'type' => 'select',
                    'title' => 'Page layout',
                    'default' => 'default',
                    'options' => array(
                        'default' => 'Default',
                        'columns_3' => '3 columns',
                    ),
                    'select2' => array(
                        'allowClear' => FALSE,
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_hide_2nd_column',
                    'type' => 'switch',
                    'title' => 'Hide 2nd column',
                    'default' => 0,
                    'description' => 'Enable it only if you have no fields in 2nd column (eg. shipping, additional info, notes)',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_ordertable_info',
                    'type' => 'info',
                    'desc' => 'Order table',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_ordertableheading_margin',
                    'type' => 'spacing',
                    'title' => 'Order table heading margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-checkout h3#order_review_heading',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_ordertable_backcolor',
                    'type' => 'color',
                    'title' => 'Order table Background Color',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_ordertable_margin',
                    'type' => 'spacing',
                    'title' => 'Order table margin',
                    'mode' => 'margin',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'margin-top' => '0px',
                        'margin-right' => '0px',
                        'margin-bottom' => '0px',
                        'margin-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_ordertable_border',
                    'type' => 'border',
                    'title' => 'Order table border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#000000',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '2px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_ordertable_padding',
                    'type' => 'spacing',
                    'title' => 'Order table padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-checkout .shop_table.woocommerce-checkout-review-order-table',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_ordertable_total_font',
                    'type' => 'typography',
                    'title' => 'Total font',
                    'output' => array(
                        '#order_review .order-total th, #order_review .order-total td, #order_review .order-total td strong',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_info',
                    'type' => 'info',
                    'desc' => 'Payment holder',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_backcolor',
                    'type' => 'color',
                    'title' => 'Payment holder Background Color',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '#add_payment_method #payment, .woocommerce-checkout #payment',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_title_font',
                    'type' => 'typography',
                    'title' => 'Title fonts',
                    'output' => array(
                        '.woocommerce-checkout #payment label',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_desc_font',
                    'type' => 'typography',
                    'title' => 'Description fonts',
                    'output' => array(
                        '.woocommerce-checkout #payment .payment_box p',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_title_padding',
                    'type' => 'spacing',
                    'title' => 'Title padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '20px',
                        'padding-right' => '20px',
                        'padding-bottom' => '20px',
                        'padding-left' => '20px',
                    ),
                    'output' => array(
                        '#add_payment_method #payment ul.payment_methods li, .woocommerce-checkout #payment ul.payment_methods li',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_checkout_payment_title_border',
                    'type' => 'border',
                    'title' => 'Title border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#000000',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '2px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '#add_payment_method #payment ul.payment_methods li, .woocommerce-checkout #payment ul.payment_methods li',
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'checkout',
        ),
        array(
            'icon' => 'el-icon-torso',
            'title' => 'Login / Register',
            'subsection' => TRUE,
            'fields' => array(
                array(
                    'id' => 'woocommerce_login_form_border',
                    'type' => 'border',
                    'title' => 'Forms border',
                    'all' => FALSE,
                    'default' => array(
                        'border-color' => '#000000',
                        'border-style' => 'solid',
                        'border-top' => '0px',
                        'border-right' => '0px',
                        'border-bottom' => '0px',
                        'border-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce form.login, .woocommerce form.register',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woocommerce_login_form_padding',
                    'type' => 'spacing',
                    'title' => 'Forms padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0',
                        'padding-right' => '0',
                        'padding-bottom' => '0',
                        'padding-left' => '0',
                    ),
                    'output' => array(
                        '.woocommerce form.login, .woocommerce form.register',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_profile_menu_info',
                    'type' => 'info',
                    'desc' => 'Profile sidebar menu',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'woo_profile_menu_backcolor',
                    'type' => 'color',
                    'title' => 'Profile Menu Background Color',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => '.woocommerce-account .woocommerce-MyAccount-navigation > ul',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_profile_menu_padding',
                    'type' => 'spacing',
                    'title' => 'Profile Menu padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '0px',
                        'padding-right' => '0px',
                        'padding-bottom' => '0px',
                        'padding-left' => '0px',
                    ),
                    'output' => array(
                        '.woocommerce-account .woocommerce-MyAccount-navigation > ul',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_profile_menu_btn_font',
                    'type' => 'typography',
                    'title' => 'Profile menu button font',
                    'text-transform' => TRUE,
                    'output' => array(
                        '.woocommerce-account .woocommerce-MyAccount-navigation > ul > li > a',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_profile_menu_btn_hover_color',
                    'type' => 'color',
                    'title' => 'Profile menu button hover color',
                    'validate' => 'color',
                    'output' => array(
                        '.woocommerce-account .woocommerce-MyAccount-navigation > ul > li:hover > a, .woocommerce-account .woocommerce-MyAccount-navigation > ul > li.is-active > a',
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'login-register',
        ),
        array(
            'icon' => 'el-icon-ok',
            'title' => 'Thank you page',
            'subsection' => TRUE,
            'fields' => array(
                array(
                    'id' => 'woo_thankyou_orderdetails_backcolor',
                    'type' => 'color',
                    'title' => 'Order details Background Color',
                    'transparent' => TRUE,
                    'output' => array(
                        'background-color' => 'body.woocommerce-order-received ul.woocommerce-thankyou-order-details.order_details, body.woocommerce-order-received ul.order_details.bacs_details',
                    ),
                    'class' => '',
                ),
                array(
                    'id' => 'woo_thankyou_orderdetails_padding',
                    'type' => 'spacing',
                    'title' => 'Order details padding',
                    'mode' => 'padding',
                    'units' => array(
                        'px',
                    ),
                    'default' => array(
                        'padding-top' => '20px',
                        'padding-right' => '20px',
                        'padding-bottom' => '20px',
                        'padding-left' => '20px',
                    ),
                    'output' => array(
                        'background-color' => 'body.woocommerce-order-received ul.woocommerce-thankyou-order-details.order_details, body.woocommerce-order-received ul.order_details.bacs_details',
                    ),
                    'class' => '',
                ),
            ),
            'id' => 'thank-you-page',
        ),
        array(
            'icon' => 'el-icon-cog',
            'title' => 'WooCommerce template files',
            'subsection' => TRUE,
            'fields' => array(
                array(
                    'id' => 'woo_template_section__info',
                    'type' => 'info',
                    'desc' => 'Folder: ',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_archive_product_template',
                    'type' => 'button_set',
                    'title' => 'Template: archive-product.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_content_product_template',
                    'type' => 'button_set',
                    'title' => 'Template: content-product.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_content_single_product_template',
                    'type' => 'button_set',
                    'title' => 'Template: content-single-product.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'woocommerce',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_single_product_reviews_template',
                    'type' => 'button_set',
                    'title' => 'Template: single-product-reviews.php overwrite theme',
                    'options' => array(
                        'wcuc' => 'wcuc',
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'wcuc',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_single_product_template',
                    'type' => 'button_set',
                    'title' => 'Template: single-product.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_template_section_cart_info',
                    'type' => 'info',
                    'desc' => 'Folder: cart',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_cart_cart_template',
                    'type' => 'button_set',
                    'title' => 'Template: cart\cart.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_cart_mini_cart_template',
                    'type' => 'button_set',
                    'title' => 'Template: cart\mini-cart.php overwrite theme',
                    'options' => array(
                        'wcuc' => 'wcuc',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'wcuc',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_template_section_global_info',
                    'type' => 'info',
                    'desc' => 'Folder: global',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_global_breadcrumb_template',
                    'type' => 'button_set',
                    'title' => 'Template: global\breadcrumb.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_global_form_login_template',
                    'type' => 'button_set',
                    'title' => 'Template: global\form-login.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_global_quantity_input_template',
                    'type' => 'button_set',
                    'title' => 'Template: global\quantity-input.php overwrite theme',
                    'options' => array(
                        'wcuc' => 'wcuc',
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'wcuc',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_global_sidebar_template',
                    'type' => 'button_set',
                    'title' => 'Template: global\sidebar.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_global_wrapper_start_template',
                    'type' => 'button_set',
                    'title' => 'Template: global\wrapper-start.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_template_section_loop_info',
                    'type' => 'info',
                    'desc' => 'Folder: loop',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_loop_add_to_cart_template',
                    'type' => 'button_set',
                    'title' => 'Template: loop\add-to-cart.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'woocommerce',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_loop_loop_start_template',
                    'type' => 'button_set',
                    'title' => 'Template: loop\loop-start.php overwrite theme',
                    'options' => array(
                        'wcuc' => 'wcuc',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'wcuc',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_loop_orderby_template',
                    'type' => 'button_set',
                    'title' => 'Template: loop\orderby.php overwrite theme',
                    'options' => array(
                        'wcuc' => 'wcuc',
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'wcuc',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_loop_rating_template',
                    'type' => 'button_set',
                    'title' => 'Template: loop\rating.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_loop_sale_flash_template',
                    'type' => 'button_set',
                    'title' => 'Template: loop\sale-flash.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'woo_template_section_single_product_info',
                    'type' => 'info',
                    'desc' => 'Folder: single-product',
                    'full_width' => TRUE,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_single_product_meta_template',
                    'type' => 'button_set',
                    'title' => 'Template: single-product\meta.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_single_product_price_template',
                    'type' => 'button_set',
                    'title' => 'Template: single-product\price.php overwrite theme',
                    'options' => array(
                        'wcuc' => 'wcuc',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'wcuc',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_single_product_product_image_template',
                    'type' => 'button_set',
                    'title' => 'Template: single-product\product-image.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'woocommerce',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_single_product_rating_template',
                    'type' => 'button_set',
                    'title' => 'Template: single-product\rating.php overwrite theme',
                    'options' => array(
                        'wcuc' => 'wcuc',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'wcuc',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_single_product_review_meta_template',
                    'type' => 'button_set',
                    'title' => 'Template: single-product\review-meta.php overwrite theme',
                    'options' => array(
                        'wcuc' => 'wcuc',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'wcuc',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_single_product_review_rating_template',
                    'type' => 'button_set',
                    'title' => 'Template: single-product\review-rating.php overwrite theme',
                    'options' => array(
                        'wcuc' => 'wcuc',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'wcuc',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_single_product_sale_flash_template',
                    'type' => 'button_set',
                    'title' => 'Template: single-product\sale-flash.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_single_product_tabs_tabs_template',
                    'type' => 'button_set',
                    'title' => 'Template: single-product\tabs\tabs.php overwrite theme',
                    'options' => array(
                        'theme' => 'theme',
                        'woocommerce' => 'woocommerce',
                    ),
                    'default' => 'theme',
                    'class' => '',
                ),
            ),
            'id' => 'woocommerce-template-files',
        ),
        array(
            'icon' => 'el-icon-cog',
            'title' => 'Hooks of active theme',
            'subsection' => TRUE,
            'fields' => array(
                array(
                    'id' => 'wcuc_woocommerce_before_main_content_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_before_main_content. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_woocommerce_after_main_content_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_after_main_content. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_woocommerce_before_shop_loop_item_title_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_before_shop_loop_item_title. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_woocommerce_after_shop_loop_item_title_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_after_shop_loop_item_title. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_woocommerce_before_shop_loop_item_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_before_shop_loop_item. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_woocommerce_after_shop_loop_item_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_after_shop_loop_item. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_woocommerce_before_shop_loop_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_before_shop_loop. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_woocommerce_after_shop_loop_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_after_shop_loop. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_woocommerce_shop_loop_item_title_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_shop_loop_item_title. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_woocommerce_before_single_product_summary_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_before_single_product_summary. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_woocommerce_after_single_product_summary_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_after_single_product_summary. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
                array(
                    'id' => 'wcuc_woocommerce_single_product_summary_hook',
                    'type' => 'switch',
                    'title' => 'Hook: woocommerce_single_product_summary. Disable theme hook',
                    'default' => 1,
                    'class' => '',
                ),
            ),
            'id' => 'hooks-of-active-theme',
        ),
        array(
            'id' => 'import/export',
            'title' => 'Εισαγωγή / Εξαγωγή',
            'heading' => '',
            'icon' => 'el el-refresh',
            'customizer' => FALSE,
            'fields' => array(
                array(
                    'id' => 'redux_import_export',
                    'type' => 'import_export',
                    'full_width' => TRUE,
                    'customizer' => FALSE,
                    'class' => '',
                ),
            ),
        ),
    ));

