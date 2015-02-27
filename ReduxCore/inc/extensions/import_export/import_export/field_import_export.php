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
     * @author      Dovy Paukstys
     * @version     3.1.5
     */

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// Don't duplicate me!
    if ( ! class_exists( 'ReduxFramework_import_export' ) ) {

        /**
         * Main ReduxFramework_import_export class
         *
         * @since       1.0.0
         */
        class ReduxFramework_import_export extends ReduxFramework {

            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            function __construct( $field = array(), $value = '', $parent ) {


                $this->parent   = $parent;
                $this->field    = $field;
                $this->value    = $value;
                $this->is_field = $this->parent->extensions['import_export']->is_field;

                if ( empty( $this->extension_dir ) ) {
                    $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                    $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
                }

                // Set default args for this field to avoid bad indexes. Change this to anything you use.
                $defaults    = array(
                    'options'          => array(),
                    'stylesheet'       => '',
                    'output'           => true,
                    'enqueue'          => true,
                    'enqueue_frontend' => true
                );
                $this->field = wp_parse_args( $this->field, $defaults );

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

                $secret = md5( md5( AUTH_KEY . SECURE_AUTH_KEY ) . '-' . $this->parent->args['opt_name'] );

                // No errors please
                $defaults = array(
                    'full_width' => true,
                    'overflow'   => 'inherit',
                );

                $this->field = wp_parse_args( $this->field, $defaults );

                if ( $this->is_field ) {
                    $fullWidth = $this->field['full_width'];
                }

                $bDoClose = false;

                $id = $this->parent->args['opt_name'] . '-' . $this->field['id'];

                if ( ! $this->is_field || ( $this->is_field && false == $fullWidth ) ) : ?>
                    <style>#<?php echo $id; ?> {padding: 0;}</style>
                    </td></tr></table>
                    <table id="<?php echo $id; ?>" class="form-table no-border redux-group-table redux-raw-table" style=" overflow: <?php $this->field['overflow']; ?>;">
                    <tbody><tr><td>
                <?php
                    $bDoClose = true;
                endif;
                ?>
                <fieldset id="<?php echo $id; ?>" class="redux-field redux-container-<?php echo $this->field['type'] . ' ' . $this->field['class']; ?>" data-id="<?php echo $this->field['id']; ?>">

                <h4><?php _e( 'Import Options', 'redux-framework' ); ?></h4>
                <p><a href="javascript:void(0);" id="redux-import-code-button" class="button-secondary"><?php _e( 'Import from File', 'redux-framework' ); ?></a> <a href="javascript:void(0);" id="redux-import-link-button" class="button-secondary"><?php _e( 'Import from URL', 'redux-framework' ) ?></a></p>

                <div id="redux-import-code-wrapper">
                <p class="description" id="import-code-description"><?php echo apply_filters( 'redux-import-file-description', __( 'Input your backup file below and hit Import to restore your sites options from a backup.', 'redux-framework' ) ); ?></p>
                <textarea id="import-code-value" name="<?php echo $this->parent->args['opt_name']; ?>[import_code]" class="large-text noUpdate" rows="2"></textarea>
                </div>

                <div id="redux-import-link-wrapper">
                <p class="description" id="import-link-description"><?php echo apply_filters( 'redux-import-link-description', __( 'Input the URL to another sites options set and hit Import to load the options from that site.', 'redux-framework' ) ); ?></p>
                <textarea class="large-text noUpdate" id="import-link-value" name="<?php echo $this->parent->args['opt_name'] ?>[import_link]" rows="2"></textarea>
                </div>

                <p id="redux-import-action"><input type="submit" id="redux-import" name="' . $this->parent->args['opt_name'] . '[import]" class="button-primary" value="<?php _e( 'Import', 'redux-framework' ) ?>">&nbsp;&nbsp;<span><?php echo apply_filters( 'redux-import-warning', __( 'WARNING! This will overwrite all existing option values, please proceed with caution!', 'redux-framework' ) ) ?></span></p>

                <div class="hr"/><div class="inner"><span>&nbsp;</span></div></div>
                <h4><?php _e( 'Export Options', 'redux-framework' ) ?></h4>
                <div class="redux-section-desc">
                <p class="description"><?php echo apply_filters( 'redux-backup-description', __( 'Here you can copy/download your current option settings. Keep this safe as you can use it as a backup should anything go wrong, or you can use it to restore your settings on this site (or any other site).', 'redux-framework' ) ) ?></p>
                </div>
                <?php
                $link = admin_url( 'admin-ajax.php?action=redux_download_options-' . $this->parent->args['opt_name'] . '&secret=' . $secret );
                ?>
                <p><a href="javascript:void(0);" id="redux-export-code-copy" class="button-secondary"><?php _e( 'Copy Data', 'redux-framework' ) ?></a> <a href="<?php echo $link; ?>" id="redux-export-code-dl" class="button-primary"><?php _e( 'Download Data File', 'redux-framework' ) ?></a> <a href="javascript:void(0);" id="redux-export-link" class="button-secondary"><?php _e( 'Copy Export URL', 'redux-framework' ) ?></a></p>
                <p></p>
                <textarea class="large-text noUpdate" id="redux-export-code" rows="2"></textarea>
                <textarea class="large-text noUpdate" id="redux-export-link-value" data-url="<?php echo $link; ?>" rows="2"><?php echo $link; ?></textarea>

                </div>

                </fieldset>
                <?php
                if ( true == $bDoClose ) : ?>
                    </td></tr></table>
                    <table class="form-table no-border" style="margin-top: 0;">
                        <tbody>
                        <tr style="border-bottom: 0;">
                            <th></th>
                            <td>
                <?php
                endif;

            }

            /**
             * Enqueue Function.
             * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function enqueue() {

                wp_enqueue_script(
                    'redux-import-export',
                    $this->extension_url . 'field_import_export' . Redux_Functions::isMin() . '.js',
                    array( 'jquery' ),
                    ReduxFramework_extension_import_export::$version,
                    true
                );

                wp_enqueue_style(
                    'redux-import-export',
                    $this->extension_url . 'field_import_export.css',
                    time(),
                    true
                );

            }

            /**
             * Output Function.
             * Used to enqueue to the front-end
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function output() {

                if ( $this->field['enqueue_frontend'] ) {

                }

            }

        }
    }
