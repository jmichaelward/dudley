<?php
/**
 * Class ACFTest
 */
class ACFTest extends \WP_Mock\Tools\TestCase {
	/**
	 * Instance of Dudley ACF class.
	 *
	 * @var \Dudley\MetaType\ACF
	 */
	public $acf;

	/**
	 * Instance of the Dudley plugin class.
	 *
	 * @var \Dudley\Dudley
	 */
	public $dudley;

	/**
	 *
	 */
	public function setUp() {
		\WP_Mock::setUp();

		$this->dudley = new \Dudley\Dudley();
		$this->acf = new \Dudley\MetaType\ACF( $this->dudley );
	}

	/**
	 *
	 */
	public function tearDown() {
		\WP_Mock::tearDown();
	}

	/**
	 * Test that our ACF object is an instance of Dudley\MetaType\ACF.
	 */
	public function test_acf_is_instanceof_dudley_acf_class() {
		$this->assertInstanceOf( \Dudley\MetaType\ACF::class, $this->acf );
	}
}
