<?php

    /**
     * Redux Framework Instance Container Class
     * Automatically captures and stores all instances
     * of ReduxFramework at instantiation.
     *
     * @package     Redux_Framework
     * @subpackage  Core
     */
    class ReduxFrameworkInstances {

        /**
         * ReduxFrameworkInstances
         *
         * @var object
         */
        private static $instance;

        /**
         * ReduxFramework instances
         *
         * @var array
         */
        private static $instances;

        /**
         * Get Instance
         * Get ReduxFrameworkInstances instance
         * OR an instance of ReduxFramework by [opt_name]
         *
         * @param  string $opt_name the defined opt_name
         *
         * @return object                class instance
         */
        public static function get_instance( $opt_name = false ) {

            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }

            if ( $opt_name && ! empty( self::$instances[ $opt_name ] ) ) {
                return self::$instances[ $opt_name ];
            }

            return self::$instance;
        }

        /**
         * Get all instantiated ReduxFramework instances (so far)
         *
         * @return [type] [description]
         */
        public static function get_all_instances() {
            return self::$instances;
        }

        private function __construct() {
            add_action( 'redux/construct', array( $this, 'capture' ), 5, 1 );
        }

        function capture( $ReduxFramework ) {
            $this->store( $ReduxFramework );
        }

        private function store( $ReduxFramework ) {
            if ( $ReduxFramework instanceof ReduxFramework ) {
                $key                     = $ReduxFramework->args['opt_name'];
                self::$instances[ $key ] = $ReduxFramework;
            }
        }
    }
