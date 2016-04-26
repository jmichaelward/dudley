<?php

namespace ThreeFiveACF\Pattern\Banner;

use ThreeFiveACF\Abstracts\AbstractPattern;

/**
 * Class Banner
 * @package ThreeFiveACF\Pattern\Banner
 */
class Banner extends AbstractPattern
{
	/**
	 * @var int
	 */
	private $page_id;

	/**
	 * @var bool|mixed|null|void
	 */
	private $type;

	/**
	 * @var BannerStandard|BannerHome
	 */
	protected $obj;

	/**
	 * Banner constructor.
	 */
	public function __construct() {
		$this->page_id = is_home() ? get_option( 'page_for_posts' ) : 0;
		$this->type    = get_field( 'banner_type' );
		$this->obj     = $this->create_banner();
	}

	/**
	 * @return BannerHome|BannerStandard
	 */
	private function create_banner() {
		$class = $this->banner_class();

		return new $class( $this->type, $this->page_id );
	}

	/**
	 *
	 */
	public function requirements() {
		return $this->obj ? $this->obj->requirements() : [ false ];
	}

	/**
	 * Get the class of banner to load.
	 *
	 * @return string
	 */
	private function banner_class() {
		$types = [
			'homepage' => 'BannerHome',
			'standard' => 'BannerStandard',
		];

		$type = array_key_exists( $this->type, $types ) ? $types[ $this->type ] : $types['standard'];

		return 'ThreeFiveACF\Pattern\Banner\\' . $type;
	}

	/**
	 * @return BannerHome|BannerStandard
	 */
	public function get_banner() {
		return $this->obj;
	}
}
