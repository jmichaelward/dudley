<?php

namespace Dudley\Patterns\Traits;

/**
 * Class HeadingTrait
 * @package Dudley\Patterns\Traits
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
	protected $heading_filter = 'dudley_acf_heading';

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
