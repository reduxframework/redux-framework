<?php
    /**
     * Admin View: Page - Status Report
     */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $wpdb;

function redux_get_file_version ( $file ) {

    // Avoid notices if file does not exist
    if ( !file_exists ( $file ) ) {
        return '';
    }

    // We don't need to write to the file, so just open for reading.
    $fp = fopen ( $file, 'r' );

    // Pull only the first 8kiB of the file in.
    $file_data = fread ( $fp, 8192 );

    // PHP will close file handle, but we are good citizens.
    fclose ( $fp );

    // Make sure we catch CR-only line endings.
    $file_data = str_replace ( "\r", "\n", $file_data );
    $version = '';

    if ( preg_match ( '/^[ \t\/*#@]*' . preg_quote ( '@version', '/' ) . '(.*)$/mi', $file_data, $match ) && $match[ 1 ] )
        $version = _cleanup_header_comment ( $match[ 1 ] );

    return $version;
}
    
function redux_scan_template_files ( $template_path ) {
    $files = scandir ( $template_path );
    $result = array();

    if ( $files ) {
        foreach ( $files as $key => $value ) {
            if ( !in_array ( $value, array( ".", ".." ) ) ) {
                if ( is_dir ( $template_path . DIRECTORY_SEPARATOR . $value ) ) {
                    $sub_files = redux_scan_template_files ( $template_path . DIRECTORY_SEPARATOR . $value );
                    foreach ( $sub_files as $sub_file ) {
                        $result[] = $value . DIRECTORY_SEPARATOR . $sub_file;
                    }
                } else {
                    $result[] = $value;
                }
            }
        }
    }
    
    return $result;
}

function redux_clean( $var ) {
    return sanitize_text_field( $var );
}

function redux_let_to_num( $size ) {
    $l   = substr( $size, -1 );
    $ret = substr( $size, 0, -1 );
    
    switch ( strtoupper( $l ) ) {
        case 'P':
            $ret *= 1024;
        //break;
        case 'T':
            $ret *= 1024;
        //break;
        case 'G':
            $ret *= 1024;
        //break;
        case 'M':
            $ret *= 1024;
        //break;
        case 'K':
            $ret *= 1024;
        //break;
    }
    
    return $ret;
}

?>
<div class="wrap about-wrap redux-status">
    <h1><?php _e( 'Redux Framework - System Status', 'redux-framework' ); ?></h1>

    <div
        class="about-text"><?php _e( 'Our core mantra at Redux is backwards compatibility. With hundreds of thousands of instances worldwide, you can be assured that we will take care of you and your clients.', 'redux-framework' ); ?></div>
    <div
        class="redux-badge"><i
            class="el el-redux"></i><span><?php printf( __( 'Version %s', 'redux-framework' ), ReduxFramework::$_version ); ?></span>
    </div>

    <p class="redux-actions">
        <a href="http://docs.reduxframework.com/" class="docs button button-primary">Docs</a>
        <a href="https://wordpress.org/plugins/redux-framework/" class="review-us button button-primary" target="_blank">Review Us</a>
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MMFMHWUPKHKPW" class="review-us button button-primary" target="_blank">Donate</a>
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://reduxframework.com" data-text="Reduce your dev time! Redux is the most powerful option framework for WordPress on the web" data-via="ReduxFramework" data-size="large" data-hashtags="Redux">Tweet</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </p>

    <?php $this->tabs(); ?>
                
    <div class="updated redux-message">
            <p><?php _e( 'Please copy and paste this information in your ticket when contacting support:', 'redux-framework' ); ?> </p>
            <p class="submit"><a href="#" class="button-primary debug-report"><?php _e( 'Get System Report', 'redux-framework' ); ?></a>
            <a class="skip button-primary" href="http://docs.reduxframework.com/understanding-the-redux-framework-system-status-report/" target="_blank"><?php _e( 'Understanding the Status Report', 'redux-framework' ); ?></a></p>
            <div id="debug-report">
                    <textarea readonly="readonly"></textarea>
                    <p class="submit">
                        <button id="copy-for-support" class="button-primary redux-hint-qtip" href="#" qtip-content="<?php _e( 'Copied!', 'redux-framework' ); ?>"><?php _e( 'Copy for Support', 'redux-framework' ); ?></button>
                    </p>
            </div>
    </div>
    <br/>
    <table class="redux_status_table widefat" cellspacing="0" id="status">
        <thead>
            <tr>
                <th colspan="3" data-export-label="WordPress Environment"><?php _e( 'WordPress Environment', 'redux-framework' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td data-export-label="Home URL"><?php _e( 'Home URL', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The URL of your site\'s homepage.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo home_url(); ?></td>
            </tr>
            <tr>
                <td data-export-label="Site URL"><?php _e( 'Site URL', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The root URL of your site.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo site_url(); ?></td>
            </tr>
            <tr>
                <td data-export-label="Redux Version"><?php _e( 'Redux Version', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The version of Redux Framework installed on your site.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo esc_html( ReduxFramework::$_version ); ?></td>
            </tr>
            <tr>
                <td data-export-label="Redux Data Directory Writable"><?php _e( 'Redux Data Directory Writable', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Redux and its extensions write data to the <code>uploads</code> directory. This directory must be writable.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php
                    if ( @fopen( ReduxFramework::$_upload_dir . 'test-log.log', 'a' ) ) {
                        echo '<mark class="yes">' . '&#10004; <code>' . ReduxFramework::$_upload_dir . '</code></mark> ';
                    } else {
                        printf( '<mark class="error">' . '&#10005; ' . __( 'To allow data saving, make <code>%s</code> writable.', 'redux-framework' ) . '</mark>', ReduxFramework::$_upload_dir );
                    }
                ?></td>
            </tr>
            <tr>
                <td data-export-label="WP Version"><?php _e( 'WP Version', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The version of WordPress installed on your site.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php bloginfo('version'); ?></td>
            </tr>
            <tr>
                <td data-export-label="WP Multisite"><?php _e( 'WP Multisite', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php if ( is_multisite() ) echo '&#10004;'; else echo '&ndash;'; ?></td>
            </tr>
            <tr>
                <td data-export-label="Permalink Structure"><?php _e( 'Permalink Structure', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The current permalink structure as defined in Wordpress Settings->Permalinks.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default'; ?></td>
            </tr>
            <?php $sof = get_option( 'show_on_front' ); ?>
            <tr>
                <td data-export-label="Front Page Display"><?php _e( 'Front Page Display', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The current Reading mode of Wordpress.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo $sof; ?></td>
            </tr>
            
<?php   
            if ($sof == 'page') {
                $front_page_id = get_option( 'page_on_front' );
                $blog_page_id  = get_option( 'page_for_posts' );
?>
                <tr>
                    <td data-export-label="Front Page"><?php _e( 'Front Page', 'redux-framework' ); ?>:</td>
                    <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The currently selected page which acts as the site\'s Front Page.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                    <td><?php echo $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset'; ?></td>
                </tr>
                <tr>
                    <td data-export-label="Posts Page"><?php _e( 'Posts Page', 'redux-framework' ); ?>:</td>
                    <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The currently selected page in where blog posts are displayed.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                    <td><?php echo $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset'; ?></td>
                </tr>
<?php
            }
?>
            <tr>
                <td data-export-label="WP Memory Limit"><?php _e( 'WP Memory Limit', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php
                    $memory = redux_let_to_num( WP_MEMORY_LIMIT );

                    if ( $memory < 67108864 ) {
                        echo '<mark class="error">' . sprintf( __( '%s - We recommend setting memory to at least 64MB. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'redux-framework' ), size_format( $memory ), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) . '</mark>';
                    } else {
                        echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
                    }
                ?></td>
            </tr>
            <tr>
                <td data-export-label="Database Table Prefix"><?php _e( 'Database Table Prefix', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The prefix structure of the current Wordpress database.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo 'Length: ' . strlen( $wpdb->prefix ) . ' - Status: ' . ( strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable' )  ?></td>
            </tr>
            <tr>
                <td data-export-label="WP Debug Mode"><?php _e( 'WP Debug Mode', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo '<mark class="yes">' . '&#10004;' . '</mark>'; else echo '<mark class="no">' . '&ndash;' . '</mark>'; ?></td>
            </tr>
            <tr>
                <td data-export-label="Language"><?php _e( 'Language', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The current language used by WordPress. Default = English', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo get_locale() ?></td>
            </tr>
        </tbody>
    </table>
    
<?php
    if ( ! class_exists( 'Browser' ) ) {
        require_once ReduxFramework::$_dir . 'inc/browser.php';
    }

    $browser = new Browser();
?>
    <table class="redux_status_table widefat" cellspacing="0" id="status">
        <thead>
            <tr>
                <th colspan="3" data-export-label="Browser"><?php _e( 'Browser', 'redux-framework' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td data-export-label="Browser Info"><?php _e( 'Browser Info', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Information about web browser current in use.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo $browser; ?></td>
            </tr>
        </tbody>
    </table>
    
    <table class="redux_status_table widefat" cellspacing="0" id="status">
        <thead>
            <tr>
                <th colspan="3" data-export-label="Server Environment"><?php _e( 'Server Environment', 'redux-framework' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td data-export-label="Server Info"><?php _e( 'Server Info', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Information about the web server that is currently hosting your site.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?></td>
            </tr>
            <tr>
                <td data-export-label="PHP Version"><?php _e( 'PHP Version', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The version of PHP installed on your hosting server.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php if ( function_exists( 'phpversion' ) ) echo esc_html( phpversion() ); ?></td>
            </tr>
            <?php if ( function_exists( 'ini_get' ) ) { ?>
                    <tr>
                        <td data-export-label="PHP Safe Mode"><?php _e( 'PHP Safe Mode', 'redux-framework' ); ?>:</td>
                        <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Is PHP running in Safe Mode.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                        <td><?php 
                            if ( true == ini_get('safe_mode') ) {
                                echo '<mark class="yes">' . '&#10004;' . '</mark>';
                            } else {
                                echo '<mark class="no">' . '&ndash;' . '</mark>';
                            }                         
                        
                        echo size_format( ini_get('safe_mode') ); ?></td>
                    </tr>
                    <tr>
                        <td data-export-label="PHP Memory Limit"><?php _e( 'PHP Memory Limit', 'redux-framework' ); ?>:</td>
                        <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The largest filesize that can be contained in one post.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                        <td><?php echo size_format( redux_let_to_num( ini_get('memory_limit') ) ); ?></td>
                    </tr>
                    <tr>
                        <td data-export-label="PHP Post Max Size"><?php _e( 'PHP Post Max Size', 'redux-framework' ); ?>:</td>
                        <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The largest filesize that can be contained in one post.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                        <td><?php echo size_format( redux_let_to_num( ini_get('post_max_size') ) ); ?></td>
                    </tr>
                    <tr>
                        <td data-export-label="PHP Time Limit"><?php _e( 'PHP Time Limit', 'redux-framework' ); ?>:</td>
                        <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                        <td><?php echo ini_get('max_execution_time'); ?></td>
                    </tr>
                    <tr>
                        <td data-export-label="PHP Max Input Vars"><?php _e( 'PHP Max Input Vars', 'redux-framework' ); ?>:</td>
                        <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                        <td><?php echo ini_get('max_input_vars'); ?></td>
                    </tr>
                    <tr>
                        <td data-export-label="PHP Display Errors"><?php _e( 'PHP Display Errors', 'redux-framework' ); ?>:</td>
                        <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Determines if PHP will display errors within the browser.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                        <td><?php 
                            if ( true == ini_get('display_errors') ) {
                                echo '<mark class="yes">' . '&#10004;' . '</mark>';
                            } else {
                                echo '<mark class="no">' . '&ndash;' . '</mark>';
                            }                         
                        ?></td>
                    </tr>
                    <tr>
                        <td data-export-label="SUHOSIN Installed"><?php _e( 'SUHOSIN Installed', 'redux-framework' ); ?>:</td>
                        <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Suhosin is an advanced protection system for PHP installations. It was designed to protect your servers on the one hand against a number of well known problems in PHP applications and on the other hand against potential unknown vulnerabilities within these applications or the PHP core itself.  If enabled on your server, Suhosin may need to be configured to increase its data submission limits.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                        <td><?php echo extension_loaded( 'suhosin' ) ? '&#10004;' : '&ndash;'; ?></td>
                    </tr>
            <?php } ?>
            <tr>
                <td data-export-label="MySQL Version"><?php _e( 'MySQL Version', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The version of MySQL installed on your hosting server.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td>
<?php
                    echo $wpdb->db_version();
?>
                </td>
            </tr>
            <tr>
                <td data-export-label="Max Upload Size"><?php _e( 'Max Upload Size', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The largest filesize that can be uploaded to your WordPress installation.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo size_format( wp_max_upload_size() ); ?></td>
            </tr>
            <tr>
                <td data-export-label="Default Timezone is UTC"><?php _e( 'Default Timezone is UTC', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The default timezone for your server.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php
                    $default_timezone = date_default_timezone_get();
                    if ( 'UTC' !== $default_timezone ) {
                        echo '<mark class="error">' . '&#10005; ' . sprintf( __( 'Default timezone is %s - it should be UTC', 'redux-framework' ), $default_timezone ) . '</mark>';
                    } else {
                        echo '<mark class="yes">' . '&#10004;' . '</mark>';
                    } ?>
                </td>
            </tr>
<?php
            $posting = array();

            // fsockopen/cURL
            $posting['fsockopen_curl']['name'] = 'fsockopen/cURL';
            $posting['fsockopen_curl']['help'] = '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Payment gateways can use cURL to communicate with remote servers to authorize payments, other plugins may also use it when communicating with remote services.', 'redux-framework'  ) . '">[?]</a>';

            if ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) {
                $posting['fsockopen_curl']['success'] = true;
            } else {
                $posting['fsockopen_curl']['success'] = false;
                $posting['fsockopen_curl']['note']    = __( 'Your server does not have fsockopen or cURL enabled - PayPal IPN and other scripts which communicate with other servers will not work. Contact your hosting provider.', 'redux-framework' ). '</mark>';
            }

            // SOAP
            $posting['soap_client']['name'] = 'SoapClient';
            $posting['soap_client']['help'] = '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Some webservices like shipping use SOAP to get information from remote servers, for example, live shipping quotes from FedEx require SOAP to be installed.', 'redux-framework'  ) . '">[?]</a>';

            if ( class_exists( 'SoapClient' ) ) {
                $posting['soap_client']['success'] = true;
            } else {
                $posting['soap_client']['success'] = false;
                $posting['soap_client']['note']    = sprintf( __( 'Your server does not have the <a href="%s">SOAP Client</a> class enabled - some gateway plugins which use SOAP may not work as expected.', 'redux-framework' ), 'http://php.net/manual/en/class.soapclient.php' ) . '</mark>';
            }

            // DOMDocument
            $posting['dom_document']['name'] = 'DOMDocument';
            $posting['dom_document']['help'] = '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'HTML/Multipart emails use DOMDocument to generate inline CSS in templates.', 'redux-framework'  ) . '">[?]</a>';

            if ( class_exists( 'DOMDocument' ) ) {
                $posting['dom_document']['success'] = true;
            } else {
                $posting['dom_document']['success'] = false;
                $posting['dom_document']['note']    = sprintf( __( 'Your server does not have the <a href="%s">DOMDocument</a> class enabled - HTML/Multipart emails, and also some extensions, will not work without DOMDocument.', 'redux-framework' ), 'http://php.net/manual/en/class.domdocument.php' ) . '</mark>';
            }

            // GZIP
            $posting['gzip']['name'] = 'GZip';
            $posting['gzip']['help'] = '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'GZip (gzopen) is used to open the GEOIP database from MaxMind.', 'redux-framework'  ) . '">[?]</a>';

            if ( is_callable( 'gzopen' ) ) {
                $posting['gzip']['success'] = true;
            } else {
                $posting['gzip']['success'] = false;
                $posting['gzip']['note']    = sprintf( __( 'Your server does not support the <a href="%s">gzopen</a> function - this is required to use the GeoIP database from MaxMind. The API fallback will be used instead for geolocation.', 'redux-framework' ), 'http://php.net/manual/en/zlib.installation.php' ) . '</mark>';
            }

            // WP Remote Post Check
            $posting['wp_remote_post']['name'] = __( 'Remote Post', 'redux-framework');
            $posting['wp_remote_post']['help'] = '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'PayPal uses this method of communicating when sending back transaction information.', 'redux-framework'  ) . '">[?]</a>';

            $response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', array(
                'sslverify'  => false,
                'timeout'    => 60,
                'user-agent' => 'ReduxFramework/' . ReduxFramework::$_version,
                'body'       => array(
                    'cmd'    => '_notify-validate'
                )
            ) );

            if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
                $posting['wp_remote_post']['success'] = true;
            } else {
                $posting['wp_remote_post']['note']    = __( 'wp_remote_post() failed. PayPal IPN won\'t work with your server. Contact your hosting provider.', 'redux-framework' );
                
                if ( $response->get_error_message() ) {
                    $posting['wp_remote_post']['note'] .= ' ' . sprintf( __( 'Error: %s', 'redux-framework' ), rexux_clean( $response->get_error_message() ) );
                }
                
                $posting['wp_remote_post']['success'] = false;
            }

            // WP Remote Get Check
            $posting['wp_remote_get']['name'] = __( 'Remote Get', 'redux-framework');
            $posting['wp_remote_get']['help'] = '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Redux Framework plugins may use this method of communication when checking for plugin updates.', 'redux-framework'  ) . '">[?]</a>';

            $response = wp_remote_get( 'http://www.woothemes.com/wc-api/product-key-api?request=ping&network=' . ( is_multisite() ? '1' : '0' ) );

            if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
                $posting['wp_remote_get']['success'] = true;
            } else {
                $posting['wp_remote_get']['note']    = __( 'wp_remote_get() failed. The Redux Framework plugin updater won\'t work with your server. Contact your hosting provider.', 'redux-framework' );
                if ( $response->get_error_message() ) {
                        $posting['wp_remote_get']['note'] .= ' ' . sprintf( __( 'Error: %s', 'redux-framework' ), redux_clean( $response->get_error_message() ) );
                }
                
                $posting['wp_remote_get']['success'] = false;
            }

            $posting = apply_filters( 'redux_debug_posting', $posting );

            foreach ( $posting as $post ) {
                $mark = ! empty( $post['success'] ) ? 'yes' : 'error';
                ?>
                <tr>
                    <td data-export-label="<?php echo esc_html( $post['name'] ); ?>"><?php echo esc_html( $post['name'] ); ?>:</td>
                    <td><?php echo isset( $post['help'] ) ? $post['help'] : ''; ?></td>
                    <td class="help">
                        <mark class="<?php echo $mark; ?>">
                            <?php echo ! empty( $post['success'] ) ? '&#10004' : '&#10005'; ?>
                            <?php echo ! empty( $post['note'] ) ? wp_kses_data( $post['note'] ) : ''; ?>
                        </mark>
                    </td>
                </tr>
<?php
            }
?>
        </tbody>
    </table>
    <table class="redux_status_table widefat" cellspacing="0" id="status">
        <thead>
            <tr>
                <th colspan="3" data-export-label="Active Plugins (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)"><?php _e( 'Active Plugins', 'redux-framework' ); ?> (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)</th>
            </tr>
        </thead>
        <tbody>
<?php
            $active_plugins = (array) get_option( 'active_plugins', array() );

            if ( is_multisite() ) {
                $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
            }

            foreach ( $active_plugins as $plugin ) {
                $plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
                $dirname        = dirname( $plugin );
                $version_string = '';
                $network_string = '';

                if ( ! empty( $plugin_data['Name'] ) ) {
                    // link the plugin name to the plugin url if available
                    $plugin_name = esc_html( $plugin_data['Name'] );

                    if ( ! empty( $plugin_data['PluginURI'] ) ) {
                        $plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . __( 'Visit plugin homepage' , 'redux-framework' ) . '">' . $plugin_name . '</a>';
                    }
?>
                    <tr>
                        <td><?php echo $plugin_name; ?></td>
                        <td class="help">&nbsp;</td>
                        <td><?php echo sprintf( _x( 'by %s', 'by author', 'redux-framework' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?></td>
                    </tr>
<?php
                }
            }
            ?>
        </tbody>
    </table>
    
    <?php
    
    $redux = ReduxFrameworkInstances::get_all_instances();
    
    if (!empty($redux) && is_array($redux)) {
        foreach($redux as $inst => $data) {
            Redux::init ( $inst );
?>
            <table class="redux_status_table widefat" cellspacing="0" id="status">
                <thead>
                    <tr>
                        <th colspan="3" data-export-label="Redux Instance: <?php echo $inst; ?>"><?php _e( 'Redux Instance: ', 'redux-framework' ); echo $inst; ?></th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td data-export-label="opt_name">opt_name:</td>
                    <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The opt_name argument for this instance of Redux.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                    <td><?php echo $data->args['opt_name']; ?></td>
                </tr>
                <tr>
                    <td data-export-label="dev_mode">dev_mode:</td>
                    <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Indicates if dev_mode is enabled for this instance of Redux.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                    <td><?php echo true == $data->args['dev_mode'] ? '<mark class="yes">'.'&#10004;'.'</mark>' : '<mark class="no">'.'&ndash;'.'</mark>'; ?></td>
                </tr>            
                <tr>
                    <td data-export-label="ajax_save">ajax_save:</td>
                    <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Indicates if ajax_save is enabled for this instance of Redux.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                    <td><?php echo true == $data->args['ajax_save'] ? '<mark class="yes">'.'&#10004;'.'</mark>' : '<mark class="no">'.'&ndash;'.'</mark>'; ?></td>
                </tr>
<?php
                if (isset($data->args['templates_path']) && $data->args['templates_path'] != '') {
?>
                    <tr>
                        <td data-export-label="template_path">template_path:</td>
                        <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The specified template path for this instance of Redux.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                        <td><?php echo '<code>' . $data->args['templates_path'] . '</code>'; ?></td>
                    </tr>
                    <tr>
                        <td data-export-label="Templates">Templates:</td>
                        <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The specified template path for this instance of Redux.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
<?php                        
                        $template_paths     = array( 'ReduxFramework' => ReduxFramework::$_dir . 'templates/panel' );
                        $scanned_files      = array();
                        $found_files        = array();
                        $outdated_templates = false;

                        foreach ( $template_paths as $plugin_name => $template_path ) {
                            $scanned_files[ $plugin_name ] = redux_scan_template_files( $template_path );
                        }

                        foreach ( $scanned_files as $plugin_name => $files ) {
                            foreach ( $files as $file ) {
                                if ( file_exists( $data->args['templates_path'] . '/' . $file ) ) {
                                    $theme_file = $data->args['templates_path'] . '/' . $file;
                                } else {
                                    $theme_file = false;
                                }

                                if ( $theme_file ) {
                                    $core_version  = redux_get_file_version( ReduxFramework::$_dir . 'templates/panel/' . $file );
                                    $theme_version = redux_get_file_version( $theme_file );

                                    if ( $core_version && ( empty( $theme_version ) || version_compare( $theme_version, $core_version, '<' ) ) ) {
                                        if ( ! $outdated_templates ) {
                                            $outdated_templates = true;
                                        }

                                        $found_files[ $plugin_name ][] = sprintf( __( '<code>%s</code> version <strong style="color:red">%s</strong> is out of date. The core version is %s', 'redux-framework' ), str_replace( WP_CONTENT_DIR . '/themes/', '', $theme_file ), $theme_version ? $theme_version : '-', $core_version );
                                    } else {
                                        $found_files[ $plugin_name ][] = sprintf( '<code>%s</code>', str_replace( WP_CONTENT_DIR . '/themes/', '', $theme_file ) );
                                    }
                                }
                            }
                        }       
                        
                        if ( $found_files ) {
                            foreach ( $found_files as $plugin_name => $found_plugin_files ) {
?>
                                <td><?php echo implode( ', <br/>', $found_plugin_files ); ?></td>
<?php
                            }
                        } else {
?>
                            <td>&ndash;</td>
<?php
                        }                        
?>                   
                    </tr>
<?php
                }
                
                $ext = Redux::getExtensions ( '', $inst );
                if (!empty($ext) && is_array($ext)) {
?>
                    <tr>
                        <td data-export-label="Extensions">Extensions</td>
                        <td></td>
                        <td>
<?php
                            foreach($ext as $name => $arr) {
                                $ver = Redux::getFileVersion ( $arr['path'] );
?>
                                <?php echo ucwords(str_replace('_', ' ', $name)) . ' - ' . $ver;  ?><br/>
<?php              
                            }
?>
                        </td>
                    </tr>
<?php
                }
?>
            </tbody>
        </table>
<?php
        }
    }
?>
    <table class="redux_status_table widefat" cellspacing="0" id="status">
	<thead>
            <tr>
                <th colspan="3" data-export-label="Theme"><?php _e( 'Theme', 'redux-framework' ); ?></th>
            </tr>
	</thead>
        <?php $active_theme = wp_get_theme(); ?>
	<tbody>
            <tr>
                <td data-export-label="Name"><?php _e( 'Name', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The name of the current active theme.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo $active_theme->Name; ?></td>
            </tr>
            <tr>
                <td data-export-label="Version"><?php _e( 'Version', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The installed version of the current active theme.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php
                    echo $active_theme->Version;

                    if ( ! empty( $theme_version_data['version'] ) && version_compare( $theme_version_data['version'], $active_theme->Version, '!=' ) ) {
                        echo ' &ndash; <strong style="color:red;">' . $theme_version_data['version'] . ' ' . __( 'is available', 'redux-framework' ) . '</strong>';
                    }
                ?></td>
            </tr>
            <tr>
                <td data-export-label="Author URL"><?php _e( 'Author URL', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The theme developers URL.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php echo $active_theme->{'Author URI'}; ?></td>
            </tr>
            <tr>
                <td data-export-label="Child Theme"><?php _e( 'Child Theme', 'redux-framework' ); ?>:</td>
                <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'Displays whether or not the current theme is a child theme.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                <td><?php
                    echo is_child_theme() ? '<mark class="yes">' . '&#10004;' . '</mark>' : '&#10005; &ndash; ' . sprintf( __( 'If you\'re modifying Redux Framework or a parent theme you didn\'t build personally we recommend using a child theme. See: <a href="%s" target="_blank">How to create a child theme</a>', 'redux-framework' ), 'http://codex.wordpress.org/Child_Themes' );
                ?></td>
            </tr>
<?php
            if( is_child_theme() ) {
                $parent_theme = wp_get_theme( $active_theme->Template );
?>
                <tr>
                    <td data-export-label="Parent Theme Name"><?php _e( 'Parent Theme Name', 'redux-framework' ); ?>:</td>
                    <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The name of the parent theme.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                    <td><?php echo $parent_theme->Name; ?></td>
                </tr>
                <tr>
                    <td data-export-label="Parent Theme Version"><?php _e( 'Parent Theme Version', 'redux-framework' ); ?>:</td>
                    <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The installed version of the parent theme.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                    <td><?php echo  $parent_theme->Version; ?></td>
                </tr>
                <tr>
                    <td data-export-label="Parent Theme Author URL"><?php _e( 'Parent Theme Author URL', 'redux-framework' ); ?>:</td>
                    <td class="help"><?php echo '<a href="#" class="redux-hint-qtip" qtip-content="' . esc_attr__( 'The parent theme developers URL.', 'redux-framework'  ) . '">[?]</a>'; ?></td>
                    <td><?php echo $parent_theme->{'Author URI'}; ?></td>
                </tr>
            <?php } ?>
	</tbody>
    </table>
    <script type="text/javascript">
        jQuery( 'a.redux-hint-qtip' ).click( function() {
            return false;
        });

        jQuery( 'a.debug-report' ).click( function() {
            var report = '';

            jQuery( '#status thead, #status tbody' ).each(function(){
                if ( jQuery( this ).is('thead') ) {
                    var label = jQuery( this ).find( 'th:eq(0)' ).data( 'export-label' ) || jQuery( this ).text();
                    report = report + "\n### " + jQuery.trim( label ) + " ###\n\n";
                } else {
                    jQuery('tr', jQuery( this ) ).each(function(){
                        var label       = jQuery( this ).find( 'td:eq(0)' ).data( 'export-label' ) || jQuery( this ).find( 'td:eq(0)' ).text();
                        var the_name    = jQuery.trim( label ).replace( /(<([^>]+)>)/ig, '' ); // Remove HTML
                        var the_value   = jQuery.trim( jQuery( this ).find( 'td:eq(2)' ).text() );
                        var value_array = the_value.split( ', ' );

                        if ( value_array.length > 1 ) {
                            // If value have a list of plugins ','
                            // Split to add new line
                            var output = '';
                            var temp_line ='';
                            jQuery.each( value_array, function( key, line ){
                                temp_line = temp_line + line + '\n';
                            });

                            the_value = temp_line;
                        }

                        report = report + '' + the_name + ': ' + the_value + "\n";
                    });
                }
            });

            try {
                jQuery( "#debug-report" ).slideDown();
                jQuery( "#debug-report textarea" ).val( report ).focus().select();
                jQuery( this ).fadeOut();

                return false;
            } catch( e ){
                console.log( e );
            }

            return false;
        });

        jQuery( document ).ready( function ( $ ) {
            $( 'body' ).on( 'copy', '#copy-for-support', function ( e ) {
                e.clipboardData.clearData();
                e.clipboardData.setData( 'text/plain', $( '#debug-report textarea' ).val() );
                e.preventDefault();
            });
        });
    </script>
</div>