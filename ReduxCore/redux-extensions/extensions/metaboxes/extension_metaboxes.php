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
 * @author      Dovy Paukstys (dovy)
 * @version     1.1.8
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'ReduxFramework_extension_metaboxes' ) ) {

    /**
     * Main ReduxFramework customizer extension class
     *
     * @since       1.0.0
     */
    class ReduxFramework_extension_metaboxes {

        static $version = "1.2.0";

        public $boxes = array();
        public $post_types = array();
        public $post_type;
        public $sections = array();
        public $output = array();
        private $parent;
        public $options = array();
        public $parent_options = array();
        public $parent_defaults = array();
        public $wp_links = array();
        public $options_defaults = array();
        public $localize_data = array();
        public $toReplace = array();
        public $_extension_url;
        public $_extension_dir;
        public $meta = array();
        public $post_id = 0;
        public $base_url;

        public function __construct( $parent ) {

            $this->parent = $parent;


            $this->parent->extensions['metaboxes'] = $this;

            if ( empty( self::$_extension_dir ) ) {
                $this->_extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->_extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->_extension_dir ) );
            }

            // Only run metaboxes on the pages/posts, not the front-end
            global $pagenow;
            if ( $pagenow !== "post-new.php" && $pagenow !== "post.php" ) {
                if ( is_admin() ) {
                    return;
                }

            }
            // Must not update the DB when just updating metaboxes. Sheesh.
            if ( is_admin() && ( $pagenow == "post-new.php" || $pagenow == "post.php" ) ) {
                $this->parent->never_save_to_db = true;
            }

            //add_filter( 'admin_footer_text', array( &$this, 'admin_footer_text' ) );


            add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
            add_action( 'save_post', array( $this, 'meta_boxes_save' ), 1, 2 );
            add_action( 'pre_post_update', array( $this, 'pre_post_update' ) );
            add_action( 'admin_notices', array( $this, 'meta_boxes_show_errors' ), 0 );

            add_action( 'admin_enqueue_scripts', array( $this, '_enqueue' ), 20 );

            // Fix for wp-seo embedding an old version (RC3) of qtip. Bah.
            add_action( 'wp_print_scripts', array( $this, 'FIX_wp_seo'), 100 );
            add_action( 'admin_enqueue_scripts', array( $this, 'FIX_wp_seo'), 100 );

            // Global variable overrides for within loops
            add_action( 'the_post', array( $this, '_loop_start' ), 0 );
            add_action( 'loop_end', array( $this, '_loop_end' ), 0 );


            //add_action( 'load_textdomain', array( $this, 'init' ), 0 );

            $this->init();

        } // __construct()

        public function init() {
            global $pagenow;

            $this->boxes = apply_filters('redux/metaboxes/'.$this->parent->args['opt_name'].'/boxes',$this->boxes);

            if ( empty( $this->boxes ) ) {
                return; // Don't do it! There's nothing here.
            }

            $this->base_url = 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            $this->post_id = $this->url_to_postid( 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] );
            if (is_admin() && isset($_GET['post_type']) && !empty($_GET['post_type'])) {
                $this->post_type = $_GET['post_type'];
            } else {
                $this->post_type = get_post_type( $this->post_id );
            }



            foreach( $this->boxes as $bk => $box ) {
                // If the post ids for this box are set, we're limiting to the current post id
                if ( isset( $box['post_ids'] ) && !empty( $box['post_ids'] ) ) {
                    if ( !is_array( $box['post_ids'] ) ) {
                        $box['post_ids'] = array( $box['post_ids'] );
                    }
                    if ( !in_array( $this->post_id, $box['post_ids'] ) ) {
                        continue;
                    }

                }
                if ( !empty( $box['sections'] ) ) {
                    $this->sections[] = $box['sections'];
                    array_merge($this->parent->sections, $box['sections']);

                    $this->post_types = wp_parse_args($this->post_types, $box['post_types']);

                    if ( is_admin() && ( $pagenow == "post-new.php" || $pagenow == "post.php" ) ) {
                        // NEST IN THE BOX ID

                    }

                    // Checking to overide the parent variables
                    $addField = false;

                    foreach($box['post_types'] as $type) {
                        if ($this->post_type == $type) {
                            $addField = true;
                        }
                    }

                    // Replacing all the fields
                    if ( $addField
                        || (
                            ( is_admin() && ( $pagenow == "post-new.php" || $pagenow == "post.php" ) )
                            ||
                            ( !is_admin() )
                        )
                    ) {
                        $runHooks = true;

                        $boxID = 'redux-'.$this->parent->args['opt_name'].'-metabox-'.$box['id'];

                        if (isset($box['page_template']) && $this->post_type == "page") {
                            if (!is_array($box['page_template'])) {
                                $box['page_template'] = array($box['page_template']);
                            }
                            $this->wp_links[$boxID]['page_template'] = isset($this->wp_links[$boxID]['page_template']) ? wp_parse_args($this->wp_links['page_template'], $box['page_template']) : $box['page_template'];
                        }
                        if (isset($box['post_format']) && ($this->post_type == "post" || $this->post_type == "")) {
                            if (!is_array($box['post_format'])) {
                                $box['post_format'] = array($box['post_format']);
                            }
                            $this->wp_links[$boxID]['post_format'] = isset($this->wp_links[$boxID]['post_format']) ? wp_parse_args($this->wp_links['post_format'], $box['post_format']) : $box['post_format'];
                        }

                        $this->meta[$this->post_id] = $this->get_meta( $this->post_id );

                        //$this->parent->options = wp_parse_args($this->meta[$this->post_id], $this->parent->options);
                        foreach($box['sections'] as $sk => $section) {
                            if ( isset( $section['fields'] ) && !empty( $section['fields'] ) ) {
                                foreach($section['fields'] as $fk => $field) {
                                    if (!isset($field['class'])) {
                                        $field['class'] = "";
                                        $this->boxes[$bk]['sections'][$sk]['fields'][$fk] = $field;
                                    }
                                    if ( $addField
                                        || (
                                            ( is_admin() && ( $pagenow == "post-new.php" || $pagenow == "post.php" ) )
                                            ||
                                            ( !is_admin() )
                                        )
                                    ) {

                                        $this->options_defaults[$field['id']] = $this->_field_default($field);
                                        
                                        if (empty($field['id'])) {
                                            continue;
                                        }

                                        if (isset($field['output']) && !empty($field['output']) ) {
                                            $this->output[$field['id']] = isset($this->output[$field['id']]) ? array_merge($field['output'], $this->output[$field['id']]) : $field['output'];
                                        }

                                        // Detect what field types are being used
                                        if ( !isset( $this->parent->fields[$field['type']][$field['id']] ) ) {
                                            $this->parent->fields[$field['type']][$field['id']] = 1;
                                        } else {
                                            $this->parent->fields[$field['type']] = array($field['id'] => 1);
                                        }

                                        if (!isset($this->options_defaults[$field['id']])) {
                                            $this->toReplace[$field['id']] = $field;
                                        }

                                        if( isset( $field['default'] ) ) {
                                            $this->options_defaults[$field['id']] = $field['default'];
                                        } else {
                                            $field['default'] = $this->options_defaults[$field['id']];
                                        }
//print_r($field);                                        
                                        //} elseif (isset($field['options'])) {
                                            //$this->options_defaults[$field['id']] = $field['options'];
                                        //}

                                        if ( !isset( $this->parent->options[$field['id']] ) ) {
                                            $this->parent->sections[(count($this->parent->sections)-1)]['fields'][] = $field;
                                        }

                                        if (!isset($this->meta[$this->post_id][$field['id']]) && isset($this->options_defaults[$field['id']])) {
                                            $this->meta[$this->post_id][$field['id']] = $this->options_defaults[$field['id']];
                                        }

                                        //if (!isset($this->meta[$this->post_id][$field['id']]) && isset($this->options_defaults[$field['id']])) {
                                        //$this->meta[$this->post_id][$field['id']] = $this->options_defaults[$field['id']];
                                        //}

                                        //$setDefaults = false;
                                        //if (!isset($this->parent->options[$field['id']]) || $this->parent->options[$field['id']] == $this->parent->options_defaults[$field['id']]) {
                                        //    $setDefaults = true;
                                        //}

                                        //if (!isset($this->meta[$this->post_id][$field['id']])) {
                                        //    $this->meta[$this->post_id][$field['id']][$field['id']] = $this->parent->options_defaults[$field['id']];
                                        //    $this->parent->options[$field['id']] = $this->parent->options_defaults[$field['id']];
                                        //}

                                        //if ( !empty($this->meta[$this->post_id]) && isset( $this->meta[$this->post_id][$field['id']] ) ) {
                                        //$this->parent->options[$field['id']] = $this->meta[$this->post_id][$field['id']];
                                        //}
                                        /*
                                        if( isset( $field['default'] ) ) {
                                            $this->parent->options_defaults[$field['id']] = $field['default'];
                                        } elseif (isset($field['options'])) {
                                            $this->parent->options_defaults[$field['id']] = $field['options'];
                                        }
                                        if (isset($setDefaults) && isset($field['default'])) {
                                            $this->parent->options[$field['id']] = $this->parent->options_defaults[$field['id']];
                                        }
                                        */



                                        // Only override if it exists and it's not the default
                                        if (isset($this->meta[$this->post_id][$field['id']]) && isset($field['default']) && $this->meta[$this->post_id][$field['id']] == $field['default']) {
                                            //unset($this->meta[$this->post_id][$field['id']]);
                                        }

                                    }
                                }
                            }
                        }

                    }
                }

            }
            if ( isset( $runHooks ) && $runHooks == true ) {

                //$this->parent->get_options();
                $this->parent_options = ''; //$this->parent->options;

                if ( !empty( $this->toReplace ) ) {
                    foreach( $this->toReplace as $id => $field ) {
                        add_filter("redux/options/{$this->parent->args['opt_name']}/field/{$id}/register", array($this, 'replace_field'));
                    }
                }

                //$this->parent->options_defaults = wp_parse_args($this->options_defaults, $this->parent->options_defaults);

                // Override the defaults, the but options have already been grabbed
                //add_filter( "redux/options/{$this->parent->args['opt_name']}/defaults", array( $this, '_override_defaults' ) );

                //add_filter( "redux/options/{$this->args['opt_name']}/defaults", '' );
                add_filter( "redux/options/{$this->parent->args['opt_name']}/options", array( $this, '_override_options' ) );

                add_filter( "redux/field/{$this->parent->args['opt_name']}/_can_output_css", array( $this, '_override_can_output_css' ) );

                add_filter( "redux/field/{$this->parent->args['opt_name']}/output_css", array( $this, '_output_css' ) );

                //add_filter( "redux/options/{$this->parent->args['opt_name']}/global_variable", array( $this, '_override_values' ) );

            }

        }

        function replace_field($field) {
            if ( isset( $this->toReplace[$field['id']] ) ) {
                $field = $this->toReplace[$field['id']];
            }
            return $field;
        }

        function _override_can_output_css($field) {

            if (isset($this->output[$field['id']])) {
                $field['force_output'] = true;
            }
            return $field;
        }

        function _output_css($field) {

            if (isset($this->output[$field['id']])) {
                $field['output'] = $this->output[$field['id']];
            }
            return $field;
        }

        // Fix for wp-seo embedding an old version (RC3) of qtip. Bah.
        public function FIX_wp_seo() {
            wp_dequeue_script( 'jquery-qtip' );
            wp_deregister_script( 'jquery-qtip' );
        }

        // DEPRECATED, just for storage
        public function _override_defaults($defaults) {
            $defaults = wp_parse_args($this->options_defaults, $defaults);
            foreach($this->options_defaults as $field => $value) {
                if (!isset($this->parent->options[$field]) || ( !isset($defaults[$field]) || !isset($this->parent->options[$field]) || $defaults[$field] == $this->parent->options[$field]   )) {
                    $this->parent->options[$field] = $value;
                    $this->meta[$field] = $value;
                }
            }
            return wp_parse_args($this->options_defaults, $defaults);
        }

        // Make sure the defaults are the defaults
        public function _override_options($options) {

            $this->parent->_default_values();
            $this->parent_defaults = $this->parent->options_defaults;

            $meta = $this->get_meta($this->post_id);
            //print_r($meta);
            $data = wp_parse_args($meta, $this->options_defaults);
            foreach($data as $key => $value) {
                if (isset($meta[$key])) {
                    $data[$key] = $meta[$key];
                    continue;
                }

                if (isset($options[$key])) {
                    //if ( isset($options[$key]) && isset($this->parent->options_defaults[$key]) && $options[$key] != $this->parent->options_defaults[$key]) {
                    if ( isset($options[$key]) ) {
                        $data[$key] = $options[$key];
                    }
                }
            }
            $this->parent->options_defaults = wp_parse_args($this->options_defaults, $this->parent->options_defaults);

            $options = wp_parse_args($data, $options);

            return $options;
        }

        public function _loop_start( $the_post = array() ) {

            if ( is_admin() ) {
                return $the_post;
            }

            if ( isset( $the_post ) && is_array( $the_post ) ) {
                global $post;
                $the_post = $post;
            }

            //echo "POST ID: ".$the_post->ID;

            if ( isset( $GLOBALS[$this->parent->args['opt_name'].'-loop'] ) ) {
                $GLOBALS[$this->parent->args['opt_name']] = $GLOBALS[$this->parent->args['opt_name'].'-loop'];
                unset($GLOBALS[$this->parent->args['opt_name'].'-loop']);
            }
// Override these values if they differ from the admin panel defaults.  ;)
            if ( in_array( $the_post->post_type, $this->post_types ) ) {
                $meta = $this->get_meta( $the_post->ID );
                if ( empty( $meta ) ) {
                    return;
                }
                // Backup the args
                $GLOBALS[$this->parent->args['opt_name'] . '-loop'] = $GLOBALS[$this->parent->args['opt_name']];
                $GLOBALS[$this->parent->args['opt_name']] = wp_parse_args( $meta, $GLOBALS[$this->parent->args['opt_name'] . '-loop'] );
            }
        }

        public function _loop_end() {
            if ( isset( $GLOBALS[$this->parent->args['opt_name'].'-loop'] ) ) {
                $GLOBALS[$this->parent->args['opt_name']] = $GLOBALS[$this->parent->args['opt_name'].'-loop'];
                unset($GLOBALS[$this->parent->args['opt_name'].'-loop']);
            }
        }

        public function _enqueue() {
            //$screen = get_current_screen();
            //print_r($screen->post_type);
            global $pagenow;

            $types = array();
            // Enqueue css
            foreach ($this->boxes as $key => $box) {
                if ( empty( $box['sections'] ) ) {
                    continue;
                }
                if ( isset( $box['post_types'] ) ) {
                    $types = array_merge($box['post_types'], $types);
                }
                if ( isset( $box['post_types'] ) && !empty( $box['post_types'] ) ) {
                    if ( !is_array( $box['post_types'] ) ) {
                        $box['post_types'] = array( $box['post_types'] );
                        $this->boxes[$key]['post_types'] = $box['post_types'];
                    }
                }
            }


            if ( $pagenow == "post-new.php" || $pagenow == "post.php" ) {
                global $post;
                if ( in_array( $post->post_type, $types ) ) {
                    $this->parent->transients = $this->parent->transients_check = get_transient( $this->parent->args['opt_name'] . '-transients-metaboxes' );

                    if (isset($this->parent->transients['notices'])) {
                        $this->notices = $this->parent->transients['notices'];
                        $this->parent->transients['last_save_mode'] = "metaboxes";
                    }

                    delete_transient( $this->parent->args['opt_name'] . '-transients-metaboxes' );
                    $this->parent->_enqueue();

                    do_action( "redux/metaboxes/{$this->parent->args['opt_name']}/enqueue" );

                    /**
                     * Redux metaboxes CSS
                     * filter 'redux/page/{opt_name}/enqueue/redux-extension-metaboxes-css'
                     * @param string  bundled stylesheet src
                     */
                    wp_enqueue_style(
                        'redux-extension-metaboxes-css',
                        apply_filters( "redux/metaboxes/{$this->parent->args['opt_name']}/enqueue/redux-extension-metaboxes-css", $this->_extension_url . 'extension_metaboxes.css' ),
                        '',
                        filemtime( $this->_extension_dir . 'extension_metaboxes.css' ), // todo - version should be based on above post-filter src
                        'all'
                    );

                    /**
                     * Redux metaboxes JS
                     * filter 'redux/page/{opt_name}/enqueue/redux-extension-metaboxes-js
                     * @param string  bundled javscript
                     */
                    $min = ( $this->parent->args['dev_mode'] ) ? '.min' : '';
                    wp_enqueue_script(
                        'redux-extension-metaboxes-js',
                        apply_filters( "redux/metaboxes/{$this->parent->args['opt_name']}/enqueue/redux-extension-metaboxes-js", $this->_extension_url . 'extension_metaboxes'.$min.'.js' ),
                        '',
                        filemtime( $this->_extension_dir . 'extension_metaboxes.js' ), // todo - version should be based on above post-filter src
                        'all'
                    );
                    // Values used by the javascript
                    wp_localize_script(
                        'redux-extension-metaboxes-js',
                        'reduxMetaboxes',
                        $this->wp_links
                    );
                }

            }

        } // _enqueue()   

        /* Post URLs to IDs function, supports custom post types - borrowed and modified from url_to_postid() in wp-includes/rewrite.php */
        // Taken from http://betterwp.net/wordpress-tips/url_to_postid-for-custom-post-types/
        // Customized to work with non-rewrite URLs
        // Copyright Dovy Paukstys (@dovy) of Redux Framework
        function url_to_postid($url) {
            global $wp_rewrite;

            if ( !empty( $this->post_id ) ) {
                return $this->post_id;
            }

            if (isset($_GET['post'])&& !empty($_GET['post']) && is_numeric($_GET['post'])) {
                return $_GET['post'];
            }

            // First, check to see if there is a 'p=N' or 'page_id=N' to match against
            if ( preg_match('#[?&](p|page_id|attachment_id)=(\d+)#', $url, $values) )   {
                $id = absint($values[2]);
                if ( $id ) {
                    return $id;
                }
            }

            // Check to see if we are using rewrite rules
            if ( isset( $wp_rewrite ) ) {
                $rewrite = $wp_rewrite->wp_rewrite_rules();
            }


            // Not using rewrite rules, and 'p=N' and 'page_id=N' methods failed, so we're out of options
            if ( empty( $rewrite ) ) {
                if ( isset( $_GET ) && !empty( $_GET ) ) {
                    //print_r($GLOBALS['wp_post_types']);
                    //if (isset($GLOBALS['wp_post_types']['acme_product']))
                    // Add custom rules for non-rewrite URLs
                    foreach( $GLOBALS['wp_post_types'] as $key => $value ) {
                        if ( isset( $_GET[$key] ) && !empty( $_GET[$key] ) ) {
                            $args = array(
                                'name'        => $_GET[$key],
                                'post_type'   => $key,
                                'showposts'   => 1,
                            );
                            if( $post = get_posts( $args ) ) {
                                return $post[0]->ID;
                            }
                        }
                    }
                }
            }

            // Get rid of the #anchor
            $url_split = explode( '#', $url );
            $url = $url_split[0];

            // Get rid of URL ?query=string
            $url_query = explode( '?', $url );
            $url = $url_query[0];


            // Add 'www.' if it is absent and should be there
            if ( false !== strpos( home_url(), '://www.' ) && false === strpos( $url, '://www.' ) ) {
                $url = str_replace( '://', '://www.', $url );
            }

            // Strip 'www.' if it is present and shouldn't be
            if ( false === strpos( home_url(), '://www.' ) ) {
                $url = str_replace('://www.', '://', $url);
            }

            // Strip 'index.php/' if we're not using path info permalinks
            if ( isset( $wp_rewrite ) && !$wp_rewrite->using_index_permalinks() ) {
                $url = str_replace('index.php/', '', $url);
            }

            if ( false !== strpos( $url, home_url() ) ) {
                // Chop off http://domain.com
                $url = str_replace(home_url(), '', $url);
            } else {
                // Chop off /path/to/blog
                $home_path = parse_url(home_url());
                $home_path = isset( $home_path['path'] ) ? $home_path['path'] : '' ;
                $url = str_replace($home_path, '', $url);
            }

            // Trim leading and lagging slashes
            $url = trim( $url, '/' );

            $request = $url;
            if ( empty( $request ) && ( !isset( $_GET ) || empty( $_GET ) ) ) {
                return get_option('page_on_front');
            }

            // Look for matches.
            $request_match = $request;


            foreach ( (array)$rewrite as $match => $query) {

                // If the requesting file is the anchor of the match, prepend it
                // to the path info.
                if ( !empty( $url ) && ( $url != $request ) && ( strpos( $match, $url ) === 0 ) )  {
                    $request_match = $url . '/' . $request;
                }

                if ( preg_match( "!^$match!", $request_match, $matches ) ) {

                    // Got a match.
                    // Trim the query of everything up to the '?'.
                    $query = preg_replace( "!^.+\?!", '', $query );

                    // Substitute the substring matches into the query.
                    $query = addslashes( WP_MatchesMapRegex::apply( $query, $matches ) );

                    // Filter out non-public query vars
                    global $wp;
                    parse_str( $query, $query_vars );
                    $query = array();
                    foreach ( (array) $query_vars as $key => $value ) {
                        if ( in_array( $key, $wp->public_query_vars ) ) {
                            $query[$key] = $value;
                        }
                    }

                    // Taken from class-wp.php
                    foreach ( $GLOBALS['wp_post_types'] as $post_type => $t ) {
                        if ( $t->query_var ) {
                            $post_type_query_vars[$t->query_var] = $post_type;
                        }
                    }

                    foreach ( $wp->public_query_vars as $wpvar ) {
                        if ( isset( $wp->extra_query_vars[$wpvar] ) ) {
                            $query[$wpvar] = $wp->extra_query_vars[$wpvar];
                        } elseif ( isset( $_POST[$wpvar] ) ) {
                            $query[$wpvar] = $_POST[$wpvar];
                        } elseif ( isset( $_GET[$wpvar] ) ) {
                            $query[$wpvar] = $_GET[$wpvar];
                        } elseif ( isset( $query_vars[$wpvar] ) ) {
                            $query[$wpvar] = $query_vars[$wpvar];
                        }


                        if ( !empty( $query[$wpvar] ) ) {
                            if ( ! is_array( $query[$wpvar] ) ) {
                                $query[$wpvar] = (string) $query[$wpvar];
                            } else {
                                foreach ( $query[$wpvar] as $vkey => $v ) {
                                    if ( !is_object( $v ) ) {
                                        $query[$wpvar][$vkey] = (string) $v;
                                    }
                                }
                            }

                            if ( isset( $post_type_query_vars[$wpvar] ) ) {
                                $query['post_type'] = $post_type_query_vars[$wpvar];
                                $query['name'] = $query[$wpvar];
                            }
                        }
                    }
                    // Do the query
                    if (isset($query['pagename']) && !empty($query['pagename'])) {
                        $args = array(
                            'name'        => $query['pagename'],
                            'post_type'   => 'page',
                            'showposts'   => 1,
                        );
                        if( $post = get_posts( $args ) ) {
                            return $post[0]->ID;
                        }
                    }
                    $query = new WP_Query($query);

                    if ( !empty($query->posts) && $query->is_singular )
                        return $query->post->ID;
                    else {
                        return 0;
                    }

                }
            }

            return 0;
        }

        // DEPRECATED
        public function _override_values( $options ) {
            // Override the global defaults
            $options = wp_parse_args($this->meta[$this->post_id], $options);

            $this->parent->options = wp_parse_args($options, $this->parent->options);

            return $options;

        } // _override_values()

        // DEPRECATED
        public function _default_values() {
            if( !empty( $this->boxes ) && empty( $this->options_defaults ) ) {
                foreach ($this->boxes as $key => $box) {
                    if ( empty( $box['sections'] ) ) {
                        continue;
                    }
                    // fill the cache
                    foreach( $box['sections'] as $sk => $section ) {
                        if (!isset($section['id'])) {
                            if (!is_numeric($sk) || !isset($section['title'])) {
                                $section['id'] = $sk;
                            } else {
                                $section['id'] = sanitize_title( $section['title'], $sk );
                            }
                            $this->boxes[$key]['sections'][$sk] = $section;
                        }
                        if( isset( $section['fields'] ) ) {
                            foreach( $section['fields'] as $k => $field ) {
                                if (empty($field['id'])) {
                                    continue;
                                }
                                //$this->parent->used_fields[$field['type']] = isset($this->parent->used_fields[$field['type']]) ? $this->parent->used_fields[$field['type']]++ : 1;

                                if ($field['type'] == "section" && $field['indent'] == "true") {
                                    $field['class'] = isset($field['class']) ? $field['class'] : '';
                                    $field['class'] .= "redux-section-indent-start";
                                    $this->boxes[$key]['sections'][$sk]['fields'][$k] = $field;
                                }
                                // Detect what field types are being used
                                if ( !isset( $this->fields[$field['type']][$field['id']] ) ) {
                                    $this->parent->fields[$field['type']][$field['id']] = 1;
                                } else {
                                    $this->parent->fields[$field['type']] = array($field['id'] => 1);
                                }
                                if( isset( $field['default'] ) ) {
                                    $this->options_defaults[$field['id']] = $field['default'];
                                } elseif (isset($field['options'])) {
                                    $this->options_defaults[$field['id']] = $field['options'];
                                }

                            }
                        }
                    }
                }
            }



            //$this->options_defaults = apply_filters( 'redux/metabox/'.$this->parent->args['opt_name'].'/defaults', $this->options_defaults );

            if ( empty( $this->meta[$this->post_id] ) ) {
                $this->meta[$this->post_id] = $this->get_meta( $this->post_id );
            }

            // Add the defaults to the current meta
            $this->meta[$this->post_id] = wp_parse_args( $this->meta[$this->post_id], $this->parent->options_defaults );

        } // _default_values()


        public function add_meta_boxes() {
            //echo "add_meta_boxes()";

            if ( empty( $this->boxes ) || !is_array( $this->boxes ) ) {
                return;
            }

            foreach ($this->boxes as $key => $box) {
                if ( empty( $box['sections'] ) ) {
                    continue;
                }
                
                // Save users from themselves
                if ( isset( $box['position'] ) && !in_array( strtolower( $box['position'] ), array( 'normal', 'advanced', 'side' ) ) ) {
                    unset( $box['position'] );
                }
                
                if ( isset( $box['priority'] ) && !in_array( strtolower( $box['priority'] ), array( 'high', 'core', 'default', 'low' ) ) ) {
                    unset( $box['priority'] );
                }
                
                $defaults = array(
                    'id' => $key . '-' . $this->parent->args['opt_name'],
                    'post_types' => array('page', 'post'),
                    'position' => 'normal',
                    'priority' => 'high',
                );

                $box = wp_parse_args( $box, $defaults );
                if ( isset( $box['post_types'] ) && !empty( $box['post_types'] ) ) {
                    foreach( $box['post_types'] as $posttype ) {
                        if ( isset( $box['title'] ) ) {
                            $title = $box['title'];
                        } else {
                            if ( isset( $box['sections'] ) && count( $box['sections'] ) == 1 && isset( $box['sections'][0]['fields'] ) && count($box['sections'][0]['fields']) == 1 && isset( $box['sections'][0]['fields'][0]['title'] ) ) {
                                // If only one field in this box
                                $title = $box['sections'][0]['fields'][0]['title'];
                            } else {
                                $title = ucfirst( $posttype ) . " " . __('Options', 'redux-framework');
                            }

                        }
                        $args = array(
                            'position' => $box['position'],
                            'sections' => $box['sections']
                        );
                        // Override the parent args on a metabox level
                        if ( !isset( $this->orig_args ) || empty( $this->orig_args ) ) {
                            $this->orig_args = $this->parent->args;
                        }
                        if (isset($box['args'])) {
                            $this->parent->args = wp_parse_args( $box['args'], $this->orig_args );
                        } else if ( $this->parent->args != $this->orig_args ) {
                            $this->parent->args = $this->orig_args;
                        }

                        add_meta_box( 'redux-'.$this->parent->args['opt_name'].'-metabox-'.$box['id'], $title, array( $this, 'generate_boxes' ), $posttype, $box['position'], $box['priority'], $args );
                    }
                }
            }
        } // add_meta_boxes()

        function _field_default($field_id) {
            if ( !isset( $this->parent->options_defaults ) ) {
                $this->parent->options_defaults = $this->parent->_default_values();
            }
            
            if (!isset($this->parent->options) || empty($this->parent->options)) {
                $this->parent->get_options();
            } else {
                $opt_values = $this->parent->options;
            }
            
            if ( isset($this->parent->options[$field_id['id']] ) && $this->parent->options[$field_id['id']] != $this->parent->options_defaults[$field_id['id']] ) {
                return $this->parent->options[$field_id['id']];
            } else {
                if( empty( $this->options_defaults ) ) {
                    $this->_default_values(); // fill cache
                }
                
                if ( !empty( $this->options_defaults ) ) {
                    $data = isset( $this->options_defaults[$field_id['id']] ) ? $this->options_defaults[$field_id['id']] : '';
                }
                
                if ( empty( $data ) && isset( $this->parent->options_defaults[$field_id['id']] ) ) {
                    $data = $this->parent->options_defaults[$field_id['id']];
                }
                
                return $data;
            }

        } // _field_default()

        // Function to get and cache the post meta.
        function get_meta( $id ) {
            if ( !isset( $this->meta[$id] ) ) {
                $this->meta[$id] = get_post_meta( $id, $this->parent->args['opt_name'], true );
            }
            return $this->meta[$id];
        }

        function generate_boxes($post, $metabox) {
            global $wpdb;
            $sections = $metabox['args']['sections'];

            wp_nonce_field( 'redux_metaboxes_meta_nonce', 'redux_metaboxes_meta_nonce' );

            wp_dequeue_script('json-view-js');

            $sidebar = true;
            if ( $metabox['args']['position'] == "side" || count($sections) == 1 || ( isset( $metabox['args']['sidebar'] ) && $metabox['args']['sidebar'] === false ) ) {
                $sidebar = false; // Show the section dividers or not
            }
            //$this->parent->options = wp_parse_args(get_post_meta( $post->ID, $this->parent->args['opt_name'], true ), $this->parent->options);
            //$data = $this->get_meta( $post->ID );

//print_r($data);
            ?>

            <div class="redux-container<?php echo ( $sidebar ) ? ' redux-has-sections' : ' redux-no-sections'; ?> redux-box-<?php echo $metabox['args']['position']; ?>">
                <div class="redux-notices">
                    <?php if ( $sidebar ) : ?>
                    <div class="saved_notice admin-notice notice-blue" style="display:none;"><strong><?php echo apply_filters( "redux-imported-text-{$this->parent->args['opt_name']}", __( 'Settings Imported!', 'redux-framework' ) ) ?></strong></div>
                    <div class="redux-save-warn notice-yellow"><strong><?php echo apply_filters( "redux-changed-text-{$this->parent->args['opt_name']}", __( 'Settings have changed, you should save them!', 'redux-framework' ) ) ?></strong></div>
                    <?php endif; ?>
                    <div class="redux-field-errors notice-red"><strong><span></span> <?php echo __( 'error(s) were found!', 'redux-framework' ) ?></strong></div>
                    <div class="redux-field-warnings notice-yellow"><strong><span></span> <?php echo __( 'warning(s) were found!', 'redux-framework' ) ?></strong></div>
                </div>
                <?php
                echo '<a href="javascript:void(0);" class="expand_options hide" style="display:none;">' . __( 'Expand', 'redux-framework' ) . '</a>';
                if ( $sidebar ) { ?>
                    <div class="redux-sidebar">
                        <ul class="redux-group-menu">
                            <?php
                            foreach( $sections as $sKey => $section ) {
                                echo $this->parent->section_menu($sKey, $section, '_box_'.$metabox['id'], $sections);
                            }
                            ?>
                        </ul>
                    </div>
                <?php } ?>

                <div class="redux-main">

                    <?php

                    $updateLocalize = false;

                    foreach($sections as $sKey => $section) :

                        if ( isset( $section['fields'] ) && !empty( $section['fields'] ) ) {

                            if (isset($section['args'])) {
                                $this->parent->args = wp_parse_args( $section['args'], $this->orig_args );
                            } else if ( $this->parent->args != $this->orig_args ) {
                                $this->parent->args = $this->orig_args;
                            }

                            $hide = $sidebar ? "" : ' display-group';
                            $section['class'] = isset( $section['class'] ) ? ' ' . $section['class'] : '';
                            echo '<div id="' . $sKey.'_box_'.$metabox['id'] . '_section_group' . '" class="redux-group-tab'.$section['class'].' redux_metabox_panel'.$hide.'">';
                            //}
                            if ( isset( $section['title'] ) && !empty( $section['title'] ) ) {
                                //if ( count( $sections ) == 1 && isset( $section[0]['fields'] ) && count( $section[0]['fields'] ) == 1 && isset( $section[0]['fields'][0]['title'] ) ) {
                                echo '<h3 class="redux-section-title">'.$section['title'].'</h3>';
                                //}
                            }

                            if ( isset( $section['desc'] ) && !empty( $section['desc'] ) ) {
                                echo '<div class="redux-section-desc">' . $section['desc'] . '</div>';
                            }
                            echo '<table class="form-table"><tbody>';
                            foreach( $section['fields'] as $fKey=> $field ) {
                                $field['name'] = $this->parent->args['opt_name'] . '[' . $field['id'] . ']';
                                echo '<tr valign="top">';



                                $th = $this->parent->get_header_html( $field );

                                if ( $sidebar ) {
                                    if ( !( isset( $metabox['args']['sections'] ) && count( $metabox['args']['sections'] ) == 1 && isset( $metabox['args']['sections'][0]['fields'] ) && count( $metabox['args']['sections'][0]['fields'] ) == 1 ) && isset( $field['title'] ) ) {
                                        echo '<th scope="row">';
                                        if (!empty($th)) {
                                            echo '<div class="redux_field_th">'.$th.'</div>';
                                        }
                                        echo '</th>';
                                        echo '<td>';
                                    }
                                } else {
                                    //<i style="float:right; " class="elusive el-icon-address-book"></i>  //hints for right metaboxes
                                    echo '<td>'.$th.'';
                                }

                                if ($field['type'] == "section" && $field['indent'] == "true") {
                                    $field['class'] = isset($field['class']) ? $field['class'] : '';
                                    $field['class'] .= "redux-section-indent-start";
                                    //$this->sections[$sk]['fields'][$k] = $field;
                                }
                                if (!isset($this->parent->options[$field['id']])) {
                                    $this->parent->options[$field['id']] = "";
                                }

                                $this->parent->_field_input($field, $this->parent->options[$field['id']]);
                                echo '</td></tr>';
                            }
                            echo '</tbody></table>';
                        }
                        //if ( $sidebar ) {
                        echo '</div>';
                        //}
                    endforeach; ?>
                </div>
                <div class="clear"></div>
            </div>
            <?php

        } // generate_boxes()

        /**
         * Save meta boxes
         *
         * Runs when a post is saved and does an action which the write panel save scripts can hook into.
         *
         * @access public
         * @param mixed $post_id
         * @param mixed $post
         * @return void
         */
        function meta_boxes_save( $post_id, $post ) {

            // Check if our nonce is set.
            if ( ! isset( $_POST['redux_metaboxes_meta_nonce'] ) ) {
                return $post_id;
            }
            
            $nonce = $_POST['redux_metaboxes_meta_nonce'];
            // Verify that the nonce is valid.
            // Validate fields (if needed)
            //$plugin_options = $this->_validate_values( $plugin_options, $this->options );

            if ( ! wp_verify_nonce( $nonce, 'redux_metaboxes_meta_nonce' ) ) {
                return $post_id;
            }

            // If this is an autosave, our form has not been submitted, so we don't want to do anything.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return $post_id;
            }

            // Check the user's permissions, even allowing custom capabilities
            $obj = get_post_type_object( $post->post_type );
            if ( ! current_user_can( $obj->cap->edit_post, $post_id ) ) {
                return $post_id;
            }

            $toSave = array();
            $toCompare = array();

            foreach($_POST[$this->parent->args['opt_name']] as $key => $value) {
                // Have to remove the escaping for array comparison
                if (is_array($value)) {
                    foreach($value as $k=>$v) {
                        if ( !is_array($v) ) {
                            $value[$k] = stripslashes($v);
                        }
                    }
                }

                $save = true;

                //parent_options
                if ( isset($this->options_defaults[$key]) && $value == $this->options_defaults[$key] ) {
                    $save = false;
                }

                if ($save && isset($this->parent_options[$key]) && $this->parent_options[$key] != $value) {
                    $save = false;
                }
                
                if ($save && !isset($this->parent_options[$key]) && isset($this->parent_defaults[$key]) && $this->parent_defaults[$key] == $value) {
                    //$save = false;
                }

                if ($save) {
                    $toSave[$key] = $value;
                    $toCompare[$key] = isset($this->parent->options[$key]) ? $this->parent->options[$key] : "";
                }
                
            }
//            print_r($toSave);
//            exit();
            
            $validate = $this->parent->_validate_values( $toSave, $toCompare, $this->parent->sections );

            // Validate fields (if needed)
            foreach($toSave as $key => $value) {
                if (isset($validate[$key]) && $validate[$key] != $toSave[$key]) {
                    if ( $validate[$key] == $this->parent->options[$key] ) {
                        unset( $toSave[$key] );
                    } else {
                        $toSave[$key] = $validate[$key];
                    }
                }
            }

            if( !empty( $this->parent->errors ) || !empty( $this->parent->warnings ) ) {
                $this->parent->transients['notices'] = (isset($this->parent->transients['notices']) && is_array($this->parent->transients['notices']) ) ? $this->parent->transients['notices'] : array();
                if (!isset($this->parent->transients['notices']['errors']) || $this->parent->transients['notices']['errors'] != $this->parent->errors ) {
                    $this->parent->transients['notices']['errors'] = $this->parent->errors;
                    $updateTransients = true;
                }
                if (!isset($this->parent->transients['notices']['warnings']) || $this->parent->transients['notices']['warnings'] != $this->parent->warnings ) {
                    $this->parent->transients['notices']['warnings'] = $this->parent->warnings;
                    $updateTransients = true;
                }
                if (isset($updateTransients)) {
                    $this->parent->transients['notices']['override'] = 1;
                    set_transient( $this->parent->args['opt_name'] . '-transients-metaboxes', $this->parent->transients );
                }
            }
            //exit();

            /* OK, its safe for us to save the data now. */
//            print_r($toSave);
//            exit();
            update_post_meta( $post_id, $this->parent->args['opt_name'], $toSave );
            //print_r($toSave);
            //exit();
        } // meta_boxes_save()


        /**
         * Some functions, like the term recount, require the visibility to be set prior. Lets save that here.
         *
         * @access public
         * @param mixed $post_id
         * @return void
         */
        function pre_post_update( $post_id ) {
            if ( isset( $_POST['_visibility'] ) ) {
                update_post_meta( $post_id, '_visibility', stripslashes( $_POST['_visibility'] ) );
            }
            if ( isset( $_POST['_stock_status'] ) ) {
                update_post_meta( $post_id, '_stock_status', stripslashes( $_POST['_stock_status'] ) );
            }
        } // pre_post_update()

        /**
         * Show any stored error messages.
         *
         * @access public
         * @return void
         */
        function meta_boxes_show_errors() {
            if ( isset( $this->notices['errors'] ) && !empty( $this->notices['errors'] ) ) {
                echo '<div id="redux_metaboxes_errors" class="error fade">';
                echo '<p><strong><span></span> ' . count( $this->notices['errors'] ) . ' ' . __( 'error(s) were found!', 'redux-framework' ) . '</strong></p>';
                echo '</div>';
            }
            if (isset( $this->notices['warnings'] ) && !empty( $this->notices['warnings'] ) ) {
                echo '<div id="redux_metaboxes_warnings" class="error fade" style="border-left-color: #E8E20C;">';
                echo '<p><strong><span></span> ' . count( $this->notices['warnings'] ) . ' ' . __( 'warnings(s) were found!', 'redux-framework' ) . '</strong></p>';
                echo '</div>';
            }
        } // meta_boxes_show_errors()

    } // class ReduxFramework_extension_metaboxes

} // if ( !class_exists( 'ReduxFramework_extension_metaboxes' ) )


if (!function_exists('redux_metaboxes_loop_start')) {
    function redux_metaboxes_loop_start( $opt_name, $the_post = array() ) {
        $redux = ReduxFrameworkInstances::get_instance( $opt_name );
        $metaboxes = $redux->extensions['metaboxes'];
        $metaboxes->_loop_start( $the_post );
    }
}

if (!function_exists('redux_metaboxes_loop_end')) {
    function redux_metaboxes_loop_end( $opt_name, $the_post = array() ) {
        $redux = ReduxFrameworkInstances::get_instance( $opt_name );
        $metaboxes = $redux->extensions['metaboxes'];

        $metaboxes->_loop_end();
    }
}