<?php
namespace Dudley\Patterns\Abstracts;

/**
 * Core representation of a element or component that has its own data, but is part of a larger module, such as a single
 * slide within a carousel. If a class represents an object that has its own data, but the object doesn't have its own
 * view, extend this class. If it DOES have its own view, extend AbstractPattern instead.
 *
 * Class AbstractItem
 * @package Dudley\Patterns\Abstracts
 */
abstract class AbstractItem {
	/**
	 * A simple array of data values this item requires to render itself.
	 *
	 * @return array
	 */
	abstract public function requirements();

	/**
	 * Method to confirm that the item has all of the data it requires to render itself.
	 *
	 * @return bool
	 */
	public function has_required() {
		$req = $this->requirements();

		return count( $req ) === count( array_filter( $req ) );
	}
}
