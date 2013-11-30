<?php

/**
 * Tests for the WP Slider Captcha plugin.
 *
 * @package wp-slider-captcha
 */
class WP_Test_WP_Slider_Captcha extends WP_UnitTestCase {

	/**
	 * Run a simple test to ensure that the tests are running
	 */
	function test_tests() {
		$this->assertTrue( true );
	}

	function test_init_hook_was_added() {
		$this->assertGreaterThan( 0,
			has_filter( 'init', 'wpsc_scripts' ) );
	}

	function test_script_is_registered() {
		$this->assertTrue(
			wp_script_is( 'wpsc-scripts', 'registered' ) );
	}

	function test_script_is_enqueued() {
		$this->assertTrue(
			wp_script_is( 'wpsc-scripts', 'enqueued' ) );
	}

	function test_style_is_enqueued() {
		$this->assertTrue(
			wp_style_is( 'wpsc-styles', 'enqueued' ) );
	}

	function test_options_menu_was_added() {
		// TODO: Check that the options page was added to admin.
		$hookname = get_plugin_page_hookname(
			plugin_basename( 'wpsc' ), 'options-general.php' );
		$this->markTestIncomplete();
	}

	function test_threshold_sanitize_less_than_one() {
		$this->assertEquals( 60,
			wpsc_threshold_sanitize( 0 ) );
	}

	function test_threshold_sanitize_more_than_100() {
		$this->assertEquals( 60,
			wpsc_threshold_sanitize( 101 ) );
	}

	function test_threshold_sanitize_non_number() {
		$this->assertEquals( 0,
			wpsc_threshold_sanitize( 'I am a string' ) );
	}

	function test_threshold_sanitize_valid_number() {
		$this->assertEquals( 40,
			wpsc_threshold_sanitize( 40 ) );
	}

}
