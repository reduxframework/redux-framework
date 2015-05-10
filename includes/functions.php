<?php

/**
 * Enqueues style for SASS comnpile or WP enqueue, depending on 'use_sass' arg.
 *
 * @since       3.3.9
 * @access      public
 * @param       string  $handle     Name of the stylesheet.
 * @param       string  $css_src    Path to the stylesheet from the root directory of WordPress. Example: '/css/mystyle.css'.
 * @param       string  $scss_dir   Directory path to SCSS file.
 * @param       array   $deps       An array of registered style handles this stylesheet depends on. Default empty array.
 * @param       string  $ver        String specifying the stylesheet version number, if it has one. This parameter is used to ensure that the correct version is sent to the client regardless of caching, and so should be included if a version number is available and makes sense for the stylesheet.
 * @param       string  $media      Optional. The media for which this stylesheet has been defined. Default 'all'. Accepts 'all', 'aural', 'braille', 'handheld', 'projection', 'print', 'screen', 'tty', or 'tv'.
 * @return      void
 */
function redux_enqueue_style ( $parent, $handle, $css_src, $scss_dir, $deps = array(), $ver = '', $media = false ) {

    if ( $parent->args['sass']['enabled'] ) {

        //if ( $parent->args['dev_mode'] || $parent->args['sass']['page_output'] ) {

            $path_parts = pathinfo( $css_src );

            $filename   = $path_parts['filename'];

            //echo $filename . '<br>';
            $scss_dir = Redux_Helpers::cleanFilePath( $scss_dir );
            $scss_dir = untrailingslashit( $scss_dir );
            $is_diff  = Redux_Sass_Compiler::is_scss_newer( $scss_dir, $filename );

            if ( $is_diff ) {
                Redux_Sass_Compiler::compile_single_field( $parent, $scss_dir, $filename );
                Redux_Sass_Compiler::$_do_compile = true;
            }

            Redux_Sass_Compiler::add_path( $scss_dir );
            Redux_Sass_Compiler::add_import( '@import "' . $filename . '.scss"' );

        //}

    } else {

        wp_enqueue_style( $handle, $css_src, $deps, $ver, $media );

    }

}


/**
 * Adds tracking parameters for Redux settings. Outside of the main class as the class could also be in use in other ways.
 *
 * @param array $options
 *
 * @return array
 */
function redux_tracking_additions( $options ) {
    $opt = array();

    $options['redux'] = array(
        'demo_mode' => get_option( 'Redux_Framework_Plugin' ),
    );

    return $options;
}
add_filter( 'redux/tracking/options', 'redux_tracking_additions' );

function redux_allow_tracking_callback() {
    // Verify that the incoming request is coming with the security nonce
    if ( wp_verify_nonce( $_REQUEST['nonce'], 'redux_activate_tracking' ) ) {
        $options = get_option( 'redux-framework-tracking' );

        if ( $_REQUEST['allow_tracking'] == "tour" ) {
            $options['tour'] = 1;
        } else {
            $options['allow_tracking'] = $_REQUEST['allow_tracking'];
        }

        if ( update_option( 'redux-framework-tracking', $options ) ) {
            die( '1' );
        } else {
            die( '0' );
        }
    } else {
        // Send -1 if the attempt to save via Ajax was completed invalid.
        die( '-1' );
    } // end if
}
add_action( 'wp_ajax_redux_allow_tracking', 'redux_allow_tracking_callback' );
