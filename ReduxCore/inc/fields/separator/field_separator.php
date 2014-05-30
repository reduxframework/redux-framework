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
     * @subpackage  Field_Separator
     * @author      Fahan Wazir (farhanwazir)
     * @author      Farhan Wazir
     * @version     3.2.0
     */

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// Don't duplicate me!
    if ( ! class_exists( 'ReduxFramework_Separator ' ) ) {

        /**
         * Main ReduxFramework_Separator class
         *
         * @since       1.0.0
         */
        class ReduxFramework_Separator {

            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
             *
             * @since         1.0.0
             * @access        public
             * @return        void
             */
            function __construct( $field = array(), $value = '', $parent ) {
                $this->field  = $field;
            }

            /**
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings
             *
             * @since         1.0.0
             * @access        public
             * @return        void
             */
            public function render() {
               $defaults	= array(
			   		'class'	=>	'',
			   		'head-tag'	=>	'h1',
					'icon'	=>	'',
					'icon-size'	=>	'18px',
					'desc'	=>	''
			   );
			   
			   $this->field = wp_parse_args( $this->field, $defaults );
				$iconsize = (!empty($this->field['icon']))? 'style="font-size:'.$this->field['icon-size'].';"' : '';
				$start = '</td></tr></table>';
				$content = '<'.$this->field['head-tag'].'><span class="'.$this->field['icon'].'" '.$iconsize.'></span> '.$this->field['title'].'</'.$this->field['head-tag'].'><p class="redux-separator-desc">'.$this->field['desc'].'</p>';
				$end = '<table class="form-table no-border" style="margin-top: 0;"><tbody><tr style="border-bottom:0;"><th style="padding-top:0;"></th><td style="padding-top:0;">';
				
				$container = $start.'<div id="'.$this->field['id'].'-separator" class="redux-separator redux-separator-field '.$this->field['class'].'">'.
							$content.'</div>'.$end;
				echo $container;
            }
			
			public function enqueue() {

                wp_enqueue_style(
                    'redux-field-separator-css',
                    ReduxFramework::$_url . 'inc/fields/separator/field_separator.css',
                    time(),
                    true
                );
			}
        }
    }