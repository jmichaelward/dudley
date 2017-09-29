<?php
/**
 * Class ConfigTest
 */
class ConfigTest extends \WP_Mock\Tools\TestCase {
	/**
	 * Instance of the Config class.
	 *
	 * @var \Dudley\Config
	 */
	public $config;

	/**
	 * Instance of the Dudley plugin class.
	 *
	 * @var \Dudley\Dudley
	 */
	public $dudley;

	protected $preserveGlobalState = false;

	/**
	 * Set up test dependencies.
	 */
	public function setUp() {
		\WP_Mock::setUp();

		$this->dudley = new Dudley\Dudley();
		$this->config = new \Dudley\Config( $this->dudley );
	}

	/**
	 * Tear down mocks.
	 */
	public function tearDown() {
		\WP_Mock::tearDown();
	}

	/**
	 * Make sure we're using an instance of the Dudley Config class.
	 */
	public function test_instance_of_dudley_config() {
		$this->assertInstanceOf( \Dudley\Config::class, $this->config );
	}

	/**
	 * Test that we get a Dudley ACF object back if the DUDLEY_META_TYPE is set to 'acf'.
	 */
	public function test_default_meta_type_is_acf() {
		$reflection = new ReflectionMethod( \Dudley\Config::class, 'get_meta_type' );
		$reflection->setAccessible( true );

		$this->assertInstanceOf( \Dudley\MetaType\ACF::class, $reflection->invoke( $this->config ) );
	}

	/**
	 * Test that get_meta_type returns false if an unsupported meta type is assigned to DUDLEY_META_TYPE.
	 */
	public function test_nonsupported_meta_type_returns_false() {
		define( 'DUDLEY_META_TYPE', 'unsupportedmetatype' );

		$reflection = new ReflectionMethod( \Dudley\Config::class, 'get_meta_type' );
		$reflection->setAccessible( true );

		$this->assertFalse( $reflection->invoke( $this->config ) );
	}
}
