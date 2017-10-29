<?php

    /**
     * Redux Framework Admin Notice Class
     * Makes instantiating a Redux object an absolute piece of cake.
     *
     * @package     Redux_Framework
     * @author      Kevin Provance
     * @author      Dovy Paukstys
     * @subpackage  Core
     */

    // Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Don't duplicate me!
    if ( ! class_exists( 'Redux_Admin_Notices' ) ) {

        /**
         * Redux API Class
         * Simple API for Redux Framework
         *
         * @since       1.0.0
         */
        class Redux_Admin_Notices {

            static public $_parent;

            public static function load() {
                add_action( 'wp_ajax_redux_hide_admin_notice', array(
                    'Redux_Admin_Notices',
                    'dismissAdminNoticeAJAX'
                ) );
            }

            public static function set_notice($data) {
                extract($data);
                
                $parent->admin_notices[$parent->args['page_slug']][] = array(
                    'type'      => $type,
                    'msg'       => $msg,
                    'id'        => $id . '_' . $parent->args['opt_name'],
                    'dismiss'   => $dismiss,
                    'color'     => isset($color) ? $color : '#00A2E3'
                );
            }
            
            /**
             * adminNotices - Evaluates user dismiss option for displaying admin notices
             *
             * @since       3.2.0
             * @access      public
             * @return      void
             */
            public static function adminNotices($parent, $notices = array() ) {
                global $current_user, $pagenow, $wp_version;;
                
                // Check for an active admin notice array
                if ( ! empty( $notices ) ) {
                    
                    if (isset($_GET) && isset($_GET['page']) && $_GET['page'] == $parent->args['page_slug']) {
                        
                    // Enum admin notices
                    foreach ( $notices[$parent->args['page_slug']] as $notice ) {

                        $add_style = '';
                        if ( strpos( $notice['type'], 'redux-message' ) != false ) {
                            $add_style = 'style="border-left: 4px solid ' . esc_attr( $notice['color'] ) . '!important;"';
                        }

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
                                    $pageName = empty( $_GET['page'] ) ? '&amp;page=' . $parent->args['page_slug'] : '&amp;page=' . esc_attr( $_GET['page'] );

                                    // Ditto for the current tab.
                                    $curTab = empty( $_GET['tab'] ) ? '&amp;tab=0' : '&amp;tab=' . esc_attr( $_GET['tab'] );
                                }

                                // Print the notice with the dismiss link
                                if ( version_compare( $wp_version, '4.2', '>' ) ) {
                                    $output    = "";
                                    $css_id    = esc_attr( $notice['id'] ) . $pageName . $curTab;
                                    $css_class = esc_attr( $notice['type'] ) . ' redux-notice notice is-dismissible redux-notice';
                                    $output .= "<div {$add_style} id='$css_id' class='$css_class'> \n";
                                    $nonce = wp_create_nonce( $notice['id'] . $userid . 'nonce' );
                                    $output .= "<input type='hidden' class='dismiss_data' id='" . esc_attr( $notice['id'] ) . $pageName . $curTab . "' value='{$nonce}'> \n";
                                    $output .= '<p>' . wp_kses_post( $notice['msg'] ) . '</p>';
                                    $output .= "</div> \n";
                                    echo $output;
                                } else {
                                    echo '<div ' . $add_style . ' class="' . esc_attr( $notice['type'] ) . ' notice is-dismissable"><p>' . wp_kses_post( $notice['msg'] ) . '&nbsp;&nbsp;<a href="?dismiss=true&amp;id=' . esc_attr( $notice['id'] ) . $pageName . $curTab . '">' . esc_html__( 'Dismiss', 'redux-framework' ) . '</a>.</p></div>';
                                }
                            }
                        } else {
                            // Standard notice
                            echo '<div ' . $add_style . ' class="' . esc_attr( $notice['type'] ) . ' notice"><p>' . wp_kses_post( $notice['msg'] ) . '</a>.</p></div>';
                        }
?>
                        <script>
                            jQuery( document ).ready(
                                function( $ ) {
                                    $( 'body' ).on(
                                        'click', '.redux-notice.is-dismissible .notice-dismiss', function( event ) {
                                            var $data = $( this ).parent().find( '.dismiss_data' );
                                            $.post(
                                                ajaxurl, {
                                                    action: 'redux_hide_admin_notice',
                                                    id: $data.attr( 'id' ),
                                                    nonce: $data.val()
                                                }
                                            );
                                        }
                                    );
                                }
                            );
                        </script>
<?php
                        }
                    }

                    // Clear the admin notice array
                    $parent->admin_notices[$parent->args['opt_name']] = array();
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
                        $id  = esc_attr( $_GET['id'] );
                        $val = esc_attr( $_GET['dismiss'] );

                        // Add the dismiss request to the user meta.
                        update_user_meta( $userid, 'ignore_' . $id, $val );
                    }
                }
            }

            /**
             * dismissAdminNotice - Updates user meta to store dismiss notice preference
             *
             * @since       3.2.0
             * @access      public
             * @return      void
             */
            public static function dismissAdminNoticeAJAX() {
                global $current_user;

                // Get the notice id
                $id = explode( '&', $_POST['id'] );
                $id = $id[0];
                // Get the user id
                $userid = $current_user->ID;

                if ( ! wp_verify_nonce( $_POST['nonce'], $id . $userid . 'nonce' ) ) {
                    die( 0 );
                } else {
                    // Add the dismiss request to the user meta.
                    update_user_meta( $userid, 'ignore_' . $id, true );
                }
            }
        }

        Redux_Admin_Notices::load();
    }
