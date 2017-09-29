<?php

/**
 * Class DudleyTest
 */
class DudleyTest extends \WP_Mock\Tools\TestCase {
	/**
	 * @var \Dudley\Dudley
	 */
	public $dudley;

	/**
	 *
	 */
	public function setUp() {
		\WP_Mock::setUp();

		$this->dudley = new \Dudley\Dudley();
	}

	/**
	 *
	 */
	public function tearDown() {
		\WP_Mock::tearDown();
	}

	public function test_instanceof_dudley_plugin_class() {
		$this->assertInstanceOf( \Dudley\Dudley::class, $this->dudley );
	}
}
