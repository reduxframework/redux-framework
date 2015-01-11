<?php

if ( !defined ( 'ABSPATH' ) ) {
    exit;
}

if (!class_exists('reduxCoreEnqueue')){
    class reduxCoreEnqueue {
        public $parent      = null;

        private $min        = '';
        private $timestamp  = '';

        public function __construct ($parent) {
            $this->parent = $parent;

            Redux_Functions::$_parent = $parent;
            $this->min                = Redux_Functions::isMin();

            $this->timestamp = ReduxFramework::$_version;
            if ($parent->args['dev_mode']) {
                $this->timestamp .= '.' . time();
            }

            $this->register_styles();
            $this->register_scripts();
            
            add_thickbox();
            
            $this->enqueue_fields();
            
            $this->set_localized_data();
            
            /**
             * action 'redux-enqueue-{opt_name}'
             *
             * @deprecated
             *
             * @param  object $this ReduxFramework
             */
            do_action( "redux-enqueue-{$parent->args['opt_name']}", $parent ); // REMOVE

            /**
             * action 'redux/page/{opt_name}/enqueue'
             */
            do_action( "redux/page/{$parent->args['opt_name']}/enqueue" );
            
        }

        private function register_styles(){

            //*****************************************************************
            // Redux Admin CSS
            //*****************************************************************
            wp_enqueue_style(
                'redux-admin-css',
                ReduxFramework::$_url . 'assets/css/redux-admin.css',
                array(),
                $this->timestamp,
                'all'
            );

            //*****************************************************************
            // Redux Fields CSS
            //*****************************************************************
            if (!$this->parent->args['dev_mode']) {
                wp_enqueue_style(
                    'redux-fields-css',
                    ReduxFramework::$_url . 'assets/css/redux-fields.css',
                    array(),
                    $this->timestamp,
                    'all'
                );
            }

            //*****************************************************************
            // Select2 CSS
            //*****************************************************************
            wp_register_style(
                'select2-css',
                ReduxFramework::$_url . 'assets/js/vendor/select2/select2.css',
                array(),
                $this->timestamp,
                'all'
            );

            //*****************************************************************
            // Spectrum CSS
            //*****************************************************************
            wp_register_style(
                'redux-spectrum-css',
                ReduxFramework::$_url . 'assets/css/vendor/spectrum/redux-spectrum.css',
                array(),
                $this->timestamp,
                'all'
            );

            //*****************************************************************
            // Elusive Icon CSS
            //*****************************************************************
            wp_enqueue_style(
                'redux-elusive-icon',
                ReduxFramework::$_url . 'assets/css/vendor/elusive-icons/elusive-webfont.css',
                array(),
                $this->timestamp,
                'all'
            );

            //*****************************************************************
            // QTip CSS
            //*****************************************************************
            wp_enqueue_style(
                'qtip-css',
                ReduxFramework::$_url . 'assets/css/vendor/qtip/jquery.qtip.css',
                array(),
                $this->timestamp,
                'all'
            );

            //*****************************************************************
            // JQuery UI CSS
            //*****************************************************************
            wp_enqueue_style(
                'jquery-ui-css',
                apply_filters( "redux/page/{$this->parent->args['opt_name']}/enqueue/jquery-ui-css", ReduxFramework::$_url . 'assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css' ),
                array(),
                $this->timestamp,
                'all'
            );

            //*****************************************************************
            // Iris CSS
            //*****************************************************************
            wp_enqueue_style( 'wp-color-picker' );

            if ($this->parent->args['dev_mode']) {
                
                //*****************************************************************
                // Color Picker CSS
                //*****************************************************************
                wp_register_style(
                    'redux-color-picker-css',
                    ReduxFramework::$_url . 'assets/css/color-picker/color-picker.css',
                    array('wp-color-picker'),
                    $this->timestamp,
                    'all'
                );

                //*****************************************************************
                // Media CSS
                //*****************************************************************                
                wp_enqueue_style(
                    'redux-field-media-css',
                    ReduxFramework::$_url . 'assets/css/media/media.css',
                    array(),
                    time(),
                    'all'
                );
            }

            //*****************************************************************
            // RTL CSS
            //*****************************************************************
            if ( is_rtl() ) {
                wp_enqueue_style(
                    'redux-rtl-css',
                    ReduxFramework::$_url  . 'assets/css/rtl.css',
                    array('redux-admin-css'),
                    $this->timestamp,
                    'all'
                );
            }

        }

        private function register_scripts() {
            //*****************************************************************
            // JQuery / JQuery UI JS
            //*****************************************************************
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-dialog' );

            //*****************************************************************
            // Select2 Sortable JS
            //*****************************************************************
            wp_register_script(
                'redux-select2-sortable-js',
                ReduxFramework::$_url . 'assets/js/vendor/redux.select2.sortable' . $this->min . '.js',
                array( 'jquery' ),
                $this->timestamp,
                true
            );

            //*****************************************************************
            // Select2 JS
            //*****************************************************************
            wp_register_script(
                'select2-js',
                ReduxFramework::$_url . 'assets/js/vendor/select2/select2.js',
                array( 'jquery', 'redux-select2-sortable-js' ),
                $this->timestamp,
                true
            );
            
            $depArray = array( 'jquery');

            //*****************************************************************
            // Vendor JS
            //*****************************************************************
            if ($this->parent->args['dev_mode']) {
                wp_register_script(
                    'redux-vendor',
                    ReduxFramework::$_url . 'assets/js/vendor.min.js',
                    array( 'jquery' ),
                    $this->timestamp,
                    true
                );                
                
                array_push( $depArray, 'redux-vendor' );
            }

            //*****************************************************************
            // Redux JS
            //*****************************************************************
            wp_register_script(
                'redux-js',
                ReduxFramework::$_url . 'assets/js/redux' . $this->min . '.js',
                $depArray,
                $this->timestamp,
                true
            );
            
            wp_enqueue_script(
                'webfontloader',
                'https://ajax.googleapis.com/ajax/libs/webfont/1.5.0/webfont.js',
                array( 'jquery' ),
                '1.5.0',
                true
            );            
        }
        
        private function enqueue_fields(){
            foreach ( $this->parent->sections as $section ) {
                if ( isset( $section['fields'] ) ) {
                    foreach ( $section['fields'] as $field ) {
                        // TODO AFTER GROUP WORKS - Revert IF below
                        // if( isset( $field['type'] ) && $field['type'] != 'callback' ) {
                        if ( isset( $field['type'] ) && $field['type'] != 'callback' ) {

                            $field_class = 'ReduxFramework_' . $field['type'];

                            /**
                             * Field class file
                             * filter 'redux/{opt_name}/field/class/{field.type}
                             *
                             * @param       string        field class file path
                             * @param array $field        field config data
                             */
                            $class_file = apply_filters( "redux/{$this->parent->args['opt_name']}/field/class/{$field['type']}", ReduxFramework::$_dir . "inc/fields/{$field['type']}/field_{$field['type']}.php", $field );
                            if ( $class_file ) {
                                if ( ! class_exists( $field_class ) ) {
                                    if ( file_exists( $class_file ) ) {
                                        require_once( $class_file );
                                    }
                                }

                                if ( ( method_exists( $field_class, 'enqueue' ) ) || method_exists( $field_class, 'localize' ) ) {

                                    if ( ! isset( $this->parent->options[ $field['id'] ] ) ) {
                                        $this->parent->options[ $field['id'] ] = "";
                                    }
                                    $theField = new $field_class( $field, $this->parent->options[ $field['id'] ], $this->parent );

                                    // Move dev_mode check to a new if/then block
                                    if ( ! wp_script_is( 'redux-field-' . $field['type'] . '-js', 'enqueued' ) && class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) {
                                        $theField->enqueue();
                                    }

                                    if ( method_exists( $field_class, 'localize' ) ) {
                                        $params = $theField->localize( $field );
                                        if ( ! isset( $this->parent->localize_data[ $field['type'] ] ) ) {
                                            $this->parent->localize_data[ $field['type'] ] = array();
                                        }
                                        $this->parent->localize_data[ $field['type'] ][ $field['id'] ] = $theField->localize( $field );
                                    }

                                    unset( $theField );
                                }
                            }
                        }
                    }
                }
            }
        }
        
        private function set_localized_data(){
            $this->parent->localize_data['required']       = $this->parent->required;
            $this->parent->localize_data['fonts']          = $this->parent->fonts;
            $this->parent->localize_data['required_child'] = $this->parent->required_child;
            $this->parent->localize_data['fields']         = $this->parent->fields;

            if ( isset( $this->parent->font_groups['google'] ) ) {
                $this->parent->localize_data['googlefonts'] = $this->parent->font_groups['google'];
            }

            if ( isset( $this->parent->font_groups['std'] ) ) {
                $this->parent->localize_data['stdfonts'] = $this->parent->font_groups['std'];
            }

            if ( isset( $this->parent->font_groups['customfonts'] ) ) {
                $this->parent->localize_data['customfonts'] = $this->parent->font_groups['customfonts'];
            }

            $this->parent->localize_data['folds'] = $this->parent->folds;

            // Make sure the children are all hidden properly.
            foreach ( $this->parent->fields as $key => $value ) {
                if ( in_array( $key, $this->parent->fieldsHidden ) ) {
                    foreach ( $value as $k => $v ) {
                        if ( ! in_array( $k, $this->parent->fieldsHidden ) ) {
                            $this->parent->fieldsHidden[] = $k;
                            $this->parent->folds[ $k ]    = "hide";
                        }
                    }
                }
            }

            if ( isset( $this->parent->args['dev_mode'] ) && $this->parent->args['dev_mode'] == true ) {

                $base                        = admin_url( 'admin-ajax.php' ) . '?action=redux_p&url=';
                $url                         = $base . urlencode( 'http://ads.reduxframework.com/api/index.php?js&g&1&v=2' ) . '&proxy=' . urlencode( $base );
                $this->parent->localize_data['rAds'] = '<span data-id="1" class="mgv1_1"><script type="text/javascript">(function(){if (mysa_mgv1_1) return; var ma = document.createElement("script"); ma.type = "text/javascript"; ma.async = true; ma.src = "' . $url . '"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ma, s) })();var mysa_mgv1_1=true;</script></span>';
            }

            $this->parent->localize_data['fieldsHidden'] = $this->parent->fieldsHidden;
            $this->parent->localize_data['options']      = $this->parent->options;
            $this->parent->localize_data['defaults']     = $this->parent->options_defaults;

            /**
             * Save pending string
             * filter 'redux/{opt_name}/localize/save_pending
             *
             * @param       string        save_pending string
             */
            $save_pending = apply_filters( "redux/{$this->parent->args['opt_name']}/localize/save_pending", __( 'You have changes that are not saved. Would you like to save them now?', 'redux-framework' ) );

            /**
             * Reset all string
             * filter 'redux/{opt_name}/localize/reset
             *
             * @param       string        reset all string
             */
            $reset_all = apply_filters( "redux/{$this->parent->args['opt_name']}/localize/reset", __( 'Are you sure? Resetting will lose all custom values.', 'redux-framework' ) );

            /**
             * Reset section string
             * filter 'redux/{opt_name}/localize/reset_section
             *
             * @param       string        reset section string
             */
            $reset_section = apply_filters( "redux/{$this->parent->args['opt_name']}/localize/reset_section", __( 'Are you sure? Resetting will lose all custom values in this section.', 'redux-framework' ) );

            /**
             * Preset confirm string
             * filter 'redux/{opt_name}/localize/preset
             *
             * @param       string        preset confirm string
             */
            $preset_confirm = apply_filters( "redux/{$this->parent->args['opt_name']}/localize/preset", __( 'Your current options will be replaced with the values of this preset. Would you like to proceed?', 'redux-framework' ) );
            global $pagenow;
            $this->parent->localize_data['args'] = array(
                'save_pending'          => $save_pending,
                'reset_confirm'         => $reset_all,
                'reset_section_confirm' => $reset_section,
                'preset_confirm'        => $preset_confirm,
                'please_wait'           => __( 'Please Wait', 'redux-framework' ),
                'opt_name'              => $this->parent->args['opt_name'],
                'slug'                  => $this->parent->args['page_slug'],
                'hints'                 => $this->parent->args['hints'],
                'disable_save_warn'     => $this->parent->args['disable_save_warn'],
                'class'                 => $this->parent->args['class'],
                'menu_search'           => $pagenow . '?page=' . $this->parent->args['page_slug'] . "&tab="
            );

            // Construct the errors array.
            if ( isset( $this->parent->transients['last_save_mode'] ) && ! empty( $this->parent->transients['notices']['errors'] ) ) {
                $theTotal  = 0;
                $theErrors = array();

                foreach ( $this->parent->transients['notices']['errors'] as $error ) {
                    $theErrors[ $error['section_id'] ]['errors'][] = $error;

                    if ( ! isset( $theErrors[ $error['section_id'] ]['total'] ) ) {
                        $theErrors[ $error['section_id'] ]['total'] = 0;
                    }

                    $theErrors[ $error['section_id'] ]['total'] ++;
                    $theTotal ++;
                }

                $this->parent->localize_data['errors'] = array( 'total' => $theTotal, 'errors' => $theErrors );
                unset( $this->parent->transients['notices']['errors'] );
            }

            // Construct the warnings array.
            if ( isset( $this->parent->transients['last_save_mode'] ) && ! empty( $this->parent->transients['notices']['warnings'] ) ) {
                $theTotal    = 0;
                $theWarnings = array();

                foreach ( $this->parent->transients['notices']['warnings'] as $warning ) {
                    $theWarnings[ $warning['section_id'] ]['warnings'][] = $warning;

                    if ( ! isset( $theWarnings[ $warning['section_id'] ]['total'] ) ) {
                        $theWarnings[ $warning['section_id'] ]['total'] = 0;
                    }

                    $theWarnings[ $warning['section_id'] ]['total'] ++;
                    $theTotal ++;
                }

                unset( $this->parent->transients['notices']['warnings'] );
                $this->parent->localize_data['warnings'] = array( 'total' => $theTotal, 'warnings' => $theWarnings );
            }

            if ( empty( $this->parent->transients['notices'] ) ) {
                unset( $this->parent->transients['notices'] );
            }

            wp_localize_script(
                'redux-js',
                'redux',
                $this->parent->localize_data
            );

            wp_enqueue_script( 'redux-js' ); // Enque the JS now
            
        }
    }
}