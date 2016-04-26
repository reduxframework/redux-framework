<?php

return;

echo "Welcome to the TIVWP Test Suite" . PHP_EOL;
echo "Version: 1.0" . PHP_EOL . PHP_EOL;

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = realpath( dirname( __FILE__ ) . '/../tmp-wp-dev/wordpress-tests' ) . '/';
}

echo 'Tests folder: ' . $_tests_dir . PHP_EOL . PHP_EOL;

// load some helpful functions
require_once $_tests_dir . 'functions.php';

// Activates this plugin in WordPress so it can be tested.
function _manually_load_plugin() {
	require dirname( __FILE__ ) . '/tivwp-dm.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

$GLOBALS['wp_tests_options'] = array(
	'active_plugins' => array( 'tivwp-dm/tivwp-dm.php' ),
);

// fire up test suite
require_once $_tests_dir . 'bootstrap.php';

// include unit test base class
//require_once dirname( __FILE__ ) . '/framework/class-wpseo-unit-test-case.php';
