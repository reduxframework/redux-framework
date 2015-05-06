<?php

/**
 * Redux Framework CDN Container Class
 *
 * @author      Kevin Provance (kprovance)
 * @package     Redux_Framework
 * @subpackage  Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Redux_CDN' ) ) {
    class Redux_CDN {
        static public $_parent;
        
        private static function is_enqueued($handle, $list = 'enqueued', $is_script){
            if ($is_script) {
                wp_script_is ( $handle, $list );
            } else {
                wp_style_is ( $handle, $list );
            }
        }

        private static function _register($handle, $src_cdn, $deps, $ver, $footer_or_media, $is_script = true){
            if ($is_script) {
                wp_register_script( $handle, $src_cdn, $deps, $ver, $footer_or_media );
            } else {
                wp_register_style( $handle, $src_cdn, $deps, $ver, $footer_or_media );
            }            
        }        
        
        private static function _enqueue($handle, $src_cdn, $deps, $ver, $footer_or_media, $is_script = true){
            if ($is_script) {
                wp_enqueue_script( $handle, $src_cdn, $deps, $ver, $footer_or_media );
            } else {
                wp_enqueue_style( $handle, $src_cdn, $deps, $ver, $footer_or_media );
            }            
        }

        private static function _cdn($register = true, $handle, $src_cdn, $deps, $ver, $footer_or_media, $is_script = true){
            $tran_key = '_style_cdn_is_up';
            if ($is_script) {
                $tran_key = '_script_cdn_is_up';
            }
            
            $cdn_is_up = get_transient( $handle . $tran_key );
            
            if ( $cdn_is_up ) {
                if ($register) {
                    self::_register($handle, $src_cdn, $deps, $ver, $footer_or_media, $is_script);
                } else {
                    self::_enqueue($handle, $src_cdn, $deps, $ver, $footer_or_media, $is_script);
                }
            } else {
                $cdn_response = wp_remote_get( $src_cdn );

                if ( is_wp_error( $cdn_response ) || wp_remote_retrieve_response_code( $cdn_response ) != '200' ) {
                    if (class_exists('Redux_VendorSupport')) {
                        $src = Redux_VendorURL::get_url($handle);
                        
                        if ($register) {
                            self::_register($handle, $src, $deps, $ver, $footer_or_media, $is_script);
                        } else {
                            self::_enqueue($handle, $src, $deps, $ver, $footer_or_media, $is_script);
                        }
                    } else {
                        if ( ! self::is_enqueued($handle, 'enqueued', $is_script)) {
                            $msg = 'Please wait a few minutes, then try refreshing the page.';
                            if (self::$_parent->args['dev_mode']) {
                                $msg = 'If developing offline, please download and install the <a href="http://reduxframework.com/wp-content/uploads/2015/05/redux-vendor-support.zip">Redux Vendor Support plugin</a> to bypass the vendor CDN and avoid this warning.';
                            }
                            
                            self::$_parent->admin_notices[] = array(
                                 'type'    => 'error',
                                 'msg'     => '<strong>Redux Framework Warning</strong><br/>' . $handle . ' CDN unavailable.  Some controls may not render properly.  ' . $msg,
                                 'id'      => $handle . $tran_key,
                                 'dismiss' => false,
                             );
                        }                        
                    }
                } else {
                    $cdn_is_up = set_transient( $handle . $tran_key, true, MINUTE_IN_SECONDS * self::$_parent->args['cdn_check_time'] );
                    
                    if ($register) {
                        self::_register($handle, $src_cdn, $deps, $ver, $footer_or_media, $is_script);
                    } else {
                        self::_enqueue($handle, $src_cdn, $deps, $ver, $footer_or_media, $is_script);
                    }
                }
            }
        }
        
        public static function register_style($handle, $src_cdn = false, $deps = array(), $ver = false, $media = 'all') {
            self::_cdn(true, $handle, $src_cdn, $deps, $ver, $media, $is_script = false);
        }
        
        public static function register_script($handle, $src_cdn = false, $deps = array(), $ver = false, $in_footer = false) {
            self::_cdn(true, $handle, $src_cdn, $deps, $ver, $in_footer, $is_script = true);
        }
        
        public static function enqueue_style( $handle, $src_cdn = false, $deps = array(), $ver = false, $media = 'all' ) {
            self::_cdn(false, $handle, $src_cdn, $deps, $ver, $media, $is_script = false);
        }
        
        public static function enqueue_script( $handle, $src_cdn = false, $deps = array(), $ver = false, $in_footer = false ) {
            self::_cdn(false, $handle, $src_cdn, $deps, $ver, $in_footer, $is_script = true);
        }
    }
}