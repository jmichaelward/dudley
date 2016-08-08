<?php

namespace Tfive\ACF\Traits;

/**
 * Class HeadingTrait
 * @package ThreeFiveACF\Traits
 */
trait HeadingTrait
{
	/**
	 * @var
	 */
	protected $heading;

	/**
	 * @var string
	 */
	protected $heading_filter = 'tf_acf_heading';

	/**
	 * @return bool|mixed|null|void
	 */
	public function get_heading() {
		return $this->heading;
	}

	/**
	 *
	 */
	public function heading() {
		echo esc_html( apply_filters( $this->heading_filter, $this->heading ) );
	}
}
