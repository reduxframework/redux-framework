<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Field_Info
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @author      Abdullah Almesbahi
 * @version     3.0.0
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// Don't duplicate me!
if (!class_exists('ReduxFramework_group')) {

    /**
     * Main ReduxFramework_info class
     *
     * @since       1.0.0
     */
    class ReduxFramework_group extends ReduxFramework {

        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function __construct($field = array(), $value = '', $parent) {

            parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);

            $this->field = $field;
            $this->value = $value;
            $this->parent = $parent;

        }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {
            if (!empty($this->field['desc'])) {
                $this->field['description'] = $this->field['desc'];
            }

            print_r($this->field);

            if (empty($this->value) || !is_array($this->value)) {
                $this->value = array(
                    array(
                        'slide_title' => __('New', 'redux-framework').' '.$this->field['groupname'],
                        'slide_sort' => '0',
                    )
                );
            }
            echo '</td></tr></table><table class="form-table no-border redux-group-table" style="margin-top: 0;"><tbody><tr><td>';
            echo '<fieldset id="'.$this->parent->args['opt_name'].'-'.$this->field['id'].'" class="redux-field redux-group redux-container-'.$this->field['type'].' '.$this->field['class'].'" data-id="'.$this->field['id'].'">';
            echo '<legend>'.$this->field['title'].'</legend>';
            if ( isset( $this->field['description'] ) ) {
                echo '<div class="description field-desc">' . $this->field['description'] . '</div>';
            }
            echo '<div id="redux-groups-accordion">';
            $x = 0;

            $groups = $this->value;
            foreach ($groups as $group) {        
                echo '<fieldset id="' . $this->field['id'] . '-group-' . $x . '" class="redux-groups-accordion-group">';
                echo '<h3 class="group-title">';
                    echo '<span class="redux-groups-header">' . $group['slide_title'] . '</span>';
                    echo '<input type="hidden" id="' . $this->field['id'] . '-slide_title_' . $x . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_title]" value="' . esc_attr($group['slide_title']) . '" class="slide-title" />';
                    echo '<input type="hidden" class="slide-sort" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $x . '][slide_sort]" id="' . $this->field['id'] . '-slide_sort_' . $x . '" value="' . $group['slide_sort'] . '" />';
                echo '</h3>';
                echo '<div>';//according content open
                
                echo '<table style="margin-top: 0;" class="redux-groups-accordion redux-group form-table no-border">';
                
                //echo '<h4>' . __('Group Title', 'redux-framework') . '</h4>';
                foreach ($this->field['subfields'] as $field) {

                    if ($field['type'] == 'group') {
                        echo "<h3>You cannot place a group field within a group.</h3>";
                        continue;
                    }
                    //we will enqueue all CSS/JS for sub fields if it wasn't enqueued
                    $this->enqueue_dependencies($field['type']);

                    // All this to get the TH value. Sheesh.
                    $th = "";
                    if( isset( $field['title'] ) && isset( $field['type'] ) && $field['type'] !== "info" ) {
                        $default_mark = ( !empty($field['default']) && isset($this->options[$field['id']]) && $this->options[$field['id']] == $field['default'] && !empty( $this->args['default_mark'] ) && isset( $field['default'] ) ) ? $this->args['default_mark'] : '';
                        if (!empty($field['title'])) {
                            $th = $field['title'] . $default_mark;
                        }
                        if( isset( $field['subtitle'] ) ) {
                            $th .= '<span class="description">' . $field['subtitle'] . '</span>';
                        }
                    } 
                    if (!isset($field['id'])) {
                        print_r($field);
                    }
                                        

                    if ( $this->args['default_show'] === true && isset( $field['default'] ) && isset($this->options[$field['id']]) && $this->options[$field['id']] != $field['default'] && $field['type'] !== "info" ) {
                        $default_output = "";
                        if (!is_array($field['default'])) {
                            if ( !empty( $field['options'][$field['default']] ) ) {
                                if (!empty($field['options'][$field['default']]['alt'])) {
                                    $default_output .= $field['options'][$field['default']]['alt'] . ', ';
                                } else {
                                    // TODO: This serialize fix may not be the best solution. Look into it. PHP 5.4 error without serialize
                                    $default_output .= serialize($field['options'][$field['default']]).", ";    
                                }
                            } else if ( !empty( $field['options'][$field['default']] ) ) {
                                $default_output .= $field['options'][$field['default']].", ";
                            } else if ( !empty( $field['default'] ) ) {
                                $default_output .= $field['default'] . ', ';
                            }
                        } else {
                            foreach( $field['default'] as $defaultk => $defaultv ) {
                                if (!empty($field['options'][$defaultv]['alt'])) {
                                    $default_output .= $field['options'][$defaultv]['alt'] . ', ';
                                } else if ( !empty( $field['options'][$defaultv] ) ) {
                                    $default_output .= $field['options'][$defaultv].", ";
                                } else if ( !empty( $field['options'][$defaultk] ) ) {
                                    $default_output .= $field['options'][$defaultk].", ";
                                } else if ( !empty( $defaultv ) ) {
                                    $default_output .= $defaultv.', ';
                                }
                            }
                        }
                        if ( !empty( $default_output ) ) {
                            $default_output = __( 'Default', 'redux-framework' ) . ": " . substr($default_output, 0, -2);
                        }                   
                        $th .= '<span class="showDefaults">'.$default_output.'</span>';
                    }                     
                    
                    echo '<tr><th>'.$th.'</th><td>';
                    if(isset($field['class']))
                        $field['class'] .= " group";
                    else
                        $field['class'] = " group";

                    $value = empty($this->parent->options[$field['id']][$x]) ? " " : $this->parent->options[$field['id']][$x];

                    ob_start();
                    $this->parent->_field_input($field, $value);
                    $content = ob_get_contents();

                    //adding sorting number to the name of each fields in group
                    $name = $this->parent->args['opt_name'] . '[' . $field['id'] . ']';
                    $content = str_replace($name, $name . '[' . $x . ']', $content);

                    //we should add $sort to id to fix problem with select field
                    $content = str_replace(' id="'.$field['id'].'-select"', ' id="'.$field['id'].'-select-'.$sort.'"', $content);
                    
                    $_field = apply_filters('redux-support-group',$content, $field, $x);
                    ob_end_clean();
                    echo $_field;
                    
                    echo '</td></tr>';
                }
                echo '<tr class="no-border"><th></th><td>';
                    echo '<a href="javascript:void(0);" rel="' . $this->field['id'] . '-group-' . $x . '" class="button deletion redux-groups-remove">' . __('Delete', 'redux-framework').' '.$this->field['groupname']. '</a>';
                echo '</td></tr>';
                echo '</table>';
                
                echo '</div></fieldset>';
                $x++;
            }

            echo '</div><a href="javascript:void(0);" class="button redux-groups-add button-primary" data-parent="'.$this->parent->args['opt_name'].'-'.$this->field['id'].'" data-count="'.$x.'" rel-id="' . $this->field['id'] . '-ul" rel-name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][slide_title][]">' . __('Add', 'redux-framework') .' '.$this->field['groupname']. '</a><br/>';

            echo '</fieldset>';
            echo '</td></tr></table><table class="form-table no-border" style="margin-top: 0;"><tbody><tr><th></th><td>';
            
        }

        function support_multi($content, $field, $sort) {
            //convert name
            $name = $this->parent->args['opt_name'] . '[' . $field['id'] . ']';
            $content = str_replace($name, $name . '[' . $sort . ']', $content);
            //we should add $sort to id to fix problem with select field
            $content = str_replace(' id="'.$field['id'].'-select"', ' id="'.$field['id'].'-select-'.$sort.'"', $content);
            return $content;
        }

        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since 		1.0.0
         * @access		public
         * @return		void
         */
        public function enqueue() {
            wp_enqueue_script(
                    'redux-field-group-js', REDUX_URL . 'inc/fields/group/field_group.js', array('jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'wp-color-picker'), time(), true
            );

            wp_enqueue_style(
                    'redux-field-group-css', REDUX_URL . 'inc/fields/group/field_group.css', time(), true
            );
        }

        public function enqueue_dependencies($field_type) {
            $field_class = 'ReduxFramework_' . $field_type;

            if (!class_exists($field_class)) {
                $class_file = apply_filters('redux-typeclass-load', REDUX_DIR . 'inc/fields/' . $field_type . '/field_' . $field_type . '.php', $field_class);

                if ($class_file) {
                    /** @noinspection PhpIncludeInspection */
                    require_once( $class_file );
                }
            }

            if (class_exists($field_class) && method_exists($field_class, 'enqueue')) {
                $enqueue = new $field_class('', '', $this);
                $enqueue->enqueue();
            }
        }

    }

}