<?php

namespace ThreeFiveACF\Pattern\FeaturedLinks;

use ThreeFiveACF\Abstracts\AbstractPattern;

/**
 * Class FeaturedLinksItem
 * @package ThreeFiveACF\Pattern\FeaturedLinks
 */
class FeaturedLinksItem extends AbstractPattern
{
	/**
	 * @var
	 */
	private $post;

	/**
	 * @var
	 */
	private $heading;

	/**
	 * @var
	 */
	private $img;

	/**
	 * FeaturedLinksItem constructor.
	 */
	public function __construct() {
		$this->post    = get_sub_field( 'featured_links_page' );
		$this->heading = get_sub_field( 'featured_links_heading' );
		$this->img     = get_sub_field( 'featured_links_image' );
	}

	/**
	 *
	 */
	public function requirements() {
		return [
			$this->post,
			$this->heading,
			$this->img,
		];
	}

	/**
	 *
	 */
	public function heading() {
		echo esc_html( $this->heading );
	}

	/**
	 *
	 */
	public function link_url() {
		echo esc_url( get_permalink( $this->post->ID ) );
	}

	/**
	 *
	 */
	public function image_url() {
		echo esc_url( $this->img['sizes']['large'] );
	}
}
