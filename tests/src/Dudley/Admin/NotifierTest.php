<?php
/**
 * Unit tests for the Dudley\Admin\Notifier class.
 *
 * @package Dudley
 */

/**
 * Class NotifierTest
 */
class NotifierTest extends WP_Mock\Tools\TestCase {
	/**
	 * Instance of the Admin Notifier class.
	 *
	 * @var \Dudley\Admin\Notifier
	 */
	public $notifier;

	/**
	 * Test objects setup.
	 */
	public function setUp() {
		\WP_Mock::setUp();

		$this->notifier = new \Dudley\Admin\Notifier();
	}

	/**
	 * Test objects teardown.
	 */
	public function tearDown() {
		\WP_Mock::tearDown();
	}

	/**
	 * Test for valid instance of admin notifier class object.
	 */
	public function test_instantiated_notifier_is_instanceof_admin_notifier() {
		$this->assertInstanceOf( \Dudley\Admin\Notifier::class, $this->notifier );
	}

	/**
	 * Test that print_error_notice returns a WordPress admin notice with received message.
	 */
	public function test_print_error_notice() {
		$message         = 'This is an error message.';
		$expected_result = '<div class="notice notice-error"><p>' . $message . '</p></div>';

		ob_start();

		$this->notifier->print_error_notice( $message );

		$this->assertEquals( $expected_result, ob_get_clean() );
	}
}
