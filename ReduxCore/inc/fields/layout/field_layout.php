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
     * @package     Redux_Field
     * @subpackage  Layout
     * @version     3.0.0
     */
// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// Don't duplicate me!
    if ( ! class_exists( 'ReduxFramework_layout' ) ) {
        class ReduxFramework_layout {

            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
             *
             * @since ReduxFramework 1.0.0
             */
            function __construct( $field = array(), $value = '', $parent ) {

                $this->parent = $parent;
                $this->field  = $field;
                $this->value  = $value;
            } //function

            /**
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings
             *
             * @since ReduxFramework 1.0.0
             */
            function render() {

                // No errors please
                $defaults = array(
                    'margin-all'					=> true,
                    'margin-top'					=> false,
                    'margin-right'					=> false,
                    'margin-bottom'					=> false,
                    'margin-left'					=> false,
                    'margin-units'					=> array('%', 'px', 'in', 'cm', 'mm', 'em', 'rem', 'ex', 'pt', 'pc'),
                    'padding-all'					=> true,
                    'padding-top'					=> false,
                    'padding-right'					=> false,
                    'padding-bottom'				=> false,
                    'padding-left'					=> false,
                    'padding-units'					=> array('%', 'px', 'in', 'cm', 'mm', 'em', 'rem', 'ex', 'pt', 'pc'),
                    'border-all'					=> true,
                    'border-top'					=> false,
                    'border-right'					=> false,
                    'border-bottom'					=> false,
                    'border-left'					=> false,
                    'border-units'					=> array('%', 'px', 'in', 'cm', 'mm', 'em', 'rem', 'ex', 'pt', 'pc'),
                    'border-style'					=> true,
                    'border-color'					=> true,
                    'border-radius'					=> true,
                    'border-radius-units'			=> array('%', 'px', 'in', 'cm', 'mm', 'em', 'rem', 'ex', 'pt', 'pc'),
				);

                $this->field = wp_parse_args( $this->field, $defaults );

                $defaults = array(
                    'margin-top'					=> '',
                    'margin-right'					=> '',
                    'margin-bottom'					=> '',
                    'margin-left'					=> '',
                    'padding-top'					=> '',
                    'padding-right'					=> '',
                    'padding-bottom'				=> '',
                    'padding-left'					=> '',
                    'border-top'					=> '',
                    'border-right'					=> '',
                    'border-bottom'					=> '',
                    'border-left'					=> '',
                    'border-style'					=> '',
                    'border-color'					=> '',
                    'border-radius'					=> ''
                );

                $this->value = wp_parse_args( $this->value, $defaults );

                $this->value = array(
                    'margin-top'    => isset( $this->value['margin-top'] ) || !isset($this->field['default']['margin-top']) ? $this->serialize_value( $this->value['margin-top'], $this->field['margin-units'] ) : $this->field['default']['margin-top'],
                    'margin-right'  => isset( $this->value['margin-right'] ) || !isset($this->field['default']['margin-right']) ? $this->serialize_value( $this->value['margin-right'], $this->field['margin-units'] ) : $this->field['default']['margin-right'],
                    'margin-bottom' => isset( $this->value['margin-bottom'] ) || !isset($this->field['default']['margin-bottom']) ? $this->serialize_value( $this->value['margin-bottom'], $this->field['margin-units'] ) : $this->field['default']['margin-bottom'],
                    'margin-left'   => isset( $this->value['margin-left'] ) || !isset($this->field['default']['margin-left']) ? $this->serialize_value( $this->value['margin-left'], $this->field['margin-units'] ) : $this->field['default']['margin-left'],
                    'padding-top'   => isset( $this->value['padding-top'] ) || !isset($this->field['default']['padding-top']) ? $this->serialize_value( $this->value['padding-top'], $this->field['padding-units'] ) : $this->field['default']['padding-top'],
                    'padding-right' => isset( $this->value['padding-right'] ) || !isset($this->field['default']['padding-right']) ? $this->serialize_value( $this->value['padding-right'], $this->field['padding-units'] ) : $this->field['default']['padding-right'],
                    'padding-bottom'=> isset( $this->value['padding-bottom'] ) || !isset($this->field['default']['padding-bottom']) ? $this->serialize_value( $this->value['padding-bottom'], $this->field['padding-units'] ) : $this->field['default']['padding-bottom'],
                    'padding-left'  => isset( $this->value['padding-left'] ) || !isset($this->field['default']['padding-left']) ? $this->serialize_value( $this->value['padding-left'], $this->field['padding-units'] ) : $this->field['default']['padding-left'],
                    'border-top'    => isset( $this->value['border-top'] ) || !isset($this->field['default']['border-top']) ? $this->serialize_value( $this->value['border-top'], $this->field['border-units'] ) : $this->field['default']['border-top'],
                    'border-right'  => isset( $this->value['border-right'] ) || !isset($this->field['default']['border-right']) ? $this->serialize_value( $this->value['border-right'], $this->field['border-units'] ) : $this->field['default']['border-right'],
                    'border-bottom' => isset( $this->value['border-bottom'] ) || !isset($this->field['default']['border-bottom']) ? $this->serialize_value( $this->value['border-bottom'], $this->field['border-units'] ) : $this->field['default']['border-bottom'],
                    'border-left'   => isset( $this->value['border-left'] ) || !isset($this->field['default']['border-left']) ? $this->serialize_value( $this->value['border-left'], $this->field['border-units'] ) : $this->field['default']['border-left'],
                    'border-style'  => isset( $this->value['border-style'] ) ? $this->value['border-style'] : $this->value['border-style'],
                    'border-color'  => isset( $this->value['border-color'] ) ? $this->value['border-color'] : $this->value['border-color'],
                    'border-radius' => isset( $this->value['border-radius'] ) ? filter_var( $this->value['border-radius'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : filter_var( $this->value['border-radius'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION )
                );

                echo '<input type="hidden" class="redux-margin-top-value" id="' . $this->field['id'] . '-margin-top" name="' . $this->field['name'] . $this->field['name_suffix'] . '[margin-top]" value="' . ( $this->value['margin-top'] ? $this->value['margin-top'] . 'px' : 0 ) . '">';
                echo '<input type="hidden" class="redux-margin-right-value" id="' . $this->field['id'] . '-margin-right" name="' . $this->field['name'] . $this->field['name_suffix'] . '[margin-right]" value="' . ( $this->value['margin-right'] ? $this->value['margin-right'] . 'px' : 0 ) . '">';
                echo '<input type="hidden" class="redux-margin-bottom-value" id="' . $this->field['id'] . '-margin-bottom" name="' . $this->field['name'] . $this->field['name_suffix'] . '[margin-bottom]" value="' . ( $this->value['margin-bottom'] ? $this->value['margin-bottom'] . 'px' : 0 ) . '">';
                echo '<input type="hidden" class="redux-margin-left-value" id="' . $this->field['id'] . '-margin-left" name="' . $this->field['name'] . $this->field['name_suffix'] . '[margin-left]" value="' . ( $this->value['margin-left'] ? $this->value['margin-left'] . 'px' : 0 ) . '">';
				echo '<input type="hidden" class="redux-padding-top-value" id="' . $this->field['id'] . '-padding-top" name="' . $this->field['name'] . $this->field['name_suffix'] . '[padding-top]" value="' . ( $this->value['padding-top'] ? $this->value['padding-top'] . 'px' : 0 ) . '">';
                echo '<input type="hidden" class="redux-padding-right-value" id="' . $this->field['id'] . '-padding-right" name="' . $this->field['name'] . $this->field['name_suffix'] . '[padding-right]" value="' . ( $this->value['padding-right'] ? $this->value['padding-right'] . 'px' : 0 ) . '">';
                echo '<input type="hidden" class="redux-padding-bottom-value" id="' . $this->field['id'] . '-padding-bottom" name="' . $this->field['name'] . $this->field['name_suffix'] . '[padding-bottom]" value="' . ( $this->value['padding-bottom'] ? $this->value['padding-bottom'] . 'px' : 0 ) . '">';
                echo '<input type="hidden" class="redux-padding-left-value" id="' . $this->field['id'] . '-padding-left" name="' . $this->field['name'] . $this->field['name_suffix'] . '[padding-left]" value="' . ( $this->value['padding-left'] ? $this->value['padding-left'] . 'px' : 0 ) . '">';
				echo '<input type="hidden" class="redux-border-top-value" id="' . $this->field['id'] . '-border-top" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-top]" value="' . ( $this->value['border-top'] ? $this->value['border-top'] . 'px' : 0 ) . '">';
                echo '<input type="hidden" class="redux-border-right-value" id="' . $this->field['id'] . '-border-right" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-right]" value="' . ( $this->value['border-right'] ? $this->value['border-right'] . 'px' : 0 ) . '">';
                echo '<input type="hidden" class="redux-border-bottom-value" id="' . $this->field['id'] . '-border-bottom" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-bottom]" value="' . ( $this->value['border-bottom'] ? $this->value['border-bottom'] . 'px' : 0 ) . '">';
                echo '<input type="hidden" class="redux-border-left-value" id="' . $this->field['id'] . '-border-left" name="' . $this->field['name'] . $this->field['name_suffix'] . '[border-left]" value="' . ( $this->value['border-left'] ? $this->value['border-left'] . 'px' : 0 ) . '">';

                echo '<div class="field-layout">';
	                echo '<div class="field-margin">';
						echo '<span>' . __( 'Margin', 'redux-framework' ) . '</span>';
						echo '<div class="field-margin-position field-margin-vertical field-margin-top">';
		                	$this->render_input('margin', 'top');
						echo '</div>';
		                echo '<div class="field-margin-position field-margin-horizontal field-margin-right">';
		                	$this->render_input('margin', 'right');
						echo '</div>';
						echo '<div class="field-margin-position field-margin-vertical field-margin-bottom">';
		                	$this->render_input('margin', 'bottom');
						echo '</div>';
						echo '<div class="field-margin-position field-margin-horizontal field-margin-left">';
		                	$this->render_input('margin', 'left');
						echo '</div>';
					echo '</div>';
					echo '<div class="field-border">';
						echo '<span>' . __( 'Border', 'redux-framework' ) . '</span>';
						echo '<div class="field-border-position field-border-vertical field-border-top">';
		                	$this->render_input('border', 'top');
						echo '</div>';
		                echo '<div class="field-border-position field-border-horizontal field-border-right">';
		                	$this->render_input('border', 'right');
						echo '</div>';
						echo '<div class="field-border-position field-border-vertical field-border-bottom">';
		                	$this->render_input('border', 'bottom');
						echo '</div>';
						echo '<div class="field-border-position field-border-horizontal field-border-left">';
		                	$this->render_input('border', 'left');
						echo '</div>';
					echo '</div>';
					echo '<div class="field-padding">';
						echo '<span>' . __( 'Padding', 'redux-framework' ) . '</span>';
		                echo '<div class="field-padding-position field-padding-vertical field-padding-top">';
		                	$this->render_input('padding', 'top');
						echo '</div>';
		                echo '<div class="field-padding-position field-padding-horizontal field-padding-right">';
		                	$this->render_input('padding', 'right');
						echo '</div>';
						echo '<div class="field-padding-position field-padding-vertical field-padding-bottom">';
		                	$this->render_input('padding', 'bottom');
						echo '</div>';
						echo '<div class="field-padding-position field-padding-horizontal field-padding-left">';
		                	$this->render_input('padding', 'left');
						echo '</div>';
					echo '</div>';
					echo '<div class="field-inner">';
					echo '</div>';
				echo '</div>';
				if( $this->field['border-style'] || $this->field['border-border'] || $this->field['border-radius'] ) {
				echo '<div class="field-properties">';
					if( $this->field['border-style'] ) {
					echo '<div class="border-style">';
						echo '<label>' . __( 'Border Style', 'redux-framework' ) . '</label>';
						$this->render_select('border-style');
					echo '</div>';
					}
					if( $this->field['border-color'] ) {
					echo '<div class="border-color">';
						echo '<label>' . __( 'Border Color', 'redux-framework' ) . '</label>';
						$this->render_color('border-color');
					echo '</div>';
					}
					if( $this->field['border-radius'] ) {
					echo '<div class="border-radius">';
						echo '<label>' . __( 'Border Radius', 'redux-framework' ) . '</label>';
						$this->render_input('border-radius');
					echo '</div>';
					}
				echo '</div>';
				}
            }

            //function

            /**
             * Enqueue Function.
             * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
             *
             * @since ReduxFramework 1.0.0
             */
            function enqueue() {
                $min = Redux_Functions::isMin();

                wp_enqueue_script(
                    'redux-field-layout-js',
                    ReduxFramework::$_url . 'inc/fields/layout/field_layout' . $min . '.js',
                    array( 'jquery', 'wp-color-picker', 'redux-js' ),
                    time(),
                    true
                );

                wp_enqueue_style(
                    'redux-field-layout-css',
                    ReduxFramework::$_url . 'inc/fields/layout/field_layout.css',
                    time(),
                    true
                );
            } //function

            public function output() {
            	$style = "";
            	if ( isset( $this->field['margin-all'] ) && !empty( $this->value['margin-top'] ) && ( $this->field['margin-all'] === true || $this->field['margin-top'] === true ) )
                	$style .= 'margin-top:' . $this->value['margin-top'];

				if ( isset( $this->field['margin-all'] ) && !empty( $this->value['margin-right'] ) && ( $this->field['margin-all'] === true || $this->field['margin-right'] === true ) )
                	$style .= 'margin-right:' . $this->value['margin-right'];

				if ( isset( $this->field['margin-all'] ) && !empty( $this->value['margin-bottom'] ) && ( $this->field['margin-all'] === true || $this->field['margin-bottom'] === true ) )
                	$style .= 'margin-bottom:' . $this->value['margin-bottom'];

				if ( isset( $this->field['margin-all'] ) && !empty( $this->value['margin-left'] ) && ( $this->field['margin-all'] === true || $this->field['margin-left'] === true ) )
                	$style .= 'margin-left:' . $this->value['margin-left'];

				if ( isset( $this->field['padding-all'] ) && !empty( $this->value['padding-top'] ) && ( $this->field['padding-all'] === true || $this->field['padding-top'] === true ) )
                	$style .= 'padding-top:' . $this->value['padding-top'];

				if ( isset( $this->field['padding-all'] ) && !empty( $this->value['padding-right'] ) && ( $this->field['padding-all'] === true || $this->field['padding-right'] === true ) )
                	$style .= 'padding-right:' . $this->value['padding-right'];

				if ( isset( $this->field['padding-all'] ) && !empty( $this->value['padding-bottom'] ) && ( $this->field['padding-all'] === true || $this->field['padding-bottom'] === true ) )
                	$style .= 'padding-bottom:' . $this->value['padding-bottom'];

				if ( isset( $this->field['padding-all'] ) && !empty( $this->value['padding-left'] ) && ( $this->field['padding-all'] === true || $this->field['padding-left'] === true ) )
                	$style .= 'padding-left:' . $this->value['padding-left'];

				if ( isset( $this->field['border-all'] ) && !empty( $this->value['border-top'] ) && ( $this->field['border-all'] === true || $this->field['border-top'] === true ) )
                	$style .= 'border-top:' . $this->value['border-top'];

				if ( isset( $this->field['border-all'] ) && !empty( $this->value['border-right'] ) && ( $this->field['border-all'] === true || $this->field['border-right'] === true ) )
                	$style .= 'border-right:' . $this->value['border-right'];

				if ( isset( $this->field['border-all'] ) && !empty( $this->value['border-bottom'] ) && ( $this->field['border-all'] === true || $this->field['border-bottom'] === true ) )
                	$style .= 'border-bottom:' . $this->value['border-bottom'];

				if ( isset( $this->field['border-all'] ) && !empty( $this->value['border-left'] ) && ( $this->field['border-all'] === true || $this->field['border-left'] === true ) )
                	$style .= 'border-left:' . $this->value['border-left'];

				if ( isset( $this->field['border-style'] ) && !empty( $this->value['border-style'] ) && $this->field['border-style'] === true )
                	$style .= 'border-style:' . $this->value['border-style'];

				if ( isset( $this->field['border-color'] ) && !empty( $this->value['border-color'] ) && $this->field['border-color'] === true )
                	$style .= 'border-color:' . $this->value['border-color'];

				if ( isset( $this->field['border-radius'] ) && !empty( $this->value['border-radius'] ) && $this->field['border-radius'] === true )
                	$style .= 'border-radius:' . $this->value['border-radius'];

                if ( ! empty( $this->field['output'] ) && is_array( $this->field['output'] ) ) {
                    $keys = implode( ",", $this->field['output'] );
                    $this->parent->outputCSS .= $keys . "{" . $style . '}';
                }

                if ( ! empty( $this->field['compiler'] ) && is_array( $this->field['compiler'] ) ) {
                    $keys = implode( ",", $this->field['compiler'] );
                    $this->parent->compilerCSS .= $keys . "{" . $style . '}';
                }
            }

			function serialize_value($value = '', $units = array()) {
				$value = preg_split('#(?<=\d)(?=[a-z%])#i', $value);
				if( isset($value[0]) && is_numeric($value[0]) && $value[0] != 0 ) {
			    	$num = $value[0];
					$unit = ( isset($value[1]) && in_array($value[1], $units) ) ? $value[1] : 'px';
					$value = $num . $unit;
			    }
				else
					$value = '';

				return $value;
			}

			function render_input($property = '', $position = '') {
				$pooled = !empty($position) ? $property . '-' . $position : $property;
				$disabled = ( isset( $this->field[$property . '-all'] ) && $this->field[$property . '-all'] !== true && $this->field[$pooled] !== true ) ? 'disabled="disabled"' : '';
			    $value = $this->serialize_value($this->value[$pooled], $this->field[$property . '-units']);
	
	            echo '<div class="field-' . $property . '-input field-' . $pooled . '-input input-prepend"><input type="text" class="redux-' . $pooled . ' redux-' . $property . '-input mini' . $this->field['class'] . '" placeholder="-" rel="' . $this->field['id'] . '-' . $pooled . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '[' . $pooled . ']" value="' . $value . '" ' . $disabled . ' /></div>';
			}

			function render_select($property = '') {
				if ( $this->field[$property] === true ) {
					$options = array(
                        'solid'  => 'Solid',
                        'dashed' => 'Dashed',
                        'dotted' => 'Dotted',
                        'none'   => 'None'
                    );
                    echo '<select original-title="' . __( 'Border style', 'redux-framework' ) . '" id="' . $this->field['id'] . '[' . $property. ']" name="' . $this->field['name'] . $this->field['name_suffix'] . '[' . $property . ']" class="tips redux-' . $property . ' ' . $this->field['class'] . '" rows="6" data-id="' . $this->field['id'] . '">';
                    foreach ( $options as $k => $v ) {
                        echo '<option value="' . $k . '"' . selected( $this->value[$property], $k, false ) . '>' . $v . '</option>';
                    }
                    echo '</select>';
                } else {
                    echo '<input type="hidden" id="' . $this->field['id'] . '[' . $property . ']" name="' . $this->field['name'] . $this->field['name_suffix'] . '[' . $property . ']" value="' . $this->value[$property] . '" data-id="' . $this->field['id'] . '">';
                }
			}

			function render_color($property = '') {
                if ( $this->field[$property] === true ) {
                    $default = isset( $this->field['default'][$property] ) ? $this->field['default'][$property] : '#ffffff';

                    echo '<input name="' . $this->field['name'] . $this->field['name_suffix'] . '[' . $property . ']" id="' . $this->field['id'] . '[' . $property . ']" class="redux-' . $property . ' redux-color redux-color-init ' . $this->field['class'] . '"  type="text" value="' . $this->value[$property] . '"  data-default-color="' . $default . '" data-id="' . $this->field['id'] . '" />';
                } else {
                    echo '<input type="hidden" id="' . $this->field['id'] . '[' . $property . ']" name="' . $this->field['name'] . $this->field['name_suffix'] . '[' . $property . ']" value="' . $this->value[$property] . '" data-id="' . $this->field['id'] . '">';
                }
			}
        } //class
    }