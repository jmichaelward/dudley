<?php

namespace ThreeFiveACF\Abstracts;


/**
 * Class AbstractBanner
 * @package ThreeFiveACF\Abstracts
 */
abstract class AbstractBanner extends AbstractPattern
{
	/**
	 * @var
	 */
	protected $type;

	/**
	 * @var
	 */
	protected $image;

	/**
	 * @var
	 */
	protected $heading;

	/**
	 * @var
	 */
	protected $subheading;

	/**
	 * @var int $page_id
	 *
	 * @param   $type
	 */
	public function init( $type, $page_id ) {
		$this->type       = $type;
		$this->image      = get_field( 'banner_image', $page_id );
		$this->subheading = get_field( 'banner_subheading', $page_id );
	}

	/**
	 * @return mixed
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 *
	 */
	public function type() {
		echo esc_attr( $this->type );
	}

	/**
	 *
	 */
	public function requirements() {
		return [
			$this->image,
			is_array( $this->image ),
		];
	}

	/**
	 * Echo the class name for the banner container
	 */
	public function class_name() {
		echo esc_attr( 'banner--' . $this->get_type() );
	}

	/**
	 * @return mixed
	 */
	public function get_image() {
		return $this->image;
	}

	/**
	 * @param bool $size
	 */
	public function image_url( $size = false ) {
		$image = $this->get_image();

		echo esc_url( isset( $image['sizes'][ $size ] ) ? $image['sizes'][ $size ] : $image['sizes']['rudiments-banner'] );
	}

	/**
	 * @return mixed
	 */
	public function get_heading() {
		return $this->heading;
	}

	/**
	 *
	 */
	public function heading() {
		echo esc_html( $this->heading );
	}

	/**
	 * @return mixed
	 */
	public function get_subheading() {
		return $this->subheading;
	}

	/**
	 *
	 */
	public function subheading() {
		echo esc_html( $this->subheading );
	}
}
