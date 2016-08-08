<?php

namespace Tfive\ACF\Traits;

use Tfive\ACF\Abstracts\AbstractPattern;

/**
 * Trait RepeaterTrait
 * @package ThreeFiveACF\Traits
 */
trait RepeaterTrait {
	/**
	 * @var array $items
	 */
	protected $items = [ ];

	/**
	 * @param $item AbstractPattern
	 *
	 * @return bool|AbstractPattern
	 */
	protected function add_item( $item ) {
		if ( $item->has_required() ) {
			return $item;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function get_items() {
		return array_filter( $this->items );
	}
}
