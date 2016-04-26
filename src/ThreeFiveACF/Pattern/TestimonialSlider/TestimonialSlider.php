<?php

namespace ThreeFiveACF\Pattern\TestimonialSlider;

use ThreeFiveACF\Abstracts\AbstractPatternWithRepeater;

/**
 * Class TestimonialSlider
 * @package ThreeFiveACF\Pattern\TestimonialSlider
 */
class TestimonialSlider extends AbstractPatternWithRepeater
{
	/**
	 * @var
	 */
	private $img;

	/**
	 * @var
	 */
	private $autoplay;

	/**
	 * @var int
	 */
	private $speed;

	/**
	 * TestimonialSlider constructor.
	 */
	public function __construct()
	{
		if ( ! get_field( 'testimonial_slides' ) ) {
			return;
		}

		while ( has_sub_field( 'testimonial_slides' ) ) {
			$this->add_item( new TestimonialSliderItem() );
		}

		if ( $this->autoplay = get_field( 'testimonial_slides_autoplay' ) ) {
			$this->speed = (int) get_field( 'testimonial_slides_speed' );
		}
	}

	/**
	 * @return array
	 */
	public function requirements() {
		$req = [
			count( $this->items ),
			$this->img,
		];

		if ( $this->autoplay ) {
			$req[] = $this->speed;
		}

		return $req;
	}

	/**
	 *
	 */
	public function autoplay() {
		echo esc_attr( $this->autoplay ? 'true' : 'false' );
	}

	/**
	 *
	 */
	public function speed() {
		echo esc_attr( $this->speed * 1000 );
	}

	/**
	 *
	 */
	public function image_url() {
		echo esc_url( isset( $this->img['sizes']['rudiments-banner'] ) ? $this->img['sizes']['rudiments-banner'] : $this->img['url'] );
	}
}
