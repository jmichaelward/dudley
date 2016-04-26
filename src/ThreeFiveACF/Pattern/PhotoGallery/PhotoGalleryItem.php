<?php

namespace ThreeFiveACF\Pattern\PhotoGallery;

use ThreeFiveACF\Abstracts\AbstractPattern;

/**
 * Class PhotoGalleryItem
 * @package ThreeFiveACF\Pattern\PhotoGallery
 */
class PhotoGalleryItem extends AbstractPattern
{
	/**
	 * @var
	 */
	private $img;

	/**
	 * PhotoGalleryItem constructor.
	 */
	public function __construct() {
		$this->img = get_sub_field( 'gallery_images_image' );
	}

	/**
	 * @return mixed
	 */
	public function requirements() {
		return [
			$this->img,
			is_array( $this->img ),
		];
	}

	/**
	 *
	 */
	public function image_url_thumb() {
		echo esc_url( $this->img['sizes']['large'] );
	}

	/**
	 *
	 */
	public function image_url_large() {
		echo esc_url( isset( $this->img['sizes']['rudiments-banner'] ) ? $this->img['sizes']['rudiments-banner'] : $this->img['url'] );
	}
}