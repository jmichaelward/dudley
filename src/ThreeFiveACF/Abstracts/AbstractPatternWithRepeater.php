<?php

namespace ThreeFiveACF\Abstracts;

/**
 * Class AbstractPatternWithRepeater
 * @package ThreeFiveACF\Abstracts
 */
abstract class AbstractPatternWithRepeater extends AbstractPattern
{
	/**
	 * @var array $items
	 */
	protected $items = [ ];

	/**
	 * @param $item AbstractPattern
	 */
	protected function add_item( $item ) {
		if ( $item->has_required() ) {
			$this->items[] = $item;
		}
	}

	/**
	 * @return array
	 */
	public function get_items() {
		return $this->items;
	}
}
