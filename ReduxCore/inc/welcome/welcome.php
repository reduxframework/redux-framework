<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }


    class Redux_Welcome {

        /**
         * @var string The capability users should have to view the page
         */
        public $minimum_capability = 'manage_options';
        public $display_version = "";
        public $redux_loaded = false;

        /**
         * Get things started
         *
         * @since 1.4
         */
        public function __construct() {

            add_action( 'redux/loaded', array( $this, 'init' ) );

        }

        public function init() {

            if ( $this->redux_loaded ) {
                return;
            }
            $this->redux_loaded = true;
            add_action( 'admin_menu', array( $this, 'admin_menus' ) );

            if ( isset( $_GET['page'] ) ) {
                if ( substr( $_GET['page'], 0, 6 ) == "redux-" ) {
                    $version               = explode( '.', ReduxFramework::$_version );
                    $this->display_version = $version[0] . '.' . $version[1];
                    add_filter( 'admin_footer_text', array( $this, 'change_wp_footer' ) );
                    add_action( 'admin_head', array( $this, 'admin_head' ) );
                    add_action( 'admin_init', array( $this, 'welcome' ) );
                }
            }
            update_option( 'redux_version_upgraded_from', ReduxFramework::$_version );
            set_transient( '_redux_activation_redirect', true, 30 );

        }

        public function change_wp_footer() {
            echo 'If you like <strong>Redux</strong> please leave us a <a href="https://wordpress.org/support/view/plugin-reviews/redux-framework?filter=5#postform" target="_blank" class="redux-rating-link" data-rated="Thanks :)">★★★★★</a> rating. A huge thank you from Redux in advance!';
        }

        /**
         * Register the Dashboard Pages which are later hidden but these pages
         * are used to render the Welcome and Credits pages.
         *
         * @access public
         * @since  1.4
         * @return void
         */
        public function admin_menus() {

            // About Page
            add_management_page(
                __( 'Welcome to Redux Framework', 'redux-framework' ), __( 'Redux Framework', 'redux-framework' ), $this->minimum_capability, 'redux-about', array(
                    $this,
                    'about_screen'
                )
            );

            // Changelog Page
            add_management_page(
                __( 'Redux Framework Changelog', 'redux-framework' ), __( 'Redux Framework Changelog', 'redux-framework' ), $this->minimum_capability, 'redux-changelog', array(
                    $this,
                    'changelog_screen'
                )
            );

            // Support Page
            add_management_page(
                __( 'Get Support', 'redux-framework' ), __( 'Get Support', 'redux-framework' ), $this->minimum_capability, 'redux-support', array(
                    $this,
                    'get_support'
                )
            );

            // Support Page
            add_management_page(
                __( 'Redux Extensions', 'redux-framework' ), __( 'Redux Extensions', 'redux-framework' ), $this->minimum_capability, 'redux-extensions', array(
                    $this,
                    'redux_extensions'
                )
            );


            // Credits Page
            add_management_page(
                __( 'The people that develop Redux Framework', 'redux-framework' ), __( 'The people that develop Redux Framework', 'redux-framework' ), $this->minimum_capability, 'redux-credits', array(
                    $this,
                    'credits_screen'
                )
            );
            remove_submenu_page( 'tools.php', 'redux-credits' );
            remove_submenu_page( 'tools.php', 'redux-changelog' );
            remove_submenu_page( 'tools.php', 'redux-getting-started' );
            remove_submenu_page( 'tools.php', 'redux-credits' );
            remove_submenu_page( 'tools.php', 'redux-support' );
            remove_submenu_page( 'tools.php', 'redux-extensions' );

        }

        /**
         * Hide Individual Dashboard Pages
         *
         * @access public
         * @since  1.4
         * @return void
         */
        public function admin_head() {
            //remove_submenu_page( 'index.php', 'redux-about' );
            //remove_submenu_page( 'index.php', 'redux-changelog' );
            //remove_submenu_page( 'index.php', 'redux-getting-started' );
            //remove_submenu_page( 'index.php', 'redux-credits' );
            //remove_submenu_page( 'index.php', 'redux-support' );
            //remove_submenu_page( 'index.php', 'redux-extensions' );

            // Badge for welcome page
            $badge_url = ReduxFramework::$_url . 'assets/images/redux-badge.png';
            ?>
            <link rel='stylesheet' id='elusive-icons'
                  href='<?php echo ReduxFramework::$_url ?>assets/css/vendor/elusive-icons/elusive-icons.css'
                  type='text/css' media='all'/>

            <link rel='stylesheet' id='elusive-icons'
                  href='<?php echo ReduxFramework::$_url ?>/inc/welcome/welcome.css'
                  type='text/css' media='all'/>
            <style type="text/css">
                .redux-badge:before {
                <?php echo is_rtl() ? 'right' : 'left'; ?> : 0;
                }

                .about-wrap .redux-badge {
                <?php echo is_rtl() ? 'left' : 'right'; ?> : 0;
                }

                .about-wrap .feature-rest div {
                    padding- <?php echo is_rtl() ? 'left' : 'right'; ?>: 100px;
                }

                .about-wrap .feature-rest div.last-feature {
                    padding- <?php echo is_rtl() ? 'right' : 'left'; ?>: 100px;
                    padding- <?php echo is_rtl() ? 'left' : 'right'; ?>: 0;
                }

                .about-wrap .feature-rest div.icon:before {
                    margin: <?php echo is_rtl() ? '0 -100px 0 0' : '0 0 0 -100px'; ?>;
                }
            </style>
        <?php
        }

        /**
         * Navigation tabs
         *
         * @access public
         * @since  1.9
         * @return void
         */
        public function tabs() {
            $selected = isset ( $_GET['page'] ) ? $_GET['page'] : 'redux-about';
            ?>
            <h2 class="nav-tab-wrapper">
                <a class="nav-tab <?php echo $selected == 'redux-about' ? 'nav-tab-active' : ''; ?>"
                   href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-about' ), 'tools.php' ) ) ); ?>">
                    <?php _e( "About Redux", 'redux-framework' ); ?>
                </a>
                <a class="nav-tab <?php echo $selected == 'redux-support' ? 'nav-tab-active' : ''; ?>"
                   href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-support' ), 'tools.php' ) ) ); ?>">
                    <?php _e( 'Support', 'redux-framework' ); ?>
                </a>
                <a class="nav-tab <?php echo $selected == 'redux-extensions' ? 'nav-tab-active' : ''; ?>"
                   href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-extensions' ), 'tools.php' ) ) ); ?>">
                    <?php _e( 'Extensions', 'redux-framework' ); ?>
                </a>
                <a class="nav-tab <?php echo $selected == 'redux-changelog' ? 'nav-tab-active' : ''; ?>"
                   href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-changelog' ), 'tools.php' ) ) ); ?>">
                    <?php _e( 'Changelog', 'redux-framework' ); ?>
                </a>
                <a class="nav-tab <?php echo $selected == 'redux-credits' ? 'nav-tab-active' : ''; ?>"
                   href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-credits' ), 'tools.php' ) ) ); ?>">
                    <?php _e( 'Credits', 'redux-framework' ); ?>
                </a>
            </h2>
        <?php
        }

        /**
         * Render About Screen
         *
         * @access public
         * @since  1.4
         * @return void
         */
        public function about_screen() {


            ?>
            <div class="wrap about-wrap">
                <h1><?php printf( __( 'Welcome to Redux Framework %s', 'redux-framework' ), $this->display_version ); ?></h1>

                <div
                    class="about-text"><?php printf( __( 'Thank you for updating to the latest version! Redux Framework %s is a huge step forward in Redux Development. Look at all that\'s new.', 'redux-framework' ), $this->display_version ); ?></div>
                <div
                    class="redux-badge"><i
                        class="el el-redux"></i><span><?php printf( __( 'Version %s', 'redux-framework' ), ReduxFramework::$_version ); ?></span>
                </div>

                <?php $this->tabs(); ?>

                <div class="changelog">
                    <h3><?php _e( 'Some Feature', 'redux-framework' ); ?></h3>

                    <div class="feature-section">

                        <h4><?php _e( 'Feature', 'redux-framework' ); ?></h4>

                        <p></p>

                        <h4><?php _e( 'Feature', 'redux-framework' ); ?></h4>

                        <p></p>

                    </div>
                </div>

                <div class="changelog">
                    <h3><?php _e( 'Some feature', 'redux-framework' ); ?></h3>

                    <div class="feature-section">


                        <h4><?php _e( 'Feature', 'redux-framework' ); ?></h4>

                        <p></p>

                        <h4><?php _e( 'Feature', 'redux-framework' ); ?></h4>

                        <p></p>

                        <h4><?php _e( 'Feature', 'redux-framework' ); ?></h4>

                        <p></p>


                        <h4><?php _e( 'Feature', 'redux-framework' ); ?></h4>

                        <p></p>

                        <p></p>
                    </div>
                </div>

                <div class="changelog">
                    <h3><?php _e( 'More Features', 'redux-framework' ); ?></h3>

                    <div class="feature-section">

                        <h4><?php _e( 'Feature', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'description', 'redux-framework' ); ?></p>

                        <h4><?php _e( 'Feature', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'description', 'redux-framework' ); ?></p>


                        <h4><?php _e( 'Feature', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'description', 'redux-framework' ); ?></p>

                    </div>
                </div>

                <div class="changelog">
                    <h3><?php _e( 'Additional Updates', 'redux-framework' ); ?></h3>

                    <div class="feature-section col three-col">
                        <div>
                            <h4><?php _e( 'Cool thing', 'redux-framework' ); ?></h4>

                            <p><?php _e( 'cool thing description.', 'redux-framework' ); ?></p>

                            <h4><?php _e( 'Cool thing', 'redux-framework' ); ?></h4>

                            <p><?php _e( 'cool thing description.', 'redux-framework' ); ?></p>
                        </div>

                        <div>
                            <h4><?php _e( 'Cool thing', 'redux-framework' ); ?></h4>

                            <p><?php _e( 'cool thing description.', 'redux-framework' ); ?></p>

                            <h4><?php _e( 'Cool thing', 'redux-framework' ); ?></h4>

                            <p><?php _e( 'cool thing description.', 'redux-framework' ); ?></p>
                        </div>

                        <div class="last-feature">
                            <h4><?php _e( 'Cool thing', 'redux-framework' ); ?></h4>

                            <p><?php _e( 'cool thing description.', 'redux-framework' ); ?></p>

                            <h4><?php _e( 'Cool thing', 'redux-framework' ); ?></h4>

                            <p><?php _e( 'cool thing description.', 'redux-framework' ); ?></p>
                        </div>
                    </div>
                </div>

            </div>
        <?php
        }

        /**
         * Render Changelog Screen
         *
         * @access public
         * @since  2.0.3
         * @return void
         */
        public function changelog_screen() {

            ?>
            <div class="wrap about-wrap">
                <h1><?php _e( 'Redux Framework - Changelog', 'redux-framework' ); ?></h1>

                <div
                    class="about-text"><?php _e( 'Our core mantra at Redux is backwards compatibility. With hundreds of thousands of instances worldwide, you can be assured that we will take care of you and your clients.', 'redux-framework' ); ?></div>
                <div
                    class="redux-badge"><i
                        class="el el-redux"></i><span><?php printf( __( 'Version %s', 'redux-framework' ), ReduxFramework::$_version ); ?></span>
                </div>

                <?php $this->tabs(); ?>

                <div class="changelog">
                    <div class="feature-section">
                        <?php echo $this->parse_readme(); ?>
                    </div>
                </div>

            </div>
        <?php
        }

        /**
         * Render Changelog Screen
         *
         * @access public
         * @since  2.0.3
         * @return void
         */
        public function redux_extensions() {
            /*
            repeater =>
            social profiles =>
            js button =>
            multi media =>
            css layout =>
            color schemes => adjust-alt
            custom fonts => fontsize
            code mirror => view-mode
            live search => search
            support faq's => question
            date time picker =>
            premium support =>
            metaboxes =>
            widget areas =>
            shortcodes =>
            icon select => gallery
            tracking =>
             * */
            $iconMap = array(
                'repeater'        => 'asl',
                'social-profiles' => 'group',
                'js-button'       => 'hand-down',
                'multi-media'     => 'picture',
                'css-layout'      => 'fullscreen',
                'color-schemes'   => 'adjust-alt',
                'custom-fonts'    => 'fontsize',
                'codemirror'      => 'view-mode',
                'live-search'     => 'search',
                'support-faqs'    => 'question',
                'date-time'       => 'calendar',
                'premium-support' => 'fire',
                'metaboxes'       => 'magic',
                'widget-areas'    => 'inbox-box',
                'shortcodes'      => 'shortcode',
                'icon-select'     => 'gallery',
            );
            $colors  = array(
                '8CC63F',
                '8CC63F',
                '0A803B',
                '25AAE1',
                '0F75BC',
                'F7941E',
                'F1592A',
                'ED217C',
                'BF1E2D',
                '8569CF',
                '0D9FD8',
                '8AD749',
                'EECE00',
                'F8981F',
                'F80E27',
                'F640AE'
            );
            shuffle($colors);
            echo '<style type="text/css">';
            ?>

            <?php
            foreach ($colors as $key => $color) {
                echo '.theme-browser .theme.color'.$key.' .theme-screenshot{background-color:'.Redux_Helpers::hex2rgba($color, .45).';}';
                echo '.theme-browser .theme.color'.$key.':hover .theme-screenshot{background-color:'.Redux_Helpers::hex2rgba($color, .75).';}';

            }
            echo '</style>';
            $color = 1;


            ?>


            <div class="wrap about-wrap">
                <h1><?php _e( 'Redux Framework - Extensions', 'redux-framework' ); ?></h1>

                <div
                    class="about-text"><?php printf( __( 'Supercharge your Redux experience. Our extensions provide you with features that will take your products to the next level.', 'redux-framework' ), $this->display_version ); ?></div>
                <div
                    class="redux-badge"><i
                        class="el el-redux"></i><span><?php printf( __( 'Version %s', 'redux-framework' ), ReduxFramework::$_version ); ?></span>
                </div>

                <?php $this->tabs(); ?>

                <p class="about-description"><?php _e( "While some are built specificially for developers, extensions such as Custom Fonts are sure to make any user happy.", 'redux-framework' ); ?></p>

                <div class="extensions">
                    <div class="feature-section theme-browser rendered" style="clear:both;">
                        <?php

                            $data = get_transient( 'redux-extensions-fetch' );

                            if ( empty( $data ) ) {
                                $data = json_decode( wp_remote_retrieve_body( wp_remote_get( 'http://reduxframework.com/wp-admin/admin-ajax.php?action=get_redux_extensions' ) ), true );
                                if ( ! empty( $data ) ) {
                                    set_transient( 'redux-extensions-fetch', $data, 24 * HOUR_IN_SECONDS );
                                }
                            }
                            function shuffle_assoc( $list ) {
                                if ( ! is_array( $list ) ) {
                                    return $list;
                                }

                                $keys = array_keys( $list );
                                shuffle( $keys );
                                $random = array();
                                foreach ( $keys as $key ) {
                                    $random[ $key ] = $list[ $key ];
                                }

                                return $random;
                            }

                            $data = shuffle_assoc( $data );

                            foreach ( $data as $key => $extension ) :

                                ?>
                                <div class="theme color<?php echo $color; $color++;?>">
                                    <div class="theme-screenshot">
                                        <figure>
                                            <i class="el <?php echo isset( $iconMap[ $key ] ) && ! empty( $iconMap[ $key ] ) ? 'el-' . $iconMap[ $key ] : 'el-redux'; ?>"></i>
                                            <figcaption>
                                                <p><?php echo $extension['excerpt'];?></p>
                                                <a href="<?php echo $extension['url']; ?>" target="_blank">Learn more</a>
                                            </figcaption>
                                        </figure>
                                    </div>
                                    <h3 class="theme-name" id="classic"><?php echo $extension['title']; ?></h3>

                                    <div class="theme-actions">
                                        <a class="button button-primary button-install-demo"
                                           data-demo-id="<?php echo $key; ?>"
                                           href="<?php echo $extension['url']; ?>" target="_blank">Learn
                                            More</a></div>
                                </div>

                            <?php
                            endforeach;

                        ?>
                    </div>
                </div>

            </div>
        <?php
        }


        /**
         * Render Get Support Screen
         *
         * @access public
         * @since  1.9
         * @return void
         */
        public function get_support() {

            ?>
            <div class="wrap about-wrap">
                <h1><?php _e( 'Redux Framework - Support', 'redux-framework' ); ?></h1>

                <div
                    class="about-text"><?php printf( __( 'We are an open source project used by developers to make powerful control panels.', 'redux-framework' ), $this->display_version ); ?></div>
                <div
                    class="redux-badge"><i
                        class="el el-redux"></i><span><?php printf( __( 'Version %s', 'redux-framework' ), ReduxFramework::$_version ); ?></span>
                </div>

                <?php $this->tabs(); ?>

                <p class="about-description"><?php _e( 'To get the proper support, we need to send you to the correct place. Please choose the type of user you are.', 'redux-framework' ); ?></p>

                <div class="support">
                    <ul>
                        <li class="userType">
                            <a href=""><i class="el el-child"></i></a><h2>User</h2>
                        </li>
                        <li class="userType">
                            <a href=""><i class="el el-idea"></i></a><h2>Developer</h2>
                        </li>
                    </ul>



                    <h3><?php _e( 'Hello there WordPress User!', 'redux-framework' ); ?></h3>

                    <div class="feature-section">


                    </div>
                </div>


            </div>
        <?php
        }

        /**
         * Render Credits Screen
         *
         * @access public
         * @since  1.4
         * @return void
         */
        public function credits_screen() {

            ?>
            <div class="wrap about-wrap">
                <h1><?php _e( 'Redux Framework - A Community Effort', 'redux-framework' ); ?></h1>

                <div
                    class="about-text"><?php _e( 'We recognize we are nothing without our community. We would like to thank all of those who help Redux to be what it is. Thank you for your involvement.', 'redux-framework' ); ?></div>
                <div
                    class="redux-badge"><i
                        class="el el-redux"></i><span><?php printf( __( 'Version %s', 'redux-framework' ), ReduxFramework::$_version ); ?></span>
                </div>

                <?php $this->tabs(); ?>

                <p class="about-description"><?php _e( 'Redux is created by a community of developers world wide. Want to have your name listed too? <a href="https://github.com/reduxframework/redux-framework/blob/master/CONTRIBUTING.md" target="_blank">Contribute to Redux</a>.', 'redux-framework' ); ?></p>

                <?php echo $this->contributors(); ?>
            </div>
        <?php
        }

        /**
         * Parse the Redux readme.txt file
         *
         * @since 2.0.3
         * @return string $readme HTML formatted readme file
         */
        public function parse_readme() {

            if ( file_exists( dirname( __FILE__ ) . '/fields/raw/' . "/parsedown.php" ) ) {
                require_once dirname( __FILE__ ) . '/fields/raw/' . "/parsedown.php";
                $Parsedown = new Parsedown();

                return $Parsedown->text( trim( str_replace( '# Redux Framework Changelog', '', wp_remote_retrieve_body( wp_remote_get( ReduxFramework::$_url . '../../CHANGELOG.md' ) ) ) ) );
            }

            return '<script src="http://gist-it.appspot.com/https://github.com/reduxframework/redux-framework/blob/master/CHANGELOG.md?slice=2:0&footer=0">// <![CDATA[// ]]></script>';

        }

        /**
         * Render Contributors List
         *
         * @since 1.4
         * @uses  Redux_Welcome::get_contributors()
         * @return string $contributor_list HTML formatted list of all the contributors for Redux
         */
        public function contributors() {
            $contributors = $this->get_contributors();

            if ( empty ( $contributors ) ) {
                return '';
            }

            $contributor_list = '<ul class="wp-people-group">';

            foreach ( $contributors as $contributor ) {
                $contributor_list .= '<li class="wp-person">';
                $contributor_list .= sprintf( '<a href="%s" title="%s" target="_blank">', esc_url( 'https://github.com/' . $contributor->login ), esc_html( sprintf( __( 'View %s', 'redux-framework' ), $contributor->login ) )
                );
                $contributor_list .= sprintf( '<img src="%s" width="64" height="64" class="gravatar" alt="%s" />', esc_url( $contributor->avatar_url ), esc_html( $contributor->login ) );
                $contributor_list .= '</a>';
                $contributor_list .= sprintf( '<a class="web" href="%s" target="_blank">%s</a>', esc_url( 'https://github.com/' . $contributor->login ), esc_html( $contributor->login ) );
                $contributor_list .= '</a>';
                $contributor_list .= '</li>';
            }

            $contributor_list .= '</ul>';

            return $contributor_list;
        }

        /**
         * Retreive list of contributors from GitHub.
         *
         * @access public
         * @since  1.4
         * @return array $contributors List of contributors
         */
        public function get_contributors() {
            $contributors = get_transient( 'redux_contributors' );

            if ( false !== $contributors ) {
                return $contributors;
            }

            $response = wp_remote_get( 'https://api.github.com/repos/ReduxFramework/redux-framework/contributors', array( 'sslverify' => false ) );

            if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
                return array();
            }

            $contributors = json_decode( wp_remote_retrieve_body( $response ) );

            if ( ! is_array( $contributors ) ) {
                return array();
            }

            set_transient( 'redux_contributors', $contributors, 3600 );

            return $contributors;
        }

        /**
         * Sends user to the Welcome page on first activation of Redux as well as each
         * time Redux is upgraded to a new version
         *
         * @access public
         * @since  1.4
         * @global $redux_options Array of all the Redux Options
         * @return void
         */
        public function welcome() {
            //logconsole( 'welcome.php' );
            //return;
            // Bail if no activation redirect
            if ( ! get_transient( '_redux_activation_redirect' ) ) {
                return;
            }

            // Delete the redirect transient
            delete_transient( '_redux_activation_redirect' );

            // Bail if activating from network, or bulk
            if ( is_network_admin() || isset ( $_GET['activate-multi'] ) ) {
                return;
            }

            $upgrade = get_option( 'redux_version_upgraded_from' );
//
//        if ( !$upgrade ) { // First time install
//            wp_safe_redirect ( admin_url ( 'index.php?page=redux-getting-started' ) );
//            exit;
//        } else { // Update
//            wp_safe_redirect ( admin_url ( 'index.php?page=redux-about' ) );
//            exit;
//        }
        }
    }

    new Redux_Welcome();




//DOVY!!  HERE!!!
// Getting started page
//                    if (  is_admin () && $this->args['dev_mode'] ) {
//
//                        if ( isset($_GET['page']) && ($_GET['page'] == 'redux-about' || $_GET['page'] == 'redux-getting-started' || $_GET['page'] == 'redux-credits' || $_GET['page'] == 'redux-changelog' )) {
//                            //logconsole('inc');

//                        } else {
//                            //logconsole('compare');
//                            if (isset($_GET['page']) && $_GET['page'] == $this->args['page_slug']) {
//                                $saveVer = get_option('redux_version_upgraded_from');
//                                $curVer = self::$_version;
//
//                                if (empty($saveVer)) {
//                                    //logconsole('redir');
//                                    wp_safe_redirect ( admin_url ( 'index.php?page=redux-getting-started' ) );
//                                    exit;
//                                } else if (version_compare($curVer, $saveVer, '>')) {
//                                    wp_safe_redirect ( admin_url ( 'index.php?page=redux-about' ) );
//                                    exit;
//                                }
//                            }
//                        }
//                    }