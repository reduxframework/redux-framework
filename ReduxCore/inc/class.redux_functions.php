<?php

/**
 * Redux Framework Private Functions Container Class
 *
 * @package     Redux_Framework
 * @subpackage  Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'Redux_Functions' ) ) {

    /**
     * Redux Functions Class
     * Class of useful functions that can/should be shared among all Redux files.
     *
     * @since       1.0.0
     */
    class Redux_Functions {

        static public $_parent;

        public static function isMin() {
            $min = '';

            if ( false == self::$_parent->args['dev_mode'] ) {
                $min = '.min';
            }

            return $min;
        }

        /**
         * Parse CSS from output/compiler array
         *
         * @since       3.2.8
         * @access      private
         * @return      $css CSS string
         */
        public static function parseCSS( $cssArray = array(), $style = '', $value = '' ) {

            // Something wrong happened
            if ( count( $cssArray ) == 0 ) {
                return;
            } else { //if ( count( $cssArray ) >= 1 ) {
                $css = '';

                foreach ( $cssArray as $element => $selector ) {

                    // The old way
                    if ( $element === 0 ) {
                        $css = self::theOldWay( $cssArray, $style );

                        return $css;
                    }

                    // New way continued
                    $cssStyle = $element . ':' . $value . ';';

                    $css .= $selector . '{' . $cssStyle . '}';
                }
            }

            return $css;
        }

        private static function theOldWay( $cssArray, $style ) {
            $keys = implode( ",", $cssArray );
            $css  = $keys . "{" . $style . '}';

            return $css;
        }

        /**
         * initWpFilesystem - Initialized the Wordpress filesystem, if it already isn't.
         *
         * @since       3.2.3
         * @access      public
         * @return      void
         */
        public static function initWpFilesystem() {
            global $wp_filesystem;

            // Initialize the Wordpress filesystem, no more using file_put_contents function
            if ( empty( $wp_filesystem ) ) {
                require_once( ABSPATH . '/wp-admin/includes/file.php' );
                WP_Filesystem();
            }
        }

        /**
         * verFromGit - Retrives latest Redux version from GIT
         *
         * @since       3.2.0
         * @access      private
         * @return      string $ver
         */
        private static function verFromGit() {
            // Get the raw framework.php from github
            $gitpage = wp_remote_get(
                'https://raw.github.com/ReduxFramework/redux-framework/master/ReduxCore/framework.php', array(
                    'headers'   => array(
                        'Accept-Encoding' => ''
                    ),
                    'sslverify' => true,
                    'timeout'   => 300
                ) );

            // Is the response code the corect one?
            if ( ! is_wp_error( $gitpage ) ) {
                if ( isset( $gitpage['body'] ) ) {
                    // Get the page text.
                    $body = $gitpage['body'];

                    // Find version line in framework.php
                    $needle = 'public static $_version =';
                    $pos    = strpos( $body, $needle );

                    // If it's there, continue.  We don't want errors if $pos = 0.
                    if ( $pos > 0 ) {

                        // Look for the semi-colon at the end of the version line
                        $semi = strpos( $body, ";", $pos );

                        // Error avoidance.  If the semi-colon is there, continue.
                        if ( $semi > 0 ) {

                            // Extract the version line
                            $text = substr( $body, $pos, ( $semi - $pos ) );

                            // Find the first quote around the veersion number.
                            $quote = strpos( $body, "'", $pos );

                            // Extract the version number
                            $ver = substr( $body, $quote, ( $semi - $quote ) );

                            // Strip off quotes.
                            $ver = str_replace( "'", '', $ver );

                            return $ver;
                        }
                    }
                }
            }
        }

        /**
         * updateCheck - Checks for updates to Redux Framework
         *
         * @since       3.2.0
         * @access      public
         *
         * @param       string $curVer Current version of Redux Framework
         *
         * @return      void - Admin notice is diaplyed if new version is found
         */
        public static function updateCheck( $curVer ) {

            // If no cookie, check for new ver
            if ( ! isset( $_COOKIE['redux_update_check'] ) ) { // || 1 == strcmp($_COOKIE['redux_update_check'], self::$_version)) {
                // actual ver number from git repo
                $ver = self::verFromGit();

                // hour long cookie.
                setcookie( "redux_update_check", $ver, time() + 3600, '/' );
            } else {

                // saved value from cookie.  If it's different from current ver
                // we can still show the update notice.
                $ver = $_COOKIE['redux_update_check'];
            }

            // Set up admin notice on new version
            //if ( 1 == strcmp( $ver, $curVer ) ) {
            if ( version_compare( $ver, $curVer, '>' ) ) {
                self::$_parent->admin_notices[] = array(
                    'type'    => 'updated',
                    'msg'     => '<strong>A new build of Redux is now available!</strong><br/><br/>Your version:  <strong>' . $curVer . '</strong><br/>New version:  <strong><span style="color: red;">' . $ver . '</span></strong><br/><br/><em>If you are not a developer, your theme/plugin author shipped with <code>dev_mode</code> on. Contact them to fix it, but in the meantime you can use our <a href="' . 'https://' . 'wordpress.org/plugins/redux-developer-mode-disabler/" target="_blank">dev_mode disabler</a>.</em><br /><br /><a href="' . 'https://' . 'github.com/ReduxFramework/redux-framework">Get it now</a>&nbsp;&nbsp;|',
                    'id'      => 'dev_notice_' . $ver,
                    'dismiss' => true,
                );
            }
        }

        /**
         * adminNotices - Evaluates user dismiss option for displaying admin notices
         *
         * @since       3.2.0
         * @access      public
         * @return      void
         */
        public static function adminNotices() {
            global $current_user, $pagenow;

            // Check for an active admin notice array
            if ( ! empty( self::$_parent->admin_notices ) ) {

                // Enum admin notices
                foreach ( self::$_parent->admin_notices as $notice ) {
                    if ( true == $notice['dismiss'] ) {

                        // Get user ID
                        $userid = $current_user->ID;

                        if ( ! get_user_meta( $userid, 'ignore_' . $notice['id'] ) ) {

                            // Check if we are on admin.php.  If we are, we have
                            // to get the current page slug and tab, so we can
                            // feed it back to Wordpress.  Why>  admin.php cannot
                            // be accessed without the page parameter.  We add the
                            // tab to return the user to the last panel they were
                            // on.
                            $pageName = '';
                            $curTab   = '';
                            if ( $pagenow == 'admin.php' || $pagenow == 'themes.php' ) {

                                // Get the current page.  To avoid errors, we'll set
                                // the redux page slug if the GET is empty.
                                $pageName = empty( $_GET['page'] ) ? '&amp;page=' . self::$_parent->args['page_slug'] : '&amp;page=' . $_GET['page'];

                                // Ditto for the current tab.
                                $curTab = empty( $_GET['tab'] ) ? '&amp;tab=0' : '&amp;tab=' . $_GET['tab'];
                            }

                            // Print the notice with the dismiss link
                            echo '<div class="' . $notice['type'] . '"><p>' . $notice['msg'] . '&nbsp;&nbsp;<a href="?dismiss=true&amp;id=' . $notice['id'] . $pageName . $curTab . '">' . __( 'Dismiss', 'redux-framework' ) . '</a>.</p></div>';
                        }
                    } else {

                        // Standard notice
                        echo '<div class="' . $notice['type'] . '"><p>' . $notice['msg'] . '</a>.</p></div>';
                    }
                }

                // Clear the admin notice array
                self::$_parent->admin_notices = array();
            }
        }

        /**
         * dismissAdminNotice - Updates user meta to store dismiss notice preference
         *
         * @since       3.2.0
         * @access      public
         * @return      void
         */
        public static function dismissAdminNotice() {
            global $current_user;

            // Verify the dismiss and id parameters are present.
            if ( isset( $_GET['dismiss'] ) && isset( $_GET['id'] ) ) {
                if ( 'true' == $_GET['dismiss'] || 'false' == $_GET['dismiss'] ) {

                    // Get the user id
                    $userid = $current_user->ID;

                    // Get the notice id
                    $id  = $_GET['id'];
                    $val = $_GET['dismiss'];

                    // Add the dismiss request to the user meta.
                    update_user_meta( $userid, 'ignore_' . $id, $val );
                }
            }
        }
    }
}