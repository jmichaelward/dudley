<?php

namespace Dudley\Scaffold;

/**
 * Class Single
 *
 * @package Dudley\Scaffold
 */
class Single {
	/**
	 * @var
	 */
	private $name;

	/**
	 * @var
	 */
	private $action;

	/**
	 * @var
	 */
	private $meta;

	/**
	 * Single constructor.
	 *
	 * @param $args
	 */
	public function __construct( $args ) {
		foreach ( $args as $key => $arg ) {
			if ( property_exists( $key, $this ) ) {
				$this->{$key} = $arg;
			}
		}

	}

	/**
	 * @return string
	 */
	public function get_name() {
		return ucwords( isset( $this->name ) ? $this->name : $this->action );
	}

	public function get_action() {
		return strtolower( $this->action );
	}

	public function get_meta() {
		return strtolower( $this->meta );
	}

	/**
	 * @return bool
	 */
	public function scaffold() {
		return true;
	}
}
