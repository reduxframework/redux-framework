<?php

if ( !defined ( 'ABSPATH' ) ) {
	exit;
}

class Redux_Core_Required {
	public $parent      = null;

	public function __construct ($parent) {
		$this->parent = $parent;
		Redux_Functions::$_parent = $parent;

		/**
		 * action 'redux/page/{opt_name}/'
		 */
		do_action( "redux/page/{$parent->args['opt_name']}/" );

	}

}
