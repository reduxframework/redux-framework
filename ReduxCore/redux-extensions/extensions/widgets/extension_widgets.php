<?php

    /**
     * Redux Framework is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 2 of the License, or
     * any later version.
     * Redux Framework is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     * GNU General Public License for more details.
     * You should have received a copy of the GNU General Public License
     * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
     *
     * @package     ReduxFramework
     * @author      Dovy Paukstys (dovy)
     * @version     1.1.8
     */

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// Don't duplicate me!
    if ( ! class_exists( 'ReduxFramework_extension_widgets' ) ) {

        /**
         * Main ReduxFramework widgets extension class
         *
         * @since       1.0.0
         */
        class ReduxFramework_extension_widgets {

            static $version = "1.0.0";

            public $widgets = array();
            private $parent;
            public $localize_data = array();
            public $toReplace = array();
            public $_extension_url;
            public $_extension_dir;
            public $base_url;
            public $fields = array();

            public function __construct( $parent ) {
                global $pagenow;

                $this->parent = $parent;

                $this->parent->extensions['widgets'] = $this;


                if ( empty( self::$_extension_dir ) ) {
                    $this->_extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                    $this->_extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->_extension_dir ) );
                }

                // Must not update the DB when just updating metaboxes. Sheesh.
                if ( is_admin() && ( $pagenow == "widgets.php" ) ) {
                    $this->parent->never_save_to_db = true;
                }

                add_action( 'admin_enqueue_scripts', array( $this, '_enqueue' ), 20 );

                $this->init();

            } // __construct()

            public function init() {

                global $wp_filesystem;
                // Ensure it exists
                if ( ! is_dir( ReduxFramework::$_upload_dir . 'widgets' ) ) {
                    // Create the directory
                    $wp_filesystem->mkdir( ReduxFramework::$_upload_dir . 'widgets' );
                }

                $this->widgets = apply_filters( 'redux/widgets/' . $this->parent->args['opt_name'] . '/widgets', $this->widgets );

                if ( empty( $this->widgets ) ) {
                    return; // Don't do it! There's nothing here.
                }

                $stored_widgets = get_option( 'redux-' . $this->parent->args['opt_name'] . '-widgets' );

                $this->base_url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

                $update = false;
                if ( count( $this->widgets ) !== count( $stored_widgets ) ) {
                    echo "Nuke all classes";
                    $update = true;

                }

                $theWidgets = array();

                if ( ! empty( $this->widgets ) ) {
                    foreach ( $this->widgets as $k => $widget ) {
                        if ( ! isset( $widget['id'] ) || empty( $widget['id'] ) ) {
                            continue;
                        }
                        if ( ! isset( $widget['title'] ) || empty( $widget['title'] ) ) {
                            continue;
                        }
                        $theWidgets[ $widget['id'] ] = $widget;
                        $update                      = false;
                        if ( ! $update ) {
                            $widgetCheck = ( json_encode( $widget, true ) != json_encode( $stored_widgets[ $widget['id'] ], true ) ) ? true : false;
                        }

                        if ( ! isset( $stored_widgets[ $widget['id'] ] ) || json_encode( $widget, true ) != $stored_widgets[ $widget['id'] ] || ! file_exists( ReduxFramework::$_upload_dir . 'widgets/class.' . $this->parent->args['opt_name'] . '.' . $widget['id'] . '.php' ) ) {

                            $class     = file_get_contents( dirname( __FILE__ ) . '/template.class.php' );
                            $className = sanitize_html_class( 'Redux_' . $this->parent->args['opt_name'] . '_' . $widget['id'] . '_Widget' );
                            if ( class_exists( $className ) ) {
                                continue;
                            }
                            $class     = str_replace( '{{CLASS}}', $className, $class );
                            $class     = str_replace( '{{ID}}', ( sanitize_html_class( $widget['id'] ) ), $class );
                            $class     = str_replace( '{{OPT_NAME}}', $this->parent->args['opt_name'], $class );
                            $subtitle  = isset( $widget['subtitle'] ) ? '<p>' . $widget['subtitle'] . '</p>' : '';
                            $class     = str_replace( '{{SUBTITLE}}', $subtitle, $class );
                            if ( isset( $widget['fields'] ) && ! empty( $widget['fields'] ) ) {
                                foreach ( $widget['fields'] as $field ) {
                                    $fieldName  = 'ReduxFramework_' . $field['type'];
                                    $class_file = ReduxFramework::$_dir . "inc/fields/{$field['type']}/field_{$field['type']}.php";

                                    if ( $class_file && file_exists( $class_file ) && ! class_exists( $fieldName ) ) {
                                        /** @noinspection PhpIncludeInspection */
                                        require_once( $class_file );
                                    }

                                    if ( ! isset( $field['name_suffix'] ) ) {
                                        $field['name_suffix'] = '';
                                    }
                                    if ( ! isset( $field['class'] ) ) {
                                        $field['class'] = '';
                                    }

                                    $fieldObject    = new $fieldName( $field, '', $this->parent );
                                    $this->fields[] = $fieldObject;
                                    $GLOBALS['redux-widget-'.$this->parent->args['opt_name'].'-'.$widget['id']][] = $fieldObject;


                                }
                            }

                            $class     = str_replace( '{{TITLE}}', ( sanitize_html_class( $widget['title'] ) ), $class );
                            $class     = str_replace( '{{DESCRIPTION}}', ( sanitize_html_class( $widget['desc'] ) ), $class );
                            $class     = str_replace( '{{DESCRIPTION}}', ( sanitize_html_class( $widget['desc'] ) ), $class );


                            if ( $this->parent->args['dev_mode'] == true ) {
                                eval( $class );
                            } else {
                                if ( $widgetCheck || ! file_exists( ReduxFramework::$_upload_dir . 'widgets/class.' . $this->parent->args['opt_name'] . '.' . $widget['id'] . '.php' ) ) {
                                    $update = true;
                                    file_put_contents( ReduxFramework::$_upload_dir . 'widgets/class.' . $this->parent->args['opt_name'] . '.' . $widget['id'] . '.php', '<?php ' . $class );
                                }
                                require_once( ReduxFramework::$_upload_dir . 'widgets/class.' . $this->parent->args['opt_name'] . '.' . $widget['id'] . '.php' );
                            }
                        }
                    }
                }

                if ( $update ) {
                    update_option( 'redux-' . $this->parent->args['opt_name'] . '-widgets', $theWidgets );
                }

            }

            public function _enqueue() {
                global $pagenow;

                // Should only run on the widgets page
                if ( $pagenow != "widgets.php" ) {
                    return;
                }

                wp_enqueue_script( 'jquery-ui-core' );
                wp_enqueue_script( 'jquery-ui-dialog' );

                wp_enqueue_style(
                    'jquery-ui-css',
                    ReduxFramework::$_url . 'assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css',
                    '',
                    filemtime( ReduxFramework::$_dir . 'assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css' ), // todo - version should be based on above post-filter src
                    'all'
                );
                wp_enqueue_style(
                    'redux-css',
                    ReduxFramework::$_url . 'assets/css/redux.css',
                    array( 'farbtastic' ),
                    filemtime( ReduxFramework::$_dir . 'assets/css/redux.css' ),
                    'all'
                );

                foreach ( $this->fields as $field ) {
                    if ( method_exists( $field, 'enqueue' ) ) {
                        $field->enqueue();
                    }

                }

            } // _enqueue()

        } // class ReduxFramework_extension_widgets

    } // if ( !class_exists( 'ReduxFramework_extension_widgets' ) )

