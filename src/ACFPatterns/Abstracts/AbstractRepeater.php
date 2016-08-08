<?php

namespace Tfive\ACF\Abstracts;

/**
 * Class AbstractRepeater
 * @package ThreeFiveACF\Abstracts
 */
abstract class AbstractRepeater extends AbstractPattern
{
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
