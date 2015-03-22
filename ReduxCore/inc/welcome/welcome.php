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

            // About Page
            add_management_page(
                __( 'Redux Framework Status', 'redux-framework' ), __( 'Redux Framework', 'redux-framework' ), $this->minimum_capability, 'redux-status', array(
                    $this,
                    'status_screen'
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
            remove_submenu_page( 'tools.php', 'redux-status' );

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
                    <?php _e( "What's New", 'redux-framework' ); ?>
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
                <a class="nav-tab <?php echo $selected == 'redux-status' ? 'nav-tab-active' : ''; ?>"
                   href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-status' ), 'tools.php' ) ) ); ?>">
                    <?php _e( 'Status', 'redux-framework' ); ?>
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

            include_once('views/about.php');

        }

        /**
         * Render Changelog Screen
         *
         * @access public
         * @since  2.0.3
         * @return void
         */
        public function changelog_screen() {

            include_once('views/changelog.php');

        }

        /**
         * Render Changelog Screen
         *
         * @access public
         * @since  2.0.3
         * @return void
         */
        public function redux_extensions() {

            include_once('views/extensions.php');

        }


        /**
         * Render Get Support Screen
         *
         * @access public
         * @since  1.9
         * @return void
         */
        public function get_support() {

            include_once('views/support.php');

        }

        /**
         * Render Credits Screen
         *
         * @access public
         * @since  1.4
         * @return void
         */
        public function credits_screen() {

            include_once('views/credits.php');

        }

        /**
         * Render Status Report Screen
         *
         * @access public
         * @since  1.4
         * @return void
         */
        public function status_screen() {

            include_once('views/status_report.php');

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