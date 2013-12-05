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
 * @subpackage  Extensions
 * @author      Dovy Paukstys (dovy)
 * @version 1.0.0
 * @since 3.1.1
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_extension_envato' ) ) {

    /**
     * Redux Framework Envato extension class. Does full API calls to check if they have a valid purchase code.
     *
     * @version 1.0.0
     * @since 3.1.1
     */
    class ReduxFramework_extension_envato {

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
      	/*
      	if ( !class_exists( "Envato_marketplaces" ) ) {
      		require_once( dirname(__FILE__) . '/Envato_marketplaces.php' );
      	}

      	$this->parent = $parent;
      	$envato_username = "ThemeFusion";
      	$envato_apikey = "phi4chu65znat8h645tte78k4qqdpwhe";
      	$license_to_check = "124e65e6-17cc-432c-ad8a-7d5d8c0b9e83";
      	$response = wp_remote_get( 'http://marketplace.envato.com/api/edge/' . $envato_username . '/' . $envato_apikey . '/verify-purchase:' . $license_to_check . '.json' );
      	$response = json_decode($response['body'], true);
      	$response = $response['verify-purchase'];
      	print_r($response);
      	if ( isset( $response['buyer'] ) ) {
      		echo 'bought';	
      		$download = wp_remote_get( 'http://marketplace.envato.com/api/edge/' . $envato_username . '/' . $envato_apikey . '/download-purchase:' . $license_to_check . '.json' );
      		echo 'http://marketplace.envato.com/api/edge/' . $envato_username . '/' . $envato_apikey . '/download-purchase:' . $license_to_check . '.json';
      		$download = json_decode($download);
      		print_r($download);
      	} 
      	
      	
      	/*
      	if ( !isset( $this->parent->envato ) ) {
      		$this->parent->envato = new Envato_marketplaces();
      	}
      	
      	$this->parent->envato->set_api_key('phi4chu65znat8h645tte78k4qqdpwhe');
      	$verify = $this->parent->envato->verify_purchase('ThemeFusion', '124e65e6-17cc-432c-ad8a-7d5d8c0b9e83');
      	if ( isset($verify->buyer) ) echo 'bought';
      	else echo "Not bought";
*/
      	
		// Allow users to extend if they want
		do_action('redux/extensions/'.$parent->args['opt_name'].'/envato');

      }




    } // class

} // if
