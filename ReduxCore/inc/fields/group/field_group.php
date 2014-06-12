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
     * @subpackage  Field_Info
     * @author      Daniel J Griffiths (Ghost1227)
     * @author      Dovy Paukstys
     * @author      Abdullah Almesbahi
     * @author      Jes�s Mendoza (@vertigo7x)
     * @author      Taha Paksu
     * @version     3.0.0
     */
// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// Don't duplicate me!
    if ( ! class_exists( 'ReduxFramework_group' ) ) {

        /**
         * Main ReduxFramework_info class
         *
         * @since       1.0.0
         */
        class ReduxFramework_group {

            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            function __construct( $field = array(), $value = '', $parent ) {

                //parent::__construct( $parent->sections, $parent->args );
                $this->parent         = $parent;
                $this->field          = $field;
                $this->value          = $value;
                $this->localized_data = array();

            }

            /**
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function render() {
                if ( isset( $this->value ) && isset( $this->value["group-field-count"] ) ) {
                    $saved_group_count = $this->value["group-field-count"];
                } else {
                    $this->value["group-field-count"] = $saved_group_count = 0;
                }
                $subtitle = ( isset( $this->field["subtitle"] ) ) ? $this->field["subtitle"] : "";
                echo "</fieldset></td></tr></tbody></table>";
                echo "<table class='form-table'><tbody><tr valign='top'><th scope='row'><div class='redux_field_th'>" . $this->field["title"];
                echo "<span class='description'>" . $subtitle . "</span></div></th><td>";
                echo "<fieldset id='" . $this->parent->args['opt_name'] . "-" . $this->_check_empty_field( 'id' ) . "-fieldset' class='redux-field-container redux-field-init redux-field redux-container-group' data-id='" . $this->field['id'] . "' data-type='group'>";
                echo "<div id='" . $this->parent->args['opt_name'] . "-" . $this->_check_empty_field( 'id' ) . "-group' class='redux-groups-accordion'>";
                echo "<input type='hidden' class='redux-group-field-count' name='" . $this->parent->args['opt_name'] . "[" . $this->_check_empty_field( "id" ) . "][group-field-count]' value='" . $saved_group_count . "' />";
                echo "<div class='redux-groups-dummy-group' style='display:none'>" . $this->_build_group_field_renamer( __( 'New', 'redux-framework' ) . " " . $this->_check_empty_field( "groupname" ) ) . "<div>";
                $this->add_subfields( "dummy-field-id" );
                echo "<input type='button' class='button redux-groups-remove' value='" . __( "Remove", 'redux-framework' ) . " " . $this->_check_empty_field( "groupname" ) . "' /></div></div>";
                if ( $saved_group_count > 0 ) {
                    for ( $saved_group_index = 0; $saved_group_index < $saved_group_count; $saved_group_index ++ ) {
                        echo "<div class='redux-groups-accordion-group' data-group-index='" . $saved_group_index . "'>" . $this->_build_group_field_renamer( __( 'New', 'redux-framework' ) . " " . $this->_check_empty_field( "groupname" ), $saved_group_index ) . "<div>";
                        $this->add_subfields( $saved_group_index );
                        echo "<input type='button' class='button redux-groups-remove button-secondary' value='" . __( "Remove", 'redux-framework' ) . " " . $this->_check_empty_field( "groupname" ) . "' /></div></div>";
                    }
                } else {
                    //add first empty group
                    $saved_group_index = 0;
                    echo "<div class='redux-groups-accordion-group' data-group-index='" . $saved_group_index . "'>" . $this->_build_group_field_renamer( __( 'New', 'redux-framework' ) . " " . $this->_check_empty_field( "groupname" ) ) . "<div>";
                    $this->add_subfields( $saved_group_index, true );
                    echo "<input type='button' class='button redux-groups-remove button-secondary' value='" . __( "Remove", 'redux-framework' ) . " " . $this->_check_empty_field( "groupname" ) . "' /></div></div>";
                }
                echo "</div>";
                echo "<input type='button' class='button redux-groups-clear button-primary' value='" . __( "Clear", 'redux-framework' ) . " " . $this->_check_empty_field( "groupname" ) . "' />";
                echo "<input type='button' class='button redux-groups-add button-primary' value='" . __( "Add New", 'redux-framework' ) . " " . $this->_check_empty_field( "groupname" ) . "' />";
                echo "</fieldset></td></tr></tbody></table>";
                echo "<table class='form-table'><tbody><tr valign='top'><th scope='row'><div class='redux_field_th'>";
            }

            function add_subfields( $index, $use_default_values = false ) {
                $unique_id_for_group_items = uniqid();
                foreach ( $this->field["subfields"] as $subfield ) {

                    $subfield_temp_id = $subfield["id"];

                    $subfield["id"]   = $subfield["id"] . "-" . $index;
                    $subfield["name"] = $this->parent->args['opt_name'] . "[" . $this->_check_empty_field( "id" ) . "][" . $index . "][" . $subfield_temp_id . "]";

                    if ( ! isset( $subfield["class"] ) ) {
                        $subfield["class"] = "";
                    }

                    if ( isset( $subfield["required"] ) ) {
                        if ( ! isset( $subfield["required"]["reindexed"] ) ) {
                            $subfield["required"][0] .= "-" . $index;
                            $subfield["required"]["reindexed"] = true;
                        }
                    }
                    if ( isset( $subfield["presets"] ) && isset( $subfield["options"] ) ) {
                        foreach ( $subfield["options"] as $option_key => $options ) {
                            // check if the presets are defined as json string
                            if ( ! is_array( $subfield["options"][ $option_key ]["presets"] ) ) {
                                // if it starts with curly bracket
                                if ( substr( $subfield["options"][ $option_key ]["presets"], 0, 1 ) == "{" ) {
                                    // decode the json options
                                    $subfield["options"][ $option_key ]["presets"] = json_decode( $subfield["options"][ $option_key ]["presets"], true );
                                } else {
                                    // options are neither array nor json string
                                    break;
                                }
                            }
                            // loop through field names and change them
                            foreach ( $subfield["options"][ $option_key ]["presets"] as $preset_key => $preset_value ) {
                                unset( $subfield["options"][ $option_key ]["presets"][ $preset_key ] );
                                $subfield["options"][ $option_key ]["presets"][ $this->field["id"] ][ $index ][ $preset_key ] = $preset_value;
                            }
                        }
                    }

                    echo "<table class='form-table " . ( ( $subfield["type"] != "section" ) ? "redux-group-subfield" : "" ) . "'><tbody><tr valign='top'><th scope='row'>";
                    echo $this->parent->get_header_html( $subfield );
                    echo "</th><td>";
                    if ( $subfield["type"] != "callback" ) {
                        if ( ! is_numeric( $index ) ) {
                            $this->enqueue_dependencies( $subfield["type"] );
                            if ( isset( $this->parent->options_defaults[ $this->_check_empty_field( "id" ) ][ $subfield_temp_id ] ) ) {
                                $this->parent->_field_input( $subfield, $this->parent->options_defaults[ $this->_check_empty_field( "id" ) ][ $subfield_temp_id ] );
                            } else {
                                $this->parent->_field_input( $subfield );
                            }
                        } else {
                            if ( $use_default_values ) {
                                if ( isset( $this->parent->options_defaults[ $this->_check_empty_field( "id" ) ][ $subfield_temp_id ] ) ) {
                                    $this->parent->_field_input( $subfield, $this->parent->options_defaults[ $this->_check_empty_field( "id" ) ][ $subfield_temp_id ] );
                                    $this->localized_data[ $subfield_temp_id ] = $this->parent->options_defaults[ $this->_check_empty_field( "id" ) ][ $subfield_temp_id ];
                                } else {
                                    $this->parent->_field_input( $subfield );
                                }
                            } else {
                                if ( ! isset( $this->value[ $index ][ $subfield_temp_id ] ) ) {
                                    $this->value[ $index ][ $subfield_temp_id ] = "";
                                }
                                $this->parent->_field_input( $subfield, $this->value[ $index ][ $subfield_temp_id ] );
                                if ( $subfield['type'] != "section" ) {
                                    $this->localized_data[ $subfield_temp_id ] = $this->value[ $index ][ $subfield_temp_id ];
                                }

                            }
                        }
                    }
                    echo "</td></table>";
                }
            }


            /**
             * Enqueue Function.
             * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
             *
             * @since         1.0.0
             * @access        public
             * @return        void
             */
            public function enqueue() {
                wp_enqueue_script(
                    'redux-field-group-js',
                    ReduxFramework::$_url . 'inc/fields/group/field_group.js',
                    array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'wp-color-picker', 'redux-js' ),
                    time(),
                    true
                );

                wp_enqueue_style(
                    'redux-field-group-css',
                    ReduxFramework::$_url . 'inc/fields/group/field_group.css',
                    time(),
                    true
                );
            }

            public function enqueue_dependencies( $field_type ) {
                $field_class = 'ReduxFramework_' . $field_type;

                if ( ! class_exists( $field_class ) ) {
                    $class_file = apply_filters( 'redux-typeclass-load', ReduxFramework::$_dir . 'inc/fields/' . $field_type . '/field_' . $field_type . '.php', $field_class );

                    if ( $class_file ) {
                        /** @noinspection PhpIncludeInspection */
                        require_once( $class_file );
                    }
                }

                if ( class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) {
                    $enqueue = new $field_class( '', '', $this->parent );
                    $enqueue->enqueue();
                }
            }

            private function _check_empty_field( $field_property_name ) {
                if ( isset( $this->field[ $field_property_name ] ) ) {
                    return $this->field[ $field_property_name ];
                }

                return "";
            }

            private function _build_group_field_renamer( $name_value, $index = "dummy-field-id" ) {
                $field_name = $this->parent->args['opt_name'] . "[" . $this->_check_empty_field( "id" ) . "][" . $index . "][group_field_name]";
                if ( isset( $this->value[ $index ]["group_field_name"] ) ) {
                    $name_value = $this->value[ $index ]["group_field_name"];
                }
                $return = "<h3>";
                $return .= "<span class='group_field_name_span'>" . $name_value . "</span>";
                $return .= "&nbsp;<span class='group_field_edit_icon el-icon-pencil'></span>";
                $return .= "<input type='text' style='display:none' class='group_field_name_input' name='" . $field_name . "' value='" . $name_value . "'></input>";
                $return .= "</h3>";

                return $return;
            }
        }
    }
