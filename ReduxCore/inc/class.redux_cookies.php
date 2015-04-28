<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if (!class_exists('reduxCookie')) {

        /**
         * Cookie class wrapper.
         * @author daithi coombes
         */
        class reduxCookie {

            /**
             * Set a cookie.
             * Do nothing if unit testing.
             * @param string $name The cookie name.
             * @param string $value The cookie value.
             * @param integer $expire Expiry time.
             * @param string $path The cookie path.
             * @param string $domain The cookie domain.
             * @param boolean $secure HTTPS only.
             * @param boolean $httponly Only set cookie on HTTP calls.
             */
            public static function setCookie( $name, $value, $expire = 0, $path, $domain=null, $secure = false, $httponly = false ){

                if ( ! defined( 'WP_TESTS_DOMAIN' ) )
                    setcookie( $name, $value, $expire, $path, $domain, $secure, $httponly );
            }
        }
    }