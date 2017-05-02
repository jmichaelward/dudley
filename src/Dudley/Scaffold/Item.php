<?php

namespace Dudley\Scaffold;

/**
 * Class Item
 *
 * @package Dudley\Scaffold
 */
class Item {
	/**
	 * @var string
	 */
	private $name;

	/**
	 * Item constructor.
	 *
	 * @param $args
	 */
	public function __construct( $args ) {
		$this->name = ucwords( isset( $args['name'] ) ? $args['name'] : $args['action'] );
	}

	/**
	 * @return bool
	 */
	public function scaffold() {
		return true;
	}
}
