<?php
namespace Dudley\Patterns\Components;

use Dudley\Patterns\Abstracts\AbstractPattern;

/**
 * Class CTA
 * @package Dudley\Patterns\View\CTA
 */
class CTA extends AbstractPattern {
	/**
	 * @var
	 */
	private $text;

	/**
	 * @var
	 */
	private $link;

	/**
	 * AbstractCTA constructor.
	 *
	 * @param $text
	 * @param $link
	 */
	public function __construct( $text, $link ) {
		$this->text = $text;
		$this->link = $this->extract_url( $link );
	}

	/**
	 * Extract and return the URL from a post object or validate a string URL
	 * and return the link if it's valid.
	 *
	 * @param WP_Post|string $link
	 *
	 * @return string
	 */
	private function extract_url( $link ) {
		if ( is_a( $link, 'WP_Post' ) ) {
			return get_permalink( $link->ID );
		}

		if ( filter_var( $link, FILTER_VALIDATE_URL ) ) {
			return filter_var( $link, FILTER_SANITIZE_URL );
		}

		return '';
	}

	/**
	 * A call to action isn't much of a call to action without the CTA text
	 * and a location to take the user next!
	 *
	 * @return mixed
	 */
	public function requirements() {
		return [
			$this->text,
			$this->link,
		];
	}

	/**
	 * Print the URL for the call to action.
	 */
	public function url() {
		echo esc_url( $this->link );
	}

	/**
	 * Print the text for the call to action link.
	 */
	public function text() {
		esc_html_e( $this->text );
	}
}
