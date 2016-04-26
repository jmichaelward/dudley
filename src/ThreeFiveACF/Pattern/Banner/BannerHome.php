<?php

namespace ThreeFiveACF\Pattern\Banner;

use ThreeFiveACF\Abstracts\AbstractBanner;

/**
 * Class BannerHome
 * @package ThreeFiveACF\Pattern\Banner
 */
class BannerHome extends AbstractBanner
{
	/**
	 * BannerHome constructor.
	 *
	 * @param $type
	 * @param $page_id
	 */
	public function __construct( $type, $page_id ) {
		parent::init( $type, $page_id );

		$this->heading    = get_field( 'banner_homepage_heading', $page_id );
		$this->subheading = get_field( 'banner_homepage_subheading', $page_id );
	}
}
