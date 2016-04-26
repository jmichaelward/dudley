<?php

namespace ThreeFiveACF\Pattern\Banner;

use ThreeFiveACF\Abstracts\AbstractBanner;

/**
 * Class BannerStandard
 * @package ThreeFiveACF\Pattern\Banner
 */
class BannerStandard extends AbstractBanner
{
	/**
	 * BannerStandard constructor.
	 *
	 * @param string $type
	 * @param int $page_id
	 */
	public function __construct( $type, $page_id ) {
		parent::init( 'standard', $page_id );

		$this->heading = get_the_title( $page_id );
	}

	/**
	 * @return bool
	 */
	public function has_text_content() {
		return $this->heading;
	}
}
