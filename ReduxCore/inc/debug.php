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
 * @subpackage  debug object
 * @author      Dovy Paukstys
 * @author      Kevin Provance (kprovance)
 * @version     3.1.8
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    exit;
}

if( !class_exists( 'ReduxDebugObject' ) ) {

    /**
     * Main ReduxFramework_import_export class
     *
     * @since       1.0.0
     */
    class ReduxDebugObject {
        public function __construct($parent) {
            $this->parent = $parent;
        }

        public function render() {
            echo '<div id="dev_mode_default_section_group' . '" class="redux-group-tab">';
            echo '<h3>' . __( 'Options Object', 'redux-framework' ) . '</h3>';
            echo '<div class="redux-section-desc">';
            echo '<div id="redux-object-browser"></div>';
            echo '</div>';

            if (version_compare(phpversion(), "5.3.0", ">=")) {
                $json = json_encode( $this->parent->options, true ) ;
            } else {
                $json = json_encode( $this->parent->options );
            }

            echo '<div id="redux-object-json" class="hide">' . $json . '</div>';

            echo '<a href="#" id="consolePrintObject" class="button">' . __( 'Show Object in Javascript Console Object', 'redux-framework' ) . '</a>';

            echo '</div>';
        }

        public function render_tab() {
            echo '<li id="dev_mode_default_section_group_li" class="redux-group-tab-link-li">';

            if( !empty( $this->parent->args['icon_type'] ) && $this->parent->args['icon_type'] == 'image' ) {
                $icon = ( !isset( $this->parent->args['dev_mode_icon'] ) ) ? '' : '<img src="' . $this->parent->args['dev_mode_icon'] . '" /> ';
            } else {
                $icon_class = ( !isset( $this->parent->args['dev_mode_icon_class'] ) ) ? '' : ' ' . $this->parent->args['dev_mode_icon_class'];
                $icon = ( !isset( $this->parent->args['dev_mode_icon'] ) ) ? '<i class="el-icon-info-sign' . $icon_class . '"></i>' : '<i class="icon-' . $this->parent->args['dev_mode_icon'] . $icon_class . '"></i> ';
            }

            echo '<a href="javascript:void(0);" id="dev_mode_default_section_group_li_a" class="redux-group-tab-link-a custom-tab" data-rel="dev_mode_default">' . $icon . ' <span class="group_title">' . __( 'Options Object', 'redux-framework' ) . '</span></a>';
            echo '</li>';
        }

        public function add_submenu() {
            add_submenu_page(
                $this->parent->args['page_slug'],
                __( 'Options Object', 'redux-framework' ),
                __( 'Options Object', 'redux-framework' ),
                $this->parent->args['page_permissions'],
                $this->parent->args['page_slug'] . '&tab=dev_mode_default',
                '__return_null'
            );
        }
    }
}
