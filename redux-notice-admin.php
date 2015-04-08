<?php
    /**
     * The Redux Framework Plugin
     * A simple, truly extensible and fully responsive options framework
     * for WordPress themes and plugins. Developed with WordPress coding
     * standards and PHP best practices in mind.
     * Plugin Name:     Redux Admin Notice Editor
     * Plugin URI:      http://reduxframework.com
     * Description:     Redux Framework Admin Notice Editor.
     * Author:          Kevin Provance for Team Redux
     * Author URI:      http://reduxframework.com
     * Version:         1.0.0
     * Text Domain:     redux-framework
     * License:         GPL3+
     * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
     * Domain Path:     /ReduxFramework/ReduxCore/languages
     *
     * @package         ReduxFramework
     * @author          Dovy Paukstys <dovy@reduxframework.com>
     * @author          Kevin Provance <kevin@reduxframework.com>
     * @license         GNU General Public License, version 3
     * @copyright       2012-2014 Redux Framework
     */

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        die;
    }

    add_action( 'setup_theme', 'redux_notice_admin', 5 );
    function redux_notice_admin() {
        if ( class_exists( 'Redux' ) ) {
            //$files = glob( dirname( __FILE__ ) . '/configs/*.php' );
            //foreach ( $files as $file ) {
            include( dirname( __FILE__ ) . '/config.php' );
            //}
        } else {

        }
    }