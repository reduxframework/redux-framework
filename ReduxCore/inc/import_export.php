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
 * @subpackage  field_import_export
 * @author      Dovy Paukstys
 * @author      Kevin Provance (kprovance)
 * @version     3.1.8
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'Redux_import_export' ) ) {

    /**
     * Main ReduxFramework_import_export class
     *
     * @since       1.0.0
     */
    class Redux_import_export {
        public $is_field    = false;
        public $field_args  = array();
        
        public function __construct( $parent ) {  
            $this->parent = $parent;

            add_action("wp_ajax_link_options",              array($this, "link_options"));
            add_action("wp_ajax_nopriv_link_options",       array($this, "link_options"));
            
            add_action("wp_ajax_download_options",          array($this, "download_options"));
            add_action("wp_ajax_nopriv_download_options",   array($this, "download_options"));
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
            $secret     = md5( AUTH_KEY . SECURE_AUTH_KEY );
            
            if (true == $this->is_field) {
                $fullWidth  = $this->field_args['full_width'];
            }
            
            $c          = '';
            $bDoClose   = false;
            
            if (false == $this->is_field) {
                $c = 'redux-group-tab-link-li hide';
            } elseif (true == $this->is_field && false == $fullWidth)  {
                echo '</td></tr></table><table class="form-table no-border redux-group-table redux-raw-table" style="margin-top: -20px;"><tbody><tr><td>';
                $bDoClose = true;
            }
            
            echo '<div id="import_export_default_section_group' . '" class="' . $c . '">';

            if (false == $this->is_field) {
                echo '<h3>' . __( 'Import / Export Options', 'redux-framework' ) . '</h3>';
            }
            
            echo '<h4>' . __( 'Import Options', 'redux-framework' ) . '</h4>';
            echo '<p><a href="javascript:void(0);" id="redux-import-code-button" class="button-secondary">' . __( 'Import from file', 'redux-framework' ) . '</a> <a href="javascript:void(0);" id="redux-import-link-button" class="button-secondary">' . __( 'Import from URL', 'redux-framework' ) . '</a></p>';

            echo '<div id="redux-import-code-wrapper">';

            echo '<div class="redux-section-desc">';
            echo '<p class="description" id="import-code-description">' . apply_filters( 'redux-import-file-description', __( 'Input your backup file below and hit Import to restore your sites options from a backup.', 'redux-framework' ) ) . '</p>';
            echo '</div>';

            echo '<textarea id="import-code-value" name="' . $this->parent->args['opt_name'] . '[import_code]" class="large-text noUpdate" rows="8"></textarea>';

            echo '</div>';

            echo '<div id="redux-import-link-wrapper">';

            echo '<div class="redux-section-desc">';

            echo '<p class="description" id="import-link-description">' . apply_filters( 'redux-import-link-description', __( 'Input the URL to another sites options set and hit Import to load the options from that site.', 'redux-framework' ) ) . '</p>';
            echo '</div>';

            echo '<input type="text" id="import-link-value" name="' . $this->parent->args['opt_name'] . '[import_link]" class="large-text noUpdate" value="" />';

            echo '</div>';

            echo '<p id="redux-import-action"><input type="submit" id="redux-import" name="' . $this->parent->args['opt_name'] . '[import]" class="button-primary" value="' . __( 'Import', 'redux-framework' ) . '">&nbsp;&nbsp;<span>' . apply_filters( 'redux-import-warning', __( 'WARNING! This will overwrite all existing option values, please proceed with caution!', 'redux-framework' ) ) . '</span></p>';
            echo '<div class="hr"/><div class="inner"><span>&nbsp;</span></div></div>';

            echo '<h4>' . __( 'Export Options', 'redux-framework' ) . '</h4>';
            echo '<div class="redux-section-desc">';

            echo '<p class="description">' . apply_filters( 'redux-backup-description', __( 'Here you can copy/download your current option settings. Keep this safe as you can use it as a backup should anything go wrong, or you can use it to restore your settings on this site (or any other site).', 'redux-framework' ) ) . '</p>';
            echo '</div>';

            $link = admin_url('admin-ajax.php?action=download_options&secret=' . $secret);
            echo '<p><a href="javascript:void(0);" id="redux-export-code-copy" class="button-secondary">' . __( 'Copy', 'redux-framework' ) . '</a> <a href="' . $link . '" id="redux-export-code-dl" class="button-primary">' . __( 'Download', 'redux-framework' ) . '</a> <a href="javascript:void(0);" id="redux-export-link" class="button-secondary">' . __( 'Copy Link', 'redux-framework' ) . '</a></p>';
            
            $backup_options = $this->parent->options;
            $backup_options['redux-backup'] = '1';
            echo '<textarea class="large-text noUpdate" id="redux-export-code" rows="8">';

            if (version_compare(phpversion(), "5.3.0", ">=")) {
                print_r( json_encode( $backup_options, true ) );
            } else {
                print_r( json_encode( $backup_options ) );
            }

            echo '</textarea>';

            $link = admin_url('admin-ajax.php?action=link_options&secret=' . $secret);

            echo '<input type="text" class="large-text noUpdate" id="redux-export-link-value" value="' . $link . '" />';
            echo '</div>';
            
            if (true == $bDoClose) {
                echo '</td></tr></table><table class="form-table no-border" style="margin-top: 0;"><tbody><tr style="border-bottom: 0;"><th></th><td>';
            }
        }
        
        public function render_tab(){
            echo '<li id="import_export_default_section_group_li" class="redux-group-tab-link-li">';

            if( !empty( $this->parent->args['icon_type'] ) && $this->parent->args['icon_type'] == 'image' ) {
                $icon = ( !isset( $this->parent->args['import_icon'] ) ) ? '' : '<img src="' . $this->parent->args['import_icon'] . '" /> ';
            } else {
                $icon_class = ( !isset( $this->parent->args['import_icon_class'] ) ) ? '' : ' ' . $this->parent->args['import_icon_class'];
                $icon = ( !isset( $this->parent->args['import_icon'] ) ) ? '<i class="el-icon-refresh' . $icon_class . '"></i>' : '<i class="icon-' . $this->parent->args['import_icon'] . $icon_class . '"></i> ';
            }

            echo '<a href="javascript:void(0);" id="import_export_default_section_group_li_a" class="redux-group-tab-link-a" data-rel="import_export_default">' . $icon . ' <span class="group_title">' . __( 'Import / Export', 'redux-framework' ) . '</span></a>';
            echo '</li>';

            echo '<li class="divide">&nbsp;</li>';            
        }
        
        public function add_submenu(){
            add_submenu_page(
                $this->parent->args['page_slug'],
                __( 'Import / Export', 'redux-framework' ),
                __( 'Import / Export', 'redux-framework' ),
                $this->parent->args['page_permissions'],
                $this->parent->args['page_slug'] . '&tab=import_export_default',
                '__return_null'
            );            
        }
        
        public function enqueue() {
            wp_enqueue_script(
                'redux-field-import-export-js', 
                ReduxFramework::$_url . 'inc/fields/import_export/field_import_export.js', 
                array('jquery'), 
                time(), 
                true
            );

            wp_enqueue_style(
                'redux-field-import-export-css', 
                ReduxFramework::$_url . 'inc/fields/import_export/field_import_export.css', 
                time(), 
                true
            ); 
        } 

        function link_options(){
            if( !isset( $_GET['secret'] ) || $_GET['secret'] != md5( AUTH_KEY . SECURE_AUTH_KEY ) ) {
                wp_die( 'Invalid Secret for options use' );
                exit;
            }
            
            $var = $this->parent->options;
            print_r($var);
            die();            
        }
        
        
        public function download_options(){
            if( !isset( $_GET['secret'] ) || $_GET['secret'] != md5( AUTH_KEY . SECURE_AUTH_KEY ) ) {
                wp_die( 'Invalid Secret for options use' );
                exit;
            }

            $this->parent->get_options();
            $backup_options = $this->parent->options;
            $backup_options['redux-backup'] = '1';

            if (version_compare(phpversion(), "5.3.0", ">=")) {
                $content = json_encode( $backup_options, true ) ;
            } else {
                $content = json_encode( $backup_options ) ;
            }

            if( isset( $_GET['action'] ) && $_GET['action'] == 'download_options' ) {
                header( 'Content-Description: File Transfer' );
                header( 'Content-type: application/txt' );
                header( 'Content-Disposition: attachment; filename="redux_options_' . $this->parent->args['opt_name'] . '_backup_' . date( 'd-m-Y' ) . '.json"' );
                header( 'Content-Transfer-Encoding: binary' );
                header( 'Expires: 0' );
                header( 'Cache-Control: must-revalidate' );
                header( 'Pragma: public' );
                echo $content;
                exit;
            } else {
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT");
                header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
                header( 'Cache-Control: no-store, no-cache, must-revalidate' );
                header( 'Cache-Control: post-check=0, pre-check=0', false );
                header( 'Pragma: no-cache' );

                // Can't include the type. Thanks old Firefox and IE. BAH.
                //header("Content-type: application/json");
                echo $content;
                exit;
            }
        }
    }
}