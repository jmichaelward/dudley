<?php

namespace ThreeFiveACF\Pattern\PhotoGallery;

use ThreeFiveACF\Abstracts\AbstractPatternWithRepeater;

/**
 * Class PhotoGallery
 * @package ThreeFiveACF\Pattern\PhotoGallery
 */
class PhotoGallery extends AbstractPatternWithRepeater
{
	/**
	 * PhotoGallery constructor.
	 */
	public function __construct() {
		if ( ! get_field( 'gallery_images' ) ) {
			return;
		}

		while ( has_sub_field( 'gallery_images' ) ) {
			$this->add_item( new PhotoGalleryItem() );
		}
	}
	/**
	 * @return array
	 */
	public function requirements() {
		return [
			count( $this->items ),
		];
	}
}