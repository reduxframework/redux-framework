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
 * @package     Redux_Framework
 * @subpackage  Core
 * @author      Redux Framework Team
 * @version     3.1.7
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Fix for the GT3 page builder: http://www.gt3themes.com/wordpress-gt3-page-builder-plugin/
/** @global string $pagenow */
if(has_action('ecpt_field_options_')) {
    global $pagenow;
    if ( $pagenow === 'admin.php' ) {
        /** @noinspection PhpUndefinedCallbackInspection */
        remove_action( 'admin_init', 'pb_admin_init' );
    }
}


// Don't duplicate me!
if( !class_exists( 'ReduxFramework' ) ) {

    // General helper functions
    include_once(dirname(__FILE__).'/inc/class.redux_helpers.php');

    /**
     * Main ReduxFramework class
     *
     * @since       1.0.0
     */
    class ReduxFramework {

        // ATTENTION DEVS
        // Please update the build number with each push, no matter how small.
        // This will make for easier support when we ask users what version they are using.
        public static $_version = '3.1.7.7';
        public static $_dir;
        public static $_url;
        public static $_properties;
        public static $_is_plugin = true;

        static function init() {

            // Windows-proof constants: replace backward by forward slashes. Thanks to: @peterbouwmeester
            self::$_dir     = trailingslashit( Redux_Helpers::cleanFilePath( dirname( __FILE__ ) ) );
            $wp_content_dir = trailingslashit( Redux_Helpers::cleanFilePath( WP_CONTENT_DIR ) );
            $wp_content_dir = trailingslashit( str_replace( '//', '/', $wp_content_dir ) );
            $relative_url   = str_replace( $wp_content_dir, '', self::$_dir );
            $wp_content_url = Redux_Helpers::cleanFilePath( ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL ) );
            self::$_url     = trailingslashit( $wp_content_url ) . $relative_url;                     

            // See if Redux is a plugin or not
            if ( defined('TEMPLATEPATH') && strpos(__FILE__,TEMPLATEPATH) !== false) {
                self::$_is_plugin = false;
            }

/**
        Still need to port these.

            $defaults['footer_credit']      = '<span id="footer-thankyou">' . __( 'Options panel created using', $this->args['domain']) . ' <a href="' . $this->framework_url . '" target="_blank">' . __('Redux Framework', $this->args['domain']) . '</a> v' . self::$_version . '</span>';
            $defaults['help_tabs']          = array();
            $defaults['help_sidebar']       = ''; // __( '', $this->args['domain'] );
            $defaults['database']           = ''; // possible: options, theme_mods, theme_mods_expanded, transient
            $defaults['customizer']         = false; // setting to true forces get_theme_mod_expanded
            $defaults['global_variable']    = '';
            $defaults['output']             = true; // Dynamically generate CSS
            $defaults['transient_time']     = 60 * MINUTE_IN_SECONDS;

            // The defaults are set so it will preserve the old behavior.
            $defaults['default_show']       = false; // If true, it shows the 
