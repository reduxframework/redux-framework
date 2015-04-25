<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if (!class_exists('reduxCookie')) {
        class reduxCookie {


            public static function setCookie( $name, $value, $expire = 0, $path, $domain=null, $secure = false, $httponly = false ){

                if ( ! defined( 'WP_TESTS_DOMAIN' ) )
                    setcookie( $name, $value, $expire, $path, $domain, $secure, $httponly );
            }
        }
    }