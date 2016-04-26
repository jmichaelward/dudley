<?php

namespace ThreeFiveACF\Pattern\FeaturedLinks;

use ThreeFiveACF\Abstracts\AbstractPatternWithRepeater;

/**
 * Class FeaturedLinks
 * @package ThreeFiveACF\Pattern\FeaturedLinks
 */
class FeaturedLinks extends AbstractPatternWithRepeater
{
	/**
	 * FeaturedLinks constructor.
	 */
	public function __construct() {
		if ( ! get_field( 'featured_links_show' ) || ! get_field( 'featured_links' ) ) {
			return;
		}

		while ( has_sub_field( 'featured_links' ) ) {
			$this->add_item( new FeaturedLinksItem() );
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