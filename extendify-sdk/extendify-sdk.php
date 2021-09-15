<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ExtendifySdk')) :

    /**
     * The Extendify Sdk
     */
    // phpcs:ignore Squiz.Classes.ClassFileName.NoMatch,Squiz.Commenting.ClassComment.Missing,PEAR.Commenting.ClassComment.Missing
    final class ExtendifySdk
    {

        /**
         * Var to make sure we only load once
         *
         * @var boolean $loaded
         */
        public static $loaded = false;

        /**
         * Set up the SDK
         *
         * @return void
         */
        public function __invoke()
        {
            if (!apply_filters('extendifysdk_load_library', true)) {
                return;
            }

            if (version_compare(PHP_VERSION, '5.6', '<') || version_compare($GLOBALS['wp_version'], '5.5', '<')) {
                return;
            }

            if (!self::$loaded) {
                self::$loaded = true;
                require dirname(__FILE__) . '/bootstrap.php';
                $app = new Extendify\ExtendifySdk\App();
                if (!defined('EXTENDIFYSDK_BASE_URL')) {
                    define('EXTENDIFYSDK_BASE_URL', plugin_dir_url(__FILE__));
                }
            }
        }
        // phpcs:ignore Squiz.Classes.ClassDeclaration.SpaceBeforeCloseBrace
    }

    $extendifySdk = new ExtendifySdk();
    $extendifySdk();
endif;
