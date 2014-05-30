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
 * @subpackage  Premium Extensions
 * @author      Dovy Paukstys (dovy)
 * @version 1.0.0
 * @since 3.1.7
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_extension_search' ) ) {

    class ReduxFramework_extension_search {

      static $version = "1.0.1";

      // Protected vars
      protected $parent;

      /**
       * Class Constructor. Defines the args for the extions class
       *
       * @since       1.0.0
       * @access      public
       * @param       array $parent Redux_Options class instance
       * @return      void
       */
      public function __construct( $parent ) {

        $this->parent = $parent;

        if (empty($this->extension_dir)) {
            $this->_extension_dir = trailingslashit(str_replace('\\', '/', dirname(__FILE__)));
            $this->_extension_url = site_url(str_replace(trailingslashit(str_replace('\\', '/', ABSPATH)), '', $this->_extension_dir));
        }    

        // Allow users to extend if they want
        do_action('redux/search/'.$parent->args['opt_name'].'/construct');

        global $pagenow;
        if ( isset( $_GET['page'] ) && $_GET['page'] && $_GET['page'] == $this->parent->args['page_slug'] )  {
          add_action( 'admin_enqueue_scripts', array( $this, '_enqueue' ), 0 ); 
        }

        add_action( "redux/metaboxes/{$this->parent->args['opt_name']}/enqueue", array( $this, '_enqueue' ), 10 ); 

      }

      function _enqueue() {
        
        /**
         * Redux search CSS
         * filter 'redux/page/{opt_name}/enqueue/redux-extension-search-css'
         * @param string  bundled stylesheet src
         */
        wp_enqueue_style(
            'redux-extension-search-css',
            apply_filters( "redux/search/{$this->parent->args['opt_name']}/enqueue/redux-extension-search-css", $this->_extension_url . 'extension_search.css' ),
            '',
            filemtime( $this->_extension_dir . 'extension_search.css' ), // todo - version should be based on above post-filter src
            'all'
        );
        /**
         * Redux search JS
         * filter 'redux/page/{opt_name}/enqueue/redux-extension-search-js
         * @param string  bundled javscript
         */
        wp_enqueue_script(
            'redux-extension-search-js',
            apply_filters( "redux/search/{$this->parent->args['opt_name']}/enqueue/redux-extension-search-js", $this->_extension_url . 'extension_search.js' ),
            array('jquery'),
            filemtime( $this->_extension_dir . 'extension_search.js' ), // todo - version should be based on above post-filter src
            'all'
        );   

        // Values used by the javascript
        wp_localize_script(
            'redux-extension-search-js', 
            'reduxsearch', 
            __('Search for field(s)', 'redux-framework')
        );

      }

    } // class

} // if
