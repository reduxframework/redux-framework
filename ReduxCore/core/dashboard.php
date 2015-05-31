<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    if (!class_exists('reduxDashboardWidget')) {
        class reduxDashboardWidget {
            public function __construct () {
                add_action('wp_dashboard_setup', array($this,'add_redux_dashboard'));
            }
            
            public function add_redux_dashboard() {
                add_meta_box('redux_dashboard_widget', 'Redux Framework News', array($this,'redux_dashboard_widget'), 'dashboard', 'side', 'high');
            }
            
            public function redux_dashboard_widget() {
                echo '<div class="rss-widget">';
                wp_widget_rss_output(array(
                     'url'          => 'http://reduxframework.com/feed/',
                     'title'        => 'REDUX_NEWS',
                     'items'        => 3,
                     'show_summary' => 1,
                     'show_author'  => 0,
                     'show_date'    => 1
                ));
                echo '</div>';
            }
        }
        
        new reduxDashboardWidget();
    }
