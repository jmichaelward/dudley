<?php

namespace Tfive\ACF\Traits;

use Tfive\ACF\Abstracts\AbstractPattern;

/**
 * Trait RepeaterTrait
 * @package ThreeFiveACF\Traits
 */
trait RepeaterTrait {
	/**
	 * Returns the name of the class property on which the other methods will operate.
	 *
	 * @param $class_prop_name string Name of the class property.
	 *
	 * @return string
	 */
	private function property_name( $class_prop_name ) {
		return ! empty( $class_prop_name ) ? $class_prop_name : 'items';
	}

	/**
	 * @param $item AbstractPattern
	 * @param $list string Name of the class property to which the item will be added. Defaults to items.
	 *
	 * @return bool|AbstractPattern
	 */
	public function add_item( $item, $list = '' ) {
		if ( $item->has_required() ) {
			array_push( $this->{$this->property_name( $list )}, $item );
		}
	}

	/**
	 * @param $list string Name of the class property to return. Defaults to items.
	 * @return array
	 */
	public function get_items( $list = '' ) {
		return $this->{$this->property_name( $list )};
	}
}
