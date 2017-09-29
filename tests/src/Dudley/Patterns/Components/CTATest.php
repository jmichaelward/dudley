<?php
/**
 * Class CTATest
 */
class CTATest extends \WP_Mock\Tools\TestCase {
	/**
	 * @var \Dudley\Patterns\Components\CTA
	 */
	public $cta;

	/**
	 * @var string
	 */
	public $text;

	/**
	 * @var string
	 */
	public $link;

	/**
	 *
	 */
	public function setUp() {
		\WP_Mock::setUp();
		$this->text = 'Click here.';
		$this->link = 'https://dudleyframework.com';

		$this->cta = new \Dudley\Patterns\Components\CTA( $this->text, $this->link );
	}

	/**
	 *
	 */
	public function tearDown() {
		\WP_Mock::tearDown();
	}

	/**
	 *
	 */
	public function test_cta_is_instanceof_dudley_cta_component_class() {
		$this->assertInstanceOf( \Dudley\Patterns\Components\CTA::class, $this->cta );
	}
}
