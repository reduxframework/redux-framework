<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    class Redux_Welcome {

        /**
         * @var string The capability users should have to view the page
         */
        public $minimum_capability = 'manage_options';

        /**
         * Get things started
         *
         * @since 1.4
         */
        public function __construct() {
            add_action( 'admin_menu', array( $this, 'admin_menus' ) );
            add_action( 'admin_head', array( $this, 'admin_head' ) );
            add_action( 'admin_init', array( $this, 'welcome' ) );

            update_option( 'redux_version_upgraded_from', ReduxFramework::$_version );
            set_transient( '_redux_activation_redirect', true, 30 );

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
            add_dashboard_page(
                __( 'Welcome to Redux Framework', 'redux-framework' ), __( 'Welcome to Redux Framework', 'redux-framework' ), $this->minimum_capability, 'redux-about', array(
                    $this,
                    'about_screen'
                )
            );

            // Changelog Page
            add_dashboard_page(
                __( 'Redux Framework Changelog', 'redux-framework' ), __( 'Redux Framework Changelog', 'redux-framework' ), $this->minimum_capability, 'redux-changelog', array(
                    $this,
                    'changelog_screen'
                )
            );

            // Getting Started Page
            add_dashboard_page(
                __( 'Getting started with Redux Framework', 'redux-framework' ), __( 'Getting started with Redux Framework', 'redux-framework' ), $this->minimum_capability, 'redux-getting-started', array(
                    $this,
                    'getting_started_screen'
                )
            );

            // Credits Page
            add_dashboard_page(
                __( 'The people that develop Redux Framework', 'redux-framework' ), __( 'The people that develop Redux Framework', 'redux-framework' ), $this->minimum_capability, 'redux-credits', array(
                    $this,
                    'credits_screen'
                )
            );
        }

        /**
         * Hide Individual Dashboard Pages
         *
         * @access public
         * @since  1.4
         * @return void
         */
        public function admin_head() {
            remove_submenu_page( 'index.php', 'redux-about' );
            remove_submenu_page( 'index.php', 'redux-changelog' );
            remove_submenu_page( 'index.php', 'redux-getting-started' );
            remove_submenu_page( 'index.php', 'redux-credits' );

            // Badge for welcome page
            $badge_url = ReduxFramework::$_url . 'assets/images/redux-badge.png';
            ?>
            <style type="text/css" media="screen">
                /*<![CDATA[*/
                .redux-badge {
                    padding-top: 150px;
                    height: 52px;
                    width: 185px;
                    color: #666;
                    font-weight: bold;
                    font-size: 14px;
                    text-align: center;
                    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
                    margin: 0 -5px;
                    background: url('<?php echo $badge_url; ?>') no-repeat;
                }

                .about-wrap .redux-badge {
                    position: absolute;
                    top: 0;
                    right: 0;
                }

                .redux-welcome-screenshots {
                    float: right;
                    margin-left: 10px !important;
                }

                .about-wrap .feature-section {
                    margin-top: 20px;
                }

                /*]]>*/
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
                   href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-about' ), 'index.php' ) ) ); ?>">
                    <?php _e( "What's New", 'redux-framework' ); ?>
                </a>
                <a class="nav-tab <?php echo $selected == 'redux-getting-started' ? 'nav-tab-active' : ''; ?>"
                   href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-getting-started' ), 'index.php' ) ) ); ?>">
                    <?php _e( 'Getting Started', 'redux-framework' ); ?>
                </a>
                <a class="nav-tab <?php echo $selected == 'redux-changelog' ? 'nav-tab-active' : ''; ?>"
                   href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-changelog' ), 'index.php' ) ) ); ?>">
                    <?php _e( 'Changelog', 'redux-framework' ); ?>
                </a>
                <a class="nav-tab <?php echo $selected == 'redux-credits' ? 'nav-tab-active' : ''; ?>"
                   href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-credits' ), 'index.php' ) ) ); ?>">
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
            list( $display_version ) = explode( '-', ReduxFramework::$_version );
            ?>
            <div class="wrap about-wrap">
                <h1><?php printf( __( 'Welcome to Redux Framework %s', 'redux-framework' ), $display_version ); ?></h1>

                <div
                    class="about-text"><?php printf( __( 'Thank you for updating to the latest version! Redux Framework %s is ready to <add description>', 'redux-framework' ), $display_version ); ?></div>
                <div
                    class="redux-badge"><?php printf( __( 'Version %s', 'redux-framework' ), $display_version ); ?></div>

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

                <div class="return-to-dashboard">
                    <a href="<?php echo esc_url( admin_url( add_query_arg( array(
                        'post_type' => 'download',
                        'page'      => 'redux-settings'
                    ), 'edit.php' ) ) ); ?>"><?php _e( 'Go to Redux Framework', 'redux-framework' ); ?></a> &middot;
                    <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'redux-changelog' ), 'index.php' ) ) ); ?>"><?php _e( 'View the Full Changelog', 'redux-framework' ); ?></a>
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
            list( $display_version ) = explode( '-', ReduxFramework::$_version );
            ?>
            <div class="wrap about-wrap">
                <h1><?php _e( 'Redux Framework Changelog', 'redux-framework' ); ?></h1>

                <div
                    class="about-text"><?php printf( __( 'Thank you for updating to the latest version! Redux Framework %s is ready to make your <description>', 'redux-framework' ), $display_version ); ?></div>
                <div
                    class="redux-badge"><?php printf( __( 'Version %s', 'redux-framework' ), $display_version ); ?></div>

                <?php $this->tabs(); ?>

                <div class="changelog">
                    <h3><?php _e( 'Full Changelog', 'redux-framework' ); ?></h3>

                    <div class="feature-section">
                        <?php echo $this->parse_readme(); ?>
                    </div>
                </div>

                <div class="return-to-dashboard">
                    <a href="<?php echo esc_url( admin_url( add_query_arg( array(
                        'post_type' => 'download',
                        'page'      => 'redux-settings'
                    ), 'edit.php' ) ) ); ?>"><?php _e( 'Go to Redux Framework', 'redux-framework' ); ?></a>
                </div>
            </div>
        <?php
        }

        /**
         * Render Getting Started Screen
         *
         * @access public
         * @since  1.9
         * @return void
         */
        public function getting_started_screen() {
            list( $display_version ) = explode( '-', ReduxFramework::$_version );
            ?>
            <div class="wrap about-wrap">
                <h1><?php printf( __( 'Welcome to Redux Framework %s', 'redux-framework' ), $display_version ); ?></h1>

                <div
                    class="about-text"><?php printf( __( 'Thank you for updating to the latest version! Redux Framework %s is ready to make your <description>', 'redux-framework' ), $display_version ); ?></div>
                <div
                    class="redux-badge"><?php printf( __( 'Version %s', 'redux-framework' ), $display_version ); ?></div>

                <?php $this->tabs(); ?>

                <p class="about-description"><?php _e( 'Use the tips below to get started using Redux Framework. You\'ll be up and running in no time!', 'redux-framework' ); ?></p>

                <div class="changelog">
                    <h3><?php _e( 'Creating Your First Panel', 'redux-framework' ); ?></h3>

                    <div class="feature-section">


                        <h4><?php printf( __( '<a href="%s">%s &rarr; Add New</a>', 'redux-framework' ), admin_url( 'post-new.php?post_type=download' ), redux_get_label_plural() ); ?></h4>

                        <p><?php printf( __( 'The %s menu is your access point for all aspects of your Easy Digital Downloads product creation and setup. To create your first product, simply click Add New and then fill out the product details.', 'redux-framework' ), redux_get_label_plural() ); ?></p>

                        <h4><?php _e( 'Product Price', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'Products can have simple prices or variable prices if you wish to have more than one price point for a product. For a single price, simply enter the price. For multiple price points, click <em>Enable variable pricing</em> and enter the options.', 'redux-framework' ); ?></p>

                        <h4><?php _e( 'Download Files', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'Uploading the downloadable files is simple. Click <em>Upload File</em> in the Download Files section and choose your download file. To add more than one file, simply click the <em>Add New</em> button.', 'redux-framework' ); ?></p>

                    </div>
                </div>

                <div class="changelog">
                    <h3><?php _e( 'Display a Product Grid', 'redux-framework' ); ?></h3>

                    <div class="feature-section">

                        <img src="<?php echo Redux_PLUGIN_URL . 'assets/images/screenshots/grid.png'; ?>"
                             class="redux-welcome-screenshots"/>

                        <h4><?php _e( 'Flexible Product Grids', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'The [downloads] shortcode will display a product grid that works with any theme, no matter the size. It is even responsive!', 'redux-framework' ); ?></p>

                        <h4><?php _e( 'Change the Number of Columns', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'You can easily change the number of columns by adding the columns="x" parameter:', 'redux-framework' ); ?></p>

                        <p>
                        <pre>[downloads columns="4"]</pre>
                        </p>

                        <h4><?php _e( 'Additional Display Options', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'The product grids can be customized in any way you wish and there is <a href="%s">extensive documentation</a> to assist you.', 'redux-framework' ); ?></p>
                    </div>
                </div>

                <div class="changelog">
                    <h3><?php _e( 'Purchase Buttons Anywhere', 'redux-framework' ); ?></h3>

                    <div class="feature-section">


                        <h4><?php _e( 'The <em>[purchase_link]</em> Shortcode', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'With easily accessible shortcodes to display purchase buttons, you can add a Buy Now or Add to Cart button for any product anywhere on your site in seconds.', 'redux-framework' ); ?></p>

                        <h4><?php _e( 'Buy Now Buttons', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'Purchase buttons can behave as either Add to Cart or Buy Now buttons. With Buy Now buttons customers are taken straight to PayPal, giving them the most frictionless purchasing experience possible.', 'redux-framework' ); ?></p>

                    </div>
                </div>

                <div class="changelog">
                    <h3><?php _e( 'Need Help?', 'redux-framework' ); ?></h3>

                    <div class="feature-section">

                        <h4><?php _e( 'Phenomenal Support', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'We do our best to provide the best support we can. If you encounter a problem or have a question, post a question in the <a href="' . 'https://' . 'easydigitaldownloads.com/support">support forums</a>.', 'redux-framework' ); ?></p>

                        <h4><?php _e( 'Need Even Faster Support?', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'Our <a href="' . 'https://' . 'easydigitaldownloads.com/support/pricing/">Priority Support forums</a> are there for customers that need faster and/or more in-depth assistance.', 'redux-framework' ); ?></p>

                    </div>
                </div>

                <div class="changelog">
                    <h3><?php _e( 'Stay Up to Date', 'redux-framework' ); ?></h3>

                    <div class="feature-section">

                        <h4><?php _e( 'Get Notified of Extension Releases', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'New extensions that make Easy Digital Downloads even more powerful are released nearly every single week. Subscribe to the newsletter to stay up to date with our latest releases. <a href="' . 'http://' . 'eepurl.com/kaerz" target="_blank">Signup now</a> to ensure you do not miss a release!', 'redux-framework' ); ?></p>

                        <h4><?php _e( 'Get Alerted About New Tutorials', 'redux-framework' ); ?></h4>

                        <p><?php _e( '<a href="' . 'http://' . 'eepurl.com/kaerz" target="_blank">Signup now</a> to hear about the latest tutorial releases that explain how to take Easy Digital Downloads further.', 'redux-framework' ); ?></p>

                    </div>
                </div>

                <div class="changelog">
                    <h3><?php _e( 'Extensions for Everything', 'redux-framework' ); ?></h3>

                    <div class="feature-section">

                        <h4><?php _e( 'Over 250 Extensions', 'redux-framework' ); ?></h4>

                        <p><?php _e( 'Add-on plugins are available that greatly extend the default functionality of Easy Digital Downloads. There are extensions for payment processors, such as Stripe and PayPal, extensions for newsletter integrations, and many, many more.', 'redux-framework' ); ?></p>

                        <h4><?php _e( 'Visit the Extension Store', 'redux-framework' ); ?></h4>

                        <p><?php _e( '<a href="' . 'https://' . 'asydigitaldownloads.com/extensions" target="_blank">The Extensions store</a> has a list of all available extensions, including convenient category filters so you can find exactly what you are looking for.', 'redux-framework' ); ?></p>

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
            list( $display_version ) = explode( '-', ReduxFramework::$_version );
            ?>
            <div class="wrap about-wrap">
                <h1><?php printf( __( 'Welcome to Redux Framework %s', 'redux-framework' ), $display_version ); ?></h1>

                <div
                    class="about-text"><?php printf( __( 'Thank you for updating to the latest version! Redux Framework %s is ready to make your <description>', 'redux-framework' ), $display_version ); ?></div>
                <div
                    class="redux-badge"><?php printf( __( 'Version %s', 'redux-framework' ), $display_version ); ?></div>

                <?php $this->tabs(); ?>

                <p class="about-description"><?php _e( 'Redux Framework is created by a worldwide team of developers who <something witty here>', 'redux-framework' ); ?></p>

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
            $url = ReduxFramework::$_dir;
            $url = str_replace( 'ReduxCore', '', $url );

            $file = file_exists( $url . 'README.txt' ) ? $url . 'README.txt' : null;

            if ( ! $file ) {
                $readme = '<p>' . __( 'No valid changlog was found.', 'redux-framework' ) . '</p>';
            } else {
                $readme = wp_remote_retrieve_body( wp_remote_get( $file ) );
                $readme = nl2br( esc_html( $readme ) );

                $readme = explode( '== Changelog ==', $readme );
                $readme = end( $readme );

                $remove = explode( '== Attribution ==', $readme );
                $readme = str_replace( '== Attribution ==' . end( $remove ), '', $readme );

                $readme = preg_replace( '/`(.*?)`/', '<code>\\1</code>', $readme );
                $readme = preg_replace( '/[\040]\*\*(.*?)\*\*/', ' <strong>\\1</strong>', $readme );
                $readme = preg_replace( '/[\040]\*(.*?)\*/', ' <em>\\1</em>', $readme );
                $readme = preg_replace( '/= (.*?) =/', '<h4>\\1</h4>', $readme );
                $readme = preg_replace( '/\[(.*?)\]\((.*?)\)/', '<a href="\\2">\\1</a>', $readme );
            }

            return $readme;
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
                $contributor_list .= sprintf( '<a href="%s" title="%s">', esc_url( 'https://github.com/' . $contributor->login ), esc_html( sprintf( __( 'View %s', 'redux-framework' ), $contributor->login ) )
                );
                $contributor_list .= sprintf( '<img src="%s" width="64" height="64" class="gravatar" alt="%s" />', esc_url( $contributor->avatar_url ), esc_html( $contributor->login ) );
                $contributor_list .= '</a>';
                $contributor_list .= sprintf( '<a class="web" href="%s">%s</a>', esc_url( 'https://github.com/' . $contributor->login ), esc_html( $contributor->login ) );
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
            logconsole( 'welcome.php' );
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
