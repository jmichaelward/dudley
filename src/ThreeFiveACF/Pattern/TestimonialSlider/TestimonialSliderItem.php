<?php

namespace ThreeFiveACF\Pattern\TestimonialSlider;

use ThreeFiveACF\Abstracts\AbstractPattern;

/**
 * Class TestimonialSliderItem
 * @package ThreeFiveACF\Pattern\TestimonialSlider
 */
class TestimonialSliderItem extends AbstractPattern
{
	/**
	 * @var bool|mixed|void
	 */
	private $data;

	/**
	 * TestimonialSliderItem constructor.
	 */
	public function __construct() {
		$this->data = get_sub_field( 'testimonial_slide' );
	}

	/**
	 * @return array
	 */
	public function requirements() {
		return [
			! empty( $this->data ),
		];
	}

	/**
	 *
	 */
	public function attribution() {
		echo esc_html( $this->data->post_title );
	}

	/**
	 *
	 */
	public function content() {
		echo wp_kses_post( '&ldquo;' . $this->data->post_content . '&rdquo;' );
	}
}
