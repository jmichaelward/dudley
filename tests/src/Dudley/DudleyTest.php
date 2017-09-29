<?php
/**
 * Class DudleyTest
 */
class DudleyTest extends \WP_Mock\Tools\TestCase {
	/**
	 * Instantiated object of the Dudley class.
	 *
	 * @var \Dudley\Dudley
	 */
	public $dudley;

	/**
	 * Setup test dependencies.
	 */
	public function setUp() {
		\WP_Mock::setUp();

		$this->dudley = new \Dudley\Dudley();
	}

	/**
	 * Test teardown.
	 */
	public function tearDown() {
		\WP_Mock::tearDown();
	}

	/**
	 * Test that the plugin class instance is of type Dudley\Dudley.
	 */
	public function test_instanceof_dudley_plugin_class() {
		$this->assertInstanceOf( \Dudley\Dudley::class, $this->dudley );
	}
}
